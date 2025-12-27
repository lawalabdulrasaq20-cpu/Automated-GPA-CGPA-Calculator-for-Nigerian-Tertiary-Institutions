# Quick Installation Guide

## Prerequisites

1. **Web Server**: Apache or Nginx
2. **PHP**: Version 8.1 or higher
3. **MySQL**: Version 8.0 or higher
4. **Composer**: PHP dependency manager

## Step-by-Step Installation

### 1. Copy Files to Web Server

Upload the entire `gpa-system` folder to your web server's document root (e.g., `htdocs`, `www`, or `public_html`).

### 2. Create Database

Login to MySQL and create a database:

```sql
CREATE DATABASE gpa_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Import Database Schema

Import the database structure:

```bash
# Using MySQL command line
mysql -u username -p gpa_system < database/schema.sql

# Using phpMyAdmin
- Login to phpMyAdmin
- Select the `gpa_system` database
- Click "Import" tab
- Choose `database/schema.sql` file
- Click "Go"
```

### 4. Configure Environment

Copy the environment file:

```bash
cp .env.example .env
```

Edit `.env` with your database credentials:

```
DB_HOST=localhost
DB_NAME=gpa_system
DB_USER=your_mysql_username
DB_PASS=your_mysql_password
BASE_URL=http://your-domain.com/gpa-system/public
```

### 5. Install Dependencies (Optional)

If you have Composer installed:

```bash
composer install
```

### 6. Set File Permissions

Ensure proper file permissions:

```bash
# Linux/Mac
chmod -R 755 storage/
chmod -R 755 config/
chmod -R 644 .env

# Windows (use File Explorer properties)
# Right-click on storage folder → Properties → Security → Set appropriate permissions
```

### 7. Configure Web Server

#### For Apache:

Ensure `mod_rewrite` is enabled:

```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

The included `.htaccess` file will handle URL rewriting.

#### For Nginx:

Add this to your server block:

```nginx
location /gpa-system {
    root /path/to/your/webroot;
    index index.php;
    try_files $uri $uri/ /gpa-system/public/index.php?$query_string;
}

location ~ \.php$ {
    include snippets/fastcgi-php.conf;
    fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
}
```

### 8. Access the Application

Open your web browser and navigate to:

```
http://your-domain.com/gpa-system/public
```

Or if using localhost:

```
http://localhost/gpa-system/public
```

### 9. Login with Demo Account

Use the default demo account:

- **Email**: admin@example.com
- **Password**: password

## Troubleshooting

### Database Connection Error

1. Check if MySQL is running
2. Verify database credentials in `.env`
3. Ensure database exists and user has permissions

### "Page Not Found" Error

1. Check if `mod_rewrite` is enabled (Apache)
2. Verify `.htaccess` file exists in root directory
3. Check web server configuration

### PHP Errors

1. Ensure PHP 8.1+ is installed
2. Check that required PHP extensions are installed:
   - php-mysqlnd
   - php-xml
   - php-mbstring
   - php-json
   - php-gd

### Permission Errors

1. Ensure web server has read access to all files
2. Ensure web server has write access to `storage/` directory
3. Check file ownership (should match web server user)

## Development Server

For quick testing, use PHP's built-in server:

```bash
cd gpa-system/public
php -S localhost:8000
```

Then access at: http://localhost:8000

## Next Steps

1. **Register a new account** or use the demo account
2. **Create your first semester**
3. **Add courses** with grades
4. **View automatic GPA calculations**
5. **Track your academic progress**

## Support

If you encounter issues:

1. Check the README.md file
2. Review the troubleshooting section above
3. Ensure all requirements are met
4. Check web server error logs
5. Verify database connection settings

## Security Notes

- Change the demo account password immediately
- Use strong passwords for database and user accounts
- Keep the application and server software updated
- Use HTTPS in production environments
- Regularly backup your database