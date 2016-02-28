<?php namespace App\Converter\PHP\Laravel\QueryBuilder;

use App\Converter\QueryBuilder;
use SelvinOrtiz\Utils\Flux\Flux;

class Converter
{
    /**
     * Convert string to SQL query if the
     * string is a Laravel query builder
     *
     * @param $builder
     * @return string
     * @throws \Exception
     */
    public static function convert($builder) {
        $static_parts = explode('::', $builder);
        $class        = $static_parts[0];
        $query        = $static_parts[1];

        $query_parts = explode('->', $query);
        $query_parts = str_replace("\n", "", $query_parts);

        $query = new QueryBuilder();
        foreach ($query_parts as $query_part) {
            $call_parts = self::deconstructCall($query_part);
            switch($call_parts[0]) {
                case 'table':
                    $query->setTable($call_parts[1]);
                    break;
                case 'select':
                    $query->setSelect(explode(',', $call_parts[1]));
                    break;
                case 'distinct':
                    $query->setDistinct(true);
                    break;
                case 'where':
                    $where_parts = explode(',', $call_parts[1]);
                    $where_count = count($where_parts);

                    if($where_count == 2) {
                        $query->addAndCondition($where_parts[0], '=', $where_parts[1]);
                    } else if($where_count == 3) {
                        $query->addAndCondition($where_parts[0], $where_parts[1], $where_parts[2]);
                    } else {
                        throw new \Exception('Invalid where clause');
                    }

                    break;
                case 'orWhere':
                    $where_parts = explode(',', $call_parts[1]);
                    $where_count = count($where_parts);

                    if($where_count == 2) {
                        $query->addOrCondition($where_parts[0], '=', $where_parts[1]);
                    } else if($where_count == 3) {
                        $query->addOrCondition($where_parts[0], $where_parts[1], $where_parts[2]);
                    } else {
                        throw new \Exception('Invalid where clause');
                    }

                    break;
                case 'whereBetween':
                    $where_parts = explode(',', $call_parts[1]);
                    $where_count = count($where_parts);

                    if($where_count == 2) {
                        $query->addWhereBetweenCondition($where_parts[0], $where_parts[1]);
                    }

                    break;
                case 'value':
                    $query->setSelect([$call_parts[1]]);
                    $query->setIsSelect(true);
                    break;
                case 'groupBy':
                    $query->setGroupBy($call_parts[1]);
                    break;
                case 'get':
                    $query->setIsSelect(true);
            }
        }

        if($query->getIsSelect()) {
            return $query->getSelectQuery();
        }
        return "no query";
    }

    /**
     * Return the function call and it's parameters
     *
     * @param $call
     * @return array
     */
    public static function deconstructCall($call) {
        $call = trim($call);
        $flux = Flux::getInstance()
                    ->startOfLine()
                    ->word()
                    ->find('(')
                    ->anything()
                    ->then(')')
                    ->maybe(';')
                    ->endOfLine();

        $pattern = $flux->getPattern();
        preg_match($pattern, $call, $matches);

        return [array_get($matches, 1), array_get($matches, 3)];
    }
}