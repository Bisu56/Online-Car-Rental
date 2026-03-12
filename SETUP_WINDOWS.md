# Online Car Rental - Windows Setup Guide

## Problem
When running this project on Windows (XAMPP/WAMP), you may encounter:
```
Not Found
The requested URL was not found on this server.
```

This is caused by URL rewriting not being properly configured on Windows Apache.

## Solution

### Step 1: Enable mod_rewrite

1. Locate `httpd.conf`:
   - **XAMPP**: `C:\xampp\apache\conf\httpd.conf`
   - **WAMP**: `C:\wamp64\bin\apache\apache2.4.x\conf\httpd.conf`

2. Open it with Notepad (run as Administrator)

3. Find and uncomment this line (remove `#`):
   ```
   #LoadModule rewrite_module modules/mod_rewrite
   ```
   Change to:
   ```
   LoadModule rewrite_module modules/mod_rewrite
   ```

### Step 2: Allow .htaccess

In the same `httpd.conf` file, add this at the end:

```apache
<Directory "C:\xampp\htdocs\online-car-rental">
    AllowOverride All
</Directory>
```

**Note:** Adjust the path to match your actual project folder location.

### Step 3: Create .htaccess file

Create a file named `.htaccess` in your project root folder with this content:

```apache
RewriteEngine On
RewriteBase /Online%20Car%20Rental/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?/$1 [L]
```

**Important:** If your folder name is different, adjust `RewriteBase` accordingly. Use `%20` for spaces in URLs.

### Step 4: Restart Apache

- **XAMPP**: Open XAMPP Control Panel → Stop Apache → Start Apache
- **WAMP**: Right-click WAMP icon → Restart All Services

## Quick Checklist

- [ ] `mod_rewrite` enabled in httpd.conf
- [ ] `AllowOverride All` set for your project directory
- [ ] `.htaccess` file exists in project root
- [ ] Apache restarted

After completing all steps, the "Not Found" error should be resolved.
