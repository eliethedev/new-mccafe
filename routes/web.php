<?php

// Create router instance
$router = new Router();

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/menu', 'ProductController@index');
$router->get('/menu/full', 'ProductController@fullMenu');
$router->get('/menu/{category}/{subcategory}', 'ProductController@subcategory');
$router->get('/menu/{category}', 'ProductController@category');
$router->get('/product/{id}', 'ProductController@show');

// Authentication routes (guest only)
$router->get('/login', 'AuthController@showLogin', [GuestMiddleware::class]);
$router->post('/login', 'AuthController@login', [GuestMiddleware::class]);
$router->get('/register', 'AuthController@showRegister', [GuestMiddleware::class]);
$router->post('/register', 'AuthController@register', [GuestMiddleware::class]);
$router->post('/logout', 'AuthController@logout');
$router->get('/logout', 'AuthController@logout');

// Email verification routes
$router->get('/verify-email', 'AuthController@verifyEmail');
$router->post('/resend-verification', 'AuthController@resendVerification');

// Password reset routes
$router->get('/forgot-password', 'AuthController@showForgotPassword', [GuestMiddleware::class]);
$router->post('/forgot-password', 'AuthController@sendResetLink', [GuestMiddleware::class]);
$router->get('/reset-password/{token}', 'AuthController@showResetPassword', [GuestMiddleware::class]);
$router->post('/reset-password', 'AuthController@resetPassword', [GuestMiddleware::class]);

// Cart routes
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/confirm', 'CartController@confirmAdd');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/remove', 'CartController@remove');
$router->post('/cart/clear', 'CartController@clear');

// Checkout routes (auth required)
$router->get('/checkout', 'CheckoutController@index');
$router->post('/checkout/process', 'CheckoutController@process');

// Order routes (auth required)
$router->get('/orders', 'OrderController@index', [AuthMiddleware::class]);
$router->get('/orders/{id}', 'OrderController@show', [AuthMiddleware::class]);
$router->get('/order/{id}', 'OrderController@show', [AuthMiddleware::class]);
$router->post('/orders/{id}/cancel', 'OrderController@cancel', [AuthMiddleware::class]);

// User dashboard (auth required)
$router->get('/dashboard', 'UserController@dashboard', [AuthMiddleware::class]);
$router->get('/profile', 'UserController@profile', [AuthMiddleware::class]);
$router->post('/profile', 'UserController@updateProfile', [AuthMiddleware::class]);
$router->post('/profile/change-password', 'UserController@changePassword', [AuthMiddleware::class]);

// Admin routes (admin only)
$router->get('/admin', 'AdminController@index', [AdminMiddleware::class]);

// Categories management
$router->get('/admin/categories', 'CategoryController@index', [AdminMiddleware::class]);
$router->post('/admin/categories', 'CategoryController@store', [AdminMiddleware::class]);
$router->get('/admin/categories/{id}/edit', 'CategoryController@edit', [AdminMiddleware::class]);
$router->put('/admin/categories/{id}', 'CategoryController@update', [AdminMiddleware::class]);
$router->delete('/admin/categories/{id}', 'CategoryController@delete', [AdminMiddleware::class]);
$router->post('/admin/categories/{id}/status', 'CategoryController@updateStatus', [AdminMiddleware::class]);

// Products management
$router->get('/admin/products', 'ProductController@adminIndex', [AdminMiddleware::class]);
$router->get('/admin/products/create', 'ProductController@create');
$router->post('/admin/products', 'ProductController@store');
$router->get('/admin/products/{id}/edit', 'ProductController@edit', [AdminMiddleware::class]);
$router->put('/admin/products/{id}', 'ProductController@update', [AdminMiddleware::class]);
$router->delete('/admin/products/{id}', 'ProductController@delete', [AdminMiddleware::class]);
$router->post('/admin/products/{id}/status', 'ProductController@updateStatus', [AdminMiddleware::class]);
$router->post('/admin/products/{id}/sort', 'ProductController@updateSortOrder', [AdminMiddleware::class]);

// Product variants management
$router->get('/admin/products/{id}/variants', 'ProductController@variants', [AdminMiddleware::class]);
$router->post('/admin/products/{id}/variants', 'ProductController@storeVariant', [AdminMiddleware::class]);
$router->post('/admin/products/variants/{id}/status', 'ProductController@updateVariantStatus', [AdminMiddleware::class]);
$router->delete('/admin/products/variants/{id}', 'ProductController@deleteVariant', [AdminMiddleware::class]);

// Orders management
$router->get('/admin/orders', 'OrderController@adminIndex', [AdminMiddleware::class]);
$router->get('/admin/orders/{id}', 'OrderController@adminShow', [AdminMiddleware::class]);
$router->post('/admin/orders/{id}/status', 'OrderController@updateStatus', [AdminMiddleware::class]);

// Users management
$router->get('/admin/users', 'UserController@adminIndex', [AdminMiddleware::class]);
$router->get('/admin/users/{id}', 'UserController@show', [AdminMiddleware::class]);
$router->get('/admin/users/{id}/edit', 'UserController@edit', [AdminMiddleware::class]);
$router->put('/admin/users/{id}', 'UserController@updateUserProfile', [AdminMiddleware::class]);
$router->delete('/admin/users/{id}', 'UserController@delete', [AdminMiddleware::class]);
$router->post('/admin/users', 'UserController@store', [AdminMiddleware::class]);

// API routes
$router->get('/api/products', 'ApiController@products');
$router->get('/api/products/{id}', 'ApiController@product');
$router->post('/api/cart/add', 'ApiController@addToCart');
$router->get('/api/cart', 'ApiController@getCart');

// Return the router for use in index.php
return $router;
