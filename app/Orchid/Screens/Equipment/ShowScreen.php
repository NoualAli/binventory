<?php

namespace App\Orchid\Screens\Equipment;

use App\Models\Equipment;
use App\Orchid\Layouts\Equipment\ShowScreenLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ShowScreen extends Screen
{
    private $equipment;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Equipment $equipment): iterable
    {
        abort_if(!can('platform.equipments.show'), 401);
        $this->equipment = $equipment;
        // dd($this->equipment);
        // dd(Auth::user()->inRole('Technicien'));
        if (Auth::user()->inRole('technician')) {
            $equipment->whereRelation('technicians', 'user_id', Auth::user()->id);
        }
        $this->equipment->load(["agency", "category", 'creator', 'technicians', 'state']);
        return ['equipment' => $equipment];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Détail';
    }

    public function description(): ?string
    {
        return 'Détail du matériel SN ' . $this->equipment->serial_number;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Edit')
                ->route('platform.equipments.edit', ['equipment' => $this->equipment->id])
                ->icon('bs.pencil'),
                Button::make(__('Delete'))
                ->canSee(can('platform.equipments.delete'))
                    ->icon('bs.trash3')
                    ->confirm('Êtes-vous sûr de vouloir supprimer le matériel <b>' . $this->equipment->serial_number . '</b>')
                    ->method('delete', [
                        'equipment' => $this->equipment->id,
                        'redirect' => false,
                    ]),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $inputDischarge = $this->equipment->inputDischarge()->first();
        $technicians = implode(', ', $this->equipment->technicians->pluck('name')->toArray());
        return [
            Layout::columns([
                Layout::block([
                    Layout::legend('equipment', [
                        Sight::make('id', "#"),
                        Sight::make('serial_number', "N° de série"),
                        Sight::make('description', "Description"),
                        Sight::make('motif')->render(function ($item) {
                            $motif = '';
                            $installAd = $item->install_ad;
                            $repair = $item->repair;
                            $separator = $installAd && $repair ? ' et ' : '';
                            if ($installAd) {
                                $motif .= "Installtion AD";
                            }
                            $motif .= $separator;
                            if ($repair) {
                                $motif .= "Réparation";
                            }
                            return $motif;
                        }),
                        Sight::make('state', "Etat")->render(fn($item) => $item->state()->first()?->state),
                        Sight::make('category', "Catégorie")->render(fn($item) => $item->category?->name),
                        Sight::make('agency', "Agence")->render(fn($item) => $item->agency?->fullname),
                        Sight::make('creator', "Créateur")->render(fn($item) => $item->creator?->name),
                        Sight::make('test', "Assigné à")->render(fn($item) => $technicians),
                        Sight::make('created_at', "Date de création")->usingComponent(DateTimeSplit::class),
                        Sight::make('entered_at', "Date d'entéer")->usingComponent(DateTimeSplit::class),
                        Sight::make('updated_at', "Date de mise à jours")->usingComponent(DateTimeSplit::class),
                        Sight::make('deleted_at', "Date de suppression")->usingComponent(DateTimeSplit::class)->canSee(boolval($this->equipment->deleted_at)),
                    ])->title("Informations de base")
                ])->vertical(),
                Layout::block([
                    Layout::legend('equipment', [
                        Sight::make('state', "Statut actuel")->render(fn($item) => $item->state()->first()?->state),
                        Sight::make('Historique des statuts')->render(fn($item) => $this->renderStatusHistory($item)),
                    ])->title("Etats"),
                    Layout::legend('equipment', [
                        Sight::make('input_dischare', 'Décharge d\'entrée')->render(function ($item) use ($inputDischarge) {
                            if ($inputDischarge) {
                                return Link::make('voir')->tabindex()->href($inputDischarge->url());
                            }
                            return Group::make([
                                Upload::make('input_discharge')->value($this->equipment->inputDischarge()->first())
                                    ->groups('documents/input_discharges')
                                    ->acceptedFiles('image/*,application/pdf')
                                    ->parallelUploads(2),
                                Button::make(__('Save'))
                                    ->type(Color::BASIC())
                                    ->icon('bs.check-circle')
                                    ->method('upload', ['type' => 'input_discharge'])
                            ]);
                        }),
                    ])->title("Décharges")
                ])->vertical(),
            ]),
        ];
    }

    /**
     * Render the status history table.
     *
     * @param $item
     * @return string
     */
    protected function renderStatusHistory($item): string
    {
        $statuses = $item->states; // Assuming $item has a relationship called 'statuses'

        if ($statuses->isEmpty()) {
            return 'Aucun historique de statut disponible.';
        }
        $html = '<table class="table w-100">';
        $html .= '<thead>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Créer par</th>
                    </tr>
                </thead>';
        $html .= '<tbody>';

        foreach ($statuses as $status) {
            // dd($status->creator);
            $html .= '<tr>';
            $html .= '<td>' . $status->created_at->format('Y-m-d H:i') . '</td>'; // Adjust format as needed
            $html .= '<td>' . $status->state . '</td>'; // Assuming 'state_name' is the field you want to display
            $html .= '<td>' . $status->creator?->name . '</td>'; // Assuming 'state_name' is the field you want to display
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }

    public function upload(Request $request)
    {
        $outputDischarges = request('output_discharge') && is_array(request('output_discharge')) ? request('output_discharge') : [];
        $inputDischarges = request('input_discharge') && is_array(request('input_discharge')) ? request('input_discharge') : [];
        dd($this->equipment, request()->get('equipment'));
        // Attach input discharges
        if (!empty($inputDischarges)) {
            $this->equipment->inputDischarge()->attach($inputDischarges);
        }
        // Attach output discharges
        if (!empty($outputDischarges)) {
            $this->equipment->outputDischarge()->attach($outputDischarges);
        }

        Toast::success("Document mis à jour avec succès");
    }
}
