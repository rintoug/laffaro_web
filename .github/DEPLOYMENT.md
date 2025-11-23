# GitHub Actions Deployment Setup

## Required GitHub Secrets

Go to your GitHub repository → Settings → Secrets and variables → Actions, and add these secrets:

### 1. SERVER_HOST
The IP address or domain of your server
```
Example: 203.0.113.50
or: laffaro.com
```

### 2. SERVER_USER
SSH username for your server
```
Example: deploy
or: ubuntu
or: root
```

### 3. SERVER_SSH_KEY
Your private SSH key for server access
```
Generate a new SSH key pair:
ssh-keygen -t ed25519 -C "github-deploy" -f ~/.ssh/github_deploy

Then copy the PRIVATE key content:
cat ~/.ssh/github_deploy

Copy the PUBLIC key to your server:
ssh-copy-id -i ~/.ssh/github_deploy.pub user@your-server
```

### 4. SERVER_PATH
The full path to your application on the server
```
Example: /var/www/laffaro_com
or: /home/deploy/laffaro_com
```

### 5. SERVER_PORT (Optional)
SSH port (default: 22)
```
Example: 22
or: 2222 (if you use custom SSH port)
```

## Server Setup Requirements

### 1. Install Git on Server
```bash
sudo apt update
sudo apt install git
```

### 2. Clone Repository on Server
```bash
cd /var/www
sudo git clone https://github.com/YOUR_USERNAME/laffaro_com.git
cd laffaro_com
```

### 3. Set Proper Permissions
```bash
sudo chown -R www-data:www-data /var/www/laffaro_com
sudo chmod -R 755 /var/www/laffaro_com
sudo chmod -R 775 /var/www/laffaro_com/storage
sudo chmod -R 775 /var/www/laffaro_com/bootstrap/cache
```

### 4. Configure Git to Allow Directory
```bash
cd /var/www/laffaro_com
git config --global --add safe.directory /var/www/laffaro_com
```

### 5. Create .env File on Server
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your production settings:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
DB_HOST=localhost
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

### 7. Run Migrations
```bash
php artisan migrate --force
php artisan storage:link
```

### 8. Setup Sudoers (Optional - for PHP-FPM restart)
```bash
sudo visudo
```
Add this line (replace `deploy` with your username):
```
deploy ALL=(ALL) NOPASSWD: /usr/bin/systemctl restart php8.2-fpm
```

## Nginx Configuration Example

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/laffaro_com/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Deployment Workflow

1. **Make changes** to your local code
2. **Commit** changes: `git commit -am "Your message"`
3. **Push** to GitHub: `git push origin main`
4. **GitHub Actions** automatically:
   - Checks out code
   - Installs dependencies
   - Builds assets
   - SSHs into your server
   - Pulls latest code
   - Updates dependencies
   - Runs migrations
   - Clears cache
   - Restarts PHP-FPM

## Verify Deployment

Check GitHub Actions:
- Go to your repository
- Click "Actions" tab
- View the latest workflow run
- Check logs for any errors

## Troubleshooting

### Permission Errors
```bash
sudo chown -R www-data:www-data /var/www/laffaro_com
sudo chmod -R 755 /var/www/laffaro_com
sudo chmod -R 775 /var/www/laffaro_com/storage
sudo chmod -R 775 /var/www/laffaro_com/bootstrap/cache
```

### Git Pull Fails
```bash
cd /var/www/laffaro_com
git config --global --add safe.directory /var/www/laffaro_com
git reset --hard origin/main
```

### PHP-FPM Service Name
If you get errors about PHP-FPM, check your service name:
```bash
systemctl list-units | grep php
```
Update the workflow file with correct service name (e.g., `php8.1-fpm`, `php-fpm`)

### Build Fails
Check Node.js and PHP versions match your local environment.
Update the workflow file versions if needed.
