# Event Reminder App

## Overview

The **Event Reminder App** is designed to manage event reminders effectively, providing functionalities for creating, updating, deleting, and retrieving events. It supports offline functionality, allowing users to work without an internet connection, and automatically syncs data with the database when online. The application can also send email reminders and import event data from CSV files.

## Features

- **CRUD Operations**: Create, Read, Update, and Delete event reminders.
- **Unique Event IDs**: Automatically generates event reminder IDs with a predefined prefix format.
- **Offline Functionality**: Stores events locally and syncs with the database when a connection is available.
- **Email Reminders**: Sends reminder emails to specified recipients at scheduled times.
- **CSV Import**: Facilitates bulk importing of event reminders from CSV files.
- **Laravel Scheduler**: Uses Laravel's scheduler to manage reminders automatically.

## Technologies Used

- **Backend Framework**: Laravel
- **Database**: MySQL
- **Email Service**: SMTP
- **Frontend**: Livewire (optional)

## Repository

GitHub Repository: [Event Reminder App](https://github.com/omitpavel/event_book.git)

## Installation

### Prerequisites

- PHP >= 8.1
- Composer
- Laravel >= 10.x
- MySQL
- NPM (for front-end dependencies)

### Steps to Install

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/omitpavel/event_book.git
   cd event_book
   ```

2. **Install Dependencies**:
   - Install PHP dependencies using Composer:
   ```bash
   composer install
   ```

   - Install front-end dependencies using NPM:
   ```bash
   npm install
   ```


3. **Set Up Environment Variables**:
  

   - Update the `.env` file with your database and SMTP configurations:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

   MAIL_MAILER=smtp
   MAIL_HOST=your_smtp_host
   MAIL_PORT=your_smtp_port
   MAIL_USERNAME=your_email
   MAIL_PASSWORD=your_email_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your_email
   MAIL_FROM_NAME="${APP_NAME}"
   ```

4. **Run Migrations**:
   - Execute the migrations to set up the database tables:
   ```bash
   php artisan migrate
   ```

5. **Install Frontend Assets** (if using Livewire):
   ```bash
   npm run dev
   ```

6. **Run the Application**:
   ```bash
   php artisan serve
   ```

## Scheduler Setup(Additional)

To automate the sending of email reminders, schedule the command by adding the following to your `app/Console/Kernel.php` file:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('events:send-reminders')->everyMinute();
}
```

## How Online and Offline Functionality Works

- **Offline Functionality**:
  - The application can operate offline by storing events in a local JSON file. This allows users to create and manage events even without an internet connection.
  - When the app detects that the device is online(The Database Server Inserted On ENV Is Accessable), it automatically synchronizes the locally stored events with the database, ensuring all data is up-to-date.

- **Online Functionality**:
  - When online, users can perform CRUD operations, send reminder emails, and import event data directly from CSV files.
  - Email reminders are sent out based on the scheduled start times of events, leveraging the Laravel scheduler for automation.



## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

## Contributing

Contributions are welcome! Please open an issue or submit a pull request for any enhancements or bug fixes.
