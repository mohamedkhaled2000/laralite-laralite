<?php

namespace App\Middleware;

class Admin {

    public function handle() {
        
        if(1 != 1) {
            die ('not allowed for you to access this page');
        }
        
    }
}