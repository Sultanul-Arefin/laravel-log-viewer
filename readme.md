# Laravel Log Viewer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sultanul-arefin/log-viewer.svg?style=flat-square)](https://packagist.org/packages/sultanul-arefin/log-viewer)
[![Total Downloads](https://img.shields.io/packagist/dt/sultanul-arefin/log-viewer.svg?style=flat-square)](https://packagist.org/packages/sultanul-arefin/log-viewer)
[![License](https://img.shields.io/packagist/l/sultanul-arefin/log-viewer.svg?style=flat-square)](https://packagist.org/packages/sultanul-arefin/log-viewer)

A lightweight, secure, and beautiful Laravel log viewer. Easily monitor your application logs, search through entries, and view stack traces without leaving your browser.

---

## Features

* 📂 **View Logs**: Read your `laravel.log` file in a clean, tabular format.
* 🔍 **Search & Filter**: Filter logs by message or level (Info, Error, Warning, etc.).
* 📑 **Pagination**: Support for large log files with customizable row counts (10-50 per page).
* 🔐 **Secure Access**: Built-in email-based authentication gate.
* 🧹 **Clear Logs**: One-click functionality to empty your log file.
* 📱 **Responsive**: Fully responsive UI built with Tailwind CSS.

---

## Installation

You can install the package via composer:

```bash
composer require sultanul-arefin/log-viewer
```
### For Laravel 11+

Add the service provider to your `bootstrap/providers.php` file:
```php
return [
    // ...
    SultanulArefin\LogViewer\LogViewerServiceProvider::class,
];
```

### For Laravel 10 and Below

Add the service provider to your `config/app.php` file:

```php
'providers' => [
    // ...
    SultanulArefin\LogViewer\LogViewerServiceProvider::class,
],
```
## Security Configuration

To protect your logs from unauthorized access, this package uses an email verification gate.

Add the authorized emails to your `.env` file, separated by commas:

```env
LOG_VIEWER_EMAILS=admin@example.com,developer@yourdomain.com
```
## Usage

Once installed, you can access the log viewer at:

```text
your-domain.com/log-viewer
```
## Authentication Flow

- **Redirection:** Upon visiting the URL, you will be redirected to a verification page.
- **Verification:** Enter an email address that is listed in your `LOG_VIEWER_EMAILS` env variable.
- **Access:** If valid, a session is created, and you gain access to the dashboard.
- **Logout:** Click the **Logout** button to securely clear your session.

## Customizing the View (Optional)

If you wish to customize the look and feel of the log viewer, you can publish the views:

```bash
php artisan vendor:publish --tag="log-viewer-views"
```
The files will be copied to:

```text
resources/views/vendor/log-viewer
```
## Changelog

Please see `CHANGELOG.md` for more information on what has changed recently.

## Contributing

Contributions are welcome!

If you have a feature request or find a bug, please open an issue or submit a pull request.

## Credits

- **Author:** Sultanul Arefin
- All Contributors

## License

The MIT License (MIT). Please see the `LICENSE` file for more information.