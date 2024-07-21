<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\Helpers;
use Livewire\Attributes\On;
use App\Models\InvoiceDetail;
use App\Models\InvoiceHeader;
use App\Models\PaymentMethod;
use App\Services\ItemService;
use App\Models\PaymentSupport;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Forms\PurchaseOrderForm;
use App\Services\ProjectServices;

class CreatePurchaseOrder extends Component
{
    public $selectedItems = [];
    public $totalPurchaseIva, $totalPurchase, $totalIVA, $retencion, $totalPay;
    public PurchaseOrderForm $formPurchase;
    public $currentDate, $order_name, $contractor_name, $contractor_nit, $responsible_name, $company_name,
        $company_nit, $phone, $material_destination, $payment_method_id, $bank_name,
        $account_type, $account_number, $support_type_id, $lastInvoiceId, $formattedDate, $project_id, $invoiceHeader, $general_observations, $generalObservations;

    public function mount($id, ProjectServices $projectServices)
    {
        $this->currentDate = now()->format('y/m/d');
        $this->project_id = $id;
        $this->responsible_name = Auth::user()->name;
        $currentProject = $projectServices->getById($this->project_id);

        if (!$currentProject || is_null($currentProject)) {
            $this->redirect('/proyectos');
            return;
        }

        $this->contractor_name = $currentProject->contratista;
        $this->contractor_nit = $currentProject->nit;

        // Obtener el último ID de la orden de compra para el proyecto específico
        $this->lastInvoiceId = InvoiceHeader::where('project_id', $this->project_id)->max('id');
    }

    #[Layout('layouts.app')]
    #[Title('Crear orden de compra')]
    #[On('itemRefresh')]
    public function render(ItemService $itemService)
    {
        $user = Auth::user();
        if (!$user->can('store.purchase')) {
            $this->redirect('/proyectos');
            return;
        }

        $paymentMethods = PaymentMethod::all();
        $paymentSupport = PaymentSupport::all();
        return view('livewire.create-purchase-order', compact('paymentMethods', 'paymentSupport'));
    }

    protected function calculateTotal()
    {
        $this->totalPurchaseIva = 0;
        $tempSum = 0;
        foreach ($this->selectedItems as $item) {
            $tempSum += $this->clearFormat($item["totalPriceIva"]);
        }
        $this->totalPurchaseIva = $tempSum;
    }

    protected function updateTotals()
    {
        $this->totalPurchase = 0;
        $this->totalIVA = 0;
        $this->totalPurchaseIva = 0;

        foreach ($this->selectedItems as $item) {
            $price = floatval($this->clearFormat($item['totalPrice']));
            $iva = floatval($this->clearFormat($item['iva']));
            $totalPriceIva = floatval($this->clearFormat($item['totalPriceIva']));

            $this->totalPurchase += $price;
            $this->totalIVA += $iva;
            $this->totalPurchaseIva += $totalPriceIva;
        }

        // Calcula la retención (2.5% del total incluido IVA)
        $this->retencion = $this->totalPurchaseIva * 0.025;

        // Total a pagar es total con IVA menos la retención
        $this->totalPay = $this->totalPurchaseIva - $this->retencion;

        // Formatear las cantidades monetarias si es necesario
        $this->formatCurrencyValues();
    }

    protected function formatCurrencyValues()
    {
        $this->totalPurchase = $this->formatCurrency($this->totalPurchase);
        $this->totalIVA = $this->formatCurrency($this->totalIVA);
        $this->totalPurchaseIva = $this->formatCurrency($this->totalPurchaseIva);
        $this->retencion = $this->formatCurrency($this->retencion);
        $this->totalPay = $this->formatCurrency($this->totalPay);
    }

