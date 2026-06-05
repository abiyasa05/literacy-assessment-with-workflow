<?php

namespace App\Models\Literacy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiteracyStoryText extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'story_text',
    ];

    // Relasi ke LiteracyMaterial
    public function material()
    {
        return $this->belongsTo(LiteracyMaterial::class, 'material_id');
    }
}