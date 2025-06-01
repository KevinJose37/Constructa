<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\BudgetHeader;
use App\Models\Project;
use App\Models\ItemsBudgets;
use App\Models\Chapter;
use Illuminate\Support\Facades\DB;


class Budget extends Component
{
    #[Layout('layouts.app')]
    #[Title('Presupuesto')]

    public $budget;
    public $localizacion;
    public $modalCapitulo = [
        'numero_capitulo' => '',
        'nombre_capitulo' => '',
    ];
    public $modalItems = [];

    public $editCapitulo = null;
    public $editItems = [];

    protected $rules = [
        'modalCapitulo.numero_capitulo' => 'required|string',
        'modalCapitulo.nombre_capitulo' => 'required|string',
        'modalItems.*.numero_item' => 'required|string',
        'modalItems.*.descripcion' => 'required|string',
        'modalItems.*.und' => 'required|string',
        'modalItems.*.cantidad' => 'required|numeric|min:0',
        'modalItems.*.vr_unit' => 'required|numeric|min:0',
    ];

    public function mount($id_presupuesto)
    {
        $this->budget = BudgetHeader::where('id_proyecto', $id_presupuesto)->first();

        if (!$this->budget) {
            $project = Project::findOrFail($id_presupuesto);
            $this->budget = BudgetHeader::create([
                'id_proyecto' => $project->id,
                'descripcion_obra' => $project->project_description,
                'localizacion' => 'Por definir',
                'fecha' => $project->project_start_date,
            ]);
        }

        $this->localizacion = $this->budget->localizacion;
    }

    public function updateLocalizacion()
{
    // Validar el campo de localización
    $this->validate([
        'localizacion' => 'required|string|max:255',
    ]);

    // Actualizar la localización en la base de datos
    $this->budget->update([
        'localizacion' => $this->localizacion,
    ]);

    // Cerrar el modal y mostrar un mensaje de éxito
    $this->dispatch('close-modal');
    session()->flash('message', 'Localización actualizada correctamente.');
}

    public function addItem()
    {
        $newItem = [
            'numero_item' => '',
            'descripcion' => '',
            'und' => '',
            'cantidad' => '',
            'vr_unit' => '',
        ];

        if (!is_array($this->modalItems)) {
            $this->modalItems = [];
        }

        $this->modalItems[] = $newItem;
    }

    public function removeItem($index)
    {
        unset($this->modalItems[$index]);
        $this->modalItems = array_values($this->modalItems);
    }
    public function saveChapter()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $chapter = Chapter::create([
                'id_presupuesto' => $this->budget->id_presupuesto,
                'numero_capitulo' => $this->modalCapitulo['numero_capitulo'],
                'nombre_capitulo' => $this->modalCapitulo['nombre_capitulo'],
            ]);

            foreach ($this->modalItems as $item) {
                ItemsBudgets::create([
                    'id_capitulo' => $chapter->id_capitulo,
                    'numero_item' => $item['numero_item'],
                    'descripcion' => $item['descripcion'],
                    'und' => $item['und'],
                    'cantidad' => $item['cantidad'],
                    'vr_unit' => $item['vr_unit'],
                    'vr_total' => round($item['cantidad'] * $item['vr_unit']),
                ]);
            }

            DB::commit();

            $this->modalCapitulo = [
                'numero_capitulo' => '',
                'nombre_capitulo' => '',
            ];
            $this->modalItems = [];

            $this->dispatch('close-modal');
            session()->flash('message', 'Capítulo e ítems guardados correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al guardar capítulo e ítems.');
        }
    }

    public function deleteChapter($id_capitulo)
{
    try {
        DB::beginTransaction();

        // Eliminar los ítems asociados al capítulo
        ItemsBudgets::where('id_capitulo', $id_capitulo)->delete();

        // Eliminar el capítulo
        Chapter::where('id_capitulo', $id_capitulo)->delete();

        DB::commit();

        session()->flash('message', 'Capítulo y ítems eliminados correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        session()->flash('error', 'Error al eliminar el capítulo y sus ítems.');
    }
}

    public function render()
{
    return view('livewire.budget', [
        'budget' => $this->budget,
        'capitulos' => Chapter::where('id_presupuesto', $this->budget->id_presupuesto)
            ->orderBy('numero_capitulo', 'asc') // Ordenar por número de capítulo de menor a mayor
            ->with('items') // Cargar los ítems asociados
            ->get()
    ]);
}
}