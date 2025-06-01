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
use App\Services\ProjectServices;
use App\Models\PurchaseOrderState;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\PurchaseOrderServices;

class EditPurchaseOrder extends Component
{
    public $selectedItems = [];
    public $attachmentsValid = true; // Por defecto asumimos que los archivos ya existentes son válidos

    public $totalPurchaseIva, $totalPurchase, $totalIVA, $totalPay;
    public $retencionPercentage = 2.5;
    public $currentDate, $order_name, $contractor_name, $contractor_nit, $responsible_name, $company_name,
        $company_nit, $phone, $material_destination, $payment_method_id, $bank_name, $retencion,
        $account_type, $accountType, $account_number, $support_type_id, $lastInvoiceId, $formattedDate, $project_id, $invoiceHeader, $general_observations;

    public $order = null;
    public $order_id;
    public $originalItems = []; // Para guardar los items originales y compararlos con los cambios

    public function mount($id, PurchaseOrderServices $purchaseOrderServices, ItemService $itemService)
    {
        // Verificar si el ID es numérico
        if (!is_numeric($id)) {
            $this->redirect('/purchaseorder');
            return;
        }

        $this->order_id = $id;

        // Cargar la orden de compra
        $this->order = $purchaseOrderServices->getById($id);
        if (!$this->order) {
            $this->dispatch('alert', type: 'error', title: 'Órdenes de compra', message: 'No se encontró la orden de compra');
            $this->redirect('/purchaseorder');
            return;
        }

        // Verificar si el estado es pagado y no tiene rol permitido
        $estado = $this->order->purchaseOrderState?->status;
        $user = Auth::user();

        // TODO: ajustar para validar permisos adicionales en lugar de solo el rol
        // if ($estado === \App\Models\PurchaseOrderState::STATUS_PAGADO && !$user->hasRole('Director')) {
        if ($estado === \App\Models\PurchaseOrderState::STATUS_PAGADO) {
            $this->dispatch('alert', type: 'error', title: 'Acceso denegado', message: 'Esta orden ya fue pagada y no puede ser editada');
            $this->redirect('/purchaseorder');
            return;
        }

        // Cargar datos del encabezado
        $this->loadHeaderData();

        // Cargar los detalles/items de la orden
        $this->loadOrderItems($itemService);

        // Calcular totales
        $this->updateTotals();
    }

    protected function loadHeaderData()
    {
        $this->currentDate = $this->order->date;
        $this->order_name = $this->order->order_name;
        $this->contractor_name = $this->order->contractor_name;
        $this->contractor_nit = $this->order->contractor_nit;
        $this->responsible_name = $this->order->responsible_name;
        $this->company_name = $this->order->company_name;
        $this->company_nit = $this->order->company_nit;
        $this->phone = $this->order->phone;
        $this->material_destination = $this->order->material_destination;
        $this->payment_method_id = $this->order->payment_method_id;
        $this->bank_name = $this->order->bank_name;
        $this->account_type = $this->order->account_type;
        $this->account_number = $this->order->account_number;
        $this->support_type_id = $this->order->support_type_id;
        $this->project_id = $this->order->project_id;
        $this->general_observations = $this->order->general_observations;
        $this->retencionPercentage = $this->order->retention_value ?? 2.5;
    }

    protected function loadOrderItems(ItemService $itemService)
    {
        // Obtener detalles/items de la orden
        $details = InvoiceDetail::where('id_purchase_order', $this->order_id)->get();

        foreach ($details as $detail) {
            $item = $itemService->getById($detail->id_item)->toArray();

            if ($item) {
                // Formatear los valores para mostrarlos correctamente
                $item['quantity'] = $detail->quantity;
                $item['price'] = $this->formatCurrency($detail->price);
                $item['totalPrice'] = $this->formatCurrency($detail->total_price);
                $item['ivaProduct'] = $detail->iva;
                $item['iva'] = $this->formatCurrency(Helpers::calculateIva($detail->price, $detail->iva));
                $item['priceIva'] = $this->formatCurrency($detail->price_iva);
                $item['totalPriceIva'] = $this->formatCurrency($detail->total_price_iva);

                $this->selectedItems[] = $item;
            }
        }

        // Guardar copia de los items originales para comparación
        $this->originalItems = json_encode($this->selectedItems);
    }

