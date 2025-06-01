<?php

namespace App\Http\Repository;

use Exception;
use App\Models\Item;
use App\Models\User;
use App\Models\InvoiceHeader;
use Spatie\Permission\Models\Role;
use Illuminate\Database\QueryException;

class PurchaseOrderRepository implements IRepository
{

    public function getAll()
    {
        return InvoiceHeader::with('project')->get();
    }

    public function FindById($id)
    {
        return $this->PurchaseOrderQuery()->find($id);
    }

    public function Create(array $data)
    {
        return;
    }

    public function validUserByColumn($columnName, $value, $ignoreId = null)
    {
        return;
    }

    public function Update($id, array $data)
    {
        return;
    }

    public function Delete($id)
    {
        try {
            $purchaseOrder = $this->FindById($id);
            if (!$purchaseOrder) {
                throw new Exception("Fail to find the order purchase", 1);
            }
            $purchaseOrder->invoiceDetails()->delete();
            $purchaseOrder->delete();
            return true;
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), 1);
        }
    }

    public function PurchaseOrderQuery()
    {
        return InvoiceHeader::with('invoiceDetails.item', 'project', 'paidInformation', 'purchaseOrderState');
    }


    public function filterLike($value, $limit = null)
    {
        return InvoiceHeader::where(function ($queryBuilder) use ($value) {
            $queryBuilder->where('date', 'like', "%$value%")
                ->orWhere('contractor_name', 'like', "%$value%")
                ->orWhere('contractor_nit', 'like', "%$value%")
                ->orWhere('responsible_name', 'like', "%$value%")
                ->orWhere('company_name', 'like', "%$value%")
                ->orWhere('company_nit', 'like', "%$value%")
                ->orWhere('phone', 'like', "%$value%")
                ->orWhere('material_destination', 'like', "%$value%")
                ->orWhereHas('order_name', function ($statusQuery) use ($value) {
                    $statusQuery->where('order_name', 'like', "%$value%");
                });
        });
    }


    public function PurchaseOrderByProject($id, $searchValue = null)
    {
        $query = InvoiceHeader::with('invoiceDetails.item', 'project', 'paidInformation')
            ->where('project_id', $id);

        // Aplicar el filtro si se proporciona un valor de búsqueda
        if ($searchValue) {
            $query->where(function ($queryBuilder) use ($searchValue) {
                $queryBuilder->where('date', 'like', "%$searchValue%")
                    ->orWhere('contractor_name', 'like', "%$searchValue%")
                    ->orWhere('contractor_nit', 'like', "%$searchValue%")
                    ->orWhere('responsible_name', 'like', "%$searchValue%")
                    ->orWhere('company_name', 'like', "%$searchValue%")
                    ->orWhere('company_nit', 'like', "%$searchValue%")
                    ->orWhere('phone', 'like', "%$searchValue%")
                    ->orWhere('material_destination', 'like', "%$searchValue%")
                    ->orWhereHas('order_name', function ($statusQuery) use ($searchValue) {
                        $statusQuery->where('order_name', 'like', "%$searchValue%");
                    });
            });
        }

        // Aplicar paginación y devolver los resultados
        return $query->paginate(10);
    }
}
