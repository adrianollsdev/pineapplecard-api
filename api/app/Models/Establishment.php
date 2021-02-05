<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    use HasFactory;

    /**
     * Get the EstablishmentCategory that owns the Payment.
     */
    public function establishmentCategory()
    {
        return $this->belongsTo(EstablishmentCategory::class, "category_id");
    }
}
