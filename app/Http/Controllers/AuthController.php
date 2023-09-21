<?php

namespace App\Http\Controllers;

use App\Domain\User\UseCases\AuthenticateUseCase;
use App\Domain\User\UseCases\RegisterUseCase;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

/**
 * @OA\Tag(
 *     name="Authenticate",
 *     description="Endpoints para authenticacao."
 * )
 * */
class AuthController extends Controller
{
    public function __construct(
        private readonly AuthenticateUseCase $authenticateUseCase,
        private readonly RegisterUseCase $registerUseCase
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Realiza o login do usuário",
     *     description="Este endpoint permite que um usuário faça o login fornecendo um email e uma senha válidos.",
     *     tags={"Authenticate"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", description="Email do usuário"),
     *             @OA\Property(property="password", type="string", description="Senha do usuário")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido. Retorna um token de autenticação.",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", description="Token de acesso"),
     *             @OA\Property(property="token_type", type="string", description="Tipo de token (Bearer)"),
     *             @OA\Property(property="expires_in", type="integer", description="Tempo de expiração do token em segundos"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas. Falha na autenticação."
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        return response()->json($this->authenticateUseCase->handle($request->email, $request->password));
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registra um novo usuário",
     *     description="Este endpoint permite que um novo usuário seja registrado fornecendo informações necessárias, como nome, email e senha.",
     *     tags={"Authenticate"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, description="Nome do usuário"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, description="Email do usuário"),
     *             @OA\Property(property="password", type="string", minLength=6, maxLength=255, description="Senha do usuário"),
     *             @OA\Property(property="password_confirmation", type="string", description="Confirmação da senha do usuário")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso. Retorna um token de autenticação.",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", description="Token de acesso"),
     *             @OA\Property(property="token_type", type="string", description="Tipo de token (Bearer)"),
     *             @OA\Property(property="expires_in", type="integer", description="Tempo de expiração do token em segundos"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação. Verifique os campos fornecidos."
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        return response()->json($this->registerUseCase->handle($request->validated()));
    }
}
