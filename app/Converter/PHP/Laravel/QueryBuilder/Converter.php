<?php namespace App\Converter\PHP\Laravel\QueryBuilder;

use App\Converter\QueryBuilder;
use SelvinOrtiz\Utils\Flux\Flux;

class Converter
{
    public static function convert($builder) {
        $static_parts = explode('::', $builder);
        $class        = $static_parts[0];
        $query        = $static_parts[1];

        $query_parts = explode('->', $query);

        $query = new QueryBuilder();
        foreach ($query_parts as $query_part) {
            $call_parts = self::deconstructCall($query_part);
            print_r($call_parts);
            switch($call_parts[0]) {
                case 'table':
                    $query->table = self::unquote($call_parts[1]);
            }
        }
        return $query->getSelectQuery();
    }

    public static function unquote($string) {
        $string = trim($string);
        $string = trim($string, '\'');
        return trim($string, '"');
    }

    public static function deconstructCall($call) {
        $flux = Flux::getInstance()
                    ->startOfLine()
                    ->word()
                    ->find('(')
                    ->anything()
                    ->then(')')
                    ->endOfLine();

        $pattern = $flux->getPattern();
        preg_match($pattern, $call, $matches);
        print_r($matches);

        return [array_get($matches, 1), array_get($matches, 3)];
    }
}