<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryItem;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image_id',
        'product_category_id',
        'company_id',
        'price',
        'preparation_time',
        'is_approved',
        'edit_duplicate_id',
        'description',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'image'
    ];

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($product) {
            $product->append('image_url');
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function image()
    {
        return $this->belongsTo(MediaLibraryItem::class, 'image_id');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return $this->image->getItem()->getUrl('400');
        }

        return null;
    }

    public function extras()
    {
        return $this->hasMany(Extra::class);
    }

    public function orderedExtras()
    {
        return $this->belongsToMany(Extra::class, 'extra_order_product')
            ->withPivot('quantity', 'price');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price');
    }

    public function duplicates()
    {
        return $this->hasMany(Product::class, 'edit_duplicate_id', 'id');
    }

    public function editDuplicate()
    {
        return Product::find($this->edit_duplicate_id);
    }
}
