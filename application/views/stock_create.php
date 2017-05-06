<div id="content">
    <section class="main padder">
        <div class="separate"></div>
        <h4><i class="icon-dropbox"></i>Add Stock <small></small></h4>
        <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo base_url('backend/stock/create/' . $product_id); ?>" enctype="multipart/form-data">      
            <!-- booking table -->


            <!-- booking table -->
            <section class="panel">
                <header class="panel-heading">
                    <span class="h5">Stock Info</span>
                </header>
                <div class="panel-body">
                    <?php if ($this->input->get('status')): ?>
                        <div class="alert alert-success" style="font-size:15px;"><i class="icon-ok"></i> Stock "<b><?php echo $this->input->get('status') ?></b>" added</div>
                    <?php endif; ?>
                    <!---
                    <div style="max-height:500px;overflow-y: auto;border:5px solid #eee" class="multi-insert">
                        <style>
                            .multi-insert .form-control
                            {
                                width: 50px;
                                padding: 5px;
                                font-size:11px;
                            }
                            .multi-insert .form-control.small
                            {
                                width: 100px;
                            }
                            </style>
                        <table class="table table-bordered table-striped table-hover" >
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Stock Code</th>
                                    <th>Due Date</th>
                                    <th>Depart Take-off</th>
                                    <th>Depart Landed</th>
                                    <th>Arrive Take-off</th>
                                    <th>Arrive Landed</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                    <?php for ($aa = 0; $aa < 10; $aa++): ?>
                                                                    <tr>
                                                                        <td><a href="#" class="btn btn-primary"><i class="icon icon-plus-sign"></i></a> <a href="#" class="btn btn-danger"><i class="icon icon-minus-sign"></i></a></td>
                                                                        <td><input type="text" name="code" placeholder="Stock Code" data-required="true" class="small form-control" value=""></td>
                                                                        <td><input type="text" name="code" placeholder="Due Date" data-required="true" class="small form-control" value=""></td>
                                                                        <td>
                                                                            <input type="text" name="code" placeholder="Depart Take-off" data-required="true" class="small form-control" value="">
                                                                            <select name="depart_date_h" class="form-control pull-left">
                        <?php for ($i = 0; $i <= 23; $i++): ?>
                                                                                                                        <option <?php echo isset($product_stock_depart_at) && date("H", strtotime($product_stock_depart_at)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                        <?php endfor; ?>
                                                                            </select>
                                                                            <select name="depart_date_h" class="form-control">
                        <?php for ($i = 0; $i <= 23; $i++): ?>
                                                                                                                        <option <?php echo isset($product_stock_depart_at) && date("H", strtotime($product_stock_depart_at)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                        <?php endfor; ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="code" placeholder="Depart Landed" data-required="true" class="small form-control" value="">
                                                                            <select name="depart_date_h" class="form-control pull-left">
                        <?php for ($i = 0; $i <= 23; $i++): ?>
                                                                                                                        <option <?php echo isset($product_stock_depart_at) && date("H", strtotime($product_stock_depart_at)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                        <?php endfor; ?>
                                                                            </select>
                                                                            <select name="depart_date_h" class="form-control">
                        <?php for ($i = 0; $i <= 23; $i++): ?>
                                                                                                                        <option <?php echo isset($product_stock_depart_at) && date("H", strtotime($product_stock_depart_at)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                        <?php endfor; ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="code" placeholder="Arrive Take-off" data-required="true" class="small form-control" value="">
                                                                            <select name="depart_date_h" class="form-control pull-left">
                        <?php for ($i = 0; $i <= 23; $i++): ?>
                                                                                                                        <option <?php echo isset($product_stock_depart_at) && date("H", strtotime($product_stock_depart_at)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                        <?php endfor; ?>
                                                                            </select>
                                                                            <select name="depart_date_h" class="form-control">
                        <?php for ($i = 0; $i <= 23; $i++): ?>
                                                                                                                        <option <?php echo isset($product_stock_depart_at) && date("H", strtotime($product_stock_depart_at)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                        <?php endfor; ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="code" placeholder="Arrive Landed" data-required="true" class="small form-control" value="">
                                                                            <select name="depart_date_h" class="form-control pull-left">
                        <?php for ($i = 0; $i <= 23; $i++): ?>
                                                                                                                        <option <?php echo isset($product_stock_depart_at) && date("H", strtotime($product_stock_depart_at)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                        <?php endfor; ?>
                                                                            </select>
                                                                            <select name="depart_date_h" class="form-control">
                        <?php for ($i = 0; $i <= 23; $i++): ?>
                                                                                                                        <option <?php echo isset($product_stock_depart_at) && date("H", strtotime($product_stock_depart_at)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                        <?php endfor; ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="code" placeholder="Price" data-required="true" class="small form-control" value="">
                                                                        </td>
                                                                    </tr>
                    <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                    -->

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Stock Code</label>

                        <div class="col-lg-6" style="padding-left: 0px;">
                            <input type="text" name="code" placeholder="CT123456789" data-required="true" class="small form-control"
                                   value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" value="1">Active</label>
                        <div class="col-lg-4">
                            <div class="checkbox">
                                <label class="checkbox-custom">
                                    <input type="checkbox" name="is_active" <?php echo isset($is_active) && $is_active ? 'checked="checked"' : ''; ?>>
                                    <i class="icon-unchecked"></i>
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Promotion</label>
                        <div class="col-lg-4">
                            <div class="checkbox">
                                <label class="checkbox-custom">
                                    <input type="checkbox" name="is_promotion" id="is_promotion" value="1" <?php echo isset($is_promotion) && $is_promotion ? 'checked="checked"' : ''; ?>>
                                    <i class="icon-unchecked"></i>
                                    Promotion
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Early Bird</label>
                        <div class="col-lg-4">
                            <div class="checkbox">
                                <label class="checkbox-custom">
                                    <input type="checkbox" name="is_early_bird" id="is_early_bird" value="1" <?php echo isset($is_early_bird) && $is_early_bird ? 'checked="checked"' : ''; ?>>
                                    <i class="icon-unchecked"></i>
                                    Early Bird
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Due Date</label>
                        <div class="col-lg-8">
                            <input type="text" name="due_date" placeholder="Due Date" data-required="true" class="form-control datepicker"
                                   value="<?php echo isset($product_stock_due_date) ? date("d-m-Y", strtotime($product_stock_due_date)) : ''; ?>" data-date-format="dd-mm-yyyy">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-xs-12 control-label">Depart</label>
                        <div class="col-xs-3" style="width: auto;">
                            <input type="text" name="depart_date" placeholder="Depart" class="form-control" id="dpd1"
                                   value="<?php echo isset($product_stock_depart_at) ? date("d-m-Y", strtotime($product_stock_depart_at)) : ''; ?>" data-date-format="dd-mm-yyyy">
                        </div>
                        <div class="col-xs-2" style="width: auto;">
                            <select name="depart_date_h" class="form-control">
                                <?php for ($i = 0; $i <= 23; $i++): ?>
                                    <option <?php echo isset($product_stock_depart_at) && date("H", strtotime($product_stock_depart_at)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-xs-1" style="width: auto; padding: 0px; font-size: 19px;">
                            <span class="control-label">:</span>
                        </div>
                        <div class="col-xs-2" style="width: auto;">

                            <select name="depart_date_i" class="form-control">
                                <?php for ($i = 0; $i < 60; $i+=5): ?>
                                    <option <?php echo isset($product_stock_depart_at) && date("i", strtotime($product_stock_depart_at)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 col-xs-12 control-label">Depart Landed</label>
                        <div class="col-xs-3" style="width: auto;">
                            <input type="text" name="depart_landed_date" placeholder="Depart Landed" class="form-control datepicker"
                                   value="<?php echo isset($product_stock_depart_duration) ? date("d-m-Y", strtotime($product_stock_depart_duration)) : ''; ?>" data-date-format="dd-mm-yyyy">
                        </div>
                        <div class="col-xs-2" style="width: auto;">
                            <select name="depart_landed_date_h" class="form-control">
                                <?php for ($i = 0; $i <= 23; $i++): ?>
                                    <option <?php echo isset($product_stock_depart_duration) && date("H", strtotime($product_stock_depart_duration)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-xs-1" style="width: auto; padding: 0px; font-size: 19px;">
                            <span class="control-label">:</span>
                        </div>
                        <div class="col-xs-2" style="width: auto;">

                            <select name="depart_landed_date_i" class="form-control">
                                <?php for ($i = 0; $i < 60; $i+=5): ?>
                                    <option <?php echo isset($product_stock_depart_duration) && date("i", strtotime($product_stock_depart_duration)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>




                    <div class="form-group">
                        <label class="col-lg-3 col-xs-12 control-label">Arrive</label>
                        <div class="col-xs-3" style="width: auto;">
                            <input type="text" name="arrive_date" placeholder="Arrive" class="form-control"  id="dpd2"
                                   value="<?php echo isset($product_stock_arrive_at) ? date("d-m-Y", strtotime($product_stock_arrive_at)) : ''; ?>" data-date-format="dd-mm-yyyy">
                        </div>
                        <div class="col-xs-2" style="width: auto;">
                            <select name="arrive_date_h" class="form-control">
                                <?php for ($i = 0; $i <= 23; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo isset($product_stock_arrive_at) && date("H", strtotime($product_stock_arrive_at)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-xs-1" style="width: auto; padding: 0px; font-size: 19px;">
                            <span class="control-label">:</span>
                        </div>
                        <div class="col-xs-2" style="width: auto;">

                            <select name="arrive_date_i" class="form-control">
                                <?php for ($i = 0; $i < 60; $i+=5): ?>
                                    <option value="<?php echo $i; ?>" <?php echo isset($product_stock_arrive_at) && date("i", strtotime($product_stock_arrive_at)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 col-xs-12 control-label">Arrive Landed</label>
                        <div class="col-xs-3" style="width: auto;">
                            <input type="text" name="arrive_landed_date" placeholder="Arrive Landed" class="form-control datepicker"
                                   value="<?php echo isset($product_stock_arrive_duration) ? date("d-m-Y", strtotime($product_stock_arrive_duration)) : ''; ?>" data-date-format="dd-mm-yyyy">
                        </div>
                        <div class="col-xs-2" style="width: auto;">
                            <select name="arrive_landed_date_h" class="form-control">
                                <?php for ($i = 0; $i <= 23; $i++): ?>
                                    <option <?php echo isset($product_stock_arrive_duration) && date("H", strtotime($product_stock_arrive_duration)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-xs-1" style="width: auto; padding: 0px; font-size: 19px;">
                            <span class="control-label">:</span>
                        </div>
                        <div class="col-xs-2" style="width: auto;">

                            <select name="arrive_landed_date_i" class="form-control">
                                <?php for ($i = 0; $i < 60; $i+=5): ?>
                                    <option <?php echo isset($product_stock_arrive_duration) && date("i", strtotime($product_stock_arrive_duration)) == $i ? 'selected="selected"' : ''; ?>><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Period Caption</label>
                        <div class="col-lg-8">
                            <input type="text" name="period" placeholder="วันสงกรานต์"  class="form-control"
                                   value="<?php echo isset($product_stock_period) ? $product_stock_period : ''; ?>">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-3 control-label">Airline</label>
                        <div class="col-lg-4">
                            <select name="airline" class="form-control">
                                <option value="0">ไม่ใส่สายการบิน</option>
                                <?php foreach ($airlines as $airline): ?>
                                    <option value="<?php echo $airline->airline_id; ?>" <?php echo isset($product_stock_airline_id) && $product_stock_airline_id == $airline->airline_id ? 'selected="selected"' : ''; ?>><?php echo $airline->airline_code; ?>-<?php echo $airline->airline_title_th; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Flight Number Go</label>
                        <div class="col-lg-8">
                            <input type="text" name="flight_no_go" placeholder="Flight Number"  class="form-control"
                                   value="<?php echo isset($product_stock_flight_no_go) ? $product_stock_flight_no_go : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Flight Route Go</label>
                        <div class="col-lg-8">
                            <input type="text" name="flight_route_go" placeholder="Route"  class="form-control"
                                   value="<?php echo isset($product_stock_flight_route_go) ? $product_stock_flight_route_go : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Flight Number Return</label>
                        <div class="col-lg-8">
                            <input type="text" name="flight_no_return" placeholder="Flight Number"  class="form-control"
                                   value="<?php echo isset($product_stock_flight_no_return) ? $product_stock_flight_no_return : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Flight Route Return</label>
                        <div class="col-lg-8">
                            <input type="text" name="flight_route_return" placeholder="Route"  class="form-control"
                                   value="<?php echo isset($product_stock_flight_route_return) ? $product_stock_flight_route_return : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" value="1">Direct Flight</label>
                        <div class="col-lg-4">
                            <div class="checkbox">
                                <label class="checkbox-custom">
                                    <input type="checkbox" name="is_direct_flight" <?php echo isset($is_direct_flight) && $is_direct_flight ? 'checked="checked"' : ''; ?>>
                                    <i class="icon-unchecked"></i>
                                    Direct Flight
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php if ($product_type != 'ticket'): ?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Hotel</label>
                            <div class="col-lg-4">
                                <select name="hotel" class="form-control">
                                    <option>&nbsp;</option>
                                    <?php foreach ($hotels as $hotel): ?>
                                        <option value="<?php echo $hotel->hotel_id; ?>" <?php echo isset($product_stock_hotel_id) && $product_stock_hotel_id == $hotel->hotel_id ? 'selected="selected"' : ''; ?>><?php echo $hotel->hotel_title_th; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Single Room Price</label>
                            <div class="col-lg-8">
                                <input type="text" name="single_price" placeholder="Single Room Price" class="form-control"
                                       value="<?php echo isset($product_stock_single_price) ? $product_stock_single_price : ''; ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Unit Price</label>
                        <div class="col-lg-8">
                            <input type="text" name="price" placeholder="Unit Price" data-required="true" class="form-control"
                                   value="<?php echo isset($product_stock_price) ? $product_stock_price : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Unit price to discount</label>
                        <div class="col-lg-8">
                            <input type="text" name="discount-price" placeholder="Unit price to discount" data-required="true" class="form-control"
                                   value="<?php echo isset($product_stock_to_discount) ? $product_stock_to_discount : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Stock</label>
                        <div class="col-lg-8">
                            <input type="text" name="stock_booked" placeholder="Booked" class="form-control small" style="display: inline; width: 80px;"
                                   value="<?php echo isset($product_stock_booked) ? $product_stock_booked : 0; ?>"> / <input type="text" name="stock_total" placeholder="Total" data-required="true" class="form-control small"  style="display: inline;  width: 80px;"
                                   value="<?php echo isset($product_stock_total) ? $product_stock_total : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Minimum Booking</label>
                        <div class="col-lg-8">
                            <input type="text" name="min_book" placeholder="0" class="form-control small" style="display: inline; width: 80px;"
                                   value="<?php echo isset($product_stock_min_book) ? $product_stock_min_book : ''; ?>">
                        </div>
                    </div>                    
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Add On Package</label>
                        <div class="col-lg-8">
                            <textarea name="add_on" placeholder="Add On Package" rows="5" class="form-control"><?php echo isset($product_stock_add_on) ? $product_stock_add_on : ''; ?></textarea>
                            <br/>
                            <p>
                                <strong>Format (หัวข้อ|+/-ตัวเลข|ต่อหัว(P)หรือนับรวมกลุ่ม(G)):</strong>
                            </p>
                            <ul>
                                <li>ตัวอย่างเช่นเพิ่ม 1000 บาท ถ้าเอา City Tour เพิ่มโดยคิดต่อหัว จะได้ City Tour|+1000|P</li>
                                <li>หากมีหลายรายการให้ใช้ขึ้นบรรทัดใหม่</li>
                            </ul>
                        </div>
                    </div>
                    <!-- ส่วน Fields ที่เพิ่มมา-->

                    <fieldset>
                        <legend>Payment</legend>
                        <div class="form-group">
                            <label class="col-lg-3 control-label" value="1"> </label>
                            <div class="col-lg-4">
                                <div class="checkbox">
                                    <label class="checkbox-custom">
                                        <input type="checkbox" name="is_full_payment" <?php echo isset($is_fully_payment) && $is_fully_payment ? 'checked="checked"' : ''; ?>>
                                        <i class="icon-unchecked"></i>
                                        Full Payment Only
                                    </label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-3 control-label">Advanced Price</label>
                            <div class="col-lg-8">
                                <input type="text" name="advanced_price" placeholder="Advanced Price" class="form-control"
                                       value="<?php echo isset($product_stock_advanced_price) ? $product_stock_advanced_price : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">ชำระโดยโอนเงิน / ชำระโดยตรง</label>
                            <div class="col-lg-4">

                                <div class="checkbox">
                                    <label class="checkbox-custom">
                                        <input type="checkbox" name="direct_bank" value="1" <?php echo isset($direct_bank) && $direct_bank ? 'checked="checked"' : ''; ?>>
                                        <i class="icon-unchecked"></i>
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Bill Payment Bank</label>
                            <div class="col-lg-4">

                                <div class="checkbox">
                                    <label class="checkbox-custom">
                                        <input type="checkbox" name="bill_bank" value="1" <?php echo isset($bill_bank) && $bill_bank ? 'checked="checked"' : ''; ?>>
                                        <i class="icon-unchecked"></i>
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Bill Payment Big C</label>
                            <div class="col-lg-4">

                                <div class="checkbox">
                                    <label class="checkbox-custom">
                                        <input type="checkbox" name="bill_bigc" value="1" <?php echo isset($bill_bigc) && $bill_bigc ? 'checked="checked"' : ''; ?>>
                                        <i class="icon-unchecked"></i>
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-lg-3 control-label">Bill Payment THP</label>
                            <div class="col-lg-4">

                                <div class="checkbox">
                                    <label class="checkbox-custom">
                                        <input type="checkbox" name="bill_thp" value="1" <?php echo isset($bill_thp) && $bill_thp ? 'checked="checked"' : ''; ?>>
                                        <i class="icon-unchecked"></i>
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-3 control-label" value="1">Payment Gateway - KBANK</label>
                            <div class="col-lg-4">

                                <div class="checkbox">
                                    <label class="checkbox-custom">
                                        <input type="checkbox" name="kbank" value="1" <?php echo isset($kbank) && $kbank ? 'checked="checked"' : ''; ?>>
                                        <i class="icon-unchecked"></i>
                                        Active
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <b>Charge Rate</b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <input type="number"  step="any" name="kbank_rate" placeholder="Charge Rate" class="form-control" value="2.5">
                                    </div>
                                    <div class="col-lg-4">
                                        <?php echo form_dropdown('kbank_type', array('%' => '%', 'บาท' => 'บาท'), '', 'class="form-control"'); ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Payment Gateway - BBL</label>
                            <div class="col-lg-4">

                                <div class="checkbox">
                                    <label class="checkbox-custom">
                                        <input type="checkbox" name="bangkok_bank" value="1" <?php echo isset($bangkok_bank) && $bangkok_bank ? 'checked="checked"' : ''; ?>>
                                        <i class="icon-unchecked"></i>
                                        Active
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <b>Charge Rate</b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <input type="number"  step="any" name="bangkok_bank_rate" placeholder="Charge Rate" class="form-control" value="2.5">
                                    </div>
                                    <div class="col-lg-4">
                                        <?php echo form_dropdown('bangkok_bank_type', array('%' => '%', 'บาท' => 'บาท'), '', 'class="form-control"'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">LINE PAY</label>
                            <div class="col-lg-4">

                                <div class="checkbox">
                                    <label class="checkbox-custom">
                                        <input type="checkbox" name="line_pay" value="1" <?php echo isset($payment_method['line_pay']) && $payment_method['line_pay'] ? 'checked="checked"' : ''; ?>>
                                        <i class="icon-unchecked"></i>
                                        Active
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <b>Discount (THB/Person)</b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <input type="number" step="any" name="line_pay_discount" placeholder="Discount Rate" class="form-control" value="<?php echo isset($payment_method['line_pay_discount']) ? $payment_method['line_pay_discount'] : '0'; ?>">
                                    </div>

                                </div>
                            </div>
                        </div>


                    </fieldset>




                    <fieldset>
                        <legend>Commission</legend>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Agent Commission</label>
                            <div class="col-lg-8">
                                <textarea rows="5" name="commission" placeholder="Agent Commission" data-required="true" class="form-control"><?php echo isset($product_stock_commission) ? $product_stock_commission : 'A=0,0' . PHP_EOL . 'B=0,0' . PHP_EOL . 'C=0,0' . PHP_EOL . 'D=0,0' . PHP_EOL . 'E=0,0'; ?></textarea>
                                * 1 บรรทัด / ประเภท 1 Agent และ มีค่าคือ Commission ของ Agent และ Commission ของ Sale
                            </div>
                        </div>

                    </fieldset>

                    <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">                      
                            <a href="<?php echo base_url('backend/product/view/' . $product_id); ?>" class="btn btn-white">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>


            </section>

        </form>
        <div class="spacing-bottom"></div>
    </section>
</div>
<script>
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    var checkin = $('#dpd1').datepicker({
        onRender: function (date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function (ev) {
        if (ev.date.valueOf() > checkout.date.valueOf()) {
            var newDate = new Date(ev.date);
            newDate.setDate(newDate.getDate() + 1);
            checkout.setValue(newDate);
        }
        $('input[name="depart_landed_date"]').val($(this).val());
        $(this).datepicker('hide');
        $('#dpd2')[0].focus();
    }).data('datepicker');
    var checkout = $('#dpd2').datepicker({
        onRender: function (date) {
            return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function (ev) {
        $(this).datepicker('hide');
        $('input[name="arrive_landed_date"]').val($(this).val());
    }).data('datepicker');
</script>