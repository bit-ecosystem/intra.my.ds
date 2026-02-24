<?php

declare(strict_types=1);

namespace BitES\IdP\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Passport;

class OauthController extends Controller
{
    /**
     * Show a custom consent screen before authorizing a client.
     */
    public function authorize(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $client = app(ClientRepository::class)->findActive($request->client_id);

        if (! $client) {
            abort(404, 'Invalid client.');
        }

        // You can render a custom Blade view or Filament page here
        return view('idp.consent', [
            'client' => $client,
            'scopes' => $request->scope ? explode(' ', $request->scope) : [],
        ]);
    }

    /**
     * Approve and redirect back to the client.
     */
    public function approve(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        // Typically this delegates to Passport’s internal approve controller
        return app(\Laravel\Passport\Http\Controllers\ApproveAuthorizationController::class)
            ->approve($request);
    }

    /**
     * Deny and redirect back to client.
     */
    public function deny(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        return app(\Laravel\Passport\Http\Controllers\DenyAuthorizationController::class)
            ->deny($request);
    }
}
