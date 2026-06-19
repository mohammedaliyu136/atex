<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Seller extends User
{
    protected $table = 'users';

    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope('seller', function (Builder $builder) {
            $builder->whereHas('roles', function ($query) {
                $query->where('name', 'seller');
            });
        });
    }

    public function getMorphClass()
    {
        return User::class;
    }
}
