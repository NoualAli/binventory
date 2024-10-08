<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentState extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow conventions
    protected $table = 'equipments_states';

    // Define fillable attributes
    protected $fillable = [
        'equipment_id',
        'user_id',
        'state',
        'is_current',
    ];

    // Define the relationship to Equipment
    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    // Define the relationship to creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
