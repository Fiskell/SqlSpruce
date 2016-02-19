<?php namespace tests\Laravel;

use App\Converter\PHP\Laravel\QueryBuilder\Converter;
use TestCase;

class WhereTest extends TestCase
{
    public function test_where_three_params() {
        $builder = "DB::table('users')->where('votes', '=', 100)->get();";
        $converted = Converter::convert($builder);

        $query = "SELECT * FROM `users` WHERE votes = 100;";
        $this->assertEquals($converted, $query);
    }

    public function test_where_two_params() {
        $builder = "DB::table('users')->where('votes', 100)->get();";
        $converted = Converter::convert($builder);

        $query = "SELECT * FROM `users` WHERE votes = 100;";
        $this->assertEquals($converted, $query);
    }

    public function test_where_greater_than_or_equal() {
        $builder = "DB::table('users')
                ->where('votes', '>=', 100)
                ->get();";
        $converted = Converter::convert($builder);

        $query = "SELECT * FROM `users` WHERE votes >= 100;";
        $this->assertEquals($converted, $query);
    }

    public function test_where_not_equal() {

        $builder = "DB::table('users')
                ->where('votes', '<>', 100)
                ->get();";
        $converted = Converter::convert($builder);

        $query = "SELECT * FROM `users` WHERE votes <> 100;";
        $this->assertEquals($converted, $query);
    }

    public function test_where_like() {
        $builder = "DB::table('users')
                ->where('name', 'like', 'T%')
                ->get();";
        $converted = Converter::convert($builder);

        $query = "SELECT * FROM `users` WHERE name like \"T%\";";
        $this->assertEquals($converted, $query);
    }

    public function test_two_wheres() {
        $builder = "DB::table('users')->where('votes', 100)->where('name', 'bernie')->get();";
        $converted = Converter::convert($builder);

        $query = "SELECT * FROM `users` WHERE votes = 100 AND name = \"bernie\";";
        $this->assertEquals($converted, $query);
    }

    public function test_or() {
        $builder = "DB::table('users')->where('votes', '>', 100)->orWhere('name', 'John')->get();";
        $converted = Converter::convert($builder);

        $query = "SELECT * FROM `users` WHERE votes > 100 OR name = \"John\"";
        $this->assertEquals($converted, $query);
    }
}