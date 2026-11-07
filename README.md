# RegistroU · Gestión de Estudiantes

Módulo web full-stack desarrollado en **PHP (arquitectura MVC)** con base de datos **MySQL/MariaDB** y acceso a datos mediante **PDO**. Permite registrar estudiantes (Create) y consultarlos en una tabla responsiva (Read), aplicando buenas prácticas de seguridad contra **inyección SQL** y **XSS**.

> Proyecto académico AI3 · Programación de Sistemas Web · Universidad ECOTEC
> Autor: John W. Rizzo V.

## Tecnologías y versiones

| Componente | Versión |
|---|---|
| XAMPP (Windows) | Control Panel v3.3.0 |
| Apache | 2.4.65 |
| PHP | 8.3.36 (extensiones `pdo_mysql` y `mbstring`) |
| MariaDB | 10.11.16 |
| phpMyAdmin | 5.2.3 |
| Bootstrap (CDN) | 5.3.3 |

## Estructura del proyecto

```
AI3_DisenoWeb_JohnRizzo/
├── index.php                      # Punto de entrada único (front controller)
├── config/
│   ├── conexion.php               # Clase Conexion (PDO + try/catch)
│   ├── credenciales.example.php   # Plantilla de credenciales (se publica)
│   └── credenciales.php           # Credenciales reales (excluido por .gitignore)
├── controllers/
│   └── EstudianteController.php   # Validación en servidor y flujo de la petición
├── models/
│   └── Estudiante.php             # Consultas con sentencias preparadas
├── vistas/
│   └── estudiantes.php            # Formulario y tabla (salida con htmlspecialchars)
├── public/css/estilos.css         # Estilos propios
└── database/db_sistema_web.sql    # Script DDL exportado desde phpMyAdmin
```

## Despliegue local (paso a paso)

1. Instale **XAMPP** e inicie **Apache** y **MySQL** desde el XAMPP Control Panel.
2. Clone el repositorio dentro de `C:\xampp\htdocs\`:
   ```bash
   git clone https://github.com/Jotiux/AI3_DisenoWeb_JohnRizzo.git
   ```
3. Abra `http://localhost/phpmyadmin/`, vaya a **Importar**, seleccione `database/db_sistema_web.sql` y haga clic en **Importar**. Se crea la base `db_sistema_web` con la tabla `estudiantes`.
4. En la carpeta `config/`, copie `credenciales.example.php` como **`credenciales.php`** y ajuste usuario/contraseña si su MySQL no usa los valores por defecto de XAMPP (`root` sin contraseña).
5. Abra en el navegador: `http://localhost/AI3_DisenoWeb_JohnRizzo/`

## Seguridad implementada

- **Inyección SQL:** todas las consultas usan sentencias preparadas de PDO (`prepare` + `bindValue` + `execute`) y `PDO::ATTR_EMULATE_PREPARES = false`.
- **XSS:** toda salida a HTML pasa por `htmlspecialchars($valor, ENT_QUOTES, 'UTF-8')`.
- **Validación doble:** en el cliente (HTML5: `required`, `pattern`, `type="email"`, `min`/`max`) y en el servidor (PHP: expresiones regulares, `filter_var`, lista blanca de carreras).
- **Manejo de errores:** `try/catch` en la conexión; el detalle técnico se registra con `error_log` y al usuario solo se muestra un mensaje genérico.
- **Credenciales fuera del repositorio:** `config/credenciales.php` está excluido mediante `.gitignore`.
