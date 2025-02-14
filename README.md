# Toko-Mini Backend

## 📌 Description
Toko-Mini is a backend API built with **Laravel 11** for managing a small-scale e-commerce platform. The application uses **JWT authentication** to secure user access and facilitate transactions.

🚧 *This project is currently under further development.* 🚧

## 📦 Technologies Used
- **Laravel 11** (PHP Framework)
- **MySQL** (Database)
- **JWT (JSON Web Token)** (Authentication)
- **Composer** (Dependency Management)
- **Postman** (API Testing, optional)

## ⚙️ Environment Configuration
Make sure you have a `.env` file in the project root with the following content:

```
APP_NAME=Toko-Mini
APP_ENV=local
APP_KEY=base64:YOUR_APP_KEY
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=toko_mini
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=your_jwt_secret_key
```

## 🚀 How to Run the Project
### 1. Clone the Repository
```sh
git clone <repository-url>
cd Toko-Mini
```

### 2. Install Dependencies
```sh
composer install
```

### 3. Configure the Environment
Copy the `.env.example` file and rename it to `.env`:
```sh
cp .env.example .env
```
Generate the application key:
```sh
php artisan key:generate
```

### 4. Set Up the Database
Run database migrations:
```sh
php artisan migrate
```
(Optional) Seed the database with sample data:
```sh
php artisan db:seed
```

### 5. Generate JWT Secret Key
```sh
php artisan jwt:secret
```

### 6. Start the Server
```sh
php artisan serve
```
The backend will run at `http://localhost:8000/`.

## 🔐 Authentication
The API uses **JWT Authentication**. To get a token, send a `POST` request to:
```
POST /api/login
```
with credentials in the request body.

After authentication, include the token in the `Authorization` header for protected routes:
```
Authorization: Bearer YOUR_JWT_TOKEN
```

## 🔗 API Endpoints
| Method | Endpoint          | Description                |
|--------|------------------|----------------------------|
| POST   | /api/register    | Register a new user       |
| POST   | /api/login       | Authenticate user         |
| GET    | /api/products    | Retrieve all products     |
| POST   | /api/orders      | Create a new order        |

## 🛠️ Deployment
For production, set up a web server (e.g., Nginx or Apache) and run:
```sh
php artisan config:cache
php artisan route:cache
php artisan optimize
```

## 📜 License
This project is licensed under the [MIT](LICENSE) license.

