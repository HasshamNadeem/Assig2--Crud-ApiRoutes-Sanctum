<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UsersProductsTest extends TestCase
{
    use WithFaker,RefreshDatabase;

//    Succcess Scenarios Tests
    public function test_index_method_returns_products_for_user()
    {
        $this->withoutExceptionHandling();

        $user = Sanctum::actingAs(User::factory()->create());
        $products = Product::factory()->count(10)->create();
        foreach ($products as $product) {
            $user->products()->attach($product->id);
        }

        $response = $this->get("api/users/{$user->id}/products");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'created_at',
                    'updated_at',
                ],
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);
    }

    public function test_store_method_attaches_product_to_user()
    {
        $this->withoutExceptionHandling();

        $user = Sanctum::actingAs(User::factory()->create());
        $product = Product::factory()->create();

        // Act: Send a POST request to the store endpoint with the user ID and product ID
        $response = $this->post("api/users/{$user->id}/products", [
            'id' => $product->id,
        ]);

        $response->assertStatus(200);
        $response->assertExactJson(['message' => 'Product attached successfully']);

        // Assert that the product is attached to the user
        $this->assertDatabaseHas('product_user', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_destroy_method_detaches_product_from_user()
    {
        $this->withoutExceptionHandling();

        $user = Sanctum::actingAs(User::factory()->create());
        $product = Product::factory()->create();
        $user->products()->attach($product->id);

        $response = $this->delete("api/users/{$user->id}/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertExactJson(['message' => 'Product detached successfully']);

        $this->assertDatabaseMissing('product_user', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

//  Failure Scenario Tests

public function test_unauthorized_user_cannot_view_products()
{
    $user = User::factory()->create();
    $products = Product::factory()->count(10);

    foreach ($products as $product) {
        $user->products()->attach($product->id);
    }

    $response = $this->getJson("/api/users/{$user->id}/products");

    $response->assertStatus(401);
}

public function test_a_user_cannot_view_other_user_products()
{
    $user = Sanctum::actingAs(User::factory()->create());
    $otheruser = User::factory()->create();

    $products = Product::factory()->count(10);

    foreach ($products as $product) {
        $user->products()->attach($product->id);
    }

    $response = $this->getJson("/api/users/{$otheruser->id}/products");

    $response->assertStatus(403);
}

public function test_unauthorized_user_cannot_attach_product()
{
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $data = [
        'id' => $product->id,
    ];

    $response = $this->postJson("/api/users/{$user->id}/products", $data);

    $response->assertStatus(401);
    $this->assertDatabaseMissing('product_user', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
}

public function test_invalid_product_id_cannot_be_attached()
{
    $user = Sanctum::actingAs(User::factory()->create());

    $data = [
        'id' => 'non_integer_value',
    ];

    $response = $this->postJson("/api/users/{$user->id}/products", $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['id']);

    $this->assertDatabaseMissing('product_user', [
        'user_id' => $user->id,
    ]);
}

public function test_unauthorized_user_cannot_detach_product()
{
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $user->products()->attach($product->id);

    $response = $this->deleteJson("/api/users/{$user->id}/products/{$product->id}");

    $response->assertStatus(401);

    $this->assertDatabaseHas('product_user', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
}

public function test_a_user_cannot_detach_other_user_product()
{
    $user = Sanctum::actingAs(User::factory()->create());

    $product = Product::factory()->create();
    $user->products()->attach($product->id);

    $otherUser = User::factory()->create();
    $this->actingAs($otherUser);

    $response = $this->deleteJson("/api/users/{$user->id}/products/{$product->id}");

    $response->assertStatus(403);

    $this->assertDatabaseHas('product_user', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
}

public function test_detach_nonexistent_product_returns_not_found()
{
    $user = User::factory()->create();
    $this->actingAs($user);
    $nonexistentProductId = 999;

    $response = $this->deleteJson("/api/users/{$user->id}/products/{$nonexistentProductId}");

    $response->assertStatus(404);
}
}
