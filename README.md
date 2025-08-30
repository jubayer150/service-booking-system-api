# Service Booking System API (Laravel)

A **basic API-based & admin-managed service booking system** built with Laravel.
The system allows **customers** to view services, and make bookings, while **admins** can manage services and view all bookings.

## 🛠️ Installation & Setup

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL
- Laravel 12+

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/service-booking.git
   cd service-booking
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
    - Copy .env.example to .env
    - pdate database credentials in .env:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=service_booking_system_api
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4. **Generate app key**
    ```bash
    php artisan key:generate
    ```

5. **Run migrations & seeders**
    ```bash
    php artisan migrate --seed
    ```
    - Create all required tables.
    - Seed admins, customers and some sample service.

6. **Run the application**
    ```bash
    php artisan serve
    ```

## 📚 API Documentation

### Postman

A ready-to-use Postman collection is included:

- File: postman/ServiceBookingSystem.postman_collection.json
- Import into Postman.
- Set collection variable local_url to http://127.0.0.1:8000.
- Login → token automatically saved in {{token}} variable.
- Refer to each field’s description for its valid format and constraints.

### Customer Endpoints
- `POST /api/register` – Register as customer.
- `POST /api/login` – Login and get access token.
- `GET /api/services` – List active services.
- `POST /api/bookings` – Create a new booking (future dates only, and a customer cannot book the same service twice on the same day).
- `GET /api/bookings` – List logged-in customer’s bookings.

### Admin Endpoints
- `POST /api/login` – Login and get access token.
- `GET /api/services` – List all services.
- `POST /api/services` – Create a service.
- `PUT /api/services/{id}` – Update a service.
- `DELETE /api/services/{id}` – Delete a service (Cannot delete service with existing bookings).
- `GET /api/bookings` – List all bookings (with user + service info).

## 👤 Default Accounts

- **Admin**
    - Email: admin@example.com
    - Password: password

- **Customer**
    - Email: customer@example.com
    - Password: password