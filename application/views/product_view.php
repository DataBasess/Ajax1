<?php $lang = get_lang(); ?>


<?php
if ($is_visible == 0 && $is_force_static_visible == 0) {
    $type_url = $this->uri->segment(1);
    //echo 'Redirect';
    ?>
    <!-- Modal -->
    <div class="modal fade" id="redirectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-exclamation-sign"></i> ไม่พบรายการสินค้าและบริการที่คุณเลือก</h4>
                </div>
                <div class="modal-body">
                    ขออภัยค่ะ รายการสินค้านี้หมดอายุแล้ว ไม่ต้องกังวล! กรุณาคลิกดู "สินค้าที่เกี่ยวข้อง" ที่ใกล้เคียงกับรายการที่คุณต้องการนี้ได้ค่ะ
                </div>
                <div class="modal-footer">

                    <a href="<?php echo base_url($type_url . '/' . $product_category_title_en); ?>" type="button" class="btn btn-primary">สินค้าที่เกี่ยวข้อง</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $('#redirectModal').modal({'show': true});
        });

    </script>
    <?php
}
?>

<div class="container" itemscope itemtype="http://schema.org/Product">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header" style="border:0;padding:0">
                <h2 itemprop="name"><?php echo $is_sold == 1 ? '<div class="badge badge-success" style="background:red">Sold out</div> ' : '' ?><?php echo $lang == 'th' ? $product_title_th : $product_title_en; ?></h2>
                <h4 style="color: #20409a;margin-bottom: 5px;font-size: 14px;"><?php echo $lang == 'th' ? $product_subtitle_th : $product_subtitle_en; ?></h4>

                <div class="breadcrumb">
                    <a href="<?php echo base_url(); ?>"><i
                            class="glyphicons x-small home"></i><?php echo lang('home') ?></a> /
                        <?php
                        $product_type_re = str_replace('_', ' ', lang($product_type));
                        $product_type_re = ucwords($product_type_re);

                        $type_url = $this->uri->segment(1);
                        ?>
                    <a href="<?php echo base_url($type_url); ?>"><?php echo $product_type_re; ?></a> /
                    <a href="<?php echo base_url($type_url . '/' . $product_category_title_en); ?>"><?php echo $lang == 'en' ? $product_category_title_en : $product_category_title_th; ?></a>
                    /
                    <a href="<?php echo base_url($type_url . '/v/' . $product_id); ?>"><?php echo $lang == 'th' ? $product_title_th : $product_title_en; ?></a>
                </div>
            </div>
            <div class="search-filters" style="display:none">
                <div class="filter-container">
                    <h3><?php echo lang('find_product') ?>:</h3>
                    <span>
                        <select id="product_type">
                            <option
                                value="product" <?php echo $product_type == 'all' ? 'selected' : ''; ?>><?php echo lang('all_product_type') ?></option>
                            <option
                                value="ticket" <?php echo $product_type == 'ticket_hotel' ? 'selected' : ''; ?>><?php echo lang('ticket') ?></option>

                            <option
                                value="tour" <?php echo $product_type == 'tour_package' ? 'selected' : ''; ?>><?php echo lang('tour_package') ?></option>
                            <option
                                value="travel" <?php echo $product_type == 'travel_package' ? 'selected' : ''; ?>><?php echo lang('travel_package') ?></option>

                            <!--
                            <option
                                value="ticket-hotel" <?php echo $product_type == 'ticket' ? 'selected' : ''; ?>><?php echo lang('ticket_hotel') ?></option>
                            <option
                                value="others" <?php echo $product_type == 'others' ? 'selected' : ''; ?>><?php echo lang('others') ?></option>
                            -->
                        </select>
                    </span>

                    <span>
                        <select id="category">
                            <option value="any"><?php echo lang('everywhere') ?></option>
                            <?php foreach ($product_categories as $cat): ?>
                                <?php
                                $cat_name = str_replace(' ', '-', $cat->product_category_title_en);
                                ?>
                                <option
                                    value="<?php echo $cat_name; ?>" <?php echo $product_category_id == $cat->product_category_id ? 'selected' : ''; ?>>
                                        <?php echo $cat->product_category_title_th; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                    <span>
                        <select id="promotion">
                            <option value="any"><?php echo lang('all_deal') ?></option>
                            <option value="promotion_early"><?php echo lang('promotion_early') ?></option>
                            <option value="early_bird"><?php echo lang('early_bird') ?></option>
                            <option value="promotion"><?php echo lang('promotion') ?></option>
                            <option value="recommend"><?php echo lang('recommended') ?></option>
                        </select>
                    </span>
                    <span>
                        <select id="sorting">
                            <option value="price"><?php echo lang('sort_by_price') ?></option>
                            <option value="recent"><?php echo lang('sort_by_recent') ?></option>
                            <option value="view"><?php echo lang('sort_by_view') ?></option>

                        </select>
                    </span>
                    <span class="filter-control">
                        <form id="search_form">
                            <input id="search_tag" placeholder="<?php echo lang('placeholder_keyword') ?>" type="text">
                            <button class="searchBtn" type="submit"><i
                                    class="glyphicons small search"></i> <?php echo lang('search') ?></button>
                        </form>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if ($product_type == 'ticket') {
    ?>
    <div class="container">


        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Promotion Tickets</h4>
                    </div>
                    <div class="modal-body">
                        <?php
                        $this->load->view('frontend/inc/inc_vendor_amadeus');
                        ?>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


    </div>
    <?php
}
?>

