<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;


class UserControllerTest extends TestCase
{
    /**
     * Test user can register successfully
     */
    public function test_user_can_register_successfully()
    {
        $data = [
            'first_name' => 'Pero 1',
            'last_name' => 'Perov 1',
            'email' => 'pero.perov1@upshift.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJson([
                'message' => 'User registered successfully!',
                'user' => [
                    'first_name' => 'Pero 1',
                    'last_name' => 'Perov 1',
                    'email' => 'pero.perov1@upshift.com',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'pero.perov1@upshift.com',
        ]);
    }

    /**
     * Test user can update profile successfully
     */
    public function test_user_can_update_profile_successfully()
    {
        $user = User::create([
            'first_name' => 'Trajce',
            'last_name' => 'Trajcev',
            'email' => 'trajce.trajcev@upshift.com',
            'password' => bcrypt('password123'), 
        ]);

        $this->actingAs($user);

        $data = [
            'first_name' => 'Trajce Updated',
            'last_name' => 'Trajcev Updated',
            'email' => 'trajce.updated@upshift.com',
            'password' => 'newpassword123',
        ];

        $response = $this->putJson('/api/profile', $data); 

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'message' => 'Profile updated successfully!',
                'user' => [
                    'first_name' => 'Trajce Updated',
                    'last_name' => 'Trajcev Updated',
                    'email' => 'trajce.updated@upshift.com',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'trajce.updated@upshift.com',
            'first_name' => 'Trajce Updated',
            'last_name' => 'Trajcev Updated',
        ]);

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    /**
     * Test user can register with invalid data (Empty array)
     */
    public function test_registration_fails_with_invalid_data()
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'password']);
    }   
}