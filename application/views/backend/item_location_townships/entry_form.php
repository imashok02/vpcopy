
<?php
	$attributes = array( 'id' => 'location-township-form', 'enctype' => 'multipart/form-data');
	echo form_open( '', $attributes);
?>
	
<section class="content animated fadeInRight">
	<div class="col-md-12">
		<div class="card card-info">
		    <div class="card-header">
		        <h3 class="card-title"><?php echo get_msg('location_info')?></h3>
		    </div>
	        <!-- /.card-header -->
	        <div class="card-body">
	            <div class="row">
	            	<div class="col-md-6">

	            		<div class="form-group">
							<label> <span style="font-size: 17px; color: red;">*</span>
								<?php echo get_msg('location_name')?>
							</label>

							<?php
								$options=array();
								$options[0]=get_msg('location_name');
								$cities = $this->Itemlocation->get_all();
									foreach($cities->result() as $item_loc) {
										$options[$item_loc->id]=$item_loc->name;
								}

								echo form_dropdown(
									'city_id',
									$options,
									set_value( 'city_id', show_data( @$item_location_township->city_id), false ),
									'class="form-control form-control-sm mr-3" id="city_id"'
								);
							?>

						</div>
	            		<div class="form-group">
	                   		<label>
	                   			<span style="font-size: 17px; color: red;">*</span>
								<?php echo get_msg('location_township_name')?>
								<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('cat_name_tooltips')?>">
									<span class='glyphicon glyphicon-info-sign menu-icon'>
								</a>
							</label>

							<?php echo form_input( array(
								'name' => 'township_name',
								'value' => set_value( 'township_name', show_data( @$item_location_township->township_name ), false ),
								'class' => 'form-control form-control-sm',
								'placeholder' => get_msg( 'township_name' ),
								'id' => 'township_name'
							)); ?>
	              		</div>

	              		<div class="form-group">
	                   		<label>
	                   			<span style="font-size: 17px; color: red;">*</span>
								<?php echo get_msg('loc_ordering')?>
								<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('ordering_name_tooltips')?>">
									<span class='glyphicon glyphicon-info-sign menu-icon'>
								</a>
							</label>

							<?php echo form_input( array(
								'name' => 'ordering',
								'value' => set_value( 'ordering', show_data( @$item_location_township->ordering ), false ),
								'class' => 'form-control form-control-sm ordering',
								'placeholder' => get_msg( 'loc_ordering' ),
								'id' => 'ordering'
							)); ?>
	              		</div>

	              	</div>


	              	<div class="col-md-6">

	              		<div id="township_map" style="width: 100%; height: 400px;"></div>
	              		
	        			<div class="clearfix">&nbsp;</div>

	        			<div class="form-group">
			              <label><?php echo get_msg('itm_lat_label') ?>
			                <a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('city_lat_label')?>">
			                  <span class='glyphicon glyphicon-info-sign menu-icon'>
			                </a>
			              </label>

			              <br>

			              <?php 
			                echo form_input( array(
			                  'type' => 'text',
			                  'name' => 'lat',
			                  'id' => 'lat',
			                  'class' => 'form-control',
			                  'placeholder' => '',
			                  'value' => set_value( 'lat', show_data( @$item_location_township->lat ), false ),
			                ));
			              ?>
			            </div>

			            <div class="form-group">
			              <label><?php echo get_msg('itm_lng_label') ?>
			                <a href="#" class="tooltip-ps" data-toggle="tooltip" 
			                  title="<?php echo get_msg('city_lng_tooltips')?>">
			                  <span class='glyphicon glyphicon-info-sign menu-icon'>
			                </a>
			              </label>

			              <br>

			              <?php 
			                echo form_input( array(
			                  'type' => 'text',
			                  'name' => 'lng',
			                  'id' => 'lng',
			                  'class' => 'form-control',
			                  'placeholder' => '',
			                  'value' => set_value( 'lng', show_data( @$item_location_township->lng ), false ),
			                ));
			              ?>
			            </div>
			            <!-- form group -->
		            </div>
            		
	              		
	            <!-- /.row -->
	        	</div>
	        <!-- /.card-body -->
	   		</div>
	   		<?php 
				if ( isset( $item_location_township )) { 
			?>
				<input type="hidden" id="township_edit" name="township_edit" value="1">
			<?php		
				} else {
			?>
				<input type="hidden" id="township_edit" name="township_edit" value="0">
			<?php } ?> 
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