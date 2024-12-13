<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo Granos de Oro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            color: #555;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #f08c42;
            font-weight: bold;
        }
        .subtitle {
            text-align: center;
            color: #28a745;
            margin-top: -10px;
            margin-bottom: 30px;
            font-size: 1rem;
            font-style: italic;
        }
        .card {
            border: 1px solid #e5e5e5;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
            padding: 10px;
        }
        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .card-img-top {
            max-height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .card-body {
            padding: 10px;
        }
        .card-title {
            font-size: 1rem;
            font-weight: bold;
        }
        .card-text {
            font-size: 0.875rem;
            margin-bottom: 5px;
        }
        .login-button {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #f08c42;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .login-button:hover {
            background-color: #e67e22;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .pagination .page-item .page-link {
            color: white;
            background-color: #f08c42;
            border: none;
            padding: 10px 15px;
            margin: 0 5px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .pagination .page-item .page-link:hover {
            background-color: #e67e22;
        }
        .pagination .page-item.active .page-link {
            background-color: #28a745;
            color: white;
        }
        /* Estilos para la barra de búsqueda */
        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .search-bar .input-group {
            max-width: 600px;
            width: 100%;
        }
        .search-bar .form-control {
            border: 2px solid #f08c42;
            border-right: none;
            border-radius: 30px 0 0 30px;
            padding: 10px 20px;
            font-size: 1rem;
        }
        .search-bar .form-control:focus {
            border-color: #e67e22;
            box-shadow: none;
        }
        .search-bar .btn-primary {
            background-color: #f08c42;
            border: 2px solid #f08c42;
            border-radius: 0 30px 30px 0;
            padding: 10px 20px;
            font-size: 1rem;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .search-bar .btn-primary:hover {
            background-color: #e67e22;
            border-color: #e67e22;
        }
    </style>
</head>
<body>
    <div class="container mt-5 position-relative">
        <!-- Botón de inicio de sesión -->
        <a href="/gdo/login" class="login-button">Iniciar Sesión</a>
        
        <h1>Catálogo Granos de Oro</h1>
        <p class="subtitle">Encuentra aquí los mejores productos para tu cocina</p>

        <!-- Barra de búsqueda -->
        <div class="search-bar">
            <form method="GET" action="{{ route('catalog.index') }}" class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar productos..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </form>
        </div>
        
        <!-- Productos -->
        <div class="row">
            @forelse ($productos as $producto)
                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                    <div class="card">
                        <img src="{{ asset('storage/' . $producto->imagen) }}" class="card-img-top" alt="{{ $producto->nombre }}">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $producto->nombre }}</h5>
                            <p class="card-text">Precio: ${{ number_format($producto->precio, 2) }}</p>
                            <p class="card-text">Disponibles: {{ $producto->cantidad_en_existencia }} uds.</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center" role="alert">
                        No se encontraron productos.
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Paginador -->
        <nav>
            <ul class="pagination">
                @if ($productos->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">Anterior</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $productos->previousPageUrl() }}">Anterior</a>
                    </li>
                @endif

                @if ($productos->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $productos->nextPageUrl() }}">Siguiente</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Siguiente</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>