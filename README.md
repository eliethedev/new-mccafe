# MacCafe Ordering System
RUN THIS : php -S localhost:8000 -t public public/index.php

A comprehensive cafe ordering management system built with vanilla PHP following proper MVC architecture.

## Features

- **User Authentication**: Registration, login, password reset with role-based access control
- **Product Management**: Full CRUD operations with categories, variants, and image uploads
- **Shopping Cart**: Session-based for guests, persistent for logged users
- **Order Management**: Complete order lifecycle with status tracking
- **Admin Panel**: Product and order management interface
- **Responsive Design**: Bootstrap 5 with mobile-friendly interface
- **Security**: Input validation, password hashing, CSRF protection

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite enabled
- Web server (XAMPP, WAMP, etc.)

## Installation

1. **Clone or download the project to your web server directory**

2. **Create the database**:
   ```sql
   CREATE DATABASE maccafe_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Import the database schema**:
   ```bash
   mysql -u root -p maccafe_db < database/schema.sql
   ```

4. **Configure the database connection**:
   Edit `config/database.php` with your database credentials:
   ```php
   return [
       'host' => 'localhost',
       'database' => 'maccafe_db',
       'username' => 'root',
       'password' => '', // Your database password
   ];
   ```

5. **Set up URL rewriting**:
   - Ensure `.htaccess` files are present in root and public directories
   - For Apache, make sure `mod_rewrite` is enabled
   - Set `AllowOverride All` in your Apache configuration

6. **Configure the base URL**:
   Edit `config/constants.php`:
   ```php
   define('APP_URL', 'http://localhost/maccafe-mor-demo');
   ```

7. **Set file permissions**:
   ```bash
   chmod -R 755 public/
   chmod -R 777 public/assets/images/uploads/
   ```

## Directory Structure

```
├── config/              # Configuration files
│   ├── config.php       # Application configuration
│   ├── constants.php    # Application constants
│   └── database.php    # Database configuration
├── core/                # MVC core classes
│   ├── Controller.php   # Base controller
│   ├── Model.php        # Base model
│   ├── Request.php      # HTTP request handling
│   ├── Response.php     # HTTP response handling
│   ├── Router.php       # URL routing
│   └── Session.php      # Session management
├── controllers/         # Application controllers
│   ├── AuthController.php
│   ├── HomeController.php
│   └── ProductController.php
├── middleware/          # Authentication middleware
│   ├── AdminMiddleware.php
│   ├── AuthMiddleware.php
│   └── GuestMiddleware.php
├── models/              # Database models
│   ├── Product.php
│   └── User.php
├── views/               # View templates
│   ├── layouts/         # Layout templates
│   ├── auth/            # Authentication views
│   ├── products/        # Product views
│   └── home.php         # Home page
├── public/              # Public assets and entry point
│   ├── assets/          # CSS, JS, images
│   ├── index.php        # Application entry point
│   └── .htaccess        # URL rewriting
├── routes/              # Route definitions
│   └── web.php          # Web routes
├── database/            # Database schema
│   └── schema.sql       # Database structure
└── .htaccess           # Root .htaccess
```

## Default Login

- **Email**: admin@maccafe.com
- **Password**: admin123

## Usage

1. **Access the application**: Open your browser and navigate to `http://localhost/maccafe-mor-demo`

2. **Register as a customer**: Use the registration form to create a customer account

3. **Browse products**: View the menu and add items to your cart

4. **Place orders**: Checkout and track your orders

5. **Admin access**: Login as admin to manage products and orders

## API Endpoints

The system includes RESTful API endpoints:

- `GET /api/products` - Get all products
- `GET /api/products/{id}` - Get specific product
- `POST /api/cart/add` - Add item to cart
- `GET /api/cart` - Get cart contents

## Security Features

- **Password Hashing**: Uses PHP's `password_hash()` function
- **SQL Injection Prevention**: Prepared statements with PDO
- **XSS Protection**: Input sanitization and output escaping
- **CSRF Protection**: Token-based CSRF protection
- **Rate Limiting**: Login attempt tracking and lockout
- **Input Validation**: Server-side validation for all inputs

## Customization

### Adding New Products

1. Login as admin
2. Navigate to Admin → Products
3. Click "Add New Product"
4. Fill in product details and upload image

### Modifying Styles

Edit `public/assets/css/style.css` for custom styling.

### Adding New Routes

Add routes in `routes/web.php` following the existing pattern.

## Troubleshooting

### 404 Errors
- Ensure `.htaccess` files are present and working
- Check Apache `mod_rewrite` is enabled
- Verify `AllowOverride All` is set in Apache config

### Database Connection Issues
- Check database credentials in `config/database.php`
- Ensure MySQL server is running
- Verify database exists and schema is imported

### Image Upload Issues
- Check `public/assets/images/uploads/` directory permissions
- Ensure PHP upload limits are sufficient
- Verify GD library is installed for image processing

## Development

### Adding New Controllers

1. Create controller in `controllers/` directory
2. Extend the base `Controller` class
3. Add routes in `routes/web.php`

### Adding New Models

1. Create model in `models/` directory
2. Extend the base `Model` class
3. Define the `$table` property

### Adding New Views

1. Create view file in `views/` directory
2. Use the main layout: `include __DIR__ . '/layouts/main.php';`
3. Pass data from controller using `$this->view()`

## License

This project is open source and available under the [MIT License](LICENSE).

## Support

For issues and questions, please create an issue in the project repository.
