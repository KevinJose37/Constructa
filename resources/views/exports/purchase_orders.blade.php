<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre de la orden</th>
            <th>Proyecto</th>
            <th>Nombre contratista</th>
            <th>Nombre compañía destino</th>
            <th>Total</th>
            <th>Fecha</th>
            <th>Estado</th>

            {{-- Detalles de ítems --}}
            <th>ORDEN DE COMPRA</th>
            <th>ITEM</th>
            <th>DESCRIPCIÓN</th>
            <th>UND.</th>
            <th>CANTIDAD</th>
            <th>VALOR UNITARIO SIN IVA</th>
            <th>VALOR PARCIAL ANTES DE IVA</th>
            <th>IVA (%)</th>
            <th>VALOR UNITARIO INCLUIDO IVA</th>
            <th>VALOR PARCIAL INCLUIDO IVA</th>
            <th>VALOR RETENCIÓN</th>
            <th>VALOR TOTAL INCLUIDO IVA Y RETENCIÓN</th>
            <th>EMPRESA</th>
            <th>NIT</th>
            <th>TELÉFONO DE CONTACTO</th>
            <th>DESTINO DE MATERIAL</th>
            <th>FORMA DE PAGO</th>
            <th>CUENTA BANCARIA</th>
            <th>TIPO SOPORTE</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($purchaseOrders as $order)
            @if ($order->invoiceDetails->isNotEmpty())
                @foreach ($order->invoiceDetails as $detail)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->order_name }}</td>
                        <td>{{ $order->project?->project_name }}</td>
                        <td>{{ $order->contractor_name }}</td>
                        <td>{{ $order->company_name }}</td>
                        <td>{{ $order->total_payable }} COP</td>
                        <td>{{ $order->date }}</td>
                        <td>{{ $order->is_active ? 'Activa' : 'Inactiva' }}</td>

                        {{-- Detalles --}}
                        <td>{{ $detail->id_purchase_order }}</td>
                        <td>{{ $detail->id_item }}</td>
                        <td>{{ $detail->item?->name }}</td>
                        <td>{{ $detail->item?->unit_measurement }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price, 2, '.', ',') }}</td>
                        <td>{{ number_format($detail->total_price, 2, '.', ',') }}</td>
                        <td>{{ $detail->iva }}</td>
                        <td>{{ number_format($detail->price_iva, 2, '.', ',') }}</td>
                        <td>{{ number_format($detail->total_price_iva, 2, '.', ',') }}</td>
                        <td>{{ number_format($order->retention, 2, '.', ',') }}</td>
                        <td>{{ number_format($order->total_payable, 2, '.', ',') }}</td>
                        <td>{{ $order->company_name }}</td>
                        <td>{{ $order->company_nit }}</td>
                        <td>{{ $order->phone }}</td>
                        <td>{{ $order->material_destination }}</td>
                        <td>{{ $order->paymentMethod?->payment_name }}</td>
                        <td>{{ $order->bank_name }}</td>
                        <td>{{ $order->supportType?->support_name }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->order_name }}</td>
                    <td>{{ $order->project?->project_name }}</td>
                    <td>{{ $order->contractor_name }}</td>
                    <td>{{ $order->company_name }}</td>
                    <td>{{ $order->total_payable }} COP</td>
                    <td>{{ $order->date }}</td>
                    <td>{{ $order->is_active ? 'Activa' : 'Inactiva' }}</td>

                    {{-- Vacíos para detalles --}}
                    @for ($i = 0; $i < 19; $i++)
                        <td></td>
                    @endfor
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
