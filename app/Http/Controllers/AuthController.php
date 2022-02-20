<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/auth/usuarios/registro",
     *     summary="Nuevo usuario",
     *     description="Servicio para el registro de un nuevo usuario",
     *     operationId="usuarios/registro",
     *     tags={"Usuarios - Autenticación"},     
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent( required={"name", "email", "password"},
     *               @OA\Property(property="name", type="string", example="Johan", maxLength=150, description="Nombre del usuario a registrar"),
     *               @OA\Property(property="email", type="string", example="correo@ejemplo.com", description="Correo del usuario (utilizado para el login)"),
     *               @OA\Property(property="password", type="string", example="contrasena123456", description="Contraseña del usuario"),
     *          ),
     *      ),       
     *      @OA\Response(
     *          response=200,
     *          description="Transacción exitosa",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),     
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acceso denegado"
     *      ),
     *       @OA\Response(
     *           response=400,
     *           description="Petición no válida"
     *      ),
     *      @OA\Response(
     *           response=404,
     *           description="Servicio no encontrado"
     *       ),
     *       @OA\Response(
     *           response=500,
     *           description="Error de servidor"
     *       ),
     * )
     */

    /**
     * Función para el registro de un nuevo usuario
     * @author Johan Morales     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function registrarUsuario(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string'
            ]);

            $registro = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            return response()->json(['Estado' => true, 'Respuesta' => 'Registro de usuario exitoso', 'Data' => $registro], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/auth/usuarios/login",
     *     summary="Login",
     *     description="Servicio para la autenticación del usuario y la generación del token, que debe ser utilizado para consumir los Endpoints",
     *     operationId="usuarios/login",
     *     tags={"Usuarios - Autenticación"},     
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent( required={"email", "password"},     
     *               @OA\Property(property="email", type="string", example="correo@ejemplo.com", description="Correo registrado"),
     *               @OA\Property(property="password", type="string", example="contrasena123456", description="Contraseña registrada"),
     *          ),
     *      ),       
     *      @OA\Response(
     *          response=200,
     *          description="Transacción exitosa",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),     
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acceso denegado"
     *      ),
     *       @OA\Response(
     *           response=400,
     *           description="Petición no válida"
     *      ),
     *      @OA\Response(
     *           response=404,
     *           description="Servicio no encontrado"
     *       ),
     *       @OA\Response(
     *           response=500,
     *           description="Error de servidor"
     *       ),
     * )
     */

    /**
     * Función para el inicio de sesión
     * @author Johan Morales     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json(['Estado' => false, 'Respuesta' => 'Acceso no autorizado'], 401);
            }

            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();
            $authent = array(
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
            );

            return response()->json(['Estado' => true, 'Respuesta' => 'Autenticación exitosa', 'Data' => $authent], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }

    /**
     * Función para finalizar la sesión
     * @author Johan Morales     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function cerrarSesion(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return response()->json(['Estado' => true, 'Respuesta' => 'Cierre de Sesión exitoso'], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/auth/usuarios/info",
     *     summary="Información del usuario",
     *     description="Servicio para ver la información del usuario validado por el token que se está utilizando",
     *     operationId="usuarios/info",
     *     tags={"Usuarios - Autenticación"},  
     *     security={{"bearerAuth":{}}},   
     *     @OA\RequestBody(),       
     *      @OA\Response(
     *          response=200,
     *          description="Transacción exitosa",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),     
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acceso denegado"
     *      ),
     *       @OA\Response(
     *           response=400,
     *           description="Petición no válida"
     *      ),
     *      @OA\Response(
     *           response=404,
     *           description="Servicio no encontrado"
     *       ),
     *       @OA\Response(
     *           response=500,
     *           description="Error de servidor"
     *       ),
     * )
     */

    /**
     * Función para obtener información del usuario
     * @author Johan Morales     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function infoUsuario(Request $request)
    {
        try {
            return response()->json(['Estado' => true, 'Respuesta' => 'Información del usuario exitosa', 'Data' => $request->user()], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }
}
