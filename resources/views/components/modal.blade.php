@props(['id', 'maxWidth', 'scrollable' => false])

@php
    $id = $id ?? md5($attributes->wire('model'));

    $maxWidth = [
        'sm' => ' modal-sm',
        'md' => '',
        'lg' => ' modal-lg',
        'xl' => ' modal-xl',
    ][$maxWidth ?? 'md'];

    $scrollableClass = $scrollable ? ' modal-dialog-scrollable' : '';
@endphp

<!-- Modal -->
<div wire:ignore.self x-init="() => {

    let el = document.querySelector('#modal-id-{{ $id }}')
    let modal = new bootstrap.Modal(el);

    $watch('show', value => {
        if (value) {
            modal.show()
        } else {
            modal.hide()
        }
    });

    el.addEventListener('hide.bs.modal', function(event) {
        show = false

    })

}" x-data="{ show: @entangle($attributes->wire('model')) }" x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false" x-show="show" id="modal-id-{{ $id }}" class="modal fade"
    style="display: none;" tabindex="-1" aria-hidden="true" aria-labelledby="modal-id-{{ $id }}"
    x-ref="modal-id-{{ $id }}">
    <div class="modal-dialog{{ $scrollableClass }}{{ $maxWidth }}">
        {{ $slot }}
    </div>
</div>
