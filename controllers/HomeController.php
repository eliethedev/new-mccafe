<?php

class HomeController extends Controller {
    
    public function index() {
        // Mock data for testing
        $featuredProducts = [
            [
                'id' => 1,
                'name' => 'Matcha Espresso',
                'price' => 120,
                'description' => 'Rich and bold matcha espresso coffee made from premium beans',
                'image' => 'products/matcha-espresso.jpg'
            ],
            [
                'id' => 2,
                'name' => 'Spanish Latte',
                'price' => 150,
                'description' => 'Smooth spanish latte with perfect milk foam',
                'image' => 'spanish latte.jpg'
            ],
            [
                'id' => 3,
                'name' => 'Cheesy Bacon',
                'price' => 80,
                'description' => 'Freshly baked cheesy bacon sandwich',
                'image' => 'cheesybacon.jpg'
            ]
        ];
        
        $categories = [
            [
                'name' => 'coffee',
                'description' => 'Premium coffee selections'
            ],
            [
                'name' => 'food',
                'description' => 'Fresh food items'
            ],
            [
                'name' => 'beverage',
                'description' => 'Cold and hot beverages'
            ],
            [
                'name' => 'dessert',
                'description' => 'Sweet treats and desserts'
            ]
        ];
        
        return $this->view('home', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'title' => 'Welcome to MacCafe'
        ]);
    }
}
