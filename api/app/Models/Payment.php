<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * Get the EstablishmentCategory that owns the Payment.
     */
    public function establishmentCategory()
    {
        return $this->belongsTo(EstablishmentCategory::class, "establishment_category_id");
    }

    /**
     * Get the EstablishmentCategory that owns the Payment.
     */
    public function user()
    {
        return $this->belongsTo(Profile::class, "user_id");
    }

}
