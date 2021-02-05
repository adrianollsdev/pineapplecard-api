<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public function establishment()
    {
        return $this->belongsTo(Establishment::class, "establishment_id");
    }

    /**
     * Get the EstablishmentCategory that owns the Payment.
     */
    public function user()
    {
        return $this->belongsTo(Profile::class, "user_id");
    }

}
