<?php


namespace App;

use System\Database\ORM\Model;

class Shift extends Model {

    protected $table            = "shifts";
    protected $fillable         = ["type","start","start_time","end","end_time","user_name","user_email","location","rate","charge","area","event_id"];
    protected $hidden           = [];
    protected $casts            = [];

    public function departments()
    {
        return $this->belongsToMany("\App\Department", "shift_department", "id", "shift_id", "department_id", "id");
    }

    public function event()
    {
        return $this->blongsTo("\App\Event","event_id","id");
    }

}