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
        return InvoiceHeader::get();
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
        return InvoiceHeader::with('invoiceDetails.item', 'project', 'paidInformation');
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
                ->orWhereHas('project', function ($statusQuery) use ($value) {
                    $statusQuery->where('project_name', 'like', "%$value%");
                });
        });
    }
}
