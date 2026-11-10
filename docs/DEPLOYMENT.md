# TVA Haute Joaillerie — Production Deployment & Launch Runbook

Comprehensive production deployment, server provisioning, security hardening, and operational maintenance guide for the **TVA Luxury Jewelry E-Commerce Platform**.

---

## 1. System Requirements & Architecture Stack

| Component | Minimum Specification | Production Recommendation |
| :--- | :--- | :--- |
| **Operating System** | Ubuntu 22.04 LTS / Debian 12 / AlmaLinux 9 | Ubuntu 24.04 LTS (HWE Kernel) |
| **PHP Runtime** | PHP 8.3 with FPM | PHP 8.3 (with OPcache & JIT enabled) |
| **PHP Extensions** | `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd` / `imagick`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `zip` |
| **Database Server** | MySQL 8.0+ or MariaDB 10.6+ | MySQL 8.4 LTS (`utf8mb4_unicode_ci`) |
| **Web Server** | Nginx 1.22+ or Apache 2.4+ | Nginx with HTTP/2 and TLS 1.3 |
| **SSL / TLS Certificate** | 2048-bit RSA / ECDSA | Let's Encrypt automated certbot or Cloudflare Origin CA |
| **Node.js** | Node 20.x LTS & npm 10.x | Used during asset compilation pipeline |
| **Memory / CPU** | 2 GB RAM, 1 vCPU | 4 GB+ RAM, 2+ vCPU (with swap space) |

---

## 2. Server Provisioning & Directory Setup

### 2.1 Cloning and Ownership
Assuming web root is located at `/var/www/tva`:

```bash
# Clone the repository
git clone https://github.com/your-organization/tva.git /var/www/tva
cd /var/www/tva

# Set proper Linux permissions
sudo chown -R www-data:www-data /var/www/tva
sudo find /var/www/tva -type f -exec chmod 644 {} \;
sudo find /var/www/tva -type d -exec chmod 755 {} \;

# Grant writable permissions to storage and cache
sudo chmod -R 775 /var/www/tva/storage
sudo chmod -R 775 /var/www/tva/bootstrap/cache
```

### 2.2 Installing Production Dependencies
```bash
# Install PHP dependencies without dev packages
composer install --no-dev --optimize-autoloader --no-interaction

# Build optimized production CSS and JavaScript assets
npm ci
npm run build
```

---

## 3. Environment Configuration (`.env.production`)

Create `/var/www/tva/.env` from `.env.example`:

```env
APP_NAME="TVA Haute Joaillerie"
APP_ENV=production
APP_KEY=base64:GENERATE_VIA_ARTISAN
APP_DEBUG=false
APP_URL=https://tvajewelry.com

LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Production MySQL Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tva_production
DB_USERNAME=tva_db_user
DB_PASSWORD="STRONG_UNGUESSABLE_PASSWORD"

# High-Performance Session & Cache Drivers
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
CACHE_STORE=database
CACHE_PREFIX=tva_cache_
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=tvajewelry.com
SESSION_SECURE_COOKIE=true

# Production Email Delivery (Postmark / Mailgun / AWS SES)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@tvajewelry.com
MAIL_PASSWORD=YOUR_SMTP_KEY
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="concierge@tvajewelry.com"
MAIL_FROM_NAME="TVA Haute Joaillerie Atelier"

# Payment Gateways (Live Credentials)
PAYSTACK_PUBLIC_KEY=pk_live_xxxxxxxxxxxxxxxxxxxxx
PAYSTACK_SECRET_KEY=sk_live_xxxxxxxxxxxxxxxxxxxxx
PAYSTACK_PAYMENT_URL=https://api.paystack.co

STRIPE_KEY=pk_live_xxxxxxxxxxxxxxxxxxxxx
STRIPE_SECRET=sk_live_xxxxxxxxxxxxxxxxxxxxx
```

Generate the unique encryption key:
```bash
php artisan key:generate --force
```

---

## 4. Database Initialization & Optimization

