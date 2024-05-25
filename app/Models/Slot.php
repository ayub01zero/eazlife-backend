<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    protected $appends = ['isOnLocation'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'date_time',
        'duration',
        'capacity',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function isReserved()
    {
        return $this->reservation()->exists();
    }

    public function reservation()
    {
        return $this->hasOne(Reservation::class);
    }

    public function getIsOnLocationAttribute()
    {
        $onLocation = $this->company->fulfillmentTypes->contains(function ($type) {
            return $type->name === 'onlocation';
        });

        return $onLocation;
    }
}
