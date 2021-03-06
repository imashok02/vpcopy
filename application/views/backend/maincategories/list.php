<div class="table-responsive animated fadeInRight">
	<table class="table m-0 table-striped">
		<tr>
			<th><?php echo get_msg('no'); ?></th>
			<th><?php echo get_msg('id'); ?></th>
			<th><?php echo get_msg('cat_name'); ?></th>

			<?php if ( $this->ps_auth->has_access( EDIT )): ?>

				<th><span class="th-title"><?php echo get_msg('btn_edit')?></span></th>

			<?php endif; ?>

			<?php if ( $this->ps_auth->has_access( DEL )): ?>

				<th><span class="th-title"><?php echo get_msg('btn_delete')?></span></th>

			<?php endif; ?>

			<?php if ( $this->ps_auth->has_access( PUBLISH )): ?>

				<th><span class="th-title"><?php echo get_msg('btn_publish')?></span></th>

			<?php endif; ?>

		</tr>


	<?php $count = $this->uri->segment(4) or $count = 0; ?>


	<?php if ( !empty( $categories ) && count( $categories->result()) > 0 ): ?>

		<?php foreach($categories->result() as $category): ?>
			<!-- <?php echo "<pre>"; print_r($categories);  ?> -->
			<tr>
				<td><?php echo ++$count;?></td>
				<td><?php echo $category->main_cat_id;?></td>
				<td ><?php echo $category->main_cat_name;?></td>

				<?php $default_photo = get_default_photo( $category->main_cat_id, 'category-icon' ); ?>	


				<?php if ( $this->ps_auth->has_access( EDIT )): ?>

					<td>
						<a href='<?php echo $module_site_url .'/edit/'. $category->main_cat_id; ?>'>
							<i style='font-size: 18px;' class='fa fa-pencil-square-o'></i>
						</a>
					</td>

				<?php endif; ?>

				<?php if ( $this->ps_auth->has_access( DEL )): ?>

					<td>
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo $category->main_cat_id;?>">
							<i style='font-size: 18px;' class='fa fa-trash-o'></i>
						</a>
					</td>

				<?php endif; ?>

				<?php if ( $this->ps_auth->has_access( PUBLISH )): ?>

					<td>
						<?php if ( @$category->status == 1): ?>
							<button class="btn btn-sm btn-success unpublish" catid='<?php echo $category->main_cat_id;?>'>
							<?php echo get_msg('btn_yes'); ?></button>
						<?php else:?>
							<button class="btn btn-sm btn-danger publish" catid='<?php echo $category->main_cat_id;?>'>
							<?php echo get_msg('btn_no'); ?></button></button><?php endif;?>
					</td>

				<?php endif; ?>

			</tr>

		<?php endforeach; ?>

	<?php else: ?>

		<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

	<?php endif; ?>

</table>
</div>
