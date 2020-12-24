<?php

namespace Aerocargo\Aeroauth;

use Aero\Admin\Models\Admin;
use Aero\Routing\Controller;
use Aerocargo\Aeroauth\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AeroAuthController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return abort(401);
        }

        $url = URL::signedRoute('aeroauth');

        return view('aeroauth::aeroauth-index', ['url' => $url]);
    }

    public function send(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return abort(401);
        }

        $validate = $request->validate([
            'email' => ['required','email', new Domain()]
        ]);

        $email = $validate['email'];

        Mail::to($email)->queue(new SendToken(Str::uuid(4), URL::signedRoute('aeroauth.login')));

        return response()->redirectTo(route('home'));
    }

    public function verifyAndLogin(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return abort(401);
        }

        $whitelistedIps = config('aeroauth')['whitelisted_ips'];

        if (collect($whitelistedIps)->contains(request()->ip())) {
            $admin = Admin::all()->first();

            Auth::guard(config('aero.admin.auth.defaults.guard'))->login($admin);

            return response()->redirectTo(config('aero.admin.slug'));
        }

        return abort(401);
    }
}

