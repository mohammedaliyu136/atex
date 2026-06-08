<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Exporter extends User
{
    protected $table = 'users';

    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope('exporter', function (Builder $builder) {
            $builder->whereHas('roles', function ($query) {
                $query->where('name', 'exporter');
            });
        });
    }

    public function getMorphClass()
    {
        return User::class;
    }
}
