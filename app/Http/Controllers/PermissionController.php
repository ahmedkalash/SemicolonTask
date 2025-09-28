<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('checkPermission:view_permissions', only: ['index']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Permission::all();
    }

}
