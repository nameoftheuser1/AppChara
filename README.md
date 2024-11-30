# AppChara

## Project Description

AppChara is a Point of Sale (POS) and Reservation system for Atsara, a traditional Filipino pickle made from grated unripe papaya. 

### What is Atsara?

Atsara (also spelled *achara* or ***atsara***) is ***a pickle made from grated unripe papaya originating from the Philippines***. It's a popular condiment known for its sweet and tangy flavor.

## Project Logo

![AppChara Logo](/public/img/appchara-logo.png)

## Disclaimer

This project was developed as a thesis and primarily generated using AI assistance.

## Prerequisites

- PHP 8.2
- Composer
- Node.js
- Laravel 11

## Installation Process

### 1. Clone the Repository

```bash
git clone https://github.com/nameoftheuser1/AppChara.git
cd AppChara
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
npm run dev
```

### 4. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure Database

Edit the `.env` file with your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=appchara
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Seed Database (Optional)

```bash
php artisan db:seed
```

### 8. Start the Development Server

```bash
php artisan serve
```

## Running the Application

Access the application by navigating to `http://localhost:8000` in your web browser.

## Technologies Used

- Backend: Laravel 11
- Frontend: Laravel Blade, Tailwind CSS
- Database: MySQL
- Language: PHP 8.2
- Package Manager: Composer, npm

## Contributing

As this is a thesis project, contributions are welcome but please discuss major changes first.

## Acknowledgements

- AI-assisted development
- Laravel Framework
- Open-source community
