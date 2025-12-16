document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM 
    const notificationsBtn = document.getElementById('notificationsBtn');
    const notificationsDropdown = document.getElementById('notificationsDropdown');
    const userProfile = document.querySelector('.user-profile');
    const userDropdown = document.getElementById('userDropdown');
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.capa-lateral'); // Cambiado de '.sidebar-overlay'
    const periodBtns = document.querySelectorAll('.boton-periodo'); // Cambiado de '.period-btn'

    // Inicializar funcionalidades
    inicializarDropdowns();
    inicializarSidebar();
    configurarGraficaVentas();
    configurarPeriodos();

    // Funciones principales
    function configurarGraficaVentas() {
        const canvas = document.getElementById('graficaVentas');
        if (!canvas) return null;

        // Obtener datos desde los atributos data-*
        const meses = JSON.parse(canvas.dataset.meses || '[]');
        const ventas = JSON.parse(canvas.dataset.ventas || '[]');
        
        // Configurar gradiente para el fondo
        const ctx = canvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
        
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Ventas ($)',
                    data: ventas,
                    backgroundColor: gradient,
                    borderColor: '#3b82f6',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return `Ventas: $${context.parsed.y.toLocaleString('es-MX', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })}`;
                            }
                        }
                    },
                    // Plugin personalizado para mostrar valores debajo de las barras
                    afterDraw: function(chart) {
                        const ctx = chart.ctx;
                        const xAxis = chart.scales.x;
                        const yAxis = chart.scales.y;
                        
                        chart.data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);
                            
                            meta.data.forEach((bar, index) => {
                                const value = dataset.data[index];
                                const x = bar.x;
                                const y = bar.y;
                                
                                // Mostrar valor debajo de la barra
                                ctx.save();
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'top';
                                ctx.font = '12px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
                                ctx.fillStyle = '#6b7280';
                                
                                // Formatear el valor
                                let formattedValue;
                                if (value >= 1000) {
                                    formattedValue = '$' + (value / 1000).toFixed(1) + 'k';
                                } else {
                                    formattedValue = '$' + value.toLocaleString('es-MX', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    });
                                }
                                
                                // Mostrar el valor debajo de la barra
                                ctx.fillText(
                                    formattedValue,
                                    x,
                                    yAxis.bottom + 10
                                );
                                
                                // Mostrar el mes debajo del valor
                                ctx.font = '11px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
                                ctx.fillStyle = '#9ca3af';
                                ctx.fillText(
                                    chart.data.labels[index],
                                    x,
                                    yAxis.bottom + 25
                                );
                                
                                ctx.restore();
                            });
                        });
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12
                            },
                            // Ocultar los valores del eje Y
                            callback: function() {
                                return '';
                            }
                        },
                        border: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12
                            },
                            display: false
                        },
                        border: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                layout: {
                    padding: {
                        bottom: 40
                    }
                }
            }
        });
    }

    function configurarPeriodos() {
        if (!periodBtns.length) return;

        periodBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remover clase activo de todos
                periodBtns.forEach(b => b.classList.remove('activo')); // Cambiado de 'active'
                // Agregar clase activo al clickeado
                this.classList.add('activo'); // Cambiado de 'active'
                
                // Aquí puedes agregar la lógica para cambiar datos del gráfico
                // cuando implementes la API para diferentes períodos
                console.log('Período seleccionado:', this.dataset.periodo);
            });
        });
    }

    function inicializarDropdowns() {
        // Toggle notificaciones 
        if (notificationsBtn && notificationsDropdown) {
            notificationsBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isVisible = notificationsDropdown.classList.contains('mostrar');
                
                // Cerrar otros dropdowns
                if (userDropdown) userDropdown.classList.remove('mostrar');
                
                // Toggle notificaciones
                notificationsDropdown.classList.toggle('mostrar', !isVisible);
            });
        }

        // Toggle perfil usuario
        if (userProfile && userDropdown) {
            userProfile.addEventListener('click', function(e) {
                e.stopPropagation();
                const isVisible = userDropdown.classList.contains('mostrar');
                
                // Cerrar otros dropdowns
                if (notificationsDropdown) notificationsDropdown.classList.remove('mostrar');
                
                // Toggle perfil
                userDropdown.classList.toggle('mostrar', !isVisible);
            });
        }

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function() {
            if (notificationsDropdown) {
                notificationsDropdown.classList.remove('mostrar');
            }
            if (userDropdown) {
                userDropdown.classList.remove('mostrar');
            }
        });

        // Cerrar dropdowns con tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (notificationsDropdown) notificationsDropdown.classList.remove('mostrar');
                if (userDropdown) userDropdown.classList.remove('mostrar');
            }
        });
    }

    function inicializarSidebar() {
        // Verificar que el botón hamburguesa existe
        if (!hamburgerBtn) {
            console.error('Botón hamburguesa no encontrado');
            return;
        }

        if (!sidebar) {
            console.error('Sidebar no encontrado');
            return;
        }

        // Funcionalidad del botón hamburguesa
        hamburgerBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isActive = sidebar.classList.contains('activo');
            
            console.log('Botón hamburguesa clickeado, estado actual:', isActive);
            
            // Alternar sidebar
            sidebar.classList.toggle('activo', !isActive);
            
            // Alternar overlay
            if (overlay) {
                overlay.classList.toggle('activo', !isActive);
                console.log('Overlay toggled:', !isActive);
            }
            
            // Alternar ícono hamburguesa
            this.classList.toggle('activo', !isActive);
            
            // Prevenir scroll del body cuando sidebar está abierto
            document.body.style.overflow = !isActive ? 'hidden' : '';
            
            console.log('Estado final sidebar:', sidebar.classList.contains('activo'));
        });

        // Cerrar sidebar al hacer clic en overlay
        if (overlay) {
            overlay.addEventListener('click', function() {
                console.log('Overlay clickeado, cerrando sidebar');
                sidebar.classList.remove('activo');
                overlay.classList.remove('activo');
                if (hamburgerBtn) hamburgerBtn.classList.remove('activo');
                document.body.style.overflow = '';
            });
        }

        // Cerrar sidebar al hacer clic en un enlace (en móvil)
        const navLinks = document.querySelectorAll('.nav-link, .logout-btn');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('activo');
                    if (overlay) overlay.classList.remove('activo');
                    if (hamburgerBtn) hamburgerBtn.classList.remove('activo');
                    document.body.style.overflow = '';
                }
            });
        });

        // Cerrar sidebar con tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar && sidebar.classList.contains('activo')) {
                sidebar.classList.remove('activo');
                if (overlay) overlay.classList.remove('activo');
                if (hamburgerBtn) hamburgerBtn.classList.remove('activo');
                document.body.style.overflow = '';
            }
        });
    }

    // Cerrar sidebar al cambiar tamaño de ventana (si se vuelve desktop)
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && sidebar) {
            sidebar.classList.remove('activo');
            if (overlay) overlay.classList.remove('activo');
            if (hamburgerBtn) hamburgerBtn.classList.remove('activo');
            document.body.style.overflow = '';
        }
    });

    // Depuración: Verificar que elementos existen
    console.log('Elementos cargados:', {
        hamburgerBtn: !!hamburgerBtn,
        sidebar: !!sidebar,
        overlay: !!overlay,
        notificationsBtn: !!notificationsBtn,
        userProfile: !!userProfile
    });
});