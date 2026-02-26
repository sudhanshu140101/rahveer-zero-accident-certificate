🛡️ RAHVEER – Zero Accident Bharat Driver Safety Pledge Certificate
[![Live Demo](https://img.shields.io/badge/Live-Demo-brightgreen)](https://rahveer.com/)[![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?logo=php)](https://www.php.net/)[![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?logo=mysql)](https://www.mysql.com/)
A web application for the 
**Zero Accident Bharat** driver safety mission. Users take a road safety pledge (Hindi & English), submit their details, and receive an official downloadable certificate. 
Built with PHP, MySQL, and a responsive frontend—deployed on GoDaddy.
## 🌐 Live Website| Page | URL ||------|-----||
**Home / Pledge Form** | [https://rahveer.com/](https://rahveer.com/) || 
**Admin Login** | [https://rahveer.com/admin/login.php](https://rahveer.com/admin/login.php) |
---## ✨ Features### 
User-facing- 
**3-step flow**: Introduction → Take Pledge → Certificate- 
**Bilingual**: Hindi & English (Devanagari support)- 
**Pledge form**: Name, mobile, profession, city/state, optional photo- 
**Unique certificate**: Auto-generated certificate number (e.g. `RAHV-2024-XXX1234`)- 
**Download**: Print or save certificate as PDF-- 
**Mobile-responsive**: Tailwind CSS, works on all devices### Admin- 
**Secure login**: Username/password protected dashboard-- 
**View submissions**: All pledge entries in a table- **Stats**: Total pledges, today's count, active days-- 
**Export/Print**: Bulk data export### Security- PDO prepared statements (SQL injection protection)- Input validation & sanitization- File upload checks (type, size)- Session-based admin auth- `.htaccess` protection for config and uploads---
## 🛠️ Tech Stack| Layer | Technology ||-------|------------|| Frontend | HTML5, CSS3, Tailwind CSS, JavaScript || Backend | PHP 7.4+ || Database | MySQL 5.7+ || Server | Apache (mod_rewrite) || Hosting | GoDaddy (self-deployed) |---
## 📁 Project Structure
rahveer-zero-accident-certificate/
├── index.html # Landing page & certificate form
├── submit_form.php # Form handler (validation, DB insert, certificate #)
├── config.php # DB config & app settings
├── database.sql # MySQL schema (pledges, admin_logs)
├── test_connection.php # DB connection test (remove in production)
├── .htaccess # Apache security rules
├── .gitignore
├── admin/
│ ├── index.php # Redirect to login
│ ├── login.php # Admin authentication
│ ├── dashboard.php # View all submissions
│ └── logout.php # Logout
├── images/ # Logos, signatures, assets
└── uploads/ # User-uploaded photos
├── .htaccess
└── index.php
---## 🚀 Setup 
(Local / New Deployment)1. **Clone the repo**   git clone https://github.com/sudhanshu140101/rahveer-zero-accident-certificate.git   
cd rahveer-zero-accident-certificate   
Create MySQL database
Create a database and user in phpMyAdmin (or your host). Import the schema:
   mysql -u your_user -p your_database < database.sql
Or import database.sql via phpMyAdmin.
Configure the app
Copy or edit config.php and set:
Database: host, name, user, password
Admin: username and password
SITE_URL (e.g. https://rahveer.com)
Set permissions
Ensure uploads/ is writable (e.g. 755 or 775).
Optional
Run test_connection.php to verify DB connection, then remove it in production.
🌐 Deployment (GoDaddy)
This project is deployed on GoDaddy:
Files uploaded to public_html (or your subdomain folder)
MySQL database created in GoDaddy cPanel
config.php updated with GoDaddy DB credentials and SITE_URL
SSL/HTTPS enabled for the domain
Admin credentials changed from defaults
📜 License & Credits
RAHVEER – Zero Accident Bharat Driver Safety Mission
Supported by Road Grip Technologies
In association with Ministry of Road Transport & Highways, Government of India
© 2024 RAHVEER Driver Safety Mission | Zero Accident Bharat
"Ek Sankalp, Surakshit Bharat"
