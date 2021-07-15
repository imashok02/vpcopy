
<?php
	$attributes = array( 'id' => 'price-form', 'enctype' => 'multipart/form-data');
	echo form_open( '', $attributes);
?>
	
<section class="content animated fadeInRight">
	<div class="col-md-6">
		<div class="card card-info">
		    <div class="card-header">
		        <h3 class="card-title"><?php echo get_msg('price_info')?></h3>
		    </div>
	        <!-- /.card-header -->
	        <div class="card-body">
	        	<div class="col-md-6">
            		<div class="form-group">
								<label> <span style="font-size: 17px; color: red;">*</span>
									<?php echo get_msg('Main Category')?>
								</label>

								<?php
									$options=array();
									$options[0]=get_msg('main_cat_id');
									$categories = $this->Maincategory->get_all();
										foreach($categories->result() as $cat) {
											$options[$cat->main_cat_id]=$cat->main_cat_name;
									}
									// print_r($options); die;

									echo form_dropdown(
										'main_cat_id',
										$options,
										set_value( '', show_data( @$cat->main_cat_name), false ),
										'class="form-control form-control-sm mr-3" id="main_cat_id"'
									);
								?>

							</div>

            	</div>
	            <div class="row">
	             	<div class="col-md-12">
	            		<div class="form-group">
	                   		<label>
	                   			<span style="font-size: 17px; color: red;">*</span>
								<?php echo get_msg('price_name')?>
								<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('cat_name_tooltips')?>">
									<span class='glyphicon glyphicon-info-sign menu-icon'>
								</a>
							</label>

							<?php echo form_input( array(
								'name' => 'name',
								'value' => set_value( 'name', show_data( @$price->name ), false ),
								'class' => 'form-control form-control-sm',
								'placeholder' => get_msg( 'price_name' ),
								'id' => 'name'
							)); ?>
	              		</div>

	              		
	            	</div>
	            <!-- /.row -->
	        	</div>
	        <!-- /.card-body -->
	   		</div>

			<div class="card-footer">
	            <button type="submit" class="btn btn-sm btn-primary">
					<?php echo get_msg('btn_save')?>
				</button>

				<a href="<?php echo $module_site_url; ?>" class="btn btn-sm btn-primary">
					<?php echo get_msg('btn_cancel')?>
				</a>
	        </div>
	       
		</div>

	</div>
</section>
				

	
	

<?php echo form_close(); ?>