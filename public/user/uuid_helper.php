<!-- uuid_helper.php -->



<?php

function generarUUIDv4() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), // 32 bits
        mt_rand(0, 0xffff), // 16 bits
        mt_rand(0, 0x0fff) | 0x4000, // 16 bits ajustados para versión 4
        mt_rand(0, 0x3fff) | 0x8000, // 16 bits ajustados para variante
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff) // 48 bits
    );
}
?>