<?php
// Configurar codificación UTF-8
header('Content-Type: text/html; charset=utf-8');

$api_url = "http://localhost:8080/api/productos";

// Función auxiliar para realizar peticiones HTTP sin cURL
function make_request($url, $method = 'GET', $data = null) {
    $options = [
        'http' => [
            'method'  => $method,
            'header'  => "Content-Type: application/json\r\n",
            'ignore_errors' => true
        ]
    ];
    if ($data !== null) {
        $options['http']['content'] = json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    $context = stream_context_create($options);
    return file_get_contents($url, false, $context);
}

// --- PROCESAR ACCIONES (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'crear' || $action === 'editar') {
        $payload = [
            'nombre' => $_POST['nombre'],
            'proveedor' => $_POST['proveedor'],
            'fechaVencimiento' => $_POST['fecha_vencimiento'],
            'precio' => (float)$_POST['precio'],
            'stock' => (int)$_POST['stock']
        ];

        if ($action === 'crear') {
            make_request($api_url, 'POST', $payload);
        } else {
            $id = $_POST['id'];
            make_request("$api_url/$id", 'PUT', $payload);
        }
        header("Location: index.php");
        exit;
    }

    if ($action === 'eliminar') {
        $id = $_POST['id'];
        make_request("$api_url/$id", 'DELETE');
        header("Location: index.php");
        exit;
    }
}

// --- CONSUMIR API REST: OBTENER PRODUCTOS ACTIVOS ---
$response = @file_get_contents($api_url);
$productos = $response ? (json_decode($response, true) ?? []) : [];

// Cargar datos si se selecciona editar
$productoEditar = null;
if (isset($_GET['edit_id'])) {
    foreach ($productos as $p) {
        if ($p['id'] == $_GET['edit_id']) {
            $productoEditar = $p;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimarket - Gestión de Productos</title>
    <!-- Bootstrap 5 & FontAwesome Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .card-header { border-top-left-radius: 12px !important; border-top-right-radius: 12px !important; font-weight: 600; }
        .table thead { background-color: #1e293b; color: #ffffff; }
        .btn-custom-primary { background-color: #2563eb; color: white; border-radius: 8px; }
        .btn-custom-primary:hover { background-color: #1d4ed8; color: white; }
        .badge-stock { font-size: 0.9em; padding: 6px 12px; border-radius: 6px; }
    </style>
</head>
<body class="py-5">

<div class="container">
    <!-- Encabezado -->
    <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-3 rounded-3 shadow-sm">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary text-white p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="fa-solid fa-store fa-lg"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-0 text-dark">Gestión de Productos</h3>
                <small class="text-muted">Sistema de Microservicios para Minimarket</small>
            </div>
        </div>
        <span class="badge bg-success px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i> Backend Conectado</span>
    </div>

    <!-- Formulario Crear / Editar -->
    <div class="card mb-5">
        <div class="card-header bg-dark text-white d-flex align-items-center gap-2 py-3">
            <i class="fa-solid <?= $productoEditar ? 'fa-pen-to-square' : 'fa-plus-circle' ?>"></i>
            <span><?= $productoEditar ? 'Editar Producto #' . $productoEditar['id'] : 'Registrar Nuevo Producto' ?></span>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="index.php">
                <input type="hidden" name="action" value="<?= $productoEditar ? 'editar' : 'crear' ?>">
                <?php if ($productoEditar): ?>
                    <input type="hidden" name="id" value="<?= $productoEditar['id'] ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nombre del Producto</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej. Leche Entera 1L" value="<?= htmlspecialchars($productoEditar['nombre'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Proveedor</label>
                        <input type="text" name="proveedor" class="form-control" placeholder="Ej. Lácteos S.A." value="<?= htmlspecialchars($productoEditar['proveedor'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha de Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="form-control" value="<?= $productoEditar['fechaVencimiento'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Precio (Bs.)</label>
                        <div class="input-group">
                            <span class="input-group-text">Bs.</span>
                            <input type="number" step="0.01" min="0" name="precio" class="form-control" placeholder="0.00" value="<?= $productoEditar['precio'] ?? '' ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Stock Disponible</label>
                        <input type="number" min="0" name="stock" class="form-control" placeholder="0" value="<?= $productoEditar['stock'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-success fw-bold w-100 py-2">
                            <i class="fa-solid fa-floppy-disk me-1"></i> <?= $productoEditar ? 'Actualizar' : 'Guardar Producto' ?>
                        </button>
                        <?php if ($productoEditar): ?>
                            <a href="index.php" class="btn btn-outline-secondary py-2">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Productos Activos -->
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <span class="fs-5"><i class="fa-solid fa-list me-2"></i> Listado de Productos Activos</span>
            <span class="badge bg-light text-dark fw-bold"><?= count($productos) ?> Productos</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Vencimiento</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th class="text-center pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-box-open fa-2x mb-3 d-block"></i>
                                    No hay productos activos actualmente.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $p): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">#<?= $p['id'] ?></td>
                                    <td class="fw-bold text-dark"><?= htmlspecialchars($p['nombre']) ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['proveedor']) ?></span></td>
                                    <td><i class="fa-regular fa-calendar me-1 text-muted"></i> <?= $p['fechaVencimiento'] ?></td>
                                    <td class="fw-bold text-success">Bs. <?= number_format($p['precio'], 2) ?></td>
                                    <td>
                                        <span class="badge badge-stock <?= $p['stock'] > 10 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning-emphasis border border-warning-subtle' ?>">
                                            <?= $p['stock'] ?> unids.
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="index.php?edit_id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fa-solid fa-pen"></i> Editar
                                        </a>
                                        <form method="POST" action="index.php" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar lógicamente este producto?');">
                                            <input type="hidden" name="action" value="eliminar">
                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>