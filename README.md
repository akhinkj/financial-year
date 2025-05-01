# Financial Year

A Laravel-based web application that calculates financial year dates and displays public holidays for the UK and Ireland, excluding weekends.

## Features

- Country selection (UK/Ireland)
- Dynamic year dropdown (10-year range)
- Financial year date calculation with weekend adjustment
- Public holiday display using [Nager.Date API](https://date.nager.at)
- Weekend (Saturday/Sunday) exclusion for all dates

## Prerequisites

- PHP 8.0+
- Composer
- MySQL/SQLite (or any Laravel-supported database)
- Web server (Apache/Nginx) or PHP built-in server

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/financial-year-calculator.git
   cd financial-year
2. Create .env file and set environment variables: you can copy the variables from .env.example file.
3. Database using mysql,
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=financial_calculator
DB_USERNAME=root
DB_PASSWORD=

add the corresponding values for the database connection in .env file

4. Run comand-  php artisan migrate

5. Run the application- php artisan serve