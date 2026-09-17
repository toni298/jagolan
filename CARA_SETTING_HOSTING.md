# 🚀 Panduan Hosting Laravel - Jagoland

## Daftar Isi

1. [Setting File .env](#1-setting-file-env)
2. [Server Requirements](#2-server-requirements)
3. [Nginx Configuration](#3-nginx-configuration)
4. [Deployment Steps](#4-deployment-steps)
5. [Cron Job (Scheduler)](#5-cron-job-scheduler)
6. [Queue Worker](#6-queue-worker)
7. [DNS Settings](#7-dns-settings)
8. [Post-Deployment Checklist](#8-post-deployment-checklist)
9. [Monitoring & Maintenance](#9-monitoring--maintenance)
10. [Rekomendasi Hosting](#10-rekomendasi-hosting)

---

## 1. Setting File `.env`

### URL & Environment

```env
APP_NAME="Jago Bangun Persada"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.jagoland.com
```

### Database (sesuaikan dengan hosting)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jagoland_db
DB_USERNAME=jagoland_user
DB_PASSWORD=your_secure_password
```

### Mail (untuk notifikasi, contact form, dll)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="info@jagoland.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Cache & Session (Production)

```env
CACHE_DRIVER=redis  # atau file
SESSION_DRIVER=redis  # atau database
QUEUE_CONNECTION=redis  # atau database
```

### Filesystem

```env
FILESYSTEM_DISK=public
```

---

## 2. Server Requirements

### Software

- **PHP**: 8.1 atau lebih baru
- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **Web Server**: Nginx (recommended) atau Apache
- **SSL Certificate**: Let's Encrypt (gratis)
- **Node.js**: Untuk build Vite assets

### PHP Extensions yang diperlukan

- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- GD
- Zip

### Spesifikasi Minimum Server

- RAM: 2GB
- CPU: 1 Core
- Storage: 20GB SSD

---

## 3. Nginx Configuration

Buat file `/etc/nginx/sites-available/jagoland`:

```nginx
server {
    listen 80;
    server_name jagoland.com www.jagoland.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name jagoland.com www.jagoland.com;

    root /var/www/jagoland/public;
    index index.php;

    # SSL
    ssl_certificate /etc/letsencrypt/live/jagoland.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/jagoland.com/privkey.pem;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    # Logs
    access_log /var/log/nginx/jagoland-access.log;
    error_log /var/log/nginx/jagoland-error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # Deny access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Enable site

```bash
sudo ln -s /etc/nginx/sites-available/jagoland /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 4. Deployment Steps

```bash
# 1. Clone project
cd /var/www
git clone your-repo-url jagoland
cd jagoland

# 2. Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# 3. Setup permissions
sudo chown -R www-data:www-data /var/www/jagoland
sudo chmod -R 755 /var/www/jagoland
sudo chmod -R 775 storage bootstrap/cache

# 4. Copy & edit .env
cp .env.example .env
nano .env  # Edit sesuai setting di atas

# 5. Generate app key
php artisan key:generate

# 6. Run migrations
php artisan migrate --force

# 7. Link storage
php artisan storage:link

# 8. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9. Setup SSL (Let's Encrypt)
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d jagoland.com -d www.jagoland.com

# 10. Setup auto-renewal SSL
sudo systemctl enable certbot.timer
```

---

## 5. Cron Job (Scheduler)

Tambahkan ke crontab (`crontab -e`):

```bash
* * * * * cd /var/www/jagoland && php artisan schedule:run >> /dev/null 2>&1
```

---

## 6. Queue Worker

Jika menggunakan queue untuk background jobs (seperti image optimization):

### Buat service file `/etc/systemd/system/laravel-queue.service`:

```ini
[Unit]
Description=Laravel Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/jagoland/artisan queue:work --sleep=3 --tries=3
StandardOutput=append:/var/log/laravel-queue.log
StandardError=append:/var/log/laravel-queue-error.log

[Install]
WantedBy=multi-user.target
```

### Enable dan start service:

```bash
sudo systemctl enable laravel-queue
sudo systemctl start laravel-queue
sudo systemctl status laravel-queue
```

---

## 7. DNS Settings

Di domain registrar (Niagahoster, Domainesia, dll):

| Type | Name | Value                                |
| ---- | ---- | ------------------------------------ |
| A    | @    | `YOUR_SERVER_IP`                     |
| A    | www  | `YOUR_SERVER_IP`                     |
| MX   | @    | `mail.jagoland.com` (jika ada email) |

**Catatan:** Propagasi DNS bisa memakan waktu 24-48 jam.

---

## 8. Post-Deployment Checklist

- [ ] SSL Certificate aktif (https://www.jagoland.com)
- [ ] Database terkoneksi
- [ ] Storage link berfungsi (gambar muncul)
- [ ] Email bisa terkirim
- [ ] Form contact berfungsi
- [ ] WhatsApp link berfungsi
- [ ] Cron job berjalan
- [ ] Queue worker berjalan (jika pakai)
- [ ] Error logging aktif di `storage/logs/laravel.log`
- [ ] Backup database terjadwal
- [ ] Open Graph preview berfungsi (test di Facebook Debugger)

---

## 9. Monitoring & Maintenance

### Cek error log

```bash
# Laravel log
tail -f storage/logs/laravel.log

# Nginx log
tail -f /var/log/nginx/jagoland-error.log
tail -f /var/log/nginx/jagoland-access.log
```

### Clear cache jika ada perubahan

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Backup database

```bash
# Manual backup
mysqldump -u jagoland_user -p jagoland_db > backup_$(date +%Y%m%d).sql

# Restore backup
mysql -u jagoland_user -p jagoland_db < backup_20260702.sql
```

### Update project

```bash
cd /var/www/jagoland
git pull origin main
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 10. Rekomendasi Hosting

### VPS (Recommended)

- **DigitalOcean** - Mulai $6/bulan
- **Linode** - Mulai $5/bulan
- **AWS EC2** - Free tier 12 bulan
- **IDCloudHost** - Provider Indonesia
- **Biznet Gio** - Provider Indonesia

### Shared Hosting

- **Niagahoster** - Pastikan support Laravel
- **Domainesia** - Support Laravel
- **Hostinger** - Support Laravel

### Managed Laravel Hosting

- **Laravel Forge** - $12/bulan (official Laravel)
- **Ploi.io** - Mulai $8/bulan
- **RunCloud** - Mulai $8/bulan

---

## Troubleshooting

### Error 500 Internal Server Error

```bash
# Cek log
tail -f storage/logs/laravel.log

# Fix permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Gambar tidak muncul

```bash
# Pastikan storage link ada
php artisan storage:link

# Cek permissions
sudo chmod -R 775 storage/app/public
```

### Mixed Content (HTTP/HTTPS)

Pastikan `APP_URL` di `.env` menggunakan `https://`

### Database connection error

- Cek kredensial di `.env`
- Pastikan database sudah dibuat
- Cek firewall port 3306

---

## Kontak Support

Jika ada pertanyaan atau masalah, hubungi:

- Email: marketing.snm.jagoland@gmail.com
- WhatsApp: 0858-9000-7460

---

**Terakhir diupdate:** 2 Juli 2026
