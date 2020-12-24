<?php

namespace Aerocargo\Aeroauth;

use Aero\Routing\Controller;
use Aerocargo\Aeroauth\Domain;
use Illuminate\Http\Request;

class AeroAuthController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return abort(401);
        }

        return view('aeroauth::aeroauth-index');
    }

    public function send(Request $request)
    {
        $request->validate([
            'email' => ['required','email', new Domain()]
        ]);
    }
}

