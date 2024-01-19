<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Product::class;
    public function definition()
    {
        return [
            'code'                      => $this->faker->ean8,
            'name'                      => $this->faker->name,
            'stock'                     => $this->faker->randomElement([10, 50, 80, 100]),
            'stock_min'                 => $this->faker->randomElement([5, 20, 30, 41]),
            'stock_max'                 => $this->faker->randomElement([15, 25, 35, 45]),
            'sell_price'                => $this->faker->randomElement([1000, 2000, 30000, 400000]),
            'sell_price_tecnico'        => $this->faker->randomElement([900, 1800, 29000, 38000]),
            'sell_price_distribuidor'   => $this->faker->randomElement([800, 1500, 25000, 35000]),
            'status'                    => $this->faker->randomElement(['ACTIVE', 'DESACTIVE']),
            'category_id'               => $this->faker->randomElement([1, 2, 3]),
            'medida_id'                 => 1,
            'brand_id'                  => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}
