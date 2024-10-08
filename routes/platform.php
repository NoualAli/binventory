<?php

declare(strict_types=1);

use App\Orchid\Screens\Agency\ListScreen as AgencyListScreen;
use App\Orchid\Screens\Equipment\ListScreen as EquipmentListScreen;
use App\Orchid\Screens\Equipment\CreateScreen as EquipmentCreateScreen;
use App\Orchid\Screens\Equipment\EditScreen as EquipmentEditScreen;
use App\Orchid\Screens\Equipment\ShowScreen as EquipmentShowScreen;
use App\Orchid\Screens\Category\CategoryListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn(Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn(Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

Route::screen('categories', CategoryListScreen::class)
    ->name('platform.categories')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push('Catégories');
    });

Route::screen('agencies', AgencyListScreen::class)
    ->name('platform.agencies')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push('Agences');
    });

Route::screen('equipments', EquipmentListScreen::class)
    ->name('platform.equipments')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push('Matériels');
    });

Route::screen('equipments/create', EquipmentCreateScreen::class)
    ->name('platform.equipments.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.equipments')
        ->push(__('Create'), route('platform.equipments.create')));

Route::screen('equipments/edit', EquipmentEditScreen::class)
    ->name('platform.equipments.edit')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.equipments')
            ->push('Modifier', route('platform.equipments.edit'));
    });

Route::screen('equipments/show', EquipmentShowScreen::class)
    ->name('platform.equipments.show')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.equipments')
            ->push('Détail', route('platform.equipments.show'));
    });
