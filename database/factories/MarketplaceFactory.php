<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarketplaceFactory extends Factory
{
    public function definition(): array
    {
        // Product categories and types
        $productTypes = [
            'chicks' => ['Kienyeji', 'Broiler', 'Layer', 'Dual Purpose', 'Improved Kienyeji', 'Sasso', 'Kenbro'],
            'feed' => ['Starter', 'Grower', 'Layer Mash', 'Finisher', 'Organic', 'Medicated'],
            'medicine' => ['Vaccine', 'Antibiotic', 'Dewormer', 'Multivitamin', 'Coccidiostat', 'Disinfectant'],
            'equipment' => ['Drinker', 'Feeder', 'Incubator', 'Brooder', 'Netting', 'Cage', 'Nest Box'],
            'eggs' => ['Kienyeji', 'Layer', 'Organic', 'Hatching', 'Duck', 'Quail'],
        ];

        $categories = array_keys($productTypes);
        $category = $this->faker->randomElement($categories);
        $productType = $this->faker->randomElement($productTypes[$category]);

        // Generate realistic titles
        $titles = [
            'chicks' => [
                "{$productType} Chicks (Day Old)",
                "{$productType} Chicks (1 Week Old)",
                "Premium {$productType} Chicks",
                "Organic {$productType} Chicks",
            ],
            'feed' => [
                "{$productType} Feed (25kg)",
                "{$productType} Feed (50kg)",
                "Premium {$productType} Feed",
                "Organic {$productType} Feed",
            ],
            'medicine' => [
                "{$productType} for Poultry",
                "Poultry {$productType} (100ml)",
                "Broad Spectrum {$productType}",
            ],
            'equipment' => [
                "Automatic {$productType}",
                "Plastic {$productType}",
                "Stainless Steel {$productType}",
            ],
            'eggs' => [
                "Fresh {$productType} Eggs (Tray)",
                "Organic {$productType} Eggs",
                "Large {$productType} Eggs",
            ],
        ];

        $title = $this->faker->randomElement($titles[$category]);

        // Generate descriptions
        $descriptions = [
            'chicks' => [
                "Healthy {$productType} chicks. Perfect for commercial farming.",
                "High-quality {$productType} chicks with excellent growth potential.",
            ],
            'feed' => [
                "High-quality {$productType} feed with essential nutrients.",
                "Specially formulated {$productType} feed for maximum productivity.",
            ],
            'medicine' => [
                "Effective {$productType} for poultry health management.",
                "Veterinary-grade {$productType} for disease prevention.",
            ],
            'equipment' => [
                "Durable {$productType} for efficient poultry farming.",
                "High-quality {$productType} designed for long-term use.",
            ],
            'eggs' => [
                "Fresh, nutritious {$productType} eggs from healthy chickens.",
                "Premium quality {$productType} eggs with rich flavor.",
            ],
        ];

        // Pricing based on category
        $priceRanges = [
            'chicks' => [150, 500],
            'feed' => [800, 6000],
            'medicine' => [500, 5000],
            'equipment' => [300, 20000],
            'eggs' => [600, 2000],
        ];

        $quantityRanges = [
            'chicks' => [100, 5000],
            'feed' => [10, 500],
            'medicine' => [5, 200],
            'equipment' => [5, 300],
            'eggs' => [20, 500],
        ];

        $locations = ['Kiambu', 'Nairobi', 'Thika', 'Nakuru', 'Murang\'a', 'Eldoret', 'Kisumu', 'Mombasa'];
        $units = [
            'chicks' => 'each',
            'feed' => 'bag',
            'medicine' => $this->faker->randomElement(['bottle', 'pack', 'vial']),
            'equipment' => 'piece',
            'eggs' => 'tray',
        ];

        // Image URLs for each category (using Unsplash/Placehold images)
        $imageUrls = [
            'chicks' => [
                'https://images.unsplash.com/photo-1566674717265-8e9a9c3b7e1f?w=400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1570654556242-630fbd2f47e2?w-400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1589923186741-7d1d6ccee3c3?w=400&h=300&fit=crop',
                'https://via.placeholder.com/400x300/4CAF50/FFFFFF?text=Chicks',
                'https://via.placeholder.com/400x300/8BC34A/FFFFFF?text=Poultry+Chicks',
            ],
            'feed' => [
                'https://images.unsplash.com/photo-1542838135-2a74b5e7c5e3?w=400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=400&h=300&fit=crop',
                'https://via.placeholder.com/400x300/FF9800/FFFFFF?text=Animal+Feed',
                'https://via.placeholder.com/400x300/FF5722/FFFFFF?text=Poultry+Feed',
                'https://via.placeholder.com/400x300/E91E63/FFFFFF?text=Chicken+Feed',
            ],
            'medicine' => [
                'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400&h=300&fit=crop',
                'https://via.placeholder.com/400x300/9C27B0/FFFFFF?text=Veterinary',
                'https://via.placeholder.com/400x300/673AB7/FFFFFF?text=Animal+Medicine',
                'https://via.placeholder.com/400x300/3F51B5/FFFFFF?text=Poultry+Medicine',
            ],
            'equipment' => [
                'https://images.unsplash.com/photo-1570727624866-8789e6d6d6b3?w=400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1556228578-9c360e1d8d6f?w=400&h=300&fit=crop',
                'https://via.placeholder.com/400x300/607D8B/FFFFFF?text=Farm+Equipment',
                'https://via.placeholder.com/400x300/795548/FFFFFF?text=Poultry+Gear',
                'https://via.placeholder.com/400x300/9E9E9E/FFFFFF?text=Farming+Tools',
            ],
            'eggs' => [
                'https://images.unsplash.com/photo-1563281577-a7a2dd63f093?w=400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?w=400&h=300&fit=crop',
                'https://via.placeholder.com/400x300/FFEB3B/000000?text=Fresh+Eggs',
                'https://via.placeholder.com/400x300/FFC107/000000?text=Organic+Eggs',
                'https://via.placeholder.com/400x300/FF9800/FFFFFF?text=Poultry+Eggs',
            ],
        ];

        $supplier = User::where('role', 'supplier')->inRandomOrder()->first() ??
                User::factory()->supplier()->create();

        return [
            'supplier_id' => $supplier->id,
            'product_type' => $category,
            'title' => $title,
            'description' => $this->faker->randomElement($descriptions[$category]),
            'price' => $this->faker->numberBetween($priceRanges[$category][0], $priceRanges[$category][1]),
            'quantity' => $this->faker->numberBetween($quantityRanges[$category][0], $quantityRanges[$category][1]),
            'unit' => $units[$category],
            'category' => 'poultry',
            'image' => $this->faker->randomElement($imageUrls[$category]), // Add random image URL
            'location' => $this->faker->randomElement($locations),
            'is_available' => $this->faker->boolean(90),
            'min_order' => $this->faker->numberBetween(1, $category === 'chicks' ? 10 : 1),
            'max_order' => $this->faker->optional(0.5)->numberBetween(10, 100),
            'tags' => json_encode([$category, $productType, strtolower($this->faker->randomElement($locations))]),
            'rating' => $this->faker->randomFloat(1, 3.5, 5.0),
            'total_ratings' => $this->faker->numberBetween(0, 200),
            'views' => $this->faker->numberBetween(0, 5000),
            'orders_count' => $this->faker->numberBetween(0, 100),
        ];
    }

    public function chicks(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_type' => 'chicks',
            'category' => 'poultry',
            'unit' => 'each',
            'min_order' => $this->faker->numberBetween(10, 50),
            'max_order' => $this->faker->numberBetween(100, 1000),
            'image' => $this->faker->randomElement([
                'https://images.unsplash.com/photo-1566674717265-8e9a9c3b7e1f?w=400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1570654556242-630fbd2f47e2?w-400&h=300&fit=crop',
                'https://via.placeholder.com/400x300/4CAF50/FFFFFF?text=Chicks',
            ]),
        ]);
    }

    public function feed(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_type' => 'feed',
            'category' => 'poultry',
            'unit' => 'bag',
            'min_order' => 1,
            'max_order' => $this->faker->numberBetween(10, 50),
            'image' => $this->faker->randomElement([
                'https://images.unsplash.com/photo-1542838135-2a74b5e7c5e3?w=400&h=300&fit=crop',
                'https://via.placeholder.com/400x300/FF9800/FFFFFF?text=Animal+Feed',
                'https://via.placeholder.com/400x300/FF5722/FFFFFF?text=Poultry+Feed',
            ]),
        ]);
    }

    public function medicine(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_type' => 'medicine',
            'category' => 'poultry',
            'unit' => $this->faker->randomElement(['bottle', 'pack', 'vial']),
            'min_order' => 1,
            'max_order' => $this->faker->numberBetween(5, 20),
            'image' => $this->faker->randomElement([
                'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=400&h=300&fit=crop',
                'https://via.placeholder.com/400x300/9C27B0/FFFFFF?text=Veterinary',
                'https://via.placeholder.com/400x300/673AB7/FFFFFF?text=Animal+Medicine',
            ]),
        ]);
    }

    public function equipment(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_type' => 'equipment',
            'category' => 'poultry',
            'unit' => 'piece',
            'min_order' => $this->faker->numberBetween(1, 5),
            'max_order' => $this->faker->numberBetween(10, 100),
            'image' => $this->faker->randomElement([
                'https://images.unsplash.com/photo-1570727624866-8789e6d6d6b3?w=400&h=300&fit=crop',
                'https://via.placeholder.com/400x300/607D8B/FFFFFF?text=Farm+Equipment',
                'https://via.placeholder.com/400x300/795548/FFFFFF?text=Poultry+Gear',
            ]),
        ]);
    }

    public function eggs(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_type' => 'eggs',
            'category' => 'poultry',
            'unit' => 'tray',
            'min_order' => 1,
            'max_order' => $this->faker->numberBetween(5, 50),
            'image' => $this->faker->randomElement([
                'https://images.unsplash.com/photo-1563281577-a7a2dd63f093?w=400&h=300&fit=crop',
                'https://via.placeholder.com/400x300/FFEB3B/000000?text=Fresh+Eggs',
                'https://via.placeholder.com/400x300/FFC107/000000?text=Organic+Eggs',
            ]),
        ]);
    }

    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->randomFloat(1, 4.5, 5.0),
            'total_ratings' => $this->faker->numberBetween(100, 500),
            'views' => $this->faker->numberBetween(1000, 10000),
            'orders_count' => $this->faker->numberBetween(50, 200),
        ]);
    }

    public function fresh(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 0,
            'total_ratings' => 0,
            'views' => $this->faker->numberBetween(0, 100),
            'orders_count' => 0,
        ]);
    }
}
