<?php
/* 
 * Generated by CRUDigniter v3.0 Beta 
 * www.crudigniter.com
 */
 
class Date_no_sale_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get date_no_sale by id_date
     */
    function get_date_no_sale($id_date)
    {
        $date_no_sale = $this->db->query("
            SELECT
                *

            FROM
                `date_no_sale`

            WHERE
                `id_date` = ?
        ",array($id_date))->row_array();

        return $date_no_sale;
    }
    
    /*
     * Get all date_no_sale
     */
    function get_all_date_no_sale()
    {
        $date_no_sale = $this->db->query("
            SELECT
                *

            FROM
                `date_no_sale`

            WHERE
                1 = 1
        ")->result_array();

        return $date_no_sale;
    }
    
    /*
     * function to add new date_no_sale
     */
    function add_date_no_sale($params)
    {
        $this->db->insert('date_no_sale',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update date_no_sale
     */
    function update_date_no_sale($id_date,$params)
    {
        $this->db->where('id_date',$id_date);
        $response = $this->db->update('date_no_sale',$params);
        if($response)
        {
            return "date_no_sale updated successfully";
        }
        else
        {
            return "Error occuring while updating date_no_sale";
        }
    }
    
    /*
     * function to delete date_no_sale
     */
    function delete_date_no_sale($id_date)
    {
        $response = $this->db->delete('date_no_sale',array('id_date'=>$id_date));
        if($response)
        {
            return "date_no_sale deleted successfully";
        }
        else
        {
            return "Error occuring while deleting date_no_sale";
        }
    }
}
