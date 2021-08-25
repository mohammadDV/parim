<?php

namespace App\Http\Requests\Api;

use System\Request\Request;

class SaveRequest extends Request
{
    public function rules(){
        return [
            'email'         => 'required|max:254',
            'username'      => 'max:254',
            'location'      => 'required|min:2|max:254',
            'type'          => 'required|min:2|max:254',
            'rate'          => 'max:11',
            'charge'        => 'max:11',
            'area'          => 'max:254',
            'start'         => 'date|required|min:10|max:254',
            'end'           => 'date|required|min:10|max:254',
            'event_name'    => 'min:2|max:254',
            'event_start'   => 'date|min:10|max:254',
            'event_end'     => 'date|min:10|max:254',
//            'event_id'      => 'exists:events,id',
//            'cat_id' => 'required|exist:categories,id',
//            'image' => 'required|file|mimes:jpeg,jpg,png,gif',
//            'published_at' => 'required|date',
        ];
    }
}