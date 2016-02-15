<?php namespace App\Converter;

class QueryBuilder
{
    private $is_select;
    private $is_update;
    private $is_insert;
    private $is_delete;
    private $select;
    private $table;
    private $and_array;
    private $order_by;
    private $limit;
    private $offset;

    public function getSelectQuery() {
        $query = "SELECT ";

        if(is_null($this->select)) {
            $this->select = ["*"];
        }

        $query .= implode(', ', $this->select) . " ";

        $query .= "FROM `{$this->table}`";

//        if(count($this->and_array) > 0) {
//            $where = array_shift($this->and_array);
//            $query .= 'WHERE ';
//        }

        return $query . ';';
    }

    /**
     * @return mixed
     */
    public function getIsSelect() {
        return $this->is_select;
    }

    /**
     * @param mixed $is_select
     */
    public function setIsSelect($is_select) {
        $this->is_select = $is_select;
    }

    /**
     * @return mixed
     */
    public function getIsUpdate() {
        return $this->is_update;
    }

    /**
     * @param mixed $is_update
     */
    public function setIsUpdate($is_update) {
        $this->is_update = $is_update;
    }

    /**
     * @return mixed
     */
    public function getIsInsert() {
        return $this->is_insert;
    }

    /**
     * @param mixed $is_insert
     */
    public function setIsInsert($is_insert) {
        $this->is_insert = $is_insert;
    }

    /**
     * @return mixed
     */
    public function getIsDelete() {
        return $this->is_delete;
    }

    /**
     * @param mixed $is_delete
     */
    public function setIsDelete($is_delete) {
        $this->is_delete = $is_delete;
    }

    /**
     * @return mixed
     */
    public function getSelect() {
        return $this->select;
    }

    /**
     * @param mixed $select
     */
    public function setSelect(array $select) {
        $this->select = $select;
    }

    /**
     * @return mixed
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * @param mixed $table
     */
    public function setTable($table) {
        $this->table = $table;
    }

    /**
     * @return mixed
     */
    public function getAndArray() {
        return $this->and_array;
    }

    /**
     * @param mixed $and_array
     */
    public function setAndArray($and_array) {
        $this->and_array = $and_array;
    }

    /**
     * @return mixed
     */
    public function getOrderBy() {
        return $this->order_by;
    }

    /**
     * @param mixed $order_by
     */
    public function setOrderBy($order_by) {
        $this->order_by = $order_by;
    }

    /**
     * @return mixed
     */
    public function getLimit() {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit) {
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getOffset() {
        return $this->offset;
    }

    /**
     * @param mixed $offset
     */
    public function setOffset($offset) {
        $this->offset = $offset;
    }
}