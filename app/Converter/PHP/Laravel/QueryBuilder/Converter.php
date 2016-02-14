<?php namespace App\Converter\PHP\Laravel\QueryBuilder;

use SelvinOrtiz\Utils\Flux\Flux;

class Converter
{
    public static function convert($builder) {
        return null;
        $static_parts = explode('::', $builder);
        $class        = $static_parts[0];
        $query        = $static_parts[1];

        $query_parts = explode('->', $query);
        foreach ($query_parts as $query_part) {
        }
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

        return [$matches[1], $matches[3]];
    }
}