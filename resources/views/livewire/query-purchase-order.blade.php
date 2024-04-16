<div>
    <x-page-title title="Tabla de órdenes de compra"></x-page-title>
    <x-table>

        <div class="row w-100">
            <div class="col-lg-6 w-25">
                <!-- Div a la izquierda -->
                <div class="input-group">
                    <button class="btn btn-primary"><i class="ri-search-line"></i></button>
                    <input type="text" name="filter" class="form-control" placeholder="Buscar orden de compra"
                        wire:model.live="search">
                    <button class="btn" id="clear-filter" wire:click="$set('search', '')"><i
                            class="ri-close-line"></i></button>
                </div>
            </div>
        </div>
        <div class="table-responsive">

        </div>
        <table class="table table-striped table-centered mb-0 ">
            <thead>
                <tr class="d-flex">
                    <th class="col-md-2">Nombre del proyecto</th>
                    <th class="col-md-2">Nombre contratista</th>
                    <th class="col-md-2">Nombre compañía destino</th>
                    <th class="col-md-2">Total</th>
                    <th class="col-md-2">Fecha</th>
                    <th class="col-md-2">Pagado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOrder as $order)
                    <tr id="purchaseOrderRow_{{ $order->id }}" class="d-flex">
                        <td class="col-md-2">{{ $order->project->project_name }}</td>
                        <td class="col-md-2">{{ $order->contractor_name }}</td>
                        <td class="col-md-2">{{ $order->company_name }}</td>
                        <td class="col-md-2">{{ $order->total_payable }} COP</td>
                        <td class="col-md-2">{{ $order->date }}</td>
                        <td>
                            <livewire:purchase-order-paid-information :order="$order"
                                :wire:key="'purchase-order-paid-' . $order->id"></livewire:purchase-order-paid-information>
                        </td>

                        <td style="display: flex; align-items: center;" class="col-md-1">
                            <a href="#" class="text-reset fs-19 px-1 delete-project-btn"
                                wire:click.prevent="destroyAlert({{ $order->id }}, '{{ $order->project->project_name }}')">
                                <i class="ri-delete-bin-2-line"></i></a>
                            <a href="{{ route('purchaseorder.get', ['id' => $order->id]) }}"><i
                                    class="ri-search-eye-line"></i></a>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $purchaseOrder->links(data: ['scrollTo' => false]) }}
    </x-table>
</div><!-- end col -->
