<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstablishmentCategory extends Model
{
    use HasFactory;

    /**
     * Get the payments for the EstablishmentCategory.
     */
    public function establishment()
    {
        return $this->hasMany(Establishment::class, "establishment_category_id");
    }
}
