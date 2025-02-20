<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This Project

# Lead Management System

##  Features
- **User & Admin Authentication** (Multi-auth with Laravel Breeze)
- **Upload Leads via Excel, Demo excel file `lead.xlsx` is present in 'resource\attachment folder** (Supports `.xlsx` and `.csv` using PhpSpreadsheet)
- **Validate Uploaded Data** (Ensures required fields, unique email, and valid phone numbers)
- **Search & Filter Leads** (Filter by status and creation date)
- **Edit & Delete Leads** (Manage leads from the dashboard)
- **Change Status via AJAX** (Update lead status dynamically)
- **Export Leads to Excel** (Download leads in `.xlsx` format)
- **Pagination for Leads Table** (Improved performance for large data sets)

---

## 🛠 Installation Guide

### 1️ Clone the Repository
```sh
git clone https://github.com/your-repo-url.git
cd your-project-folder
```

### 2️ Install Dependencies
```sh
composer install
npm install && npm run dev
```

### 3️ Setup Environment
```sh
cp .env.example .env
php artisan key:generate
```

### 4️ Configure Database
- Open `.env` and update database credentials:
  ```env
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=your_database_name
  DB_USERNAME=your_db_user
  DB_PASSWORD=your_db_password
  ```

### 5️ Run Migrations & Seeders
```sh
php artisan migrate --seed
php artisan db:seed --class=AdminUserSeeder
```

### 6 Start the Application
```sh
php artisan serve
```

---

##  Uploading Leads
1. Navigate to the dashboard.
2. Click the **Upload Excel** button.
3. Select a `.xlsx` or `.csv` file (Demo excel file `lead.xlsx` is present in 'resource\attachment folder').
4. Click **Upload**.

---

##  Exporting Leads
1. Navigate to the dashboard.
2. Click **Export to Excel** to download the file.

---

##  Troubleshooting
- **Error: `Missing ext-gd` when installing PhpSpreadsheet**
  - Run: `sudo apt install php-gd` (Linux) or `brew install php` (Mac) and restart your server.
- **File size limit exceeded error**
  - Update `.env`:
    ```env
    UPLOAD_MAX_SIZE=5M
    ```
  - Increase `post_max_size` in `php.ini`.
- **Changes not reflecting?**
  ```sh
  php artisan cache:clear
  php artisan config:clear
  php artisan migrate:refresh --seed
  ```

---

## 📜 Test Scenarios

###  Import Leads
1. Upload a valid `.xlsx` file with correct data.
2. Upload an empty file and check for validation errors.
3. Upload a file with duplicate emails and verify error handling.
4. Upload a file with an invalid format (`.txt`, `.pdf`) and ensure it is rejected.
5. Upload a file larger than the allowed size and check for an error message.

###  Update Leads
1. Modify lead information from the dashboard and verify changes.
2. Attempt to update with duplicate email and confirm validation error.
3. Change lead status using the AJAX functionality and ensure updates are reflected.

###  Export Leads
1. Click the **Export to Excel** button and verify the downloaded file.
2. Check if all necessary fields (`name`, `email`, `phone`, `status`, `date_added`) are included.
3. Ensure the exported file format is `.xlsx`.
4. Test export functionality with different lead filters applied.

###  Search & Filter Leads
1. Search by lead name and verify results.
2. Filter by lead status and ensure correct data is displayed.
3. Apply date filters and confirm expected results.

###  Delete Leads
1. Delete a lead from the dashboard and verify removal.
2. Try deleting a non-existent lead and check error handling.

---

##  Contributing
Feel free to fork and contribute! Open a pull request with your improvements.

---







## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
