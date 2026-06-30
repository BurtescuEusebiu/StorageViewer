<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Relation;

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

    public function storages(): Relation
    {
        return $this->hasMany(Storage::class, 'storage_system_id');
    }

    public static function isSpaceOccupied($posX, $posZ, $sizeX, $sizeZ, $excludeId = null)
    {
        $r1HalfWidth = $sizeX / 2;
        $r1HalfLength = $sizeZ / 2;

        $r1MinX = $posX - $r1HalfWidth;
        $r1MaxX = $posX + $r1HalfWidth;
        $r1MinZ = $posZ - $r1HalfLength;
        $r1MaxZ = $posZ + $r1HalfLength;

        $query = self::query();
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        $existingRacks = $query->get();

        foreach ($existingRacks as $rack) {
            $r2HalfWidth = $rack->size_x / 2;
            $r2HalfLength = $rack->size_z / 2;

            $r2MinX = $rack->pos_x - $r2HalfWidth;
            $r2MaxX = $rack->pos_x + $r2HalfWidth;
            $r2MinZ = $rack->pos_z - $r2HalfLength;
            $r2MaxZ = $rack->pos_z + $r2HalfLength;

            if ($r1MinX < $r2MaxX && $r1MaxX > $r2MinX && $r1MinZ < $r2MaxZ && $r1MaxZ > $r2MinZ) {
                return true;
            }
        }

        return false;
    }
}
