<?php
// Evita que la vista se abra directamente sin pasar por index.php
if (!isset($datos, $errores)) {
    header('Location: ../index.php');
    exit;
}

/** Escapa cualquier valor antes de imprimirlo en HTML (prevención de XSS). */
function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/** Clase CSS y mensaje de error de un campo. */
function invalido(array $errores, string $campo): string
{
    return isset($errores[$campo]) ? ' is-invalid' : '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RegistroU · Gestión de Estudiantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/estilos.css" rel="stylesheet">
</head>
<body>
<header class="cabecera py-3 mb-4">
    <div class="container">
        <h1 class="h4 mb-0">RegistroU <span class="fw-light">· Gestión de Estudiantes</span></h1>
    </div>
</header>

<main class="container pb-5">
    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?= e($mensaje) ?></div>
    <?php endif; ?>
    <?php if (isset($errores['general'])): ?>
        <div class="alert alert-danger"><?= e($errores['general']) ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Formulario de registro (Create) -->
        <section class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Registrar estudiante</div>
                <div class="card-body">
                    <form action="index.php" method="POST" autocomplete="off">
                        <input type="hidden" name="accion" value="guardar">

                        <div class="mb-3">
                            <label for="nombres" class="form-label">Nombres</label>
                            <input type="text" id="nombres" name="nombres"
                                   class="form-control<?= invalido($errores, 'nombres') ?>"
                                   value="<?= e($datos['nombres']) ?>"
                                   required minlength="2" maxlength="60"
                                   pattern="[A-Za-zÁÉÍÓÚÜÑáéíóúüñ' \-]{2,60}"
                                   title="Solo letras y espacios (2 a 60)">
                            <div class="invalid-feedback"><?= e($errores['nombres'] ?? '') ?></div>
                        </div>

                        <div class="mb-3">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input type="text" id="apellidos" name="apellidos"
                                   class="form-control<?= invalido($errores, 'apellidos') ?>"
                                   value="<?= e($datos['apellidos']) ?>"
                                   required minlength="2" maxlength="60"
                                   pattern="[A-Za-zÁÉÍÓÚÜÑáéíóúüñ' \-]{2,60}"
                                   title="Solo letras y espacios (2 a 60)">
                            <div class="invalid-feedback"><?= e($errores['apellidos'] ?? '') ?></div>
                        </div>

                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input type="email" id="correo" name="correo"
                                   class="form-control<?= invalido($errores, 'correo') ?>"
                                   value="<?= e($datos['correo']) ?>"
                                   required maxlength="120" placeholder="nombre@ejemplo.com">
                            <div class="invalid-feedback"><?= e($errores['correo'] ?? '') ?></div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8 mb-3">
                                <label for="carrera" class="form-label">Carrera</label>
                                <select id="carrera" name="carrera" required
                                        class="form-select<?= invalido($errores, 'carrera') ?>">
                                    <option value="">Seleccione…</option>
                                    <?php foreach (Estudiante::CARRERAS as $carrera): ?>
                                        <option value="<?= e($carrera) ?>"
                                            <?= $datos['carrera'] === $carrera ? 'selected' : '' ?>><?= e($carrera) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback"><?= e($errores['carrera'] ?? '') ?></div>
                            </div>
                            <div class="col-sm-4 mb-3">
                                <label for="semestre" class="form-label">Semestre</label>
                                <input type="number" id="semestre" name="semestre"
                                       class="form-control<?= invalido($errores, 'semestre') ?>"
                                       value="<?= e($datos['semestre']) ?>"
                                       required min="1" max="10" step="1">
                                <div class="invalid-feedback"><?= e($errores['semestre'] ?? '') ?></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Guardar</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</main>
</body>
</html>
