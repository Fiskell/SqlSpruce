<?php namespace tests\Laravel;

use App\Converter\PHP\Laravel\QueryBuilder\Converter;
use TestCase;

class SelectTest extends TestCase
{
    public function test_get_all() {
        $builder = "DB::table('user')->get();";
        $converted = Converter::convert($builder);

        $query = "SELECT * FROM `user`;";
        $this->assertEquals($converted, $query);
    }
}