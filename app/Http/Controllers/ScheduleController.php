<?php

namespace App\Http\Controllers;

use App\Domain\Schedule\Data\SearchScheduleDto;
use App\Domain\Schedule\Models\Schedule;
use App\Domain\Schedule\UseCases\CreateScheduleUseCase;
use App\Domain\Schedule\UseCases\DeleteScheduleUseCase;
use App\Domain\Schedule\UseCases\GetScheduleUseCase;
use App\Domain\Schedule\UseCases\UpdateScheduleUseCase;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Schedule",
 *     description="Endpoints relacionados ao agendamento."
 * )
 * */
class ScheduleController extends Controller
{
    public function __construct(
        private readonly CreateScheduleUseCase $createScheduleUseCase,
        private readonly GetScheduleUseCase $getScheduleUseCase,
        private readonly UpdateScheduleUseCase $updateScheduleUseCase,
        private readonly DeleteScheduleUseCase $deleteScheduleUseCase
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/auth/schedules",
     *     tags={"Schedule"},
     *     summary="Recupera dados de agendamento",
     *     description="Este endpoint permite recuperar dados de agendamento com base em filtros opcionais.",
     *     @OA\Response(
     *         response=200,
     *         description="Dados de agendamento recuperados com sucesso."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado. O usuário não está autenticado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado."
     *     ),
     *     @OA\Parameter(
     *         name="starts_at",
     *         in="query",
     *         description="Data de início para filtrar os agendamentos (opcional).",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="ends_at",
     *         in="query",
     *         description="Data de término para filtrar os agendamentos (opcional).",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Título para filtrar os agendamentos (opcional).",
     *         @OA\Schema(type="string")
     *     ),
     *     security={{ "bearer":{} }}
     * )
     */
    public function index()
    {
        $userId = Auth::user()->id;
        if (!$userId) {
            throw new UserNotFoundException;
        }
        $starts = request()->query('starts_at') ? new Carbon(request()->query('starts_at')) : null;
        $ends = request()->query('ends_at') ? new Carbon(request()->query('ends_at')) : null;

        $searchDto = new SearchScheduleDto(
            $userId,
            request()->query('title', null),
            $starts,
            $ends,
        );
        return response()->json($this->getScheduleUseCase->handle($searchDto));
    }

    /**
     * @OA\Post(
     *     path="/api/auth/schedules",
     *     summary="Cria um novo agendamento",
     *     description="Este endpoint permite criar um novo agendamento com os campos especificados.",
     *     tags={"Schedule"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", description="Título do agendamento"),
     *             @OA\Property(property="description", type="string", description="Descrição do agendamento"),
     *             @OA\Property(property="user_id", type="integer", description="ID do usuário associado ao agendamento"),
     *             @OA\Property(property="starts_at", type="string", format="date-time", description="Data e hora de início do agendamento (Formato: YYYY-MM-DD HH:MM:SS)"),
     *             @OA\Property(property="ends_at", type="string", format="date-time", nullable=true, description="Data e hora de término do agendamento (Formato: YYYY-MM-DD HH:MM:SS) - Opcional"),
     *             @OA\Property(property="status", type="string", description="Status do agendamento"),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Data e hora de criação do agendamento (Formato: YYYY-MM-DD HH:MM:SS)"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Data e hora de atualização do agendamento (Formato: YYYY-MM-DD HH:MM:SS)"),
     *             @OA\Property(property="id", type="integer", description="ID do agendamento criado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Agendamento criado com sucesso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", description="Título do agendamento"),
     *             @OA\Property(property="description", type="string", description="Descrição do agendamento"),
     *             @OA\Property(property="user_id", type="integer", description="ID do usuário associado ao agendamento"),
     *             @OA\Property(property="starts_at", type="string", format="date-time", description="Data e hora de início do agendamento (Formato: YYYY-MM-DD HH:MM:SS)"),
     *             @OA\Property(property="ends_at", type="string", format="date-time", nullable=true, description="Data e hora de término do agendamento (Formato: YYYY-MM-DD HH:MM:SS) - Opcional"),
     *             @OA\Property(property="status", type="string", description="Status do agendamento"),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Data e hora de criação do agendamento (Formato: YYYY-MM-DD HH:MM:SS)"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Data e hora de atualização do agendamento (Formato: YYYY-MM-DD HH:MM:SS)"),
     *             @OA\Property(property="id", type="integer", description="ID do agendamento criado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação. Verifique os campos fornecidos."
     *     ),
     *     security={{ "bearer":{} }}
     * )
     */
    public function store(StoreScheduleRequest $request)
    {
        return response()->json($this->createScheduleUseCase->handle($request->validated()));
    }

