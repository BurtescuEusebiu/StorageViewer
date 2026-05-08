<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Storage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'storage_system_id',
        'row',
        'column',
        'level'
    ];

    public function system()
    {
        return $this->belongsTo(StorageSystem::class, 'storage_system_id');
    }
}