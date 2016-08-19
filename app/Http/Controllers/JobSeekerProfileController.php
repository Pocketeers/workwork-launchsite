<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;

use App\Advert;
use App\Job_Seeker;
use App\Job_Seeker_Rating;
use App\Application;
use App\Employer_Rating;


use App\Http\Requests;

class JobSeekerProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('jobSeeker', ['except' => ['profileInfo','jobSeekerReview']]);
    }



    public function create(Request $request)
    {
        $user = $request->user();

        return view('profiles.profile_info.profile_create', compact('user'));
    }



    public function store(Request $request)
    {
        $user = $request->user();

        $user->update([
            // update user info
            'name' => $request->name,
            'contact' => $request->contact,
        ]);

        $user->save();


        // create a new user_id and fields and store it in jobseekers table
        $jobSeeker = $user->jobSeeker()->create([

            // 'column' => request->'field'
            'age' => $request->age,
            'location' => $request->location,
            'street' => $request->street,
            'city' => $request->city,
            'zip' => $request->zip,
            'state' => $request->state,
            'country' => $request->country, 
        ]);

        // set user_id in the row created in the Job_Seeker model using associate method
        $jobSeeker->user()->associate($user);

        // save user's job seeker profile info
        $jobSeeker->save();

        // assign role "job_seeker" permissions with "assignRole" method from hasRoles trait
        $user->assignRole('job_seeker');

        // check if user storing procedure is a success
        if($user){

            // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
            Mail::send('mail.welcomeJobSeeker', compact('user'), function ($m) use ($user) {

                // set email sender stmp url and sender name
                $m->from('postmaster@sandbox12f6a7e0d1a646e49368234197d98ca4.mailgun.org', 'WorkWork');

                // set email recepient and subject
                $m->to('farid@pocketpixel.com', $user->name)->subject('Welcome to WorkWork!');
            });
        }

        // set flash attribute and key. example --> flash('success message', 'flash_message_level')
        flash('Your profile has been created. Welcome to WorkWork, Job Seeker!', 'success');

        // redirect to home
        return redirect('/dashboard');
    }



    public function profileInfo(Request $request, $id)
    {
    	$profileInfo = Job_Seeker::find($id);

        $ratings = $profileInfo->ownRating->count();

        $avatar = $profileInfo->user->avatar;

        $user = $request->user();

        if($avatar != "" || $avatar != null){

            $photo = $avatar;

        }else{

            $photo = "/images/profile_images/defaults/default.jpg";
        }

        if($ratings === 0)
        {
            $average = 0;

        }else{

            $average = $profileInfo->ownRating->avg('rating');
        }

        $jobSeeker = $user->jobSeeker;

        if($jobSeeker)
        {
            if($jobSeeker->id === $profileInfo->id)
            {
                $authorize = true;

            }else{

                $authorize = false;
            }
        }

    	return view('profiles.profile_info.profile', compact('photo','profileInfo','authorize','average','ratings'));
    }



    public function edit(Request $request)
    {
    	$user = $request->user();

    	$jobSeeker = $user->jobSeeker;

    	return view('profiles.profile_info.profile_edit', compact('user', 'jobSeeker'));
    }



    public function update(Request $request)
    {
    	$user = $request->user();

    	$jobSeeker = $user->jobSeeker;

    	$user->update([
    		'name' => $request->name,
    		'contact' => $request->contact,
    	]);

    	$jobSeeker->update([

                'age' => $request->age,
                'location' => $request->location,
				'street' => $request->street,
				'city' => $request->city,
				'zip' => $request->zip,
				'state' => $request->state,
				'country' => $request->country,
    	]);

    	$jobSeeker->save();

    	$user->save();

    	// set flash attribute and key. example --> flash('success message', 'flash_message_level')
		flash('Your profile has been updated', 'success');

    	return redirect()->route('jobSeeker', [$jobSeeker->id]);
    }



    public function rate(Request $request, $id, $business_name)
    {
        $user = $request->user();

        $this->validate($request, [

        'star' => 'required',
        'comment' => 'required|max:255',

        ]);

        $jobSeeker = $user->jobSeeker;

        $rating = new Employer_Rating;

        $rating->rating = $request->star;

        $rating->comment = $request->comment;

        $rating->postedBy = $user->name;

        $rating->jobSeeker()->associate($jobSeeker->id);

        $rating->employer()->associate($id);

        $rating->save();

        flash('Thank you for your feedback', 'success');

        return redirect()->back();
    }



    public function jobSeekerReview($id)
    {
        $jobSeeker = Job_Seeker::find($id);

        $userReviews = $jobSeeker->ownRating()->paginate(5);

        return view('profiles.profile_info.profile_reviews', compact('userReviews'));
    }
}