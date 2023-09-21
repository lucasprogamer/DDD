<?php

namespace Tests\Unit\Domain\Schedule\UseCases;

use App\Domain\Schedule\Enums\ScheduleStatusEnum;
use App\Domain\Schedule\Exceptions\ScheduleAlreadyExistsException;
use App\Domain\Schedule\Interfaces\ScheduleRepositoryInterface;
use App\Domain\Schedule\Models\Schedule;
use App\Domain\Schedule\UseCases\CheckIfScheduleExistsUseCase;
use App\Domain\Schedule\UseCases\CheckWeekendUseCase;
use App\Domain\Schedule\UseCases\UpdateScheduleUseCase;
use Mockery;
use Tests\TestCase;

class UpdateScheduleUseCaseTest extends TestCase
{
    protected $scheduleRepository;
    protected $checkIfScheduleExists;
    protected $checkWeekendUseCase;

    public function setUp(): void
    {
        parent::setUp();

        $this->scheduleRepository = Mockery::mock(ScheduleRepositoryInterface::class);
        $this->checkIfScheduleExists = new CheckIfScheduleExistsUseCase($this->scheduleRepository);
        $this->checkWeekendUseCase = new CheckWeekendUseCase;
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testHandleUpdatesSchedule()
    {
        $id = 1;
        $params =  [
            'starts_at' => '2023-09-22 10:00:00',
            'ends_at' => '2023-09-22 11:00:00',
            'user_id' => 1
        ];
        $schedule = Schedule::factory()->make();

        $this->scheduleRepository
            ->shouldReceive('update')
            ->once()
            ->with($id, $params + ['status' => ScheduleStatusEnum::OPEN->value])
            ->andReturn($schedule);

        $this->scheduleRepository
            ->shouldReceive('checkIfDateScheduleExists')
            ->once()
            ->andReturn(false);


        $useCase = new UpdateScheduleUseCase(
            $this->scheduleRepository,
            $this->checkIfScheduleExists,
            $this->checkWeekendUseCase
        );

        $result = $useCase->handle($id, $params);

        $this->assertEquals($schedule, $result);
    }

    public function testHandleThrowsExceptionIfScheduleExists()
    {
        $id = 1;
        $params = [
            'starts_at' => '2023-09-22 10:00:00',
            'ends_at' => '2023-09-22 11:00:00',
            'user_id' => $id,
        ];

        $this->scheduleRepository
            ->shouldReceive('checkIfDateScheduleExists')
            ->once()
            ->andReturn(true);

        $this->expectException(ScheduleAlreadyExistsException::class);

        $useCase = new UpdateScheduleUseCase(
            $this->scheduleRepository,
            $this->checkIfScheduleExists,
            $this->checkWeekendUseCase
        );

        $useCase->handle($id, $params);
    }
}
