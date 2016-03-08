<?php namespace tests\Laravel;

use App\Converter\PHP\Laravel\QueryBuilder\Converter;
use TestCase;

class WriteTest extends TestCase
{
    public function test_delete()
    {
        $builder   = "DB::table('users')->delete();";
        $converted = Converter::convert($builder);

        $query = "delete from `users`;";
        $this->assertEquals($converted, $query);
    }

    public function test_delete_where()
    {
        $builder   = "DB::table('users')->where('votes', '<', 100)->delete();";
        $converted = Converter::convert($builder);

        $query = "delete from `users` where `votes` < 100;";
        $this->assertEquals($converted, $query);
    }

}