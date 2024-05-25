<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'slider_id',
        'title',
        'description',
        'link',
        'company_id',
        'product_id',
        'image_path',
    ];

    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
