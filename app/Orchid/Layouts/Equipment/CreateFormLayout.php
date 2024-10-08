<?php

namespace App\Orchid\Layouts\Equipment;

use App\Models\Agency;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

class CreateFormLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        $technicians = collect(['' => '']);

        $techniciens = User::role('Technicien')->get();

        foreach ($techniciens as $technicien) {
            $technicians[$technicien->id] = $technicien->name;
        }
        return [
            Input::make()->type('hidden')->title('Motif'),
            Group::make([
                CheckBox::make('repair')->value($this->getValue('repaire', 0))->default(false)->sendTrueOrFalse()->placeholder('Réparation'),
                CheckBox::make('install_ad')->value($this->getValue('install_ad', 0))->default(false)->sendTrueOrFalse()->placeholder('Installation AD'),
            ])->autoWidth()->alignEnd(),

            Group::make([
                Input::make('serial_number')
                    ->type('text')
                    ->title(__('N° de série'))
                    ->placeholder(__('N° de série'))
                    ->max(255)
                    ->required()
                    ->value($this->getValue('serial_number')),

                DateTimer::make('entered_at')
                    ->title(__('Date d\'entrer'))
                    ->allowInput()
                    ->required()
                    ->format24hr()
                    ->format('Y-m-d H:i')
                    ->enableTime()
                    ->value($this->getValue('entered_at', now())),
            ]),

            Input::make('description')
                ->title(__('Description'))
                ->placeholder(__('Description'))
                ->required()
                ->value($this->getValue('description')),

            Group::make([
                Relation::make('agency_id')
                    ->title(__('Agence'))
                    ->fromModel(Agency::class, 'fullname')
                    ->required()
                    ->value($this->getValue('agency_id')),

                Relation::make('category_id')
                    ->title(__('Catégorie'))
                    ->fromModel(Category::class, 'name')
                    ->required()
                    ->value($this->getValue('category_id')),
                Select::make('technicians')
                    ->canSee(!Auth::user()->inRole('technicien'))
                    ->multiple()
                    ->title(__('Techniciens'))
                    ->options($technicians)
                    ->required()
                    ->value($this->getValue('technicians')),
            ]),

            Upload::make('input_discharge')->title('Décharge d\'entrée')->groups('documents/input_discharges')->acceptedFiles('image/*,application/pdf')->parallelUploads(2),

            Button::make(__('Save'))
                ->type(Color::BASIC())
                ->icon('bs.check-circle')
                ->right()
                ->method('save')
        ];
    }

    private function getValue(string $key, $value = null){
        return old($key, $value);
    }
}
