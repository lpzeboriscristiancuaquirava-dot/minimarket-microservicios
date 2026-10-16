# Sistema de Microservicios - Gestión de Productos (Minimarket)

Sistema basado en una arquitectura de microservicios para la gestión de productos de un minimarket. La solución integra una base de datos MySQL desplegada en un contenedor Docker, un microservicio backend desarrollado con Quarkus para la API REST, y un microservicio frontend desarrollado en PHP que consume dicha API.

## Requisitos Previos
- Docker y Docker Compose
- Java 17 o superior
- PHP 8.x

## Estructura del Proyecto
- `docker-compose.yml`: Archivo de configuración para crear y ejecutar el contenedor de MySQL con persistencia de datos.
- `init.sql`: Script SQL para la creación de la base de datos `db_minimarket_pro413` y la estructura obligatoria de la tabla `Producto`.
- `/backend`: Microservicio API REST en Quarkus (Java) que gestiona el CRUD y la eliminación lógica.
- `/frontend`: Aplicación web en PHP que consume exclusivamente los endpoints REST del backend.

## Pasos para la Puesta en Marcha

### 1. Levantar la Base de Datos (MySQL en Docker)
Ejecutar en la raíz del proyecto para iniciar el contenedor de MySQL en el puerto `3307`:
```bash
docker-compose up -d
2. Iniciar el Backend (Quarkus)
En una terminal, ingresar a la carpeta del backend y ejecutar el servidor en modo desarrollo:

Bash
cd backend
.\mvnw quarkus:dev
El servicio estará disponible y exponiendo la API REST en: http://localhost:8080/api/productos

3. Iniciar el Frontend (PHP)
En otra terminal independiente, ingresar a la carpeta del frontend e iniciar el servidor de desarrollo de PHP:

Bash
cd frontend
php -S localhost:8000
Abrir un navegador web e ingresar a: http://localhost:8000

Funcionalidades del Sistema
Consultar: Muestra únicamente los productos activos (eliminado = 0).

Registrar: Permite agregar un producto nuevo mediante el formulario.

Editar: Permite actualizar los campos de un producto existente.

Eliminación Lógica: Al eliminar un producto desde la interfaz web, se cambia su estado a eliminado = 1 en la base de datos MySQL sin borrar físicamente el registro.