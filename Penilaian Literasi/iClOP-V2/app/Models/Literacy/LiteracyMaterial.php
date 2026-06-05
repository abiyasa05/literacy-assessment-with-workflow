<?php

namespace App\Models\Literacy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiteracyMaterial extends Model
{
    use HasFactory;

    protected $table = 'literacy_materials';
    protected $fillable = ['user_id', 'title', 'description', 'file_path'];

    public function teacher()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function questions()
    {
        return $this->hasMany(LiteracyQuestion::class, 'material_id');
    }

    public function storyTexts()
    {
        return $this->hasMany(LiteracyStoryText::class, 'material_id');
    }
}