    #[Layout('layouts.app')]
    #[Title('Editar orden de compra')]
    #[On('itemRefresh')]
    public function render()
    {
        $paymentMethods = PaymentMethod::all();
        $paymentSupport = PaymentSupport::all();
        return view('livewire.purchase-order-form-edit', compact('paymentMethods', 'paymentSupport'));
    }

    protected function calculateTotal()
    {
        $this->totalPurchaseIva = '0'; // Iniciar como string para BCMath
        $tempSum = '0'; // Iniciar como string para BCMath

        foreach ($this->selectedItems as $item) {
            // Limpia el formato y convierte a string
            $totalPriceIva = $this->clearFormat($item["totalPriceIva"]);

            // Asegúrate de que el valor sea en el formato adecuado para BCMath
            $tempSum = bcadd($tempSum, $totalPriceIva, 2); // Suma los valores con precisión de 2 decimales
        }

        $this->totalPurchaseIva = $tempSum; // Asigna el resultado final
    }

    public function updateTotals()
    {
        $this->totalPurchase = '0';
        $this->totalIVA = '0';
        $this->totalPurchaseIva = '0';

        foreach ($this->selectedItems as $item) {
            $price = $this->clearFormat($item['totalPrice']);
            $iva = $this->clearFormat($item['iva']);
            $totalPriceIva = $this->clearFormat($item['totalPriceIva']);

            $this->totalPurchase = bcadd($this->totalPurchase, $price, 2);
            $this->totalIVA = bcadd($this->totalIVA, $iva, 2);
            $this->totalPurchaseIva = bcadd($this->totalPurchaseIva, $totalPriceIva, 2);
        }

        // Retención calculada como porcentaje del subtotal antes de IVA
        $retencionPercentage = $this->percentageToDecimal($this->retencionPercentage);
        $this->retencion = bcmul($this->totalPurchase, $retencionPercentage, 2);

        // Total a pagar es el total con IVA menos la retención
        $this->totalPay = bcsub($this->totalPurchaseIva, $this->retencion, 2);

        $this->formatCurrencyValues();
    }

