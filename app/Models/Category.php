<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Platform\Concerns\Sortable;
use Orchid\Screen\AsSource;

class Category extends Model
{
    use HasFactory, SoftDeletes, AsSource, Filterable, Sortable;

    protected $fillable = [
        'name',
        'created_by_id'
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'name'       => Like::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name'
    ];
}
