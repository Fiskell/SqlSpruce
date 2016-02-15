<?php namespace tests\Laravel;

use App\Converter\PHP\Laravel\QueryBuilder\Converter;
use TestCase;

class RegexTest extends TestCase
{
    public function test_simple_call_parts() {
        $query_part = "table('user')";
        $parts = Converter::deconstructCall($query_part);
        $this->assertEquals('table', $parts[0]);
        $this->assertEquals("'user'", $parts[1]);
    }

    public function test_multi_param_call_parts() {
        $query_part = "table('user', 'thing')";
        $parts = Converter::deconstructCall($query_part);
        $this->assertEquals('table', $parts[0]);
        $this->assertEquals("'user', 'thing'", $parts[1]);
    }

    // TODO test param as closure

}