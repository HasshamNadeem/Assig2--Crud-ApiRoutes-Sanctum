<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $applianceList = [
            'Television', 'Refrigerator', 'Washing Machine', 'Microwave Oven', 'Air Conditioner', 'Dishwasher', 'Vacuum Cleaner', 'Toaster', 'Blender',
            'Coffee Maker', 'Electric Kettle', 'Food Processor', 'Electric Grill', 'Rice Cooker', 'Air Fryer', 'Iron', 'Hair Dryer', 'Hair Straightener',
            'Hair Curler', 'Steam Iron', 'Steam Cleaner', 'Electric Shaver', 'Electric Toothbrush', 'Water Purifier', 'Humidifier', 'Dehumidifier',
            'Electric Fan', 'Electric Heater', 'Electric Blanket', 'Electric Fireplace', 'Portable Air Cooler', 'Electric Air Purifier', 'Projector',
            'Soundbar', 'DVD Player', 'Home Theater System', 'Speaker System', 'Bluetooth Speaker', 'Portable Speaker', 'Headphones', 'Earphones',
            'Smartwatch', 'Fitness Tracker', 'Electric Scooter', 'Electric Bike', 'Drone', 'Digital Camera', 'Camcorder', 'Smart Home Hub', 'Robot Vacuum',
        ];

        return [
            'name' => $this->faker->unique()->RandomElement($applianceList),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(10000, 500000),

        ];
    }
}
