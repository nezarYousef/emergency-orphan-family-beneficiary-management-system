<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AidDistribution extends Model
{
    protected $guarded = [];

    protected $casts = ['distribution_date' => 'date'];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }
}
