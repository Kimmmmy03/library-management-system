# Library Management System

## Original Authors
This project is created and maintained by:

* **Akmal Hakimi Bin Abd Rashid**
* **AHMAD AZRI BIN ANUAR**

## Introduction
**Library Management System** is a web-based application developed as part of **ISB42503 - Internet Programming** course at **Universiti Kuala Lumpur (UniKL MIIT)**. The project demonstrates the implementation of a **CRUD-based library system** using PHP and MySQL, providing a practical platform for managing books, user accounts, and rental transactions through a browser-based interface.

The goal of this project is to apply web development fundamentals — server-side scripting, database interaction, and form handling — while delivering a functional system that serves two distinct user roles: **Librarian** and **User (Public)**.

## Problem Statements & Objectives

### Problem Statements
* **Inefficient Manual Tracking**: Managing book inventories, rental records, and payment statuses manually is time-consuming and error-prone.
* **Lack of Centralized Access**: Without a web-based system, librarians and users cannot efficiently access or manage library data from any device.
* **No Rental Workflow**: Traditional systems lack a structured approval workflow for book rentals, leading to miscommunication between librarians and users.

### Objectives
* Develop a fully functional **web-based library management system** with role-based access
* Implement **CRUD operations** (Create, Read, Update, Delete) for books, users, and rental records
* Provide **search and filter** functionality for efficient book navigation
* Build a **rental approval workflow** where users submit requests and librarians approve or reject them
* Apply **form validation** and error handling for reliable data entry
* Display **payment summaries and totals** for financial oversight

## Program Scope
The Library Management System provides the following capabilities:

### Librarian Features

| Module | Capabilities |
| :--- | :--- |
| **Book Management** | Add new books, view/search/filter books by name, price range, and status, update book details, delete books |
| **Rental Oversight** | View all rental records with duration and total cost, approve or reject pending rental requests |
| **Payment Management** | View total payments grouped by user and status, update payment status, calculate approved rental totals |

### User Features

| Module | Capabilities |
| :--- | :--- |
| **Account Management** | Register a new account with name, email, and password, view account details, update profile information |
| **Book Rental** | Browse available books, select a book to rent, submit a rental request |

## Technology Stack

| Layer | Technology |
| :--- | :--- |
| Backend | PHP (procedural) |
| Database | MySQL via `mysqli` extension |
| Frontend | XHTML 1.0 Transitional, CSS |
| Server | Apache (XAMPP / WAMP) |
| Authentication | SHA-1 password hashing, per-request credential verification |

## Database Structure
The system uses a MySQL database named `library` with three core tables:

| Table | Purpose | Key Columns |
| :--- | :--- | :--- |
| `books` | Stores book catalogue | `book_id`, `book_code`, `book_name`, `description`, `rental_price`, `book_status` |
| `users` | Stores registered user accounts | `user_id`, `name`, `email`, `password` |
| `rental` | Tracks rental transactions | `rental_id`, `user_id`, `book_code`, `rental_duration`, `total_rental`, `rental_status` |

### Entity Relationships
* **users → rental**: One-to-Many (a user can have multiple rentals)
* **books → rental**: One-to-Many (a book can appear in multiple rental records)

### Database Setup
```sql
CREATE DATABASE library;
USE library;

CREATE TABLE books (
    book_id      INT AUTO_INCREMENT PRIMARY KEY,
    book_code    VARCHAR(20) NOT NULL UNIQUE,
    book_name    VARCHAR(100) NOT NULL,
    description  TEXT,
    rental_price DECIMAL(10,2) NOT NULL,
    book_status  ENUM('Available','Rented','to be approved') DEFAULT 'Available'
);

CREATE TABLE users (
    user_id  INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(50) NOT NULL,
    email    VARCHAR(100) NOT NULL UNIQUE,
    password CHAR(40) NOT NULL
);

CREATE TABLE rental (
    rental_id       INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT NOT NULL,
    book_code       VARCHAR(20) NOT NULL,
    rental_duration INT DEFAULT 1,
    total_rental    DECIMAL(10,2) NOT NULL,
    rental_status   ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (book_code) REFERENCES books(book_code)
);
```

## Prerequisites
* [XAMPP](https://www.apachefriends.org/) or [WAMP](https://www.wampserver.com/) (PHP 7.4+ with `mysqli` enabled)
* MySQL 5.7+ or MariaDB 10.3+
* A modern web browser

## Installation & Setup

1. **Clone or copy** the project folder into your web server's document root:
   ```
   C:\xampp\htdocs\library\
   ```

2. **Create the database** using phpMyAdmin or the MySQL CLI with the SQL statements provided in the [Database Setup](#database-setup) section above.

3. **Configure the database connection** in `mysqli.php` if your MySQL credentials differ from the defaults (`root` with no password on `localhost`).

4. **Start Apache and MySQL** from the XAMPP/WAMP control panel.

5. **Access the application** in your browser:
   * Librarian Portal: `http://localhost/library/librarian.php`
   * User Portal: `http://localhost/library/user.php`

## Project Structure

```
library/
├── mysqli.php                       # Database connection configuration
├── includes/
│   ├── header_librarian.html        # Librarian page layout and navigation menu
│   ├── header_user.html             # User page layout and navigation menu
│   ├── footer.html                  # Shared HTML footer
│   └── layout.css                   # Site-wide stylesheet
│
├── librarian.php                    # Librarian dashboard
├── librarian_insert.php             # Add new book to catalogue
├── librarian_view.php               # View, search, and filter books
├── librarian_update.php             # Update book details
├── librarian_delete.php             # Delete a book from catalogue
├── librarian_pending_approvals.php  # Approve or reject pending rentals
├── librarian_view_rentals.php       # View all rental records
├── librarian_view_payments.php      # View payments grouped by user
├── librarian_update_payment.php     # Update payment status
├── librarian_calculate_totals.php   # Calculate total approved payments
│
├── user.php                         # User dashboard
├── user_register.php                # New user registration
├── user_view.php                    # View account information
├── user_update.php                  # Update account details
└── user_book_view.php               # Browse and rent available books
```

## Application Workflow

```
User Registration → Browse Books → Select & Rent Book → Rental Request (Pending)
                                                              ↓
Librarian Reviews → Approve / Reject → Payment Status Updated → Totals Calculated
```
