<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Buyer extends User
{
    protected $table = 'users';

    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope('buyer', function (Builder $builder) {
            $builder->whereHas('roles', function ($query) {
                $query->where('name', 'buyer');
            });
        });
    }

    public function getMorphClass()
    {
        return User::class;
    }
}
