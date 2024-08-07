<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PurchaseAttachment;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class AttachmentsPage extends Component
{
    use WithFileUploads;

    public $invoiceHeaderId;
    public $attachments = [];
    public $existingAttachments = [];

    protected $rules = [
        'attachments.*' => 'file|mimes:jpg,png,pdf|max:2048',
    ];

    public function mount($invoiceHeaderId)
    {
        $this->invoiceHeaderId = $invoiceHeaderId;
        $this->loadExistingAttachments();
    }

    public function loadExistingAttachments()
    {
        $this->existingAttachments = PurchaseAttachment::where('invoice_header_id', $this->invoiceHeaderId)->get();
    }

    public function saveAttachments()
    {
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
        $this->loadExistingAttachments();
        session()->flash('message', 'Attachments uploaded successfully.');
    }

    public function deleteAttachment($id)
    {
        $attachment = PurchaseAttachment::find($id);
        if ($attachment) {
            \Storage::disk('public')->delete($attachment->path);
            $attachment->delete();
            $this->loadExistingAttachments();
        }
    }

    #[Layout('layouts.app')]
    #[Title('Adjuntos de ordenes de compra')]
    public function render()
    {
        return view('livewire.attachments-page');
    }
}
