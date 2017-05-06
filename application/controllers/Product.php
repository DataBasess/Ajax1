<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller
{

    var $list_per_page = 12;

    function __construct()
    {
        parent::__construct();

        $this->load->library('template');
        $this->load->library('carabiner');

        $this->load->model('product_model');
        $this->load->model('product_category_model');
        $this->load->language('wonderfulpackage', get_lang_full());
    }

    /**
     * List member
     */
    function index($product_type = 'all', $category_name = '', $promotion = '', $sorting = '')
    {


        $this->load->helper('image');
        //echo $product_type.'-'.$category_name.'-'.$promotion.'-'.$sorting;
        $keyword = $this->input->get('k');

        $view_data = array();
        $filter = array();

        if ($category_name == 'any')
            $category_name = '';
        if ($promotion == 'any')
            $promotion = '';


        if ($category_name != '') {
            $category_name = str_replace('-', ' ', $category_name);
            $category_id = $this->product_category_model->get_id_by_name($category_name);

            if ($category_id)
                $filter['product_category_id'] = $category_id;
        } else {
            $category_id = 0;
        }

        /* Redirect 301*/
        if ($category_name != '') {
            $cat_name = $this->product_category_model->get_title_by_namme($category_name);
            if ($cat_name['product_category_title_th'] == $category_name) {
                redirect(str_replace($category_name, $cat_name['product_category_title_en'], current_url() . '/'), 301);

            }
        }


        switch ($promotion) {
            case 'early_bird':
                $filter['is_early_bird'] = 1;
                break;
            case 'promotion':
                $filter['is_promotion'] = 1;
                break;
            case 'recommend':
                $filter['is_recommend'] = 1;
                break;
            case 'promotion_early';
                $filter['is_early_bird'] = 1;
                $filter['is_promotion'] = 1;
                break;
        }

        $this->load->library('user_agent');


        switch ($sorting) {
            case 'recent':
                $sorting = 'recent';
                $order_type = 'desc';
                break;
            case 'price':
                $sorting = 'price';
                $order_type = 'asc';
                break;
            case 'view':
                $sorting = 'view';
                $order_type = 'desc';
                break;
            default:
                //if ($product_type == 'ticket') {
                //$sorting = 'recent';
                //$order_type = 'desc';
                //} else {
                $sorting = 'price';
                $order_type = 'asc';
            //}
        }

        if ($product_type == 'ticket' || $product_type == 'all' || $product_type == 'pass') {

            $this->list_per_page = 999;
        } else {
            if ($this->agent->is_robot()) {
                $this->list_per_page = 40;
            }
        }

        $view_data = array(
            'products' => $this->product_model->all($product_type, $this->list_per_page, 0, $filter, $sorting, $order_type, $keyword),
            'category_id' => $category_id,
            'product_type' => $product_type,
            'promotion' => $promotion,
            'keyword' => $keyword
        );


        // Fix Inline //
        $view_data['product_category_title_th'] = '';
        if (isset($cat_name['product_category_title_th']))
            $view_data['product_category_title_th'] = $cat_name['product_category_title_th'];


        // ถ้าไม่มี Product ให้ดึงทุกอัน //
        if (!$view_data['products']) {
            $view_data['products_all'] = $this->product_model->all('all', 999, 0, $filter, $sorting, $order_type, $keyword);
        }

        if ($product_type == 'ticket') {
            $view_data['hide_lifestyle'] = TRUE;
            $view_data['member'] = $this->session->userdata('is_loggedin');
        }

        //echo $this->db->last_query();


        $product_ids = array();
        if ($view_data['products']) {
            foreach ($view_data['products'] as $r) {
                $product_ids[] = $r->product_id;
            }

            $view_data['tags'] = $this->product_model->get_tags_by_many_products($product_ids, 'lifestyle');
        }

        $lang = $this->session->userdata('lang') != '' ? $this->session->userdata('lang') : 'th';
        $this->load->config('page_custom_content', TRUE);
        $page_custom_content = $this->config->item($lang, 'page_custom_content');

        $product_type_re = 'product';
        switch ($product_type) {
            case 'tour_package':
                $product_type_re = 'tour';
                break;
            case 'travel_package':
                $product_type_re = 'travel';
                break;
            case 'cruise':
                $product_type_re = 'cruise';
                break;
            case 'ticket':
                $product_type_re = 'ticket';
                break;
            case 'ticket_hotel':
                $product_type_re = 'ticket-hotel';
                break;
            case 'others':
                $product_type_re = 'others';
                break;
            case 'pass':
                $product_type_re = 'pass';
                break;
            case 'hotel':
                $product_type_re = 'hotel';
                break;
        }

        $head_data['meta']['title'] = ($promotion ? ucfirst(str_replace('_', ' ', $promotion)) . '-' : '') . ($keyword != '' ? '"' . $keyword . '" ' : '') . $page_custom_content[$product_type_re]['title'] . ($this->uri->segment(2) && $this->uri->segment(2) != 'any' ? '-' . $this->uri->segment(2) : '') . ' | ' . $page_custom_content['main_web_title'];
        $key_explode = array(
            $page_custom_content[$product_type_re]['title'],
            ($keyword != '' ? $keyword : ''),
            ($this->uri->segment(2) && $this->uri->segment(2) != 'any' ? $this->uri->segment(2) : '')
        );
        $key_explode = array_filter($key_explode);

        $head_data['meta']['keywords'] = implode(',', $key_explode);
        $head_data['meta']['description'] = $page_custom_content[$product_type_re]['description'];


        $view_data['page_title'] = $page_custom_content[$product_type_re]['title'] . (isset($cat_name['product_category_title_th']) && $this->uri->segment(2) != 'any' ? $cat_name['product_category_title_th'] : '');
        $view_data['page_description'] = $page_custom_content[$product_type_re]['description'] . (isset($cat_name['product_category_title_th']) && $this->uri->segment(2) != 'any' ? 'สำหรับ' . $cat_name['product_category_title_th'] : '');
        if ($category_name && isset($page_custom_content[$product_type_re . '_' . $category_name]['caption'])) {
            $view_data['page_caption'] = $page_custom_content[$product_type_re . '_' . $category_name]['caption'];
        } else {
            $view_data['page_caption'] = $page_custom_content[$product_type_re]['caption'];
        }
        // For Your SEO //

        if ($category_name && isset($page_custom_content[$product_type_re . '_' . $category_name]['title'])) {

            $head_data['meta']['title'] = $page_custom_content[$product_type_re . '_' . $category_name]['title'] . ' | ' . $page_custom_content['main_web_title'];
            $head_data['meta']['description'] = $page_custom_content[$product_type_re . '_' . $category_name]['description'];
        }


        $view_data['product_categories'] = $this->product_category_model->product_category_all();


        if ($this->agent->is_mobile()) {
            $view_data['mobile'] = true;
        }

        // Load Slideshow for Ticket //
        if ($product_type == 'ticket') {
            $this->load->model('slideshow_model');
            $view_data['slideshows'] = $this->slideshow_model->slideshow_all('ticket');
        }
        // Load Slideshow for Tour //
        if ($product_type == 'tour_package') {
            $this->load->model('slideshow_model');
            $view_data['slideshows'] = $this->slideshow_model->slideshow_all('tour');
        }
        // Load Slideshow for Package //
        if ($product_type == 'travel_package') {
            $this->load->model('slideshow_model');
            $view_data['slideshows'] = $this->slideshow_model->slideshow_all('package');
        }
        // Load Slideshow for Package //
        if ($product_type == 'pass') {
            $this->load->model('slideshow_model');
            $view_data['slideshows'] = $this->slideshow_model->slideshow_all('pass');
        }
        // Load Slideshow for Package //
        if ($product_type == 'hotel') {
            $this->load->model('slideshow_model');
            $view_data['slideshows'] = $this->slideshow_model->slideshow_all('hotel');
        }

        $this->template->write_view('header', 'frontend/header', $head_data);


        $this->template->write_view('content', 'frontend/product/product_list', $view_data);


        $this->template->render();
    }

    function view($product_type = 'all', $product_id = 0)
    {

        if ($this->uri->segment(4)) {
            redirect(base_url() . $this->uri->segment(1) . '/' . $this->uri->segment(2) . '/' . $this->uri->segment(3) . '/', 301);
        }

        $view_data = $this->product_model->product_get($product_id);
        $this->load->model('stock_model');
        $view_data['stocks'] = $this->stock_model->stock_get_by_product_front($product_id);

        // Redirect when not same cat//
        // echo $view_data['product_type'];
        if ($view_data['product_type'] != $product_type) {
            redirect(base_url() . (str_replace('_package', '', $view_data['product_type'])) . '/' . $this->uri->segment(2) . '/' . $this->uri->segment(3) . '/', 301);
        }

        // Update Sold

        if (!$view_data['stocks'] && $product_type == 'tour_package') {
            $this->db->set('is_sold', 1)->where('product_id', $product_id)
                ->update('products');
        }

        // Update Minimum Price //
        if ($view_data['stocks']) {
            $minimum_price = PHP_INT_MAX;
            foreach ($view_data['stocks'] as $row) {
                $to_check = intval(str_replace(',', '', $row->product_stock_price));
                if ($to_check < $minimum_price) {
                    $minimum_price = $to_check;
                }
            }

            if ($minimum_price != PHP_INT_MAX) {
                // Update DB //
                $this->db->set('product_start_price', $minimum_price)->where('product_id', $product_id)->update('products');

                $view_data['product_start_price'] = $minimum_price;
            }
        }


        $view_data['tags'] = $this->product_model->get_tags($product_id, 'location');
        //print_r($view_data['tags']);
        // Get related product

        if ($view_data['tags']) {
            $view_data['related_product'] = $this->product_model->all('all', 4, 0, array(), 'price', '', '', $view_data['tags'], $product_id);
        } else {
            $view_data['related_product'] = array();
        }
        //shuffle($view_data['related_product']);
        $product_ids = array();
        $view_data['related_product_tags'] = array();
        if ($view_data['related_product']) {
            foreach ($view_data['related_product'] as $r) {
                $product_ids[] = $r->product_id;
            }

            $view_data['related_product_tags'] = $this->product_model->get_tags_by_many_products($product_ids, 'lifestyle');
        }


        // Get related article
        $this->load->model('article_model');
        if ($view_data['tags']) {
            $view_data['related_article'] = $this->article_model->all(10, 0, array(), '', '', '', $view_data['tags']);
        } else {
            $view_data['related_article'] = array();
        }

        shuffle($view_data['related_article']);

        $related_article_tags = array();
        /*
          foreach ($view_data['related_article'] as $r) {
          $related_article_tags[$r->article_id] = $this->article_model->get_tags($r->article_id, 'lifestyle');
          }
         * 
         */
        $view_data['related_article_tags'] = $related_article_tags;

        $lang = $this->session->userdata('lang') != '' ? $this->session->userdata('lang') : 'th';

        $this->load->config('page_custom_content', TRUE);
        $page_custom_content = $this->config->item($lang, 'page_custom_content');

        $view_data['product_categories'] = $this->product_category_model->product_category_all();


        $view_data['meta_title'] = $lang == 'en' ? $view_data['meta_title_en'] : $view_data['meta_title_th'];
        $view_data['meta_title_en'] = $view_data['product_title_en'];
        $view_data['meta_title_th'] = $view_data['product_title_th'];

        $head_data['meta']['title'] = $lang == 'en' ? $view_data['meta_title_en'] . ' ' . number_format($view_data['product_start_price']) . ' ' . lang('baht') : $view_data['meta_title_th'] . ' ' . number_format($view_data['product_start_price']) . ' ' . lang('baht') . ' | ' . $page_custom_content['main_web_title'] . ' - ' . $view_data['meta_title'];
        $head_data['meta']['keywords'] = $lang == 'en' ? $view_data['meta_keyword_en'] : $view_data['meta_keyword_th'];
        $head_data['meta']['description'] = $lang == 'en' ? preg_replace("/\r|\n/", "", $view_data['meta_description_en']) : preg_replace("/\r|\n/", "", $view_data['meta_description_th']);
        $head_data['fb_meta_img'] = str_replace('./', base_url(), $view_data['product_thumb'] . '?v=' . rand(1, 999));

        if ($this->agent->is_mobile()) {
            $view_data['mobile'] = true;
        }

        if (!isset($_GET['partner'])) {
            $this->template->write_view('header', 'frontend/header', $head_data);
            $this->template->write_view('content', 'frontend/product/product_view', $view_data);
            $this->template->render();
        } else {
            $this->template->set_template('partner');
            $this->template->write_view('header', 'frontend/header', $head_data);
            $this->template->write_view('content', 'partner_template/product/product_view', $view_data);
            $this->template->render();
        }
    }

    function book($product_id = 0, $stock_id = 0)
    {
        $member = $this->session->userdata('is_loggedin');
        if (!is_loggedin()) {
            redirect('member/login?redirect=' . base64_encode(base_url('product/book/' . $product_id . '/' . $stock_id)));
        }

        if ($_POST) {

            $this->load->library('form_validation');
            $this->load->model('stock_model');
            $this->form_validation->set_rules('select_adult', 'Number of adults', 'trim|required');
            if ($this->form_validation->run() === TRUE) {
                $create_data = array(
                    'stock_id' => $stock_id,
                    'product_id' => $product_id,
                    'number_of_adults' => $this->input->post('select_adult') ? $this->input->post('select_adult') : 0,
                    'number_of_children' => $this->input->post('select_children') ? $this->input->post('select_children') : 0,
                    'number_of_doubleroom' => $this->input->post('double_room') ? $this->input->post('double_room') : 0,
                    'number_of_tripleroom' => $this->input->post('triple_room') ? $this->input->post('triple_room') : 0,
                    'number_of_singleroom' => $this->input->post('single_room') ? $this->input->post('single_room') : 0,
                    'is_seafood_allergy' => $this->input->post('is_seafood_allergy') ? $this->input->post('is_seafood_allergy') : 0,
                    'is_vegetarian' => $this->input->post('is_vegetarian') ? $this->input->post('is_vegetarian') : 0,
                    'is_islam' => $this->input->post('is_islam') ? $this->input->post('is_islam') : 0,
                    'is_window_seat' => 0, //$this->input->post('is_window_seat'),
                    'is_pathway_seat' => 0, //$this->input->post('is_pathway_seat'),
                    'other_detail' => $this->input->post('order_memo'),
                    'is_pending' => 1,
                    'in_process' => 1,
                );

                $create_data['member_id'] = $member['member_id'];

                $create_data['order_add_on'] = '';
                if ($this->input->post('add_ons') != '') {
                    $create_data['order_add_on'] = implode('\n', $this->input->post('add_ons'));
                }

                $add_ons = $this->input->post('add_ons');
                $create_data['addon_price'] = 0;
                if (isset($add_ons)) {
                    foreach ($add_ons as $add_on) {
                        $temp = explode('|', $add_on);
                        if ($temp[2] == 'G') {
                            $create_data['addon_price'] += (int)$temp[1];
                        } elseif ($temp[2] == 'P') {
                            $create_data['addon_price'] += (int)$temp[1] * ((int)$create_data['number_of_adults'] + (int)$create_data['number_of_children']);
                        }
                    }
                }
                $stock = $this->stock_model->stock_get($stock_id);


                $create_data['order_price'] = (int)str_replace(',', '', $stock['product_stock_price']) * ((int)$create_data['number_of_adults'] + (int)$create_data['number_of_children']);
                $create_data['order_price'] += (int)str_replace(',', '', $stock['product_stock_single_price']) * (int)$create_data['number_of_singleroom'];


                // Insurance //
                $addon_insurance_price = 0;
                if ($this->input->post('insurance')) {
                    $insurance = $this->input->post('insurance');
                    // Load Insurance //
                    $this->load->helper('insurance');
                    $view_data = $this->product_model->product_get($product_id);
                    $this->load->model('stock_model');
                    $view_data['stock'] = $this->stock_model->stock_get($stock_id);

                    if ($this->input->post('insurance_start') && $this->input->post('insurance_stop') && $this->input->post('insurance_zone')) {
                        $insurance_list = get_insurance_price($this->input->post('insurance_zone'), ceil(($this->input->post('insurance_stop') - $this->input->post('insurance_start')) / 86400) + 1);
                        if ($insurance_list && isset($insurance_list[$insurance[0]])) {
                            $create_data['insurance_type'] = $insurance[0];
                            $create_data['insurance_amount'] = $insurance_list[$insurance[0]];
                            $create_data['insurance_start'] = date('Y-m-d', $this->input->post('insurance_start'));
                            $create_data['insurance_stop'] = date('Y-m-d', $this->input->post('insurance_stop'));
                            $create_data['insurance_country'] = $this->input->post('insurance_country');
                            $addon_insurance_price = $create_data['insurance_amount'] * ((int)$create_data['number_of_adults'] + (int)$create_data['number_of_children']);
                        }
                    } else {
                        $day = ceil((strtotime(date('Y-m-d 11:59:59', strtotime($view_data['stock']['product_stock_arrive_at']))) - strtotime(date('Y-m-d 00:00:00', strtotime($view_data['stock']['product_stock_depart_at'])))) / 86400);

                        $insurance_list = get_insurance_price($view_data['product_category_group'], $day);
                        if ($insurance_list && isset($insurance_list[$insurance[0]])) {
                            $create_data['insurance_type'] = $insurance[0];
                            $create_data['insurance_amount'] = $insurance_list[$insurance[0]];
                            $addon_insurance_price = $create_data['insurance_amount'] * ((int)$create_data['number_of_adults'] + (int)$create_data['number_of_children']);
                        }
                    }
                }


                $create_data['grand_total'] = (int)$create_data['order_price'] + (int)$create_data['addon_price'] + $addon_insurance_price;

                $this->load->model('order_model');

                if ($this->input->cookie('aff_id', TRUE)) {
                    $create_data['aff_id'] = $this->input->cookie('aff_id', TRUE);
                }

                $insert_id = $this->order_model->order_create($create_data);
                if ($insert_id) {
                    $this->session->set_userdata('booking_information', $_POST);

                    redirect('product/booking_review/' . $product_id . '/' . $stock_id . '/' . $insert_id);
                }
            } else {
                echo validation_errors();
                exit;
            }
        }

        $view_data = $this->product_model->product_get($product_id);

        $this->load->model('stock_model');
        $view_data['stock'] = $this->stock_model->stock_get($stock_id);


        $lang = $this->session->userdata('lang') != '' ? $this->session->userdata('lang') : 'th';
        $view_data['lang'] = $lang;
        $this->load->config('page_custom_content', TRUE);
        $page_custom_content = $this->config->item($lang, 'page_custom_content');

        $view_data['product_categories'] = $this->product_category_model->product_category_all();
        // Load Insurance //
        $this->load->helper('insurance');

        // Only Insurance //
        if (strstr($view_data['stock']["product_stock_code"], 'INSURANCE') && isset($_GET['insurance_start']) && isset($_GET['insurance_stop']) && isset($_GET['insurance_zone']) && isset($_GET['insurance_country'])) {
            $view_data['is_insurance'] = true;
            $view_data['insurance_start'] = strtotime($_GET['insurance_start']);
            $view_data['insurance_stop'] = strtotime($_GET['insurance_stop']);
            $view_data['insurance_zone'] = $_GET['insurance_zone'];
            $view_data['insurance_country'] = $_GET['insurance_country'];
            $view_data['insurance_list'] = get_insurance_price($view_data['insurance_zone'], ceil(($view_data['insurance_stop'] - $view_data['insurance_start']) / 86400) + 1);

        } else {

            $day = ceil((strtotime(date('Y-m-d 11:59:59', strtotime($view_data['stock']['product_stock_arrive_at']))) - strtotime(date('Y-m-d 00:00:00', strtotime($view_data['stock']['product_stock_depart_at'])))) / 86400);

            $view_data['insurance_list'] = get_insurance_price($view_data['product_category_group'], $day);
        }

        // Check is insurance but no data //
        if (strstr($view_data['stock']["product_stock_code"], 'INSURANCE') && !isset($view_data['is_insurance'])):
            redirect('p/insurance/preview');
        endif;


        $view_data['meta_title_en'] = $view_data['meta_title_en'] == '' ? $view_data['product_title_en'] : $view_data['meta_title_en'];
        $view_data['meta_title_th'] = $view_data['meta_title_th'] == '' ? $view_data['product_title_th'] : $view_data['meta_title_th'];

        $head_data['meta']['title'] = $lang == 'en' ? 'Booking : ' . $view_data['meta_title_en'] : 'Booking : ' . $view_data['meta_title_th'] . ' | ' . $page_custom_content['main_web_title'];
        $head_data['meta']['keyword'] = $lang == 'en' ? $view_data['meta_keyword_en'] : $view_data['meta_keyword_th'];
        $head_data['meta']['description'] = $lang == 'en' ? $view_data['meta_description_en'] : $view_data['meta_description_th'];

        if ($this->agent->is_mobile()) {
            $view_data['mobile'] = true;
        }

        $this->template->write_view('header', 'frontend/header', $head_data);
        $this->template->write_view('content', 'frontend/product/product_book', $view_data);
        $this->template->render();
    }

    function passenger_save($booking_id)
    {
        $passenger_count = count($this->input->post('passenger_title'));
        $passenger_data = $_REQUEST;


        if (isset($passenger_data['insurance'])) {
            $this->db->where('order_id', $booking_id)->delete('insurance_holder');
            $insert_data = array(
                'order_id' => $booking_id,
                'mobile' => $passenger_data['passenger_mobile'][0],
                'email' => $passenger_data['passenger_email'][0],
                'address_01' => $passenger_data['insurance']['address_01'],
                'address_02' => $passenger_data['insurance']['address_02'],
                'address_03' => $passenger_data['insurance']['address_03'],
                'address_04' => $passenger_data['insurance']['address_04'],
                'address_05' => $passenger_data['insurance']['address_05'],
                'zipcode' => $passenger_data['insurance']['zipcode']
            );
            $this->db->insert('insurance_holder', $insert_data);
        }


        $this->db->where('order_id', $booking_id)->delete('order_passengers');
        for ($i = 0; $i < $passenger_count; $i++) {
            $insert_data = array(
                'order_id' => $booking_id,
                'passenger_title' => $passenger_data['passenger_title'][$i],
                'passenger_firstname' => $passenger_data['passenger_firstname'][$i],
                'passenger_lastname' => $passenger_data['passenger_lastname'][$i],
                'passenger_firstname_th' => $passenger_data['passenger_firstname_th'][$i] ? $passenger_data['passenger_firstname_th'][$i] : $passenger_data['passenger_firstname'][$i],
                'passenger_lastname_th' => $passenger_data['passenger_lastname_th'][$i] ? $passenger_data['passenger_lastname_th'][$i] : $passenger_data['passenger_lastname'][$i],
                'passenger_gender' => $passenger_data['passenger_gender'][$i],
                'passenger_birthdate' => date('Y-m-d', strtotime($passenger_data['passenger_birthdate'][$i])),
                'passenger_citizen_id' => $passenger_data['passenger_citizen_id'][$i],
                'passenger_nationality' => $passenger_data['passenger_nationality'][$i],
                'passenger_telephone' => $passenger_data['passenger_telephone'][$i],
                'passenger_mobile' => $passenger_data['passenger_mobile'][$i],
                'passenger_email' => $passenger_data['passenger_email'][$i],
                'passenger_passport_id' => $passenger_data['passenger_passport_id'][$i],
                'passenger_passport_authority' => $passenger_data['passenger_passport_authority'][$i],
                'passenger_passport_issue' => date('Y-m-d', strtotime($passenger_data['passenger_passport_issue'][$i])),
                'passenger_passport_expiry' => date('Y-m-d', strtotime($passenger_data['passenger_passport_expiry'][$i])),
                'passenger_visa' => $passenger_data['passenger_visa'][$i],
                'passenger_visa_expiry' => $passenger_data['passenger_visa_expiry'][$i],
            );
            $this->db->insert('order_passengers', $insert_data);
        }
    }

    function booking_review($product_id = 0, $stock_id = 0, $booking_id = 0)
    {

        if (!is_loggedin()) {
            redirect('product/book/' . $product_id . '/' . $stock_id);
        }

        if ($this->db->where('order_id', $booking_id)->where('in_process', 1)->count_all_results('orders') <= 0) {
            redirect('product/book/' . $product_id . '/' . $stock_id);
        }

        $view_data = $this->product_model->product_get($product_id);
        $view_data['book_data'] = $this->session->userdata('booking_information');

        $this->load->model('order_model');
        $view_data['order_data'] = $this->order_model->order_get($booking_id);

        $this->load->model('stock_model');
        $view_data['stock'] = $this->stock_model->stock_get($stock_id);

        $view_data['booking_id'] = $booking_id;


        $lang = $this->session->userdata('lang') != '' ? $this->session->userdata('lang') : 'th';
        $view_data['lang'] = $lang;
        $this->load->config('page_custom_content', TRUE);
        $page_custom_content = $this->config->item($lang, 'page_custom_content');

        $view_data['product_categories'] = $this->product_category_model->product_category_all();

        $view_data['meta_title_en'] = $view_data['meta_title_en'] == '' ? $view_data['product_title_en'] : $view_data['meta_title_en'];
        $view_data['meta_title_th'] = $view_data['meta_title_th'] == '' ? $view_data['product_title_th'] : $view_data['meta_title_th'];

        $head_data['meta']['title'] = $lang == 'en' ? 'Booking : ' . $view_data['meta_title_en'] : 'Booking : ' . $view_data['meta_title_th'] . ' | ' . $page_custom_content['main_web_title'];
        $head_data['meta']['keyword'] = $lang == 'en' ? $view_data['meta_keyword_en'] : $view_data['meta_keyword_th'];
        $head_data['meta']['description'] = $lang == 'en' ? $view_data['meta_description_en'] : $view_data['meta_description_th'];

        if ($this->agent->is_mobile()) {
            $view_data['mobile'] = true;
        }

        $this->template->write_view('header', 'frontend/header', $head_data);
        $this->template->write_view('content', 'frontend/product/product_book_review', $view_data);
        $this->template->render();
    }

    function booking_linepay($product_id, $stock_id, $booking_id, $amount, $hash)
    {


        if (md5($amount . $booking_id . 'miramar') != $hash) {

            redirect('product/booking_payment/' . $product_id . '/' . $stock_id . '/' . $booking_id);
        }


        if (!is_loggedin()) {
            redirect('product/book/' . $product_id . '/' . $stock_id);
        }

        if ($this->db->where('order_id', $booking_id)->where('in_process', 1)->count_all_results('orders') <= 0) {
            redirect('product/book/' . $product_id . '/' . $stock_id);
        }

        $this->load->model('order_model');
        $order = $this->order_model->order_get_with_customer_product_stock($booking_id);

        $this->load->library('linepay');
        $lp = new Linepay();
        $lp->channelId = "1451867489";
        $lp->channelSecret = "3a130de1d07add6e2fb935a77f7cd5d9";

        $orderId = $booking_id . '-' . uniqid();
        $data = array(
            "productName" => $order['product_title_th'],
            "productImageUrl" => "https://www.wonderfulpackage.com/assets/img/mascot.png",
            "amount" => $amount,
            "currency" => "THB",
            "orderId" => $orderId,
            "confirmUrl" => base_url() . 'product/booking_finish/linepay/0/' . $amount,
            "cancelUrl" => base_url() . 'product/booking_payment/' . $product_id . '/' . $stock_id . '/' . $booking_id,
            "capture" => "true",
            "confirmUrlType" => "CLIENT"
        );

        $result = $lp->paymentsRequest($data);

        if (!$result) {
            redirect('product/booking_payment/' . $product_id . '/' . $stock_id . '/' . $booking_id);
        }

        $response = json_decode($result, true);

        $web = $response['info']['paymentUrl']['web'];
        if (!$web) {
            redirect('product/booking_payment/' . $product_id . '/' . $stock_id . '/' . $booking_id);
        }
        header("Location: " . $web);


        echo '<pre>';
        print_r($order);
    }

    function booking_payment($product_id = 0, $stock_id = 0, $booking_id = 0)
    {

        if (!is_loggedin()) {
            redirect('product/book/' . $product_id . '/' . $stock_id);
        }

        if ($this->db->where('order_id', $booking_id)->where('in_process', 1)->count_all_results('orders') <= 0) {
            redirect('product/book/' . $product_id . '/' . $stock_id);
        }

        if ($_POST) {

            /* Change in process */
            $this->db->set('in_process', 0)
                ->set('is_pending', 1)
                ->where('order_id', $booking_id)->update('orders');
            $create_data = array(
                'contact_name' => $this->input->post('name'),
                'contact_phone' => $this->input->post('phone'),
                'contact_email' => $this->input->post('email'),
                'contact_subject' => 'Ordering [' . $booking_id . '] : [' . $this->input->post('stock_code') . '] ' . $this->input->post('subject')
            );


            $this->load->model('order_model');
            $order = $this->order_model->order_get_with_customer_product_stock($booking_id);

            $order_type = '02';
            if ($order['insurance_start'] != '0000-00-00' && $order['insurance_stop'] != '0000-00-00') {
                $order['product_stock_depart_at'] = $order['insurance_start'];
                $order['product_stock_arrive_at'] = $order['insurance_stop'];
                $order_type = '04';

                $create_data['contact_detail'] = 'Depart : ' . date('j M Y', strtotime($order['product_stock_depart_at'])) . PHP_EOL;
                $create_data['contact_detail'] .= 'Arrive : ' . date('j M Y', strtotime($order['product_stock_arrive_at'])) . PHP_EOL;

                $create_data['contact_detail'] .= 'Insurance : ' . $order['insurance_type'] . ' - ' . number_format($order['insurance_amount']) . ' THB/Person' . PHP_EOL;
                $create_data['contact_detail'] .= 'Insurance Country: ' . $order['insurance_country'];
                $create_data['contact_detail'] .= 'Grand Total : ' . number_format($order['grand_total']) . ' THB' . PHP_EOL;
                $create_data['contact_detail'] .= '<a href="' . base_url() . 'backend/order/edit/' . $booking_id . '/' . $product_id . '/' . $stock_id . '" target="_blank" class="btn btn-success">กดเพื่อดูรายละเอียด</a>';
            } else {
                $create_data['contact_detail'] = 'Depart : ' . date('j M Y', strtotime($order['product_stock_depart_at'])) . PHP_EOL;
                $create_data['contact_detail'] .= 'Arrive : ' . date('j M Y', strtotime($order['product_stock_arrive_at'])) . PHP_EOL;
                $create_data['contact_detail'] .= 'Number of adults : ' . $order['number_of_adults'] . PHP_EOL;
                $create_data['contact_detail'] .= 'Number of children : ' . $order['number_of_children'] . PHP_EOL;
                $create_data['contact_detail'] .= 'Doubleroom : ' . $order['number_of_doubleroom'] . PHP_EOL;
                $create_data['contact_detail'] .= 'Tripleroom : ' . $order['number_of_tripleroom'] . PHP_EOL;
                $create_data['contact_detail'] .= 'Singleroom : ' . $order['number_of_singleroom'] . PHP_EOL;
                $create_data['contact_detail'] .= 'Vegetarian : ' . $order['is_vegetarian'] . PHP_EOL;
                $create_data['contact_detail'] .= 'Islam : ' . $order['is_islam'] . PHP_EOL;
                $create_data['contact_detail'] .= 'Seafood Allergy : ' . $order['is_seafood_allergy'] . PHP_EOL;
                $create_data['contact_detail'] .= 'Window Seat : ' . $order['is_window_seat'] . PHP_EOL;
                $create_data['contact_detail'] .= 'Aisle Seat : ' . $order['is_pathway_seat'] . PHP_EOL;
                $create_data['contact_detail'] .= 'Other Detail : ' . $order['other_detail'] . PHP_EOL;
                if ($order['insurance_type']) {
                    $create_data['contact_detail'] .= 'Insurance : ' . $order['insurance_type'] . ' - ' . number_format($order['insurance_amount']) . ' THB/Person' . PHP_EOL;
                }
                $create_data['contact_detail'] .= 'Grand Total : ' . number_format($order['grand_total']) . ' THB' . PHP_EOL;
                $create_data['contact_detail'] .= '<a href="' . base_url() . 'backend/order/edit/' . $booking_id . '/' . $product_id . '/' . $stock_id . '" target="_blank" class="btn btn-success">กดเพื่อดูรายละเอียด</a>';
            }

            $this->load->model('contact_model');
            $insert_id = $this->contact_model->contact_create($create_data);
            if ($insert_id) {
                $message1 = '<img src="' . base_url() . 'assets/img/email_header.jpg" style="width:100%">';

                $message2 = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>'
                    . '<td style="vertical-align:top"><h2>รายละเอียดผู้สั่งจอง</h2>' .
                    '<b>ชื่อ-นามสกุล:</b> ' . $order['member_firstname'] . ' ' . $order['member_lastname'] .
                    '<br/><b>เบอร์โทรติดต่อ:</b> ' . $order['member_contact'] .
                    '<br/><b>อีเมล์:</b> ' . $order['member_email'] .
                    '</td><td style="vertical-align:top"><h2>รายละเอียดการสั่งจอง</h2>' .
                    '<b>' . $order['promotion_word'] . '</b>' .
                    '<li><b>หมายเลขการสั่งจอง:</b> ' . $order['order_id'] . '</li>' .
                    '<li><b>จอง ณ วันที่:</b> ' . date('j M Y') . '</li>' .
                    '<li><b>ราคาที่ต้องชำระทั้งสิ้น:</b> ' . number_format($order['grand_total']) . ' บาท' . '</li>' .
                    '<li><b>วิธีการชำระเงิน:</b> ชำระด้วยตัวเอง (Offline Payment)' . '</li></td></tr></table>' .
                    '<table width="100%" border="0" cellspacing="0" cellpadding="0">
                             <tr>
                                <td style="padding:5px;border:1px solid #ddd;text-align:center;background: #20409a; color: white;"><b>รายการ</b></td>
                                <td style="padding:5px;border:1px solid #ddd;text-align:center;background: #20409a; color: white;"><b>รายละเอียด</b></td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ชื่อสินค้า:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">(' . $order['product_code'] . ') ' . $order['product_title_th'] . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>รหัสสต๊อก:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . $order['product_stock_code'] . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ราคาตามสต๊อก:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . number_format($order['product_stock_price']) . ' บาท/ท่าน</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ขาไป:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . date('j M Y', strtotime($order['product_stock_depart_at'])) . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ขากลับ:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . date('j M Y', strtotime($order['product_stock_arrive_at'])) . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>เที่ยวบิน:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . $order['product_stock_flight_no_go'] . ' / ' . $order['product_stock_flight_no_return'] . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ผู้ใหญ่:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . $order['number_of_adults'] . ' ท่าน</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>เด็ก:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . $order['number_of_children'] . ' ท่าน</td>
                            </tr>
                            
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>รายละเอียดเที่ยวบิน:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">'
                    . (($order['is_window_seat'] != 0) ? 'ติดริมหน้าต่าง: ' . $order['is_window_seat'] . ' ท่าน<br/>' : '')
                    . (($order['is_pathway_seat'] != 0) ? 'ติดริมทางเดิน: ' . $order['is_pathway_seat'] . ' ท่าน<br/>' : '')
                    . '</td>
                            </tr>
                            
                             <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>รายละเอียดห้องพัก:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">'
                    . (($order['number_of_doubleroom'] != 0) ? 'ห้องพักเตียงคู่: ' . $order['number_of_doubleroom'] . ' ห้อง<br/>' : '')
                    . (($order['number_of_tripleroom'] != 0) ? 'ห้องพักเตียงคู่+เสริม: ' . $order['number_of_tripleroom'] . ' ห้อง<br/>' : '')
                    . (($order['number_of_singleroom'] != 0) ? 'ห้องพักเตียงเดี่ยว: ' . $order['number_of_singleroom'] . ' ห้อง<br/>' : '')
                    . '</td>
                            </tr>
                            
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>รายละเอียดอาหาร:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">'
                    . (($order['is_vegetarian'] != 0) ? 'มังสวิรัส: ' . $order['is_vegetarian'] . ' ท่าน<br/>' : '')
                    . (($order['is_islam'] != 0) ? 'อิสลาม: ' . $order['is_islam'] . ' ท่าน<br/>' : '')
                    . (($order['is_seafood_allergy'] != 0) ? 'แพ้อาหารทะเล: ' . $order['is_seafood_allergy'] . ' ท่าน<br/>' : '')
                    . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ประกันการเดินทาง:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . ($order['insurance_type'] ? 'แผน ' . ucfirst($order['insurance_type']) . ' - ' . number_format($order['insurance_amount'] * ($order['number_of_adults'] + $order['number_of_children'])) . ' บาท' : ' - ') . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>การมัดจำ:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . number_format($order['product_stock_advanced_price'] * ($order['number_of_adults'] + $order['number_of_children'])) . ' บาท ภายใน 48 ชั่วโมง</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ข้อเสนอแนะอื่นๆ:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . $order['other_detail'] . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ช่องทางการชำระเงิน:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">1. ผ่านบัตรเครดิต<br/>2. ติดต่อเจ้าหน้าที่ฝ่ายขาย ติดต่อ 02-792-9292</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ข้อเสนอแนะจากทางบริษัท:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">บริษัทขอสงวนสิทธิ์ ในการทำยกเลิกหากท่านมิได้ทำการชำระเงินภายในเวลาที่กำหนด</td>
                            </tr>
                        </table><br/><img src="' . base_url() . 'assets/img/email_footer.jpg" style="width:100%">';
                $message3 = 'ด้วยความเคารพ,<br/><br/><img src="' . base_url() . 'assets/img/logo.png"/>';
                $message4 = 'กรุณากดที่นี่: [<a href="' . base_url() . 'backend/order/edit/' . $booking_id . '/' . $product_id . '/' . $stock_id . '" target="_blank">รายละเอียดรายการนี้</a>] และกด Booking & Cut Stock เพื่อทำการยืนยันการสั่งซื้อนี้';
                // Send Email to Sale //
                $this->load->config('email', TRUE);
                $mail_config = $this->config->item('mail', 'email');
                $this->load->library('email');
                $this->email->initialize($mail_config);
                $this->email->set_newline("\r\n");
                $this->email->from($this->config->item('email_system', 'email'), 'Wonderfulpackage Order');
                $this->email->reply_to($create_data['contact_email'], $create_data['contact_name']);
                $this->email->to($this->config->item('email_sale', 'email'));
                $this->email->subject('สั่งจอง "' . $order['product_title_th'] . '"');
                $this->email->message($message2 . $message4);

                if (!$this->email->send()) {
                    //echo $this->email->print_debugger();
                    //die();
                }
                //die();
                // Send Email to Customer //
                //$this->email->set_newline("\r\n");
                $this->email->from($this->config->item('email_system', 'email'), 'Wonderfulpackage.com');
                $this->email->reply_to($this->config->item('email_sale', 'email'));
                $this->email->to($order['member_email'], $order['member_firstname'] . ' ' . $order['member_lastname']);
                $this->email->subject('ท่านได้ทำการสั่งจอง "' . $order['product_title_th'] . '"');
                $this->email->message($message1 . $message2);

                // Attach PDF //
                $billpay_type = 'all';
                if ($this->input->post('billpay_type')) {
                    $billpay_type = $this->input->post('billpay_type');
                }
                if (!$this->email->send()) {
                    //echo $this->email->print_debugger();
                    //die();
                }

                // Load API KEY //
                $this->config->load('partner_api');
                $partner_api_secret = $this->config->item('partner_api_secret');
                //echo $partner_api_secret;
                // Send to Billpay API //
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, base_url() . 'api/partner/send_billpay');
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Api-Secret: ' . $partner_api_secret));
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
                    array(
                        'amount' => $order['grand_total'],
                        'fullname' => trim($order['member_firstname'] . ' ' . $order['member_lastname']),
                        'telephone' => $order['member_contact'],
                        'order_no' => $order['order_id'],
                        'email' => $order['member_email'],
                        'order_type' => $order_type,
                        'billpay_type' => $billpay_type,
                        'billpay_expiry' => time() + (86400 * 14),
                    )));

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                // Send to Kradan Booking //
                $this->load->config('partner_api');
                if ($this->config->item('is_call_kradan')) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, base_url() . 'api/kradan/send_booking/' . $booking_id);
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    curl_close($ch);
                }

                redirect('product/booking_finish/none/1');
            }
        }

        if ($this->session->userdata('booking_information') == '') {
            redirect('product/book/' . $product_id . '/' . $stock_id);
        }

        $view_data = array();

        if ($this->agent->is_mobile()) {
            $view_data['mobile'] = true;
        }

        $view_data = $this->product_model->product_get($product_id);
        $view_data['book_data'] = $this->session->userdata('booking_information');
        $view_data['booking_id'] = $booking_id;

        $this->load->model('order_model');
        $view_data['order_data'] = $this->order_model->order_get($booking_id);

        $this->load->model('stock_model');
        $view_data['stock'] = $this->stock_model->stock_get($stock_id);


        $lang = $this->session->userdata('lang') != '' ? $this->session->userdata('lang') : 'th';
        $view_data['lang'] = $lang;
        $this->load->config('page_custom_content', TRUE);
        $page_custom_content = $this->config->item($lang, 'page_custom_content');

        $view_data['product_categories'] = $this->product_category_model->product_category_all();

        $view_data['meta_title_en'] = $view_data['meta_title_en'] == '' ? $view_data['product_title_en'] : $view_data['meta_title_en'];
        $view_data['meta_title_th'] = $view_data['meta_title_th'] == '' ? $view_data['product_title_th'] : $view_data['meta_title_th'];

        $head_data['meta']['title'] = $lang == 'en' ? 'Payment : ' . $view_data['meta_title_en'] : 'Booking : ' . $view_data['meta_title_th'] . ' | ' . $page_custom_content['main_web_title'];
        $head_data['meta']['keyword'] = $lang == 'en' ? $view_data['meta_keyword_en'] : $view_data['meta_keyword_th'];
        $head_data['meta']['description'] = $lang == 'en' ? $view_data['meta_description_en'] : $view_data['meta_description_th'];

        $this->template->write_view('header', 'frontend/header', $head_data);
        $this->template->write_view('content', 'frontend/product/product_book_payment', $view_data);
        $this->template->render();
    }

    function Luhn($number)
    {
        $stack = 0;
        $number = str_replace(array('|', PHP_EOL), '', $number);

        $number = str_split(strrev($number));

        foreach ($number as $key => $value) {
            //echo $key % 2;
            if ($key % 2 == 0) {
                $value = array_sum(str_split($value * 2));
            }

            $stack += $value;
        }

        $stack *= 9;
        $stack %= 10;

        $number = implode('', array_reverse($number));
        $number = $number . strval($stack);

        return $stack;
    }

    function booking_finish($bank = '', $is_manaul = 0, $amount = 0)
    {


        if (!$is_manaul) {
            $this->load->config('payment');
            switch ($bank) {
                case 'kbank':
                    //for kasikorn bank
                    $ref_code = $this->input->get_post('REFCODE');
                    $host_res = $this->input->get_post('HOSTRESP');
                    $card_number = $this->input->get_post('CARDNUMBER');
                    $return_inv = $this->input->get_post('RETURNINV');
                    $order_id = (int)$this->input->get_post('RETURNINV');
                    $auth_code = $this->input->get_post('AUTHCODE');
                    $fill_space = $this->input->get_post('FILLSPACE');
                    $amount = substr($this->input->get_post('AMOUNT'), 0, -2);
                    break;

                case 'bbl':
                    $api_bbl = simplexml_load_file($this->config->item('payment_api_bbl') . '?merchantId=' . $this->config->item('payment_merchant_id_bbl') . '&loginId=' . $this->config->item('payment_username_bbl') . '&password=' . $this->config->item('payment_password_bbl') . '&actionType=Query&orderRef=' . $_GET['Ref']);
                    // print_r($api_bbl);
                    //for bangkok bank
                    $ref_code = $api_bbl->record->payRef; //รหัสยืนยันการจ่ายเงิน
                    $host_res = $api_bbl->record->orderStatus; //รหัส สถาณะ
                    $card_number = $api_bbl->record->cc1316; //รหัสเลขบัตรเครดิต
                    $return_inv = $api_bbl->record->ref; // รหัสสั่งซื้อ
                    $order_id = (int)$api_bbl->record->ref; // รหัสสั่งซื้อ
                    $auth_code = $api_bbl->record->authId; // รหัสรักษาความปลอดภัย มั้ง
                    $fill_space = $api_bbl->record->holder; // ประเภทบัตรเครดิต
                    $amount = $api_bbl->record->amt;
                    break;
                case 'linepay':
                    $this->load->library('linepay');
                    $lp = new Linepay();
                    $lp->channelId = "1451867489";
                    $lp->channelSecret = "3a130de1d07add6e2fb935a77f7cd5d9";

                    $transactionId = $_GET['transactionId'];
                    $data = array(
                        "amount" => $amount,
                        "currency" => "THB"
                    );

                    $result = $lp->paymentsConfirm($transactionId, $data);
                    $response = json_decode($result, true);
                    //print_r($response);

                    $ref_code = $transactionId;
                    $host_res = $response['returnCode'];
                    $card_number = 'LINE PAY';
                    $return_inv = (int)$response['info']['orderId'];
                    $order_id = (int)$response['info']['orderId'];
                    $auth_code = 'LINE PAY';
                    $fill_space = 'LINE PAY';


                    break;
                default:
                    $ref_code = '';
                    $host_res = '';
                    $card_number = '';
                    $return_inv = '';
                    $order_id = '';
                    $auth_code = '';
                    $fill_space = '';
                    break;
            }

            if (!isset($order_id)) {
                redirect('/');
            }


            if ($host_res == '00' || $host_res == 'Accepted' || $host_res == '0000') {

            } else {
                $return = $this->db->select('product_id,stock_id')->where('order_id', $order_id)->get('orders')->row_array();
                if ($return) {
                    redirect('product/booking_payment/' . $return['product_id'] . '/' . $return['stock_id'] . '/' . $order_id);
                } else {
                    redirect('/');
                }
            }

            /* Change in process */
            $this->db->set('in_process', 0)
                ->set('is_pending', 1)
                ->where('order_id', $order_id)
                ->update('orders');


            $paid_amount = (int)$amount;


            $this->load->model('order_model');
            if ($this->order_model->payment_complete($order_id, $paid_amount)) {

                $this->db->set('order_memo', '<b>[' . date('j M Y H:i') . ']</b> ' . strtoupper($bank) . ' Payment Gateway <b>Invoice No.:</b> ' . $return_inv
                    . ' <b>Ref Code:</b> ' . $ref_code
                    . ' <b>Auth Code:</b> ' . $auth_code
                    . ' <b>Amount:</b> ' . number_format($paid_amount)
                    . ' THB <b>Card No.:</b> ' . $card_number
                    . ' <b>FILLSPACE</b>' . $fill_space)
                    ->where('order_id', $order_id)->update('orders');

                /* Send Email to System */
                $this->load->model('order_model');
                $order = $this->order_model->order_get_with_customer_product_stock($order_id);

                $create_data = array(
                    'contact_name' => $order['member_firstname'] . ' ' . $order['member_lastname'],
                    'contact_phone' => $order['member_contact'],
                    'contact_email' => $order['member_email'],
                    'contact_subject' => 'Payment : [' . $order_id . '] : [' . $order['product_stock_code'] . '] ' . $order['product_title_th']
                );

                if ($order['insurance_start'] != '0000-00-00' && $order['insurance_stop'] != '0000-00-00') {
                    $order['product_stock_depart_at'] = $order['insurance_start'];
                    $order['product_stock_arrive_at'] = $order['insurance_stop'];
                    $create_data['contact_detail'] = 'Payment : ' . strtoupper($bank);
                    $create_data['contact_detail'] = 'Payment Inv / Ref : ' . $return_inv . ' / ' . $ref_code;
                    $create_data['contact_detail'] .= 'Depart : ' . date('j M Y', strtotime($order['product_stock_depart_at'])) . PHP_EOL;
                    $create_data['contact_detail'] .= 'Arrive : ' . date('j M Y', strtotime($order['product_stock_arrive_at'])) . PHP_EOL;

                    $create_data['contact_detail'] .= 'Insurance : ' . $order['insurance_type'] . ' - ' . number_format($order['insurance_amount']) . ' THB/Person' . PHP_EOL;
                    $create_data['contact_detail'] .= 'Insurance Country: ' . $order['insurance_country'];
                    $create_data['contact_detail'] .= 'Grand Total : ' . number_format($order['grand_total']) . ' THB' . PHP_EOL;
                    $create_data['contact_detail'] .= '<a href="' . base_url() . 'backend/order/edit/' . $booking_id . '/' . $product_id . '/' . $stock_id . '" target="_blank" class="btn btn-success">กดเพื่อดูรายละเอียด</a>';
                } else {
                    $create_data['contact_detail'] = 'Depart : ' . date('j M Y', strtotime($order['product_stock_depart_at'])) . PHP_EOL;
                    $create_data['contact_detail'] .= 'Arrive : ' . date('j M Y', strtotime($order['product_stock_arrive_at'])) . PHP_EOL;
                    $create_data['contact_detail'] .= 'Paid : ' . number_format($paid_amount) . ' THB' . PHP_EOL;
                    $create_data['contact_detail'] .= 'Number of adults : ' . $order['number_of_adults'] . PHP_EOL;
                    $create_data['contact_detail'] .= 'Number of children : ' . $order['number_of_children'] . PHP_EOL;
                    $create_data['contact_detail'] .= 'Doubleroom : ' . $order['number_of_doubleroom'] . PHP_EOL;
                    $create_data['contact_detail'] .= 'Tripleroom : ' . $order['number_of_tripleroom'] . PHP_EOL;
                    $create_data['contact_detail'] .= 'Singleroom : ' . $order['number_of_singleroom'] . PHP_EOL;
                    $create_data['contact_detail'] .= 'Vegetarian : ' . $order['is_vegetarian'] . PHP_EOL;
                    $create_data['contact_detail'] .= 'Islam : ' . $order['is_islam'] . PHP_EOL;
                    $create_data['contact_detail'] .= 'Seafood Allergy : ' . $order['is_seafood_allergy'] . PHP_EOL;
                    $create_data['contact_detail'] .= 'Window Seat : ' . $order['is_window_seat'] . PHP_EOL;
                    $create_data['contact_detail'] .= 'Aisle Seat : ' . $order['is_pathway_seat'] . PHP_EOL;
                    $create_data['contact_detail'] .= 'Other Detail : ' . $order['other_detail'] . PHP_EOL;
                    if ($order['insurance_type']) {
                        $create_data['contact_detail'] .= 'Insurance : ' . $order['insurance_type'] . ' - ' . number_format($order['insurance_amount']) . ' THB/Person' . PHP_EOL;
                    }
                    $create_data['contact_detail'] .= 'Grand Total : ' . number_format($order['grand_total']) . ' THB' . PHP_EOL;
                    $create_data['contact_detail'] .= '<a href="' . base_url() . 'backend/order/edit/' . $order_id . '/' . $order['product_id'] . '/' . $order['stock_id'] . '" class="btn btn-primary" target="_blank">กดเพื่อดูรายละเอียดการสั่งซื้อนี้</a>';
                }


                $this->load->model('contact_model');
                $this->contact_model->contact_create($create_data);

                // Send Email to Sale //
                $this->load->config('email', TRUE);
                $mail_config = $this->config->item('mail', 'email');
                $this->load->library('email');
                $this->email->initialize($mail_config);
                $message = '<b>Name:</b> ' . $create_data['contact_name'] . '<br/>' .
                    '<b>Telephone:</b> ' . $create_data['contact_phone'] . '<br/>' .
                    '<b>Email:</b> ' . $create_data['contact_email'] . '<br/>' .
                    '<b>Message:</b> ' . nl2br($create_data['contact_detail']);
                $this->email->set_newline("\r\n");
                $this->email->from($this->config->item('email_system', 'email'), 'Ordering System');
                $this->email->reply_to($create_data['contact_email'], $create_data['contact_name']);
                $this->email->to($this->config->item('email_sale', 'email'));
                $this->email->subject($create_data['contact_subject']);
                $this->email->message($message);

                if (!$this->email->send()) {
                    //echo $this->email->print_debugger();
                    //die();
                }

                // Email to Customer //
                $message1 = '<img src="' . base_url() . 'assets/img/email_header.jpg" style="width:100%">';

                $message2 = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>'
                    . '<td style="vertical-align:top"><h2>รายละเอียดผู้สั่งจอง</h2>' .
                    '<b>ชื่อ-นามสกุล:</b> ' . $order['member_firstname'] . ' ' . $order['member_lastname'] .
                    '<br/><b>เบอร์โทรติดต่อ:</b> ' . $order['member_contact'] .
                    '<br/><b>อีเมล์:</b> ' . $order['member_email'] .
                    '</td><td style="vertical-align:top"><h2>รายละเอียดการสั่งจอง</h2>' .
                    '<b>' . $order['promotion_word'] . '</b>' .
                    '<li><b>หมายเลขการสั่งจอง:</b> ' . $order['order_id'] . '</li>' .
                    '<li><b>จอง ณ วันที่:</b> ' . date('j M Y') . '</li>' .
                    '<li><b>ราคาที่ต้องชำระทั้งสิ้น:</b> ' . number_format($order['grand_total']) . ' บาท' . '</li>' .
                    '<li><b>วิธีการชำระเงิน:</b> ชำระผ่านบัตรเครดิต' . '</li></td></tr></table>' .
                    '<table width="100%" border="0" cellspacing="0" cellpadding="0">
                             <tr>
                                <td style="padding:5px;border:1px solid #ddd;text-align:center;background: #20409a; color: white;"><b>รายการ</b></td>
                                <td style="padding:5px;border:1px solid #ddd;text-align:center;background: #20409a; color: white;"><b>รายละเอียด</b></td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ชื่อสินค้า:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">(' . $order['product_code'] . ') ' . $order['product_title_th'] . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>รหัสสต๊อก:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . $order['product_stock_code'] . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ราคาตามสต๊อก:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . number_format($order['product_stock_price']) . ' บาท/ท่าน</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ขาไป:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . date('j M Y', strtotime($order['product_stock_depart_at'])) . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ขากลับ:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . date('j M Y', strtotime($order['product_stock_arrive_at'])) . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>เที่ยวบิน:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . $order['product_stock_flight_no_go'] . ' / ' . $order['product_stock_flight_no_return'] . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ผู้ใหญ่:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . $order['number_of_adults'] . ' ท่าน</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>เด็ก:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . $order['number_of_children'] . ' ท่าน</td>
                            </tr>
                            
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>รายละเอียดเที่ยวบิน:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">'
                    . (($order['is_window_seat'] != 0) ? 'ติดริมหน้าต่าง: ' . $order['is_window_seat'] . ' ท่าน<br/>' : '')
                    . (($order['is_pathway_seat'] != 0) ? 'ติดริมทางเดิน: ' . $order['is_pathway_seat'] . ' ท่าน<br/>' : '')
                    . '</td>
                            </tr>
                            
                             <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>รายละเอียดห้องพัก:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">'
                    . (($order['number_of_doubleroom'] != 0) ? 'ห้องพักเตียงคู่: ' . $order['number_of_doubleroom'] . ' ห้อง<br/>' : '')
                    . (($order['number_of_tripleroom'] != 0) ? 'ห้องพักเตียงคู่+เสริม: ' . $order['number_of_tripleroom'] . ' ห้อง<br/>' : '')
                    . (($order['number_of_singleroom'] != 0) ? 'ห้องพักเตียงเดี่ยว: ' . $order['number_of_singleroom'] . ' ห้อง<br/>' : '')
                    . '</td>
                            </tr>
                            
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>รายละเอียดอาหาร:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">'
                    . (($order['is_vegetarian'] != 0) ? 'มังสวิรัส: ' . $order['is_vegetarian'] . ' ท่าน<br/>' : '')
                    . (($order['is_islam'] != 0) ? 'อิสลาม: ' . $order['is_islam'] . ' ท่าน<br/>' : '')
                    . (($order['is_seafood_allergy'] != 0) ? 'แพ้อาหารทะเล: ' . $order['is_seafood_allergy'] . ' ท่าน<br/>' : '')
                    . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ประกันการเดินทาง:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . ($order['insurance_type'] ? 'แผน ' . ucfirst($order['insurance_type']) . ' - ' . number_format($order['insurance_amount'] * ($order['number_of_adults'] + $order['number_of_children'])) . ' บาท' : ' - ') . '</td>
                            </tr>
                            <tr>
                                <td style="padding:5px;border:1px solid #ddd"><b>ข้อเสนอแนะอื่นๆ:</b></td>
                                <td style="padding:5px;border:1px solid #ddd">' . $order['other_detail'] . '</td>
                            </tr>
                            
                            
                        </table><br/><img src="' . base_url() . 'assets/img/email_footer.jpg" style="width:100%">';
                $this->email->from($this->config->item('email_system', 'email'), 'Wonderfulpackage.com');
                $this->email->reply_to($this->config->item('email_sale', 'email'));
                $this->email->to($order['member_email'], $order['member_firstname'] . ' ' . $order['member_lastname']);
                $this->email->subject('ท่านได้ทำการสั่งซื้อ "' . $order['product_title_th'] . '"');
                $this->email->message($message1 . $message2);
                if (!$this->email->send()) {
                    //echo $this->email->print_debugger();
                    //die();
                }


                // Send to Kradan Booking //
                $this->load->config('partner_api');
                if ($this->config->item('is_call_kradan')) {

                    // Do Booking //
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, base_url() . 'api/kradan/send_booking/' . $order_id);
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    curl_close($ch);

                    // Do Payment //
                    if ($response == 'success') {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, base_url() . 'api/kradan/send_booking_payment/' . $order_id);
                        curl_setopt($ch, CURLOPT_POST, TRUE);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, 'payment_data=' . json_encode(array(
                                'payment_status' => ($paid_amount >= $order['grand_total'] ? 'Deposit' : 'Complete'),
                                'payment_amount' => $paid_amount,
                                'payment_remark' => strtoupper($bank) . ' Payment Gateway Ref Code:' . $ref_code . ' Card No.: ' . $card_number . ' FILLSPACE: ' . $fill_space,
                                'payment_timestamp' => time(),
                            )));

                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($ch);
                        curl_close($ch);
                    }
                    //echo $response;
                    //die();
                }

                redirect('product/booking_finish/' . $bank . '/1');
            }
        } else {
            $view_data = array();
            $head_data = array();
            $this->template->write_view('header', 'frontend/header', $head_data);
            $this->template->write_view('content', 'frontend/product/product_book_finish');
            $this->template->render();
        }
    }

    function pay_more($bank = '')
    {
        $this->load->config('payment');
        $order_id = (int)$this->input->post('RETURNINV');

        if (!isset($order_id)) {
            redirect('/');
        }

        switch ($bank) {
            case 'kbank':
                //for kasikorn bank
                $ref_code = $this->input->get_post('REFCODE');
                $host_res = $this->input->get_post('HOSTRESP');
                $card_number = $this->input->get_post('CARDNUMBER');
                $return_inv = $this->input->get_post('RETURNINV');
                $order_id = (int)$this->input->get_post('RETURNINV');
                $auth_code = $this->input->get_post('AUTHCODE');
                $fill_space = $this->input->get_post('FILLSPACE');
                $amount = substr($this->input->get_post('AMOUNT'), 0, -2);
                break;

            case 'bbl':
                $api_bbl = simplexml_load_file($this->config->item('payment_api_bbl') . '?merchantId=' . $this->config->item('payment_merchant_id_bbl') . '&loginId=' . $this->config->item('payment_username_bbl') . '&password=' . $this->config->item('payment_password_bbl') . '&actionType=Query&orderRef=' . $_GET['Ref']);
                // print_r($api_bbl);
                //for bangkok bank
                $ref_code = $api_bbl->record->payRef; //รหัสยืนยันการจ่ายเงิน
                $host_res = $api_bbl->record->orderStatus; //รหัส สถาณะ
                $card_number = $api_bbl->record->cc1316; //รหัสเลขบัตรเครดิต
                $return_inv = $api_bbl->record->ref; // รหัสสั่งซื้อ
                $order_id = (int)$api_bbl->record->ref; // รหัสสั่งซื้อ
                $auth_code = $api_bbl->record->authId; // รหัสรักษาความปลอดภัย มั้ง
                $fill_space = $api_bbl->record->holder; // ประเภทบัตรเครดิต
                $amount = $api_bbl->record->amt;
                break;
            default:
                $ref_code = '';
                $host_res = '';
                $card_number = '';
                $return_inv = '';
                $order_id = '';
                $auth_code = '';
                $fill_space = '';
                break;
        }


        if ($host_res == '00' || $host_res == 'Accepted') {

        } else {
            redirect('member/history');
        }


//        $paid_amount = (int) $this->input->post('AMOUNT');
//        $paid_amount = (int) substr($paid_amount, 0, -2);
        $paid_amount = (int)$amount;

        $this->load->model('order_model');
        if ($this->order_model->pay_more($order_id, $paid_amount)) {

            $this->db->set('order_memo', 'CONCAT_WS("", order_memo,"' . PHP_EOL . '<b>[' . date('j M Y H:i') . ']</b> ' . strtoupper($bank) . ' Payment Gateway <b>Invoice No.:</b> ' . $return_inv . ' <b>Ref Code:</b> ' . $ref_code . ' <b>Auth Code:</b> ' . $auth_code . ' <b>Amount:</b> ' . number_format($paid_amount) . ' THB <b>Card No.:</b> ' . $card_number . ' <b>Fill Space:</b> ' . $fill_space . '")', FALSE)->where('order_id', $order_id)->update('orders');
            $this->db->set('is_read', 0)->where('is_read', 1)->where('order_id', $order_id)->update('orders');

            /* Send Email to System */
            $this->load->model('order_model');
            $order = $this->order_model->order_get_with_customer_product_stock($order_id);
            $create_data = array(
                'contact_name' => $order['member_firstname'] . ' ' . $order['member_lastname'],
                'contact_phone' => $order['member_contact'],
                'contact_email' => $order['member_email'],
                'contact_subject' => 'Payment : [' . $order_id . '] ' . $order['product_title_th']
            );
            $create_data['contact_detail'] = 'Depart : ' . date('j M Y', strtotime($order['product_stock_depart_at'])) . PHP_EOL;
            $create_data['contact_detail'] .= 'Arrive : ' . date('j M Y', strtotime($order['product_stock_arrive_at'])) . PHP_EOL;
            $create_data['contact_detail'] .= 'Paid : ' . number_format($paid_amount) . ' THB' . PHP_EOL;
            $create_data['contact_detail'] .= 'Number of adults : ' . $order['number_of_adults'] . PHP_EOL;
            $create_data['contact_detail'] .= 'Number of children : ' . $order['number_of_children'] . PHP_EOL;
            $create_data['contact_detail'] .= 'Doubleroom : ' . $order['number_of_doubleroom'] . PHP_EOL;
            $create_data['contact_detail'] .= 'Tripleroom : ' . $order['number_of_tripleroom'] . PHP_EOL;
            $create_data['contact_detail'] .= 'Singleroom : ' . $order['number_of_singleroom'] . PHP_EOL;
            $create_data['contact_detail'] .= 'Vegetarian : ' . $order['is_vegetarian'] . PHP_EOL;
            $create_data['contact_detail'] .= 'Islam : ' . $order['is_islam'] . PHP_EOL;
            $create_data['contact_detail'] .= 'Seafood Allergy : ' . $order['is_seafood_allergy'] . PHP_EOL;
            $create_data['contact_detail'] .= 'Window Seat : ' . $order['is_window_seat'] . PHP_EOL;
            $create_data['contact_detail'] .= 'Aisle Seat : ' . $order['is_pathway_seat'] . PHP_EOL;
            $create_data['contact_detail'] .= 'Other Detail : ' . $order['other_detail'] . PHP_EOL;
            $create_data['contact_detail'] .= 'Grand Total : ' . number_format($order['grand_total']) . ' THB' . PHP_EOL;
            $create_data['contact_detail'] .= '<a href="' . base_url() . 'backend/order/edit/' . $order_id . '/' . $order['product_id'] . '/' . $order['stock_id'] . '" class="btn btn-primary" target="_blank">กดเพื่อดูรายละเอียดการสั่งซื้อนี้</a>';
            $this->load->model('contact_model');
            $this->contact_model->contact_create($create_data);

            // Send Email to Sale //
            $this->load->config('email', TRUE);
            $mail_config = $this->config->item('mail', 'email');
            $this->load->library('email');
            $this->email->initialize($mail_config);
            $message = '<b>Name:</b> ' . $create_data['contact_name'] . '<br/>' .
                '<b>Telephone:</b> ' . $create_data['contact_phone'] . '<br/>' .
                '<b>Email:</b> ' . $create_data['contact_email'] . '<br/>' .
                '<b>Message:</b> ' . nl2br($create_data['contact_detail']);
            $this->email->set_newline("\r\n");
            $this->email->from($this->config->item('email_system', 'email'), 'Ordering System');
            $this->email->reply_to($create_data['contact_email'], $create_data['contact_name']);
            $this->email->to($this->config->item('email_sale', 'email'));
            $this->email->subject($create_data['contact_subject']);
            $this->email->message($message);

            if (!$this->email->send()) {
                //echo $this->email->print_debugger();
                //die();
            }


            redirect('member/history?status=payment');
        }
    }

    function get($product_type = 'all', $category_name = '', $promotion = '', $sorting = '', $page = 0)
    {


        $keyword = $this->input->post('k');
        $filter = array();
        $start = $page * $this->list_per_page;

        $this->load->model('product_category_model');

        if ($category_name == 'any')
            $category_name = '';
        if ($promotion == 'any')
            $promotion = '';


        if ($category_name != '') {
            $category_name = str_replace('-', ' ', $category_name);
            $category_id = $this->product_category_model->get_id_by_name($category_name);

            if ($category_id)
                $filter['product_category_id'] = $category_id;
        } else {
            $category_id = 0;
        }

        switch ($sorting) {
            case 'price':
                $sorting = 'price';
                $order_type = 'asc';
                break;
            case 'view':
                $sorting = 'view';
                $order_type = 'desc';
                break;
            default:
                $sorting = 'recent';
                $order_type = 'desc';
        }


        switch ($promotion) {
            case 'early_bird':
                $filter['is_early_bird'] = 1;
                break;
            case 'promotion':
                $filter['is_promotion'] = 1;
                break;
            case 'recommend':
                $filter['is_recommend'] = 1;
                break;
            case 'promotion_early';
                $filter['is_early_bird'] = 1;
                $filter['is_promotion'] = 1;
                break;
        }
        switch ($product_type) {
            case 'tour':
                $product_type = 'tour_package';
                break;
            case 'travel':
                $product_type = 'travel_package';
                break;
            case 'cruise':
                $product_type = 'cruise';
                break;
            case 'เรือสำราญ':
                $product_type = 'cruise';
                break;
            case 'ticket-hotel':
                $product_type = 'ticket_hotel';
                break;
            case 'ticket':
                $product_type = 'ticket';
                break;
            case 'others':
                $product_type = 'others';
                break;
            case 'hotel':
                $product_type = 'hotel';
                break;
            default:
                $product_type = 'all';
        }

//$this->product_model->all($product_type, $this->list_per_page, 0, $filter, $sorting, $order_type, $keyword),
        $i = 0;
        $product_db = $this->product_model->all($product_type, $this->list_per_page, $start, $filter, $sorting, $order_type, $keyword);
        //echo $this->db->last_query();
        $products = array();

        $product_ids = array();
        if ($product_db) {
            foreach ($product_db as $r) {
                $product_ids[] = $r->product_id;
            }

            $product_tag = $this->product_model->get_tags_by_many_products($product_ids, 'lifestyle');
        }

        foreach ($product_db as $p) {
            $products[$i]['thumb'] = get_thumb($p->product_thumb, 491, 326);
            $products[$i]['title'] = $this->session->userdata('lang') == 'en' ? $p->product_title_en : $p->product_title_th;

            if ($p->is_sold == 1) {
                $products[$i]['title'] = '<div class="badge badge-success" style="background:red">Sold out</div> ' . $products[$i]['title'];
            }

            $products[$i]['subtitle'] = $this->session->userdata('lang') == 'en' ? $p->product_subtitle_en : $p->product_subtitle_th;
            $products[$i]['period'] = $this->session->userdata('lang') == 'en' ? 'Period: ' . $p->product_period_en : 'เดินทาง: ' . $p->product_period_th;


            $highlight = $this->session->userdata('lang') == 'en' ? $p->product_highlight_en : $p->product_highlight_th;
            $regex = '#<li>(.*?)</li>#';
            preg_match($regex, $highlight, $matches);
            if (isset($matches[1])) {
                $products[$i]['highlight'] = strip_tags($matches[1]); //'[' . $p->product_code . '] ' . 
                if (count($matches) > 0) {

                    $products[$i]['highlight'] .= ' ...[อ่านต่อ]';
                }
            } else {
                $products[$i]['highlight'] = ''; //'[' . $p->product_code . '] ';
            }
            //$products[$i]['highlight'] = $this->session->userdata('lang') == 'en' ? strip_tags('[' . $p->product_code . '] ' . $p->product_highlight_en) : strip_tags('[' . $p->product_code . '] ' . $p->product_highlight_th);

            $products[$i]['product_discount_price'] = '';
            if ($p->product_discount_price) {
                $products[$i]['product_discount_price'] = '*ปกติ <del>' . number_format($p->product_discount_price) . '</del> ' . lang('baht') . '/' . lang('people_unit');
            }

            if ($p->product_start_price == 0) {
                $price = lang('negotiate');
            } else {
                $price = number_format($p->product_start_price);
            }
            $products[$i]['price'] = $price;
			$products[$i]['is_new'] = $p->is_new;

            $products[$i]['promotion'] = $p->is_promotion;
            $products[$i]['early_bird'] = $p->is_early_bird;
            $products[$i]['tags'] = $product_tag[$p->product_id];

            switch ($p->product_type) {
                case 'tour_package':
                    $products[$i]['package_type'] = 'tour-package';
                    $products[$i]['view_url'] = 'tour'; // ((int)$p->is_group_and_go==0)? 'tour' : 'groupandgo';
                    break;
                case 'travel_package':
                    $products[$i]['package_type'] = 'travel-package';
                    $products[$i]['view_url'] = 'travel'; // ((int)$p->is_group_and_go==0)? 'travel' : 'groupandgo';
                    break;
                case 'ticket':
                    $products[$i]['package_type'] = 'ticket-package';
                    $products[$i]['view_url'] = 'ticket';
                    break;
                case 'ticket':
                    $products[$i]['package_type'] = 'ticket-hotel';
                    $products[$i]['view_url'] = 'ticket-hotel';
                    break;
                case 'others':
                    $products[$i]['package_type'] = 'other-package';
                    $products[$i]['view_url'] = 'others';
                    break;
                case 'hotel':
                    $products[$i]['package_type'] = 'hotel';
                    $products[$i]['view_url'] = 'hotel';
                    break;
            }

            if ($p->airline_thumb != null) {
                $products[$i]['airline_thumb'] = get_thumb($p->airline_thumb, 80, 40);
                $products[$i]['airline_title'] = $this->session->userdata('lang') == 'en' ? $p->airline_title_en : $p->airline_title_th;
            } else {
                $products[$i]['airline_thumb'] = '';
                $products[$i]['airline_title'] = '';
            }
            $products[$i]['view_url'] .= '/v/' . $p->product_id . '/' . url_title($products[$i]['title']);
            if ($p->product_partner_id) {
                $products[$i]['partner'] = get_partner_logo($p->product_partner_id);
            } else {
                $products[$i]['partner'] = '';
            }
            $i++;
        }

        $output['products'] = $products;
        $output['total_product'] = $this->product_model->all_count($product_type, 0, 0, $filter, '', '', $keyword);

        echo json_encode($output);
    }

}

