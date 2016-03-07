<?php namespace tests\Laravel;

use App\Converter\PHP\Laravel\QueryBuilder\Converter;
use TestCase;

class FilterTest extends TestCase
{
    public function test_where_three_params() {
        $builder = "DB::table('users')->orderBy('name', 'desc')->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` order by `name` desc;";
        $this->assertEquals($converted, $query);
    }
}