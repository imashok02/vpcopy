<?php $this->load->model('Maincategory'); ?>
<script>

	<?php if ( $this->config->item( 'client_side_validation' ) == true ): ?>

	function jqvalidate() {

		$('#category-form').validate({
			rules:{
				cat_name:{
					blankCheck : "",
					minlength: 3,
					remote: "<?php echo $module_site_url .'/ajx_exists/'.@$Maincategory->main_cat_id; ?>"
				}
			},
			messages:{
				cat_name:{
					blankCheck : "<?php echo get_msg( 'err_cat_name' ) ;?>",
					minlength: "<?php echo get_msg( 'err_cat_len' ) ;?>",
					remote: "<?php echo get_msg( 'err_cat_exist' ) ;?>."
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

</script>