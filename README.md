# ProyectoTech

Desarrollo realizado en prueba técnica, abarcando los siguientes puntos:
1. Generación de tablas para base de datos con migraciones.
2. CRUD productos.
3. Carrito de compra (Agregar / Ver resumen / Eliminar / Comprar).
4. Registro y autenticación de usuarios.
5. Carga masiva de productos desde CSV.
6. Token para consumir Endpoints.

## Para iniciar 🚀

Descargar el contenido del repositorio y seguir los pasos de instalación para su entorno local

### Pre-requisitos 📋
1. Contar con MySQL en el computador.
2. Crear una base de datos (se deja por defecto el nombre de la base <b>proyectotech</b>).
### Instalación 🔧

1. Copiar el contenido del archivo .env.example al archivo .env
2. Configurar credenciales de base de datos de acuerdo a la configuración del entorno local a utilizar.

3. Comando de instalación dependencias de acuerdo a lo que necesita el proyecto
```
composer update
```

4. Comando para ejecutar las migraciones creadas
```
php artisan migrate
```

5. Comando para ejecutar el seed creado para la tabla productos
```
php artisan db:seed
```

6. Ejecutar comando para el servidor incorporado de PHP
```
php artisan serve
```
7. La documentación del proyecto la podrá visualizar en la siguiente url: <a href="http://127.0.0.1:8000/api/documentation" target="_blank"> http://127.0.0.1:8000/api/documentation </a>
**Para visualizar la documentación el servidor incorporado de PHP debe esstar arriba**
**Por defecto carga por el puerto 8000, salvo alguna configugración específica del entorno local utilizado**
