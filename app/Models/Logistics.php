<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Logistics extends User
{
    protected $table = 'users';

    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope('logistics', function (Builder $builder) {
            $builder->whereHas('roles', function ($query) {
                $query->where('name', 'logistics');
            });
        });
    }

    public function getMorphClass()
    {
        return User::class;
    }
}
