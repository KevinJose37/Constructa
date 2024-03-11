import "./bootstrap";
import Swal from "sweetalert2";

window.addEventListener("alert", (event) => {
    let data = event.detail;
    Swal.fire({
        title: data.title,
        text: data.message,
        icon: data.type,
    });
});

document.addEventListener("livewire:init", function () {
    window.addEventListener("alertConfirmation", (event) => {
        let data = event.detail;
        Swal.fire({
            title: data.title,
            text: data.message,
            icon: data.type,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí",
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch(data.emit, {id: data.id});
            }
        });
    });
});
