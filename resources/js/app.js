import "./bootstrap";
import Swal from "sweetalert2";
//-- Select2
import "/node_modules/select2/dist/css/select2.css";
//--
// import '../css/app.css';

import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import select2 from 'select2';
select2();

window.addEventListener("alert", (event) => {
    let data = event.detail;
    Swal.fire({
        title: data.title,
        text: data.message,
        icon: data.type,
    });
});

window.addEventListener("resetSelect", (event) => {
    let data = event.detail;
    $(`#${data.id}`).val(null).trigger('change');
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
                Livewire.dispatch(data.emit, { id: data.id, secondparameters: data.secondparameters });
            }
        });
    });
});
