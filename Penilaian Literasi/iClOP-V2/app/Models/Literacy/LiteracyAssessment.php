<?php

namespace App\Models\Literacy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiteracyAssessment extends Model
{
    use HasFactory;

    protected $table = 'literacy_assessments';

    // Tentukan kolom yang bisa diisi secara massal
    protected $fillable = [
        'user_id',
        'status',
        'score',
        'answer_text',
        'assessed_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Relasi ke User (siswa yang mengerjakan asesmen)
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}