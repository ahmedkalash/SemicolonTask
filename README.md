# Laravel User/Group/Permission API

## Setup
- composer install
- cp .env.example .env
- configure DB
- php artisan key:generate
- php artisan migrate --seed
- php artisan storage:link
- php artisan serve

## Auth
Uses Laravel Sanctum (install with `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`).

## Endpoints
  GET|HEAD        api/v1/admin/groups ...................................... api.admin.groups.index › GroupController@index
  POST            api/v1/admin/groups .................................. api.admin.groups.store › GroupController@store
  GET|HEAD        api/v1/admin/groups/{group} .................................... api.admin.groups.show › GroupController@show
  PUT|PATCH       api/v1/admin/groups/{group} .................................. api.admin.groups.update › GroupController@update
  DELETE          api/v1/admin/groups/{group} .............................. api.admin.groups.destroy › GroupController@destroy
  POST            api/v1/admin/groups/{group}/permissions .................. api.admin. › GroupController@assignPermissions
  POST            api/v1/admin/login ................................................. api.admin.login › AuthController@login
  GET|HEAD        api/v1/admin/permissions ........................................... api.admin. › PermissionController@index
  GET|HEAD        api/v1/admin/users ............................................ api.admin.users.index › UserController@index
  POST            api/v1/admin/users ............................................. api.admin.users.store › UserController@store
  GET|HEAD        api/v1/admin/users/{user} ......................................... api.admin.users.show › UserController@show
  PUT|PATCH       api/v1/admin/users/{user} .................................. api.admin.users.update › UserController@update
  DELETE          api/v1/admin/users/{user} ................................... api.admin.users.destroy › UserController@destroy
  POST            api/v1/admin/users/{user}/groups ................................... api.admin. › UserController@assignGroups
