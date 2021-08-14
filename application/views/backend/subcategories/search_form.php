<div class='row my-3'>
	<div class='col-9'>
	<?php
		$attributes = array('class' => 'form-inline');
		echo form_open( $module_site_url .'/search', $attributes);
	?>
		
		<div class="form-group mr-3">

			<?php echo form_input(array(
				'name' => 'searchterm',
				'value' => set_value( 'searchterm' ),
				'class' => 'form-control form-control-sm',
				'placeholder' => get_msg( 'btn_search' )
			)); ?>

	  	</div>

	  	<div class="form-group" style="padding-right: 3px;">

			<?php
				$options=array();
				$options[0]=get_msg('Prd_search_cat');
				$categories = $this->Category->get_all();
				foreach($categories->result() as $cat) {
					
						$options[$cat->cat_id]=$cat->cat_name;
				}

				echo form_dropdown(
					'cat_id',
					$options,
					set_value( 'cat_id', show_data( @$subcategories->cat_id), false ),
					'class="form-control form-control-sm mr-3" id="cat_id"'
				);
			?>

	  	</div>

	  	<div class="form-group">
			&nbsp;
			<?php
				echo get_msg( 'order_by' );
				//echo $order_by . " #####";

				$options=array();
				$options[0]=get_msg('select_order');

				foreach ($this->Order->get_all()->result() as $ord) {

					$options[$ord->id]=$ord->name;
								
				}
				echo form_dropdown(
					'order_by',
					$options,
					set_value( 'order_by', show_data( $order_by), false ),
					'class="form-control form-control-sm mr-3 ml-3" id="order_by"'
				);
			?>

	  	</div>

	  	<div class="form-group" style="padding-right: 2px;">
		  	<button type="submit" class="btn btn-sm btn-primary">
		  		<?php echo get_msg( 'btn_search' )?>
		  	</button>
	  	</div>

	  	<div class="form-group">
		  	<a href='<?php echo $module_site_url; ?>' class='btn btn-sm btn-primary'>
				<?php echo get_msg( 'btn_reset' )?>
			</a>
	  	</div>

	<?php echo form_close(); ?>

	</div>	

	<div class='col-3'>
		<a href='<?php echo $module_site_url .'/add';?>' class='btn btn-sm btn-primary pull-right'>
			<span class='fa fa-plus'></span> 
			<?php echo get_msg( 'subcat_add' )?>
		</a>
	</div>

</div>