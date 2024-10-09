<?php

namespace App\Orchid\Screens\Equipment;

use App\Enums\EquipmentStateEnum;
use App\Http\Requests\Equipment\StoreRequest;
use App\Models\Equipment;
use App\Orchid\Layouts\Equipment\EditFormLayout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class EditScreen extends Screen
{
    public $equipment;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Equipment $equipment): iterable
    {
        abort_if(!can('platform.equipments.edit'), 401);
        $this->equipment = $equipment;
        return [
            'equipment' => $equipment
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Modifier le matériel';
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            EditFormLayout::class
        ];
    }

     /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Détails')
                ->route('platform.equipments.show', ['equipment' => $this->equipment->id])
                ->icon('bs.eye'),
            Button::make(__('Delete'))
                ->icon('bs.trash3')
                ->confirm('Êtes-vous sûr de vouloir supprimer le matériel <b>' . $this->equipment->serial_number . '</b>')
                ->method('delete', [
                    'equipment' => $this->equipment->id,
                    'redirect' => true,
                ]),
        ];
    }

    public function save(StoreRequest $request)
    {
        try {
            $equipment = DB::transaction(function () use ($request) {
                $data = $request->validated();
                $data['updated_by_id'] = Auth::user()->id;
                $technicians = isset($data['technicians']) && !empty($data['technicians']) ? $data['technicians'] : [];
                $inputDischarges = isset($data['input_discharge']) && !empty($data['input_discharge']) ? $data['input_discharge'] : [];
                unset($data['technicians'], $data['input_discharge']);
                $this->equipment->update($data);

                // Attach input discharges
                if (!empty($inputDischarges)) {
                    $this->equipment->inputDischarge()->sync($inputDischarges);
                }

                // Sync technicians
                if (!empty($technicians)) {
                    $this->equipment->technicians()->sync($technicians);
                }

                // Create a new state
                $actualState = $this->equipment->state()->first();
                $stateVal = count($technicians) > 0 || !anyRole('technicien') ? EquipmentStateEnum::ASSIGNED : EquipmentStateEnum::PENDING_ASSIGNATION;
                if ($actualState->state !== $stateVal) {
                    $this->equipment->states()->update(['is_current' => false]);
                    $this->equipment->states()->create([
                        'state' => $stateVal,
                        'is_current' => true,
                        'user_id' => Auth::user()->id
                    ]);
                }

                return $this->equipment;
            });
            if ($equipment) {
                Toast::success("Matériel mis à jour avec succès");
            } else {
                Toast::error("Une erreur est survenu lors de la création de la catégorie");
            }
        } catch (\Throwable $th) {
            Toast::success($th->getMessage());
        }
    }
}
