<?php

namespace Aerocargo\Aeroauth;

use Aero\Routing\Controller;
use Aerocargo\Aeroauth\Domain;
use Illuminate\Http\Request;
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

        Mail::to($email)->queue(new SendToken(Str::uuid(4)));


    }
}

