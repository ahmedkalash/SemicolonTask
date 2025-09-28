<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class UserController extends Controller implements HasMiddleware
{
    // todo: Refactor to use UserService
    public static function middleware(): array
    {
        return [
            new Middleware('checkPermission:view_users', only: ['index', 'show']),
            new Middleware('checkPermission:create_users', only: ['store']),
            new Middleware('checkPermission:delete_users', only: ['destroy']),
            new Middleware('checkPermission:update_users', only: ['update']),
            new Middleware('checkPermission:assign_group_to_user', only: ['assignRoles']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->query('q')) {
            $query->where(
                fn($q) => $q
                    ->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
            );
        }

        $users = $query->paginate(15);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $user = new User;
        DB::transaction(function () use ($request, $data, $user) {
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->phone = $data['phone'];
            $user->role = $data['role'];
            if ($request->hasFile('photo')) {
                $user->photo = $request->file('photo')->store('photo', 'public');
            }
            $user->save();

            if ($data['groups'] ?? null) {
                $user->groups()->sync($data['groups']);
            }
        });

        return new UserResource($user->load('groups'));

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user->load('groups'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photo', 'public');
        }

        $user->update($data);

        if ($groups = $request->input('groups')) {
            $user->groups()->sync($groups);
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }

    public function assignGroups(Request $request, User $user)
    {
        $request->validate(['groups' => 'required|array', 'groups.*' => 'required|integer|exists:groups,id']);
        $user->groups()->sync($request->groups);

        return new UserResource($user->load('groups'));
    }
}
