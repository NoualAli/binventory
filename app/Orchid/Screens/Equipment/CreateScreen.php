<?php

namespace App\Orchid\Screens\Equipment;

use App\Enums\EquipmentStateEnum;
use App\Http\Requests\Equipment\StoreRequest;
use App\Models\Equipment;
use App\Orchid\Layouts\Equipment\CreateFormLayout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class CreateScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        abort_if(!can('platform.equipments.create'), 401);
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Ajouter un matériel';
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            CreateFormLayout::class
        ];
    }

    public function save(StoreRequest $request)
    {
        try {
            $equipment = DB::transaction(function () use ($request) {
                $data = $request->validated();
                $data['created_by_id'] = Auth::user()->id;
                $technicians = Auth::user()->inRole('technicien') ? [Auth::user()->id] : $data['technicians'];
                $inputDischarges = isset($data['input_discharge']) && !empty($data['input_discharge']) ? $data['input_discharge'] : [];
                unset($data['technicians'], $data['input_discharge']);
                $equipment = Equipment::create($data);

                // Attach input discharges
                if (!empty($inputDischarges)) {
                    foreach ($inputDischarges as $id) {
                        $equipment->attachments()->create($id);
                    }
                }

                // Attach technicians
                $equipment->technicians()->attach($technicians, ['created_by_id' => Auth::user()->id]);

                // Create a new state
                $stateVal = count($technicians) > 0 || !anyRole('technicien') ? EquipmentStateEnum::PENDING_ASSIGNATION : EquipmentStateEnum::ASSIGNED;
                $equipment->states()->create([
                    'state' => $stateVal,
                    'is_current' => true,
                    'user_id' => $data['created_by_id']
                ]);

                return $equipment;
            });
            if ($equipment) {
                Toast::success("Matériel ajouter avec succès");
            } else {
                Toast::error("Une erreur est survenu lors de la création de la catégorie");
            }
        } catch (\Throwable $th) {
            Toast::success($th->getMessage());
        }
    }
}
