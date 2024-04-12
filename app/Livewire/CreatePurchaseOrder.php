<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use App\Helpers\Helpers;
use App\Livewire\Forms\CreatePurchaseOrderModalForm;
use Livewire\Attributes\On;
use App\Models\PaymentMethod;
use App\Services\ItemService;
use App\Models\PaymentSupport;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Livewire\Forms\PurchaseOrderForm;
use App\Models\InvoiceHeader;
use App\Models\InvoiceDetail;

use Illuminate\Support\Facades\Log;

class CreatePurchaseOrder extends Component
{

    public $selectedItems = [];
    public $totalPurchaseIva, $totalPurchase,$totalIVA,$retencion,$totalPay;
    public PurchaseOrderForm $formPurchase;
    public $currentDate, $contractor_name, $contractor_nit, $responsible_name, $company_name,
        $company_nit, $phone, $material_destination, $payment_method_id, $bank_name,
        $account_type, $account_number, $support_type_id, $lastInvoiceId,$formattedDate, $project_id, $invoiceHeader, $general_observations,$generalObservations;

    public function mount($id)
    {
        $this->currentDate = now()->format('d/m/y');
        $this->lastInvoiceId = InvoiceHeader::max('id');
        $this->project_id = $id;
        Log::info('Project ID en mount:', ['project_id' => $this->project_id]);

    }

    #[Layout('layouts.app')]
    #[Title('Crear orden de compra')]
    #[On('itemRefresh')]
    public function render(ItemService $itemService)
    {
        $paymentMethods = PaymentMethod::all();
        $paymentSupport = PaymentSupport::all();
        return view('livewire.create-purchase-order', compact('paymentMethods', 'paymentSupport'));
    }

    protected function calculateTotal()
    {
        $this->totalPurchaseIva = 0;
        $tempSum = 0;
        foreach ($this->selectedItems as $item) {
            $tempSum += str_replace(',', '', $item["totalPriceIva"]);
        }
        $this->totalPurchaseIva = $tempSum;
    }

    protected function updateTotals()
    {
        $this->totalPurchase = 0;
        $this->totalIVA = 0;
        $this->totalPurchaseIva = 0;
    
        foreach ($this->selectedItems as $item) {
            $price = floatval(str_replace(',', '', $item['totalPrice']));
            $iva = floatval(str_replace(',', '', $item['iva']));
            $totalPriceIva = floatval(str_replace(',', '', $item['totalPriceIva']));
    
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
    public function store(ItemService $itemService, $idItem, $unitPrice, $quantityItem)
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
            if ($item['id'] == $idItem && $item['price'] == $unitPrice) {
                $existingItemIndex = $index;
                break;
            }
        }

        if ($existingItemIndex !== false) {
            $this->selectedItems[$existingItemIndex]["quantity"] += $quantityItem;
            $this->selectedItems[$existingItemIndex]["totalPrice"] = $this->selectedItems[$existingItemIndex]["price"] * $this->selectedItems[$existingItemIndex]["quantity"];
            $this->selectedItems[$existingItemIndex]["iva"] = $this->formatCurrency(Helpers::calculateIva($this->selectedItems[$existingItemIndex]["price"]));
            $this->selectedItems[$existingItemIndex]["priceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($this->selectedItems[$existingItemIndex]["price"]));
            $this->selectedItems[$existingItemIndex]["totalPriceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($this->selectedItems[$existingItemIndex]["totalPrice"]));
        } else {
            $currentItem["quantity"] = $quantityItem;
            $currentItem["price"] = $unitPrice;
            $currentItem["totalPrice"] = $this->formatCurrency($unitPrice * $currentItem["quantity"]);
            $currentItem["iva"] = $this->formatCurrency(Helpers::calculateIva($unitPrice));
            $currentItem["priceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($unitPrice));
            $currentItem["totalPriceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($currentItem["totalPrice"]));

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
        return number_format($value, 2, '.', ',');
    }
    public function storeHeader()
    {
        try {
            // Validar los campos de la cabecera
            $this->validate([
                'currentDate' => 'required|date',
                'contractor_name' => 'required|string',
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
                
            ]);
            $this->updateTotals();
            $subtotalBeforeIva = floatval(str_replace(',', '', $this->totalPurchase));
            $totalIva = floatval(str_replace(',', '', $this->totalIVA));
            $totalWithIva = floatval(str_replace(',', '', $this->totalPurchaseIva));
            $retention = floatval(str_replace(',', '', $this->retencion));
            $totalPayable = floatval(str_replace(',', '', $this->totalPay));

            $invoiceHeader = InvoiceHeader::create([
                'date' => $this->currentDate,
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
                    'price' => str_replace(',', '', $item['price']),
                    'total_price' => str_replace(',', '', $item['totalPrice']),
                    'iva' => str_replace(',', '', $item['iva']),
                    'price_iva' => str_replace(',', '', $item['priceIva']),
                    'total_price_iva' => str_replace(',', '', $item['totalPriceIva']),
                    'project_id' => $this->project_id,
                ]);
            }
            

            // Limpiar los campos después de guardar

            $this->selectedItems = [];
            $this->updateTotals();


            $this->reset([
                'contractor_name', 'contractor_nit', 'responsible_name', 
                'company_name', 'company_nit', 'phone', 'material_destination', 
                'payment_method_id', 'bank_name', 'account_type', 'account_number', 'support_type_id', 'general_observations',
            ]);
            

            session()->flash('message', 'La cabecera se ha almacenado exitosamente.');

        } catch (\Exception $e) {
            // Capturar cualquier excepción y registrarla
            Log::error('Error al guardar la cabecera de la factura: ', ['error' => $e->getMessage()]);
            session()->flash('error', 'Ocurrió un error al guardar la cabecera.');
        }
    }
}
