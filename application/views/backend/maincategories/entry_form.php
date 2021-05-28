<?php
	$attributes = array( 'id' => 'main-category-form', 'enctype' => 'multipart/form-data');
	echo form_open( '', $attributes);
?>

<section class="content animated fadeInRight">
	<div class="card card-info">
	    <div class="card-header">
	        <h3 class="card-title"><?php echo get_msg('cat_info')?></h3>
	    </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
             	<div class="col-md-6">
            		<div class="form-group">
                   		<label>
                   			<span style="font-size: 17px; color: red;">*</span>
							<?php echo get_msg('main_cat_name')?>
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('cat_name_tooltips')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>

						<?php echo form_input( array(
							'name' => 'main_cat_name',
							'value' => set_value( 'main_cat_name', show_data( @$category->main_cat_name ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'main_cat_name' ),
							'id' => 'main_cat_name'
						)); ?>
              		</div>

              	</div>



            </div>
            <!-- /.row -->
        </div>
        <!-- /.card-body -->

		<div class="card-footer">
            <button type="submit" class="btn btn-sm btn-primary">
				<?php echo get_msg('btn_save')?>
			</button>

			<a href="<?php echo $module_site_url; ?>" class="btn btn-sm btn-primary">
				<?php echo get_msg('btn_cancel')?>
			</a>
        </div>

    </div>
    <!-- card info -->
</section>

<?php echo form_close(); ?> 