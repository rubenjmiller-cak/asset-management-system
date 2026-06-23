<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetTertiaryType extends Model
{
    protected $table = 'tblAssets_LU_TertiaryType';
    protected $primaryKey = 'TertiaryTypeID';
    public $timestamps = false;

    public function subtype()
    {
        return $this->belongsTo(AssetSubType::class, 'AssetSubTypeID', 'AssetSubTypeID');
    }
}
