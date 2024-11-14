// tienda.js




function rateProduct(productId, puntuacion) {
    $.post('rating.php', {
        producto_id: productId,
        puntuacion: puntuacion,
        usuario_email: "<?php echo $_SESSION['email']; ?>"
    }).done(response => {
        alert(response);
        highlightStars(productId, puntuacion);
    }).fail(() => alert("Error al registrar la puntuación."));
}

function highlightStars(productId, rating) {
    for (let i = 1; i <= 5; i++) {
        let star = document.getElementById(`star-${productId}-${i}`);
        star.classList.toggle('bi-star-fill', i <= rating);
        star.classList.toggle('bi-star', i > rating);
    }
}

function changeQuantity(change, productId, maxQuantity) {
    let quantityInput = document.getElementById(`quantity-${productId}`);
    let newQuantity = parseInt(quantityInput.value) + change;

    if (newQuantity >= 1 && newQuantity <= maxQuantity) {
        quantityInput.value = newQuantity;
    }
}

function addToCart(productId) {
    let selectedQuantity = parseInt(document.getElementById(`quantity-${productId}`).value);
    $.get('agregar_al_carrito.php', { id: productId, cantidad: selectedQuantity })
        .done(response => alert(response))
        .fail(() => alert("Error al añadir el producto al carrito."));
}
