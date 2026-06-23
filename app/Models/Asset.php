<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    protected $table = 'tblAssets';
    protected $primaryKey = 'AssetID';

    // Soft deletes use SoftDelete datetime column
    public const CREATED_AT = 'DateAdded';
    public const UPDATED_AT = null;

    protected $fillable = [
        'Description',
        'CAKID',
        'SerialNumber',
        'AssetName',
        'AssetShortName',
        'AssetStatus',
        'AssetArchive',
        'Notes',
        'DateOfPurchase',
        'DateOfInstall',
        'PurchasePrice',
        'AssetBuilding',
        'ItemID',
        'AssetType',
        'AssetManufactureID',
        'AssetModelID',
        'AccountID',
    ];

    protected function casts(): array
    {
        return [
            'DateAdded'        => 'datetime',
            'DateOfPurchase'   => 'date',
            'DateOfInstall'    => 'date',
            'DateOfManufacture'=> 'date',
            'AssetArchive'     => 'boolean',
            'PurchasePrice'    => 'decimal:2',
            'TotalAcquisitionCost' => 'decimal:2',
        ];
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->AssetName ?: $this->CAKID ?: "Asset #{$this->AssetID}";
    }
}
