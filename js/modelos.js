// Funciones principales
function confirmarEliminar(id, nombre, pagina) {
    if (confirm(`¿Estás seguro de eliminar el modelo "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
        window.location.href = `modelos.php?eliminar=${id}&pagina=${pagina}`;
    }
}

function filtrarModelos() {
    const marcaSelect = document.getElementById('filtro-marca');
    const busquedaInput = document.getElementById('buscar-modelo');
    const formBusqueda = document.getElementById('formBusquedaModelos');
    
    if (!marcaSelect || !busquedaInput || !formBusqueda) return;
    
    // Actualizar página a 1 cuando se filtra
    const paginaInput = formBusqueda.querySelector('input[name="pagina"]');
    if (paginaInput) {
        paginaInput.value = '1';
    }
    
    // Enviar el formulario
    formBusqueda.submit();
}

function limpiarFiltros() {
    window.location.href = 'modelos.php';
}

// Inicializar eventos cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Variables
    const modalFormulario = document.getElementById('modalFormulario');
    const btnCerrarModal = document.getElementById('btnCerrarModal');
    const btnCancelar = document.getElementById('btnCancelar');
    const btnNuevoModelo = document.getElementById('btnNuevoModelo');
    const buscarInput = document.getElementById('buscar-modelo');
    const filtroMarca = document.getElementById('filtro-marca');
    const formBusquedaModelos = document.getElementById('formBusquedaModelos');
    
    // Botón nuevo modelo - ABRE EL MODAL
    if (btnNuevoModelo) {
        btnNuevoModelo.addEventListener('click', function() {
            // Redirigir para abrir modal de creación
            const urlParams = new URLSearchParams(window.location.search);
            const paginaActual = urlParams.get('pagina') || '1';
            window.location.href = `modelos.php?crear=1&pagina=${paginaActual}`;
        });
    }
    
    // Cerrar modal con botón X
    if (btnCerrarModal) {
        btnCerrarModal.addEventListener('click', function() {
            if (modalFormulario) {
                modalFormulario.style.display = 'none';
                // Limpiar parámetros de URL y redirigir
                const urlParams = new URLSearchParams(window.location.search);
                const paginaActual = urlParams.get('pagina') || '1';
                window.location.href = `modelos.php?pagina=${paginaActual}`;
            }
        });
    }
    
    // Cancelar modal
    if (btnCancelar) {
        btnCancelar.addEventListener('click', function() {
            if (modalFormulario) {
                modalFormulario.style.display = 'none';
                // Limpiar parámetros de URL y redirigir
                const urlParams = new URLSearchParams(window.location.search);
                const paginaActual = urlParams.get('pagina') || '1';
                window.location.href = `modelos.php?pagina=${paginaActual}`;
            }
        });
    }
    
    // Cerrar modal al hacer clic fuera
    if (modalFormulario) {
        modalFormulario.addEventListener('click', function(e) {
            if (e.target === modalFormulario) {
                modalFormulario.style.display = 'none';
                // Limpiar parámetros de URL y redirigir
                const urlParams = new URLSearchParams(window.location.search);
                const paginaActual = urlParams.get('pagina') || '1';
                window.location.href = `modelos.php?pagina=${paginaActual}`;
            }
        });
    }
    
    // Búsqueda automática con delay
    if (buscarInput && formBusquedaModelos) {
        let timeoutId;
        buscarInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                filtrarModelos();
            }, 800);
        });
        
        // Permitir búsqueda con Enter
        buscarInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filtrarModelos();
            }
        });
    }
    
    // Filtro de marca
    if (filtroMarca) {
        // Remover evento anterior si existe
        filtroMarca.onchange = null;
        // Agregar nuevo evento
        filtroMarca.addEventListener('change', filtrarModelos);
    }
    
    // Si el modal está visible, prevenir scroll del body
    if (modalFormulario && modalFormulario.style.display === 'flex') {
        document.body.style.overflow = 'hidden';
    }
    
    // Botón limpiar filtros
    const btnLimpiarFiltros = document.querySelector('.btn-limpiar-filtros');
    if (btnLimpiarFiltros) {
        btnLimpiarFiltros.addEventListener('click', function(e) {
            e.preventDefault();
            limpiarFiltros();
        });
    }
    
    // Manejar eliminación desde botones
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        if (!btn.hasAttribute('onclick')) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id') || this.closest('button').getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre') || this.closest('button').getAttribute('data-nombre');
                if (id && nombre) {
                    // Obtener página actual de la URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const paginaActual = urlParams.get('pagina') || '1';
                    confirmarEliminar(id, nombre, paginaActual);
                }
            });
        }
    });
    
    // NO agregar event listener a botones editar - ya tienen onclick en HTML
    // Los botones ya tienen: onclick="window.location.href='modelos.php?editar=ID&pagina=PAGINA'"
    
    // Evitar envío doble del formulario
    const formModelo = document.querySelector('.form-modelo');
    if (formModelo) {
        formModelo.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
            }
        });
    }
});