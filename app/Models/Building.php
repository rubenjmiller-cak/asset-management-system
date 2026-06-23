<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $table = 'tblBuildings';
    protected $primaryKey = 'BuildingID';
    public $timestamps = false;

    protected $fillable = ['Name', 'BuildingNumber', 'Owner'];
}
