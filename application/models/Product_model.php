<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function all($product_type = 'all', $limit = 16, $start = 0, $filter = array(), $order_by = '', $order = 'ASC', $keyword = '', $tags = array(), $not_id = null) {

        $this->db->distinct()->select('products.product_id,product_code,airlines.airline_title_th,airlines.airline_title_en,airlines.airline_thumb,product_thumb,product_pdf,product_title_th,product_title_en,product_category_id,product_type,'
                        . 'is_new,is_recommend,is_promotion,is_early_bird,is_sold,is_visible,is_group_and_go,is_cruise,product_start_price,product_discount_price,product_group_size,product_trip_style,'
                        . 'product_necessary_item,product_pocket_money,product_title_th,product_title_en,product_subtitle_th,product_subtitle_en,product_period_th,product_period_en,product_buy_before_en,product_buy_before_th,product_partner_id,'
                        . 'product_description_th,product_description_en,product_highlight_th,product_highlight_en,'
                        . 'meta_title_th,meta_title_en,meta_keyword_th,meta_keyword_en,meta_description_th,meta_description_en,view_count')
                ->from('products')
                ->join('airlines', 'products.product_airline_id=airlines.airline_id', 'LEFT');

        if ($not_id) {
            $this->db->where('products.product_id !=', $not_id);
        }

        if ($product_type != 'all') {
			if($product_type != 'cruise')
			{
				$this->db->where('products.product_type', $product_type);
			} else {
				$this->db->where('products.is_cruise', 1);
			}
        }
        /* else
          {
          $this->db->where('products.product_type !=','ticket');
          } */


        if (isset($filter['is_promotion']) && isset($filter['is_early_bird'])) {
            if (isset($filter['product_category_id'])) {
                // Hack for Europe //
                if ($filter['product_category_id'] == 27) {
                    $this->db->where('product_category_id=27 OR product_category_id=25');
                } else {
                    $this->db->where('product_category_id', $filter['product_category_id']);
                }
            }
            $this->db->where('(products.is_promotion=1 OR products.is_early_bird=1)');
        } else {
            // Hack for Europe //
            if (isset($filter['product_category_id']) && $filter['product_category_id'] == 27) {
                $this->db->where('(product_category_id=27 OR product_category_id=25)');
                unset($filter['product_category_id']);
                $this->db->where($filter);
            } else {
                $this->db->where($filter);
            }
        }


        if ($limit)
            $this->db->limit($limit, $start);

        if ($keyword != '') {

            $keywords = explode(' ', $keyword);

            $this->db->join('product_tag', 'product_tag.product_id = products.product_id');
            $this->db->join('tags', 'product_tag.tag_id = tags.tag_id');
            $this->db->group_start();
            foreach ($keywords as $key => $keyword) {

                $this->db->or_like('product_title_th', $keyword)
                        ->or_like('product_title_en', $keyword)
                        ->or_like('product_highlight_th', $keyword)
                        ->or_like('product_highlight_en', $keyword)
                        ->or_like('product_highlight_en', $keyword)
                        ->or_like('tags.tag_name', $keyword);
            }
            $this->db->group_end();




            //$tags = array_merge((array)$keywords, (array)$tags);
        }

        if ($tags) {


            $this->db->join('product_tag', 'product_tag.product_id = products.product_id');

            $tag_ids = array();

            foreach ($tags as $tag) {
                $tag_ids[] = $tag->tag_id;
            }


            $this->db->where_in('product_tag.tag_id', $tag_ids);

            $this->db->group_by('products.product_id');
        }


        // Order sold last //
        $this->db->order_by('products.is_sold', 'ASC');

        if ($order_by == 'price') {
            $this->db->order_by('products.is_recommend', 'DESC');
            $this->db->order_by('products.product_start_price', 'ASC');
        }

        if ($order_by == 'recent') {
            $this->db->order_by('products.is_recommend', 'DESC');
            $this->db->order_by('products.stocked_at', 'DESC');
        }

        if ($order_by == 'view') {
            $this->db->order_by('products.is_recommend', 'DESC');
            $this->db->order_by('products.view_count', 'DESC');
        }

        if ($order_by == 'booking') {
            $this->db->select('SUM(number_of_adults + number_of_children) as booking');
            $this->db->join('orders', 'orders.product_id = products.product_id');
            $this->db->where('orders.is_pending', 0);
            $this->db->where('orders.in_process', 0);
            $this->db->group_by('orders.product_id');
            $this->db->order_by('booking', $order);
        }

        if ($order_by == 'purchased') {
            $this->db->select('SUM(number_of_adults + number_of_children) as purchased');
            $this->db->join('orders', 'orders.product_id = products.product_id');
            $this->db->where('(order_status = "Paid" OR order_status="Deposit")');
            $this->db->group_by('orders.product_id');
            $this->db->order_by('purchased', $order);
        }

        if ($order_by == 'popular') {
            $this->db->select('SUM(number_of_adults + number_of_children) as booking,SUM(number_of_adults + number_of_children)/products.view_count as ratio');
            $this->db->where('orders.is_pending', 0);
            $this->db->join('orders', 'orders.product_id = products.product_id');
            $this->db->group_by('orders.product_id');
            $this->db->order_by('ratio', $order);
        }


        $this->db->where('products.is_visible', 1);
        // $this->db->where('products.is_group_and_go', 1);


        if (!$order_by) {
            $this->db->order_by('products.is_recommend', 'DESC');
            $this->db->order_by('FIELD(product_type,"ticket","others","tour_package","travel_package","ticket_hotel")', '', FALSE);
        }

        $return = $this->db->get()->result();

        // echo $this->db->last_query();

        return $return;
    }

    public function all_count($product_type = 'all', $limit = 0, $start = 0, $filter = array(), $order_by = 'date', $order = 'ASC', $keyword = '') {

        $this->db->select('product_id')->from('products');



        if ($product_type != 'all') {
			if($product_type!='cruise')
			{
				$this->db->where('products.product_type', $product_type);
			} else {
				$this->db->where('products.is_cruise', 1);
			}
        }

        $this->db->where('products.is_visible', 1);


        if (count($filter)) {
            $this->db->where($filter);
        }


        if ($keyword != '') {
            $this->db->like('product_title_th', $keyword);
            $this->db->or_like('product_title_en', $keyword);
            $this->db->or_like('product_highlight_th', $keyword);
            $this->db->or_like('product_highlight_en', $keyword);
            $this->db->or_like('product_description_th', $keyword);
            $this->db->or_like('product_description_en', $keyword);
        }


        return $this->db->count_all_results();
    }

    function product_get($id) {
        $this->update_view($id);

        $this->db->select('product_id,product_code,product_thumb,product_header,product_header2,product_header3,product_header4,product_airline_id,product_pdf,product_doc,product_title_th,product_title_en,product_detail,product_type,products.product_category_id,'
                        . 'is_new,is_recommend,is_promotion,is_promotion_gng,is_early_bird,is_sold,is_visible,is_group_and_go,is_cruise,is_force_static_visible,product_start_price,product_discount_price,product_group_size,product_trip_style,'
                        . 'product_necessary_item,product_pocket_money,product_title_th,product_title_en,product_subtitle_th,product_subtitle_en,product_period_th,product_period_en,product_buy_before_th,product_buy_before_en,product_partner_id,'
                        . 'product_description_th,product_description_en,product_description_gng_th,product_description_gng_en,product_condition_th,product_condition_en,product_highlight_th,product_highlight_en,product_summarize_th,product_summarize_en,'
                        . 'meta_title_th,meta_title_en,meta_keyword_th,meta_keyword_en,meta_description_th,meta_description_en,modified_by,airlines.airline_title_th,airlines.airline_title_en,airlines.airline_thumb,promotion_word')
                ->from('products')
                ->where('product_id', $id);

        $this->db->select('product_category_title_th,product_category_title_en,product_category_group');
        $this->db->join('product_categories', 'product_categories.product_category_id = products.product_category_id', 'left');
        $this->db->join('airlines', 'airlines.airline_id = products.product_airline_id', 'left');



        return $this->db->get()->row_array();
    }

    function product_all($limit = 8) {
        $this->db->select('products.product_id,product_code,product_thumb,product_pdf,product_title_th,product_title_en,product_category_id,product_type,'
                        . 'is_recommend,is_promotion,is_early_bird,is_sold,is_visible,is_group_and_go,product_start_price,product_discount_price,product_group_size,product_trip_style,'
                        . 'product_necessary_item,product_pocket_money,product_title_th,product_title_en,product_subtitle_th,product_subtitle_en,product_period_th,product_period_en,product_partner_id,'
                        . 'product_description_th,product_description_en,product_highlight_th,product_highlight_en,'
                        . 'meta_title_th,meta_title_en,meta_keyword_th,meta_keyword_en,meta_description_th,meta_description_en')
                ->from('products')
                ->limit($limit);


        return $this->db->get()->result();
    }

    // Change to Promotion / Early Bird
    function product_recommended_all($limit = 8) {
        $this->db->select('products.product_id,product_code,airlines.airline_title_th,airlines.airline_title_en,airlines.airline_thumb,product_thumb,product_pdf,product_title_th,product_title_en,product_category_id,product_type,'
                        . 'is_new,is_recommend,is_promotion,is_early_bird,is_sold,is_visible,is_group_and_go,product_start_price,product_discount_price,product_group_size,product_trip_style,'
                        . 'product_necessary_item,product_pocket_money,product_title_th,product_title_en,product_subtitle_th,product_subtitle_en,product_period_th,product_period_en,product_partner_id,'
                        . 'product_highlight_th,product_highlight_en')
                ->from('products')
                ->join('airlines', 'products.product_airline_id=airlines.airline_id', 'LEFT')
                ->where('products.is_visible', 1)
                ->where("(products.is_promotion = 1 OR products.is_early_bird = 1)")
                ->where("products.product_type <> 'ticket'")
                //->where("products.product_start_price <>", 0)
                ->order_by('products.view_count', 'DESC')
                ->limit($limit);


        return $this->db->get()->result();
    }

    // Change to Promotion / Early Bird
    function product_recommended_ticket($limit = 8) {
        $this->db->select('products.product_id,product_code,airlines.airline_title_th,airlines.airline_title_en,airlines.airline_thumb,product_thumb,product_pdf,product_title_th,product_title_en,product_partner_id,product_category_id,product_type,'
                        . 'is_recommend,is_promotion,is_early_bird,is_sold,is_visible,is_group_and_go,product_start_price,product_group_size,product_trip_style,'
                        . 'product_necessary_item,product_pocket_money,product_title_th,product_title_en,product_subtitle_th,product_subtitle_en,product_period_th,product_period_en,'
                        . 'product_highlight_th,product_highlight_en')
                ->from('products')
                ->join('airlines', 'products.product_airline_id=airlines.airline_id', 'LEFT')
                ->where('products.is_visible', 1)
                ->where("(products.is_promotion = 1 OR products.is_early_bird = 1)")
                ->where("products.product_type = 'ticket'")
                //->where("products.product_start_price <>", 0)
                ->order_by('products.view_count', 'DESC')
                ->limit($limit);


        return $this->db->get()->result();
    }

    function product_all_in($product_ids) {
        $product_ids = array_reverse($product_ids);
        $implode = implode(',', $product_ids);

        if (isset($_GET['haha']))
            echo $implode;

        $this->db->select('products.product_id,product_code,airlines.airline_title_th,airlines.airline_title_en,airlines.airline_thumb,product_thumb,product_pdf,product_title_th,product_title_en,product_partner_id,product_highlight_en,product_highlight_th,product_category_id,product_type,'
                        . 'is_new,is_recommend,is_promotion,is_early_bird,is_sold,is_visible,is_group_and_go,product_start_price,product_discount_price,product_group_size,product_trip_style,'
                        . 'product_necessary_item,product_pocket_money,product_title_th,product_title_en,product_subtitle_th,product_subtitle_en,product_period_th,product_period_en,'
                        . 'product_description_th,product_description_en,product_highlight_th,product_highlight_en,'
                        . 'meta_title_th,meta_title_en,meta_keyword_th,meta_keyword_en,meta_description_th,meta_description_en')
                ->from('products')
                ->join('airlines', 'products.product_airline_id=airlines.airline_id', 'LEFT')
                ->where_in('products.product_id', $product_ids)
                ->where('(products.is_visible=1 OR products.is_force_static_visible=1)');
        $this->db->order_by('FIELD(products.product_id,' . $implode . ')', '', FALSE);

        return $this->db->get()->result();
    }

    function product_all_in_location_tag($product_location_tags) {
        $tags = array_reverse($product_location_tags);
        $implode = implode(',', $tags);


        $this->db->select('products.product_id,product_code,airlines.airline_title_th,airlines.airline_title_en,airlines.airline_thumb,product_thumb,product_pdf,product_title_th,product_partner_id,product_title_en,product_highlight_en,product_highlight_th,product_category_id,product_type,'
                        . 'is_new,is_recommend,is_promotion,is_early_bird,is_sold,is_visible,is_group_and_go,product_start_price,product_discount_price,product_group_size,product_trip_style,'
                        . 'product_necessary_item,product_pocket_money,product_title_th,product_title_en,product_subtitle_th,product_subtitle_en,product_period_th,product_period_en,'
                        . 'product_description_th,product_description_en,product_highlight_th,product_highlight_en,'
                        . 'meta_title_th,meta_title_en,meta_keyword_th,meta_keyword_en,meta_description_th,meta_description_en')
                ->from('products')
                ->join('airlines', 'products.product_airline_id=airlines.airline_id', 'LEFT')
                ->where('products.is_visible', 1);
        $this->db->join('product_tag', 'product_tag.product_id = products.product_id');
        $this->db->join('tags', 'product_tag.tag_id = tags.tag_id');

        foreach ($tags as $tag) {

            $this->db->like('tags.tag_name', $tag);
        }

        $this->db->order_by('FIELD(product_type,"ticket","ticket_hotel","others","travel_package","tour_package"),product_start_price DESC', '', FALSE);

        $query = $this->db->get()->result();
        //echo $this->db->last_query();
        return $query;
    }

    // function get_tags($id) {
    //     $this->db->select('tags.tag_id,tags.tag_name');
    //     $this->db->join('product_tag', 'product_tag.tag_id = tags.tag_id');
    //     $this->db->where('product_tag.product_id', $id);
    //     return $this->db->get('tags')->result();
    // }

    function get_product_type($id) {
        return $this->db->select('product_type')->where('product_id', $id)
                        ->get('products')->row()->product_type;
    }

    function get_product_code($id) {
        return $this->db->select('product_code')->where('product_id', $id)
                        ->get('products')->row()->product_code;
    }

    function delete($id) {
        //Check & Delete thumb if exist 
        $this->db->select('product_thumb')
                ->from('products')
                ->where('product_id', $id)
                ->limit(1);

        $exist_thumb = $this->db->get()->row();

        if (isset($exist_thumb->product_thumb) && $exist_thumb->product_thumb != '') {

            foreach (glob('uploads/product/thumbs/*/' . basename($exist_thumb->product_thumb)) as $file) {
                @unlink($file);
            }
            @unlink(('uploads/product/' . basename($exist_thumb->product_thumb)));
        }

        //Check & Delete header if exist 
        $this->db->select('product_header')
                ->from('products')
                ->where('product_id', $id)
                ->limit(1);

        $exist_thumb = $this->db->get()->row();

        if (isset($exist_thumb->product_header) && $exist_thumb->product_header != '') {

            foreach (glob('uploads/product/thumbs/*/' . basename($exist_thumb->product_header)) as $file) {
                @unlink($file);
            }
            @unlink(('uploads/product/' . basename($exist_thumb->product_header)));
        }

        //Check & Delete thumb if exist 
        $this->db->select('product_pdf')
                ->from('products')
                ->where('product_id', $id)
                ->limit(1);

        $exist_thumb = $this->db->get()->row();

        if (isset($exist_thumb->product_pdf) && $exist_thumb->product_pdf != '') {

            foreach (glob('uploads/product/thumbs/*/' . basename($exist_thumb->product_pdf)) as $file) {
                @unlink($file);
            }
            @unlink(('uploads/product/' . basename($exist_thumb->product_pdf)));
        }

        //Check & Delete thumb if exist 
        $this->db->select('product_doc')
                ->from('products')
                ->where('product_id', $id)
                ->limit(1);

        $exist_thumb = $this->db->get()->row();

        if (isset($exist_thumb->product_doc) && $exist_thumb->product_doc != '') {

            foreach (glob('uploads/product/thumbs/*/' . basename($exist_thumb->product_doc)) as $file) {
                @unlink($file);
            }
            @unlink(('uploads/product/' . basename($exist_thumb->product_doc)));
        }

        $this->db->delete('products', array('product_id' => $id));
        return TRUE;
    }

    function get_tags($product_id, $tag_type) {
        return $this->db->select('product_tag.tag_id,tags.tag_name')
                        ->from('product_tag')
                        ->where('product_tag.product_id', $product_id)
                        ->join('tags', 'tags.tag_id = product_tag.tag_id AND tags.tag_type = "' . $tag_type . '"')
                        ->get()->result();
    }

    function get_tags_by_many_products($product_ids, $tag_type) {
        $result = $this->db->select('product_id,product_tag.tag_id,tags.tag_name')
                        ->from('product_tag')
                        ->where_in('product_tag.product_id', $product_ids)
                        ->join('tags', 'tags.tag_id = product_tag.tag_id AND tags.tag_type = "' . $tag_type . '"')
                        ->get()->result();
        $product_array = array();
        foreach ($product_ids as $product_id) {
            $product_array[$product_id] = array();
        }

        if ($result) {
            foreach ($result as $row) {
                $product_array[$row->product_id][] = $row;
            }
        }
        return $product_array;
    }

    function add_tags($product_id, $tags = array(), $tag_type) {

        $original_tag = $this->db->select('product_tag.tag_id')
                        ->from('product_tag')
                        ->where('product_tag.product_id', $product_id)
                        ->join('tags', 'tags.tag_id = product_tag.tag_id AND tags.tag_type = "' . $tag_type . '"')
                        ->get()->result();

        foreach ($original_tag as $tag) {
            $this->db->where('product_id', $product_id)
                    ->where('tag_id', $tag->tag_id)
                    ->delete('product_tag');
        }

        $tags = array_unique($tags);

        // User array for temp tag_id to avoid duplicate id
        $tag_ids = array();
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $tag = trim($tag);

                $query = $this->db->where('tag_name', $tag)
                                ->where('tag_type', $tag_type)
                                ->get('tags')->row();

                if (count($query)) {
                    $tag_ids[] = $query->tag_id;
                } elseif ($tag != '') {

                    $this->db->insert('tags', array('tag_name' => $tag, 'tag_type' => $tag_type));
                    $tag_id = $this->db->insert_id();
                    $tag_ids[] = $tag_id;
                }
                //echo $tag;
            }
        }


        foreach ($tag_ids as $tag_id) {

            $this->db->insert('product_tag', array('product_id' => $product_id, 'tag_id' => $tag_id));
        }


        $this->db->query('DELETE FROM tags WHERE tags.tag_id NOT IN (SELECT product_tag.tag_id FROM product_tag UNION SELECT article_tag.tag_id FROM article_tag)');
        return TRUE;
    }

    function delete_photo_header($id) {

        //Check & Delete thumb if exist 
        $this->db->select('product_header')
                ->from('products')
                ->where('product_id', $id)
                ->limit(1);

        $exist_thumb = $this->db->get()->row();

        if (isset($exist_thumb->product_header) && $exist_thumb->product_header != '') {

            foreach (glob('uploads/product/thumbs/*/' . basename($exist_thumb->product_header)) as $file) {
                @unlink($file);
            }
            @unlink(('uploads/product/' . basename($exist_thumb->product_header)));
        }

        $this->db->where('product_id', $id)
                ->update('products', array('product_header' => NULL));
        return TRUE;
    }

    function delete_photo_header_gng($id, $filed) {

        //Check & Delete thumb if exist 
        $this->db->select($filed)
                ->from('products')
                ->where('product_id', $id)
                ->limit(1);

        $exist_thumb = $this->db->get()->row();

        if (isset($exist_thumb->$filed) && $exist_thumb->$filed != '') {

            foreach (glob('uploads/product/thumbs/*/' . basename($exist_thumb->$filed)) as $file) {
                @unlink($file);
            }
            @unlink(('uploads/product/' . basename($exist_thumb->$filed)));
        }

        $this->db->where('product_id', $id)
                ->update('products', array($filed => NULL));
        return TRUE;
    }

    function delete_doc($id) {

        //Check & Delete thumb if exist 
        $this->db->select('product_doc')
                ->from('products')
                ->where('product_id', $id)
                ->limit(1);

        $exist_thumb = $this->db->get()->row();

        if (isset($exist_thumb->product_doc) && $exist_thumb->product_doc != '') {

            foreach (glob('uploads/product/thumbs/*/' . basename($exist_thumb->product_doc)) as $file) {
                @unlink($file);
            }
            @unlink(('uploads/product/' . basename($exist_thumb->product_doc)));
        }

        $this->db->where('product_id', $id)
                ->update('products', array('product_doc' => ''));
        return TRUE;
    }

    function delete_pdf($id) {

        //Check & Delete thumb if exist 
        $this->db->select('product_pdf')
                ->from('products')
                ->where('product_id', $id)
                ->limit(1);

        $exist_thumb = $this->db->get()->row();

        if (isset($exist_thumb->product_pdf) && $exist_thumb->product_pdf != '') {

            foreach (glob('uploads/product/thumbs/*/' . basename($exist_thumb->product_pdf)) as $file) {
                @unlink($file);
            }
            @unlink(('uploads/product/' . basename($exist_thumb->product_pdf)));
        }

        $this->db->where('product_id', $id)
                ->update('products', array('product_pdf' => ''));
        return TRUE;
    }

    function product_create($create_data) {

        $create_data['created_at'] = time();
        $create_data['modified_at'] = time();
        $create_data['stocked_at'] = time();
        $this->db->insert('products', $create_data);

        return $this->db->insert_id();
    }

    function product_edit($id, $edit_data) {
        return $this->db->where('product_id', $id)->update('products', $edit_data);
    }

    function product_status($id, $status) {
        if ($status == '1') {
            $st = '0';
        } else {
            $st = '1';
        }
        return $this->db->where('product_id', $id)
                        ->update('products', array('is_visible' => $st));
    }

    function add_thumb($id, $data) {

        //Check & Delete thumb if exist 
        $this->db->select('product_thumb')
                ->from('products')
                ->where('product_id', $id)
                ->limit(1);

        $exist_thumb = $this->db->get()->row();

        if (isset($exist_thumb->product_thumb) && $exist_thumb->product_thumb != '') {

            $glob = glob('uploads/product/thumbs/*/' . basename($exist_thumb->product_thumb));
            if ($glob)
                foreach ($glob as $file) {
                    @unlink($file);
                }
            @unlink(('uploads/product/' . basename($exist_thumb->product_thumb)));
        }


        $thumb = $data['media_name'];

        $this->db->where('product_id', $id)
                ->update('products', array('product_thumb' => $thumb));

        return TRUE;
    }

    function add_header($id, $data) {

        //Check & Delete thumb if exist 
        $this->db->select('product_header')
                ->from('products')
                ->where('product_id', $id)
                ->limit(1);

        $exist_thumb = $this->db->get()->row();

        if (isset($exist_thumb->product_header) && $exist_thumb->product_header != '') {
            $glob = glob('uploads/product/thumbs/*/' . basename($exist_thumb->product_header));
            if ($glob)
                foreach ($glob as $file) {
                    @unlink($file);
                }
            @unlink(('uploads/product/' . basename($exist_thumb->product_header)));
        }


        $thumb = $data['media_name'];

        $this->db->where('product_id', $id)
                ->update('products', array('product_header' => $thumb));

        return TRUE;
    }

    function add_header_gng($id, $data, $product_header) {

        //Check & Delete thumb if exist 
        $this->db->select($product_header)
                ->from('products')
                ->where('product_id', $id)
                ->limit(1);

        $exist_thumb = $this->db->get()->row();

        if (isset($exist_thumb->$product_header) && $exist_thumb->$product_header != '') {
            $glob = glob('uploads/product/thumbs/*/' . basename($exist_thumb->$product_header));
            if ($glob)
                foreach ($glob as $file) {
                    @unlink($file);
                }
            @unlink(('uploads/product/' . basename($exist_thumb->$product_header)));
        }


        $thumb = $data['media_name'];

        $this->db->where('product_id', $id)
                ->update('products', array($product_header => $thumb));
//echo $thumb;exit();

        return TRUE;
    }

    function add_pdf($id, $data) {

        //Check & Delete thumb if exist 
        $this->db->select('product_pdf')
                ->from('products')
                ->where('product_id', $id)
                ->limit(1);

        $exist_thumb = $this->db->get()->row();

        if (isset($exist_thumb->product_pdf) && $exist_thumb->product_pdf != '') {
            $glob = glob('uploads/product/thumbs/*/' . basename($exist_thumb->product_pdf));
            if ($glob)
                foreach ($glob as $file) {
                    @unlink($file);
                }
            @unlink(('uploads/product/' . basename($exist_thumb->product_pdf)));
        }


        $thumb = $data['media_name'];

        $this->db->where('product_id', $id)
                ->update('products', array('product_pdf' => $thumb));

        return TRUE;
    }

    function add_doc($id, $data) {

        //Check & Delete thumb if exist 
        $this->db->select('product_doc')
                ->from('products')
                ->where('product_id', $id)
                ->limit(1);

        $exist_thumb = $this->db->get()->row();

        if (isset($exist_thumb->product_doc) && $exist_thumb->product_doc != '') {

            $glob = glob('uploads/product/thumbs/*/' . basename($exist_thumb->product_doc));
            if ($glob)
                foreach ($glob as $file) {
                    @unlink($file);
                }
            @unlink(('uploads/product/' . basename($exist_thumb->product_doc)));
        }


        $thumb = $data['media_name'];

        $this->db->where('product_id', $id)
                ->update('products', array('product_doc' => $thumb));

        return TRUE;
    }

    function count_product_kradan_pending() {
        $this->db->where('product_category_id', null);
        $this->db->where('is_visible', 0);
        return $this->db->count_all_results('products');
    }

    function count_product($product_type = 0, $category_id = 0, $is_promotion = 0, $is_early_bird = 0, $is_recommend = 0, $is_group_and_go = 0) {
        if ($product_type) {
            $this->db->where('product_type', $product_type);
        }
        if ($category_id != '') {
            $this->db->where('product_category_id', $category_id);
        }

        if ($is_promotion != 0) {
            $this->db->where('is_promotion', $is_promotion);
        }
        if ($is_early_bird != 0) {
            $this->db->where('is_early_bird', $is_early_bird);
        }
        if ($is_recommend != 0) {
            $this->db->where('is_recommend', $is_recommend);
        }
        if ($is_group_and_go != 0) {
            $this->db->where('is_group_and_go', $is_group_and_go);
        }
        $this->db->where('is_visible', 1);
        return $this->db->count_all_results('products');
    }

    function product_search($keyword, $page, $limit, $type = '') {
        $this->db->select('product_id,product_code,product_thumb,product_title_th,product_highlight_th,product_start_price');

        $where = "  (product_title_th LIKE '%$keyword%' 
                    OR product_code LIKE '%$keyword%'"
                . "OR product_description_th LIKE '%$keyword%')";

        $this->db->where($where);

        if ($type != '') {
            $this->db->where('product_type', $type);
        }

        $this->db->limit(($page - 1) * $limit + $limit, ((($page - 1) * $limit)));
        $this->db->where('is_visible', 1);
        return $this->db->get('products')->result();
    }

    function product_search_count($keyword, $type = '') {

        $this->db->select('product_id');
        $where = "  (product_title_th LIKE '%$keyword%' 
                    OR product_code LIKE '%$keyword%')";

        $this->db->where($where);

        if ($type != '') {
            $this->db->where('product_type', $type);
        }
        $this->db->where('is_visible', 1);
        return $this->db->get('products')->num_rows();
    }

    function update_view($product_id) {
        $ip_address = $this->input->ip_address();
        $is_view = $this->db->where('product_id', $product_id)
                        ->where('view_ip', $ip_address)
                        ->get('products_views')->num_rows();



        if ($is_view) {

            $view = $this->db->where('product_id', $product_id)->limit(1)->get('products_views')->row();

            if ($view->expire_date < date('Y-m-d H:i:s')) {
                // View +1

                $data = array(
                    'view_value' => $view->view_value + 1,
                    'modify_date' => date('Y-m-d H:i:s'),
                    'expire_date' => date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")))
                );

                $where = array(
                    'product_id' => $product_id,
                    'view_ip' => $ip_address
                );

                $this->db->update('products_views', $data, $where);

                // Add more //
                $this->db->set('view_count', 'view_count+1', FALSE)->where('product_id', $product_id)->update('products');
            }
        } else {
            $data = array(
                'product_id' => $product_id,
                'view_ip' => $ip_address,
                'view_value' => 1,
                'create_date' => date('Y-m-d H:i:s'),
                'modify_date' => date('Y-m-d H:i:s'),
                'expire_date' => date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")))
            );

            $this->db->insert('products_views', $data);

            // Add more //
            $this->db->set('view_count', 'view_count+1', FALSE)->where('product_id', $product_id)->update('products');
        }

        $this->db->where('expire_date < ', date('Y-m-d H:i:s', time() - 172800))->delete('products_views');

        return TRUE;
    }

    // Change to recommended //
    function promotion_product() {

        $this->db->select('MIN(product_start_price) as product_start_price,products.product_category_id,product_category_title_th,product_category_title_en,product_title_th,product_title_en,product_subtitle_th,product_subtitle_en,product_period_th,product_period_en,product_id,product_thumb,product_type')
                ->from('products')
                ->order_by('product_start_price', 'ASC');
        $this->db->where('products.is_visible', 1)
                ->where("products.is_recommend", 1)
                ->where("products.product_start_price <>", 0);
        $this->db->group_by('products.product_category_id');

        $this->db->join('product_categories', 'product_categories.product_category_id = products.product_category_id');

        return $this->db->get()->result();
    }

