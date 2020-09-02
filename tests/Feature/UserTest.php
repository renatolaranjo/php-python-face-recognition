<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testList()
    {
        $response = $this->get('/api/list');
        $response->assertStatus(200);
    }

    public function testRegister()
    {
        $faker = Factory::create();
        $img = base64_encode(file_get_contents(resource_path('img/kreeves.jpeg')));
        $response = $this->post('/api/register', [
            'name' => 'Keanu Reeves',
            'email' => $faker->unique()->safeEmail,
            'country' => 'USA',
            'encode_img' => 'data:image/png;base64,'. $img
        ]);
        $response->assertStatus(201);

    }
}
