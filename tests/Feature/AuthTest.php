<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    //  Success Scenarios Test Cases
    public function test_register_method_returns_user_and_token_on_successful_registration()
    {
        $userData = [
            'name' => 'Hassham Nadeem',
            'email' => 'hasshamnadeem@example.com',
            'password' => 'password123',
        ];

        $response = $this->post('api/register', $userData);

        $response->assertStatus(201);

        $response->assertJson([
            'user' => [
                'name' => $userData['name'],
                'email' => $userData['email'],
            ],
        ]);

        $response->assertJsonStructure([
            'token',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
    }

    public function test_login_method_returns_token_and_user_on_successful_login()
    {
        $this->withoutExceptionHandling();

        $password = 'password';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $response = $this->post('api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email', ],
            'token',
        ]);

        $response->assertJsonFragment([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_logout_method_deletes_user_tokens()
    {
        $this->withoutExceptionHandling();

        $user = Sanctum::actingAs(User::factory()->create());

        $response = $this->post('api/logout');

        $response->assertStatus(200);
        $response->assertExactJson(['message' => 'User Logged Out Successfuly']);

        $this->assertCount(0, $user->tokens);
    }

    // Failure  Scenarios Test Cases

    public function test_registration_with_existing_email()
    {
        $existingUser = User::factory()->create([
            'email' => 'hassham@example.com',
        ]);

        $userData = [
            'name' => 'Hassham Nadeem',
            'email' => 'hassham@example.com',
            'password' => 'password123',
        ];

        $response = $this->post('api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        $this->assertDatabaseMissing('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
    }

    public function test_registration_with_invalid_data()
    {
        $userData = [
            'name' => 'The people who cast the characters dont
                     decide the subject, the people
                      who count the characters do h',
            'email' => 'invalid_email_format',
        ];

        $response = $this->post('api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);

        $this->assertDatabaseMissing('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
    }

    public function test_login_with_invalid_credentials()
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $userData = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->post('api/login', $userData);

        $response->assertStatus(401)
            ->assertJson(['message' => 'User Not Found']);
    }

    public function test_login_with_nonexistent_user()
    {
        $userData = [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ];

        $response = $this->post('api/login', $userData);

        $response->assertStatus(401)
            ->assertJson(['message' => 'User Not Found']);
    }

    public function test_unauthenticated_user_cannot_view_product()
    {
        // No need to authenticate user here

        // Create a product in the database
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(401);
    }

    public function test_nonexistent_product_returns_not_found()
    {
        // Assuming you have set up Sanctum authentication middleware for the route
        // Acting as an authenticated user
        $this->actingAs(User::factory()->create());

        // Nonexistent product ID
        $nonexistentProductId = 999;

        $response = $this->getJson("/api/products/{$nonexistentProductId}");

        $response->assertStatus(404);
    }
}