class Num_to_thai
{
#เอาของชาวบ้านเขามาจำที่มาไม่ไ้ด้แล้วมาปรับแต่งใหม่ แจกต่อ by songsaluang
#การเรียกใช้
#echo $number = 120000023.45 . '<br>';
#$callclassthai = new numtobahtthai();
#echo $callclassthai-> tothai($number); #หนึ่งร้อยยี่สิบล้านยี่สิบสามบาทสี่สิบห้าสตางค์

    public function tothai($number)
    {
        $numberformat = number_format($number, 2);
        $explode = explode('.', $numberformat);
        $baht = $explode[0];
        $stang = $explode[1];

        if ($stang == '00') {

            return $this->thai($baht) . 'บาทถ้วน';
        } else {
            return $this->thai($baht) . 'บาท' . $this->thai($stang) . 'สตางค์';
        }
    }

    public function thai($num)
    {
        $num = str_replace(',', '', $num);
        $num_decimal = explode('.', $num);
        $num = $num_decimal[0];
        $returnNumWord = '';
        $lenNumber = strlen($num);
        $lenNumber2 = $lenNumber - 1;
        $kaGroup = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
        $kaDigit = array('', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
        $kaDigitDecimal = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
        $ii = 0;
        for ($i = $lenNumber2; $i >= 0; $i--) {
            $kaNumWord[$i] = substr($num, $ii, 1);
            $ii++;
        }
        $ii = 0;
        for ($i = $lenNumber2; $i >= 0; $i--) {
            if (($kaNumWord[$i] == 2 && $i == 1) || ($kaNumWord[$i] == 2 && $i == 7)) {
                $kaDigit[$kaNumWord[$i]] = 'ยี่';
            } else {
                if ($kaNumWord[$i] == 2) {
                    $kaDigit[$kaNumWord[$i]] = 'สอง';
                }
                if (($kaNumWord[$i] == 1 && $i <= 2 && $i == 0) || ($kaNumWord[$i] == 1 && $lenNumber > 6 && $i == 6)) {
                    if ($kaNumWord[$i + 1] == 0) {
                        $kaDigit[$kaNumWord[$i]] = 'หนึ่ง';
                    } else {
                        $kaDigit[$kaNumWord[$i]] = 'เอ็ด';
                    }
                } else if (($kaNumWord[$i] == 1 && $i <= 2 && $i == 1) || ($kaNumWord[$i] == 1 && $lenNumber > 6 && $i == 7)) {
                    $kaDigit[$kaNumWord[$i]] = '';
                } else {
                    if ($kaNumWord[$i] == 1) {
                        $kaDigit[$kaNumWord[$i]] = 'หนึ่ง';
                    }
                }
            }
            if ($kaNumWord[$i] == 0) {
                if ($i != 6) {
                    $kaGroup[$i] = '';
                }
            }
            $kaNumWord[$i] = substr($num, $ii, 1);
            $ii++;
            $returnNumWord .= $kaDigit[$kaNumWord[$i]] . $kaGroup[$i];
        }
        return $returnNumWord;
    }

}
