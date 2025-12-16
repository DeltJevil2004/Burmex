  // Mostrar/ocultar formulario
    document.getElementById('btnNuevaMarca').addEventListener('click', function() {
        document.getElementById('formularioMarca').classList.add('mostrar');
        document.getElementById('nombre').focus();
    });
    
    document.getElementById('cerrarFormulario').addEventListener('click', function() {
        document.getElementById('formularioMarca').classList.remove('mostrar');
        window.location.href = 'marcas.php';
    });
    
    document.getElementById('btnCancelar').addEventListener('click', function() {
        document.getElementById('formularioMarca').classList.remove('mostrar');
        window.location.href = 'marcas.php';
    });
    
    // Cerrar formulario al hacer clic fuera
    document.getElementById('formularioMarca').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('mostrar');
            window.location.href = 'marcas.php';
        }
    });
    
    // Búsqueda en tiempo real
    document.getElementById('buscar-marca').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const tarjetas = document.querySelectorAll('.tarjeta-marca');
        
        tarjetas.forEach(tarjeta => {
            const nombre = tarjeta.getAttribute('data-nombre');
            if (nombre.includes(searchTerm)) {
                tarjeta.style.display = '';
            } else {
                tarjeta.style.display = 'none';
            }
        });
    });
    
    // Confirmar eliminación
    function confirmarEliminar(id, nombre) {
        if (confirm(`¿Estás seguro de eliminar la marca "${nombre}"?`)) {
            window.location.href = 'marcas.php?eliminar=' + id;
        }
    }