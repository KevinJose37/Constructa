<div>
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <select wire:model="selectedProjectId" wire:change="loadMessages" class="form-select form-select-sm">
                            <option value="">Seleccionar proyecto</option>
                            @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                            @endforeach
                        </select>

                    </div>

                    <h4 class="mb-4 mt-0 fs-16">Chat</h4>

                    <div id="messages-container" class="card-body py-0 mb-3 overflow-auto" data-simplebar style="max-height: 250px;">
                        @if($messages)
                        @foreach($messages as $message)
                        <div class="d-flex align-items-start mt-3">
                            <img class="me-2 rounded-circle" src="assets/images/users/avatar-5.jpg" alt="Generic placeholder image" height="32" />
                            <div class="w-100">
                                <h5 class="mt-0">{{ $message->user->name }} <small class="text-muted float-end">{{ $message->created_at }}</small></h5>
                                {{ $message->message }}
                                <br />
                            </div>
                        </div>
                        @endforeach
                        @else
                        <p>Seleccione un proyecto que tenga mensajes o envíe uno.</p>
                        @endif
                    </div>

                    <form wire:submit.prevent="saveMessage" class="comment-area-box">
                        <input type="hidden" wire:model="selectedProjectId" name="projectId">
                        <textarea wire:model="newMessage" rows="3" class="form-control border-0 resize-none mt-3 " placeholder="Agregar comentario"></textarea>
                        <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-sm btn-success"><i class="ri-send-plane-2 me-1"></i>ENVIAR</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="card">
                <div class="card-body">


                    <h5 class="card-title fs-16 mb-3">ADJUNTOS</h5>

                    <form action="/" method="post" class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>

                        <div class="dz-message needsclick">
                            <i class="fs-36 text-muted ri-upload-cloud-line"></i>
                            <h4>Arrastra el archivo acá o presiona para subir</h4>
                        </div>
                    </form>

                    <!-- Preview -->
                    <div class="dropzone-previews mt-3" id="file-previews"></div>

                    <!-- file preview template -->
                    <div class="d-none" id="uploadPreviewTemplate">
                        <div class="card mb-1 mb-0 shadow-none border">
                            <div class="p-2">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="" />
                                    </div>
                                    <div class="col ps-0">
                                        <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                                        <p class="mb-0" data-dz-size></p>
                                    </div>
                                    <div class="col-auto">
                                        <!-- Button -->
                                        <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                            <i class="ri-close-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end file preview template -->



                    <div class="card mb-1 shadow-none border">
                        <div class="p-2">
                            <div class="row align-items-center">
                                <div class="col-auto">

                                </div>
                                <div class="col ps-0">
                                    <a href="javascript:void(0);" class="text-muted fw-bold">Dashboard-design.jpg</a>
                                    <p class="mb-0">3.25 MB</p>
                                </div>
                                <div class="col-auto">
                                    <!-- Button -->
                                    <a href="javascript:void(0);" class="btn btn-link fs-16 text-muted">
                                        <i class="ri-download-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-0 shadow-none border">
                        <div class="p-2">
                            <div class="row align-items-center">
                                <div class="col-auto">

                                </div>
                                <div class="col ps-0">
                                    <a href="javascript:void(0);" class="text-muted fw-bold">Admin-bug-report.mp4</a>
                                    <p class="mb-0">7.05 MB</p>
                                </div>
                                <div class="col-auto">
                                    <!-- Button -->
                                    <a href="javascript:void(0);" class="btn btn-link fs-16 text-muted">
                                        <i class="ri-download-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<script src="assets/vendor/dropzone/min/dropzone.min.js"></script>
<!-- init js -->
<script src="assets/js/pages/component.fileupload.js"></script>