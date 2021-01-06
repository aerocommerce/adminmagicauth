<?php

namespace Aerocargo\Adminmagicauth;

use Illuminate\Support\Facades\URL;

class AdminMagicAuth
{
    public function button()
    {
        $whitelistedIps = config('adminmagicauth.whitelisted_ips');

        if (collect($whitelistedIps)->contains(request()->ip())) {
            return view('adminmagicauth::button', ['url' => URL::signedRoute('adminmagicauth.index')]);
        }
    }
}
