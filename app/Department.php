<?php


namespace App;

use System\Database\ORM\Model;

class Department extends Model {

    protected $table            = "departments";
    protected $fillable         = ["title"];
    protected $hidden           = [];
    protected $casts            = [];

    public function shifts()
    {
        return $this->belongsToMany("\App\Shift", "shift_department", "id", "department_id", "shift_id", "id");
    }

}