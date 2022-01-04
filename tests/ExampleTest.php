<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;

include_once 'app/BusinessLogicLayer/managers/functions.php';

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Laravel');
    }

    public function testSlug() {
        $original = 'file-name-ελληνικά-1.mp3';
        $str_arr = explode('.', $original);
        $only_name = $str_arr[0];
        $extension = $str_arr[1];
        $converted = Str::slug($only_name);
        $final = $converted . '.' . $extension;
        dd($final);
    }
}
