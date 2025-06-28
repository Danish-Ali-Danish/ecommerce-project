<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    // These columns are fillable (can be mass assigned)
    protected $fillable = [
        'name',
        'category_id',  // Ensure 'category_id' is here since you're adding it
    ];

    /**
     * Define the relationship with the Category model.
     * A Brand belongs to one Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
