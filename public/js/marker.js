document.addEventListener('DOMContentLoaded', function() {
    const productForm = document.getElementById('product-form');
    const productTableBody = document.getElementById('product-body');

    productForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const nombre = document.getElementById('product-name').value;
        const precio = document.getElementById('unit-price').value;
        const cantidad = document.getElementById('inventory-quantity').value;

        agregarProducto(nombre, precio, cantidad);
    });

    function agregarProducto(nombre, precio, cantidad) {
        const formData = new FormData();
        formData.append('product-name', nombre);
        formData.append('unit-price', precio);
        formData.append('inventory-quantity', cantidad);

        fetch('/index.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            document.body.innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});