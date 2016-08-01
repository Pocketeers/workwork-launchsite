@extends('layouts.app')

@section('content')
<h1>Applying for a Job as </h1>

<h2>"{{ $advert->job_title }}" ?</h2>

<hr>

<div class="row">
	<form method="post" action="/adverts/{{ $advert->id }}/{{ $advert->job_title }}/apply/add" enctype="multipart/form-data" class="col-md-6">
		@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		
		@include('adverts.application_form')

	</form>
</div>
@stop