<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use App\Models\PaidInformation;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Repository\PurchaseOrderRepository;

class PurchaseOrderServices implements IService
{

    protected $paginationService;
    protected $purchaseOrderRepository;

    public function __construct(PaginationServices $paginationService, PurchaseOrderRepository $itemRepo)
    {
        $this->paginationService = $paginationService;
        $this->purchaseOrderRepository = $itemRepo;
    }


    public function getAll()
    {
        return $this->purchaseOrderRepository->getAll();
    }

    public function getAllPaginate($filter = '', $paginate = true)
{
    $projectQuery = $this->purchaseOrderRepository->PurchaseOrderQuery();
    if ($filter != "") {
        $filter = htmlspecialchars(trim($filter));
        $projectQuery = $this->purchaseOrderRepository->filterLike($filter);
    }
    return ($paginate ) ? $projectQuery->paginate(5) : $projectQuery->get();
}

    public function getAllFilter($filter, $limit = null)
    {
        $filter = htmlspecialchars(trim($filter));
        return $this->purchaseOrderRepository->filterLike($filter, $limit);
    }

    public function getById(int $id)
    {
        return $this->purchaseOrderRepository->FindById($id);
    }

    public function Add(array $data)
    {
        return;
    }

    public function Update(int $id, array $data)
    {
        return $this->purchaseOrderRepository->Update($id, $data);
    }

    public function Delete(int $id)
    {
        try {
            // Validamos la existencia del usuario en el proyecto
            $validDelete = $this->purchaseOrderRepository->Delete($id);
            return true;
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function UpdateCreatePaid(array $data, int $id)
    {
        // Orden de compra header
        $currentPurchaseOrder = $this->getById($id);

        // Si ya hay información de pago
        if ($currentPurchaseOrder->paidInformation != null) {
            // Actualiza la información de pago existente
            $currentPaidInfo = $currentPurchaseOrder->paidInformation;
            $currentPaidInfo->update($data);
        } else {
            // Si no hay información de pago, crea una nueva
            $newPaidInfo = PaidInformation::create($data);
            $currentPurchaseOrder->paidInformation()->associate($newPaidInfo);
        }

        // Guarda los cambios en la orden de compra
        $currentPurchaseOrder->save();
        $currentPurchaseOrder->refresh();
    }
    public function updateTechDecision(bool $decision, int $id)
    {
        $currentPurchaseOrder = $this->getById($id);

        // Verificar si el usuario tiene el rol adecuado
        if (Auth::user()->hasRole('Empleado') || Auth::user()->hasRole('Contador')) {
            abort(403, 'No autorizado.');
        }

        // Verificar si existe PaidInformation asociado a la orden de compra
        if ($currentPurchaseOrder->paidInformation()->exists()) {
            // Actualizar la decisión del técnico en PaidInformation existente
            $currentPaidInfo = $currentPurchaseOrder->paidInformation;
            $currentPaidInfo->approved_tech = $decision;
            $currentPaidInfo->save();
            return;
        } else {

            $newPaidInfo = PaidInformation::create(['approved_tech' => $decision]);
            // Si no existe PaidInformation, crear uno nuevo
            $currentPurchaseOrder->paidInformation()->associate($newPaidInfo);
        }

        // Refrescar la instancia de la orden de compra
        $currentPurchaseOrder->save();
        $currentPurchaseOrder->refresh();
    }

    public function updateAccountDecision(bool $decision, int $id)
    {
        $currentPurchaseOrder = $this->getById($id);

        // Verificar si el usuario tiene el rol adecuado
        if (!Auth::user()->can('approved_account.purchase')) {
            abort(403, 'No autorizado.');
        }

        // Verificar si existe PaidInformation asociado a la orden de compra
        if ($currentPurchaseOrder->paidInformation()->exists()) {
            // Actualizar la decisión del técnico en PaidInformation existente
            $currentPaidInfo = $currentPurchaseOrder->paidInformation;
            $currentPaidInfo->approved_account = $decision;
            $currentPaidInfo->save();
            return;
        } else {

            $newPaidInfo = PaidInformation::create(['approved_account' => $decision]);
            // Si no existe PaidInformation, crear uno nuevo
            $currentPurchaseOrder->paidInformation()->associate($newPaidInfo);
        }

        // Refrescar la instancia de la orden de compra
        $currentPurchaseOrder->save();
        $currentPurchaseOrder->refresh();
    }

    public function getByProject(int $id, $searchValue = null, $paginate = true)
    {
        return $this->purchaseOrderRepository->PurchaseOrderByProject($id, $searchValue, $paginate);
    }
}
