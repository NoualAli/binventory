<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\BaseHttpEloquentFilter;

class AgencyFullnameFilter extends BaseHttpEloquentFilter
{
    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereRaw("CONCAT(code, ' - ', name) LIKE ?", ['%' . $this->getHttpValue() . '%']);
    }
}
