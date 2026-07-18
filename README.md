# Dibitech - Order Management & E-Commerce API (Backend)

A robust, enterprise-grade RESTful e-commerce API built with **Laravel 11**. This project provides comprehensive backend services for an e-commerce platform, including management for Users, Products, Categories, Orders, Shopping Cart, Wishlist, and Reviews. It is designed with a scalable architecture, Role-Based Access Control (RBAC), and standardized JSON API responses.

🔗 **Frontend Repository:** [WisnuCodes/react-laravel-api-integration](https://github.com/WisnuCodes/react-laravel-api-integration)

---

## 🚀 Key Features

*   **Role-Based Authentication (Sanctum)**
    *   **Admin:** Full access to manage users, categories, products, and view global system statistics (Total Users, Total Products, Total Orders, Revenue).
    *   **Seller:** Can create, edit, delete their own products and view orders made for their specific products.
    *   **Buyer:** Can browse products, add items to the cart, toggle wishlist items, create orders, and leave reviews.
*   **Complete Catalog Management:** CRUD operations for Categories and Products.
*   **Shopping Cart:** Full cart lifecycle management (Add, Update, Remove, Checkout).
*   **Wishlist System:** One-click Add/Remove products to favorites.
*   **Reviews & Ratings:** Buyers can review and rate products they purchased or liked.
*   **Standardized JSON Responses:** Consistent response structures across all endpoints (success, validation error, server error) ensuring smooth frontend integration.

## 🛠️ Technology Stack

*   **Language:** PHP 8.2+
*   **Framework:** Laravel 11.x
*   **Database:** MySQL / MariaDB
*   **Authentication:** Laravel Sanctum
*   **API Testing:** Postman / Insomnia (Compatible)
*   **Package Manager:** Composer

---

## 📦 Installation & Setup Guide

### Prerequisites
* PHP >= 8.2
* Composer
* MySQL or MariaDB

### Local Development Steps

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

4.  **Database Configuration:**
    Create a database in your MySQL server (e.g., `mini_project`) and update your `.env` file:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=mini_project
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5.  **Run Migrations & Seeders:**
    This will create the database tables and populate them with essential dummy data (including Admin, Seller, and Buyer test accounts).
    ```bash
    php artisan migrate:fresh --seed
    ```

6.  **Start the local development server:**
    ```bash
    php artisan serve
    ```
    *The API will run locally on `http://localhost:8000`.*

---

## 🔑 Default Test Accounts
All default seed accounts use the password: `password`

| Role   | Email | Password |
| :---   | :--- | :--- |
| **Admin**  | `admin@dibitech.com` | `password` |
| **Seller** | `john@example.com` | `password` |
| **Buyer**  | `jane@example.com` | `password` |

---

## 🗄️ Database Structure (ERD Overview)

The database consists of the following core tables:
*   `users`: Stores all users (Admin, Seller, Buyer) and their credentials.
*   `categories`: Product categories.
*   `products`: Stores product details, pricing, and foreign key to the `users` table (Seller).
*   `orders` & `order_items`: Handles the checkout process and order history.
*   `carts`: Temporary storage for items added to the cart by Buyers.
*   `wishlists`: Junction table linking `users` (Buyers) and `products` they favorited.
*   `reviews`: Stores user ratings (1-5) and text reviews for products.

---

## 🔗 Comprehensive API Documentation (Prefix: `/api`)

### 1. Authentication (Public)
*   `POST /login` : Authenticate a user and return a Sanctum Bearer token.
*   `POST /register` : Register a new user (default role: buyer).

### 2. Catalog (Public)
*   `GET /products` : Retrieve a paginated list of all products.
*   `GET /products/{id}` : Retrieve specific product details including its reviews.
*   `GET /categories` : Retrieve all product categories.
*   `GET /categories/{id}` : Retrieve a specific category.

### 3. User Profile (Protected)
*   `GET /profile` : Get the currently authenticated user's data.
*   `POST /logout` : Invalidate the current Sanctum token.

### 4. Buyer Endpoints (Protected - Buyer Role)
*   **Cart:**
    *   `GET /cart` : View current user's cart items and subtotal.
    *   `POST /cart` : Add a product to the cart (requires `product_id`, `quantity`).
    *   `PUT /cart/{id}` : Update the quantity of a cart item.
    *   `DELETE /cart/{id}` : Remove an item from the cart.
    *   `POST /cart/checkout` : Convert the current cart into a finalized Order.
*   **Wishlist:**
    *   `GET /wishlists` : Retrieve all products in the user's wishlist.
    *   `POST /wishlists/toggle/{product_id}` : Add or remove a product from the wishlist.
*   **Reviews:**
    *   `POST /reviews` : Submit a review for a product (requires `product_id`, `rating`, `comment`).

### 5. Seller Endpoints (Protected - Seller Role)
*   `POST /products` : Create a new product listing.
*   `PUT /products/{id}` : Update an existing product listing.
*   `DELETE /products/{id}` : Delete a product listing.

### 6. Admin Endpoints (Protected - Admin Role)
*   `GET /admin/stats` : Retrieve global dashboard analytics (user count, order count, total revenue).
*   `GET /users` : Retrieve a list of all registered users.
*   `GET /users/{id}` : Retrieve specific user details.
*   *(Admins also have full CRUD access to Categories and Products).*

---
**Developed with ❤️ by Wisnu Nugraha**
