<?php

namespace Tests\Unit\Domain\Schedule\UseCases;

use Tests\TestCase;
use App\Domain\Schedule\Data\SearchScheduleDto;
use App\Domain\Schedule\Interfaces\ScheduleRepositoryInterface;
use App\Domain\Schedule\Models\Schedule;
use App\Domain\Schedule\UseCases\GetScheduleUseCase;
use Illuminate\Database\Eloquent\Collection;
use Mockery;

class GetScheduleUseCaseTest extends TestCase
{
    protected $scheduleRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->scheduleRepository = Mockery::mock(ScheduleRepositoryInterface::class);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testHandleCallsRepositoryWithDto()
    {
        $dto = new SearchScheduleDto(
            user_id: 1,
            title: 'Test Schedule',
            starts_at: now(),
            ends_at: now()->addHours(2)
        );
        $schedules = new Collection(Schedule::factory(3)->make());

        $this->scheduleRepository
            ->shouldReceive('search')
            ->once()
            ->with($dto)
            ->andReturn($schedules);

        $useCase = new GetScheduleUseCase($this->scheduleRepository);

        $result = $useCase->handle($dto);

        $this->assertEquals($schedules, $result);
    }
}
