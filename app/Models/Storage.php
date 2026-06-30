<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Relation;

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

    public function system() : Relation
    {
        return $this->belongsTo(StorageSystem::class, 'storage_system_id');
    }
}