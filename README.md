# DailyTracker

A comprehensive activity and performance tracking system for organizations, built with Laravel.

## Features

- **User Management**: Admin and support team roles, department assignment, registration, and authentication.
- **Activity Tracking**: Create, assign, update, and track activities with priorities, statuses, and categories.
- **Activity Updates**: Log progress and status changes with remarks and user bio details.
- **Categories & Departments**: Organize activities and users for better reporting and filtering.
- **Reports**: Filterable, exportable reports with grouping (by date/month), statistics, and performance metrics.
- **Dashboard**: Daily overview of activities, pending handovers, and recent updates.
- **Comprehensive Test Suite**: Unit and feature tests for all major flows.

## Tech Stack

- **Backend**: Laravel (PHP)
- **Frontend**: Blade, Tailwind CSS
- **Database**: MySQL (default), SQLite (for testing)
- **Testing**: PHPUnit

## Getting Started

### Prerequisites
- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL or SQLite

### Installation
1. **Clone the repository:**
   ```bash
   git clone <your-repo-url>
   cd DailyTracker
   ```
2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```
3. **Copy and configure environment:**
   ```bash
   cp .env.example .env
   # Edit .env to set your database and mail settings
   php artisan key:generate
   ```
4. **Run migrations and seeders:**
   ```bash
   php artisan migrate --seed
   ```
5. **Build frontend assets:**
   ```bash
   npm run build
   ```
6. **Start the development server:**
   ```bash
   php artisan serve
   ```

## Usage

- **Login/Register:**
  - Register as a support team member or login as admin (see seeders for default admin credentials).
- **Dashboard:**
  - View today's activities, pending handovers, and recent updates.
- **Activities:**
  - Create, assign, update, and track activities. Change status and add remarks.
- **Reports:**
  - Filter by date, user, category, or status. Group by date or month. Export as CSV.
- **Performance:**
  - View user and department performance metrics, completion rates, and activity breakdowns.

## Running Tests

- **Unit and Feature Tests:**
  ```bash
  php artisan test
  ```
- Uses SQLite in-memory by default for fast, isolated testing.

## Project Structure

- `app/Models/` — Eloquent models
- `app/Http/Controllers/` — Controllers for activities, reports, auth, etc.
- `database/migrations/` — Database schema
- `database/seeders/` — Initial data
- `resources/views/` — Blade templates
- `tests/` — Unit and feature tests

## Contribution

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/YourFeature`)
3. Commit your changes with clear messages
4. Push to the branch (`git push origin feature/YourFeature`)
5. Open a Pull Request

## License

This project is open-source and available under the [MIT License](LICENSE).
