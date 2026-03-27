<?php

declare(strict_types=1);

namespace Bites\Core\Identity\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Http\Controllers\ApproveAuthorizationController;
use Laravel\Passport\Http\Controllers\DenyAuthorizationController;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;

class OauthController extends Controller
{
    /**
     * Show a custom consent screen before authorizing a client.
     */
    public function authorize(Request $request): Factory|View
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
    public function approve(Request $request): Response
    {
        // Typically this delegates to Passport’s internal approve controller
        return app(ApproveAuthorizationController::class)
            ->approve($request);
    }

    /**
     * Deny and redirect back to client.
     */
    public function deny(Request $request): Response
    {
        return app(DenyAuthorizationController::class)
            ->deny($request);
    }
}
