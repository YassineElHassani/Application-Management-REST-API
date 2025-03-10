<?

namespace App\Http;

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// In the $middlewareGroups property:
// protected $middlewareGroups = [
//     // ...
//     'api' => [
//         EnsureFrontendRequestsAreStateful::class,
//         // other middleware...
//     ],
// ];
