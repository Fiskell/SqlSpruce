<?php namespace tests\Laravel;

use App\Converter\PHP\Laravel\QueryBuilder\Converter;
use TestCase;

class WhereTest extends TestCase
{
    public function test_where_three_params() {
        $builder = "DB::table('users')->where('votes', '=', 100)->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` where `votes` = 100;";
        $this->assertEquals($converted, $query);
    }

    public function test_where_two_params() {
        $builder = "DB::table('users')->where('votes', 100)->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` where `votes` = 100;";
        $this->assertEquals($converted, $query);
    }

    public function test_where_greater_than_or_equal() {
        $builder = "DB::table('users')
                ->where('votes', '>=', 100)
                ->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` where `votes` >= 100;";
        $this->assertEquals($converted, $query);
    }

    public function test_where_not_equal() {

        $builder = "DB::table('users')
                ->where('votes', '<>', 100)
                ->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` where `votes` <> 100;";
        $this->assertEquals($converted, $query);
    }

    public function test_where_like() {
        $builder = "DB::table('users')
                ->where('name', 'like', 'T%')
                ->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` where `name` like \"T%\";";
        $this->assertEquals($converted, $query);
    }

    public function test_two_wheres() {
        $builder = "DB::table('users')->where('votes', 100)->where('name', 'bernie')->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` where `votes` = 100 and `name` = \"bernie\";";
        $this->assertEquals($converted, $query);
    }

    public function test_or() {
        $builder = "DB::table('users')->where('votes', '>', 100)->orWhere('name', 'John')->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` where `votes` > 100 or `name` = \"John\";";
        $this->assertEquals($converted, $query);
    }

    public function test_where_between() {
        $builder = "DB::table('users')
                    ->whereBetween('votes', [1, 100])->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` where `votes` between 1 and 100;";
        $this->assertEquals($converted, $query);
    }

    public function test_where_in() {
        $builder = "DB::table('users')->whereIn('id', [1, 2, 3])->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` where `id` in (1, 2, 3);";
        $this->assertEquals($converted, $query);
    }

    public function test_where_not_in() {
        $builder = "DB::table('users')->whereNotIn('id', [1, 2, 3])->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` where `id` not in (1, 2, 3);";
        $this->assertEquals($converted, $query);
    }

    public function test_where_null() {
        $builder = "DB::table('users')->whereNull('updated_at')->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` where `updated_at` is null;";
        $this->assertEquals($converted, $query);
    }

    public function test_where_not_null() {
        $builder = "DB::table('users')->whereNotNull('updated_at')->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` where `updated_at` is not null;";
        $this->assertEquals($converted, $query);
    }
}