<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'photo',
        'role'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setPasswordAttribute($value): void
    {
         $this->attributes['password'] = Hash::make($value);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    public function permissions(array $colomuns = ['name']): Collection
    {
        // todo cache the permissins
        $colomuns = implode(',', $colomuns);
        return Permission::query()
            ->select("permissions.$colomuns")
            ->join('group_permitions', 'permissions.id', '=', 'group_permitions.permission_id')
            ->join('user_groups', 'group_permitions.group_id', '=', 'user_groups.group_id')
            ->where('user_groups.user_id', $this->id)
            ->distinct()
            ->get();
    }   

    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->contains('name', $permission);
    }
}
