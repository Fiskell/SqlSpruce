<?php namespace App\Converter\PHP\Laravel\QueryBuilder;

use DB;
use Illuminate\Database\Query\Builder;
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

        $query = null;
        foreach ($query_parts as $query_part) {
            $call_parts = self::deconstructCall($query_part);
            $params_raw = explode(',', array_get($call_parts, 1));
            $params     = [];
            foreach ($params_raw as $param) {
                $params[] = self::sanitizeParameter($param);
            }
            switch ($call_parts[0]) {
                case 'table':
                    $query = DB::table($params[0]);
                    break;
                case 'select':
                    $query->select($params);
                    break;
                case 'distinct':
                    $query->distinct();
                    break;
                case 'where':
                case 'orWhere':
                    $query = self::addWhere($query, $call_parts[0], $params);
                    break;
                case 'groupBy':
                    $query->groupBy($params[0]);
                    break;
                case 'value':
                    $query->select($params[0]);
                    break;
                case 'get':
                    // DO NOTHING
                    break;
            }
        }

        return self::getRawQuery($query);
    }

    public static function addWhere(Builder $query, $function, $params) {
        $param_count = count($params);
        if ($param_count == 2) {
            $query->$function($params[0], self::sanitizeValue($params[1]));
        } else if ($param_count == 3) {
            $query->$function($params[0], $params[1], self::sanitizeValue($params[2]));
        } else {
            throw new \Exception('Invalid where clause');
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @return string
     */
    public static function getRawQuery(Builder $query) {
        $raw_query   = $query->toSql();
        $bindings    = $query->getBindings();
        $bound_query = vsprintf(str_replace("?", "%s", $raw_query), $bindings);

        return $bound_query . ";";
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

    /**
     * Remove starting and ending quotes (single and double)
     *
     * @param $string
     * @return string
     */
    public static function unquote($string) {
        $string = trim($string);
        $string = trim($string, '\'');

        return trim($string, '"');
    }

    /**
     * @param $param
     * @return string
     */
    public static function sanitizeParameter($param) {
        return self::unquote($param);
    }

    /**
     * @param $param
     * @return int|string
     */
    public static function sanitizeValue($param) {
        $param = self::unquote($param);
        $param = is_numeric($param) ? $param : "\"$param\"";

        return $param;
    }
}