    /**
     * @OA\Get(
     *     path="/api/auth/schedules/{schedule}",
     *     summary="Recupera detalhes de um agendamento",
     *     description="Este endpoint permite recuperar detalhes de um agendamento específico.",
     *     tags={"Schedule"},
     *     @OA\Parameter(
     *         name="schedule",
     *         in="path",
     *         description="ID do agendamento",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do agendamento recuperados com sucesso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="ID do agendamento"),
     *             @OA\Property(property="title", type="string", description="Título do agendamento"),
     *             @OA\Property(property="description", type="string", description="Descrição do agendamento"),
     *             @OA\Property(property="status", type="string", description="Status do agendamento"),
     *             @OA\Property(property="user_id", type="integer", description="ID do usuário associado ao agendamento"),
     *             @OA\Property(property="starts_at", type="string", format="date-time", description="Data e hora de início do agendamento (Formato: YYYY-MM-DD HH:MM:SS)"),
     *             @OA\Property(property="ends_at", type="string", format="date-time", description="Data e hora de término do agendamento (Formato: YYYY-MM-DD HH:MM:SS) - Opcional"),
     *             @OA\Property(property="finished_at", type="string", format="date-time", nullable=true, description="Data e hora de finalização do agendamento (Formato: YYYY-MM-DD HH:MM:SS) - Opcional"),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Data e hora de criação do agendamento (Formato: YYYY-MM-DD HH:MM:SS)"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Data e hora de atualização do agendamento (Formato: YYYY-MM-DD HH:MM:SS)"),
     *             @OA\Property(property="user", type="object", description="Detalhes do usuário associado ao agendamento",
     *                 @OA\Property(property="id", type="integer", description="ID do usuário"),
     *                 @OA\Property(property="name", type="string", description="Nome do usuário"),
     *                 @OA\Property(property="email", type="string", description="Email do usuário"),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, description="Data e hora de verificação do email do usuário - Opcional"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", description="Data e hora de criação do usuário (Formato: YYYY-MM-DD HH:MM:SS)"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Data e hora de atualização do usuário (Formato: YYYY-MM-DD HH:MM:SS)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Agendamento não encontrado."
     *     ),
     *     security={{ "bearer":{} }}
     * )
     */
    public function show(Schedule $schedule)
    {
        return response()->json($schedule->load(['user']));
    }

    /**
     * @OA\Put(
     *     path="/api/auth/schedules/{schedule}",
     *     summary="Atualiza um agendamento existente",
     *     description="Este endpoint permite atualizar um agendamento existente com os campos especificados.",
     *     tags={"Schedule"},
     *     @OA\Parameter(
     *         name="schedule",
     *         in="path",
     *         description="ID do agendamento",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="ID do agendamento a ser atualizado"),
     *             @OA\Property(property="title", type="string", maxLength=40, description="Título do agendamento"),
     *             @OA\Property(property="description", type="string", maxLength=255, description="Descrição do agendamento"),
     *             @OA\Property(property="starts_at", type="string", format="date-time", description="Data e hora de início do agendamento (Formato: YYYY-MM-DD HH:MM:SS) - Opcional"),
     *             @OA\Property(property="ends_at", type="string", format="date-time", description="Data e hora de término do agendamento (Formato: YYYY-MM-DD HH:MM:SS) - Opcional"),
     *             @OA\Property(property="status", type="string", description="Status do agendamento"),
     *             @OA\Property(property="user_id", type="integer", description="ID do usuário associado ao agendamento"),
     *             @OA\Property(property="finished_at", type="string", format="date-time", nullable=true, description="Data e hora de finalização do agendamento (Formato: YYYY-MM-DD HH:MM:SS) - Opcional"),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Data e hora de criação do agendamento (Formato: YYYY-MM-DD HH:MM:SS)"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Data e hora de atualização do agendamento (Formato: YYYY-MM-DD HH:MM:SS)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agendamento atualizado com sucesso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="ID do agendamento atualizado"),
     *             @OA\Property(property="title", type="string", description="Título do agendamento"),
     *             @OA\Property(property="description", type="string", description="Descrição do agendamento"),
     *             @OA\Property(property="status", type="string", description="Status do agendamento"),
     *             @OA\Property(property="user_id", type="integer", description="ID do usuário associado ao agendamento"),
     *             @OA\Property(property="starts_at", type="string", format="date-time", description="Data e hora de início do agendamento (Formato: YYYY-MM-DD HH:MM:SS) - Opcional"),
     *             @OA\Property(property="ends_at", type="string", format="date-time", nullable=true, description="Data e hora de término do agendamento (Formato: YYYY-MM-DD HH:MM:SS) - Opcional"),
     *             @OA\Property(property="finished_at", type="string", format="date-time", nullable=true, description="Data e hora de finalização do agendamento (Formato: YYYY-MM-DD HH:MM:SS) - Opcional"),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Data e hora de criação do agendamento (Formato: YYYY-MM-DD HH:MM:SS)"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Data e hora de atualização do agendamento (Formato: YYYY-MM-DD HH:MM:SS)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Agendamento não encontrado."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação. Verifique os campos fornecidos."
     *     ),
     *     security={{ "bearer ":{} }}
     * )
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        return response()->json($this->updateScheduleUseCase->handle($schedule->id, $request->validated()));
    }

    /**
     * @OA\Delete(
     *     path="/api/auth/schedules/{schedule}",
     *     summary="Remove um agendamento existente",
     *     description="Este endpoint permite remover um agendamento existente.",
     *     tags={"Schedule"},
     *     @OA\Parameter(
     *         name="schedule",
     *         in="path",
     *         description="ID do agendamento a ser removido",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agendamento removido com sucesso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Agendamento removido com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Agendamento não encontrado."
     *     ),
     *     security={{ "bearer ":{} }}
     * )
     */
    public function destroy(Schedule $schedule)
    {
        $this->deleteScheduleUseCase->handle($schedule->id);
        return response()->json(['message' => 'Agendamento removido com sucesso.']);
    }
}
