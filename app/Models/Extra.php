<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryItem;

class Extra extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'product_id',
        'price',
        'max_quantity',
        'is_approved',
        'edit_duplicate_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
