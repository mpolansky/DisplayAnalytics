<?php
/*
 * Basic model
 */
//NS61.DOMAINCONTROL.COM

class Base extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

     /**
     * Returns a resultset array with specified fields from database matching given conditions.
     * @access public
     */

    function find_all($table, $conditions = NULL, $fields = '*', $order = NULL, $start = 0, $limit = NULL, $join_table = NULL, $join = NULL)
    {
        if ($conditions != NULL)
        {
            $this->db->where($conditions);
        }

        if ($fields != NULL)
        {
            $this->db->select($fields, FALSE);
        }

        if ($order != NULL)
        {
            $this->db->order_by($order);
        }

        if ($limit != NULL)
        {
            $this->db->limit($limit, $start);
        }

        if ($join != NULL && $join_table != NULL)
        {
            $this->db->join($join_table, $join);
        }

        $query = $this->db->get($table);
        $this->__num_rows = $query->num_rows();

        return $query->result_array();
    }

    /**
     * Insert
     *
     * @return    the last inserted id
     * @param    array
     */
    function insert($table, $data = null)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * Get Where
     *
     * @return    result
     * @param    array, int, int
     */

    function get_where_order_desc($table, $where = null, $order = null, $limit = null, $offset = null)
    {
        $this->db->order_by($order,'desc');
        $q = $this->db->get_where($table, $where, $limit, $offset);
        return $q->result_array();
    }

    /**
     * Get Where ORDER BY
     *
     * @return    result
     * @param    array, int, int
     */

    function get_where($table, $where = null, $limit = null, $offset = null)
    {
        return $this->db->get_where($table, $where, $limit, $offset);
    }

    function get_where_array($table, $where, $limit = null)
    {
        $q = $this->db->get_where($table, $where, $limit);
        return $q->result_array();
    }

    /**
     * Update
     *
     * @return    void
     * @param    int, array
     */
    function update($table, $id = null, $data = null)
    {
        $this->db->where('id', $id);
        $this->db->update($table, $data);

        return $this->db->affected_rows();
    }

    /**
     * Update Where
     *
     * @return    void
     * @param    int, array
     */
    function update_where($table, $data = null, $where = '')
    {
        $this->db->where($where);
        $this->db->update($table, $data);

    }

    /**
     * Delete
     *
     * @return    void
     * @param    bool
     */
    function delete($table, $id = null)
    {
        $this->db->where('id', $id);
        return $this->db->delete($table);
    }

    /**
     * Delete Where
     *
     * @return    void
     * @param    bool
     */
    function delete_where($table, $where = null)
    {
        $this->db->where($where);
        return $this->db->delete($table);
    }

    /**
     * Get all the records
     *
     * @return    void
     */
    function get_all($table)
    {
        $q = $this->db->get($table);
        return $q->result();
    }

    function get_all_array($table)
    {
        $q = $this->db->get($table);
        return $q->result_array();
    }
    /**
     * Get all the records
     *
     * @return    void
     */
    function get_all_order($table, $order)
    {
        $this->db->order_by($order);
        $q = $this->db->get($table);
        return $q->result_array();
    }

    /**
     * Get one row
     *
     * @return    array
     * @param    int
     */
    function get_row($table, $id = null)
    {
        $this->db->where('id', $id);
        $q = $this->db->get($table);
        return $q->row();
    }

    function get_row_array($table, $id = null)
    {
        $this->db->where('id', $id);
        $q = $this->db->get($table);
        return $q->row_array();
    }

    /**
     * Get one row Where
     *
     * @return    array
     * @param    int
     */
    function get_row_where($table, $where)
    {
        $this->db->where($where);
        $q = $this->db->get($table);
        return $q->row_array();
    }

    /**
     * Count All
     *
     * @return    int
     */
    function count_all($table)
    {
        $table = '';
        return (int) $this->db->count_all_results($table);
    }

    /**
     * Count All Where
     *
     * @return    int
     */
    function count_all_where($table, $where)
    {
        $this->db->where($where);
        return (int) $this->db->count_all_results($table);
    }

    function empty_table($table)
    {
        $table = '';
        $this->db->empty_table($table);
    }

    function max_field($table, $field, $resulting_field)
    {
        $this->db->select_max($field, $resulting_field);
        $q = $this->db->get($table);
        return $q->row_array();
     }
}