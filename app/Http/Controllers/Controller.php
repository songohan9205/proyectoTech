<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="ProyectoTech",
 *      description="Documentación proyectoTech (CRUD de productos y carga masiva - Carrito de Compras)",
 *      @OA\Contact(
 *          email="johan33@hotmail.es"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Desarrollo ProyectoTech"
 * )
 *      @OA\SecurityScheme(
 *          securityScheme="bearerAuth",
 *          in="header",
 *          name="bearerAuth",
 *          type="http",
 *          scheme="bearer",
 *          bearerFormat="JWT",
 *      ),  
 * @OA\Tag(
 *   name="Usuarios - Autenticación",
 *   description="Registro, login e información de usuario"
 * ),
 * @OA\Tag(
 *   name="Productos",
 *   description="CRUD de productos y carga masiva"
 * ),
 * @OA\Tag(
 *   name="Carrito",
 *   description="Agregar, eliminar, resumen y compra del carrito de compras"
 * ),
  * @OA\Tag(
 *   name="Informes",
 *   description="Sección de reportes"
 * ),   
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
