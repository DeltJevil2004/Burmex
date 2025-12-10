    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="inicio.php">
                    <img src="../img/img-inicio/header-logo.png" alt="Logo Burmex" class="logo-desktop">
                    <img src="../img/img-inicio/logo-mobile.svg" alt="Logo Burmex" class="logo-mobile">
                </a>
            </div>

            <div class="hamburger" id="hamburger">
                <img src="../img/img-inicio/hamburguesa.svg" alt="Menú" class="hamburger-icon">
            </div>

            <ul class="nav-menu" id="navMenu">
                <li class="nav-item"><a href="../php/inicio.php" class="nav-link">Inicio</a></li>
                <li class="nav-item"><a href="../php/productos.php" class="nav-link">Productos</a></li>
                <li class="nav-item"><a href="../php/servicios.php" class="nav-link">Servicios</a></li>
                <li class="nav-item"><a href="../php/nosotros.php" class="nav-link">Nosotros</a></li>
                <li class="nav-item"><a href="inicio.php#contacto" class="nav-link">Contacto</a></li>

                <div class="nav-mobile-items">
                    <div class="nav-icon search-container mobile-search">
                        <img src="../img/img-inicio/lupa.svg" alt="Buscar" class="search-icon" id="searchToggleMobile">
                        <div class="search-box mobile-search-box" id="searchBoxMobile">
                            <input type="text" placeholder="Buscar..." class="search-input">
                            <button class="search-btn">Buscar</button>
                        </div>
                    </div>

                    <div class="nav-icon cart-container mobile-cart">
                        <img src="../img/img-inicio/carrito.svg" alt="Carrito de compras" class="cart-icon">
                        <span class="cart-count">0</span>
                    </div>

                    <a href="../php/cotizar.php" class="cta-button nav-link">Cotizar</a>
                </div>
            </ul>

            <div class="nav-right">
                <div class="nav-icon search-container">
                    <img src="../img/img-inicio/lupa.svg" alt="Buscar" class="search-icon" id="searchToggle">
                    <div class="search-box" id="searchBox">
                        <input type="text" placeholder="Buscar..." class="search-input">
                        <button class="search-btn">Buscar</button>
                    </div>
                </div>

                <div class="nav-icon cart-container">
                    <img src="../img/img-inicio/carrito.svg" alt="Carrito de compras" class="cart-icon">
                    <span class="cart-count">0</span>
                </div>

                <a href="../php/cotizar.php" class="cta-button nav-link">Cotizar</a>
            </div>
        </div>
    </nav>