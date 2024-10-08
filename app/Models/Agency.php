<?php

namespace App\Models;

use App\Orchid\Filters\AgencyFullnameFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Platform\Concerns\Sortable;
use Orchid\Screen\AsSource;

class Agency extends Model
{
    use HasFactory, SoftDeletes, AsSource, Filterable, Sortable;

    protected $fillable = [
        'name',
        'code',
        'created_by_id'
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'name' => Like::class,
        'code' => Like::class,
        'fullname' => AgencyFullnameFilter::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'code',
    ];

    public function getFullnameAttribute(): string
    {
        return $this->code . ' - ' . $this->name;
    }

    public function getCodeAttribute(string $code): string
    {
        return sprintf('%04d', $code);
    }
}
