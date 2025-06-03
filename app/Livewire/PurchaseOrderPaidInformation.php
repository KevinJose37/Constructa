<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InvoiceHeader;
use App\Models\PurchaseOrderState;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\PurchaseOrderServices;
use App\Livewire\Forms\PurchaseOrderPaidInformationForm;

class PurchaseOrderPaidInformation extends Component
{
    public InvoiceHeader $order;
    public PurchaseOrderPaidInformationForm $form;
    public PurchaseOrderState $orderState;

    public function mount(InvoiceHeader $order)
    {
        $this->order = $order;
        $this->orderState = $order->getOrCreateState();
        //        dd($this->orderState, $this->orderState->status_label);
        $this->loadFormData();
        $this->calculateAndUpdateState();
    }

    /**
     * Carga los datos existentes al formulario
     */
    private function loadFormData()
    {
        if ($this->order->paidInformation) {
            $paid = $this->order->paidInformation;
            $this->form->fill([
                'name' => $paid->payment_person,
                'method' => $paid->payment_method,
                'how' => $paid->payment_how,
                'date' => $paid->payment_date,
                'approved_tech' => $paid->approved_tech,
                'approved_account' => $paid->approved_account,
            ]);
        }
    }

    /**
     * Calcula y actualiza el estado en la base de datos
     */
    private function calculateAndUpdateState()
    {
        $newStatus = $this->determineCurrentStatus();

        // Solo actualizar si el estado cambió
        if ($this->orderState->status !== $newStatus) {
            $this->orderState->updateStatus(
                $newStatus,
                "Estado actualizado automáticamente por el sistema",
                [
                    'trigger' => 'status_calculation',
                    'previous_paid_info' => $this->order->paidInformation?->toArray(),
                ]
            );
        }
    }

    /**
     * Determina el estado actual basado en la lógica de negocio
     */
    private function determineCurrentStatus(): string
    {
        $paid = $this->order->paidInformation;

        if (!$paid) {
            return PurchaseOrderState::STATUS_SIN_PROCESAR;
        }

        // Estados basados en aprobaciones
        if ($this->hasPartialApproval($paid)) {
            return PurchaseOrderState::STATUS_PENDIENTE;
        }

        if ($this->hasFullApproval($paid)) {
            return $this->hasPaymentInfo($paid)
                ? PurchaseOrderState::STATUS_PAGADO
                : PurchaseOrderState::STATUS_POR_CONFIRMAR;
        }

        return PurchaseOrderState::STATUS_SIN_PROCESAR;
    }

    /**
     * Verifica si el usuario puede interactuar con el componente
     */
    private function userCanInteract(): bool
    {
        $user = Auth::user();

        // Si no tiene ningún permiso, no puede hacer click
        if (!$user->can('approved_tech.purchase') && !$user->can('approved_account.purchase')) {
            return false;
        }

        // Lógica específica basada en el estado actual
        $paid = $this->order->paidInformation;
        if ($this->orderState->status === PurchaseOrderState::STATUS_PENDIENTE && $paid) {
            // Para usuarios técnicos (no Residente ni Contador)
            if (!$user->hasRole('Residente') && !$user->hasRole('Contador')) {
                return !$paid->approved_tech; // Solo puede hacer click si no ha decidido
            }

            // Para Contador
            if ($user->hasRole('Contador')) {
                return !$paid->approved_account; // Solo puede hacer click si no ha decidido
            }
        }

        // Para estado "Por confirmar", solo quien puede marcar como pagado
        if ($this->orderState->status === PurchaseOrderState::STATUS_POR_CONFIRMAR) {
            return $user->can('paid.purchase');
        }

        return true;
    }

    /**
     * Verifica si hay aprobación parcial (solo una de las dos)
     */
    private function hasPartialApproval($paid): bool
    {
        return $paid->approved_tech xor $paid->approved_account;
    }

    /**
     * Verifica si hay aprobación completa
     */
    private function hasFullApproval($paid): bool
    {
        return $paid->approved_tech && $paid->approved_account;
    }

    /**
     * Verifica si tiene información de pago completa
     */
    private function hasPaymentInfo($paid): bool
    {
        return !empty($paid->payment_person) ||
            !empty($paid->payment_method) ||
            !empty($paid->payment_how) ||
            !empty($paid->payment_date);
    }

