<?php

namespace App\Orchid\Screens\Category;

use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;
use App\Orchid\Layouts\Category\ListTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CategoryListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        abort_if(!can('platform.categories.show'), 401);
        return [
            'categories' => Category::filters()->paginate(10)
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Catégories';
    }


    /**
     * The description of the screen displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Liste des catégories de matériel';
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
            ->canSee(can('platform.categories.create'))
                ->modal('createCategoryModal')
                ->method('create')
                ->icon('plus'),
        ];
    }

    /**
     * Loads category data when opening the modal window.
     *
     * @return array
     */
    public function loadCategoryOnOpenModal(Category $category): iterable
    {
        return [
            'category' => $category,
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
            $category = Category::create($request->validated());
            if ($category->wasRecentlyCreated) {
                Toast::success("Catégorie créer avec succès");
            } else {
                Toast::error("Une erreur est survenu lors de la création de la catégorie");
            }
        } catch (\Throwable $th) {
            Toast::success($th->getMessage());
        }
    }

    public function edit(UpdateRequest $request, Category $category): void
    {
        try {
            $result = $category->update($request->validated());
            if ($result) {
                Toast::success("Catégorie mise à jour avec succès");
            } else {
                Toast::error("Une erreur est survenu lors de la mise à jour de la catégorie");
            }
        } catch (\Throwable $th) {
            Toast::success($th->getMessage());
        }
    }

    public function delete(Category $category)
    {
        try {
            if ($category->delete()) {
                Toast::success("Catégorie supprimer avec succès");
            } else {
                Toast::error("Une erreur est survenu lors de la suppression de la catégorie");
            }
        } catch (\Throwable $th) {
            Toast::success($th->getMessage());
        }
    }

    private function createModal()
    {
        return Layout::modal('createCategoryModal', Layout::rows([
            Input::make('name')
                ->title('Nom')
                ->required()
                ->placeholder('Entrer le nom de la catégorie')
                ->help('Le nom de la catégorie à créer.'),
        ]))
            ->method("create")
            ->title('Ajouter une catégorie')
            ->withoutCloseButton()
            ->applyButton('Ajouter');
    }

    private function editModal()
    {
        $category = null;
        if (request('category')) {
            $category = Category::findOrFail(request('category'));
        }

        return Layout::modal('updateCategoryModal', Layout::rows([
            Input::make('name')
                ->title('Nom')
                ->value($category?->name)
                ->required()
                ->placeholder('Entrer le nom de la catégorie')
        ]))
            ->method("edit")
            ->title('Modifier la catégorie')
            ->withoutCloseButton()
            ->applyButton('Enregistrer')
            ->deferred('loadCategoryOnOpenModal');
    }
}
