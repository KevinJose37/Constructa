<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use App\Models\PaidInformation;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Repository\PurchaseOrderRepository;
use App\Models\InvoiceHeader;


namespace App\Services;

use App\Models\InvoiceHeader;

class ConsolidatedServices
{
    public function getAllPaginate($search = "")
    {
        return InvoiceHeader::with(['invoiceDetails.item'])
            ->whereHas('invoiceDetails.item', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(10);
    }

    public function getFilteredDetails($search = "")
    {
        return InvoiceHeader::with(['invoiceDetails' => function($query) use ($search) {
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }, 'invoiceDetails.item'])
        ->whereHas('invoiceDetails.item', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->paginate(10);
    }
    
    #Esto hace el filtro del buscador de la tabla de consolidados
    public function getFilteredDetailsByProject($search, $projectId)
{
    return InvoiceHeader::with(['invoiceDetails.item']) 
        ->where('project_id', $projectId)
        ->whereHas('invoiceDetails', function($query) use ($search) {
            $query->whereHas('item', function($query) use ($search) {
                if ($search) {
                    $query->where('name', 'like', "%{$search}%");
                }
            });
        })
        ->paginate(10);
}

}
