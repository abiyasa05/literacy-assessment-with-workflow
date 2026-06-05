<?php

namespace App\Models\Literacy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiteracyAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'option_id', 'assessment_id', 'answer_text', 'submitted_at', 'feedback', 'created_at', 'updated_at'];

    public function question()
    {
        return $this->belongsTo(LiteracyQuestion::class, 'question_id');
    }

    public function option()
    {
        return $this->belongsTo(LiteracyOption::class, 'option_id');
    }

    public function assessment()
    {
        return $this->belongsTo(LiteracyAssessment::class, 'assessment_id');
    }
}
