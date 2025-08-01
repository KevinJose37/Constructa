@props(['id', 'maxWidth', 'scrollable' => false])

@php
    $id = $id ?? md5($attributes->wire('model')->value());
    $maxWidth = ['sm' => ' modal-sm', 'md' => '', 'lg' => ' modal-lg', 'xl' => ' modal-xl'][$maxWidth ?? 'md'];
    $scrollableClass = $scrollable ? ' modal-dialog-scrollable' : '';
@endphp

<div x-data="{ show: $wire.entangle('{{ $attributes->wire('model')->value() }}') }">
    <div wire:ignore.self x-init="() => {
        const el = $refs.modal;
        const modal = new bootstrap.Modal(el);
        $watch('show', value => value ? modal.show() : modal.hide());
        el.addEventListener('hide.bs.modal', () => show = false);
    }" x-show="show" x-ref="modal" class="modal fade"
        id="modal-id-{{ $id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog{{ $scrollableClass }}{{ $maxWidth }}">
            <div class="modal-content">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
