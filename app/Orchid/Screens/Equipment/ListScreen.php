<?php

namespace App\Orchid\Screens\Equipment;

use App\Enums\EquipmentStateEnum;
use App\Models\Equipment;
use App\Orchid\Layouts\Equipment\ListTableLayout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        abort_if(!can('platform.equipments.show'), 401);
        $equipments = Equipment::filters();
        if (Auth::user()->inRole('technicien')) {
            $equipments = $equipments->whereRelation('technicians', 'user_id',auth()->user()->id);
        }

        $equipments = $equipments->paginate(10);
        return compact('equipments');
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Matériels';
    }

    /**
     * The description of the screen displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Liste des matériels';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->route('platform.equipments.create'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ListTableLayout::class,
            $this->updateStateModal()
        ];
    }

    public function updateState(Request $request, Equipment $equipment)
    {
        if (!in_array($request->state, EquipmentStateEnum::asArray())) {
            return new Exception("Le statut {$request->state} n'existe pas",422);
        }

        abort_if(!(!anyRole(['admin', 'chef-de-service']) || !in_array(Auth::user()->id,$equipment->technicians()->get()->pluck('id')->toArray())), 401);

        try {
            $equipment->states()->update(['is_current' => false]);
            $result = $equipment->state()->create([
                'state' => $request->state,
                'is_current' => true,
                'user_id' => Auth::user()->id
            ]);
            if ($result) {
                Toast::success("Statut mis à jour avec succès");
            } else {
                Toast::error("Une erreur est survenu lors de la mise à jour du statut");
            }
        } catch (\Throwable $th) {
            Toast::success($th->getMessage());
        }
    }

    private function updateStateModal()
    {
        $equipment = null;
        $currentState = null;
        if (request('equipment')) {
            $equipment = Equipment::findOrFail(request('equipment'));
            abort_if(!(!anyRole(['admin', 'chef-de-service']) || !in_array(Auth::user()->id,$equipment->technicians()->get()->pluck('id')->toArray())), 401);
            $currentState = $equipment->state()->first()->state;
        }

        return Layout::modal('updateStateModal', Layout::rows([
            Select::make('state')
                ->title('Statut')
                ->required()
                ->options(EquipmentStateEnum::asArray())
                ->value($currentState)
        ]))
            ->method("updateState")
            ->title('Modifier le statut')
            ->withoutCloseButton()
            ->applyButton('Enregistrer')
            ->deferred('loadOnOpenStateModel');
    }

    /**
     * Loads category data when opening the modal window.
     *
     * @return array
     */
    public function loadOnOpenStateModel(Equipment $equipment): iterable
    {
        abort_if(!(!anyRole(['admin', 'chef-de-service']) || !in_array(Auth::user()->id,$equipment->technicians()->get()->pluck('id')->toArray())), 401);
        return [
            'equipment' => $equipment,
        ];
    }

    public function delete(Equipment $equipment, bool $redirect = false)
    {
        abort_if(!can('platform.equipments.delete'), 401);
        abort_if(!(!anyRole(['admin', 'chef-de-service']) || !in_array(Auth::user()->id,$equipment->technicians()->get()->pluck('id')->toArray())), 401);
        try {
            if ($equipment->delete()) {
                Toast::success("Matériel supprimer avec succès");
            } else {
                Toast::error("Une erreur est survenu lors de la suppression du matériel");
            }
        } catch (\Throwable $th) {
            Toast::success($th->getMessage());
        }
    }
}