<!-- Articles and Banners -->
<div class="container">
    <div class="row">
        <!-- Product Container -->
        <div class="col-md-9">

            <div class="row">
                <div class="content-container">
                    <!--
                    <h3><span class="text-blue">แชร์</span> <span class="text-red">สินค้านี้</span></h3>
                    <div class="trip-info-box">
                        <div class="addthis_native_toolbox"></div>
                        <span>
                            <script type="text/javascript" src="//media.line.me/js/line-button.js?v=20140411" ></script>
                            <script type="text/javascript">
                                new media_line_me.LineButton({"pc": false, "lang": "en", "type": "a"});
                            </script>
                        </span>
                    </div>
                    -->
                    <?php $member = $this->session->userdata('is_loggedin'); ?>

                    <div class="product-teaser-container">
                        <!-- SLIDER -->
                        <?php if ($product_header): ?>
                            <img src="<?php echo get_thumb($product_header, 853, 282); ?>">
                        <?php endif ?>
                        <!-- SLIDER END -->

                        <?php if ($product_type != 'ticket' && $product_type != 'pass'): ?>
                            <div itemprop="description">
                                <?php echo $lang == 'en' ? str_replace('</h2>', '</h2><span class="text-red">Highlight</span>', $product_highlight_en) : str_replace('</h2>', '</h2><span class="text-red">Highlight</span>', $product_highlight_th); ?>
                            </div>

                            <?php
                            $detail_widget = @unserialize(base64_decode($product_detail));
                            $is_active = 1;
                            if (isset($detail_widget['active']) && $detail_widget['active'] == 0) {
                                $is_active = 0;
                            }

                            if ($is_active):
                                ?>


                                <span class="text-red">Details</span>

                                <div class="well">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6">
                                            <strong>ประเทศ:</strong> <?php echo isset($detail_widget['country']) && $detail_widget['country'] ? $detail_widget['country'] : $product_category_title_th ?>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <strong>ระยะเวลา:</strong> <?php echo isset($detail_widget['period']) && $detail_widget['period'] ? $detail_widget['period'] : '-' ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6">
                                            <strong>รวมมื้ออาหาร:</strong> <?php echo isset($detail_widget['meal']) && $detail_widget['meal'] ? $detail_widget['meal'] . ' มื้อ ' : '-' ?> <?php echo isset($detail_widget['freedom_meal']) && $detail_widget['freedom_meal'] ? '+ อิสระ ' . $detail_widget['freedom_meal'] . ' มื้อ ' : '' ?>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <strong>สายการบิน:</strong> <?php echo $airline_title_th != '' ? $airline_title_th : 'ไม่รวมตั๋วเครื่องบิน' ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6"><strong>ระดับโรงแรม:</strong>
                                            <?php
                                            if (isset($detail_widget['hotel_star']) && $detail_widget['hotel_star']) {
                                                for ($i = 1; $i <= $detail_widget['hotel_star']; $i++) {
                                                    ?>
                                                    <i class="glyphicon glyphicon-star" style="color:orange"></i>
                                                    <?php
                                                }
                                                echo ' หรือเทียบเท่า';
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <strong>ขึ้นเครื่อง:</strong> <?php echo isset($detail_widget['depart_from']) && $detail_widget['depart_from'] ? $detail_widget['depart_from'] : '-' ?>
                                        </div>
                                    </div>
                                    <?php $product_period = $this->session->userdata('lang') == 'en' ? $product_period_en : $product_period_th; ?>
                                    <?php if ($product_period): ?>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6"><strong>ช่วงเวลาเดินทาง:</strong>
                                                <?php echo $product_period; ?>
                                            </div>

                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="product-price-container" itemprop="offers" itemscope
                             itemtype="http://schema.org/Offer">
                            <div class="row">
                                <?php
                                if ($product_type != 'ticket' && $product_type != 'pass') {
                                    ?>
                                    <div class="col-sm-6 <?php echo $product_pdf ? '' : 'col-sm-offset-3' ?>">
                                        <?php echo lang('starting_price') ?>

                                        <?php if ($product_start_price == 0): ?>
                                            <br><span itemprop="price"><?php echo lang('negotiate') ?></span>
                                        <?php else: ?>
                                            <br>

                                            <span
                                                itemprop="price"><?php echo number_format($product_start_price); ?><?php lang('baht') ?></span>
                                                <?php
                                                if ($product_discount_price) {
                                                    echo '<br/><span style="color:#bbb;font-size:13px;">จากราคาปกติ <del>' . number_format($product_discount_price) . '</del> ' . lang('baht') . '/' . lang('people_unit') . '</span>';
                                                }
                                                ?>
                                                <?php echo ($product_type != 'ticket' && $product_type != 'ticket_hotel' && $product_type != 'travel_package') ? '<br/><i style="font-size:12px;font-style: italic">(' . lang('extra_cost_are_included') . ')</i>' : '<br/><i style="font-size:12px;font-style: italic">(กรุณาดูรายละเอียดตามเงื่อนไข)</i>' ?>
                                            <?php endif; ?>
                                        <a href="https://line.me/R/ti/p/%40xri9106a" target="_blank"><img
                                                src="./assets/img/line_contact.png" title="ติดต่อสอบถามทาง Line"/></a>
                                    </div>
                                    <?php
                                }

                                if ($product_type != 'pass') {
                                    ?>
                                    <div class="col-sm-6">
                                        <?php if ($member['member_role'] == 'agent'): ?>
                                            <a href="<?php echo base_url() ?>b2b_order?product_id=<?php echo $product_id ?>"
                                               class="btn btn-warning" style="height:auto;margin-bottom:20px;"><i
                                                    class="glyphicon glyphicon-import"></i> Add to B2B Order</a>
                                            <?php endif; ?>
                                            <?php if ($product_pdf && $member['member_role'] != 'agent'): ?>
                                            <a href="<?php echo $product_pdf ?>" target="_balnk"><img
                                                    src="<?php echo base_url() ?>assets/img/pdf.png"></a>
                                            <?php endif; ?>
                                            <?php if ($product_doc && $member['member_role'] == 'agent'): ?>
                                            <a href="<?php echo $product_doc ?>" target="_balnk"><img
                                                    src="<?php echo base_url() ?>assets/img/doc.png"></a>
                                            <?php endif; ?>
                                    </div>                                
                                    <?php
                                }
                                ?>
                            </div>
                        </div>

                        <style>
                            .bookingBtn.red {
                                background: #bd1e2c;
                            }
                        </style>



                        <?php
                        if ($product_type != 'ticket' && $product_type != 'pass') {
                            ?>
                            <div class="trip-info-box">
                                <h3><span class="text-blue">แชร์</span> <span class="text-red">สินค้านี้</span></h3>

                                <div class="addthis_native_toolbox"></div>


                                <script type="text/javascript"
                                src="//media.line.me/js/line-button.js?v=20140411"></script>
                                <script type="text/javascript">
        new media_line_me.LineButton({"pc": true, "lang": "en", "type": "a"});</script>


                                <!--<div class="fb-like" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>-->
                            </div>

                            <div class="sku-container">

                                <?php
                                /*
                                  $stock_depart = array();
                                  $stock_arrive = array();
                                  if ($product_type == 'ticket')
                                  {
                                  foreach ($stocks as $s)
                                  {
                                  if (isset($s->product_stock_depart_at) && !isset($s->product_stock_depart_at))
                                  {
                                  $stock_depart[] = $s;
                                  }
                                  else if (!isset($s->product_stock_depart_at) && isset($s->product_stock_depart_at))
                                  {
                                  $stock_arrive[] = $s;
                                  }
                                  }
                                  }
                                 *
                                 */
                                ?>

                                <div class="row">   
                                    <div class="col-lg-12">

                                        <?php if ($member['member_role'] == 'agent'): ?>
                                            <h3 style="margin-bottom:20px;"><span
                                                    class="text-blue">AVAILABLE FOR B2B ORDER</span>
                                            </h3>

                                        <?php else: ?>
                                            <h3 style="margin-bottom:20px;"><span class="text-blue">BOOKING</span></h3>
                                        <?php endif ?>

                                        <?php if (count($stocks) && ($is_visible || (!$is_visible && $is_force_static_visible))): ?>
                                            <div class="table-responsive">
                                                <table class="table sku-table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <?php if ($member['member_role'] != 'agent'): ?>
                                                                <th>Booking</th>


                                                            <?php endif; ?>

                                                            <th>Seat Available</th>
                                                            <th>Month</th>
                                                            <th>Period</th>
                                                            <th>Depart</th>
                                                            <th>Return</th>
                                                            <th>Airline</th>
                                                            <!--<th>Flight No.</th>-->
                                                            <!--<th>Hotel</th>-->
                                                            <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($stocks as $s) : ?>
                                                            <?php
                                                            if ($member['member_role'] == 'agent' && ($s->product_stock_total - $s->product_stock_booked) == 0) {
                                                                continue;
                                                            }
                                                            ?>
                                                            <tr>
                                                                <?php if ($member['member_role'] != 'agent'): ?>
                                                                    <td>
                                                                        <?php if ($s->product_stock_total - $s->product_stock_booked != 0 && $is_sold == 0): ?>
                                                                            <?php
                                                                            if (is_loggedin()) {
                                                                                $booking_url = base_url('product/book/' . $product_id . '/' . $s->product_stock_id);
                                                                            } else {
                                                                                $booking_url = base_url('member/login?redirect=' . base64_encode(base_url('product/book/' . $product_id . '/' . $s->product_stock_id)));
                                                                            }
                                                                            ?>
                                                                            <a class="bookingBtn"
                                                                               href="<?php echo $booking_url; ?>">
                                                                                <i class="glyphicons x-small log_book"></i> <?php echo lang('book') ?>
                                                                            </a>
                                                                        <?php else: ?>
                                                                            <a class="bookingBtn red"
                                                                               href="javascript:void(0)"
                                                                               style="cursor:default">
                                                                                <i class="glyphicons x-small remove_2"></i> <?php echo lang('full') ?>
                                                                            </a>
                                                                        <?php endif ?>

                                                                        <?php
                                                                        if ($member['member_role'] == 'admin') {
                                                                            ?>
                                                                            <br/>
                                                                            <a target="_blank"
                                                                               href="./backend/stock/edit/<?php echo $s->product_stock_id ?>/<?php echo $product_id ?>"
                                                                               target="_blank" class="btn btn-link"><i
                                                                                    class="glyphicon glyphicon-pencil"></i></a>
                                                                                <?php
                                                                            }
                                                                            ?>

                                                                    </td>
                                                                <?php endif ?>


                                                                <td>
                                                                    <?php
                                                                    if (!$is_sold) {
                                                                        echo $s->product_stock_total - $s->product_stock_booked;
                                                                    } else {
                                                                        echo '0';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo date('F', strtotime($s->product_stock_depart_at)); ?>
                                                                </td>
                                                                <td>
                                                                    <?php if (date('M', strtotime($s->product_stock_depart_at)) == date('M', strtotime($s->product_stock_arrive_at)) && date('Y', strtotime($s->product_stock_depart_at)) == date('Y', strtotime($s->product_stock_arrive_at))): ?>
                                                                        <?php echo date('j', strtotime($s->product_stock_depart_at)); ?>-<?php echo date('j', strtotime($s->product_stock_arrive_at)); ?><?php echo date('M Y', strtotime($s->product_stock_depart_at)); ?>
                                                                    <?php else: ?>
                                                                        <?php echo date('j M Y', strtotime($s->product_stock_depart_at)); ?>-<?php echo date('j M Y', strtotime($s->product_stock_arrive_at)); ?>
                                                                    <?php endif ?>


                                                                    <?php
                                                                    if (isset($s->product_stock_period) && $s->product_stock_period) {
                                                                        ?>
                                                                        <div class="text-blue"
                                                                             style="font-weight:bold;font-size:12px;">
                                                                            (<?php echo $s->product_stock_period ?>)
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <b><?php echo $s->product_stock_flight_no_go != '' ? $s->product_stock_flight_no_go : '-'; ?></b><br/>
                                                                    <?php if (isset($s->product_stock_depart_at)): ?>

                                                                        <?php echo date('H:i', strtotime($s->product_stock_depart_at)); ?><?php if (isset($s->product_stock_depart_duration) && strtotime($s->product_stock_depart_duration) > 0): ?>-<?php echo date('H:i', strtotime($s->product_stock_depart_duration)); ?>
                                                                            <?php
                                                                            echo date('j M Y', strtotime($s->product_stock_depart_at)) != date('j M Y', strtotime($s->product_stock_depart_duration)) ?
                                                                                    '<b>+' . ceil((strtotime(date('j M Y', strtotime($s->product_stock_depart_duration))) - strtotime(date('j M Y', strtotime($s->product_stock_depart_at)))) / 86400) : '</b>'
                                                                            ?>
                                                                        <?php endif ?>
                                                                    <?php else: ?>
                                                                        -
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <b><?php echo $s->product_stock_flight_no_return != '' ? $s->product_stock_flight_no_return : '-'; ?></b><br/>
                                                                    <?php if (isset($s->product_stock_arrive_at)): ?><?php echo date('H:i', strtotime($s->product_stock_arrive_at)); ?><?php if (isset($s->product_stock_arrive_duration) && strtotime($s->product_stock_arrive_duration) > 0): ?>-<?php echo date('H:i', strtotime($s->product_stock_arrive_duration)); ?>
                                                                            <?php
                                                                            echo date('j M Y', strtotime($s->product_stock_arrive_at)) != date('j M Y', strtotime($s->product_stock_arrive_duration)) ?
                                                                                    '<b>+' . ceil((strtotime(date('j M Y', strtotime($s->product_stock_arrive_duration))) - strtotime(date('j M Y', strtotime($s->product_stock_arrive_at)))) / 86400) : '</b>'
                                                                            ?>
                                                                        <?php endif ?>
                                                                    <?php else: ?>
                                                                        -
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><img src="<?php echo $s->airline_thumb ?>"
                                                                         title="<?php echo $s->airline_title_th ?>"
                                                                         style="width:50px"/></td>

                                                                                                                    <!--<td><a href="<?php echo $s->hotel_url; ?>" target="blank"><?php echo $this->session->userdata('lang') == 'en' ? $s->hotel_title_en : $s->hotel_title_th; ?></a></td>-->
                                                                <td>
                                                                    <?php
                                                                    if ($s->product_stock_to_discount) {
                                                                        echo '<div style="font-size:12px;color:#bbb">ปกติ <del>' . number_format($s->product_stock_to_discount) . '</del> ' . lang('baht') . '</div>';
                                                                    }
                                                                    ?>
                                                                    <b style="font-size:15px;"><?php echo $s->product_stock_price; ?>
                                                                        THB</b>
                                                                    <?php if ($member['member_role'] != 'agent'): ?>

                                                                        <?php if ($s->is_early_bird == 1): ?>
                                                                            <br/><span
                                                                                class="label early-bird">Early Bird</span>
                                                                            <?php endif; ?>
                                                                            <?php if ($s->is_promotion == 1): ?>
                                                                            <br/><span
                                                                                class="label promotion">Promotion</span>
                                                                            <?php endif; ?>


                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>

                                        <div
                                            class="alert alert-info"><?php echo lang('non_booking_description') ?></div>


                                        <?php if ($member['member_role'] == 'agent'): ?>

                                            <a href="<?php echo base_url() ?>b2b_order?product_id=<?php echo $product_id ?>"
                                               class="btn btn-warning btn-block btn-large"
                                               style="height:auto;margin-bottom:20px;"><i
                                                    class="glyphicon glyphicon-import"></i> Add to B2B Order</a>
                                            <?php endif; ?>
                                    </div>
                                </div>

                            </div>
                            <?php
                        }
                        ?>
                    </div>


                    <div class="product-details-container">

                        <div class="tab">

                            <ul class="nav nav-tabs" role="tablist"
                                style="margin-left:0;background:transparent;height:auto">
                                <li role="presentation" class="active"><a href="#product-detail" role="tab"
                                                                          data-toggle="tab"><?php echo lang('trip_detail') ?></a>
                                </li>

                                <?php
                                if ($product_type != 'pass') {
                                    ?>  
                                    <li role="presentation"><a href="#product-condition" role="tab" data-toggle="tab">เงื่อนไขสินค้า</a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content"
                                 style="padding:20px;border:1px solid #ddd;border-top:0;background:white">
                                <div role="tabpanel" class="tab-pane active" id="product-detail">
                                    <?php
                                    $product_summarize = ($lang == 'th' ? $product_summarize_th : $product_summarize_en);
                                    ?>
                                    <?php if ($product_summarize): ?>
                                        <h2 class="trip-details">สรุปการเดินทาง</h2>
                                        <table class="table sku-table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="vertical-align:middle;background: #20409a; color: white;font-weight:bold"
                                                        rowspan="2">
                                                        Day
                                                    </th>
                                                    <th style="vertical-align:middle;background: #20409a; color: white;font-weight:bold;"
                                                        rowspan="2">
                                                        Highlight
                                                    </th>
                                                    <th style="background: #20409a; color: white;font-weight:bold"
                                                        colspan="3">
                                                        Meal
                                                    </th>
                                                    <th style="vertical-align:middle;background: #20409a; color: white;font-weight:bold"
                                                        rowspan="2">
                                                        Hotel<br/>

                                                        <div style="font-weight:normal;font-size:12px;">*หรือเทียบเท่า</div>
                                                    </th>
                                                </tr>
                                                <tr>


                                                    <th style="background: #20409a; color: white;font-weight:bold">B</th>
                                                    <th style="background: #20409a; color: white;font-weight:bold">L</th>
                                                    <th style="background: #20409a; color: white;font-weight:bold">D</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $summarize_row = explode(';', $product_summarize);
                                                $day = 0;
                                                foreach ($summarize_row as $row) {
                                                    if (trim($row) == '') {
                                                        continue;
                                                    }

                                                    $row_array = explode('|', $row);
                                                    // if (trim($row_array[0]) == '') continue;
                                                    $day++;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $day ?></td>
                                                        <td style="text-align:left"><?php echo $row_array[0] ?></td>
                                                        <td><?php echo $row_array[1][0] == '1' ? '<i class="glyphicon glyphicon-ok" style="color:green"></i>' : '<i class="glyphicon glyphicon-remove" style="color:red"></i>' ?></td>
                                                        <td><?php echo $row_array[1][1] == '1' ? '<i class="glyphicon glyphicon-ok" style="color:green"></i>' : '<i class="glyphicon glyphicon-remove" style="color:red"></i>' ?></td>
                                                        <td><?php echo $row_array[1][2] == '1' ? '<i class="glyphicon glyphicon-ok" style="color:green"></i>' : '<i class="glyphicon glyphicon-remove" style="color:red"></i>' ?></td>
                                                        <td>
                                                            <?php if (isset($row_array[2]) && $row_array[2]): ?>
                                                                <?php
                                                                $hotel = explode(':', $row_array[2]);
                                                                ?>
                                                                <?php
                                                                if ($hotel[0] > 0)
                                                                    for ($i = 1; $i <= $hotel[0]; $i++) {
                                                                        echo '<i class="glyphicon glyphicon-star" style="color:orange"></i>';
                                                                    }
                                                                if ($hotel[0] != (int) $hotel[0]) {
                                                                    echo '<i class="glyphicon glyphicon-star half" style="color:orange"></i>';
                                                                }
                                                                ?>
                                                                <br/><?php echo $hotel[1] ?>
                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                            <style>

                                                                .glyphicon-star.half {
                                                                    position: relative;
                                                                }

                                                                .glyphicon-star.half:before {
                                                                    position: relative;
                                                                    z-index: 9;
                                                                    width: 47%;
                                                                    display: block;
                                                                    overflow: hidden;
                                                                }

                                                                .glyphicon-star.half:after {
                                                                    content: '\e006';
                                                                    position: absolute;
                                                                    z-index: 8;
                                                                    color: #bdc3c7;
                                                                    top: 0;
                                                                    left: 0;
                                                                }

                                                            </style>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    <?php endif; ?>
             <!--   <h2 class="trip-details"><?php //echo lang('trip_detail');     ?></h2>   -->
                                    <!-- For Ticket Manipulation -->
                                    <?php
                                    if ($product_type == 'ticket'):

                                        function manipulate_ibe_button($string) {

                                            $string = str_replace(array('[[', ']]'), array('[ticket]', '[/ticket]'), $string);
                                            $startPoint = '[ticket]';
                                            $endPoint = '[/ticket]';

                                            preg_match_all('|(' . preg_quote($startPoint) . ')(.*)(' . preg_quote($endPoint) . ')|U', $string, $matches, PREG_PATTERN_ORDER);
                                            if (isset($matches[2][0])) {

                                                $to_replace_list = array();
                                                $to_replace_count = 0;
                                                foreach ($matches[2] as $match) {
                                                    $value_list = explode('|', $match);
                                                    foreach ($value_list as $value) {
                                                        $attri_list = explode('=', $value);
                                                        if (isset($attri_list[0]) && isset($attri_list[1])) {
                                                            $to_replace_list[$to_replace_count][$attri_list[0]] = $attri_list[1];
                                                        }
                                                    }
                                                    $to_replace_list[$to_replace_count]['replace'] = $startPoint . $match . $endPoint;
                                                    $to_replace_count++;
                                                }
                                                //echo '<pre>';
                                                //print_r($to_replace_list);
                                                // Do Replace //

                                                foreach ($to_replace_list as $to_replace) {
                                                    $attri_string = '';
                                                    if (isset($to_replace['from'])) {
                                                        $attri_string .= ' data-ibe-from="' . $to_replace['from'] . '" ';
                                                    }
                                                    if (isset($to_replace['to'])) {
                                                        $attri_string .= ' data-ibe-to="' . $to_replace['to'] . '" ';
                                                    }
                                                    if (isset($to_replace['airline'])) {
                                                        $attri_string .= ' data-ibe-airline="' . $to_replace['airline'] . '" ';
                                                    }

                                                    if (isset($to_replace['class'])) {
                                                        $attri_string .= ' data-ibe-class="' . $to_replace['class'] . '" ';
                                                    }


                                                    if (!isset($_GET['ynotfly'])) {
                                                        $replace = '<a href="javascript:void(0)" target="_blank" class="popup-ibe btn btn-primary" ' . $attri_string . '>เช็คราคา</a>';
                                                    } else {
                                                        $replace = '<a href="https://www.ynotfly.com/" class="btn btn-primary">เช็คราคา</a>';
                                                    }
                                                    $string = str_replace($to_replace['replace'], $replace, $string);
                                                }
                                                // echo $string;
                                            }
                                            return $string;
                                        }
                                        ?>
                                        <script>
                                            $(function () {
                                                $('.popup-ibe').click(function (e) {
                                                    $('select[name="DEPARTCITY"]').val($(this).attr('data-ibe-from'));
                                                    $('input[name="RETURNCITY"]').val($(this).attr('data-ibe-to'));
                                                    $('select[name="AIRLINE1"]').val($(this).attr('data-ibe-airline'));
                                                    if ($(this).attr('data-ibe-class'))
                                                    {
                                                        $('select[name="CABINCLASS"]').val($(this).attr('data-ibe-class'));
                                                    } else {
                                                        $('select[name="CABINCLASS"]').val(3);
                                                    }
                                                    $('#myModal').modal({show: true});
                                                });
                                            });
                                        </script>
                                        <style>
                                            .service-tabs .tab-content {
                                                border-top: 1px solid #a9a9a9
                                            }

                                            #vendorTab {
                                                padding-bottom: 0;
                                                margin-bottom: 0;
                                            }

                                            .nav.nav-tabs {
                                                display: none;
                                            }
                                        </style>

                                        <?php echo $lang == 'th' ? manipulate_ibe_button($product_description_th) : manipulate_ibe_button($product_description_en); ?>
                                    <?php else: ?>

                                        <?php echo $lang == 'th' ? $product_description_th : $product_description_en; ?>
                                    <?php endif; ?>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="product-condition">
                                    <h2>เงื่อนไขสินค้า</h2>
                                    <?php
                                    $condition = ($lang == 'th' ? $product_condition_th : $product_condition_en);
                                    if ($condition) {
                                        echo $condition;
                                    } else {
                                        ?>
                                        <div class="alert alert-info">
                                            หากคุณต้องการข้อมูลเพิ่มเติมเกี่ยวกับเงื่อนไขของสินค้านี้กรุณาติดต่อเราได้ที่
                                            <b>Call Center.</b> 02-792-9292
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>

                            </div>
                        </div>


                    </div>


                </div>
            </div>
        </div>
        <!-- Articles -->
        <div class="col-md-3">
            <div class="trip-info">

                <?php
                if ($member['member_role'] == 'admin') {
                    ?>
                    <a target="_blank" href="./backend/product/edit/<?php echo $product_id ?>" class="btn btn-danger"><i
                            class="glyphicons pencil small white"></i> แก้ไขสินค้านี้</a>
                    <a target="_blank" href="./backend/product/view/<?php echo $product_id ?>" class="btn btn-info"><i
                            class="glyphicons pencil small white"></i> แก้ไข SKU</a>
                    <hr/>
                    <?php
                }
                ?>

            </div>

            <!-- Relate Product -->
            <?php if ($related_product): ?>
                <div class="trip-info">
                    <h3><span class="text-blue">RELATED</span> <span class="text-red">PRODUCTS</span></h3>

                    <?php
                    foreach ($related_product as $product) :
                        switch ($product->product_type) {
                            case 'tour_package':
                                $package_type = 'tour-package';
                                $package_url = 'tour';
                                break;
                            case 'travel_package':
                                $package_type = 'travel-package';
                                $package_url = 'travel';
                                break;
                            case 'ticket':
                                $package_type = 'ticket-package';
                                $package_url = 'ticket';
                                break;
                            case 'ticket_hotel':
                                $package_type = 'ticket-hotel';
                                $package_url = 'ticket-hotel';
                                break;
                            case 'others':
                                $package_type = 'other-package';
                                $package_url = 'others';
                                break;
                            case 'pass':
                                $package_type = 'pass';
                                $package_url = 'pass';
                        }
                        ?>
                        <!-- Product Block -->
                        <div class="trip-info-box">
                            <a style="background:url('<?php echo get_thumb($product->product_thumb); ?>') center center; background-size:cover;"
                               class="article-img"
                               href="<?php echo base_url($package_url . '/v/' . $product->product_id) . '/'; ?>"
                               title="<?php echo $this->session->userdata('lang') == 'en' ? $product->product_title_en : $product->product_title_th; ?>"></a>

                            <div class="article-details">
                                <a title="<?php echo $this->session->userdata('lang') == 'en' ? $product->product_title_en : $product->product_title_th; ?>"
                                   href="<?php echo base_url($package_url . '/v/' . $product->product_id); ?>/">
                                    <h4 style="font-size:13px;"><?php echo $this->session->userdata('lang') == 'en' ? $product->product_title_en : $product->product_title_th; ?></h4>
                                </a>

                                <p><?php
                                    if (isset($product->product_subtitle_th)) {
                                        echo $this->session->userdata('lang') == 'en' ? $product->product_subtitle_en : $product->product_subtitle_th;
                                    }
                                    ?></p>

                            </div>
                            <div class="product-price"
                                 style="float:none; padding: 0px; margin-left: 80px; margin-top: 10px;font-size:13px;">
                                     <?php if ($product->product_start_price == 0): ?>
                                    <span><?php echo lang('negotiate') ?></span>
                                <?php else: ?>
                                    เริ่มต้น
                                    <span><?php echo number_format($product->product_start_price); ?></span> <?php echo lang('baht') . '/' . lang('people_unit') ?>
                                    <?php
                                    if ($product->product_discount_price) {
                                        echo '<div style="color:#bbb;font-size:12px;font-style:italic">*ปกติ <del>' . number_format($product->product_discount_price) . '</del> ' . lang('baht') . '/' . lang('people_unit') . '</div>';
                                    }
                                    ?>
                                <?php endif; ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!-- Product Block End -->
                    <?php endforeach; ?>
                    <?php if (count($related_product) > 0) : ?>
                        <?php
                        $tag_link = array();
                        foreach ($tags as $tag) {
                            $tag_link[] = $tag->tag_name;
                        }
                        ?>
                        <div style="margin-top:20px;">
                            <a href="<?php echo base_url() ?>product?k=<?php echo implode(' ', $tag_link) ?>"
                               class="view-all">สินค้าที่เกี่ยวข้องทั้งหมด <i class="glyphicons chevron-right"></i></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <!-- Relate Product End -->


            <!-- Trip Info -->
            <div class="trip-info">
                <h3><span class="text-blue">TRIP</span> <span class="text-red">INFORMATION</span></h3>

                <?php if ($product_group_size != ''): ?>
                    <!-- Info -->
                    <div class="trip-info-box">
                        <h4>Group Size:</h4>
                        <span><?php echo $product_group_size; ?><?php echo $lang == 'th' ? 'คน' : 'People'; ?></span>
                    </div>
                    <!-- Info End -->
                <?php endif; ?>

                <?php if ($product_necessary_item != ''): ?>
                    <!-- Info -->
                    <div class="trip-info-box">
                        <h4>What to bring?</h4>
                        <span><?php echo $product_necessary_item; ?></span>
                    </div>
                    <!-- Info End -->
                <?php endif; ?>

                <?php if ($product_trip_style != ''): ?>
                    <!-- Info -->
                    <div class="trip-info-box">
                        <h4>Trip Style:</h4>
                        <span><?php echo $product_trip_style; ?></span>
                    </div>
                    <!-- Info End -->
                <?php endif; ?>

                <?php if ($product_pocket_money != 0): ?>
                    <!-- Info -->
                    <div class="trip-info-box">
                        <h4>Pocket Money (THB):</h4>
                        <span><?php echo $product_pocket_money; ?></span>
                    </div>
                    <!-- Info End -->
                <?php endif; ?>

                <!-- Info -->
                <?php if ($tags): ?>
                    <div class="trip-info-box">
                        <h4>Location:</h4>
                        <ul class="life-style-tags">
                            <?php foreach ($tags as $t): ?>
                                <li><a href="./product?k=<?php echo $t->tag_name; ?>"><?php echo $t->tag_name; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <!-- Info End -->
            </div>


            <!-- Trip Info End -->
            <?php if ($related_article): ?>
                <div class="article-container">
                    <h3><span class="text-blue">RELATED</span> <span class="text-red">ARTICLES</span></h3>
                    <?php foreach ($related_article as $article): ?>
                        <!-- Article Block -->
                        <div class="article small">
                            <a style="background:url('<?php echo get_thumb($article->article_thumb); ?>') center center; background-size:cover;"
                               class="article-img" href="#"></a>

                            <div class="article-details">
                                <a href="<?php echo base_url('article/v/' . $article->article_id); ?>/<?php echo $this->session->userdata('lang') == 'en' ? url_title($article->article_title_en) : url_title($article->article_title_th); ?>">
                                    <h4><?php echo $this->session->userdata('lang') == 'en' ? $article->article_title_en : $article->article_title_th; ?></h4>
                                </a>
                                <!--<span class="time-stamp">Last Edited: <?php echo date('d.m.y', $article->updated_at); ?></span>-->
                                <p><?php echo $this->session->userdata('lang') == 'en' ? $article->article_highlight_en : $article->article_highlight_th; ?></p>

                                <div class="hide-article-overflow"></div>
                            </div>
                        </div>
                        <!-- Article Block End -->
                    <?php endforeach ?>

                </div>
            <?php endif; ?>
        </div>
    </div>


</div>
<!-- Articles and Banners End-->
<!-- Relate Product -->
<?php /* if ($related_product): ?>
  <div class="recommend">
  <div class="container">
  <div>
  <h3 style="margin-bottom:20px;"><span class="text-blue">RELATED</span> <span class="text-red">PRODUCTS</span></h3>
  </div>
  <div class="row product-container">

  <?php
  foreach ($related_product as $product) {
  $item_view['product'] = $product;
  $item_view['product_tags'] = $related_product_tags;
  $this->load->view('frontend/product/product_item', $item_view);
  }
  ?>
  </div>
  <div class="header-text">
  <?php if (count($related_product) > 0) : ?>
  <?php
  $tag_link = array();
  foreach ($tags as $tag) {
  $tag_link[] = $tag->tag_name;
  }
  ?>

  <a href="<?php echo base_url() ?>product?k=<?php echo implode(' ', $tag_link) ?>" class="view-all"><?php echo lang('view_all_related_products') ?> <i class="glyphicons chevron-right"></i></a>
  <?php endif; ?>
  </div>
  </div>
  </div>
  <?php endif; */ ?>
<!-- Relate Product End -->

<script type="text/javascript">
    $(function () {
        var redirect_url = "<?php echo base_url(); ?>";
        var product_type = $('#product_type').val();
        var category = $('#category option:selected').val();
        var promotion = $('#promotion option:selected').val();
        $('select#product_type, select#category, select#promotion, select#sorting').change(function () {


            product_type = $('#product_type').val();
            category = $('#category').val();
            promotion = $('#promotion').val();
            sorting = $('#sorting').val();


            redirect_url += product_type + '/' + category + '/' + promotion + '/' + sorting;


            window.location = redirect_url;

        });


        var cache = {};
        $("#search_tag").autocomplete({
            minLength: 2,
            source: function (request, response) {
                var term = request.term;
                if (term in cache) {
                    response(cache[term]);
                    return;
                }

                $.getJSON("<?php echo base_url('tag/autocomplete'); ?>", request, function (data, status, xhr) {
                    cache[term] = data;
                    response(data);
                    toggle_search_box();
                });
            },
            close: function (event, ui) {
                toggle_search_box();
            }
        });

        function toggle_search_box() {
            if ($('#ui-id-1').css('display') == 'none') {
                $('#search_tag').removeClass('show_list');
            } else {
                $('#search_tag').addClass('show_list');
            }
        }

        $('#search_form').submit(function (e) {

            product_type = $('#product_type').val();
            category = $('#category').val();
            promotion = $('#promotion').val();
            sorting = $('#sorting').val();

            redirect_url += product_type + '/' + category + '/' + promotion + '/' + sorting;

            var k = $(this).find('input').val();

            if (k != '') {
                window.location = redirect_url + '?k=' + k;
            } else {
                window.location = redirect_url;
            }

            e.preventDefault();

        });

        //$('table').wrap('<div class="table-responsive"></div>');
        //$('table').css({'width':'100%'})
    });
</script>