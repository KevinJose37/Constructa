<div>
    <!-- Modal -->
    <div class="modal fade" id="editPurchaseOrderInfoModal" tabindex="-1" role="dialog" aria-labelledby="editPurchaseOrderInfoModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPurchaseOrderInfoModalLabel">Editar Información de la Orden de Compra</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="form-group">
                            <label for="has_support">¿Tiene soporte?</label>
                            <select wire:model="has_support" class="form-control" id="has_support">
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="payment_date">Fecha de Pago</label>
                            <input type="date" wire:model="payment_date" class="form-control" id="payment_date">
                        </div>
                        <div class="form-group">
                            <label for="payer">¿Quién pagó?</label>
                            <input type="text" wire:model="payer" class="form-control" id="payer">
                        </div>
                        <div class="form-group">
                            <label for="is_petty_cash">¿Es caja menor?</label>
                            <select wire:model="is_petty_cash" class="form-control" id="is_petty_cash">
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
            <script>
        window.addEventListener('show-modal', event => {
            $('#editPurchaseOrderInfoModal').modal('show');
        });

        window.addEventListener('hide-modal', event => {
            $('#editPurchaseOrderInfoModal').modal('hide');
        });
    </script>
        </div>
    </div>
    

</div>