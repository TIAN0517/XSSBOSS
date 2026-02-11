# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

XSS Platform - A security research/educational tool for testing XSS (Cross-Site Scripting) vulnerabilities. Allows security researchers to create projects with XSS modules that capture cookies, keystrokes, and other data from test targets.

## Development Commands

```bash
# Run PHP built-in server for development
php -S localhost:8000 -t /root/d3cd1-main/www

# Server management (production)
systemctl restart mysql php8.3-fpm nginx
```

## Architecture

### Entry Points
- **`www/xss.php`** - Main router/controller that dispatches to `source/{do}.php` based on `?do=` parameter
- **`www/init.php`** - Bootstrap file that loads `.env`, initializes database (BlueDB), and sets up Smarty

### Routing
Supported `do` values: `index`, `login`, `project`, `module`, `code`, `api`, `do`, `register`, `user`, `keepsession`, `admin`

### Directory Structure
| Path | Purpose |
|------|---------|
| `www/source/` | Core PHP controllers |
| `www/source/class/` | PHP classes (BlueDB, User, Security, TelegramBot, etc.) |
| `www/source/api/` | API endpoints |
| `www/themes/{theme}/templates/` | Smarty templates |
| `www/libs/` | Third-party libraries (Smarty) |
| `www/sql/` | Database schemas |

### Database Tables (prefix: `oc_`)
- `oc_user`, `oc_session` - User management
- `oc_project`, `oc_module` - Projects and XSS modules
- `oc_project_content` - Captured data (cookies, keystrokes)
- `oc_payment_orders`, `oc_user_subscriptions` - Payment system
- `oc_users` - Telegram bot user storage

## Smarty Pattern

```php
$smarty = InitSmarty();
$smarty->assign('key', $value);
$smarty->display('template.html');
```

## Key Classes

| Class | Purpose |
|-------|---------|
| `Config` | Loads `.env` configuration |
| `Security` | XSS filtering, SQL injection protection, rate limiting |
| `BlueDB` | MySQL database wrapper with prepared statements |
| `User` | Authentication and session management |

## Configuration

Environment variables are managed in `www/.env`:
- Database credentials
- Telegram Bot token/admin ID
- TRON/USDT payment settings
- Security settings (encryption key, allowed origins)

## Important Notes

- This is a security testing tool - use only for legitimate security research
- Admin user ID is `1` for permission checks
- Templates use Smarty `{literal}` blocks for raw CSS
