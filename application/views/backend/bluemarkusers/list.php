<div class="table-responsive animated fadeInRight">
	<table class="table m-0 table-striped">
		<tr>
			<th><?php echo get_msg('no'); ?></th>
			<th><?php echo get_msg('user_name'); ?></th>
			<th><?php echo get_msg('user_email'); ?></th>
			<th><?php echo get_msg('user_phone_label'); ?></th>
            <th><?php echo get_msg('status_label'); ?></th>
			<th><?php echo get_msg('note'); ?></th>
			<th><span class="th-title"><?php echo get_msg('btn_edit'); ?></span></th>
            <th><?php echo get_msg('applied_date_label'); ?></th>
		</tr>

	<?php $count = $this->uri->segment(4) or $count = 0; ?>

	<?php if ( !empty( $bluemarks ) && count( $bluemarks->result()) > 0 ): ?>

		<?php foreach($bluemarks->result() as $bluemark): ?>
			
			<tr>
				<td><?php echo ++$count;?></td>
				<td><?php echo $this->User->get_one($bluemark->user_id)->user_name; ?></td>
				<td><?php echo $this->User->get_one($bluemark->user_id)->user_email; ?></td>
				<td><?php echo $this->User->get_one($bluemark->user_id)->user_phone; ?></td>
                <td style="width: 10%;">
                    <?php

                    if ( $this->User->get_one($bluemark->user_id)->is_verify_blue_mark == 1) { ?>
                        <span class="badge badge-success">
				                  <?php echo get_msg("verified"); ?>
				                </span>
                    <?php } elseif ( $this->User->get_one($bluemark->user_id)->is_verify_blue_mark == 3) { ?>
                        <span class="badge badge-danger">
				                  <?php echo get_msg("rejected"); ?>
				                </span>
                    <?php } elseif ( $this->User->get_one($bluemark->user_id)->is_verify_blue_mark == 2) { ?>
                        <span class="badge badge-warning">
				                  <?php echo get_msg("pending"); ?>
				                </span>
                    <?php } ?>
                </td>
				<td><?php 

					$note = strip_tags($this->User->get_one($bluemark->user_id)->blue_mark_note);

					if (strlen($note) > 80) {
                    	
                    	    $stringCut = substr($note, 0, 80);
                
                    	    $note = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
                    	}

					echo $note;
				 ?></td>

				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
			
					<td>
						<a href='<?php echo $module_site_url .'/edit/'. $bluemark->id; ?>'>
							<i class='fa fa-pencil-square-o'></i>
						</a>
					</td>

				<?php endif; ?>
                <td>

                   <?php

                   if(array_key_exists('blue_mark_note',$bluemark)){
                       //search
                       $conds['user_id'] = $bluemark->user_id;
                       $bluemark_data = $this->Blue_mark->get_one_by($conds);
                       $dateFromBlueMark = ($bluemark_data->updated_date);

                       echo date("d/m/Y",strtotime($bluemark_data->updated_date));
                   } else {
                       //list
                       echo date("d/m/Y",strtotime($bluemark->updated_date));
                   }

                   ?>

                </td>
			</tr>

		<?php endforeach; ?>

	<?php else: ?>
			
		<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

	<?php endif; ?>

</table>
</div>