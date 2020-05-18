<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Testcontroller extends Controller
{
    public function getTest()
    {
        $db_ext = \DB::connection('mysql');
        $class = $db_ext->table('class_section_subjects')->get();
        print_r($class);
    }
}
