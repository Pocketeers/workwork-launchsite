
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.js"></script>


<script>

	Dropzone.options.addPhotosForm = {

		paramName: 'photo', // input name / id

		maxFilesize: 3, // mb

		acceptedFiles: '.jpg, .jpeg, .png, .bmp', // allowed files

		accept: function(file, done) {
		    console.log("uploaded");
		    done();
		},
		init: function() {
		    this.on("addedfile", function() {
				if (this.files[1]!=null){
					this.removeFile(this.files[0]);
				}
		    });
		}
	};

</script>

@include('java_plugins.defaultPhoto')