<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
