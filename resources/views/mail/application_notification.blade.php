You got a response from your JOB APPLICATION for <b>{{ $application->advert->job_title }}</b> <br><br>
<b>Status:</b> {{ $application->status }} <br><br>
<b>Message:</b> {{ $application->employer_comment }} <br><br>

For more info: <a href="{{ $websiteURL }}my/applications/{{ $application->id }}">click here</a>
<br><br>