<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\PurchaseAttachment;
use App\Models\InvoiceHeader;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class AttachmentsPage extends Component
{
    use WithFileUploads, WithPagination;

    public $invoiceHeaderId;
    public $attachments = [];
    public $newAttachments = [];
    public $orderName;
    public $onlyView = false;
    protected $listeners = ['saveAttachmentsEvent' => 'saveAttachments', "flashMessage" => 'flashMessage'];

    protected $rules = [
        'attachments.*' => 'file|max:2048',
    ];

    public function mount($invoiceHeaderId = null)
    {
        try {
            $this->invoiceHeaderId = $invoiceHeaderId;
            if ($invoiceHeaderId) {
                $this->loadOrderName();
            }
        } catch (Exception $e) {
            Log::error('Error in mount method: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al cargar los datos.');
        }
    }
    
    public function updatedNewAttachments()
    {
        // Agregar los nuevos archivos a la colección existente
        if ($this->newAttachments) {
            foreach ($this->newAttachments as $newFile) {
                $this->attachments[] = $newFile;
            }
            // Limpiar la variable temporal
            $this->newAttachments = [];
        }
    }

    public function loadOrderName()
    {
        try {
            $order = InvoiceHeader::find($this->invoiceHeaderId);
            if ($order) {
                $this->orderName = $order->order_name;
            } else {
                session()->flash('error', 'Orden de compra no encontrada.');
            }
        } catch (Exception $e) {
            Log::error('Error in loadOrderName method: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al cargar el nombre de la orden.');
        }
    }
    public function download($id)
    {
        $attachment = PurchaseAttachment::find($id);
        if ($attachment && Storage::disk('public')->exists($attachment->path)) {
            return response()->download(storage_path('app/public/' . $attachment->path), $attachment->filename);
        } else {
            abort(404, 'Archivo no encontrado');
        }
    }

    public function flashMessage($type, $message)
    {
        session()->flash($type, $message);
    }

    public function removeTempFile($index)
    {
        try {
            // Eliminar el archivo de la lista temporal
            array_splice($this->attachments, $index, 1);
        } catch (Exception $e) {
            Log::error('Error in removeTempFile method: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al eliminar el archivo temporal.');
        }
    }

    public function updatedAttachments()
    {
        $hasFiles = !empty($this->attachments);

        $this->dispatch('attachmentsStatusUpdated', $hasFiles);
    }

    public function saveAttachments($invoiceHeaderId = null)
    {
        try {

            if ($invoiceHeaderId) {
                $this->invoiceHeaderId = $invoiceHeaderId;
            }

            if (empty($this->attachments)) {
                $this->addError('attachments', 'Por favor seleccione al menos un archivo para subir.');
                $this->dispatch('attachmentsStatus', false);
                return;
            }

            if (count($this->attachments) > 3) {
                $this->addError('attachments', 'Por favor seleccione hasta 3 archivos.');
                $this->dispatch('attachmentsStatus', false);
                return;
            }

            $this->validate();

            foreach ($this->attachments as $attachment) {
                $path = $attachment->store('attachments', 'public');

                PurchaseAttachment::create([
                    'invoice_header_id' => $this->invoiceHeaderId,
                    'filename' => $attachment->getClientOriginalName(),
                    'path' => $path,
                ]);
            }

            $this->attachments = [];  // Limpiar la lista de archivos después de guardar
            $this->dispatch('attachmentsStatus', true);
            session()->flash('message', 'Adjuntos subidos exitosamente.');
        } catch (Exception $e) {
            Log::error('Error in saveAttachments method: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());  // Detalles de la ubicación
            Log::error('Trace: ' . $e->getTraceAsString());
            $this->addError('attachments', 'Ocurrió un error al subir los adjuntos. Reinicie la página e intente nuevamente');
        }
    }

    public function deleteAttachment($id)
    {
        try {
            $this->dispatch(
                'alertConfirmation',
                id: $id,
                type: 'warning',
                title: 'Eliminar adjunto',
                message: "¿Estás seguro de eliminar este adjunto?",
                emit: 'destroy-attachment',
            );
        } catch (Exception $e) {
            Log::error('Error in deleteAttachment method: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al preparar la eliminación del adjunto.');
        }
    }

    #[On('destroy-attachment')]
    public function destroyAttachment($id)
    {
        try {
            Log::info('Attempting to delete attachment with ID: ' . $id);
            $attachment = PurchaseAttachment::find($id);
            if ($attachment) {
                Storage::disk('public')->delete($attachment->path);
                $attachment->delete();
                session()->flash('message', 'Adjunto eliminado exitosamente.');
            } else {
                session()->flash('error', 'No se encontró el adjunto.');
            }
        } catch (Exception $e) {
            Log::error('Error in destroyAttachment method: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al eliminar el adjunto.');
        }
    }

    #[Layout('layouts.app')]
    #[Title('Adjuntos de ordenes de compra')]
    public function render()
    {
        try {
            $existingAttachments = PurchaseAttachment::where('invoice_header_id', $this->invoiceHeaderId)
                ->paginate(5);

            return view('livewire.attachments-page', [
                'existingAttachments' => $existingAttachments,
            ]);
        } catch (Exception $e) {
            Log::error('Error in render method: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al cargar los adjuntos.');
            return view('livewire.attachments-page', [
                'existingAttachments' => collect([]),
            ]);
        }
    }
}
