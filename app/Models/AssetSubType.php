<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetSubType extends Model
{
    protected $table = 'tblAssets_LU_SubType';
    protected $primaryKey = 'AssetSubTypeID';
    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo(AssetType::class, 'AssetTypeID', 'AssetTypeID');
    }

    public function tertiaryTypes()
    {
        return $this->hasMany(AssetTertiaryType::class, 'AssetSubTypeID', 'AssetSubTypeID');
    }
}
