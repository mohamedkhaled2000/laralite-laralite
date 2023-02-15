<?php

namespace App\Controllers\Admin;

use App\Models\Color;
use Laralite\Database\Database;
use Laralite\Http\Response;
use Laralite\View\View;

class AdminController {

    public function index () {
         $data = Color::paginate(5);
        return view('users.index',compact('data'));
        // return $data;
    }
}