    public function percentageToDecimal($percentage)
    {
        if (!is_numeric($percentage) || $percentage == 0) {
            return '0'; // Retorna '0' como cadena si no es un número válido o es 0
        }

        return bcdiv((string)$percentage, '100', 6); // 6 es la precisión decimal deseada
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

            // Calcular el total sin IVA utilizando BCMath
            $price = $this->clearFormat($this->selectedItems[$existingItemIndex]["price"]);
            $quantity = $this->selectedItems[$existingItemIndex]["quantity"];
            $this->selectedItems[$existingItemIndex]["totalPrice"] = bcmul($price, $quantity, 2);

            // Calcular el IVA y el total con IVA
            $ivaValue = $iva > 0 ? Helpers::calculateIva($price, $iva) : 0;
            $this->selectedItems[$existingItemIndex]["iva"] = $this->formatCurrency($ivaValue);
            $this->selectedItems[$existingItemIndex]["ivaProduct"] = $iva;
            $this->selectedItems[$existingItemIndex]["priceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($price, $iva));

            $totalPrice = $this->selectedItems[$existingItemIndex]["totalPrice"];
            $this->selectedItems[$existingItemIndex]["totalPriceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($totalPrice, $iva));
        } else {
            $currentItem["quantity"] = $quantityItem;
            $currentItem["price"] = $unitPrice;

            // Calcular el total sin IVA
            $price = $this->clearFormat($unitPrice);
            $currentItem["totalPrice"] = $this->formatCurrency(bcmul($price, $currentItem["quantity"], 2));

            // Calcular el IVA y el total con IVA
            $ivaValue = $iva > 0 ? Helpers::calculateIva($price, $iva) : 0;
            $currentItem["iva"] = $this->formatCurrency($ivaValue);
            $currentItem["ivaProduct"] = $iva;
            $currentItem["priceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($price, $iva));

            $totalPrice = $this->clearFormat($currentItem["totalPrice"]);
            $currentItem["totalPriceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($totalPrice, $iva));

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
        return number_format((float) $value, 2, ',', '.');
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

    #[On('attachmentsStatusUpdated')]
    public function handleAttachmentsStatus($status)
    {
        $this->attachmentsValid = $status;
    }

    public function updateHeader()
    {
        $this->validate();
        DB::beginTransaction();

        try {
            // Verificar si hay archivos adjuntos válidos (solo si se actualizaron)
            if (isset($this->attachmentsValid) && !$this->attachmentsValid) {
                $this->dispatch('flashMessage', 'error', 'Los adjuntos son requeridos o tienen un formato inválido.');
                DB::rollBack();
                return;
            }

            // Convertir a mayúsculas
            $this->contractor_name = strtoupper($this->contractor_name);
            $this->order_name = strtoupper($this->order_name);
            $this->contractor_nit = strtoupper($this->contractor_nit);
            $this->responsible_name = strtoupper($this->responsible_name);
            $this->company_name = strtoupper($this->company_name);
            $this->company_nit = strtoupper($this->company_nit);
            $this->phone = strtoupper($this->phone);
            $this->material_destination = strtoupper($this->material_destination);
            $this->bank_name = strtoupper($this->bank_name);
            $this->account_type = strtoupper($this->account_type);
            $this->general_observations = strtoupper($this->general_observations);

            $this->updateTotals();
            $subtotalBeforeIva = floatval($this->clearFormat($this->totalPurchase));
            $totalIva = floatval($this->clearFormat($this->totalIVA));
            $totalWithIva = floatval($this->clearFormat($this->totalPurchaseIva));
            $retention = bcmul($subtotalBeforeIva, $this->percentageToDecimal($this->retencionPercentage), 2);
            $totalPayable = floatval($this->clearFormat($this->totalPay));

            $accountType = $this->account_type ?: 'N/A';

            // Actualizar el encabezado de la factura
            $invoiceHeader = InvoiceHeader::findOrFail($this->order_id);
            $invoiceHeader->update([
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
                'account_type' => $accountType,
                'account_number' => $this->account_number,
                'support_type_id' => $this->support_type_id,
                'project_id' => $this->project_id,
                'general_observations' => $this->general_observations,
                'subtotal_before_iva' => $subtotalBeforeIva,
                'total_iva' => $totalIva,
                'total_with_iva' => $totalWithIva,
                'retention' => $retention,
                'total_payable' => $totalPayable,
                'retention_value' => $this->retencionPercentage,
            ]);

            $idInvoiceHeader = $invoiceHeader->id;

            $editState = PurchaseOrderState::where('invoice_header_id', $idInvoiceHeader)
                ->where(function ($query) {
                    $query->where('status', PurchaseOrderState::STATUS_POR_CONFIRMAR)
                        ->orWhere('status', PurchaseOrderState::STATUS_PENDIENTE);
                })
                ->first();

            if ($editState) {
                $editState->update([
                    'status' => PurchaseOrderState::STATUS_SIN_PROCESAR,
                    'status_notes' => 'Orden de compra editada por el usuario',
                    'status_changed_at' => now(),
                    'changed_by_user_id' => Auth::id(),
                    'previous_status' => $editState->status,
                    'change_metadata' => [
                        'action' => 'edit',
                        'user_id' => Auth::id(),
                        'timestamp' => now()->toDateTimeString(),
                    ],
                ]);
            }


            // Si hay archivos adjuntos actualizados, guardarlos
            $this->dispatch('updateAttachmentsEvent', $invoiceHeader->id);

            // Eliminar los detalles anteriores de la factura
            InvoiceDetail::where('id_purchase_order', $this->order_id)->delete();

            // Crear los nuevos detalles
            foreach ($this->selectedItems as $item) {
                InvoiceDetail::create([
                    'id_purchase_order' => $invoiceHeader->id,
                    'id_item' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $this->clearFormat($item['price']),
                    'total_price' => $this->clearFormat($item['totalPrice']),
                    'iva' => $item['ivaProduct'],
                    'price_iva' => $this->clearFormat($item['priceIva']),
                    'total_price_iva' => $this->clearFormat($item['totalPriceIva']),
                    'project_id' => $this->project_id,
                ]);
            }

            DB::commit();

            $this->dispatch('alert', type: 'success', title: 'Órdenes de compra', message: 'Se actualizó correctamente la orden de compra');
            sleep(1);
            $this->redirect(route('purchaseorder.view', ['id' => $this->order_id]));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', title: 'Error al actualizar', message: 'Ocurrió un error: ' . $e->getMessage());
        }
    }

    public function cancelEdit()
    {
        $this->redirect(route('purchaseorder.view', ['id' => $this->order_id]));
    }
}
