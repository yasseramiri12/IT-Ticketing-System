# 🛠️ IT Ticketing System

[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![MySQL](https://img.shields.io/badge/database-MySQL-orange.svg)](https://mysql.com)

A professional, web-based IT Ticketing System built for internal company support. This application streamlines the process of reporting technical issues and tracking resolutions between employees and the IT department.

---

## 🚀 Features

### 👤 User Portal (Client)
- **Instant Ticket Submission**: Effortlessly report hardware, software, or network issues.
- **Live Tracking**: Monitor the "Progress" and "Urgency" of your tickets in real-time.
- **Easy Access**: Clean interface designed for quick navigation.

### 🛡️ Admin Dashboard
- **Ticket Lifecycle Management**: View, update progress (In Progress, Completed, Held), and set urgency levels.
- **User Management**: Create, edit, and specialized roles (Admin vs. Client).
- **Control Panel**: Unified view of all organizational IT needs.

---

## 📂 Project Structure

The project follows a clean, organized directory structure for better maintainability:

```text
├── assets/
│   ├── css/        # Component-specific stylesheets
│   └── js/         # Client-side JavaScript logic
├── config/
│   └── database.php # Database connection & environment constants
├── sql/
│   └── schema.sql   # Full database schema and seed data
├── index.php        # Entry point (auto-redirect)
└── [pages].php      # Core application pages (login, admin, etc.)
```

---

## ⚙️ Installation & Setup

### 1. Prerequisites
- PHP 8.1 or higher
- MySQL/MariaDB Server
- A web server (Apache, Nginx, or PHP Built-in Server)

### 2. Clone the Repository
```bash
git clone https://github.com/yasseramiri12/IT-Ticketing-System.git
cd IT-Ticketing-System
```

### 3. Database Configuration
1. Login to your MySQL server.
2. Create a database named `ticket`.
3. Import the schema found in `sql/schema.sql`:
   ```bash
   mysql -u your_username -p ticket < sql/schema.sql
   ```

### 4. Application Configuration
1. Navigate to the `config/` directory.
2. Open `database.php` and update your MySQL credentials:
   ```php
   $db_server = "localhost";
   $db_user   = "your_username";
   $db_pass   = "your_password";
   $db_name   = "ticket";
   ```

### 5. Start the Server
Run with PHP's built-in development server:
```bash
php -S localhost:8000
```
Then visit: **[http://localhost:8000](http://localhost:8000)**
---

## 🛠️ Technologies Used

- **Frontend**: Semantic HTML5, CSS3 (Modern UI), Native JavaScript
- **Backend**: Native PHP (SAPI-compatible)
- **Database**: MySQL with MySQLi Prepared Statements for Security

---

## 🤝 Contributing

Contributions are welcome!
1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License
Distributed under the MIT License. See `LICENSE` for more information.
