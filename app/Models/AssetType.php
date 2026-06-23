<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    protected $table = 'tblAssets_LU_Type';
    protected $primaryKey = 'AssetTypeID';
    public $timestamps = false;

    public function subtypes()
    {
        return $this->hasMany(AssetSubType::class, 'AssetTypeID', 'AssetTypeID');
    }
}
