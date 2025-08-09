@props([
    'id' => 'dashboard-date-picker',
    'placeholder' => 'Selecciona un rango de fechas',
    'config' => [],
])

@php
    $config = array_merge(
        [
            'mode' => 'range',
            'dateFormat' => 'd-m-Y',
            'altInput' => true,
            'altFormat' => 'l d F, Y',
            'locale' => 'es',
        ],
        $config,
    );
@endphp

<div wire:ignore x-data="{
    @if ($attributes->has('wire:model')) model: @entangle($attributes->wire('model')), @endif
    init() {
        const config = {
            ...@js($config),
            @if ($attributes->has('wire:model')) defaultDate: this.model, @endif
            onChange: (selectedDates, dateStr, instance) => {
                @if($attributes->has('wire:model'))
                this.model = dateStr;
                @endif

                // Disparar el evento personalizado
                Livewire.dispatch('dateRangeChanged:{{ $id }}', selectedDates);
            }
        };

        flatpickr(this.$refs.input, config);
        @if($attributes->has('wire:model'))
        this.$watch('model', value => {
            if (this.$refs.input._flatpickr) {
                this.$refs.input._flatpickr.setDate(value, false);
            }
        });
        @endif
    }
}" x-init="init()" class="w-100">
    <input type="text" x-ref="input" id="{{ $id }}" placeholder="{{ $placeholder }}"
        @if ($attributes->has('wire:model')) x-model="model" @endif
        {{ $attributes->except(['wire:model'])->merge(['class' => 'flatpickr-input', 'autocomplete' => 'off']) }}
        wire:key="date-picker-{{ $id }}" />
</div>
