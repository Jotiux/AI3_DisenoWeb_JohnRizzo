<?php
/**
 * Plantilla de credenciales de la base de datos.
 *
 * Uso: copie este archivo como "credenciales.php" (misma carpeta) y ajuste
 * los valores a su entorno. "credenciales.php" está excluido en .gitignore
 * para no publicar usuarios ni contraseñas en GitHub.
 */
return [
    'host'    => '127.0.0.1',
    'puerto'  => 3306,
    'base'    => 'db_sistema_web',
    'usuario' => 'root',
    'clave'   => '',          // XAMPP trae root sin contraseña por defecto
];
