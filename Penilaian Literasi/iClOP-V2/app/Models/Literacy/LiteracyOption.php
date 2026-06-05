<?php

namespace App\Models\Literacy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiteracyOption extends Model
{
    use HasFactory;

    protected $table = 'literacy_options';
    protected $fillable = ['question_id', 'option_text', 'is_correct', 'score'];

    public function question()
    {
        return $this->belongsTo(LiteracyQuestion::class, 'question_id');
    }
}