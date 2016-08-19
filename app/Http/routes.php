<?php

// Route::get('/', 'PagesController@home');

// Route::get('/launch', 'PagesController@launch');

Route::auth();

Route::get('/dashboard', 'HomeController@index');

Route::get('/avatar', 'HomeController@avatar');

Route::post('/avatar/upload', 'HomeController@uploadAvatar');

Route::delete('/avatar/{avatar_id}', 'HomeController@remove');

/**
 * Social routes
 */
Route::get('/redirect', 'SocialAuthController@redirect');

Route::get('/callback', 'SocialAuthController@callback');

/**
 * Assign Roles routes
 */
Route::get('/choose', 'TypeController@choose');

Route::post('/set', 'TypeController@assignType');

/**
 * Company Profile routes
 */
Route::get('/company/create', 'CompanyProfileController@create');

Route::post('/company/store', 'CompanyProfileController@store');

Route::get('/company/{id}/{business_name}', 'CompanyProfileController@profile')->name('company');

Route::get('/company/edit', 'CompanyProfileController@edit');

Route::post('/company/update', 'CompanyProfileController@update');

Route::get('/logo', 'CompanyProfileController@logo');

Route::post('/upload/logo', 'CompanyProfileController@uploadLogo');

Route::delete('/logo/{logo_id}', 'CompanyProfileController@destroy');

Route::get('/company/{id}/{business_name}/review', 'CompanyProfileController@companyReview');

Route::get('/my/adverts', 'CompanyProfileController@myAdvert');

Route::get('/advert/{id}/job/requests', 'CompanyProfileController@jobRequest');

Route::get('/advert/{id}/job/requests/rejected', 'CompanyProfileController@rejected');

Route::get('/advert/{id}/job/requests/reviewing', 'CompanyProfileController@inReview');

Route::get('/advert/{id}/job/requests/{role_id}', 'CompanyProfileController@appliedProfile');

Route::post('/advert/job/requests/{id}/response', 'CompanyProfileController@response');

Route::post('/profile/{id}/rate', 'CompanyProfileController@rate');

/**
 * Job Seeker Profile routes
 */
Route::get('/profile/create', 'JobSeekerProfileController@create');

Route::post('/profile/store', 'JobSeekerProfileController@store');

Route::get('/profile/{id}', 'JobSeekerProfileController@profileInfo')->name('jobSeeker');

Route::get('/profile/info/edit', 'JobSeekerProfileController@edit');

Route::post('/profile/edit/update', 'JobSeekerProfileController@update');

Route::get('/profile/{id}/review', 'JobSeekerProfileController@jobSeekerReview');

Route::post('/company/{id}/{business_name}/rate', 'JobSeekerProfileController@rate');

/**
 * Adverts routes
 */
Route::resource('/', 'AdvertsController');

Route::get('/home', 'AdvertsController@index');

Route::get('/adverts/create', 'AdvertsController@create');

Route::get('/adverts/{id}/{job_title}', 'AdvertsController@show')->name('show');

Route::post('adverts/preview', 'AdvertsController@preview');

Route::get('adverts/{id}/{job_title}/edit', 'AdvertsController@edit');

Route::post('adverts/{id}/{job_title}/edit/update', 'AdvertsController@update');

/**
* Job Seeker Apply Advert routes
*/
Route::get('/adverts/{id}/{job_title}/apply', 'ApplyController@apply');

Route::post('/adverts/{id}/{job_title}/apply/add', 'ApplyController@storeApply');

/**
* Subcription routes
*/
Route::get('/plans', 'SubscribeController@plans');

Route::get('/subscribe', 'SubscribeController@subscribe');

Route::post('/checkout', 'SubscribeController@checkout');

Route::get('/invoices', 'SubscribeController@invoices');

Route::get('/invoices/download/{invoiceId}', 'SubscribeController@download');

/**
* Webhook routes
*/
Route::post('braintree/webhook', '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook');

/**
* Payment routes
*/
Route::get('/status', 'StatusController@status');

Route::get('/cancel', 'StatusController@cancel');

Route::get('/resume', 'StatusController@resume');


