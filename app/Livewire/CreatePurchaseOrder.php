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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Livewire\Forms\PurchaseOrderForm;

class CreatePurchaseOrder extends Component
{
	public $selectedItems = [];
	public $attachmentsValid = false; // Propiedad para saber si los archivos son válidos
	public $isViewMode = false;
	public $project;
	// [CACHE]
	public $hasCache = false;
	public $cacheProperties = ['attachmentsValid', 'isViewMode', 'order', 'invoiceHeader'];

	public $totalPurchaseIva, $totalPurchase, $totalIVA, $totalPay;
	public PurchaseOrderForm $formPurchase;
	public $retencionPercentage = 2.5;
	public $currentDate, $order_name, $contractor_name, $contractor_nit, $responsible_name, $company_name,
		$company_nit, $phone, $material_destination, $payment_method_id, $bank_name, $retencion,
		$account_type, $accountType, $account_number, $support_type_id, $lastInvoiceId, $formattedDate, $project_id, $invoiceHeader, $general_observations, $generalObservations;
	public $order = null;


	public function mount($id, ProjectServices $projectServices)
	{
		$this->currentDate = now()->format('y/m/d');
		$this->project_id = $id;
		$this->responsible_name = Auth::user()->name;
		$currentProject = $projectServices->getById($this->project_id);
		$this->project = $currentProject;

		if (!$currentProject || is_null($currentProject)) {
			$this->redirect('/proyectos');
			return;
		}

		$this->contractor_name = $currentProject->contratista;
		$this->contractor_nit = $currentProject->nit;

		// Obtener el último ID de la orden de compra para el proyecto específico
		$this->lastInvoiceId = InvoiceHeader::where('project_id', $this->project_id)->count();


		// Verificar si hay cache guardado
		$hasCacheKey = $this->getCacheKey('hasCache');
		if (Cache::has($hasCacheKey)) {
			$this->dispatch(
				'alertConfirmation',
				id: null,
				secondparameters: null,
				type: 'info',
				title: 'Trabajo guardado',
				message: 'Tienes trabajo guardado previamente. ¿Quieres retomar el trabajo guardado?',
				emit: 'resumeCachedForm',
				rejectEmit: 'clearProjectCache'
			);
		}
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
		return view('livewire.purchase-order-form', compact('paymentMethods', 'paymentSupport'));
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
		$this->saveToCache('selectedItems', $this->selectedItems);
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

		$this->calculateTotal();
		$this->updateTotals();
		$this->saveToCache('selectedItems', $this->selectedItems);
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

	public function storeHeader()
	{
		$this->validate();
		DB::beginTransaction();
		try {
			// Verificar si hay archivos adjuntos
			if (!isset($this->attachmentsValid) || !$this->attachmentsValid) {
				$this->dispatch('flashMessage', 'error', 'Los adjuntos son requeridos.');
				DB::rollBack();
				return;
			}

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
			$lastInvoiceId = InvoiceHeader::where('project_id', $this->project_id)->count() + 1;
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
				'invoice_number'  => $lastInvoiceId,
				'retention_value' => is_numeric($this->retencionPercentage)
					? floatval(str_replace(',', '.', $this->retencionPercentage))
					: 0.00,
			]);

			$this->dispatch('saveAttachmentsEvent', $invoiceHeader->id);

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

			$this->selectedItems = [];
			$this->updateTotals();
			$this->reset([
				'contractor_name',
				'order_name',
				'contractor_nit',
				'company_name',
				'company_nit',
				'phone',
				'material_destination',
				'payment_method_id',
				'bank_name',
				'account_type',
				'account_number',
				'support_type_id',
				'general_observations',
			]);

			DB::commit();

			$this->dispatch('alert', type: 'success', title: 'Órdenes de compra', message: 'Se guardó correctamente la orden de compra');
			sleep(1);
			$this->redirect(route('purchaseorder.redirect', ['id' => $invoiceHeader->id]));
			$this->clearProjectCache();
		} catch (\Exception $e) {
			DB::rollBack();
			$this->dispatch('alert', type: 'error', title: 'Error al guardar', message: 'Ocurrió un error: ' . $e->getMessage());
		}
	}

	public function updated(string $property, $value)
	{
		if (in_array($property, $this->cacheProperties)) return;
		$this->hasCache = true;
		$this->saveToCache('hasCache', $this->hasCache);
		$this->saveToCache($property, $value);
		if (in_array($property, ['selectedItems', 'retencionPercentage'])) {
			$this->updateTotals();
			$this->calculateTotal();
		}
	}


	protected function getCacheKey(string $property): string
	{
		$idUser = Auth::user()->id;
		return "po:{$this->project_id}:{$property}:{$idUser}";
	}

	protected function saveToCache(string $property, $value)
	{
		$cacheValue = is_array($value) || is_object($value) ? json_encode($value) : $value;
		Cache::put($this->getCacheKey($property), $cacheValue, now()->addHours(2));
	}

	#[On('resumeCachedForm')]
	public function loadFormFromCache()
	{
		$foundAny = false;
		foreach (get_object_vars($this) as $prop => $_) {
			$key = $this->getCacheKey($prop);
			if (Cache::has($key)) {
				$val = Cache::get($key);
				if ($prop === 'selectedItems') {
					$this->$prop = json_decode($val, true);
				} else {
					$this->$prop = $val;
				}
				$foundAny = true;
			}
		}
		if ($foundAny) {
			$this->updateTotals();
			$this->calculateTotal();
		}
	}

	#[On('clearProjectCache')]
	public function clearProjectCache(): void
	{
		$this->hasCache = false;
		foreach (get_object_vars($this) as $prop => $_) {
			if (in_array($prop, $this->cacheProperties)) continue;
			Cache::forget($this->getCacheKey($prop));
		}
	}
}
