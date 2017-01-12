@extends('layouts.app')

@section('meta_info')
	<meta property="og:title" content="WorkWork - portal pencarian kerja part-time" />
    <meta property="og:description" content="Jika anda mencari kerja part-time atau sedang mencari pekerja part-time, di sinilah tempatnya! #kerjakerjakerja" />
    <meta property="og:url" content="http://www.workwork.my/" />
    <meta property="og:image" content="http://www.workwork.my/images/fb-image.jpg" />
    <meta property="og:image:type" content="image/jpeg" />
@stop

@section('content')

<div class="flash">
	@include('messages.flash')
</div>

<section id="intro">
	<div class="ww-banner">
	</div>
	<!-- <article>
		<p class="lead">
		@lang('messages.welcome')
		</p>
	</article> -->
</section>

<div class="row">
	<div class="col-md-3 hidden-xs">
		<div id="filter-tool" class="panel panel-default">
			<div class="panel-body">
				<div class="title">@lang('adverts.filter')</div>
				<div id="sort-by-container" class="panel-section"></div>
				<div id="categories" class="panel-section"></div>
				<div id="clear-all" class="panel-bottom-action"></div>
			</div>
		</div>
	</div>


    <div class="col-md-6" id="adverts" >
    	<div class="form-group">

		    <input type="text" class="form-control" id="search-box" />

	    </div>
		<div class="results" id="results">

		    <div id="hits-container"></div>
		    <center><div id="pagination-container"></div></center>

		</div>
	</div>


	<div class="col-md-3">
	@if(Auth::guest())
		<div class="well focus-box focus-box-yellow">
			<!-- todo Sidebar -->
			@lang('marketing.welcome')
			<a href="/register" class="btn btn-primary btn-lg btn-block btn-ww-md">Sign up now </a>
		</div>
		<div class="well focus-box focus-box-white">
			<!-- todo Sidebar -->
			@lang('marketing.employer_welcome')
			<a href="/register" class="btn btn-primary btn-lg btn-block btn-ww-md">Create your ad now </a>
		</div>
	@endif
		<div class="well focus-box focus-box-black">
			<!-- todo Sidebar -->
			@lang('marketing.tips')
		</div>
	</div>
</div>
@stop

@section('js_plugins')
	@include('js_plugins.algolia')
@stop