<?php
/* 
 * Generated by CRUDigniter v3.0 Beta 
 * www.crudigniter.com
 */
 
class Ticket_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get ticket by id_ticket
     */
    function get_ticket($id_ticket)
    {
        $ticket = $this->db->query("
            SELECT
                *

            FROM
                `ticket`

            WHERE
                `id_ticket` = ?
        ",array($id_ticket))->row_array();

        return $ticket;
    }

    function get_categories($categories_id)
    {
        $Query = $this->db->query("
            SELECT
                *

            FROM
                `ml_products`

            WHERE
                `categories_id` = ?
        ",array($categories_id))->row_array();

        return $Query;
    }
    
    /*
     * Get all ticket
     */
    function get_all_ticket()
    {
        $ticket = $this->db->query("
            SELECT
                *

            FROM
                `ticket`

            WHERE
                1 = 1
        ")->result_array();

        return $ticket;
    }

    function get_categories_all()
    {
        $cate = $this->db->query("
            SELECT
                *

            FROM
                `ml_categories`

            WHERE
                1 = 1
        ")->result_array();

        return $cate;
    }
    
    /*
     * function to add new ticket
     */
    function add_ticket($params)
    {
        $this->db->insert('ticket',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update ticket
     */
    function update_ticket($id_ticket,$params)
    {
        $this->db->where('id_ticket',$id_ticket);
        $response = $this->db->update('ticket',$params);
        if($response)
        {
            return "ticket updated successfully";
        }
        else
        {
            return "Error occuring while updating ticket";
        }
    }
    
    /*
     * function to delete ticket
     */
    function delete_ticket($id_ticket)
    {
        $response = $this->db->delete('ticket',array('id_ticket'=>$id_ticket));
        if($response)
        {
            return "ticket deleted successfully";
        }
        else
        {
            return "Error occuring while deleting ticket";
        }
    }
}