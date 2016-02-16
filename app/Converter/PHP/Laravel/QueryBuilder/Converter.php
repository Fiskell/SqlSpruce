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
     */
    public static function convert($builder) {
        $static_parts = explode('::', $builder);
//        $class        = $static_parts[0];
        $query        = $static_parts[1];

        $query_parts = explode('->', $query);
        $query_parts = str_replace("\n", "", $query_parts);
        print_r($query_parts);

        $query = new QueryBuilder();
        foreach ($query_parts as $query_part) {
            echo 'part' . "\n";
            print_r($query_part);
            $call_parts = self::deconstructCall($query_part);
            print_r($call_parts);
            switch($call_parts[0]) {
                case 'table':
                    $query->setTable(self::unquote($call_parts[1]));
                    break;
                case 'select':
                    $select_parts = [];
                    foreach(explode(',', $call_parts[1]) as $select_part){
                        $select_parts[] = self::unquote($select_part);
                    }
                    $query->setSelect($select_parts);
                    break;
                case 'distinct':
                    $query->setDistinct(true);
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
     * Return the function call and it's parameters
     *
     * @param $call
     * @return array
     */
    public static function deconstructCall($call) {
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
        echo "\n" . $call . ' ' . json_encode($matches);

        return [array_get($matches, 1), array_get($matches, 3)];
    }
}