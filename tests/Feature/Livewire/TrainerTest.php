<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Trainer;
use Livewire\Livewire;
use Tests\TestCase;

class TrainerTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(Trainer::class)
            ->assertStatus(200);
    }
}
