# Laravel HistoryKeeper Package

The Laravel HistoryKeeper package provides functionality to manage history tables and triggers in your Laravel application. It allows you to create, update, and manage history tables and triggers easily.

## Installation

You can install the package via Composer. Run the following command in your terminal:

```bash
composer require codestepsbd/historykeeper:dev-master
```


## Database Migration

After installation you have to run migration for creating the `table_history_with_settings` table.

You can run the migration using Laravel's migration command:

```bash
php artisan migrate
```

## Usage

### Commands

The package provides a console command `app:update-history-tables-and-triggers` with several options:

- `--makeNewHistoryTable=false`: Creates a new history table if set to `true`.
- `--runTest`: Runs a test to understand package is working or not.
- `--scanMismatch`: Scans for mismatches between the base table and its history table.

You can use the command with options like this:

```bash
php artisan app:update-history-tables-and-triggers --makeNewHistoryTable=true --runTest --scanMismatch
```

### Routes

The package defines several routes under the `history-keeper` prefix:

- `/`: Displays the index page of history data.
- `/url-command/{value?}`: Provides a command URL endpoint. here value can be `runTest` for run test, `true` for creating history tables and `scanMismatch` for Scans for mismatches between the base table and its history table on web browser.

### Middleware

Middleware can be applied to the `history-keeper` routes using the `config("historyKeeper.middleware")` configuration.


## License

This package is open-source software licensed under the [MIT license](LICENSE).

---

Feel free to customize this according to your package's specific details and requirements.
