<?php

namespace App\Models\Literacy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiteracyUserLogin extends Model
{
    protected $table    = 'users'; // Sesuaikan dengan nama tabel di database
    protected $fillable = ['name','email'];
}
