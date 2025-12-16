// Funciones para manejo de imágenes
function mostrarVistaPrevia(event) {
    const input = event.target;
    const vistaPreviaContenedor = document.getElementById('vistaPreviaContenedor');
    const imagenVistaPrevia = document.getElementById('imagenVistaPrevia');
    const sinImagenTexto = document.getElementById('sinImagenTexto');
    const inputUrl = document.getElementById('logo_url');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            imagenVistaPrevia.src = e.target.result;
            imagenVistaPrevia.style.display = 'block';
            if (sinImagenTexto) sinImagenTexto.style.display = 'none';
            if (vistaPreviaContenedor) vistaPreviaContenedor.style.display = 'block';
            
            // Limpiar el campo de URL cuando se sube archivo
            if (inputUrl) inputUrl.value = '';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

function mostrarVistaPreviaURL() {
    const inputUrl = document.getElementById('logo_url');
    const vistaPreviaContenedor = document.getElementById('vistaPreviaContenedor');
    const imagenVistaPrevia = document.getElementById('imagenVistaPrevia');
    const sinImagenTexto = document.getElementById('sinImagenTexto');
    const inputArchivo = document.getElementById('logo_archivo');
    
    if (inputUrl && inputUrl.value.trim() !== '') {
        imagenVistaPrevia.src = inputUrl.value;
        imagenVistaPrevia.style.display = 'block';
        imagenVistaPrevia.onload = function() {
            if (sinImagenTexto) sinImagenTexto.style.display = 'none';
            if (vistaPreviaContenedor) vistaPreviaContenedor.style.display = 'block';
        };
        imagenVistaPrevia.onerror = function() {
            imagenVistaPrevia.style.display = 'none';
            if (sinImagenTexto) sinImagenTexto.style.display = 'block';
            if (vistaPreviaContenedor) vistaPreviaContenedor.style.display = 'block';
        };
        
        // Limpiar el campo de archivo cuando se ingresa URL
        if (inputArchivo) inputArchivo.value = '';
    }
}

function quitarImagen() {
    const vistaPreviaContenedor = document.getElementById('vistaPreviaContenedor');
    const imagenVistaPrevia = document.getElementById('imagenVistaPrevia');
    const sinImagenTexto = document.getElementById('sinImagenTexto');
    const inputArchivo = document.getElementById('logo_archivo');
    const inputUrl = document.getElementById('logo_url');
    
    if (imagenVistaPrevia) imagenVistaPrevia.src = '';
    if (sinImagenTexto) sinImagenTexto.style.display = 'block';
    if (vistaPreviaContenedor) vistaPreviaContenedor.style.display = 'block';
    if (inputArchivo) inputArchivo.value = '';
    if (inputUrl) inputUrl.value = '';
}

function ocultarVistaPrevia() {
    const imagenVistaPrevia = document.getElementById('imagenVistaPrevia');
    const sinImagenTexto = document.getElementById('sinImagenTexto');
    if (imagenVistaPrevia) imagenVistaPrevia.style.display = 'none';
    if (sinImagenTexto) sinImagenTexto.style.display = 'block';
}

// Confirmar eliminación con paginación
function confirmarEliminar(id, nombre) {
    if (confirm(`¿Estás seguro de eliminar la marca "${nombre}"?`)) {
        // Obtener página actual de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const paginaActual = urlParams.get('pagina') || '1';
        window.location.href = 'marcas.php?eliminar=' + id + '&pagina=' + paginaActual;
    }
}

// Filtrar marcas en tiempo real (para búsqueda sin recargar página)
function filtrarMarcasEnTiempoReal() {
    const searchTerm = this.value.toLowerCase();
    const tarjetas = document.querySelectorAll('.tarjeta-marca');
    let visibleCount = 0;
    
    tarjetas.forEach(tarjeta => {
        const nombre = tarjeta.getAttribute('data-nombre');
        if (nombre.includes(searchTerm)) {
            tarjeta.style.display = '';
            visibleCount++;
        } else {
            tarjeta.style.display = 'none';
        }
    });
    
    // Opcional: Mostrar mensaje si no hay resultados
    const mensajeSinResultados = document.getElementById('mensajeSinResultados');
    if (visibleCount === 0 && searchTerm !== '') {
        if (!mensajeSinResultados) {
            const gridMarcas = document.getElementById('gridMarcas');
            const mensaje = document.createElement('div');
            mensaje.id = 'mensajeSinResultados';
            mensaje.className = 'sin-marcas';
            mensaje.innerHTML = `
                <i class="fas fa-search"></i>
                <h3>No se encontraron marcas</h3>
                <p>No hay marcas que coincidan con "${searchTerm}"</p>
                <button onclick="limpiarBusqueda()" class="btn-limpiar-filtros">
                    <i class="fas fa-times"></i> Limpiar búsqueda
                </button>
            `;
            gridMarcas.appendChild(mensaje);
        }
    } else if (mensajeSinResultados) {
        mensajeSinResultados.remove();
    }
}

// Limpiar búsqueda en tiempo real
function limpiarBusqueda() {
    const inputBusqueda = document.getElementById('buscar-marca');
    if (inputBusqueda) {
        inputBusqueda.value = '';
        filtrarMarcasEnTiempoReal.call(inputBusqueda);
    }
}

// Cargar vista previa inicial si hay imagen
function cargarVistaPreviaInicial() {
    const imagenVistaPrevia = document.getElementById('imagenVistaPrevia');
    if (imagenVistaPrevia && imagenVistaPrevia.src) {
        const sinImagenTexto = document.getElementById('sinImagenTexto');
        const vistaPreviaContenedor = document.getElementById('vistaPreviaContenedor');
        
        // Verificar si la imagen ya está cargada
        if (imagenVistaPrevia.src.includes('data:') || imagenVistaPrevia.src.trim() !== '') {
            imagenVistaPrevia.onload = function() {
                imagenVistaPrevia.style.display = 'block';
                if (sinImagenTexto) sinImagenTexto.style.display = 'none';
                if (vistaPreviaContenedor) vistaPreviaContenedor.style.display = 'block';
            };
            imagenVistaPrevia.onerror = function() {
                imagenVistaPrevia.style.display = 'none';
                if (sinImagenTexto) sinImagenTexto.style.display = 'block';
                if (vistaPreviaContenedor) vistaPreviaContenedor.style.display = 'block';
            };
            
            // Forzar carga si ya tiene src
            if (imagenVistaPrevia.src && !imagenVistaPrevia.src.includes('data:')) {
                imagenVistaPrevia.style.display = 'block';
                if (sinImagenTexto) sinImagenTexto.style.display = 'none';
                if (vistaPreviaContenedor) vistaPreviaContenedor.style.display = 'block';
            }
        }
    }
}

// Inicializar eventos cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar formulario
    const btnNuevaMarca = document.getElementById('btnNuevaMarca');
    const cerrarFormulario = document.getElementById('cerrarFormulario');
    const btnCancelar = document.getElementById('btnCancelar');
    const formularioMarca = document.getElementById('formularioMarca');
    const buscarInput = document.getElementById('buscar-marca');
    
    // Botón nueva marca
    if (btnNuevaMarca) {
        btnNuevaMarca.addEventListener('click', function() {
            formularioMarca.classList.add('mostrar');
            document.getElementById('nombre').focus();
        });
    }
    
    // Cerrar formulario con botón X
    if (cerrarFormulario) {
        cerrarFormulario.addEventListener('click', function() {
            formularioMarca.classList.remove('mostrar');
            // Obtener página actual de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const paginaActual = urlParams.get('pagina') || '1';
            window.location.href = 'marcas.php?pagina=' + paginaActual;
        });
    }
    
    // Cancelar formulario
    if (btnCancelar) {
        btnCancelar.addEventListener('click', function() {
            formularioMarca.classList.remove('mostrar');
            // Obtener página actual de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const paginaActual = urlParams.get('pagina') || '1';
            window.location.href = 'marcas.php?pagina=' + paginaActual;
        });
    }
    
    // Cerrar formulario al hacer clic fuera
    if (formularioMarca) {
        formularioMarca.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('mostrar');
                // Obtener página actual de la URL
                const urlParams = new URLSearchParams(window.location.search);
                const paginaActual = urlParams.get('pagina') || '1';
                window.location.href = 'marcas.php?pagina=' + paginaActual;
            }
        });
    }
    
    // Búsqueda en tiempo real (si no hay formulario de búsqueda POST)
    if (buscarInput) {
        // Verificar si hay un formulario de búsqueda
        const formBusqueda = buscarInput.closest('form');
        if (!formBusqueda || formBusqueda.method.toLowerCase() !== 'get') {
            buscarInput.addEventListener('input', filtrarMarcasEnTiempoReal);
        } else {
            // Si hay formulario GET, agregar delay para enviar
            let timeout;
            buscarInput.addEventListener('input', function(e) {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    // Actualizar campo página a 1 cuando se busca
                    const paginaInput = formBusqueda.querySelector('input[name="pagina"]');
                    if (paginaInput) {
                        paginaInput.value = '1';
                    }
                    formBusqueda.submit();
                }, 500);
            });
        }
    }
    
    // Cargar vista previa inicial
    cargarVistaPreviaInicial();
    
    // Manejar redirecciones de botones editar con paginación
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href') || this.closest('a').href;
            window.location.href = url;
        });
    });
    
    // Manejar eliminación desde botones (si usan onclick en HTML)
    // Esto es un respaldo si los botones no tienen onclick
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        if (!btn.hasAttribute('onclick')) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id') || this.closest('button').getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre') || this.closest('button').getAttribute('data-nombre');
                if (id && nombre) {
                    confirmarEliminar(id, nombre);
                }
            });
        }
    });
    
    // Manejar tecla Enter en búsqueda
    if (buscarInput) {
        buscarInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const formBusqueda = buscarInput.closest('form');
                if (formBusqueda) {
                    // Actualizar página a 1 cuando se presiona Enter
                    const paginaInput = formBusqueda.querySelector('input[name="pagina"]');
                    if (paginaInput) {
                        paginaInput.value = '1';
                    }
                    formBusqueda.submit();
                }
            }
        });
    }
});