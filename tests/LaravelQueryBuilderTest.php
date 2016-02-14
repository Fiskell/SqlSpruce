<?php namespace tests;

use App\Converter\PHP\Laravel\QueryBuilder\Converter;
use TestCase;

class LaravelQueryBuilderTest extends TestCase
{
    public function test_call_parts() {
        $query_part = "table('user')";
        $parts = Converter::deconstructCall($query_part);
        $this->assertEquals('table', $parts[0]);
        $this->assertEquals("'user'", $parts[1]);
    }

    public function test_get_all() {
        $builder = "DB::table('user')->get();";
        $converted = Converter::convert($builder);

        $query = "SELECT * FROM `user`;";
        $this->assertEquals($converted, $query);
    }
}
