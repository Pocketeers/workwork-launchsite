@extends('layouts.app')

@section('content')
<form method="post" action="/interest">
	<div class="btn-group" data-toggle="buttons">
	  <label class="btn btn-primary active">
	    <input type="checkbox" checked autocomplete="off"> Checkbox 1 (pre-checked)
	  </label>
	  <label class="btn btn-primary">
	    <input type="checkbox" autocomplete="off"> Checkbox 2
	  </label>
	  <label class="btn btn-primary">
	    <input type="checkbox" autocomplete="off"> Checkbox 3
	  </label>
	</div>
</form>
@stop