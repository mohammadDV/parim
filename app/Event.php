<?php


namespace App;

use System\Database\ORM\Model;

class Event extends Model {

    protected $table            = "events";
    protected $fillable         = ["name","start","start_time","end","end_time"];
    protected $hidden           = [];
    protected $casts            = [];

    public function shifts()
    {
        return $this->hasMany("\App\shift","event_id","id");
    }

}