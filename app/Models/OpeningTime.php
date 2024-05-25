<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'day',
        'opening_time',
        'closing_time',
    ];

    public function getOpeningTimeAttribute($value)
    {
        if ($value !== null) {
            return date('H:i', strtotime($value));
        }
    }

    public function getClosingTimeAttribute($value)
    {
        if ($value !== null) {
            return date('H:i', strtotime($value));
        }
    }

    public function setOpeningTimeAttribute($value)
    {
        if ($value === null && $this->closing_time !== null) {
            $this->attributes['opening_time'] = '00:00';
        } else {
            if (is_numeric($value)) {
                $value .= ':00';
            }
            if ($value !== null) {
                $this->attributes['opening_time'] = date('H:i', strtotime($value));
            }
        }
    }

    public function setClosingTimeAttribute($value)
    {
        if ($value === null && $this->opening_time !== null) {
            $this->attributes['closing_time'] = '00:00';
        } else {
            if (is_numeric($value)) {
                $value .= ':00';
            }
            if ($value !== null) {
                $this->attributes['closing_time'] = date('H:i', strtotime($value));
            }
        }
    }
}
