<?php

namespace Laralite\Validation;

use Laralite\Http\Request;
use Laralite\Session\Session;
use Laralite\Url\Url;
use Rakit\Validation\Validator;
class Validate  {

    private function __construct()
    {
        
    }

    /**
     * 
     * Validate inputs
     */

    public static function validate ($rules,$json) {
        $validator = new Validator;

        // make it
        $validation = $validator->make($_POST + $_FILES, $rules);

        // then validate
        $errors = $validation->errors();

        if ($validation->fails()) {
            if ($json) {
                return ['errors' => $errors->firstOfAll()];
            } else {
                Session::set('errors',$errors);
                Session::set('old',Request::all());
                return Url::redirect(Url::previous());
            }
        }
    }
}