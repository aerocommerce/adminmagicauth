<?php

namespace Aerocargo\Adminmagicauth;

use Illuminate\Support\Facades\Facade;

class AdminMagicAuthFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'adminmagicauth';
    }
}
