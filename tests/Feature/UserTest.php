<?php
/**
 * User Test
 */
namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;

/**
 * Testing some methods
 */
class UserTest extends TestCase
{
    /**
     * Test listing users
     *
     * @return void
     */
    public function testList()
    {
        $response = $this->get('/api/list');
        $response->assertStatus(200);
    }

    /**
     * Test user is registered
     *
     * @return void
     */
    public function testRegister()
    {
        $faker = Factory::create();
        $img = base64_encode(file_get_contents(resource_path('img/sample.jpg')));
        $response = $this->post('/api/register', [
            'name' => 'Gal Gadot',
            'email' => $faker->unique()->safeEmail,
            'country' => 'Israel',
            'encode_img' => 'data:image/png;base64,' . $img
        ]);
        $response->assertStatus(201);
    }

    /**
     * Test recognition find user
     *
     * @return void
     */
    public function testRecognitionOk()
    {
        $img = base64_encode(file_get_contents(resource_path('img/recog.jpg')));
        $response = $this->post('/api/recog', [
            'img' => 'data:image/png;base64,' . $img
        ]);
        $response->assertJsonStructure([
            'status', 'confidence', 'user'
        ]);
    }

    /**
     * Test recognition don`t find User
     *
     * @return void
     */
    public function testRecognitionUnknown()
    {
        $img = base64_encode(file_get_contents(resource_path('img/unknown.jpeg')));
        $response = $this->post('/api/recog', [
            'img' => 'data:image/png;base64,' . $img
        ]);
        $response->assertJson([
            'status' => 'unknown'
        ]);
    }

    /**
     * Test recognition don`t find User
     *
     * @return void
     */
    public function testRecognitionNoFace()
    {
        $img = base64_encode(file_get_contents(resource_path('img/noface.jpeg')));
        $response = $this->post('/api/recog', [
            'img' => 'data:image/png;base64,' . $img
        ]);
        $response->assertJson([
            'status' => 'no_face'
        ]);
    }
}
