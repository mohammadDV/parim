<?php

namespace App\Http\Requests\Api;

use System\Request\Request;

class SearchRequest extends Request
{
    public function rules(){
        return [
            'location'  => 'min:2|max:254',
            'start'     => 'date|min:10|max:254',
            'end'       => 'date|min:10|max:254',
        ];
    }
}