<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
				<div class="pull-right">
                    <a href="<?php echo site_url('date_no_sale/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
                <div class="card-title">
                    <div class="title">Date No Sale Listing</div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <tr>
						<th>Id Date</th>
						<th>Date</th>
						<th>Id Ticket</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($date_no_sale as $d){ ?>
                    <tr>
						<td><?php echo $d['id_date']; ?></td>
						<td><?php echo $d['date']; ?></td>
						<td><?php echo $d['id_ticket']; ?></td>
						<td>
                            <a href="<?php echo site_url('date_no_sale/edit/'.$d['id_date']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('date_no_sale/remove/'.$d['id_date']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>