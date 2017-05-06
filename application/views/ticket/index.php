<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
				<div class="pull-right">
                    <a href="<?php echo site_url('ticket/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
                <div class="card-title">
                    <div class="title">Ticket Listing</div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <tr>
						<th>Id Ticket</th>
						<th>Name Ticket</th>
						<th>Price Kid</th>
						<th>Price Adult</th>
						<th>Price Older</th>
						<th>Detail Ticket</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($ticket as $t){ ?>
                    <tr>
						<td><?php echo $t['id_ticket']; ?></td>
						<td><?php echo $t['name_ticket']; ?></td>
						<td><?php echo $t['price_kid']; ?></td>
						<td><?php echo $t['price_adult']; ?></td>
						<td><?php echo $t['price_older']; ?></td>
						<td><?php echo $t['detail_ticket']; ?></td>
						<td>
                            <a href="<?php echo site_url('ticket/edit/'.$t['id_ticket']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('ticket/remove/'.$t['id_ticket']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>