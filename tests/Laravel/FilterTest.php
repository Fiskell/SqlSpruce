<?php namespace tests\Laravel;

use App\Converter\PHP\Laravel\QueryBuilder\Converter;
use TestCase;

class FilterTest extends TestCase
{
    public function test_order_by()
    {
        $builder   = "DB::table('users')->orderBy('name', 'desc')->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` order by `name` desc;";
        $this->assertEquals($converted, $query);
    }

    public function test_group_by()
    {
        $builder   = "DB::table('users')->groupBy('account_id')->get();";
        $converted = Converter::convert($builder);

        $query = "select * from `users` group by `account_id`;";
        $this->assertEquals($converted, $query);
    }

}