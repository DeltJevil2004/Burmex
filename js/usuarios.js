function filtrarPorRol() {
    const rol = document.getElementById('filtro-rol').value;
    window.location.href = 'usuarios.php?rol=' + rol;
}

function editarUsuario(id) {
    window.location.href = 'usuarios/editar.php?id=' + id;
}

function eliminarUsuario(id, nombre) {
    if (confirm(`¿Estás seguro de eliminar al usuario "${nombre}"?`)) {
        window.location.href = 'usuarios/eliminar.php?id=' + id;
    }
}

// Búsqueda en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const buscarInput = document.getElementById('buscar-usuario');
    if (buscarInput) {
        buscarInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.tabla-usuarios tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});