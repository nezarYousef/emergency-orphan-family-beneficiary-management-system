<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function orphans()
    {
        return $this->hasMany(Orphan::class);
    }

    public function aidDistributions()
    {
        return $this->hasMany(AidDistribution::class);
    }
}
