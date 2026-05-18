<?php

namespace Tests\Unit;

use App\Support\RoomOccupancy;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RoomOccupancyTest extends TestCase
{
    public function test_it_rejects_occupied_status_without_active_tenant(): void
    {
        $this->expectException(ValidationException::class);

        RoomOccupancy::ensureStatusIsConsistent('occupied');
    }

    public function test_it_allows_available_status_without_active_tenant(): void
    {
        $this->expectNotToPerformAssertions();

        RoomOccupancy::ensureStatusIsConsistent('available');
    }
}
