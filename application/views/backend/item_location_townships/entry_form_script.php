<script>

	<?php if ( $this->config->item( 'client_side_validation' ) == true ): ?>

	function jqvalidate() {

		$('#location-township-form').validate({
			rules:{
				township_name:{
					blankCheck : "",
					minlength: 3
					
				},
				lat:{
					blankCheck : ""
				},
				lng:{
					blankCheck : ""
				}
			},
			messages:{
				township_name:{
					blankCheck : "<?php echo get_msg( 'err_township_name' ) ;?>",
					minlength: "<?php echo get_msg( 'err_township_len' ) ;?>"
					
				},
				lat:{
					blankCheck : "<?php echo get_msg( 'err_lat' ) ;?>"
				},
				lng:{
					blankCheck : "<?php echo get_msg( 'err_lng' ) ;?>"
				}
			}
		});
		// custom validation
		jQuery.validator.addMethod("blankCheck",function( value, element ) {
			
			   if(value == "") {
			    	return false;
			   } else {
			    	return true;
			   }
		})
	}

	<?php endif; ?>

	function runAfterJQ() {

		  $('input[name="ordering"]').keyup(function(e)
                                {
		  if (/[^0-9\.]/g.test(this.value))
		  {
		    // Filter non-digits from input value.
		    //this.value = this.value.replace(/\D/g, '');
		    this.value = this.value.replace(/[^0-9\.]/g,'');
		  }
		});
	}	

</script>