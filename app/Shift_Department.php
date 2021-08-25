<?php


namespace App;

use System\Database\ORM\Model;

class Shift_Department extends Model {

    protected $table            = "shift_department";
    protected $fillable         = ["shift_id","department_id"];
    protected $hidden           = [];
    protected $casts            = [];
    protected $primaryKey       = false;
    protected $createdAt        = null;
    protected $updatesAt        = null;

}