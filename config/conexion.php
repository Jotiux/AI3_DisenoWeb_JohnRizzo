<?php
/**
 * Módulo de conexión a MySQL/MariaDB mediante PDO.
 *
 * - Las credenciales se leen de un archivo separado (credenciales.php).
 * - Los errores se capturan con try/catch: el detalle técnico se registra
 *   en el log del servidor y al usuario solo se le muestra un mensaje
 *   genérico (no se exponen credenciales ni mensajes del sistema).
 * - Se usa una única conexión por petición (patrón Singleton sencillo).
 */
class Conexion
{
    private static ?PDO $pdo = null;

    public static function obtener(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $archivo = __DIR__ . '/credenciales.php';
        if (!is_file($archivo)) {
            throw new RuntimeException('Falta el archivo de configuración de la base de datos.');
        }
        $cfg = require $archivo;

        $dsn = "mysql:host={$cfg['host']};port={$cfg['puerto']};dbname={$cfg['base']};charset=utf8mb4";

        try {
            self::$pdo = new PDO($dsn, $cfg['usuario'], $cfg['clave'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // errores como excepciones
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // filas como arreglos asociativos
                PDO::ATTR_EMULATE_PREPARES   => false,                  // sentencias preparadas reales
            ]);
        } catch (PDOException $e) {
            error_log('[Conexion] ' . $e->getMessage());   // detalle solo en el log
            throw new RuntimeException('No fue posible conectar con la base de datos.');
        }

        return self::$pdo;
    }
}
