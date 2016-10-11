@extends('layouts.app')

@section('content')

<div class="flash">
	@include('messages.flash')
</div>

@if($user->ftu_level < 4)
	@include('messages.ftu_level')
@else
	@include('messages.advert_level')
@endif

<!-- <body onbeforeunload="return popUp()"> -->

<h1 class="ftu-intro">@lang('ftu.new_ad')</h1>

<div class="ftu-panel panel-ww-600 panel panel-default center-block">
	<div class="ftu-arrow"></div>
    <div class="panel-heading panel-heading-ww">@lang('forms.ad_title')</div>
    <div class="panel-body">
	    <form method="post" action="/" enctype="multipart/form-data" id="myForm">
			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif

			@include('adverts.form')
		</form>
    </div>
</div>

@stop