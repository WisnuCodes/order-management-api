# Dibitech - Order Management & E-Commerce API (Backend)

A robust, RESTful e-commerce API built with Laravel 11. This project provides comprehensive data management for Users, Products, Categories, Orders, Shopping Cart, Wishlist, and Reviews. It is designed with a scalable architecture, Role-Based Access Control (RBAC), and standardized JSON API responses.

## 🚀 Features

*   **Role-Based Authentication (Sanctum)**
    *   **Admin:** Full access to manage users, categories, products, and view global statistics.
    *   **Seller:** Can create, edit, delete their own products and view orders made for their products.
    *   **Buyer:** Can browse products, add to cart, toggle wishlist, create orders, and leave reviews.
*   **Product & Category Management:** Complete catalog management.
*   **Shopping Cart:** Full cart lifecycle management (Add, Update, Remove, Checkout).
*   **Wishlist System:** Add/Remove products to favorites.
*   **Reviews & Ratings:** Buyers can review and rate products they purchased or liked.
*   **Admin Dashboard Stats:** Aggregated data endpoints for dashboard analytics.
*   **Standardized JSON Responses:** Consistent response structures across all endpoints (success, validation error, server error).

## 🛠️ Technology Stack

*   **Language:** PHP 8.2+
*   **Framework:** Laravel 11.x
*   **Database:** MySQL / MariaDB
*   **Authentication:** Laravel Sanctum
*   **Package Manager:** Composer

## 📦 Installation & Setup

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/WisnuCodes/order-management-api.git
    cd order-management-api
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Environment Setup:**
    Copy the `.env` example file and generate the application key.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Make sure to configure your database connection (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) in the `.env` file.*

4.  **Run Migrations & Seeders:**
    This will create the database tables and populate them with dummy data (including Admin, Seller, and Buyer accounts).
    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Start the server:**
    ```bash
    php artisan serve
    ```
    *The API will run locally on `http://localhost:8000`.*

## 🔑 Default Test Accounts
All default seed accounts use the password: `password`
*   **Admin:** `admin@dibitech.com`
*   **Seller:** `john@example.com`
*   **Buyer:** `jane@example.com`

## 🔗 Key API Endpoints (Prefix: `/api`)

### Public Routes
*   `POST /login` - Authenticate a user
*   `POST /register` - Register a new user
*   `GET /products` - Retrieve all products
*   `GET /products/{id}` - Retrieve product details
*   `GET /categories` - Retrieve all categories

### Protected Routes (Requires Sanctum Bearer Token)
*   **General:**
    *   `POST /logout` - Logout the authenticated user
    *   `GET /profile` - Get authenticated user data
*   **Cart (Buyer):**
    *   `GET /cart` - View cart items
    *   `POST /cart` - Add to cart
    *   `PUT /cart/{id}` - Update quantity
    *   `DELETE /cart/{id}` - Remove from cart
    *   `POST /cart/checkout` - Checkout cart to orders
*   **Wishlist (Buyer):**
    *   `GET /wishlists` - Retrieve user's wishlist
    *   `POST /wishlists/toggle/{product_id}` - Toggle product favorite status
*   **Reviews (Buyer):**
    *   `POST /reviews` - Add a review
*   **Seller/Admin:**
    *   `POST /products`, `PUT /products/{id}`, `DELETE /products/{id}`
    *   `GET /admin/stats` - Admin dashboard analytics

---
**Author:** Wisnu Nugraha
