
<?php
	$attributes = array( 'id' => 'type-form', 'enctype' => 'multipart/form-data');
	echo form_open( '', $attributes);
?>
	
<section class="content animated fadeInRight">
	<div class="col-md-6">
		<div class="card card-info">
		    <div class="card-header">
		        <h3 class="card-title"><?php echo get_msg('type_info')?></h3>
		    </div>
	        <!-- /.card-header -->
	        <div class="card-body">
	            <div class="row">
	            	 <div class="col-md-12">
            		<div class="form-group">
								<label> <span style="font-size: 17px; color: red;">*</span>
									<?php echo get_msg('Prd_search_main_cat')?>
								</label>

								<?php
									$options=array();
									$options[0]=get_msg('Prd_search_main_cat');
									$categories = $this->Maincategory->get_all();
										foreach($categories->result() as $cat) {
											$options[$cat->main_cat_id]=$cat->main_cat_name;
									}

									echo form_dropdown(
										'main_cat_id',
										$options,
										set_value( 'main_cat_id', show_data( @$type->main_cat_id), false ),
										'class="form-control form-control-sm mr-3" id="cat_id"'
									);
								?>

							</div>

            	</div>
	             	<div class="col-md-12">
	            		<div class="form-group">
	                   		<label>
	                   			<span style="font-size: 17px; color: red;">*</span>
								<?php echo get_msg('type_name')?>
								<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('cat_name_tooltips')?>">
									<span class='glyphicon glyphicon-info-sign menu-icon'>
								</a>
							</label>

							<?php echo form_input( array(
								'name' => 'name',
								'value' => set_value( 'name', show_data( @$type->name ), false ),
								'class' => 'form-control form-control-sm',
								'placeholder' => get_msg( 'type_name' ),
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