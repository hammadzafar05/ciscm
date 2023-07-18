<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'admin/filemanager/*',
        'ipn/*',
        'cart/callback/*',
        'cart/*',
        'cart',
	    'sslcommerz/*',
	    '/pay-via-ajax',
	    '/success',
	    '/cancel',
	    '/fail',
	    '/ipn',
	    '/success/sslcommerz',
	    'http://localhost/sites/zaidi/worldacademy.uk/public/success/sslcommerz',
	    'http://localhost/sites/zaidi/worldacademy.uk/public/cart/callback/sslcommerz',
	    'https://worldacademy.uk/public/cart/callback/sslcommerz',
	    'https://worldacademy.uk/cart/callback/sslcommerz',
    ];
}
