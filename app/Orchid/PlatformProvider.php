<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Tableau de bord')
                ->icon('bs.house')
                ->route(config('platform.index')),

            Menu::make(__('Agences'))
                ->icon('bs3.bank')
                ->route('platform.agencies')
                ->permission('platform.agencies.show')
                ->divider(),

            Menu::make(__('Catégories'))
                ->icon('bs3.bookmarks')
                ->route('platform.categories')
                ->permission('platform.categories.show')
                ->title(__('Gestion matériel')),

            Menu::make(__('Matériel'))
                ->icon('bs3.motherboard')
                ->route('platform.equipments')
                ->permission('platform.equipments.show')
                ->divider(),

            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Gestion utilisateurs')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
                ItemPermission::group(__('Catégories'))
                ->addPermission('platform.categories.show', __('Voir'))
                ->addPermission('platform.categories.create', __('Créer'))
                ->addPermission('platform.categories.edite', __('Modifier'))
                ->addPermission('platform.categories.delete', __('Supprimer')),
                ItemPermission::group(__('Agences'))
                ->addPermission('platform.agencies.show', __('Voir'))
                ->addPermission('platform.agencies.create', __('Créer'))
                ->addPermission('platform.agencies.edite', __('Modifier'))
                ->addPermission('platform.agencies.delete', __('Supprimer')),
                ItemPermission::group(__('Matériels'))
                ->addPermission('platform.equipments.show', __('Voir'))
                ->addPermission('platform.equipments.create', __('Créer'))
                ->addPermission('platform.equipments.edit', __('Modifier'))
                ->addPermission('platform.equipments.delete', __('Supprimer'))
        ];
    }
}
