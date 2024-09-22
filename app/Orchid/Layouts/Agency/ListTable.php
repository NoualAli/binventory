<?php

namespace App\Orchid\Layouts\Agency;

use App\Models\Agency;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ListTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'agencies';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->cantHide()->sort(),
            TD::make('fullname', 'Nom complet')->filter(Input::make())->cantHide()->sort(),
            TD::make('Actions')
                ->cantHide()
                ->alignRight()
                ->render(fn(Agency $agency) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        ModalToggle::make('Edit')
                            ->modal('updateAgencyModal')
                            ->method('edit', ['agency' => $agency->id])
                            ->icon('bs.pencil'),
                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm('Êtes-vous sûr de vouloir supprimer l\'agence <b>' . $agency->fullname . '</b>')
                            ->method('delete', [
                                'agency' => $agency->id,
                            ]),
                    ])),
        ];
    }
}
