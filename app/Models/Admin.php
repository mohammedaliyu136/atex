<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Admin extends User
{
    protected $table = 'users';

    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope('admin', function (Builder $builder) {
            $builder->whereHas('roles', function ($query) {
                $query->whereIn('name', ['super-admin', 'admin', 'admin']);
            });
        });
    }
}
