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
        'category_id',
        'file_path',  // Added image file path
    ];

    /**
     * Define the relationship with the Category model.
     * A Brand belongs to one Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Accessor to return full image URL (optional helper)
     */
    public function getImageUrlAttribute()
    {
        return $this->file_path
            ? asset('storage/' . $this->file_path)
            : asset('images/default.png');  // fallback image if not uploaded
    }
}
