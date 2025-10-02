# Demo API - Laravel Customer Management

A simple yet robust REST API built with Laravel 12 for customer management with proper error handling and consistent JSON responses.

## Features

-   **Customer CRUD Operations**: Complete Create, Read, Update, Delete operations
-   **Pagination Support**: Configurable pagination with metadata
-   **Consistent API Responses**: Standardized JSON response format using traits
-   **Proper Error Handling**: JSON error responses for all API endpoints
-   **Request Validation**: Input validation with detailed error messages
-   **Database Integration**: SQLite database with migrations and factories

## Tech Stack

-   **Laravel 12** - PHP Framework
-   **PHP 8.2+** - Programming Language
-   **SQLite** - Database
-   **PHPUnit** - Testing Framework

## Project Structure

```
app/
├── Http/Controllers/Api/
│   └── CustomerController.php    # Customer API endpoints
├── Models/
│   └── Customer.php              # Customer Eloquent model
├── Traits/
│   └── ApiResponse.php           # Standardized API responses
└── Exceptions/
    └── Handler.php               # Global exception handling

database/
├── migrations/
│   └── *_create_customers_table.php
└── factories/
    └── UserFactory.php

routes/
├── api.php                       # API routes
└── web.php                       # Web routes
```

## Installation

1. **Clone the repository**

```bash
git clone <repository-url>
cd demo-api
```

2. **Install dependencies**

```bash
composer install
npm install
```

3. **Environment setup**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Database setup**

```bash
php artisan migrate
```

5. **Start the development server**

```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`

## API Endpoints

### Base URL

```
http://127.0.0.1:8000/api
```

### Health Check

```http
GET /api/ping
```

### Customer Management

| Method | Endpoint              | Description                     |
| ------ | --------------------- | ------------------------------- |
| GET    | `/api/customers`      | Get paginated list of customers |
| POST   | `/api/customers`      | Create a new customer           |
| GET    | `/api/customers/{id}` | Get specific customer           |
| PATCH  | `/api/customers/{id}` | Update specific customer        |
| DELETE | `/api/customers/{id}` | Delete specific customer        |

### Request Examples

#### Create Customer

```http
POST /api/customers
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com"
}
```

#### Get Customers with Pagination

```http
GET /api/customers?page=2&per_page=5
```

#### Update Customer

```http
PATCH /api/customers/1
Content-Type: application/json

{
    "name": "Jane Doe",
    "email": "jane@example.com"
}
```

## Response Format

All API responses follow a consistent JSON structure:

### Success Response

```json
{
    "success": true,
    "code": 200,
    "message": "Operation successful",
    "data": {
        /* response data */
    }
}
```

### Paginated Response

```json
{
    "success": true,
    "code": 200,
    "message": "Customer list retrieved successfully",
    "data": [
        {
            /* customer objects */
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 10,
        "total": 50,
        "last_page": 5
    }
}
```

### Error Response

```json
{
    "success": false,
    "code": 404,
    "message": "Resource not found",
    "error": "Additional error details"
}
```

## Error Handling

The API includes comprehensive error handling for:

-   **404 Not Found**: When routes or resources don't exist
-   **422 Validation Error**: When request data is invalid
-   **401 Unauthorized**: When authentication is required
-   **403 Forbidden**: When access is denied
-   **500 Internal Server Error**: For unexpected errors

All errors return JSON responses when accessing `/api/*` endpoints.

## Validation Rules

### Customer Creation/Update

-   `name`: Required string
-   `email`: Required, must be valid email format, unique in database

For updates, fields are optional (using `sometimes` validation rule).

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

```bash
./vendor/bin/pint
```

### Database Operations

```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

## API Response Trait

The project uses `ApiResponse` trait for consistent response formatting:

-   `successResponse($data, $message, $code)` - For successful operations
-   `errorResponse($message, $code, $error)` - For error responses
-   `paginatedResponse($paginator, $message)` - For paginated data

## Database Schema

### Customers Table

```sql
- id (bigint, primary key, auto increment)
- name (varchar)
- email (varchar, unique)
- created_at (timestamp)
- updated_at (timestamp)
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
