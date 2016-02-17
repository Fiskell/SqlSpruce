<?php namespace tests\Laravel;

use App\Converter\PHP\Laravel\QueryBuilder\Converter;
use TestCase;

class WhereTest extends TestCase
{
    public function test_get_all() {
        $builder = "DB::table('users')->where('votes', '=', 100)->get();";
        $converted = Converter::convert($builder);

        $query = "SELECT * FROM `users` WHERE votes = 100;";
        $this->assertEquals($converted, $query);
    }
}