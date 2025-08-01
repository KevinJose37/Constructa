<div>
    <button type="button" wire:click="$set('open', true)" class="btn btn-info">
        <span><i class="ri-add-line"></i> Crear semana</span>
    </button>

    <x-dialog-modal wire:model="open" maxWidth="md" id="progress-week">
        <x-slot name="title"></x-slot>
        <x-slot name="content">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <h3>Creación de semanas del proyecto: {{ $project->project_name }}</h3>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <label for="work-progress-date-picker" class="form-label">Selecciona un rango de
                                        fechas</label>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <x-date-picker id="work-progress-date-picker" name="work-progress-date-picker"
                                        placeholder="Selecciona un rango de fechas" class="w-100"
                                        wire:model="weekDate" />
                                </div>
                                @error('weekDate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="row  mt-3">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <label for="nmb-week" class="form-label">Número de semana a crear</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <input type="number" id="nmb-week" class="form-control w-100"
                                        wire:model="numberWeek" placeholder="Número de semana a crear">
                                </div>
                                @error('numberWeek')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if ($project->weekProjects?->count() > 0)
                                <div class="row mt-3">
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <label for="week-number" class="form-label">Semana actual</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <table class="table table table-striped table-centered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Número semana</th>
                                                <th>Fecha semana</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($project->weekProjects as $week)
                                                <tr>
                                                    <td>{{ $week->number_week }}</td>
                                                    <td>{{ $week->string_date }}</td>
                                                    <td><a class="text-reset fs-19 px-1 delete-week-project-btn"
                                                            wire:click="destroyAlert({{ $week->id }}, '{{ $week->string_date }}')">
                                                            <i class="ri-delete-bin-2-line"></i></a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <div class="modal-footer d-flex justify-content-end w-100">
                <button type="button" class="btn btn-light me-2" wire:click="$set('open', false)">
                    Cerrar
                </button>
                <button type="button" class="btn btn-success" wire:click="createWeek">
                    Guardar
                </button>
            </div>
        </x-slot>

    </x-dialog-modal>
</div>
