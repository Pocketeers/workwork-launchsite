<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Braintree_ClientToken;
use \Braintree_Transaction;

use Image;
use Services_Twilio;

use Carbon\Carbon;

use App\User;
use App\Advert;
use App\Application;

use App\Contracts\Search;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'terms']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        // get authorized user's data
        $user = $request->user();

        $ftu_level = $user->ftu_level;

        // get user's type of role
        $haveType = $user->type;

        // get user's avatar
        $avatar = $user->avatar;

        // check if user has a role type, if not it redirect the user
        if(!$haveType || (!$user->employer && !$user->jobSeeker))
        {
            // check which type does the user have
            if($haveType  === "employer")
            {
                // redirect to create company profile page
                return redirect('/company/create');

            }elseif($haveType === "job_seeker"){

                // redirect to create profile page
                return redirect('/profile/create');
            }else{
                // redirect to choose type page
                return redirect('/choose');
            }
        }

        // check if user already have an avatar
        if($avatar != "" || $avatar != null)
        {
            // user's avatar photo
            $photo = $avatar;
        }else{
            // default avatar photo
            $photo = "/images/defaults/default.jpg";
        }

        // check if user profile record exist
        if($user->employer)
        {
            // get user's profile related info
            $role = $user->employer;
            $requests = $role->applications->where('status', 'PENDING');
            $requestTotal = count($requests);

            switch ($requestTotal)
            {
                case ($requestTotal > 0):
                    $noticeInfos = $requests;
                    $message = "You have a request from";
                    break;

                default:
                    $noticeInfos = null;
                    $message = "";
            }

            switch ($ftu_level)
            {
                case 1:
                    $message1 = "You have not yet created your first advert -";
                    $message2 = "Continue";
                    $link = '/adverts/create';
                    break;

                case 2:
                    $advert = $role->adverts->first();
                    $advertID = $advert->id;
                    $advertJobTitle = $advert->job_title;
                    if($advert->ready_to_publish != 0)
                    {
                        $message1 = "You have not yet publish your advert -";
                        $message2 = "Continue";
                        $link = '/choose/plan/'.$advertID;
                    }else{
                        $message1 = "You have not yet finish filling up your advert form -";
                        $message2 = "Continue";
                        $link = '/adverts/'.$advertID.'/'.$advertJobTitle.'/edit';
                    }
                    break;

                case 3:
                    $advert = $role->adverts->first();
                    $advertID = $advert->id;
                    $advertJobTitle = $advert->job_title;
                    $message1 = "You have not yet publish your advert! -";
                    $message2 = "Continue";
                    $link = '/adverts/'.$advertID.'/'.$advertJobTitle;
                    break;

                default:
                    $message1 = "";
                    $message2 = "";
                    $link = "";
            }

        }elseif($user->jobSeeker){
            // get user's profile related info
            $role = $user->jobSeeker;
            $responses = $role->applications->where('responded', 1)->where('viewed', 0);
            $responseTotal = count($responses);
            
            switch ($responseTotal)
            {
                case ($responseTotal > 0):
                    $noticeInfos = $responses;
                    $message = "You have a response from your request for";
                    break;

                default:
                    $noticeInfos = null;
                    $message = "";
            }

            switch ($ftu_level)
            {
                case 1:
                    $message1 = "You have not yet select your preferred job category -";
                    $message2 = "Continue";
                    $link = '/preferred-category';
                    break;

                default:
                    $message1 = "";
                    $message2 = "";
                    $link = "";
            }
        }

        // return user to home dashboard
        return view('dashboard', compact('user','photo','noticeInfos','message','message1','message2','link','site'));
    }


    /**
     * Show the upload avatar page.
     *
     */
    public function avatar(Request $request)
    {
        // store user info in variable
        $user = $request->user();

        $avatar = $user->avatar;

        if($avatar != "" || $avatar != null){

            $fileExist = true;

            $photo = $avatar;

        }else{

            $fileExist = false;

            $photo = "/images/defaults/default.jpg";
        }

        // display the upload page
        return view('auth.account.avatar', compact('user','photo','fileExist'));
    }


    /**
     * Store the uploaded image.
     *
     */
    protected function uploadAvatar(Request $request, Search $search)
    {   
        // store user's info in variable
        $user = $request->user();

        // validation function
        $this->validate($request, [

            // allow only this type of image
            'photo' => 'required|mimes:jpg,jpeg,png,bmp' 
        ]);

        // store the uploaded file in a variable and fetch by paramName
        $file = $request->file('photo');

        // set the timestamp to the file name
        $name = time(). '-' .$file->getClientOriginalName();

        // provide a path
        $path = "images/profile_images/avatars/avatars";

        // provide path URl for Database
        $pathURL = "/".$path."/".$name;

        // use intervention facade to resize image then move/upload to dir
        Image::make($file)->fit(200, 200)->save($path."/".$name);

        // update user's file path url then save to database
        $user->update([ 'avatar' => $pathURL ]);

        $user->save();

        // run process if user is an employer
        if($user->employer)
        {
            // determine which rows to fetch
            $adverts = Advert::where('employer_id', '=',$user->employer->id);

            // fetch the rows
            $rows = $adverts->get();

            //fetch data from config.php
            $config = config('services.algolia');

            // provide index
            $index = $config['index'];

            // select algolia index/indice name
            $indexFromAlgolia = $search->index($index);

            $liveAds = $adverts->where('published', 1)->get();

            // loop algolia object update for each row
            foreach($liveAds as $liveAd)
            {
                // update algolia existing object. Determine which by row id
                $object = $indexFromAlgolia->partialUpdateObject([
                    'avatar' => $pathURL,
                    'objectID' => $liveAd->id,
                ]);
            }

            //MASS UPDATE existing advert's "avatar" column to database
            $adverts->update([ 'avatar' => $pathURL ]);
        }
}



    public function remove(Request $request, $avatar_id, Search $search)
    {
        // fetch user's info
        $user = $request->user();

        // find photo's row data using the "avatar_id"
        $thisPhoto = User::findOrFail($avatar_id);

        //fetch data from config.php
        $config = config('services.algolia');

        // provide index
        $index = $config['index'];

        //check IF job advert is own by user
        if(!$thisPhoto->avatarBy($user))
        {
            return $this->unauthorized($request);
        }

        // check IF avatar path url exist
        if($thisPhoto->avatar != "" || $thisPhoto->avatar != null){

            $exist = true;

        }else{

            $exist = false;
        }

        // run process IF photo path url exist/is true
        if($exist === true){

            //UPDATE advert's "avatar" column to null then SAVE changes to database
            $user->update([ 'avatar' => null ]);

            $user->save();

            // run process IF user is an employer
            if($user->employer)
            {
                // determine which rows to fetch
                $adverts = Advert::where('employer_id', '=',$user->employer->id);

                // fetch the rows
                $rows = $adverts->get();

                // select algolia index/indice name
                $indexFromAlgolia = $search->index($index);

                // provide path URl for Database
                $pathURL = "/images/defaults/default.jpg";

                $liveAds = $adverts->where('published', 1)->get();

                // loop algolia object update for each row
                foreach($liveAds as $liveAd)
                {
                    // update algolia existing object. Determine which by row id
                    $object = $indexFromAlgolia->partialUpdateObject([
                        'avatar' => $pathURL,
                        'objectID' => $liveAd->id,
                    ]);
                }

                //MASS UPDATE existing advert's "avatar" column to database
                $adverts->update([ 'avatar' => $pathURL ]);
            }

            // flash message
            flash('Your photo has been successfully removed', 'success');

            return redirect()->back();

        }else{

            flash('Error, please try again', 'error');

            return redirect()->back();
        }
    }



    public function terms()
    {
        return view('pages.terms');
    }



    public function contact(Request $request)
    {
        $user = $request->user();
        $verified = $user->contact_verified;
        $contact = $user->contact;

        if($verified === 1)
        {
            return redirect('/dashboard');
        }

        return view('auth.verifications.contact_verification', compact('contact'));
    }



    public function sendContactToken(Request $request)
    {
        $contactCode = mt_rand(11111,99999);

        $user = $request->user();
        $user->contact_verification_code = $contactCode;
        $user->save();

        $contact = $user->contact;

        $config = config('services.twilio');

            // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
            $AccountSid = $config['acc_id'];
            $AuthToken = $config['auth_token'];

                // Step 3: instantiate a new Twilio Rest Client
                $client = new Services_Twilio($AccountSid, $AuthToken);

                // Step 4: make an array of people we know, to send them a message. 
                // Feel free to change/add your own phone number and name here.
                $people = array(
                    //"+60176613069" => $user->name,
                    "+6".$contact => $user->contact,
                    //"+14158675310" => "Boots",
                    //"+14158675311" => "Virgil",
                );
                
                // Step 5: Loop over all our friends. $number is a phone number above, and 
                // $name is the name next to it
                foreach ($people as $number => $name) {

                    $sms = $client->account->messages->sendMessage(

                        // Step 6: Change the 'From' number below to be a valid Twilio number 
                        // that you've purchased, or the (deprecated) Sandbox number
                        "+12602184571", 

                        // the number we are sending to - Any phone number
                        $number,

                        // the sms body
                        "This is your verification code for WorkWork: $contactCode"
                    );
                    // Display a confirmation message on the screen
                    //echo "Sent message to $name";
                }
    }



    public function verifyContact(Request $request)
    {
        $this->validate($request, [
                'code' => 'required|min:5|max:5',
            ]);

        $code = $request->code;
        $user = $request->user();
        $userCode = $user->contact_verification_code;

        if($code === $userCode)
        {
            $user->contact_verified = 1;
            $user->contact_verification_code = null;
            $user->save();

            flash('Your contact has been verified','success');

            return redirect('/dashboard');
        }

        flash('Your code did not match!','error');
        return redirect()->back();  
    }



    public function account(Request $request)
    {
        $user = $request->user();
        return view('auth.account.account', compact('user'));
    }



    public function accountEdit(Request $request)
    {
        $user = $request->user();
        return view('auth.account.account_edit', compact('user'));
    }



    public function accountUpdate(Request $request)
    {
        $user = $request->user();
        $user->update([
            'name' => $request->name,
            'contact' => $request->contact,
        ]);

        flash('Your account detail has been updated', 'success');
        return redirect('dashboard');
    }



    protected function unauthorized(Request $request)
    {
        if($request->ajax())
        {
            return response(['message' => 'No!'], 403);
        }

        abort(403, 'Unauthorized action.');
    }
    


    /**
    public function time()
    {
        $todaysDate = Carbon::now();
        $endDate = Carbon::createFromDate(2016, 9, 2);
        $expDate =  $todaysDate->diffInDays($endDate, false);

        if($expDate < 0){

            echo "expiration has passed:";
            echo " ";
            echo $expDate;

        }else{

            echo "expiration has not passed:";
            echo " ";
            echo $expDate;
        }
    }
    */
}
