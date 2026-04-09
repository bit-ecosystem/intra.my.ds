<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'sub' => $user->getKey(),
            'name' => $user->name,
            'email' => $user->email,
            'roles' => method_exists($user, 'getRoleNames') ? $user->getRoleNames() : [],
            'attributes' => $user->personAttributes ?? [],
        ]);
    }
}
