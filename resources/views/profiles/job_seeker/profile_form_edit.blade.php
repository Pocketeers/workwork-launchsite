@inject('countries', 'App\Http\Utilities\Country')

{{ csrf_field() }}

<div class="form-group">
	<label for="business_name">Name:</label>
	<input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
</div>

<div class="form-group">
	<label for="business_category">Age:</label>
	<input type="text" name="age" id="age" class="form-control" value="{{ $jobSeeker->age}}" required>
</div>

<div class="form-group">
	<label for="contact">Contact:</label>
	<input type="text" name="contact" id="contact" class="form-control" value="{{ $user->contact }}" required>
</div>


<hr>

<h3>Address</h3>

<div class="form-group">
	<label for="location">Location:</label>
	<input type="text" name="location" id="location" class="form-control" value="{{ $jobSeeker->location }}" required>
</div>

<div class="form-group">
	<label for="street">Street:</label>
	<input type="text" name="street" id="street" class="form-control" value="{{ $jobSeeker->street }}" required>
</div> 

<div class="form-group">
	<label for="city">City:</label>
	<input type="text" name="city" id="city" class="form-control" value="{{ $jobSeeker->city }}" required>
</div> 

<div class="form-group">
	<label for="zip">Zip:</label>
	<input type="text" name="zip" id="zip" class="form-control" value="{{ $jobSeeker->zip }}" required>
</div> 

<div class="form-group">
	<label for="state">State:</label>
	<input type="text" name="state" id="state" class="form-control" value="{{ $jobSeeker->state }}" required>
</div>

<div class="form-group">
	<label for="country">Country:</label>
	<select name="country" id="country" class="form-control" required>
			<option value="{{ $jobSeeker->country }}">{{ $jobSeeker->country }}</option>
		@foreach ($countries::all() as $code => $name)
			<option value="{{ $code }}">{{ $name }}</option>
		@endforeach
	</select>
</div>

<hr>

<div class="form-group">
	<button type="submit" class="btn btn-primary">Save Profile</button>
</div> 