import Swal from 'sweetalert2';

const baseUrl = import.meta.env.VITE_BASE_URL_API;

document.querySelectorAll('.view-users').forEach(button => {
    button.addEventListener('click', async function (e) {
        e.preventDefault(); // Evita el comportamiento por defecto del enlace

        let currentProjectId = e.currentTarget.getAttribute('data-project-id');
        reloadModalData(currentProjectId);
    });
});

function linkedEventListeners() {
    // Event listener para eliminar usuarios del proyecto
    document.querySelectorAll('.delete-user-project-btn').forEach(function (button) {
        button.addEventListener('click', function (event) {
            let idProject = document.getElementById('idProject').value;
            let idUser = event.currentTarget.getAttribute('data-id-user');
            Swal.fire({
                title: "Atención",
                text: "¿Estás seguro de quitar al usuario del proyecto?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí"
            }).then((result) => {
                if (result.isConfirmed) {       
                    let jsonData = {
                        "idProject": idProject,
                        "idUser": idUser,
                    }

                    fetch(`${baseUrl}projects/unassigned`, {
                        method: 'POST',
                        body: JSON.stringify(jsonData),
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: "Completado!",
                                    text: "Se desvinculó correctamente al usuario del proyecto.",
                                    icon: "success"
                                }).then(() => {
                                    if (result.isConfirmed) {
                                        reloadModalData(idProject);
                                    }
                                });

                                return;
                            }

                            Swal.fire({
                                title: "¡Ocurrió un error!",
                                text: data.errors,
                                icon: "danger"
                            });
                        })
                        .catch(error => {
                            console.error('Error al enviar el formulario:', error);
                        });
                }
            });
        });
    });

    // Event listener para asignar usuarios del proyecto
    document.getElementById('accept-assign').addEventListener('click', function (e) {
        e.preventDefault();
        let idProject = document.getElementById('idProject').value;
        let idUser = document.getElementById('all-users-assign').value;

        let jsonData = {
            "idProject": idProject,
            "idUser": idUser,
        }

        fetch(`${baseUrl}projects/assign`, {
            method: 'POST',
            body: JSON.stringify(jsonData),
            headers: {
                'Content-Type': 'application/json',
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: "¡Acción realizada!",
                        text: data.message,
                        icon: "success"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            reloadModalData(idProject);
                        }
                    });
                    return;
                }

                Swal.fire({
                    title: "¡Ocurrió un error!",
                    text: data.errors,
                    icon: "danger"
                });
            })
            .catch(error => {
                console.error('Error al enviar el formulario:', error);
            });
    });


    document.getElementById('event-assign-project').addEventListener('click', function (event) {
        event.preventDefault();
        let selectUsers = document.getElementById('all-users-assign');
        let btnFormUsers = document.getElementById('accept-assign');


        if (selectUsers.style.display == 'none') {
            selectUsers.style.display = 'block';
            btnFormUsers.style.display = 'block';
        } else {
            selectUsers.style.display = 'none';
            btnFormUsers.style.display = 'none';
            selectUsers.value = "";
        }
    });
}


async function reloadModalData(projectId) {
    let currentUrl = `${baseUrl}projects/${projectId}/users`;

    try {
        const response = await fetch(currentUrl);
        const data = await response.json();
        if (!response.ok && !data.success) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: data.message,
            });
            $('#event-modal-proyectosusuario').modal('hide');
            return;
        }

        document.getElementById('modal-body-users').innerHTML = data.html;
        let modal = $('#event-modal-proyectosusuario');
        modal.modal('show');
        linkedEventListeners();
    } catch (error) {
        console.error('Error en la solicitud:', error);
    }
}

async function UnassignedUser(projectId, $userId) {
    let currentUrl = `${baseUrl}projects/${projectId}/users`;

    try {
        const response = await fetch(currentUrl);
        const data = await response.json();
        if (!response.ok && !data.success) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: data.message,
            });
            $('#event-modal-proyectosusuario').modal('hide');
            return;
        }

        document.getElementById('modal-body-users').innerHTML = data.html;
        let modal = $('#event-modal-proyectosusuario');
        modal.modal('show');
        linkedEventListeners();
    } catch (error) {
        console.error('Error en la solicitud:', error);
    }
}
