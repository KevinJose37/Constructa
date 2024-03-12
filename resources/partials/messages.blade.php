@if(isset($messages) && count($messages) > 0)
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
    <p>No hay mensajes disponibles para este proyecto.</p>
@endif