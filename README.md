# ProyectoTech

Desarrollo realizado en prueba t茅cnica, abarcando los siguientes puntos:
1. Generaci贸n de tablas para base de datos con migraciones.
2. CRUD productos.
3. Carrito de compra (Agregar / Ver resumen / Eliminar / Comprar).
4. Registro y autenticaci贸n de usuarios.
5. Carga masiva de productos desde CSV.
6. Token para consumir Endpoints.

## Para iniciar 馃殌

Descargar el contenido del repositorio y seguir los pasos de instalaci贸n para su entorno local

### Pre-requisitos 馃搵
1. Contar con MySQL en el computador.
2. Crear una base de datos (se deja por defecto el nombre de la base <b>proyectotech</b>).
### Instalaci贸n 馃敡

1. Copiar el contenido del archivo .env.example al archivo .env
2. Configurar credenciales de base de datos de acuerdo a la configuraci贸n del entorno local a utilizar.

3. Comando de instalaci贸n dependencias de acuerdo a lo que necesita el proyecto
```
composer update
```

4. Comando para ejecutar las migraciones creadas
```
php artisan migrate
```

5. Comando para generar las llaves
```
php artisan passport:install
```

6. Comando para ejecutar el seed creado para la tabla productos
```
php artisan db:seed
```

7. Ejecutar comando para el servidor incorporado de PHP
```
php artisan serve
```
8. La documentaci贸n del proyecto la podr谩 visualizar en la siguiente url: <a href="http://127.0.0.1:8000/api/documentation" target="_blank"> http://127.0.0.1:8000/api/documentation </a>
**Para visualizar la documentaci贸n el servidor incorporado de PHP debe esstar arriba.**<br>
**Por defecto carga por el puerto 8000, salvo alguna configuraci贸n espec铆fica del entorno local utilizado.**
