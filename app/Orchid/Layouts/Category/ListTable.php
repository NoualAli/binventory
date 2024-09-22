<?php

namespace App\Orchid\Layouts\Category;

use App\Models\Category;
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
    protected $target = 'categories';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->cantHide()->sort(),
            TD::make('name', 'Nom')->filter(Input::make())->cantHide()->sort(),
            TD::make('Actions')
                ->cantHide()
                ->alignRight()
                ->render(fn(Category $category) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        ModalToggle::make('Edit')
                            ->modal('updateCategoryModal')
                            ->method('edit', ['category' => $category->id])
                            ->icon('bs.pencil'),
                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm('Êtes-vous sûr de vouloir supprimer la catégorie <b>' . $category->name . '</b>')
                            ->method('delete', [
                                'category' => $category->id,
                            ]),
                    ])),

        ];
    }
}
