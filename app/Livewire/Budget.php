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

    protected $rules = [
        'modalCapitulo.numero_capitulo' => 'required|string',
        'modalCapitulo.nombre_capitulo' => 'required|string',
        'modalItems.*.descripcion' => 'required|string',
        'modalItems.*.und' => 'required|string',
        'modalItems.*.cantidad' => 'required|numeric|min:0',
        'modalItems.*.vr_unit' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'modalCapitulo.numero_capitulo.required' => 'El número de capítulo es requerido',
        'modalCapitulo.nombre_capitulo.required' => 'El nombre del capítulo es requerido',
        'modalItems.*.descripcion.required' => 'La descripción del ítem es requerida',
        'modalItems.*.und.required' => 'La unidad es requerida',
        'modalItems.*.cantidad.required' => 'La cantidad es requerida',
        'modalItems.*.cantidad.numeric' => 'La cantidad debe ser un número',
        'modalItems.*.vr_unit.required' => 'El valor unitario es requerido',
        'modalItems.*.vr_unit.numeric' => 'El valor unitario debe ser un número',
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

    public function addItem()
    {
        $newItem = [
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
        if (isset($this->modalItems[$index])) {
            unset($this->modalItems[$index]);
            $this->modalItems = array_values($this->modalItems);
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updateLocalizacion()
    {
        $this->validate([
            'localizacion' => 'required|string|max:255',
        ]);

        $this->budget->update([
            'localizacion' => $this->localizacion,
        ]);

        $this->dispatch('close-modal');
        session()->flash('message', 'Localización actualizada correctamente.');
    }

    public function saveChapter()
{
    // Validar datos
    $this->validate();

    try {

        // Iniciar transacción
        DB::beginTransaction();

        // Guardar capítulo
        $chapter = Chapter::create([
            'id_presupuesto' => $this->budget->id_presupuesto,
            'numero_capitulo' => $this->modalCapitulo['numero_capitulo'],
            'nombre_capitulo' => $this->modalCapitulo['nombre_capitulo'],
        ]);


        // Guardar ítems
        foreach ($this->modalItems as $item) {
            $itemBudget = ItemsBudgets::create([
                'id_capitulo' => $chapter->id_capitulo,
                'descripcion' => $item['descripcion'],
                'und' => $item['und'],
                'cantidad' => $item['cantidad'],
                'vr_unit' => $item['vr_unit'],
                'vr_total' => $item['cantidad'] * $item['vr_unit'],
            ]);

        }

        // Confirmar transacción
        DB::commit();

        // Limpiar formulario
        $this->modalCapitulo = [
            'numero_capitulo' => '',
            'nombre_capitulo' => '',
        ];
        $this->modalItems = [];

        // Cerrar modal y mostrar mensaje de éxito
        $this->dispatch('close-modal');
        session()->flash('message', 'Capítulo e ítems guardados correctamente.');

    } catch (\Exception $e) {
        // Revertir transacción en caso de error
        DB::rollBack();

        // Mostrar mensaje de error al usuario
        session()->flash('error', 'Error al guardar capítulo e ítems. Por favor, inténtalo de nuevo.');
    }
}


    public function render()
    {
        return view('livewire.budget', [
            'budget' => $this->budget,
            'capitulos' => Chapter::where('id_presupuesto', $this->budget->id_presupuesto)
                ->with('items')
                ->get()
        ]);
    }
}