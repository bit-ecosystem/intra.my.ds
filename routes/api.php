<?php

use App\Http\Controllers\ReceiveDataController;
use Bites\Business\Lms\Entities\Course;
use Bites\Business\Lms\Entities\CourseJsonApi;
use Bites\Organization\Structure\Location;
use Bites\Organization\Structure\LocationJsonApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/receive', [ReceiveDataController::class, 'store']);

Route::post('/slo/revoke', function (Request $request) {
    // Auth this endpoint with client credentials or a service account guard
    // e.g., middleware('client') or 'auth:api' with a special machine user.
    // Validate inputs:
    $request->validate([
        'user_id' => ['required', 'integer'],
        'client_id' => ['nullable', 'integer'],
        'all' => ['nullable', 'boolean'], // if true, revoke all user tokens
    ]);

    $userId = (int) $request->input('user_id');
    $clientId = $request->input('client_id');
    $revokeAll = (bool) $request->boolean('all', true);

    // Fetch tokens for the user
    $builder = DB::table('oauth_access_tokens')->where('user_id', $userId)->where('revoked', false);

    if (! $revokeAll && $clientId) {
        $builder->where('client_id', $clientId);
    }

    $tokens = $builder->get();

    foreach ($tokens as $token) {
        // Revoke access token
        DB::table('oauth_access_tokens')->where('id', $token->id)->update(['revoked' => true]);

        // Revoke associated refresh tokens
        DB::table('oauth_refresh_tokens')->where('access_token_id', $token->id)->update(['revoked' => true]);
    }

    // Optional: if the user has a web session on App A and you want to ensure front-channel logout,
    // you could enqueue an action to invalidate it on next request or trigger user-specific session invalidation
    // (Laravel sessions are cookie-bound; server-to-server cannot directly kill a browser's session cookie).

    return response()->json(['revoked' => $tokens->count()]);
});

Route::get('/get_data/locations', function () {
    return LocationJsonApi::collection(
        Location::with(['parent', 'company'])->get()
    );
});

Route::get('/get_data/locations/{location?}', function (?Location $location) {
    if ($location) {
        return $location->toResource();
    }

    return LocationJsonApi::collection(
        Location::with(['parent', 'company'])->get()
    );
});

Route::get('/get_data/courses', function () {
    return CourseJsonApi::collection(
        Course::with(['modules'])->get()
    );
});
Route::get('/get_data/courses/{course?}', function (?Course $course) {
    if ($course) {
        return $course->toResource();
    }

    return CourseJsonApi::collection(
        Course::with(['modules'])->get()
    );
});
Route::get('/get_data/{resource}', function (string $resource) {
    $map = config("api_resources.$resource");

    abort_if(! $map, 404, 'Unknown resource');

    $models = $map['model']::query()
        ->with($map['with'] ?? [])
        ->get();

    return $map['resource']::collection($models);
});

// Route::get('/get_data/{type}/{id?}', function (string $type, $id = null) {
//     // 1. Resolve Class Names (e.g., 'locations' -> 'Location')
//     $modelName = Str::studly(Str::singular($type));

//     // 2. Define your Namespace Mappings
//     $namespaces = [
//         'Location' => ['model' => \Bites\Organization\Structure\Location::class, 'api' => \Bites\Organization\Structure\LocationJsonApi::class, 'with' => ['parent', 'company']],
//         'Course'   => ['model' => \Bites\Business\Lms\Entities\Course::class, 'api' => \Bites\Business\Lms\Entities\CourseJsonApi::class, 'with' => ['modules']],
//     ];

//     if (!isset($namespaces[$modelName])) {
//         abort(404, "Data type '$type' not supported.");
//     }

//     $config = $namespaces[$modelName];
//     $modelClass = $config['model'];
//     $resourceClass = $config['api'];

//     // 3. Handle Single Item
//     if ($id) {
//         $item = $modelClass::with($config['with'])->findOrFail($id);
//         return method_exists($item, 'toResource') ? $item->toResource() : new $resourceClass($item);
//     }

//     // 4. Handle Collection (with the permission scope we debugged!)
//     $query = $modelClass::with($config['with']);

//     if (method_exists($modelClass, 'scopeVisibleTo')) {
//         $query->visibleTo(auth()->user());
//     }

//     return $resourceClass::collection($query->get());
// });