    #[On('storeItem')]
    public function store(ItemService $itemService, $idItem, $unitPrice, $quantityItem, $iva)
    {
        if ($idItem === null || empty($idItem)) {
            $this->dispatch('alert', type: 'error', title: 'Órdenes de compra', message: 'Ingrese un valor válido');
            return;
        }


        $currentItem = $itemService->getById($idItem)->toArray();
        if ($currentItem === null) {
            $this->dispatch('alert', type: 'error', title: 'Órdenes de compra', message: 'No se encontró información del item actual');
            return;
        }

        $existingItemIndex = false;
        foreach ($this->selectedItems as $index => $item) {
            if ($item['id'] == $idItem && $item['price'] == $unitPrice && $item['ivaProduct'] == $iva) {
                $existingItemIndex = $index;
                break;
            }
        }

        if ($existingItemIndex !== false) {
            $this->selectedItems[$existingItemIndex]["quantity"] += $quantityItem;
            $this->selectedItems[$existingItemIndex]["totalPrice"] = $this->clearFormat($this->selectedItems[$existingItemIndex]["price"]) * $this->selectedItems[$existingItemIndex]["quantity"];
            $this->selectedItems[$existingItemIndex]["iva"] = $this->formatCurrency(Helpers::calculateIva($this->clearFormat($this->selectedItems[$existingItemIndex]["price"]), $iva));
            $this->selectedItems[$existingItemIndex]["ivaProduct"] = $iva;
            $this->selectedItems[$existingItemIndex]["priceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($this->clearFormat($this->selectedItems[$existingItemIndex]["price"]), $iva));
            $this->selectedItems[$existingItemIndex]["totalPriceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($this->selectedItems[$existingItemIndex]["totalPrice"], $iva));
        } else {
            $currentItem["quantity"] = $quantityItem;
            $currentItem["price"] = $unitPrice;
            $currentItem["totalPrice"] = $this->formatCurrency($this->clearFormat($unitPrice) * $currentItem["quantity"]);
            $currentItem["iva"] = $this->formatCurrency(Helpers::calculateIva($this->clearFormat($unitPrice), $iva));
            $currentItem["ivaProduct"] = $iva;
            $currentItem["priceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($this->clearFormat($unitPrice), $iva));
            $currentItem["totalPriceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($currentItem["totalPrice"], $iva));
            array_push($this->selectedItems, $currentItem);
        }

        $this->calculateTotal();
        $this->updateTotals();
    }

    #[On('destroy-item')]
    public function destroy($id, $secondparameters, ItemService $itemService)
    {
        $currentItem = $itemService->getById($id)->toArray();
        if ($currentItem === null) {
            $this->dispatch('alert', type: 'error', title: 'Órdenes de compra', message: 'No se encontró información del item el cuál se desea eliminar');
            return;
        }

        $existingItemIndex = $secondparameters;

        if ($existingItemIndex !== false) {
            unset($this->selectedItems[$existingItemIndex]);
            $this->selectedItems = array_values($this->selectedItems);
            // Recalculamos para evitar errores
            $this->calculateTotal();
        } else {
            $this->dispatch('alert', type: 'error', title: 'Órdenes de compra', message: 'Ocurrió un error encontrando el índice del elemento');
            return;
        }

        if (count($this->selectedItems) === 0) {
            $this->totalPurchaseIva = 0;
        }

        $this->totalPurchaseIva = $this->formatCurrency($this->totalPurchaseIva);
        $this->updateTotals();
    }

    public function destroyAlertPurchase($id, $name, $index)
    {
        $this->dispatch(
            'alertConfirmation',
            id: $id,
            secondparameters: $index,
            type: 'warning',
            title: 'Usuario',
            message: "¿estás seguro de eliminar el item {$name} de la orden de compra?",
            emit: 'destroy-item',
        );
    }

    protected function formatCurrency($value)
    {
        return number_format($value, 2, ',', '.');
    }

    protected function clearFormat($value)
    {
        return str_replace(',', '.', str_replace('.', '', $value));
    }

    public function rules()
    {
        return [
            'contractor_name' => 'required|string',
            'order_name' => 'required|string',
            'contractor_nit' => 'required|string',
            'responsible_name' => 'required|string',
            'company_name' => 'required|string',
            'company_nit' => 'required|string',
            'phone' => 'required|string',
            'material_destination' => 'required|string',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'bank_name' => 'required|string',
            'account_type' => 'required|string',
            'account_number' => 'required|string',
            'support_type_id' => 'required|exists:payment_support,id',
            'project_id' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            '*.date' => 'Ingrese una fecha válida',
            '*.required' => 'Ingrese el campo requerido',
            '*.string' => 'El campo debe ser un texto válido',
            'payment_method_id.exists' => 'Seleccione un método de pago válido',
            'support_type_id.exists' => 'Seleccione un tipo de soporte válido',
            'project_id.numeric' => 'Debe ser un valor numérico',
        ];
    }

    public function storeHeader()
    {
        // Validar los campos de la cabecera
        $this->validate();

        $this->updateTotals();
        $subtotalBeforeIva = floatval($this->clearFormat($this->totalPurchase));
        $totalIva = floatval($this->clearFormat($this->totalIVA));
        $totalWithIva = floatval($this->clearFormat($this->totalPurchaseIva));
        $retention = floatval($this->clearFormat($this->retencion));
        $totalPayable = floatval($this->clearFormat($this->totalPay));

        $invoiceHeader = InvoiceHeader::create([
            'date' => $this->currentDate,
            'order_name' => $this->order_name,
            'contractor_name' => $this->contractor_name,
            'contractor_nit' => $this->contractor_nit,
            'responsible_name' => $this->responsible_name,
            'company_name' => $this->company_name,
            'company_nit' => $this->company_nit,
            'phone' => $this->phone,
            'material_destination' => $this->material_destination,
            'payment_method_id' => $this->payment_method_id,
            'bank_name' => $this->bank_name,
            'account_type' => $this->account_type,
            'account_number' => $this->account_number,
            'support_type_id' => $this->support_type_id,
            'project_id' => $this->project_id,
            'general_observations' => $this->general_observations,
            'subtotal_before_iva' => $subtotalBeforeIva,
            'total_iva' => $totalIva,
            'total_with_iva' => $totalWithIva,
            'retention' => $retention,
            'total_payable' => $totalPayable
        ]);

        foreach ($this->selectedItems as $item) {
            InvoiceDetail::create([
                'id_purchase_order' => $invoiceHeader->id,
                'id_item' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $this->clearFormat($item['price']),
                'total_price' => $this->clearFormat($item['totalPrice']),
                'iva' => $this->clearFormat($item['iva']),
                'price_iva' => $this->clearFormat($item['priceIva']),
                'total_price_iva' => $this->clearFormat($item['totalPriceIva']),
                'project_id' => $this->project_id,
            ]);
        }

        // Limpiar los campos después de guardar
        $this->selectedItems = [];
        $this->updateTotals();

        $this->reset([
            'contractor_name', 'order_name', 'contractor_nit',
            'company_name', 'company_nit', 'phone', 'material_destination',
            'payment_method_id', 'bank_name', 'account_type', 'account_number', 'support_type_id', 'general_observations',
        ]);

        $this->dispatch('alert', type: 'success', title: 'Órdenes de compra', message: 'Se guardó correctamente la orden de compra');
        sleep(1);
        $this->redirect('/purchaseorder');
    }
}