//==================================== Group & Go =========================================
    function product_promotion_gng($limit = 8) {
        $this->db->select('products.product_id,product_code,airlines.airline_title_th,airlines.airline_title_en,airlines.airline_thumb,product_thumb,product_pdf,product_title_th,product_title_en,product_category_id,product_type,'
                        . 'is_new,is_recommend,is_promotion_gng,is_early_bird,is_sold,is_visible,is_group_and_go,product_start_price,product_discount_price,product_group_size,product_trip_style,'
                        . 'product_necessary_item,product_pocket_money,product_title_th,product_title_en,product_subtitle_th,product_subtitle_en,product_period_th,product_period_en,product_partner_id,'
                        . 'product_highlight_th,product_highlight_en')
                ->from('products')
                ->join('airlines', 'products.product_airline_id=airlines.airline_id', 'LEFT')
                ->where('products.is_visible', 1)
                ->where('products.is_promotion_gng', 1)
                ->where('products.is_group_and_go', 1)
                ->where("products.product_type = 'tour_package'")
                //->where("products.product_start_price <>", 0)
                ->order_by('products.view_count', 'DESC')
                ->limit($limit);


        return $this->db->get()->result();
    }

    function product_promotion_by_country($limit = 10, $category_id = 0) {
        $this->db->select('products.product_id,product_code,airlines.airline_title_th,airlines.airline_title_en,airlines.airline_thumb,product_thumb,product_pdf,product_title_th,product_title_en,products.product_category_id,product_type,'
                        . 'is_new,is_recommend,is_promotion_gng,is_early_bird,is_sold,is_visible,is_group_and_go,product_start_price,product_discount_price,product_group_size,product_trip_style,'
                        . 'product_necessary_item,product_pocket_money,product_title_th,product_title_en,product_subtitle_th,product_subtitle_en,product_period_th,product_period_en,product_partner_id,'
                        . 'product_highlight_th,product_highlight_en')
                ->from('products')
                ->join('airlines', 'products.product_airline_id=airlines.airline_id', 'LEFT');

        if ($category_id != 0) {
            $this->db->join('product_categories', 'products.product_category_id=product_categories.product_category_id', 'LEFT');
            $this->db->where('product_categories.product_category_id', $category_id);
        }

        $this->db->where('products.is_visible', 1);
        $this->db->where('products.is_promotion_gng', 1);
        $this->db->where('products.is_group_and_go', 1);
        $this->db->where("products.product_type = 'tour_package'");
        //->where("products.product_start_price <>", 0)
        $this->db->order_by('products.product_start_price', 'ASC');
        $this->db->order_by('products.view_count', 'DESC');
        $this->db->limit($limit);


        return $this->db->get()->result();
    }

    function product_filter_gng($limit = 10, $category_id = 0, $period = '', $group_size = 4, $duration = '', $product_id = 0) {
        $this->db->select('products.product_id,products.product_code,products.product_thumb,products.product_pdf,products.product_title_th,products.product_title_en,products.product_category_id,products.product_type,'
                        . 'products.is_new,products.is_recommend,products.is_promotion,products.is_early_bird,products.is_sold,products.is_visible,products.is_group_and_go,products.product_start_price,products.product_discount_price,products.product_group_size,products.product_trip_style,'
                        . 'products.product_title_th,products.product_title_en,products.product_subtitle_th,products.product_subtitle_en,products.product_period_th,products.product_period_en,products.product_partner_id,'
                        . 'products.product_highlight_th,products.product_highlight_en')
                ->from('products');



        $this->db->join('product_categories', 'products.product_category_id=product_categories.product_category_id', 'LEFT');
        if ($product_id != 0) {
            $this->db->where('products.product_id', $product_id);
        }

        if ($category_id != 0) {
            $this->db->where('product_categories.product_category_id', $category_id);
        }
        /*      else
          {
          $this->db ->where('product_categories.product_category_id',$category_id);
          }
         */
        $this->db->join('product_stocks', 'products.product_id=product_stocks.product_id', 'LEFT');
        if ($period != '') {
            $this->db->where("DATE_FORMAT(product_stocks.product_stock_depart_at,'%Y-%m') =", $period);
        } else {
            $this->db->where("DATE_FORMAT(product_stocks.product_stock_depart_at,'%Y-%m') >=", date('Y-m'));
        }

        if ($duration < 5) {
            /*
              $this->db->where('DATEDIFF(product_stocks.product_stock_arrive_at, product_stocks.product_stock_depart_at) >=', (int) $duration);
              $this->db->where('DATEDIFF(product_stocks.product_stock_arrive_at, product_stocks.product_stock_depart_at) <=', (int) $duration + 1);
             */
            $this->db->where('DATEDIFF(product_stocks.product_stock_arrive_at, product_stocks.product_stock_depart_at) >=', (int) $duration - 1);
            $this->db->where('DATEDIFF(product_stocks.product_stock_arrive_at, product_stocks.product_stock_depart_at) <=', (int) $duration);
        } else {
            // $this->db->where('DATEDIFF(product_stocks.product_stock_arrive_at, product_stocks.product_stock_depart_at) >=', (int) $duration);
            $this->db->where('DATEDIFF(product_stocks.product_stock_arrive_at, product_stocks.product_stock_depart_at) >=', (int) $duration - 1);
        }

        $this->db->where("CAST(products.product_group_size AS SIGNED) <=", (int) $group_size); //ขั้นต่ำที่จะเดินทางได้ # ถ้าใช้ >= ถ้าเลือกมา 4 แต่ group size เป็น 6 จะไม่ได้ราคาที่แสดง
        $this->db->where('products.is_visible', 1);
        $this->db->where('products.is_group_and_go', 1);
        $this->db->where("(products.product_type = 'tour_package'");
        $this->db->or_where("products.product_type = 'travel_package')");
        //->where("products.product_start_price <>", 0)
        $this->db->group_by('products.product_id');
        $this->db->order_by('products.view_count', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result(); //$this->db->get()->result(); //$this->db->get_compiled_select();//$this->db->get()->result();
    }

    function product_gng_id($product_id = 0) {
        $this->db->select('products.product_id,products.product_code,products.product_thumb,products.product_pdf,products.product_title_th,products.product_title_en,products.product_category_id,products.product_type,'
                        . 'products.is_new,products.is_recommend,products.is_promotion,products.is_early_bird,products.is_sold,products.is_visible,products.is_group_and_go,products.product_start_price,products.product_discount_price,products.product_group_size,products.product_trip_style,'
                        . 'products.product_title_th,products.product_title_en,products.product_subtitle_th,products.product_subtitle_en,products.product_period_th,products.product_period_en,products.product_partner_id,'
                        . 'products.product_highlight_th,products.product_highlight_en')
                ->from('products');


        $this->db->where('products.product_id', $product_id);
        $this->db->join('product_categories', 'products.product_category_id=product_categories.product_category_id', 'LEFT');

        $this->db->join('product_stocks', 'products.product_id=product_stocks.product_id', 'LEFT');
        /*  if ($product_id != 0) {
          $this->db->where('products.product_id', $product_id);
          }

          if ($category_id != 0) {
          $this->db->where('product_categories.product_category_id', $category_id);
          }

          $this->db->join('product_stocks', 'products.product_id=product_stocks.product_id', 'LEFT');
          if ($period != '') {
          $this->db->where("DATE_FORMAT(product_stocks.product_stock_depart_at,'%Y-%m') =", $period);
          } else {
          $this->db->where("DATE_FORMAT(product_stocks.product_stock_depart_at,'%Y-%m') >=", date('Y-m'));
          }

          $this->db->where("CAST(products.product_group_size AS SIGNED) <=", (int) $group_size); */
        $this->db->where('products.is_visible', 1);
        $this->db->where('products.is_group_and_go', 1);
        $this->db->where("(products.product_type = 'tour_package'");
        $this->db->or_where("products.product_type = 'travel_package')");
        $this->db->group_by('products.product_id');
        $this->db->order_by('products.view_count', 'DESC');
        //  $this->db->limit($limit);

        return $this->db->get()->result(); //$this->db->get()->result(); //$this->db->get_compiled_select();//$this->db->get()->result();
    }

    function product_lifestyle_by_country($limit = 10, $lifestyle_id = 0, $category_id = 0) {

        $this->db->select('products.product_id,product_code,airlines.airline_title_th,airlines.airline_title_en,airlines.airline_thumb,product_thumb,product_pdf,product_title_th,product_title_en,products.product_category_id,product_type,'
                        . 'is_new,is_recommend,is_promotion,is_early_bird,is_sold,is_visible,is_group_and_go,product_start_price,product_discount_price,product_group_size,product_trip_style,'
                        . 'product_necessary_item,product_pocket_money,product_title_th,product_title_en,product_subtitle_th,product_subtitle_en,product_period_th,product_period_en,product_partner_id,'
                        . 'product_highlight_th,product_highlight_en')
                ->from('products')
                ->join('airlines', 'products.product_airline_id=airlines.airline_id', 'LEFT')
                ->join('product_tag', 'product_tag.product_id = products.product_id')
                ->where('product_tag.tag_id', $lifestyle_id);

        if ($category_id != 0) {
            $this->db->join('product_categories', 'products.product_category_id=product_categories.product_category_id', 'LEFT');
            $this->db->where('product_categories.product_category_id', $category_id);
        }

        $this->db->where('products.is_visible', 1);
        $this->db->where('products.is_group_and_go', 1);
        $this->db->where("products.product_type = 'tour_package'");
        $this->db->order_by('products.product_start_price', 'ASC');
        $this->db->order_by('products.view_count', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result(); //$this->db->get_compiled_select();//$this->db->get()->result();//
    }

    function product_by_country($limit = 10, $category_id = 0) {
        $this->db->select('products.product_id,product_code,airlines.airline_title_th,airlines.airline_title_en,airlines.airline_thumb,product_thumb,product_pdf,product_title_th,product_title_en,products.product_category_id,product_type,'
                        . 'is_new,is_recommend,is_promotion,is_early_bird,is_sold,is_visible,is_group_and_go,product_start_price,product_discount_price,product_group_size,product_trip_style,'
                        . 'product_necessary_item,product_pocket_money,product_title_th,product_title_en,product_subtitle_th,product_subtitle_en,product_period_th,product_period_en,product_partner_id,'
                        . 'product_highlight_th,product_highlight_en')
                ->from('products')
                ->join('airlines', 'products.product_airline_id=airlines.airline_id', 'LEFT');

        if ($category_id != 0) {
            $this->db->join('product_categories', 'products.product_category_id=product_categories.product_category_id', 'LEFT');
            $this->db->where('product_categories.product_category_id', $category_id);
        }

        $this->db->where('products.is_visible', 1);
        $this->db->where('products.is_group_and_go', 1);
        $this->db->where("(products.product_type = 'tour_package'");
        $this->db->or_where("products.product_type = 'travel_package')");
        $this->db->order_by('products.product_start_price', 'ASC');
        $this->db->order_by('products.view_count', 'DESC');
        $this->db->limit($limit);


        return $this->db->get()->result();//$this->db->get()->result();//$this->db->get_compiled_select();
    }

    function product_get_country($id) {

        $this->db->select('product_categories.product_category_title_th,'
                        . 'product_categories.product_category_title_en, product_categories.product_category_id,'
                        . ',products.product_title_th, products.product_title_en, products.product_group_size')
                ->from('product_categories')
                ->join('products', 'products.product_category_id = product_categories.product_category_id')
                ->where('product_id', $id);

        return $this->db->get()->row_array();
    }

    function product_get_condition($id) {
        $this->update_view($id);

        $this->db->select('product_condition_th,product_condition_en')
                ->from('products')
                ->where('product_id', $id);

//        $this->db->select('product_category_title_th,product_category_title_en,product_category_group');
//        $this->db->join('product_categories', 'product_categories.product_category_id = products.product_category_id', 'left');
//        $this->db->join('airlines', 'airlines.airline_id = products.product_airline_id', 'left');



        return $this->db->get()->row_array();
    }

    public function gng_all($product_type = 'all', $limit = 16, $start = 0, $filter = array(), $order_by = '', $order = 'ASC', $keyword = '', $tags = array(), $not_id = null) {

        $this->db->distinct()->select('products.product_id,product_code,airlines.airline_title_th,airlines.airline_title_en,airlines.airline_thumb,product_thumb,product_pdf,product_title_th,product_title_en,product_category_id,product_type,'
                        . 'is_new,is_recommend,is_promotion,is_early_bird,is_sold,is_visible,is_group_and_go,product_start_price,product_discount_price,product_group_size,product_trip_style,'
                        . 'product_necessary_item,product_pocket_money,product_title_th,product_title_en,product_subtitle_th,product_subtitle_en,product_period_th,product_period_en,product_buy_before_en,product_buy_before_th,product_partner_id,'
                        . 'product_description_th,product_description_en,product_highlight_th,product_highlight_en,'
                        . 'meta_title_th,meta_title_en,meta_keyword_th,meta_keyword_en,meta_description_th,meta_description_en,view_count')
                ->from('products')
                ->join('airlines', 'products.product_airline_id=airlines.airline_id', 'LEFT');

        if ($not_id) {
            $this->db->where('products.product_id !=', $not_id);
        }

        if ($product_type == 'tour_package' || $product_type == 'travel_package') {
			$this->db->where("(products.product_type='tour_package'");
			$this->db->or_where("products.product_type='travel_package')");
        }
        /* else
          {
          $this->db->where('products.product_type !=','ticket');
          } */


        if ($limit)
            $this->db->limit($limit, $start);

        if ($keyword != '') {

            $keywords = explode(' ', $keyword);

            $this->db->join('product_tag', 'product_tag.product_id = products.product_id');
            $this->db->join('tags', 'product_tag.tag_id = tags.tag_id');
            $this->db->group_start();
            foreach ($keywords as $key => $keyword) {

                $this->db->or_like('product_title_th', $keyword)
                        ->or_like('product_title_en', $keyword)
                        ->or_like('product_highlight_th', $keyword)
                        ->or_like('product_highlight_en', $keyword)
                        ->or_like('product_highlight_en', $keyword)
                        ->or_like('tags.tag_name', $keyword);
            }
            $this->db->group_end();




            //$tags = array_merge((array)$keywords, (array)$tags);
        }

        if ($tags) {


            $this->db->join('product_tag', 'product_tag.product_id = products.product_id');

            $tag_ids = array();

            foreach ($tags as $tag) {
                $tag_ids[] = $tag->tag_id;
            }


            $this->db->where_in('product_tag.tag_id', $tag_ids);

            $this->db->group_by('products.product_id');
        }


        // Order sold last //
        $this->db->order_by('products.is_sold', 'ASC');

        if ($order_by == 'price') {
            $this->db->order_by('products.is_recommend', 'DESC');
            $this->db->order_by('products.product_start_price', 'ASC');
        }

        if ($order_by == 'recent') {
            $this->db->order_by('products.is_recommend', 'DESC');
            $this->db->order_by('products.stocked_at', 'DESC');
        }

        if ($order_by == 'view') {
            $this->db->order_by('products.is_recommend', 'DESC');
            $this->db->order_by('products.view_count', 'DESC');
        }

        if ($order_by == 'booking') {
            $this->db->select('SUM(number_of_adults + number_of_children) as booking');
            $this->db->join('orders', 'orders.product_id = products.product_id');
            $this->db->where('orders.is_pending', 0);
            $this->db->where('orders.in_process', 0);
            $this->db->group_by('orders.product_id');
            $this->db->order_by('booking', $order);
        }

        if ($order_by == 'purchased') {
            $this->db->select('SUM(number_of_adults + number_of_children) as purchased');
            $this->db->join('orders', 'orders.product_id = products.product_id');
            $this->db->where('(order_status = "Paid" OR order_status="Deposit")');
            $this->db->group_by('orders.product_id');
            $this->db->order_by('purchased', $order);
        }

        if ($order_by == 'popular') {
            $this->db->select('SUM(number_of_adults + number_of_children) as booking,SUM(number_of_adults + number_of_children)/products.view_count as ratio');
            $this->db->where('orders.is_pending', 0);
            $this->db->join('orders', 'orders.product_id = products.product_id');
            $this->db->group_by('orders.product_id');
            $this->db->order_by('ratio', $order);
        }


        $this->db->where('products.is_visible', 1);
        $this->db->where('products.is_group_and_go', 1); //เพิ่มมา


        if (!$order_by) {
            $this->db->order_by('products.is_recommend', 'DESC');
            $this->db->order_by('FIELD(product_type,"ticket","others","tour_package","travel_package","ticket_hotel")', '', FALSE);
        }

        $return = $this->db->get()->result();

        // echo $this->db->last_query();

        return $return;
    }

	public function product_get_airline_name($product_id=0){
        $this->db->select('airlines.airline_title_th,airlines.airline_title_en')
				->from('products')
				->join('airlines', 'products.product_airline_id=airlines.airline_id', 'LEFT')
                ->where('product_id', $product_id);

		return $this->db->get()->row_array();
	}

	public function product_column($product_id=0,$column_name=''){
		$return_column = '';
		$this->db->select('p.'. $column_name)->from('products as p')->where('p.product_id',$product_id)->limit(1);
		$query = $this->db->get();
		$rowcount = $query->num_rows();
		if($rowcount>0)
		{
			$result = (array) $query->row();
			$return_column = $result[$column_name];
		}
		return $return_column;
	}

}
