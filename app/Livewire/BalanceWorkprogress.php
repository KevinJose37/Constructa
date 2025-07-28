<?php

namespace App\Livewire;

use Exception;
use Livewire\Component;

class BalanceWorkprogress extends Component
{

	public $open = false;
	public $action;
	// Info
	public $detail;
	public $workProgress;
	public $quantity;

	public function mount($detail, $workProgress)
	{
		$this->detail = $detail;
		$this->workProgress = $workProgress;
	}

	public function applyBalance()
	{
		// dd($this->detail);
		// dd($this->workProgress);
		$this->validate([
			'quantity' => 'required|numeric|min:0',
			'action' => 'required|in:up,down',
		], [
			'quantity.required' => 'La cantidad es requerida.',
			'quantity.numeric' => 'La cantidad debe ser un número.',
			'quantity.min' => 'La cantidad no puede ser negativa.',
			'action.required' => 'La acción es requerida.',
			'action.in' => 'La acción debe ser "up" o "down".',
		]);

		try {
			if ($this->action == "down") {
				$tmpResult = intval($this->detail->unit ?? 0) - intval($this->quantity);
				if ($tmpResult < 0) {
					$this->dispatch('alert', type: 'error', title: 'Balance', message: 'La cantidad a disminuír no puede ser mayor que la cantidad balanceada.');
					return;
				}
			}

			$this->detail->update([
				'balance_quantity'    => $this->quantity,
				'balance_adjustment'  => $this->action,
			]);

			$this->reset(['quantity', 'action']);
			$this->dispatch('alert', type: 'success', title: 'Balance', message: 'Se realizó el cambio en el balanceo.');
			$this->dispatch('workProgressUpdate');
		} catch (Exception $e) {
			$this->dispatch('alert', type: 'error', title: 'Balance', message: "Ocurrió un error al balancear: {$e->getMessage()}");
		} finally {
			$this->open = false;
		}
	}



	public function render()
	{
		return view('livewire.balance-workprogress');
	}
}
