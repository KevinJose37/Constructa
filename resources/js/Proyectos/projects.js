import Swal from 'sweetalert2';

const baseUrl = import.meta.env.VITE_BASE_URL_API;



document.querySelectorAll('.view-users').forEach(button => {
    button.addEventListener('click', async function (e) {
        e.preventDefault(); // Evita el comportamiento por defecto del enlace

        let currentProjectId = e.currentTarget.getAttribute('data-project-id');
        let currentUrl = `${baseUrl}projects/${currentProjectId}/users`;

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
            $('#event-modal-proyectosusuario').modal('show');
            document.getElementById('event-assign-project').addEventListener('click', function(event){
                event.preventDefault();
                let selectUsers = document.getElementById('all-users-assign');
                let btnFormUsers = document.getElementById('accept-assign');
                

                if(selectUsers.style.display == 'none'){
                    selectUsers.style.display = 'block';
                    btnFormUsers.style.display = 'block';
                } else {
                    selectUsers.style.display = 'none';
                    btnFormUsers.style.display = 'none';
                    selectUsers.value = "";
                }
            });

            document.getElementById('accept-assign').addEventListener('click', function(e){
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
                    if(data.success){
                        Swal.fire({
                            title: "¡Acción realizada!",
                            text: data.message,
                            icon: "success"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                                
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
        } catch (error) {
            console.error('Error en la solicitud:', error);
        }
    });
});
