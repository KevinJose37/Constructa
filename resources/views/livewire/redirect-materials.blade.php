<div>
    <x-page-title title="Tabla de materiales para redireccionar"></x-page-title>
    <x-table>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Cantidad</th>
                        <th>Capítulo</th>
                        <th>Ítem del capítulo</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($purchaseOrder->invoiceDetails as $index => $order)
                        <tr>
                            <td>{{ $order->item->cod }}</td>
                            <td>{{ $order->item->name }}</td>
                            <td>{{ $order->quantity }}</td>

                            {{-- Capítulo --}}
                            <td>
                                <select wire:model="chapterSelections.{{ $index }}"
                                    wire:change="$set('chapterSelections.{{ $index }}', $event.target.value)"
                                    class="form-select">
                                    <option value="">Seleccione</option>
                                    @foreach ($chapters as $chapter)
                                        <option value="{{ $chapter->id }}">{{ $chapter->chapter_number }} -
                                            {{ $chapter->chapter_name }}</option>
                                    @endforeach
                                </select>
                            </td>

                            {{-- Ítem del capítulo --}}
                            <td>
                                <select wire:model="itemSelections.{{ $index }}" class="form-select"
                                    @if (empty($availableItems[$index])) disabled @endif>
                                    <option value="">Seleccione</option>
                                    @foreach ($availableItems[$index] ?? [] as $item)
                                        <option value="{{ $item->id }}">{{ $item->item_number }} -
                                            {{ $item->description }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button wire:click="saveRedirections" class="btn btn-success mt-3">
            Guardar
        </button>


        {{-- <div class="mt-3">
            {{ $purchaseOrder->links(data: ['scrollTo' => false]) }}
        </div> --}}
    </x-table>
</div><!-- end col -->
