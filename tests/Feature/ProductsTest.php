<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use WithFaker,RefreshDatabase;

    //  Success Scenarios Test Cases
    public function test_index_returns_paginated_list_of_products()
    {
        $this->withoutExceptionHandling();

        $user = Sanctum::actingAs(User::factory()->create());

        Product::factory()->count(10)->create();

        $response = $this->get('api/products');

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

    public function test_show_method_returns_correct_product()
    {
        $this->withoutExceptionHandling();

        $user = Sanctum::actingAs(User::factory()->create());
        $product = Product::factory()->create();
        $user->products()->attach($product->id);

        $response = $this->get("api/products/{$product->name}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
        ]);
    }

    public function test_user_create_a_product()
    {
        $this->withoutExceptionHandling();

        $user = Sanctum::actingAs(User::factory()->create());

        $attributes = [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(10000, 500000),
        ];

        $response = $this->post('api/products', $attributes);

        $this->assertDatabaseHas('products', $attributes);

        // $this->get('/projects')->assertSee($attributes['title']);

        $response->assertStatus(201);

        $response->assertJson([
            'message' => 'Product Added Successfully!',
        ]);
    }

    public function test_update_method_updates_product_successfully()
    {
        $this->withoutExceptionHandling();

        $product = Product::factory()->create();

        $user = Sanctum::actingAs(User::factory()->create());
        $user->products()->attach($product->id);
        $newData = [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(10000, 500000),
        ];
        $response = $this->put("api/products/{$product->id}", $newData);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Product Update Successfully',
        ]);

        $updatedProduct = Product::find($product->id);
        $this->assertEquals($newData['name'], $updatedProduct->name);
        $this->assertEquals($newData['description'], $updatedProduct->description);
        $this->assertEquals($newData['price'], $updatedProduct->price);
    }

    public function test_destroy_method_deletes_product_successfully()
    {
        $this->withoutExceptionHandling();

        $product = Product::factory()->create();

        $user = Sanctum::actingAs(User::factory()->create());

        $user->products()->attach($product->id);

        $response = $this->delete("api/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Product deleted successfully',
        ]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

//  Failure Scenairo Tests

    public function test_unauthenticated_user_cannot_view_products()
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_view_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(401);
    }

    public function test_nonexistent_product_returns_not_found()
    {
        $this->actingAs(User::factory()->create());

        $nonexistentProductId = 999;

        $response = $this->getJson("/api/products/{$nonexistentProductId}");

        $response->assertStatus(404);
    }

    public function test_unauthenticated_user_cannot_create_product()
    {
        $data = [
            'name' => 'New Product',
            'description' => 'Product description',
            'price' => 100,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('products', [
            'name' => 'New Product',
            'description' => 'Product description',
            'price' => 100,
        ]);
    }

    public function test_invalid_data_cannot_be_used_to_create_product()
    {
        $this->actingAs(User::factory()->create());

        $data = [
            'name' => '', // Empty name (invalid)
            'description' => str_repeat('A', 201), // Exceeds max length (invalid)
            'price' => 'abc', // Invalid price format (invalid)
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'description', 'price']);

        $this->assertDatabaseMissing('products', [
            'name' => '',
            'description' => str_repeat('A', 201),
            'price' => 'abc',
        ]);
    }

    public function test_duplicate_product_name_cannot_be_created()
    {
        $this->actingAs(User::factory()->create());

        $existingProduct = Product::factory()->create();

        $data = [
            'name' => $existingProduct->name,
            'description' => 'New Product description',
            'price' => 150,
        ];

        $response = $this->post('/api/products', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        $this->assertDatabaseMissing('products', [
            'name' => $existingProduct->name,
            'description' => 'New Product description',
            'price' => 150,
        ]);
    }

    public function test_unauthenticated_user_cannot_update_product()
    {
        $product = Product::factory()->create();

        $data = [
            'name' => 'Updated Product',
            'description' => 'Updated Product description',
            'price' => 150,
        ];

        $response = $this->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'description' => 'Updated Product description',
            'price' => 150,
        ]);
    }

    public function test_invalid_data_cannot_be_used_to_update_product()
    {
        $user = Sanctum::actingAs(User::factory()->create());

        $product = Product::factory()->create();

        $user->products()->attach($product->id);

        $data = [
            'name' => '',
            'description' => str_repeat('A', 201),
            'price' => 'abc',
        ];

        $response = $this->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'description', 'price']);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
            'name' => '',
            'description' => str_repeat('A', 201),
            'price' => 'abc',
        ]);
    }

    public function test_unauthenticated_user_cannot_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(401);

        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_nonexistent_product_deletion_returns_not_found()
    {
        $this->actingAs(User::factory()->create());

        $nonexistentProductId = 999;

        $response = $this->deleteJson("/api/products/{$nonexistentProductId}");

        $response->assertStatus(404);

        $this->assertDatabaseMissing('products', ['id' => $nonexistentProductId]);
    }

    public function test_unauthenticated_user_cannot_logout()
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }
}
