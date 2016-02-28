<?php namespace tests\Laravel;

use App\Converter\PHP\Laravel\QueryBuilder\Converter;
use TestCase;

class SelectTest extends TestCase
{
    public function test_get_all() {
        $builder = "DB::table('users')->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users`;";
        $this->assertEquals($converted, $query);
    }

    public function test_multi_line() {
        $builder = "DB::table('users')
            ->select('name')
            ->get();";
        $converted = Converter::convert($builder);

        $query = "select `name` from `users`;";
        $this->assertEquals($converted, $query);
    }

    public function test_simple_select_with_multi_column_and_alias() {
        $builder = "DB::table('users')->select('name', 'email as user_email')->get();";
        $converted = Converter::convert($builder);

        $query = "select `name`, `email` as `user_email` from `users`;";
        $this->assertEquals($converted, $query);
    }

    public function test_distinct() {
        $builder = "DB::table('users')->distinct()->get();";
        $converted = Converter::convert($builder);

        $query = "select distinct * from `users`;";
        $this->assertEquals($converted, $query);
    }

    public function test_group_by() {
        $builder = "DB::table('users')
                     ->where('status', '<>', 1)
                     ->groupBy('status')
                     ->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` where `status` <> 1 group by `status`;";
        $this->assertEquals($converted, $query);
    }

    public function test_get_value() {
        $builder = "DB::table('users')->where('name', 'John')->value('email');";
        $converted = Converter::convert($builder);

        $query = "select `email` from `users` where `name` = \"John\";";
        $this->assertEquals($converted, $query);
    }

//    public function test_where_between() {
//        $builder = "DB::table('users')
//                    ->whereBetween('votes', [1, 100])->get();";
//        $converted = Converter::convert($builder);
//
//        $query = "select * from `users` where `votes` between 1 and 100;";
//        $this->assertEquals($converted, $query);
//    }
}