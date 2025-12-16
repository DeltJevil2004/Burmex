// Script para el funcionamiento del buscador y menú hamburguesa
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const searchToggle = document.getElementById('searchToggle');
    const searchBox = document.getElementById('searchBox');
    const searchToggleMobile = document.getElementById('searchToggleMobile');
    const searchBoxMobile = document.getElementById('searchBoxMobile');
    const searchInput = document.querySelector('.search-input');
    const searchInputMobile = document.querySelector('.mobile-search-box .search-input');
    const searchBtn = document.querySelector('.search-btn');
    const searchBtnMobile = document.querySelector('.mobile-search-box .search-btn');
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    
    // Variables de estado
    let isSearchOpen = false;
    let isMobileSearchOpen = false;
    let isMenuOpen = false;
    
    //  FUNCIONES PARA EL BUSCADOR DESKTOP 
    function openSearch() {
        searchBox.classList.add('active');
        searchToggle.style.opacity = '0';
        searchToggle.style.visibility = 'hidden';
        searchToggle.style.pointerEvents = 'none';
        searchInput.focus();
        isSearchOpen = true;
    }
    
    function closeSearch() {
        searchBox.classList.remove('active');
        searchToggle.style.opacity = '1';
        searchToggle.style.visibility = 'visible';
        searchToggle.style.pointerEvents = 'auto';
        searchInput.value = '';
        isSearchOpen = false;
    }
    
    //  FUNCIONES PARA EL BUSCADOR MÓVIL 
    function openMobileSearch() {
        searchBoxMobile.classList.add('active');
        searchToggleMobile.style.opacity = '0';
        searchToggleMobile.style.visibility = 'hidden';
        searchToggleMobile.style.pointerEvents = 'none';
        searchInputMobile.focus();
        isMobileSearchOpen = true;
        
        // NO cerramos el menú hamburguesa cuando se abre el buscador móvil
        // El menú permanece abierto
    }
    
    function closeMobileSearch() {
        searchBoxMobile.classList.remove('active');
        searchToggleMobile.style.opacity = '1';
        searchToggleMobile.style.visibility = 'visible';
        searchToggleMobile.style.pointerEvents = 'auto';
        searchInputMobile.value = '';
        isMobileSearchOpen = false;
    }
    
    //  FUNCIONES PARA EL MENÚ HAMBURGUESA 
    function openMenu() {
        navMenu.classList.add('active');
        hamburger.classList.add('active');
        document.body.style.overflow = 'hidden';
        isMenuOpen = true;
    }
    
    function closeMenu() {
        navMenu.classList.remove('active');
        hamburger.classList.remove('active');
        document.body.style.overflow = '';
        isMenuOpen = false;
        
        // También cerramos el buscador móvil si está abierto
        if (isMobileSearchOpen) {
            closeMobileSearch();
        }
    }
    
    // EVENT LISTENERS PARA DESKTOP 
    if (searchToggle) {
        searchToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            openSearch();
        });
    }
    
    //  EVENT LISTENERS PARA MÓVIL 
    if (searchToggleMobile) {
        searchToggleMobile.addEventListener('click', function(e) {
            e.stopPropagation();
            // SOLO abrimos el buscador móvil, NO cerramos el menú hamburguesa
            openMobileSearch();
        });
    }
    
    // Botón hamburguesa
    if (hamburger) {
        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            if (isMenuOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        });
    }
    
    //  EVENT LISTENERS COMPARTIDOS 
    // Buscar en desktop
    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            performSearch(searchInput.value.trim());
        });
    }
    
    // Buscar en móvil
    if (searchBtnMobile) {
        searchBtnMobile.addEventListener('click', function(e) {
            e.stopPropagation();
            performSearch(searchInputMobile.value.trim());
        });
    }
    
    // Enter en campo de búsqueda desktop
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch(searchInput.value.trim());
            }
        });
    }
    
    // Enter en campo de búsqueda móvil
    if (searchInputMobile) {
        searchInputMobile.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch(searchInputMobile.value.trim());
            }
        });
    }
    
    //  FUNCIÓN PARA REALIZAR BÚSQUEDA 
    function performSearch(searchTerm) {
        if (searchTerm) {
            console.log('Buscando:', searchTerm);

        }
    }
    
    //  CERRAR AL HACER CLIC FUERA 
    document.addEventListener('click', function(e) {
        // Cerrar buscador desktop
        if (isSearchOpen && 
            !searchBox.contains(e.target) && 
            !searchToggle.contains(e.target)) {
            closeSearch();
        }
        
        // Cerrar buscador móvil
        if (isMobileSearchOpen && 
            !searchBoxMobile.contains(e.target) && 
            !searchToggleMobile.contains(e.target)) {
            closeMobileSearch();
        }
        
        // Cerrar menú hamburguesa al hacer clic fuera 
        if (isMenuOpen && 
            !navMenu.contains(e.target) && 
            !hamburger.contains(e.target) &&
            !searchBoxMobile.contains(e.target)) { 
            closeMenu();
        }
    });
    
    // Prevenir que el click dentro de los elementos los cierre
    if (searchBox) {
        searchBox.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    if (searchBoxMobile) {
        searchBoxMobile.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    if (navMenu) {
        navMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Cerrar menú al hacer clic en un enlace 
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (isMenuOpen) {
                closeMenu();
            }
        });
    });
    
});

