<?php namespace App\Converter;

class QueryBuilder
{
    public $table;
    public $select;
    public $and_array;
    public $order_by;
    public $limit;
    public $offset;

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
}