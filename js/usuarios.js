// FUNCIONES GLOBALES
function filtrarPorRol() {
    const rol = document.getElementById('filtro-rol').value;
    window.location.href = 'usuarios.php?rol=' + rol;
}

function editarUsuario(id) {
    window.location.href = 'usuarios.php?editar=' + id;
}

function eliminarUsuario(id, nombre) {
    if (confirm(`¿Estás seguro de eliminar al usuario "${nombre}"?`)) {
        window.location.href = 'usuarios.php?eliminar=' + id;
    }
}

// Código que necesita DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
// Mostrar formulario para nuevo usuario
const btnNuevoUsuario = document.getElementById('btnNuevoUsuario');
if (btnNuevoUsuario) {
    btnNuevoUsuario.addEventListener('click', function() {
        document.getElementById('formularioUsuario').classList.add('mostrar');
        document.getElementById('email').focus();
    });
}
    // Cerrar formulario
    const cerrarFormulario = document.getElementById('cerrarFormulario');
    if (cerrarFormulario) {
        cerrarFormulario.addEventListener('click', function() {
            window.location.href = 'usuarios.php';
        });
    }
    
    const btnCancelar = document.getElementById('btnCancelar');
    if (btnCancelar) {
        btnCancelar.addEventListener('click', function() {
            window.location.href = 'usuarios.php';
        });
    }
    
    // Cerrar formulario al hacer clic fuera
    const formularioUsuario = document.getElementById('formularioUsuario');
    if (formularioUsuario) {
        formularioUsuario.addEventListener('click', function(e) {
            if (e.target === this) {
                window.location.href = 'usuarios.php';
            }
        });
    }
    
    // Mostrar/ocultar campos de contraseña al editar
    const cambiarPasswordCheckbox = document.getElementById('cambiar_password');
    if (cambiarPasswordCheckbox) {
        cambiarPasswordCheckbox.addEventListener('change', function(e) {
            const camposPassword = document.querySelector('.campos-password');
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('confirm_password');
            
            if (e.target.checked) {
                camposPassword.style.display = 'grid';
                if (passwordInput) passwordInput.required = true;
                if (confirmInput) confirmInput.required = true;
            } else {
                camposPassword.style.display = 'none';
                if (passwordInput) {
                    passwordInput.required = false;
                    passwordInput.value = '';
                }
                if (confirmInput) {
                    confirmInput.required = false;
                    confirmInput.value = '';
                }
            }
        });
    }
    
    // Búsqueda en tiempo real
    const buscarInput = document.getElementById('buscar-usuario');
    if (buscarInput) {
        buscarInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#tablaUsuariosBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});