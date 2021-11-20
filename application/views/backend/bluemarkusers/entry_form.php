<?php
	$attributes = array('id' => 'user-form');
	echo form_open( '', $attributes );
?>
<section class="content animated fadeInRight">

	<div class="card card-info">
	    <div class="card-header">
	      <h3 class="card-title"><?php echo get_msg('user_info')?></h3>
	    </div>

	   
  		<div class="card-body">
    		<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label><?php echo get_msg('user_name'); ?></label>
						<?php echo form_input( array(
							'name' => 'user_name',
							'value' => set_value( 'user_name', show_data( @$this->User->get_one($bluemark->user_id)->user_name ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'user_name' ),
							'id' => 'user_name',
							'readonly' => "true"
						)); ?>
					</div>

					<div class="form-group">
						<label><?php echo get_msg('user_email'); ?></label>
						<?php echo form_input( array(
							'name' => 'user_email',
							'value' => set_value( 'user_email', show_data( @$this->User->get_one($bluemark->user_id)->user_email ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'user_email' ),
							'id' => 'user_email',
							'readonly' => "true"
						)); ?>
					</div>

					<div class="form-group">
						<label><?php echo get_msg('user_phone'); ?></label>
						<?php echo form_input( array(
							'name' => 'user_phone',
							'value' => set_value( 'user_phone', show_data( @$this->User->get_one($bluemark->user_id)->user_phone ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'user_phone' ),
							'id' => 'user_phone',
							'readonly' => "true"
						)); ?>
					</div>

					<div class="form-group">
						<label><?php echo get_msg('blue_mark'); ?></label>
						<?php 
							$is_verify_blue_mark = $this->User->get_one($bluemark->user_id)->is_verify_blue_mark;

							if ($is_verify_blue_mark == '1') {
								$bluemark_test = get_msg('blue_mark_verified');
							} else if ($is_verify_blue_mark == '2') {
								$bluemark_test = get_msg('blue_mark_applied');
							} else {
								$bluemark_test = get_msg('blue_mark_rejected');
							}
						 ?>

						<input type="text" class="form-control form-control-sm" readonly="true" name="blue_mark" placeholder="<?php echo $bluemark_test; ?>">
					</div>
					
				</div>

				<div class="col-md-6">
					<div class="form-group">	
						<label><?php echo get_msg('user_address'); ?></label>
						<?php echo form_input( array(
							'name' => 'user_address',
							'value' => set_value( 'user_address', show_data( @$this->User->get_one($bluemark->user_id)->user_address ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'user_address' ),
							'id' => 'user_address',
							'readonly' => "true"
						)); ?>
					</div>
					<div class="form-group">	
						<label><?php echo get_msg('user_city'); ?></label>
						<?php echo form_input( array(
							'name' => 'city',
							'value' => set_value( 'city', show_data( @$this->User->get_one($bluemark->user_id)->city ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'city' ),
							'id' => 'city',
							'readonly' => "true"
						)); ?>
					</div>
					<div class="form-group">	
						<label><?php echo get_msg('about_me'); ?></label>
						<?php echo form_input( array(
							'name' => 'user_about_me',
							'value' => set_value( 'user_about_me', show_data( @$this->User->get_one($bluemark->user_id)->user_about_me ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'about_me' ),
							'id' => 'user_about_me',
							'readonly' => "true"
						)); ?>
					</div>
					<div class="form-group">	
						<label><?php echo get_msg('note'); ?></label>
						<?php echo form_textarea( array(
			                'name' => 'note',
			                'value' => set_value( 'note', show_data( @$bluemark->note), false ),
			                'class' => 'form-control form-control-sm',
			                'placeholder' => get_msg('item_description_label'),
			                'id' => 'note',
			                'rows' => "3",
			                'readonly' => 'true'
						)); ?>
					</div>
				</div>
			</div>

			<hr>

			<div class="row">
            	<div class="col-md-12">
			        <div class="form-group" style="background-color: #edbbbb; padding: 20px;">
			          <label>
			            <strong><?php echo get_msg('select_status')?></strong>
			          </label>

			          <select id="verify_blue_mark" name="verify_blue_mark" class="form-control">
			             <option value="1">Approved</option>
			             <option value="3">Reject</option>
			          </select>
			        </div>
		        </div>
            	
            </div>
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
</section>

<?php echo form_close(); ?>