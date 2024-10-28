<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Gig;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class GigControllerTest extends TestCase
{
    /**
     * Test gig can be created successfully
     */
    public function test_can_create_gig_successfully()
    {
        $user = User::create([
            'first_name' => 'Gig',
            'last_name' => 'User',
            'email' => 'gig.user@upshift.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $company = Company::create([
            'name' => 'Test Company for Gig',
            'description' => 'Company for gig creation test',
            'address' => 'Gig Company Address',
            'user_id' => $user->id,
        ]);

        $gigData = [
            'name' => 'Test Gig 1',
            'description' => "This Test Gig 1",
            'start_time' => '2024-10-10 19:42:23',
            'end_time' => '2024-10-11 19:42:23',
            'number_of_positions' => 3,
            'pay_per_hour' => 20,
            'status' => true,
            'company_id' => $company->id, 
        ];

        $response = $this->postJson('/api/gigs', $gigData);

        $response->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJson([
                'message' => 'Gig created successfully!',
                'gig' => [
                    'name' => 'Test Gig 1',
                    'description' => "This Test Gig 1",
                    'start_time' => '2024-10-10 19:42:23',
                    'end_time' => '2024-10-11 19:42:23',
                    'number_of_positions' => 3,
                    'pay_per_hour' => 20,
                    'status' => true,
                    'company_id' => $company->id, 
                ],
            ]);

        $this->assertDatabaseHas('gigs', [
            'name' => 'Test Gig 1',
            'description' => "This Test Gig 1",
            'start_time' => '2024-10-10 19:42:23',
            'end_time' => '2024-10-11 19:42:23',
            'number_of_positions' => 3,
            'pay_per_hour' => 20,
            'status' => true,
            'company_id' => $company->id, 
        ]);
    }

    /**
     * Test user can update gig successfully
     */
    public function test_user_can_update_gig_successfully()
    {
        $user = User::create([
            'first_name' => 'Gig2',
            'last_name' => 'User2',
            'email' => 'gig.user2@upshift.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $company = Company::create([
            'name' => 'Test Company for Gig 2',
            'description' => 'Company for gig creation test',
            'address' => 'Gig Company Address',
            'user_id' => $user->id,
        ]);

        $gig = Gig::create([
            'name' => 'Test Gig 2',
            'description' => "This Test Gig 2",
            'start_time' => '2024-10-10 19:42:23',
            'end_time' => '2024-10-11 19:42:23',
            'number_of_positions' => 3,
            'pay_per_hour' => 20,
            'status' => true,
            'company_id' => $company->id,
        ]);

        $updatedGigData = [
            'name' => 'Updated Test Gig 2',
            'description' => "Updated description for Test Gig 2",
            'start_time' => '2024-10-12 09:00:00',
            'end_time' => '2024-10-12 18:00:00',
            'number_of_positions' => 5,
            'pay_per_hour' => 25,
            'status' => false,  
            'company_id' => $company->id, 
        ];

        $response = $this->putJson("/api/gigs/{$gig->id}", $updatedGigData);

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'message' => 'Gig updated successfully!',
                'gig' => [
                    'name' => 'Updated Test Gig 2',
                    'description' => "Updated description for Test Gig 2",
                    'start_time' => '2024-10-12 09:00:00',
                    'end_time' => '2024-10-12 18:00:00',
                    'number_of_positions' => 5,
                    'pay_per_hour' => 25,
                    'status' => false,
                    'company_id' => $company->id,
                ],
            ]);

        $this->assertDatabaseHas('gigs', [
            'id' => $gig->id,
            'name' => 'Updated Test Gig 2',
            'description' => "Updated description for Test Gig 2",
            'start_time' => '2024-10-12 09:00:00',
            'end_time' => '2024-10-12 18:00:00',
            'number_of_positions' => 5,
            'pay_per_hour' => 25,
            'status' => false,
            'company_id' => $company->id,
        ]);
    }

    /**
     * Test user can create gig with invalid data (Empty array)
     */
    public function test_gig_creation_fails_with_invalid_data()
    {
        $user = User::create([
            'first_name' => 'Gig3',
            'last_name' => 'User3',
            'email' => 'gig.user3@upshift.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/gigs', []);
        
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'description', 'start_time', 'end_time', 'number_of_positions', 'pay_per_hour', 'company_id']);
    }   

    /**
     * Test gigs can be listed successfully
     */
    public function test_can_list_gigs_successfully()
    {
        $user = User::create([
            'first_name' => 'Gig4',
            'last_name' => 'User4',
            'email' => 'gig.user4@upshift.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $company = Company::create([
            'name' => 'Test Company for Gig 4',
            'description' => 'Company for gig listing test',
            'address' => 'Gig Company Address',
            'user_id' => $user->id,
        ]);

        $gig1 = Gig::create([
            'name' => 'Test Gig 1',
            'description' => 'Description for Gig 1',
            'start_time' => '2024-10-10 19:42:23',
            'end_time' => '2024-10-11 19:42:23',
            'number_of_positions' => 5,
            'pay_per_hour' => 25,
            'status' => 1,
            'company_id' => $company->id,
        ]);

        $gig2 = Gig::create([
            'name' => 'Test Gig 2',
            'description' => 'Description for Gig 2',
            'start_time' => '2024-10-12 19:42:23',
            'end_time' => '2024-10-13 19:42:23',
            'number_of_positions' => 3,
            'pay_per_hour' => 30,
            'status' => 1,
            'company_id' => $company->id,
        ]);

        $response = $this->getJson('/api/gigs');

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'description',
                    'start_time',
                    'end_time',
                    'number_of_positions',
                    'pay_per_hour',
                    'status',
                    'company_id',
                    'created_at',
                    'updated_at',
                ]
            ]);

        $response->assertJsonFragment([
            'name' => $gig1->name,
            'description' => $gig1->description,
            'start_time' => '2024-10-10 19:42:23',
            'end_time' => '2024-10-11 19:42:23',
            'number_of_positions' => 5,
            'pay_per_hour' => 25,
            'status' => 1,
            'company_id' => $company->id,
        ]);

        $response->assertJsonFragment([
            'name' => $gig2->name,
            'description' => $gig2->description,
            'start_time' => '2024-10-12 19:42:23',
            'end_time' => '2024-10-13 19:42:23',
            'number_of_positions' => 3,
            'pay_per_hour' => 30,
            'status' => 1,
            'company_id' => $company->id,
        ]);
    }

    /**
     * Test gig can be deleted successfully
     */
    public function test_user_can_delete_gig_successfully()
    {
        $user = User::create([
            'first_name' => 'Gig5',
            'last_name' => 'User5',
            'email' => 'gig.user5@upshift.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $company = Company::create([
            'name' => 'Test Company for Gig',
            'description' => 'Company for gig delete test',
            'address' => 'Gig Company Address',
            'user_id' => $user->id,
        ]);

        $gig = Gig::create([
            'name' => 'Test Gig',
            'description' => 'Gig for delete test',
            'start_time' => '2024-10-12 10:00:00',
            'end_time' => '2024-10-12 18:00:00',
            'number_of_positions' => 5,
            'pay_per_hour' => 20,
            'status' => 1,
            'company_id' => $company->id,
        ]);

        $response = $this->deleteJson("/api/gigs/{$gig->id}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'message' => 'Gig deleted successfully!',
            ]);

        $this->assertDatabaseMissing('gigs', [
            'id' => $gig->id,
        ]);
    }

}
