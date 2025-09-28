<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignPermissionsToGroupRequest;
use App\Models\Group;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GroupController extends Controller implements HasMiddleware
{

    /**
     * @inheritDoc
     */
    public static function middleware()
    {
        return [
            new Middleware('checkPermission:view_groups', only: ['index', 'show']),
            new Middleware('checkPermission:create_groups', only: ['store']),
            new Middleware('checkPermission:delete_groups', only: ['destroy']),
            new Middleware('checkPermission:update_groups', only: ['update']),
            new Middleware('checkPermission:assign_permission_to_role', only: ['assignPermissions']),
        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Group::with('permissions')->paginate(perPage: 10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupRequest $request)
    {
        return Group::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        return $group->load('permissions', 'users');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        $group->update($request->validated());
        return $group;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return response()->json(['message' => 'item delete successfuly'], Response::HTTP_OK);
    }

    public function assignPermissions(AssignPermissionsToGroupRequest $request, Group $group)
    {
        $group->permissions()->attach($request->validated('permissions'));
        return $group->load('permissions');
    }


}
