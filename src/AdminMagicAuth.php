<?php

namespace Aerocargo\Adminmagicauth;

use Illuminate\Support\Facades\URL;

/**
 * Class AdminMagicAuth
 * @package Aerocargo\Adminmagicauth
 */
class AdminMagicAuth
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function button()
    {
        $whitelistedIps = config('adminmagicauth.whitelisted_ips');

        if (collect($whitelistedIps)->contains(request()->ip())) {
            return view('adminmagicauth::button', ['url' => URL::signedRoute('adminmagicauth.index')]);
        }
    }
}
