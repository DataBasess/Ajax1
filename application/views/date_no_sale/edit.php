<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="title">Date No Sale Edit</div>
                </div>
            </div>
            <div class="card-body">
				<?php echo validation_errors(); ?>
				<?php echo form_open('date_no_sale/edit/'.$date_no_sale['id_date']); ?>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="date" class="control-label">Date</label>
						<div class="form-group">
							<select name="date" class="form-control">
								<option value="">select</option>
								<?php 
								$date_values = array(
					);

								foreach($date_values as $value => $display_text)
								{
									$selected = ($value == $date_no_sale['date']) ? ' selected="selected"' : "";

									echo '<option value="'.$value.'" '.$selected.'>'.$display_text.'</option>';
								} 
								?>
							</select>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="id_ticket" class="control-label">Id Ticket</label>
						<div class="form-group">
							<input type="text" name="id_ticket" value="<?php echo ($this->input->post('id_ticket') ? $this->input->post('id_ticket') : $date_no_sale['id_ticket']); ?>" class="form-control" id="id_ticket" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-success">
						<i class="fa fa-check"></i> Save
					</button>
				</div>
				<?php echo form_close(); ?>
			</div>
        </div>
    </div>
</div>
