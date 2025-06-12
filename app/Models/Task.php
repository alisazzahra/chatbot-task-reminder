<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi (fillable)
    protected $fillable = [
        'subject',
        'description',
        'deadline',
        'date',
    ];
}
