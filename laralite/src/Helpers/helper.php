<?php

use Laralite\Cookie\Cookie;
use Laralite\Database\Database;
use Laralite\Http\Request;
use Laralite\Session\Session;
use Laralite\Url\Url;
use Laralite\View\View;

function view ($path,$data) {
    return View::render($path,$data);
}


function request ($key) {
    return Request::value($key);
}

function redirect ($path) {
    return Url::redirect($path);
}

function back () {
    return Url::previous();
}


function url ($path) {
    return Url::path($path);
}

function asset ($path) {
    return Url::path($path);
}


function dd ($data) {
    echo '<pre>';
    if(is_string($data)) {
        echo $data;
    } else {
        print_r($data);
    }
    echo '<pre>';
    die();
}


function session ($key) {
    return Session::get($key);
}

function flash ($data) {
    return Session::flash($data);
}

function links ($current_page,$pages) {
    return Database::links($current_page,$pages);
}


function auth ($table) {
    $auth = Session::get($table) ?: Cookie::get($table);
    return Database::table($table)->where('id','=',$auth)->first();
}





