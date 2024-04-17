<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Repository\PurchaseOrderRepository;
use App\Models\PaidInformation;

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

    public function getAllPaginate($filter = '')
    {
        $projectQuery = $this->purchaseOrderRepository->PurchaseOrderQuery();
        if ($filter != "") {
            $filter = htmlspecialchars(trim($filter));
            $projectQuery = $this->purchaseOrderRepository->filterLike($filter);
        }
        return $this->paginationService->filter($projectQuery);
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
        return;
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
}
