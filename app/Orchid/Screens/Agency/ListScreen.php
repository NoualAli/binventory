<?php

namespace App\Orchid\Screens\Agency;

use App\Http\Requests\Agency\StoreRequest;
use App\Http\Requests\Agency\UpdateRequest;
use App\Models\Agency;
use App\Orchid\Layouts\Agency\ListTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
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
        return [
            'agencies' => Agency::filters()->paginate(10)
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Agences';
    }

    /**
     * The description of the screen displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Liste des agences';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Ajouter')
                ->modal('createAgencyModal')
                ->method('create')
                ->icon('plus'),
        ];
    }

    /**
     * Loads agency data when opening the modal window.
     *
     * @return array
     */
    public function loadAgencyOnOpenModal(Agency $agency): iterable
    {
        return [
            'agency' => $agency,
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
            ListTable::class,
            $this->createModal(),
            $this->editModal(),
        ];
    }

    public function create(StoreRequest $request): void
    {
        try {
            $agency = Agency::create($request->validated());
            if ($agency->wasRecentlyCreated) {
                Toast::success("Agence créer avec succès");
            } else {
                Toast::error("Une erreur est survenu lors de la création de l'agence");
            }
        } catch (\Throwable $th) {
            Toast::success($th->getMessage());
        }
    }

    public function edit(UpdateRequest $request, Agency $agency): void
    {
        try {
            $result = $agency->update($request->validated());
            if ($result) {
                Toast::success("Agence mise à jour avec succès");
            } else {
                Toast::error("Une erreur est survenu lors de la mise à jour de l'agence");
            }
        } catch (\Throwable $th) {
            Toast::success($th->getMessage());
        }
    }

    public function delete(Agency $agency)
    {
        try {
            if ($agency->delete()) {
                Toast::success("Agence supprimer avec succès");
            } else {
                Toast::error("Une erreur est survenu lors de la suppression de l'agence");
            }
        } catch (\Throwable $th) {
            Toast::success($th->getMessage());
        }
    }

    private function createModal()
    {
        return Layout::modal('createAgencyModal', Layout::rows([
            Input::make('name')
                ->title('Nom')
                ->required()
                ->placeholder('Entrer le nom de l\'agence')
                ->help('Le nom de l\'agence à créer.'),
            Input::make('code')
                ->title('Code')
                ->required()
                ->placeholder('Entrer le code de l\'agence')
                ->help('Le code de l\'agence à créer.'),
        ]))
            ->method("create")
            ->title('Ajouter une agence')
            ->withoutCloseButton()
            ->applyButton('Ajouter');
    }

    private function editModal()
    {
        $agency = null;
        if (request('agency')) {
            $agency = Agency::findOrFail(request('agency'));
        }

        return Layout::modal('updateAgencyModal', Layout::rows([
            Input::make('name')
                ->title('Nom')
                ->required()
                ->placeholder('Entrer le nom de l\'agence')
                ->value($agency?->name)
                ->help('Le nom de l\'agence à créer.'),
            Input::make('code')
                ->title('Code')
                ->required()
                ->placeholder('Entrer le code de l\'agence')
                ->value($agency?->code)
                ->help('Le code de l\'agence à créer.'),
        ]))
            ->method("edit")
            ->title('Modifier l\'agence')
            ->withoutCloseButton()
            ->applyButton('Enregistrer')
            ->deferred('loadAgencyOnOpenModal');
    }
}
