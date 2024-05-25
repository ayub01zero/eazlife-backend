<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'price',
        'name',
        'address',
        'email',
        'phone',
        'business',
        'delivery_at',
        'pickup_at',
        'paid'
    ];

    protected $casts = [
        'delivery_at' => 'datetime:Y-m-d H:i:s',
        'pickup_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->using(OrderProduct::class)
            ->withPivot('quantity', 'price');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function productsWithExtras()
    {
        return $this->belongsToMany(Product::class)
            ->using(OrderProduct::class)
            ->withPivot('quantity', 'price')
            ->with('orderedExtras');
    }

    public function notification()
    {
        return $this->morphOne(UserNotification::class, 'notifiable');
    }
}
