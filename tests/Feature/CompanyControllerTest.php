<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class CompanyControllerTest extends TestCase
{
    /**
     * Test company can be created successfully
     */
    public function test_can_create_company_successfully()
    {
        $user = User::create([
            'first_name' => 'Company1',
            'last_name' => 'User1',
            'email' => 'company.user1@upshift.com',
            'password' => bcrypt('password123'), 
        ]);

        $this->actingAs($user);

        $data = [
            'name' => 'Test Company',
            'description' => 'Company for Unit Test',
            'address' => 'Unit Test Address',
            'user_id' => $user->id,
        ];

        $response = $this->postJson('/api/companies', $data);

        $response->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJson([
                'message' => 'Company created successfully!',
                'company' => [
                    'name' => 'Test Company',
                    'description' => 'Company for Unit Test',
                    'address' => 'Unit Test Address',
                    'user_id' => $user->id,
                ]
            ]);

        $this->assertDatabaseHas('companies', [
            'name' => 'Test Company',
        ]);
    }

    /**
     * Test user can update company successfully
     */
    public function test_user_can_update_company_successfully()
    {
        $user = User::create([
            'first_name' => 'Company2',
            'last_name' => 'User2',
            'email' => 'company.user2@upshift.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

         $company = Company::create([
            'name' => 'Test Company',
            'description' => 'Company for Unit Test',
            'address' => 'Unit Test Address',
            'user_id' => $user->id,
        ]);

        $updateData = [
            'name' => 'Updated Test Company',
            'description' => 'Updated description for Company for Unit Test',
            'address' => 'Updated Unit Test Address',
            'user_id' => $user->id,
        ];

        $response = $this->putJson("/api/companies/{$company->id}", $updateData);

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'message' => 'Company updated successfully!',
                'company' => [
                    'name' => 'Updated Test Company',
                    'description' => 'Updated description for Company for Unit Test',
                    'address' => 'Updated Unit Test Address',
                ]
            ]);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Updated Test Company',
            'description' => 'Updated description for Company for Unit Test',
            'address' => 'Updated Unit Test Address',
        ]);
    }

    /**
     * Test user can create company with invalid data (Empty array)
     */
    public function test_company_creation_fails_with_invalid_data()
    {
        $user = User::create([
            'first_name' => 'Company3',
            'last_name' => 'User3',
            'email' => 'company.user@upshift.com',
            'password' => bcrypt('password123'), 
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/companies', []);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'address']);
    }   

    /**
     * Test companies can be listed successfully
     */
    public function test_can_list_companies_successfully()
    {
        $user = User::create([
            'first_name' => 'Company4',
            'last_name' => 'User4',
            'email' => 'company.user4@upshift.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $company1 = Company::create([
            'name' => 'Test Company 1',
            'description' => 'Description for Company 1',
            'address' => 'Address 1',
            'user_id' => $user->id,
        ]);
    
        $company2 = Company::create([
            'name' => 'Test Company 2',
            'description' => 'Description for Company 2',
            'address' => 'Address 2',
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/companies');

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                '*' => [ 
                    'id',
                    'name',
                    'description',
                    'address',
                    'user_id',
                    'created_at',
                    'updated_at',
                ]
            ]);

        $response->assertJsonFragment([
            'name' => $company1->name,
            'description' => $company1->description,
            'address' => $company1->address,
        ]);
    
        $response->assertJsonFragment([
            'name' => $company2->name,
            'description' => $company2->description,
            'address' => $company2->address,
        ]);
    }

    /**
     * Test company can be deleted successfully
     */
    public function test_user_can_delete_company_successfully()
    {
       $user = User::create([
            'first_name' => 'Company5',
            'last_name' => 'User5',
            'email' => 'company.user5@upshift.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $company = Company::create([
            'name' => 'Test Company',
            'description' => 'Company for delete test',
            'address' => 'Delete Company Address',
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson("/api/companies/{$company->id}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'message' => 'Company deleted successfully!',
            ]);

        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
        ]);
    }

    /**
     * Test user cannot delete company that do not own
     */
    public function test_user_cannot_delete_company_they_do_not_own()
    {
        $user1 = User::create([
            'first_name' => 'Company6',
            'last_name' => 'User6',
            'email' => 'company.user6@upshift.com',
            'password' => bcrypt('password123'),
        ]);

        $user2 = User::create([
            'first_name' => 'Company7',
            'last_name' => 'User7',
            'email' => 'company.user7@upshift.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user2);

        $company = Company::create([
            'name' => 'Test Company',
            'description' => 'Company for unauthorized delete test',
            'address' => 'Unauthorized Delete Company Address',
            'user_id' => $user1->id,
        ]);

        $response = $this->deleteJson("/api/companies/{$company->id}");

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'Unauthorized',
            ]);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
        ]);
    }

}