    /**
     * Aprueba la decisión técnica
     */
    public function approveTech(PurchaseOrderServices $purchaseService)
    {
        $this->authorize('approved_tech.purchase');

        if (Auth::user()->hasRole('Residente') || Auth::user()->hasRole('Contador')) {
            abort(403, 'No autorizado.');
        }

        $this->validate(['form.approved_tech' => 'required']);

        $approvedTech = (bool) $this->form->approved_tech;
        $purchaseService->updateTechDecision($approvedTech, $this->order->id);

        // Actualizar estado con trazabilidad
        $this->orderState->updateStatus(
            PurchaseOrderState::STATUS_PENDIENTE,
            "Decisión técnica aprobada",
            ['action' => 'tech_approval', 'approved' => $approvedTech]
        );

        $this->handleSuccessfulUpdate($purchaseService, 'Se actualizó correctamente la decisión técnica');
    }

    /**
     * Aprueba la decisión contable
     */
    public function approveAccount(PurchaseOrderServices $purchaseService)
    {
        $this->authorize('approved_account.purchase');

        if (!Auth::user()->hasRole('Contador')) {
            abort(403, 'No autorizado.');
        }

        $this->validate(['form.approved_account' => 'required']);

        $approvedAccount = (bool) $this->form->approved_account;
        $purchaseService->updateAccountDecision($approvedAccount, $this->order->id);

        // Actualizar estado con trazabilidad
        $this->orderState->updateStatus(
            PurchaseOrderState::STATUS_POR_CONFIRMAR,
            "Decisión contable aprobada",
            ['action' => 'account_approval', 'approved' => $approvedAccount]
        );

        $this->handleSuccessfulUpdate($purchaseService, 'Se actualizó correctamente la decisión contable');
    }

    /**
     * Guarda la información de pago
     */
    public function save(PurchaseOrderServices $purchaseService)
    {
        $this->form->validate();

        $data = [
            'payment_person' => $this->form->name,
            'payment_method' => $this->form->method,
            'payment_how' => $this->form->how,
            'payment_date' => $this->form->date,
        ];

        $response = $purchaseService->UpdateCreatePaid($data, $this->order->id);

        if (!is_array($response) && !isset($response['success'])) {
            // Actualizar estado a pagado
            $this->orderState->updateStatus(
                PurchaseOrderState::STATUS_PAGADO,
                "Información de pago registrada",
                ['payment_data' => $data]
            );

            $this->handleSuccessfulUpdate($purchaseService, 'Se asignó correctamente la información de pago');
            $this->form->reset(['name', 'method', 'how', 'date']);
            $this->redirect(route('purchaseorder.redirect', ['id' => $this->order->id]));
        } else {
            $this->dispatch(
                'alert',
                type: 'error',
                title: 'Órdenes de compra',
                message: 'Ocurrió un error al asignar la información de pago'
            );
        }
    }

    /**
     * Maneja actualizaciones exitosas
     */
    private function handleSuccessfulUpdate(PurchaseOrderServices $purchaseService, string $message)
    {
        $this->form->open = false;
        $this->mount($purchaseService->getById($this->order->id));
        $this->dispatch('purchaseRefresh')->to(QueryPurchaseOrder::class);
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Órdenes de compra',
            message: $message
        );
    }

    /**
     * Propiedades computadas para la vista
     */
    public function getCanShowTechDecisionProperty(): bool
    {
        return Auth::user()->can('approved_tech.purchase') &&
            $this->orderState->status !== PurchaseOrderState::STATUS_PAGADO &&
            !Auth::user()->hasRole('Residente') &&
            !Auth::user()->hasRole('Contador');
    }

    public function getCanShowAccountDecisionProperty(): bool
    {
        return Auth::user()->can('approved_account.purchase') &&
            Auth::user()->hasRole('Contador') &&
            $this->orderState->status !== PurchaseOrderState::STATUS_PAGADO;
    }

    public function getCanShowPaymentInfoProperty(): bool
    {
        return $this->orderState->status === PurchaseOrderState::STATUS_POR_CONFIRMAR ||
            $this->orderState->status === PurchaseOrderState::STATUS_PAGADO;
    }

    public function getButtonClassProperty(): string
    {
        return $this->orderState->status_class;
    }

    public function getButtonStatusProperty(): string
    {
        return $this->orderState->status_label;
    }

    public function getCanClickProperty(): bool
    {
        return $this->userCanInteract();
    }

    public function render()
    {
        return view('livewire.purchase-order-paid-information');
    }
}
