<!-- uuidv4.php -->



<?php
    function generateUUIDv4() {
        // Generamos un valor aleatorio en formato hexadecimal
        $data = random_bytes(16);  // 16 bytes = 128 bits
        
        // Establecer el bit de versión a 0100 para UUIDv4
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);  // versión 4
        // Establecer el bit de variante a 10xx para que sea un UUID de variante RFC 4122
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);  // variante DCE
        
        // Convertir los bytes a un formato UUID
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    // Ejemplo de uso
    $uuid = generateUUIDv4();
    echo "UUID generado: " . $uuid;
?>