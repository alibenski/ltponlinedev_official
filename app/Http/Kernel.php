<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [

        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // https redirects
            // \App\Http\Middleware\HttpsProtocol::class,
        ],

        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        //custom middleware classes
        'checksubmissioncount' => \App\Http\Middleware\CheckSubmissionCount::class,
        'checksubmissionselfpay' => \App\Http\Middleware\CheckSubmissionSelfPay::class,
        'opencloseenrolment' => \App\Http\Middleware\OpenCloseEnrolment::class,
        'checkcontinue' => \App\Http\Middleware\CheckContinue::class,
        'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
        'redirect-if-not-profile' => \App\Http\Middleware\RedirectIfNotYourProfile::class,
        'isAdmin' => \App\Http\Middleware\AdminMiddleware::class,
        'clearance' => \App\Http\Middleware\ClearanceMiddleware::class,
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
        'limit-cancel' => \App\Http\Middleware\LimitCancelPeriod::class,
        'check-prev-url' => \App\Http\Middleware\CheckPrevURL::class,
        'check-placement-exam' => \App\Http\Middleware\CheckPlacementExam::class,
        'prevent-access-placement' => \App\Http\Middleware\PreventAccessPlacement::class,
        'first-time-login' => \App\Http\Middleware\FirstTimeLogin::class,
        'open-close-approval-routes' => \App\Http\Middleware\OpenCloseApprovalRoutes::class,
        'open-close-approval-routes-hr' => \App\Http\Middleware\OpenCloseApprovalRoutesHR::class,
    ];
}
