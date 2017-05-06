<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('template');
        $this->template->set_template('backend');
        $this->load->library('carabiner');

        $this->load->model('stock_model');
        /*
         * check admin and return session data
         */
        if (!is_loggedin()) {
            redirect($this->router->reverseRoute('backend_login') . '?redirect=' . base64_encode(current_url()));
        }
        /* Check Permission */
        is_member_backend();
    }

    /**
     * Create member
     */
    function create($product_id = 0, $duplicate_id = 0) {
        if (!$product_id) {
            show_404();
        }

        if ($_POST) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('code', 'Code', 'trim|required');
            $this->form_validation->set_rules('due_date', 'Due Date', 'trim|required');
            $this->form_validation->set_rules('price', 'Price', 'trim|required');
            $this->form_validation->set_rules('discount-price', 'Unit Price to Discount', 'trim');
            $this->form_validation->set_rules('stock_total', 'Stock', 'trim|required');

            if ($this->input->post('kbank')) {
                $this->form_validation->set_rules('kbank_rate', 'Kbank rate', 'trim|required');
            }
            if ($this->input->post('bangkok_bank')) {
                $this->form_validation->set_rules('bangkok_bank_rate', 'Bangkok bank rate', 'trim|required');
            }

            if ($this->form_validation->run() === TRUE) {

                $this->load->model('product_model');
                $product_code = $this->product_model->get_product_code($product_id);

                if ($this->input->post('depart_date')) {
                    $depart_at = date("Y-m-d H:i:s", strtotime($this->input->post('depart_date') . ' ' . $this->input->post('depart_date_h') . ':' . $this->input->post('depart_date_i') . ':00'));
                } else {
                    $depart_at = null;
                }

                if ($this->input->post('arrive_date')) {
                    $arrive_at = date("Y-m-d H:i:s", strtotime($this->input->post('arrive_date') . ' ' . $this->input->post('arrive_date_h') . ':' . $this->input->post('arrive_date_i') . ':00'));
                } else {
                    $arrive_at = null;
                }
                if ($this->input->post('depart_landed_date')) {
                    $depart_landed_at = date("Y-m-d H:i:s", strtotime($this->input->post('depart_landed_date') . ' ' . $this->input->post('depart_landed_date_h') . ':' . $this->input->post('depart_landed_date_i') . ':00'));
                } else {
                    $depart_landed_at = null;
                }


                if ($this->input->post('arrive_landed_date')) {
                    $arrive_landed_at = date("Y-m-d H:i:s", strtotime($this->input->post('arrive_landed_date') . ' ' . $this->input->post('arrive_landed_date_h') . ':' . $this->input->post('arrive_landed_date_i') . ':00'));
                } else {
                    $arrive_landed_at = null;
                }

                //payment getway
                $payment_arr = array(
                    'direct' => $this->input->post('direct_bank'),
                    'bill_bank' => $this->input->post('bill_bank'),
                    'bill_bigc' => $this->input->post('bill_bigc'),
                    'bill_thp' => $this->input->post('bill_thp'),
                    'kbank' => $this->input->post('kbank'),
                    'kbank_rate' => $this->input->post('kbank_rate'),
                    'kbank_type' => $this->input->post('kbank_type'),
                    'bangkok_bank' => $this->input->post('bangkok_bank'),
                    'bangkok_rate' => $this->input->post('bangkok_bank_rate'),
                    'bangkok_type' => $this->input->post('bangkok_bank_type'),
                    'line_pay' => $this->input->post('line_pay'),
                    'line_pay_discount' => $this->input->post('line_pay_discount'),
                );

                $create_data = array(
                    'product_id' => $product_id,
                    'product_stock_code' => $this->input->post('code'),
                    'product_stock_due_date' => date("Y-m-d", strtotime($this->input->post('due_date'))),
                    'product_stock_depart_at' => $depart_at,
                    'product_stock_depart_duration' => $depart_landed_at,
                    'product_stock_arrive_at' => $arrive_at,
                    'product_stock_arrive_duration' => $arrive_landed_at,
                    'product_stock_airline_id' => $this->input->post('airline'),
                    'product_stock_flight_no_go' => $this->input->post('flight_no_go'),
                    'product_stock_flight_no_return' => $this->input->post('flight_no_return'),
                    'product_stock_flight_route_go' => $this->input->post('flight_route_go'),
                    'product_stock_flight_route_return' => $this->input->post('flight_route_return'),
                    'product_stock_period' => $this->input->post('period'),
                    'product_stock_hotel_id' => $this->input->post('hotel'),
                    'product_stock_single_price' => str_replace(',', '', $this->input->post('single_price')),
                    'product_stock_price' => str_replace(',', '', $this->input->post('price')),
                    'product_stock_to_discount' => str_replace(',', '', $this->input->post('discount-price')),
                    'product_stock_advanced_price' => str_replace(',', '', $this->input->post('advanced_price')),
                    'product_stock_booked' => $this->input->post('stock_booked'),
                    'product_stock_total' => $this->input->post('stock_total'),
                    'product_stock_min_book' => $this->input->post('min_book'),
                    'payment_method' => serialize($payment_arr),
                    'product_stock_add_on' => $this->input->post('add_on')
                );

                $create_data['is_active'] = ($this->input->post('is_active') != '') ? 1 : 0;
                $create_data['is_fully_payment'] = ($this->input->post('is_full_payment') != '') ? 1 : 0;
                $create_data['is_direct_flight'] = ($this->input->post('is_direct_flight') != '') ? 1 : 0;
                $create_data['is_promotion'] = ($this->input->post('is_promotion') != '') ? 1 : 0;
                $create_data['is_early_bird'] = ($this->input->post('is_early_bird') != '') ? 1 : 0;

                $a_commission = explode(PHP_EOL, $this->input->post('commission'));
                $a_commission_result = array();
                foreach ($a_commission as $row) {
                    if (!$row)
                        continue;
                    $key_value = explode('=', $row);
                    $a_commission_result[$key_value[0]] = explode(',', $key_value[1]);
                }
                $create_data['product_stock_commission'] = serialize($a_commission_result);
                //$create_data['product_stock_sale_commission'] = str_replace(',', '', $this->input->post('sale_commission'));
                $insert_id = $this->stock_model->stock_create($create_data);
                if ($insert_id) {

                    redirect('backend/stock/create/' . $product_id . '/' . $insert_id . '?status=' . $product_code . '-' . $this->input->post('code'));
                }
            } else {
                echo validation_errors();
                exit;
            }
        } else {

            if ($duplicate_id) {
                $view_data = $this->stock_model->stock_get($duplicate_id);
                $view_data['product_stock_commission'] = $this->stock_model->commission_display($view_data['product_stock_commission']);
            }

            $this->load->model('airline_model');
            $view_data['airlines'] = $this->airline_model->airline_all();

            $this->load->model('hotel_model');
            $view_data['hotels'] = $this->hotel_model->hotel_all();
            $view_data['product_id'] = $product_id;

            $this->load->model('product_model');
            $view_data['product_type'] = $this->product_model->get_product_type($product_id);

            $view_data['product_code'] = $this->product_model->get_product_code($product_id);

            $this->load->helper('form');
            $this->template->write_view('side_menu', 'backend/side_menu');
            $this->template->write_view('content', 'backend/stock/stock_create', $view_data);
            $this->template->render();
        }
    }

    /**
     * Edit member
     */
    function edit($stock_id = 0, $product_id = 0) {


        if ($_POST) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('code', 'Code', 'trim|required');
            $this->form_validation->set_rules('due_date', 'Due Date', 'trim|required');
            $this->form_validation->set_rules('price', 'Price', 'trim|required');
            $this->form_validation->set_rules('stock_total', 'Stock', 'trim|required');

            if ($this->input->post('kbank')) {
                $this->form_validation->set_rules('kbank_rate', 'Kbank rate', 'trim|required');
            }
            if ($this->input->post('bangkok_bank')) {
                $this->form_validation->set_rules('bangkok_bank_rate', 'Bangkok bank rate', 'trim|required');
            }

            if ($this->input->post('depart_date')) {
                $depart_at = date("Y-m-d H:i:s", strtotime($this->input->post('depart_date') . ' ' . $this->input->post('depart_date_h') . ':' . $this->input->post('depart_date_i') . ':00'));
            } else {
                $depart_at = null;
            }

            if ($this->input->post('arrive_date')) {
                $arrive_at = date("Y-m-d H:i:s", strtotime($this->input->post('arrive_date') . ' ' . $this->input->post('arrive_date_h') . ':' . $this->input->post('arrive_date_i') . ':00'));
            } else {
                $arrive_at = null;
            }

            if ($this->input->post('depart_landed_date')) {
                $depart_landed_at = date("Y-m-d H:i:s", strtotime($this->input->post('depart_landed_date') . ' ' . $this->input->post('depart_landed_date_h') . ':' . $this->input->post('depart_landed_date_i') . ':00'));
            } else {
                $depart_landed_at = null;
            }


            if ($this->input->post('arrive_landed_date')) {
                $arrive_landed_at = date("Y-m-d H:i:s", strtotime($this->input->post('arrive_landed_date') . ' ' . $this->input->post('arrive_landed_date_h') . ':' . $this->input->post('arrive_landed_date_i') . ':00'));
            } else {
                $arrive_landed_at = null;
            }

            //payment getway
            $payment_arr = array(
                'direct' => $this->input->post('direct_bank'),
                'bill_bank' => $this->input->post('bill_bank'),
                'bill_bigc' => $this->input->post('bill_bigc'),
                'bill_thp' => $this->input->post('bill_thp'),
                'kbank' => $this->input->post('kbank'),
                'kbank_rate' => $this->input->post('kbank_rate'),
                'kbank_type' => $this->input->post('kbank_type'),
                'bangkok_bank' => $this->input->post('bangkok_bank'),
                'bangkok_rate' => $this->input->post('bangkok_bank_rate'),
                'bangkok_type' => $this->input->post('bangkok_bank_type'),
                'line_pay' => $this->input->post('line_pay'),
                'line_pay_discount' => $this->input->post('line_pay_discount'),
            );

            if ($this->form_validation->run() === TRUE) {
                $edit_data = array(
                    'product_stock_code' => $this->input->post('code'),
                    'product_stock_due_date' => date("Y-m-d", strtotime($this->input->post('due_date'))),
                    'product_stock_depart_at' => $depart_at,
                    'product_stock_depart_duration' => $depart_landed_at,
                    'product_stock_arrive_at' => $arrive_at,
                    'product_stock_arrive_duration' => $arrive_landed_at,
                    'product_stock_flight_no_go' => $this->input->post('flight_no_go'),
                    'product_stock_flight_no_return' => $this->input->post('flight_no_return'),
                    'product_stock_flight_route_go' => $this->input->post('flight_route_go'),
                    'product_stock_flight_route_return' => $this->input->post('flight_route_return'),
                    'product_stock_period' => $this->input->post('period'),
                    'product_stock_single_price' => str_replace(',', '', $this->input->post('single_price')),
                    'product_stock_price' => str_replace(',', '', $this->input->post('price')),
                    'product_stock_to_discount' => str_replace(',', '', $this->input->post('discount-price')),
                    'product_stock_advanced_price' => str_replace(',', '', $this->input->post('advanced_price')),
                    'product_stock_booked' => $this->input->post('stock_booked'),
                    'product_stock_total' => $this->input->post('stock_total'),
                    'product_stock_min_book' => $this->input->post('min_book'),
                    'payment_method' => serialize($payment_arr),
                    'product_stock_add_on' => $this->input->post('add_on')
                );

                if ($this->input->post('airline') != '') {
                    $edit_data['product_stock_airline_id'] = $this->input->post('airline') ? $this->input->post('airline') : NULL;
                }

                if ($this->input->post('hotel') != '') {
                    $edit_data['product_stock_hotel_id'] = $this->input->post('hotel') ? $this->input->post('hotel') : NULL;
                }

                $edit_data['is_active'] = ($this->input->post('is_active') != '') ? 1 : 0;
                $edit_data['is_fully_payment'] = ($this->input->post('is_full_payment') != '') ? 1 : 0;
                $edit_data['is_direct_flight'] = ($this->input->post('is_direct_flight') != '') ? 1 : 0;
                $edit_data['is_promotion'] = ($this->input->post('is_promotion') != '') ? 1 : 0;
                $edit_data['is_early_bird'] = ($this->input->post('is_early_bird') != '') ? 1 : 0;

                $a_commission = explode(PHP_EOL, $this->input->post('commission'));
                $a_commission_result = array();
                foreach ($a_commission as $row) {
                    if (!$row)
                        continue;
                    $key_value = explode('=', $row);
                    $a_commission_result[$key_value[0]] = explode(',', $key_value[1]);
                }
                $edit_data['product_stock_commission'] = serialize($a_commission_result);
                //$edit_data['product_stock_sale_commission'] = str_replace(',', '', $this->input->post('sale_commission'));
                $result = $this->stock_model->stock_edit($stock_id, $edit_data);
                if ($result) {
                    redirect('backend/product/view/' . $product_id);
                }
            } else {
                echo validation_errors();
                exit;
            }
        } else {
            
        }



        $view_data = $this->stock_model->stock_get($stock_id);
        $view_data['product_stock_commission'] = $this->stock_model->commission_display($view_data['product_stock_commission']);

        $view_data['payment_method'] = unserialize($view_data['payment_method']);

        $this->load->model('airline_model');
        $view_data['airlines'] = $this->airline_model->airline_all();

        $this->load->model('hotel_model');
        $view_data['hotels'] = $this->hotel_model->hotel_all();

        $this->load->model('product_model');
        $view_data['product_type'] = $this->product_model->get_product_type($view_data['product_id']);

        $this->load->helper('form');
        $this->template->write_view('side_menu', 'backend/side_menu');
        $this->template->write_view('content', 'backend/stock/stock_edit', $view_data);
        $this->template->render();
    }

    function delete($id) {

        $this->stock_model->delete($id);
        return true;
    }

    function active($stock_id, $status) {
        $this->stock_model->stock_status($stock_id, $status);
        return TRUE;
    }

    function json_data($product_id, $archive = false) {


        $this->load->library('Datatables');


        //$buttons = '<a title="ลบรายการนี้" class="editPrice editIcon pull-right tbl-delete-row" href="#" rel="' . base_url('backend/stock/delete') . '/$1" ><i class="icon-remove"></i></a>';
        $buttons = '<a title="สร้างรายการใหม่จากรายการนี้" class="editPrice editIcon pull-right" href="' . base_url('backend/stock/create') . '/' . $product_id . '/$1" ><i class="icon-copy"></i></a>';
        $buttons .= '<a title="แก้ไขรายการนี้" class="editPrice editIcon pull-right" href="' . base_url('backend/stock/edit') . '/$1/' . $product_id . '" rel="$1"  ><i class="icon-edit"></i></a>';
        $buttons .= '<a title="ดูรายการสั่งซื้อทั้งหมดจากรายการนี้" class="editPrice editIcon pull-right" href="' . base_url('backend/order/index/' . $product_id) . '/$1/all" rel="$1"  ><i class="icon-search"></i></a>';
        $buttons .= '<a title="แสดงผลหรือซ่อนสต๊อคนี้" class="editPrice editIcon productControls pull-right tbl_active_btn active status_$2" href="#" rel="' . base_url('backend/stock/active') . '/$1/$2" ><i data-toggle="tooltip" data-placement="bottom" data-title="Active Stock" class="icon-play"></i><i data-toggle="tooltip" data-placement="bottom" data-title="Pause Product" class="icon-pause"></i></a>';
        $buttons .= '<a title="สร้างการสั่งซื้อจากรายการนี้" class="editPrice editIcon pull-right" href="' . base_url('backend/order/create/' . $product_id) . '/$1" rel="$1"  ><i class="icon-calendar"></i></a>';

        $booked = '$1/$2';

        function format_date($date) {
            if (isset($date))
                return date('j M Y H:i', strtotime($date));
            else
                return '-';
        }

        function format_payment_method($str) {

            $payment_method = unserialize($str);

            if ($payment_method) {
                $return = '';

                if (isset($payment_method['direct'])) {
                    $return .= '- Direct Payment<br/>';
                }
                if (isset($payment_method['bill_bank'])) {
                    $return .= '- Bank Billpay<br/>';
                }
                if (isset($payment_method['bill_bigc'])) {
                    $return .= '- Big C Billpay<br/>';
                }
                if(isset($payment_method['bill_thp']))
                {
                    $return .= '- THP Billpay<br/>';
                }
                if (isset($payment_method['kbank'])) {
                    $return .= '- KBank GW (' . $payment_method['kbank_rate'] . ' ' . $payment_method['kbank_type'] . ')<br/>';
                }
                if (isset($payment_method['bangkok'])) {
                    $return .= '- BBL GW (' . $payment_method['bangkok_rate'] . ' ' . $payment_method['bangkok_type'] . ')<br/>';
                }
                if (isset($payment_method['line_pay']))
                {
                    $return .= '- Line Pay';
                    if (isset($payment_method['line_pay_discount']) && $payment_method['line_pay_discount'])
                    {
                        $return .= ' ('.number_format($payment_method['line_pay_discount']).' THB/Person)';
                    }
                }

                if (!$return) {
                    return 'Default';
                }


                return $return;
            } else {
                return 'Default';
            }
        }

        $this->datatables
                ->select('product_stocks.product_stock_id,product_stocks.is_active,product_stocks.product_stock_code,product_stocks.product_stock_due_date,'
                        . 'product_stocks.product_stock_booked,product_stocks.product_stock_total,product_stocks.product_stock_depart_at,product_stocks.product_stock_arrive_at,'
                        . 'airlines.airline_title_th,product_stocks.product_stock_single_price,'
                        . 'product_stocks.product_stock_advanced_price,product_stocks.product_stock_price,product_stocks.payment_method', FALSE)
                ->from('product_stocks')
                ->order_by('product_stocks.created_at DESC')
                ->where('product_stocks.product_id', $product_id);

        if (!$archive) {
            $this->datatables->where('(UNIX_TIMESTAMP(product_stocks.product_stock_due_date) > ' . time() . ' AND product_stocks.is_active=1)');
        } else {
            $this->datatables->where('(UNIX_TIMESTAMP(product_stocks.product_stock_due_date) <= ' . time() . ' OR product_stocks.is_active=0)');
        }
        $this->datatables->join('hotels', 'hotels.hotel_id = product_stocks.product_stock_hotel_id', 'left outer')
                ->join('airlines', 'airlines.airline_id = product_stocks.product_stock_airline_id', 'left outer')
                ->unset_column('product_stocks.product_stock_total')
                ->unset_column('product_stocks.product_stock_id')
                ->unset_column('product_stocks.is_active')
                ->edit_column('product_stocks.payment_method', '$1', 'format_payment_method(product_stocks.payment_method)')
                ->edit_column('product_stocks.product_stock_due_date', '$1', "format_date(product_stocks.product_stock_due_date)")
                ->edit_column('product_stocks.product_stock_depart_at', '$1', "format_date(product_stocks.product_stock_depart_at)")
                ->edit_column('product_stocks.product_stock_arrive_at', '$1', "format_date(product_stocks.product_stock_arrive_at)")
                ->edit_column('product_stocks.product_stock_advanced_price', '$1', "number_format(product_stocks.product_stock_advanced_price)")
                ->edit_column('product_stocks.product_stock_price', '$1', "number_format(product_stocks.product_stock_price)")
                ->edit_column('product_stocks.product_stock_single_price', '$1', "number_format(product_stocks.product_stock_single_price)")
                ->edit_column('product_stocks.product_stock_booked', $booked, 'product_stocks.product_stock_booked,product_stocks.product_stock_total')
                ->add_column('Tools', $buttons, 'product_stocks.product_stock_id , product_stocks.is_active')
        ;

        $data['result'] = $this->datatables->generate();
        //echo $this->db->last_query();die();
        //echo $this->db->last_query();
        //$data['result'] = str_replace(array('|P', '|G', '|+', '|-'), array('/Person</i>)', '/Group</i>)', ' (<i>+', ' (<i>-'), $data['result']);
        echo $data['result'];
    }

}
