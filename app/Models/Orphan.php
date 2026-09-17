<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orphan extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }
}
