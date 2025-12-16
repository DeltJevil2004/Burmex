 
// Funciones para manejo de imágenes
function mostrarVistaPrevia(event) {
    const input = event.target;
    const vistaPreviaContenedor = document.getElementById('vistaPreviaContenedor');
    const imagenVistaPrevia = document.getElementById('imagenVistaPrevia');
    const sinImagenTexto = document.getElementById('sinImagenTexto');
    const inputUrl = document.getElementById('imagen_url');
    
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
    const inputUrl = document.getElementById('imagen_url');
    const vistaPreviaContenedor = document.getElementById('vistaPreviaContenedor');
    const imagenVistaPrevia = document.getElementById('imagenVistaPrevia');
    const sinImagenTexto = document.getElementById('sinImagenTexto');
    const inputArchivo = document.getElementById('imagen_archivo');
    
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
    const inputArchivo = document.getElementById('imagen_archivo');
    const inputUrl = document.getElementById('imagen_url');
    
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

document.addEventListener('DOMContentLoaded', function() {
    // Variables
    const modalFormulario = document.getElementById('modalFormulario');
    const btnCerrarModal = document.getElementById('btnCerrarModal');
    const btnCerrarModalEliminar = document.getElementById('btnCerrarModalEliminar');
    const btnCancelar = document.getElementById('btnCancelar');
    const btnCancelarEliminar = document.getElementById('btnCancelarEliminar');
    const tieneDescuento = document.getElementById('tiene_descuento');
    const campoDescuento = document.getElementById('campo-descuento');
    const precioInput = document.getElementById('precio');
    const porcentajeDescuentoInput = document.getElementById('porcentaje_descuento');
    const precioConDescuento = document.getElementById('precio-con-descuento');
    const formBusqueda = document.getElementById('formBusqueda');
    const inputBusqueda = document.getElementById('inputBusqueda');
    const filtroCategoria = document.getElementById('filtro-categoria');
    const filtroMarca = document.getElementById('filtro-marca');
    
    //  FUNCIÓN CORREGIDA PARA FILTROS 
    function filtrarProductos() {
        const categoria = filtroCategoria ? filtroCategoria.value : 'todas';
        const marca = filtroMarca ? filtroMarca.value : 'todas';
        const busqueda = inputBusqueda ? inputBusqueda.value : '';
        
        // Construir URL con todos los parámetros actuales
        const urlParams = new URLSearchParams(window.location.search);
        
        // Actualizar parámetros
        urlParams.set('pagina', '1'); // Siempre volver a página 1 al filtrar
        
        if (busqueda) {
            urlParams.set('busqueda', busqueda);
        } else {
            urlParams.delete('busqueda');
        }
        
        if (categoria !== 'todas') {
            urlParams.set('categoria', categoria);
        } else {
            urlParams.delete('categoria');
        }
        
        if (marca !== 'todas') {
            urlParams.set('marca', marca);
        } else {
            urlParams.delete('marca');
        }
        
        // Construir URL final
        const nuevaURL = 'productos.php?' + urlParams.toString();
        
        // Redirigir
        window.location.href = nuevaURL;
    }
    
    //  BÚSQUEDA AUTOMÁTICA 
    if (inputBusqueda) {
        let timeoutId;
        inputBusqueda.addEventListener('input', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(filtrarProductos, 800); // Aumentado a 800ms
        });
        
        // Permitir búsqueda con Enter
        inputBusqueda.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filtrarProductos();
            }
        });
    }
    
    //  FILTROS DE CATEGORÍA Y MARCA 
    // Asegurar que los eventos estén correctamente asignados
    if (filtroCategoria) {
        // Remover evento anterior si existe
        filtroCategoria.onchange = null;
        // Agregar nuevo evento
        filtroCategoria.addEventListener('change', filtrarProductos);
    }
    
    if (filtroMarca) {
        // Remover evento anterior si existe
        filtroMarca.onchange = null;
        // Agregar nuevo evento
        filtroMarca.addEventListener('change', filtrarProductos);
    }
    
    // Cerrar modal de formulario
    if (btnCerrarModal) {
        btnCerrarModal.addEventListener('click', function() {
            modalFormulario.style.display = 'none';
            // Limpiar parámetros de URL
            const url = new URL(window.location);
            url.searchParams.delete('crear');
            url.searchParams.delete('editar');
            window.history.replaceState({}, '', url);
        });
    }
    
    if (btnCancelar) {
        btnCancelar.addEventListener('click', function() {
            modalFormulario.style.display = 'none';
            // Limpiar parámetros de URL
            const url = new URL(window.location);
            url.searchParams.delete('crear');
            url.searchParams.delete('editar');
            window.history.replaceState({}, '', url);
        });
    }
    
    // Cerrar modal de eliminación
    if (btnCerrarModalEliminar) {
        btnCerrarModalEliminar.addEventListener('click', function() {
            modalFormulario.style.display = 'none';
        });
    }
    
    if (btnCancelarEliminar) {
        btnCancelarEliminar.addEventListener('click', function() {
            modalFormulario.style.display = 'none';
        });
    }
    
    // Cerrar modal al hacer clic fuera
    modalFormulario.addEventListener('click', function(e) {
        if (e.target === modalFormulario) {
            modalFormulario.style.display = 'none';
            // Limpiar parámetros de URL
            const url = new URL(window.location);
            url.searchParams.delete('crear');
            url.searchParams.delete('editar');
            window.history.replaceState({}, '', url);
        }
    });
    
    // Mostrar/ocultar campo de descuento
    if (tieneDescuento) {
        tieneDescuento.addEventListener('change', function() {
            if (campoDescuento) {
                campoDescuento.style.display = this.checked ? 'flex' : 'none';
            }
            calcularPrecioDescuento();
        });
    }
    
    // Calcular precio con descuento
    function calcularPrecioDescuento() {
        if (precioInput && porcentajeDescuentoInput && precioConDescuento) {
            const precio = parseFloat(precioInput.value) || 0;
            const porcentaje = parseFloat(porcentajeDescuentoInput.value) || 0;
            
            if (porcentaje > 0 && porcentaje <= 100) {
                const descuento = precio * (porcentaje / 100);
                const precioFinal = precio - descuento;
                precioConDescuento.textContent = '$' + precioFinal.toFixed(2);
            } else {
                precioConDescuento.textContent = '$' + precio.toFixed(2);
            }
        }
    }
    
    // Escuchar cambios en precio y porcentaje
    if (precioInput) {
        precioInput.addEventListener('input', calcularPrecioDescuento);
    }
    
    if (porcentajeDescuentoInput) {
        porcentajeDescuentoInput.addEventListener('input', calcularPrecioDescuento);
    }
    
    // Calcular precio inicial
    calcularPrecioDescuento();
    
    // Botones eliminar en tarjetas
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            const productoId = this.getAttribute('data-id');
            const productoNombre = this.getAttribute('data-nombre');
            
            if (confirm(`¿Estás seguro de que quieres eliminar "${productoNombre}"?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const accionInput = document.createElement('input');
                accionInput.type = 'hidden';
                accionInput.name = 'accion';
                accionInput.value = 'eliminar';
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'producto_id';
                idInput.value = productoId;
                
                form.appendChild(accionInput);
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
    
    // Evitar envío del formulario de búsqueda
    if (formBusqueda) {
        formBusqueda.addEventListener('submit', function(e) {
            e.preventDefault();
            filtrarProductos();
        });
    }
    
    // Si el modal está visible, prevenir scroll del body
    if (modalFormulario.style.display === 'flex') {
        document.body.style.overflow = 'hidden';
    }
    
    // Mostrar vista previa inicial si hay imagen
    const imagenVistaPrevia = document.getElementById('imagenVistaPrevia');
    if (imagenVistaPrevia && imagenVistaPrevia.src) {
        const sinImagenTexto = document.getElementById('sinImagenTexto');
        const vistaPreviaContenedor = document.getElementById('vistaPreviaContenedor');
        
        if (imagenVistaPrevia.src.includes('img-productos/') || imagenVistaPrevia.src.trim() !== '') {
            imagenVistaPrevia.style.display = 'block';
            if (sinImagenTexto) sinImagenTexto.style.display = 'none';
            if (vistaPreviaContenedor) vistaPreviaContenedor.style.display = 'block';
        }
    }
    
    // Si hay botones de limpiar filtros, agregar funcionalidad
    document.querySelectorAll('.btn-limpiar-filtros').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Redirigir a productos.php sin parámetros
            window.location.href = 'productos.php';
        });
    });
});
 