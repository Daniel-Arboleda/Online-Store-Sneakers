document.getElementById('botonPago').addEventListener('click', function() {
    fetch('/config/paypal/createPayment.php', { method: 'POST' })
    .then(response => response.json())
    .then(data => {
        if (data.approval_url) {
            window.location.href = data.approval_url;
        } else {
            alert("Error: " + data.error);
        }
    });
});
