# IT Ticketing System

A web-based IT Ticketing System built with **PHP** and **MySQL**. This application allows users to submit support tickets, track their status, and enables administrators to manage tickets and user accounts.

## Features

- **User Portal**:
    - **Submit Tickets**: Users can report hardware, software, network, or other issues.
    - **Track Tickets**: Real-time status updates on submitted tickets.
    - **Ticket History**: View past tickets (requires login).
- **Admin Dashboard**:
    - **Manage Tickets**: View, update, and close tickets.
    - **Manage Users**: Create and manage user accounts and roles (Admin/Client).
- **Authentication**: secure login system for both Admins and Clients.

## Installation

1.  **Clone the repository**:
    ```bash
    git clone https://github.com/yasseramiri12/IT-Ticketing-System.git
    ```

2.  **Database Setup**:
    - Import the database schema (if you have an SQL file, otherwise create a database named `ticket`).
    - Create the necessary tables (`users`, `it_ticket`, `manage tickets`).

3.  **Configuration**:
    - Rename `database.example.php` to `database.php`.
    - Open `database.php` and update the credentials to match your local MySQL setup:
      ```php
      $db_server = "localhost";
      $db_user = "root";       // Your MySQL username
      $db_pass = "";           // Your MySQL password
      $db_name = "ticket";     // Your Database name
      ```

4.  **Run the Application**:
    - If using XAMPP/WAMP, place the project folder in `htdocs` or `www`.
    - Or run with PHP's built-in server:
      ```bash
      php -S localhost:8000
      ```
    - Visit `http://localhost:8000/homepage.php` in your browser.

## Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: Native PHP
- **Database**: MySQL

## contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.
