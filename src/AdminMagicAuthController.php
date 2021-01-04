<?php

namespace Aerocargo\Adminmagicauth;

use Aero\Admin\Models\Admin;
use Aero\Routing\Controller;
use Aerocargo\Adminmagicauth\DomainRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Validator;

class AdminMagicAuthController extends Controller
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

        $url = URL::signedRoute('adminmagicauth');

        return view('adminmagicauth::index', ['url' => $url]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function status(Request $request)
    {
        return view('adminmagicauth::status');
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
            'expires_at'=> Carbon::now()->addHours(config('adminmagicauth.token_timeout_in_hours'))
        ]);

        Mail::to($email)->queue(new SendToken($token, URL::signedRoute('adminmagicauth.verify')));

        return response()->redirectTo(route('adminmagicauth.status'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verify()
    {
        $adminToken = AdminToken::where(['token' => request()->input('token')])->first();

        if (!$adminToken || $adminToken->expires_at <= Carbon::now()) {
            $validate = ['email' => ['Token has expired or does not exist. Please try again']];

            if ($adminToken) {
                AdminToken::destroy($adminToken->id);
            }

            return redirect(config('aero.admin.slug').'/'.__('login'))
                ->withErrors($validate);
        }

        return view('adminmagicauth::verify', [
            'url' => URL::signedRoute('adminmagicauth.login'),
            'email' => $adminToken->email
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

        if ($request->input('shared') != config('adminmagicauth.shared_password')) {
            return redirect()->back()->withErrors('Shared key was invalid.');
        }

        $whitelistedIps = config('adminmagicauth.whitelisted_ips');

        if (collect($whitelistedIps)->contains(request()->ip())) {

            $email = $request->input('email');
            $admin = Admin::where('email', '=', $email)->first();

            if (!$admin) {
                $permissions = config('adminmagicauth.permissions');
                $emailDomain = explode('@', $email)[1];

                if (isset($permissions[$emailDomain])) {
                    $permissions = $permissions[$emailDomain];
                }

                // Create a new admin
                $admin = Admin::create([
                    'name' => $email,
                    'email' => $email,
                    'password' => bcrypt(Str::uuid(4)),
                    'permissions' => $permissions
                ]);
            }

            Auth::guard(config('aero.admin.auth.defaults.guard'))->login($admin);

            $adminToken = AdminToken::where('email', '=', $email)->first();
            AdminToken::destroy($adminToken->id);

            return response()->redirectTo(config('aero.admin.slug'));
        }

         abort(401);
    }
}

