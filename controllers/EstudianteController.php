<?php
/**
 * Controlador: recibe la petición, valida en el servidor, llama al modelo
 * y entrega los datos a la vista.
 */
require_once __DIR__ . '/../models/Estudiante.php';

class EstudianteController
{
    private const VACIO = ['nombres' => '', 'apellidos' => '', 'correo' => '', 'carrera' => '', 'semestre' => ''];

    /** GET: muestra el formulario y la lista de estudiantes. */
    public function index(): void
    {
        $datos   = self::VACIO;
        $errores = [];
        $mensaje = isset($_GET['ok']) ? 'Estudiante registrado correctamente.' : null;

        $this->mostrar($datos, $errores, $mensaje);
    }

    /** POST: valida y guarda. Si todo va bien redirige (patrón Post/Redirect/Get). */
    public function guardar(): void
    {
        [$datos, $errores] = $this->validar($_POST);

        if (!$errores) {
            try {
                (new Estudiante())->insertar($datos);
                header('Location: index.php?ok=1');
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') {            // violación de UNIQUE (correo)
                    $errores['correo'] = 'Este correo ya está registrado.';
                } else {
                    error_log('[EstudianteController] ' . $e->getMessage());
                    $errores['general'] = 'No se pudo guardar el registro. Intente más tarde.';
                }
            } catch (RuntimeException $e) {                 // error de conexión (mensaje genérico)
                $errores['general'] = $e->getMessage();
            }
        }

        $this->mostrar($datos, $errores, null);
    }

    /** Consulta los registros (Read) y carga la vista. */
    private function mostrar(array $datos, array $errores, ?string $mensaje): void
    {
        try {
            $estudiantes = (new Estudiante())->obtenerTodos();
        } catch (RuntimeException $e) {                     // incluye PDOException
            error_log('[EstudianteController] ' . $e->getMessage());
            $estudiantes = [];
            $errores['general'] ??= 'No fue posible conectar con la base de datos.';
        }

        require __DIR__ . '/../vistas/estudiantes.php';
    }

    /**
     * Validación del lado del servidor (no se confía en la del navegador,
     * porque puede omitirse). Devuelve [datos limpios, errores].
     */
    private function validar(array $entrada): array
    {
        $datos = [
            'nombres'   => trim((string) ($entrada['nombres']   ?? '')),
            'apellidos' => trim((string) ($entrada['apellidos'] ?? '')),
            'correo'    => mb_strtolower(trim((string) ($entrada['correo'] ?? ''))),
            'carrera'   => trim((string) ($entrada['carrera']   ?? '')),
            'semestre'  => trim((string) ($entrada['semestre']  ?? '')),
        ];
        $errores = [];

        $patronNombre = "/^[\p{L} '-]{2,60}$/u";   // letras (con tildes/ñ), espacios, ' y -
        if (!preg_match($patronNombre, $datos['nombres'])) {
            $errores['nombres'] = 'Ingrese de 2 a 60 letras.';
        }
        if (!preg_match($patronNombre, $datos['apellidos'])) {
            $errores['apellidos'] = 'Ingrese de 2 a 60 letras.';
        }
        if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL) || mb_strlen($datos['correo']) > 120) {
            $errores['correo'] = 'Ingrese un correo válido.';
        }
        if (!in_array($datos['carrera'], Estudiante::CARRERAS, true)) {
            $errores['carrera'] = 'Seleccione una carrera de la lista.';
        }
        $semestre = filter_var($datos['semestre'], FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1, 'max_range' => 10]]);
        if ($semestre === false) {
            $errores['semestre'] = 'El semestre debe ser un número entre 1 y 10.';
        } else {
            $datos['semestre'] = $semestre;
        }

        return [$datos, $errores];
    }
}
