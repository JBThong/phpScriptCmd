# User Upload PHP Script

This PHP script processes a CSV file containing user information and inserts it into a PostgreSQL database.

## Requirements

- PHP 8.3
- PostgreSQL 13 or higher
- Composer (optional, for dependency management)
- PHPUnit (for testing)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your_username/user-upload-php.git
   
2. Install dependencies via Composer:
   ```bash
   cd user-upload-php
   composer install
3. Install PHPUnit
   ```bash
   composer require --dev phpunit/phpunit

4. Usage
- Run the script with a CSV file (Dry Run):
   ```bash
   php user_upload.php --file users.csv --dry_run --u username --p secret --h localhost
- Create the users table in PostgreSQL:
   ```bash
   php user_upload.php --create_table --u username --p secret --h localhost
- Run the script with a CSV file
   ```bash
   php user_upload.php --file=users.csv --u username --p secret --h localhost