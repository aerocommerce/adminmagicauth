<?php

namespace Aerocargo\Aeroauth;

use Aero\Admin\Models\Admin;
use Aero\Routing\Controller;
use Aerocargo\Aeroauth\DomainRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Validator;

class AeroAuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function index(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $url = URL::signedRoute('aeroauth');

        return view('aeroauth::aeroauth-index', ['url' => $url]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function send(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $validate = $request->validate([
            'email' => ['required','email', new DomainRule()]
        ]);

        $token = Str::uuid(4);
        $email = $validate['email'];

        AdminToken::create([
            'token' => $token,
            'email' => $email,
            'expires_at'=> Carbon::now()->addHours(24)
        ]);

        Mail::to($email)->queue(new SendToken($token, URL::signedRoute('aeroauth.verify')));

        return response()->redirectTo(route('home'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verify()
    {
        $validate = Validator::make(request()->all(), [
            'token' => ['required', new AdminTokenRule()]
        ]);

        if ($validate->fails()) {
            $validate = ['email' => ['Token has expired or does not exist. Please try again']];

            return redirect(config('aero.admin.slug').'/'.__('login'))
                ->withErrors($validate);
        }

        return view('aeroauth::verify', [
            'url' => URL::signedRoute('aeroauth.login')
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function verifyAndLogin(Request $request)
    {
        if (!$request->hasValidSignature()) {
             abort(401);
        }

        if ($request->input('shared') != config('aeroauth.shared_password')) {
            return redirect()->back()->withErrors('Shared key was invalid.');
        }

        $whitelistedIps = config('aeroauth')['whitelisted_ips'];

        if (collect($whitelistedIps)->contains(request()->ip())) {
            $admin = Admin::all()->first();

            Auth::guard(config('aero.admin.auth.defaults.guard'))->login($admin);

            return response()->redirectTo(config('aero.admin.slug'));
        }

         abort(401);
    }
}

