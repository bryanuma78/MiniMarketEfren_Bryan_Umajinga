<?php
session_start();
if (!isset($_SESSION['productos'])) {
    $_SESSION['productos'] = [];
}

function validarDatos($nombre, $precio, $cantidad) {
    $errores = [];
    if (empty($nombre)) {
        $errores[] = 'El nombre del producto es obligatorio.';
    }
    if (!is_numeric($precio) || $precio <= 0) {
        $errores[] = 'El precio debe ser un número positivo.';
    }
    if (!is_numeric($cantidad) || $cantidad < 0) {
        $errores[] = 'La cantidad debe ser un número no negativo.';
    }
    return $errores;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['product-name'];
    $precio = $_POST['unit-price'];
    $cantidad = $_POST['inventory-quantity'];

    $errores = validarDatos($nombre, $precio, $cantidad);
    if (empty($errores)) {
        $producto = [
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => $cantidad,
            'valor_total' => $precio * $cantidad,
            'estado' => $cantidad > 0 ? 'Stock' : 'Agotado'
        ];
        $_SESSION['productos'][] = $producto;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Productos</title>
    <link href="../public/css/tailwind.css" rel="stylesheet">
    <script src="../public/js/marker.js"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Administración de Productos</h2>

        <!-- Mostrar errores -->
        <?php if (!empty($errores)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php foreach ($errores as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para agregar productos -->
        <form id="product-form" action="" method="POST" class="mb-8">
            <div class="flex flex-col space-y-4">
                <div class="flex flex-col">
                    <label for="product-name" class="text-gray-700 mb-2">Nombre del Producto</label>
                    <input type="text" id="product-name" name="product-name" class="border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="flex flex-col">
                    <label for="unit-price" class="text-gray-700 mb-2">Precio por Unidad</label>
                    <input type="number" id="unit-price" name="unit-price" class="border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" step="0.01" required>
                </div>
                <div class="flex flex-col">
                    <label for="inventory-quantity" class="text-gray-700 mb-2">Cantidad de Inventario</label>
                    <input type="number" id="inventory-quantity" name="inventory-quantity" class="border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Agregar Producto</button>
        </form>

        <!-- Tabla para mostrar los productos -->
        <table id="product-table" class="min-w-full bg-white border border-gray-200 rounded-md overflow-hidden mb-4">
            <thead class="bg-gray-100">
                <tr class="text-left text-gray-700">
                    <th class="px-4 py-2">Nombre del Producto</th>
                    <th class="px-4 py-2">Precio por Unidad</th>
                    <th class="px-4 py-2">Cantidad en Inventario</th>
                    <th class="px-4 py-2">Valor Total</th>
                    <th class="px-4 py-2">Estado</th>
                </tr>
            </thead>
            <tbody id="product-body">
                <?php if (!empty($_SESSION['productos'])): ?>
                    <?php foreach ($_SESSION['productos'] as $producto): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($producto['precio']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($producto['valor_total']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($producto['estado']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="border px-4 py-2 text-center" colspan="5">No hay productos para mostrar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
