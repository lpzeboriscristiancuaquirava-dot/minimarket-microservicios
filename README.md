# Sistema de Microservicios - Gestion de Productos (Minimarket)

Sistema basado en arquitectura de microservicios para la gestion de productos de un minimarket.

## Requisitos
- Docker y Docker Compose
- Java 17+ / Maven
- PHP 8.x

## Estructura del Proyecto
- `docker-compose.yml`: Contenedor MySQL (Puerto local: 3307).
- `init.sql`: Script de la base de datos `db_minimarket_pro413` y la tabla `Producto`.
- `/backend`: API REST en Quarkus.
- `/frontend`: Aplicación Web en PHP.

## Pasos para ejecutar el proyecto

### 1. Base de Datos (MySQL en Docker)
```bash
docker-compose up -d