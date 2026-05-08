<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StorageSystem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'pos_x',
        'pos_y',
        'pos_z',
        'columns',
        'rows',
        'levels',
        'spacing',
        'size_x',
        'size_y',
        'size_z',
        'orientation'
    ];
}
