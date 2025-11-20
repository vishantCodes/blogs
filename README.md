# Blog API (Laravel 12)

A robust RESTful API for a blogging platform built with Laravel 12. This application manages user authentication, blog posts, and social interactions like "liking" posts, complete with advanced filtering and sorting capabilities.

---

## 🚀 Features

- **User Authentication**: Secure registration and login using Bearer tokens (Sanctum/Passport).
- **Blog Management (CRUD)**:
  - Create, Read, Update, and Delete blog posts.
  - Pagination included for listing blogs.
- **Advanced Filtering & Sorting**:
  - **Search**: Filter blogs by title or content keywords.
  - **Sort**: Order blogs by latest or most liked.
- **Social Interactions**:
  - Like and Unlike blog posts.
  - Track like counts per post.
- **Resource Authorization**: Users can only update or delete their own posts.

---

## 🛠️ Tech Stack

- **Framework**: Laravel 12
- **Language**: PHP 8.2+
- **Database**: MySQL
- **API Testing**: Postman

---

## 📋 Prerequisites

Ensure you have the following installed on your local machine:

- PHP >= 8.2
- Composer
- MySQL

---

## ⚙️ Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/blog-api.git
   cd blog-api
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Environment Configuration**:
   Copy the example environment file and configure your database credentials:
   ```bash
   cp .env.example .env
   ```

   Open `.env` and update your database settings:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=blog_api_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations & Seeders**:
   This project includes seeders to populate the database with dummy users and blog posts for testing.
   ```bash
   php artisan migrate --seed
   ```

   Note: The `--seed` flag runs the `DatabaseSeeder` class, creating sample users and blogs automatically.

6. **Serve the Application**:
   ```bash
   php artisan serve
   ```

   The API will be accessible at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 📚 API Documentation

### Authentication

| Method | Endpoint     | Description                |
|--------|--------------|----------------------------|
| POST   | /api/register | Register a new user        |
| POST   | /api/login    | Login and receive a Bearer token |
| GET    | /api/me       | Get authenticated user details |

### Blogs

| Method | Endpoint         | Description                |
|--------|------------------|----------------------------|
| GET    | /api/blogs       | List all blogs (Paginated) |
| POST   | /api/blogs       | Create a new blog post     |
| GET    | /api/blogs/{id}  | View a specific blog post  |
| PUT    | /api/blogs/{id}  | Update a blog post         |
| DELETE | /api/blogs/{id}  | Delete a blog post         |

### Interactions & Filters

| Method | Endpoint                  | Description                |
|--------|---------------------------|----------------------------|
| POST   | /api/blogs/{id}/like      | Toggle Like/Unlike on a post |
| GET    | /api/blogs?search={query} | Search blogs by keyword    |
| GET    | /api/blogs?sort_by=latest | Sort blogs by newest first |
| GET    | /api/blogs?sort_by=most_liked | Sort blogs by popularity   |

---

## 🧪 Testing

You can test the API endpoints using the provided Postman Collection.

1. Import the `Blog API.postman_collection.json` file into Postman.
2. Set the `baseURL` variable in your Postman environment to [http://127.0.0.1:8000](http://127.0.0.1:8000).
3. Login to retrieve a token and paste it into the Authorization header (or update the `authToken` variable in Postman) for protected routes.

---
