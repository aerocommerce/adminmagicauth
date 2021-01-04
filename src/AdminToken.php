<?php

namespace Aerocargo\Aeroauth;

use Aero\Common\Models\Model;

class AdminToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'email', 'expires_at',
    ];
}
