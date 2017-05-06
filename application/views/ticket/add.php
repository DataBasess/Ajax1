<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="title">Ticket Add</div>
                </div>
            </div>
            <div class="card-body">
                <?php echo validation_errors(); ?>
				<?php echo form_open('ticket/add'); ?>
                <div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="name_ticket" class="control-label">Name Ticket</label>
						<div class="form-group">
							<input type="text" name="name_ticket" value="<?php echo $this->input->post('name_ticket'); ?>" class="form-control" id="name_ticket" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="price_kid" class="control-label">Price Kid</label>
						<div class="form-group">
							<input type="number" name="price_kid"  min="0" max="10000" step="1000" value="<?php echo $this->input->post('price_kid'); ?>" class="form-control" id="price_kid" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="price_adult" class="control-label">Price Adult</label>
						<div class="form-group">
							<input type="number" name="price_adult"  min="0" max="10000" step="1000" value="<?php echo $this->input->post('price_adult'); ?>" class="form-control" id="price_adult" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="price_older" class="control-label">Price Older</label>
						<div class="form-group">
							<input type="number" name="price_older"  min="0" max="10000" step="1000" value="<?php echo $this->input->post('price_older'); ?>" class="form-control" id="price_older" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="detail_ticket" class="control-label">Detail Ticket</label>
						<div class="form-group">
							<input type="date" name="detail_ticket" value="<?php echo $this->input->post('detail_ticket'); ?>" class="form-control" id="detail_ticket" />
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