```bash
# Run migrations in production mode
php artisan migrate --force

# Seed essential baseline catalog, shipping methods, and admin user
php artisan db:seed --class=LuxurySeeder --force

# Create public symlink for product imagery and invoices
php artisan storage:link

# Compile and cache application configuration, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 5. Web Server Configuration

### 5.1 Nginx Configuration (`/etc/nginx/sites-available/tva.conf`)

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name tvajewelry.com www.tvajewelry.com;
    return 301 https://tvajewelry.com$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name www.tvajewelry.com;
    return 301 https://tvajewelry.com$request_uri;
    
    ssl_certificate /etc/letsencrypt/live/tvajewelry.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/tvajewelry.com/privkey.pem;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name tvajewelry.com;
    root /var/www/tva/public;

    # SSL / TLS Hardening
    ssl_certificate /etc/letsencrypt/live/tvajewelry.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/tvajewelry.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;

    index index.php index.html;
    charset utf-8;

    # Static Asset Caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff2|woff|ttf|svg|webp)$ {
        expires 365d;
        access_log off;
        add_header Cache-Control "public, no-transform";
    }

    # Core Laravel Routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    # FastCGI PHP-FPM Handler
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Prevent access to hidden files (.env, .git)
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site and restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/tva.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 6. Automation & Scheduled Tasks (Cron & Queues)

### 6.1 Server Crontab for Laravel Scheduler
Run `crontab -e -u www-data` and add:
```cron
* * * * * cd /var/www/tva && php artisan schedule:run >> /dev/null 2>&1
```

### 6.2 Supervisor Configuration for Background Queues
Create `/etc/supervisor/conf.d/tva-worker.conf`:
```ini
[program:tva-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/tva/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/tva/storage/logs/worker.log
stopwaitsecs=3600
```

Start supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start tva-worker:*
```

---

## 7. cPanel / Shared Hosting Alternative Guide

For deployments onto cPanel or shared environments (e.g. GoDaddy cPanel):
1. **Document Root Alignment**:
   - In cPanel **Domains**, set the Document Root for `tvajewelry.com` to `tva/public` (or `public_html/public`).
   - If Document Root cannot be altered from `public_html`: Move the contents of `public/` into `public_html/`, and place all other Laravel directories in a sibling folder (e.g., `tva_app/`), updating `require __DIR__.'/../tva_app/vendor/autoload.php'` in `index.php`.
2. **PHP Version**: Select PHP 8.3 via **MultiPHP Manager** and ensure `bcmath`, `fileinfo`, `gd`, `pdo_mysql`, and `intl` are activated in **MultiPHP INI**.
3. **Database**: Create MySQL database and user in **MySQL Databases** and update `.env`.
4. **Symlink Storage**: In cPanel Terminal or via PHP script, execute `php artisan storage:link`.
5. **Cron Job**: In cPanel **Cron Jobs**, add a job running every minute:
   `* * * * * /usr/local/bin/php /home/username/tva/artisan schedule:run >> /dev/null 2>&1`

---

## 8. Backup & Disaster Recovery Procedures

### 8.1 Automated Nightly Database Dumps
Create `/etc/cron.daily/tva-db-backup`:
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/tva/database"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
mkdir -p "$BACKUP_DIR"

mysqldump -u tva_db_user -p"STRONG_UNGUESSABLE_PASSWORD" tva_production | gzip > "$BACKUP_DIR/tva_backup_$TIMESTAMP.sql.gz"

# Retain backups for 30 days
find "$BACKUP_DIR" -type f -name "*.sql.gz" -mtime +30 -delete
```
Make executable: `chmod +x /etc/cron.daily/tva-db-backup`.

### 8.2 Restoring from Backup
```bash
gunzip < /var/backups/tva/database/tva_backup_20261101_000000.sql.gz | mysql -u tva_db_user -p"STRONG_UNGUESSABLE_PASSWORD" tva_production
```

---

## 9. Post-Launch Verification Checklist

- [ ] **SSL Certificate**: HTTPS padlock verified across all storefront and admin URLs.
- [ ] **SEO & Metadata**: `/sitemap.xml` returns 200 XML; `/robots.txt` disallows admin/cart.
- [ ] **Cart & Checkout**: Add item to cart, apply coupon privilege code, calculate tax and insured shipping.
- [ ] **Payment Gateway**: Live payment charge, webhook callback confirmation, and receipt generation.
- [ ] **PDF Invoices**: Download luxury invoice receipt from `/order/{order_number}/invoice`.
- [ ] **Order Tracking**: Enter order number at `/orders/track` to view courier status.
- [ ] **Client Dashboard**: View purchased high-jewelry pieces, track orders, manage addresses and submit reviews.
- [ ] **Atelier Administration**: View orders, update shipment tracking numbers, adjust vault inventory, manage coupons and export newsletter subscribers.
- [ ] **Security**: CSRF tokens verified on forms, rate limiting functioning on search and newsletter subscriptions.

