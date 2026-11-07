<?php
/**
 * Modelo Estudiante: única capa que accede a la tabla `estudiantes`.
 * Todas las consultas usan sentencias preparadas de PDO.
 */
require_once __DIR__ . '/../config/conexion.php';

class Estudiante
{
    /** Carreras permitidas (lista blanca usada en la vista y en la validación). */
    public const CARRERAS = [
        'Ingeniería en Sistemas Inteligentes',
        'Ingeniería Industrial',
        'Administración de Empresas',
        'Derecho',
        'Psicología',
    ];

    private PDO $db;

    public function __construct()
    {
        $this->db = Conexion::obtener();
    }

    /**
     * Inserta un estudiante. Los datos viajan separados de la instrucción SQL
     * (marcadores :nombre), por lo que no pueden alterar la consulta: esto
     * previene la inyección SQL.
     */
    public function insertar(array $datos): bool
    {
        $sql = 'INSERT INTO estudiantes (nombres, apellidos, correo, carrera, semestre)
                VALUES (:nombres, :apellidos, :correo, :carrera, :semestre)';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nombres',   $datos['nombres'],   PDO::PARAM_STR);
        $stmt->bindValue(':apellidos', $datos['apellidos'], PDO::PARAM_STR);
        $stmt->bindValue(':correo',    $datos['correo'],    PDO::PARAM_STR);
        $stmt->bindValue(':carrera',   $datos['carrera'],   PDO::PARAM_STR);
        $stmt->bindValue(':semestre',  $datos['semestre'],  PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Devuelve todos los estudiantes, del más reciente al más antiguo.
     * La consulta no recibe datos del usuario; igual se ejecuta con prepare().
     */
    public function obtenerTodos(): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, nombres, apellidos, correo, carrera, semestre, fecha_creacion
               FROM estudiantes
              ORDER BY id DESC'
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
