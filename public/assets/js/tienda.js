// tienda.js

function rateProduct(productId, puntuacion) {
    console.log(`rateProduct - Inicio: productId=${productId}, puntuacion=${puntuacion}`);
    $.post('rating.php', {
        producto_id: productId,
        puntuacion: puntuacion,
        usuario_email: "<?php echo $_SESSION['email']; ?>"
    })
    .done(response => {
        console.log(`rateProduct - Success: response=${response}`);
        highlightStars(productId, puntuacion);
    })
    .fail(() => {
        console.error("rateProduct - Error al registrar la puntuación.");
    });
}

function highlightStars(productId, rating) {
    console.log(`highlightStars - Inicio: productId=${productId}, rating=${rating}`);
    for (let i = 1; i <= 5; i++) {
        let star = document.getElementById(`star-${productId}-${i}`);
        if (star) {
            star.classList.toggle('bi-star-fill', i <= rating);
            star.classList.toggle('bi-star', i > rating);
            console.log(`highlightStars - Actualizando estrella ${i}:`, star.className);
        } else {
            console.warn(`highlightStars - Estrella no encontrada: star-${productId}-${i}`);
        }
    }
}

function changeQuantity(change, productId, maxQuantity) {
    console.log(`changeQuantity - Inicio: change=${change}, productId=${productId}, maxQuantity=${maxQuantity}`);
    let quantityInput = document.getElementById(`quantity-${productId}`);
    if (!quantityInput) {
        console.error(`changeQuantity - Input de cantidad no encontrado: quantity-${productId}`);
        return;
    }

    let currentQuantity = parseInt(quantityInput.value);
    let newQuantity = currentQuantity + change;
    console.log(`changeQuantity - currentQuantity=${currentQuantity}, newQuantity=${newQuantity}`);

    if (newQuantity >= 1 && newQuantity <= maxQuantity) {
        quantityInput.value = newQuantity;
        console.log(`changeQuantity - Nueva cantidad actualizada: ${newQuantity}`);
    } else {
        console.warn("changeQuantity - Nueva cantidad fuera de límites permitidos");
    }
}

function addToCart(productId) {
    console.log(`addToCart - Inicio: productId=${productId}`);
    let quantityInput = document.getElementById(`quantity-${productId}`);
    if (!quantityInput) {
        console.error(`addToCart - Input de cantidad no encontrado: quantity-${productId}`);
        return;
    }

    let selectedQuantity = parseInt(quantityInput.value);
    console.log(`addToCart - selectedQuantity=${selectedQuantity}`);
    
    $.get('agregar_al_carrito.php', { id: productId, cantidad: selectedQuantity })
        .done(response => {
            console.log(`addToCart - Success: response=${response}`);
        })
        .fail(() => {
            console.error("addToCart - Error al añadir el producto al carrito.");
        });
}

console.log("Archivo tienda.js cargado correctamente");
