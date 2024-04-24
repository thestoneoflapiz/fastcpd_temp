<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        "sync/",
        "data/import/action",
        "help/action/upload",
        "/instructor/register/action/*",
        "/personal/register/action/*",
        "/provider/register/action/*",
        "/course_management/upload_video",
        "/course/management/poster/upload",
        "/course/management/content/textarea/upload",
        "/webinar/management/attract/video/upload",
        "/webinar/management/attract/poster/upload",
        "/webinar/management/content/textarea/upload",
        "/hooks/pmongo/payment",
    ];
}
