<?php

namespace App\Orchid\Layouts\Equipment;

use App\Models\Agency;
use App\Models\Category;
use App\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Color;

class EditFormLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    protected $equipment;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        $this->equipment = $this->query->all()['equipment'];
        // dd($this->equipment->technicians->pluck('id')->toArray());

        $technicians = collect(['' => '']);

        $techniciens = User::role('Technicien')->get();

        foreach ($techniciens as $technicien) {
            $technicians[$technicien->id] = $technicien->name;
        }
        return [
            Input::make()->type('hidden')->title('Motif'),
            Group::make([
                CheckBox::make('repair')->value($this->equipment->repair)->default(false)->sendTrueOrFalse()->placeholder('Réparation'),
                CheckBox::make('install_ad')->value($this->equipment->install_ad)->default(false)->sendTrueOrFalse()->placeholder('Installation AD'),
            ])->autoWidth()->alignEnd(),

            Group::make([
                Input::make('serial_number')
                    ->type('text')
                    ->title(__('N° de série'))
                    ->placeholder(__('N° de série'))
                    ->max(255)
                    ->value($this->equipment->serial_number)
                    ->required(),

                DateTimer::make('entered_at')
                    ->title(__('Date d\'entrer'))
                    ->allowInput()
                    ->required()
                    ->value(now())
                    ->format24hr()
                    ->format('Y-m-d H:i')
                    ->value($this->equipment->getAttribute('entered_at'))
                    ->enableTime(),
            ]),

            Input::make('description')
                ->title(__('Description'))
                ->placeholder(__('Description'))
                ->value($this->equipment->description)
                ->required(),
            Group::make([
                Relation::make('agency_id')
                    ->title(__('Agence'))
                    ->fromModel(Agency::class, 'fullname')
                    ->value($this->equipment->agency_id)
                    ->required(),
                Relation::make('category_id')
                    ->title(__('Catégorie'))
                    ->fromModel(Category::class, 'name')
                    ->value($this->equipment->category_id)
                    ->required(),
                Select::make('technicians')
                    ->multiple()
                    ->title(__('Techniciens'))
                    ->options($technicians)
                    ->value($this->equipment->technicians->pluck('id')->toArray())
                    ->required(),
            ]),

            Upload::make('input_discharge')->value($this->equipment->inputDischarge()->first())->title('Décharge d\'entrée')->groups('documents/input_discharges')->acceptedFiles('image/*,application/pdf')->parallelUploads(2),

            Button::make(__('Save'))
                ->type(Color::BASIC())
                ->icon('bs.check-circle')
                ->right()
                ->method('save')
        ];
    }
}
