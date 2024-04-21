# Ping CRM

Demo Aplikasi Rental Mobil RK.

![](https://github.com/zahariz/rental-mobil-rk/blob/main/rental-mobil-screenshot.png)

## Installation

Clone the repo locally:

```sh
git clone https://github.com/zahariz/rental-mobil-rk.git
cd rental-mobil-rk
```

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm install
```

Build assets:

```sh
npm run dev
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```

Run the dev server (the output will give the address):

```sh
php artisan serve
```

Login sebagai user :

- **Email:** user@mail.com
- **Password:** rahasia


Login sebagai admin :

- **Email:** admin@mail.com
- **Password:** rahasia


Tools yang digunakan :

- **Frontend:** Vue.Js, Inertia.Js
- **Backend:** Laravel 10
- **Database:** MySQL

