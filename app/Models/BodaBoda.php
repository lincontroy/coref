<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodaBoda extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel convention)
    protected $table = 'boda_bodas';

    // Mass assignable fields
    protected $guarded = [];

    // Casts (optional)
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Optional: Get a truncated description for display
     */
    public function getShortDescriptionAttribute(): string
    {
        return $this->description ? \Illuminate\Support\Str::limit($this->description, 100, '...') : '';
    }
}
