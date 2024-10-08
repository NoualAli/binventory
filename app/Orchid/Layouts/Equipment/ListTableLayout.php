<?php

namespace App\Orchid\Layouts\Equipment;

use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ListTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'equipments';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', '#'),
            TD::make('serial_number', 'N° de série')->render(fn (Equipment $equipment) => Link::make($equipment->serial_number)
            ->route('platform.equipments.edit', ['equipment' => $equipment->id])),
            TD::make('short_description', 'Description'),
            TD::make('state', 'Statut')->render(fn(Equipment $equipment) => $equipment->state()->first()->state),
            TD::make('entered_at', 'Date d\'entrée')->usingComponent(DateTimeSplit::class),
            TD::make('Actions')
                ->cantHide()
                ->alignRight()
                ->render(fn(Equipment $equipment) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make('Détails')
                        ->canSee(can('platform.equipments.show'))
                            ->route('platform.equipments.show', ['equipment' => $equipment])
                            ->icon('bs.eye'),
                        Link::make('Edit')
                        ->canSee(can('platform.equipments.edit'))
                            ->route('platform.equipments.edit', ['equipment' => $equipment->id])
                            ->icon('bs.pencil'),
                        Button::make(__('Delete'))
                        ->canSee(can('platform.equipments.delete'))
                            ->icon('bs.trash3')
                            ->confirm('Êtes-vous sûr de vouloir supprimer le matériel <b>' . $equipment->serial_number . '</b>')
                            ->method('delete', [
                                'equipment' => $equipment->id,
                                'redirect' => false,
                            ]),
                            ModalToggle::make(__('Statut'))
                            ->canSee(can('platform.equipments.edit') || anyRole('admin') || $equipment->created_by_id == Auth::user()->id)
                            ->icon('bs.bullseye')
                            ->modal('updateStateModal')
                            ->method('updateState', ['equipment' => $equipment->id])
                    ])),

        ];
    }

    /**
     * @return string
     */
    protected function textNotFound(): string
    {
        return "Vous n'avez enregistré aucun matériel";
    }

    /**
     * @return bool
     */
    protected function striped(): bool
    {
        return true;
    }
}
