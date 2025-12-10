function mostrarRecuperacion() {
        alert('Contacta al administrador del sistema para recuperar tu contraseña.');
        return false;
    }

    // Validación básica del formulario
    document.querySelector('.login-form').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('contrasena').value;
        
        if (!email || !password) {
            e.preventDefault();
            alert('Por favor, completa todos los campos');
            return false;
        }
        
        // Opcional: Mostrar mensaje de carga
        const submitBtn = this.querySelector('.login-button');
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Cargando...';
    });