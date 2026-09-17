# Jagoland Blog Management System

Sistem manajemen blog untuk PT Jago Bangun Persada - Developer Properti Terpercaya.

## Fitur Utama

### Admin Panel
- **Dashboard** - Statistik dan ringkasan data
- **Tipe Produk** - CRUD tipe produk (Rumah Subsidi, Rumah Komersial, dll)
- **Produk** - CRUD produk dengan gallery gambar dan carousel
- **Postingan** - CRUD blog dengan TinyMCE rich text editor
- **Users** - Manajemen user admin

### Frontend Public
- **Beranda** - Hero section, produk unggulan, blog terbaru
- **Halaman Produk** - Filter by tipe, grid layout, detail dengan carousel
- **Blog** - Daftar artikel, detail artikel dengan share button
- **WhatsApp Integration** - Tombol chat WhatsApp di semua halaman

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2)
- **Frontend**: Blade Templates + Bootstrap 5
- **Database**: MySQL
- **Admin Components**:
  - DataTables (Yajra) - Server-side paginated tables
  - Tom Select - Async searchable select
  - TinyMCE - Rich text editor
  - Dropzone.js - File upload
  - Flatpickr - Date picker
  - Cleave.js - Currency input (Rupiah)
  - SignaturePad - Digital signature

## Instalasi

### Prerequisites
- PHP 8.2 atau lebih tinggi
- MySQL
- Composer
- Node.js (optional, untuk asset compilation)

### Steps

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd jagoland-blog
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env` sesuai konfigurasi database Anda:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=jagoland_blog
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Buat Database**
   Buat database `jagoland_blog` di MySQL Anda.

6. **Jalankan Migration & Seeder**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Storage Link**
   ```bash
   php artisan storage:link
   ```

8. **Jalankan Server**
   ```bash
   php artisan serve
   ```

## Default Login

### Admin User 1
- **Email**: admin@jagobangun.co.id
- **Password**: password123

### Admin User 2
- **Email**: superadmin@jagobangun.co.id
- **Password**: admin123456

## Struktur Database

### Tables
1. **users** - Data user admin
   - id (UUID)
   - name, email, password
   - role (admin)
   - avatar, is_active

2. **product_types** - Tipe produk
   - id (UUID)
   - name, slug (unique)
   - description, is_active

3. **products** - Data produk
   - id (UUID)
   - product_type_id (FK)
   - name, slug (unique)
   - banner_image, status

4. **product_images** - Gallery gambar produk
   - id (UUID)
   - product_id (FK)
   - image_path, is_primary, sort_order

5. **posts** - Postingan blog
   - id (UUID)
   - user_id (FK)
   - product_id (FK nullable)
   - title, slug (unique)
   - content, featured_image
   - status, published_at

## Routes

### Public Routes
- `/` - Beranda
- `/produk` - Daftar produk
- `/produk/{slug}` - Detail produk
- `/blog` - Daftar blog
- `/blog/{slug}` - Detail blog

### Auth Routes
- `/login` - Halaman login
- `POST /login` - Proses login
- `POST /logout` - Proses logout

### Admin Routes (requires auth)
- `/admin/dashboard` - Dashboard admin
- `/admin/product-types` - CRUD tipe produk
- `/admin/products` - CRUD produk
- `/admin/posts` - CRUD postingan
- `/admin/users` - CRUD users

## Deployment

### VPS Deployment

1. **Upload files ke VPS**
   ```bash
   scp -r jagoland-blog user@server:/var/www/
   ```

2. **Install dependencies di server**
   ```bash
   cd /var/www/jagoland-blog
   composer install --no-dev --optimize-autoloader
   ```

3. **Konfigurasi environment**
   ```bash
   cp .env.example .env
   # Edit .env sesuai konfigurasi server
   php artisan key:generate
   ```

4. **Jalankan migration**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

5. **Set permissions**
   ```bash
   chown -R www-data:www-data storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

6. **Konfigurasi Web Server**
   
   **Nginx:**
   ```nginx
   server {
       listen 80;
       server_name domain.com;
       root /var/www/jagoland-blog/public;
       
       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }
       
       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```

   **Apache:**
   ```apache
   <VirtualHost *:80>
       ServerName domain.com
       DocumentRoot /var/www/jagoland-blog/public
       
       <Directory /var/www/jagoland-blog/public>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

7. **Restart services**
   ```bash
   systemctl restart nginx
   # or
   systemctl restart apache2
   ```

## Konfigurasi TinyMCE

Untuk menggunakan TinyMCE, Anda perlu mendapatkan API key gratis:
1. Kunjungi https://www.tiny.cloud/auth/signup/
2. Daftar dan dapatkan API key
3. Edit file `resources/views/layouts/admin.blade.php`
4. Ganti `no-api-key` dengan API key Anda:
   ```html
   <script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/6/tinymce.min.js"></script>
   ```

## License

MIT License

Copyright (c) 2026 PT Jago Bangun Persada