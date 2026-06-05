<?php

namespace App\Models\Literacy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiteracyGeneratedText extends Model
{
    use HasFactory;

    protected $table = 'literacy_generated_texts';

    protected $fillable = [
        'user_id',
        'material_id',
        'generate_text',
        'question_type',
        'question_count',
        'created_at',
        'updated_at'
    ];

    public function teacher()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function material()
    {
        return $this->belongsTo(\App\Models\Literacy\LiteracyMaterial::class, 'material_id');
    }
}
