<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comfachocó - Sistema de Gestión de Permisos y Vacaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #3b82f6;
            --text-color: #1e293b;
            --light-bg: #f8fafc;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            background-color: var(--light-bg);
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 6rem 0;
            border-radius: 0 0 2rem 2rem;
        }
        
        .feature-card {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-outline-light {
            border-radius: 0.5rem;
            padding: 0.75rem 2rem;
        }
        
        .footer {
            background-color: var(--text-color);
            color: white;
            padding: 3rem 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <span class="fs-4">COMFACHOCÓ</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Sistema de Gestión de Permisos y Vacaciones</h1>
                    <p class="lead mb-4">Simplifica la gestión de permisos, vacaciones y licencias para tu equipo de trabajo con nuestra plataforma intuitiva y eficiente.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Registrarse</a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="https://cdn.pixabay.com/photo/2018/03/10/12/00/teamwork-3213924_1280.jpg" alt="Equipo de trabajo" class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 my-5">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Características Principales</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="bi bi-calendar-check"></i>
                                📅
                            </div>
                            <h3 class="card-title">Gestión de Permisos</h3>
                            <p class="card-text">Solicita y aprueba permisos de manera rápida y sencilla, con notificaciones automáticas.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="bi bi-graph-up"></i>
                                📊
                            </div>
                            <h3 class="card-title">Reportes Detallados</h3>
                            <p class="card-text">Genera informes personalizados sobre ausencias, vacaciones y permisos del personal.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="bi bi-people"></i>
                                👥
                            </div>
                            <h3 class="card-title">Calendario Compartido</h3>
                            <p class="card-text">Visualiza en un calendario todas las ausencias programadas del equipo.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="fw-bold mb-4">¿Listo para optimizar la gestión de tu equipo?</h2>
                    <p class="lead mb-4">Comienza a utilizar nuestro sistema y mejora la eficiencia en la gestión de permisos y vacaciones.</p>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Comenzar Ahora</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="mb-4">COMFACHOCÓ</h4>
                    <p>Sistema de Gestión de Permisos y Vacaciones para empresas modernas.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2023 Comfachocó. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
