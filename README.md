# Reygenix Setup Guide

Follow these steps to set up this project:

## Prerequisites

- Make sure you have PHP, Composer, and Git installed on your system.

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/jasfer223/reygenix.git
    cd reygenix
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Create a copy of the `.env.example` file and rename it to `.env`:

    ```bash
    cp .env.example .env
    ```

4. Configure your database connection settings in the `.env` file:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=your-database-host
    DB_PORT=your-database-port
    DB_DATABASE=reygenix
    DB_USERNAME=your-database-username
    DB_PASSWORD=your-database-password
    ```

5. Generate the application key:

    ```bash
    php artisan key:generate
    ```

## Database Migration and Seeding

Run the following command to migrate the database and seed it with predefined user accounts:

```bash
php artisan migrate:fresh --seed
```

## User Account
- **Email:** user@mail.com
- **Password:** password

## Admin Account
- **Email:** admin@mail.com
- **Password:** password

## Super Admin Account
- **Email:** superadmin@mail.com
- **Password:** password
