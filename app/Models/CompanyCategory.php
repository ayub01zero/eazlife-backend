<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'company_type_id',
        'icon_path',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'category_company', 'category_id', 'company_id');
    }

    public function type()
    {
        return $this->belongsTo(CompanyType::class, 'company_type_id', 'id', 'company_type');
    }
}
