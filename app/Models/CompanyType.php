<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'marker_image_path'];

    public function categories()
    {
        return $this->hasMany(CompanyCategory::class);
    }

    public function fulfillment_types()
    {
        return $this->belongsToMany(FulfillmentType::class);
    }
}
