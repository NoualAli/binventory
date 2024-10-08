<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Platform\Concerns\Sortable;
use Orchid\Screen\AsSource;

class Equipment extends Model
{
    use HasFactory, SoftDeletes, AsSource, Filterable, Sortable, Attachable;

    public $table = 'equipments';

    protected $fillable = [
        'serial_number',
        'description',
        'install_ad',
        'repair',
        'created_by_id',
        'agency_id',
        'category_id',
        'entered_at',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'serial_number' => Like::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'serial_number',
        'entered_at'
    ];

    public $casts = [
        "entered_at" => "datetime:d-m-Y H:i",
        "created_at" => "datetime:d-m-Y H:i",
        "updated_at" => "datetime:d-m-Y H:i",
        "deleted_at" => "datetime:d-m-Y H:i",
    ];

    /**
     * Getters
     */

    public function getShortDescriptionAttribute(): ?string
    {
        return strlen($this->description) > 30 ? substr($this->description, 0, 30) : $this->description;
    }

    /**
     * Relationships
     */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function technicians()
    {
        return $this->belongsToMany(User::class, 'equipments_technicians', 'equipment_id', 'user_id');
    }


    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function states()
    {
        return $this->hasMany(EquipmentState::class, 'equipment_id');
    }

    public function state()
    {
        return $this->hasMany(EquipmentState::class, 'equipment_id')->where('is_current', true);
    }

    public function inputDischarge()
    {
        return $this->attachments()->where('group', 'documents/input_discharges');
    }

    public function outputDischarge()
    {
        return $this->attachments()->where('group', 'documents/output_discharges');
    }
}
