#Rental Management System

## Project Overview
This repository contains the complete implementation of a Web-Based Rental
Management System developed as part of an academic project. The system is
designed to support end-to-end rental operations, including property and unit
management, tenant administration, contract handling, payment processing, and
automated reminders.

The primary objective of the project is to demonstrate the application of modern
web development practices, software architecture principles, and design patterns
in a real-world web-based information system.

The application is implemented using CakePHP 4.x, MySQL, and Firebase services
for notification handling.

---

## Key Features
- **Role-Based Access Control**: Super Admin, Landlord, and Tenant roles
- **Property & Unit Management**: Management of properties and rental units
- **Rental Agreements**: PDF agreement upload with digital signature support
- **Manual Payment System**: Cash and bank transfer payments with verification
- **Payment Verification Workflow**: Approval or rejection with proof
- **PDF Invoices & Receipts**: Automatic generation for verified payments
- **Dispute Management**: Structured tenant–landlord dispute handling
- **Reminder System**: Automated rent due reminders
- **Firebase Integration**: SMS (OTP) and Email notifications
- **Responsive User Interface**: Bootstrap 5 based design

---

## System Architecture
The system follows the **Model–View–Controller (MVC)** architectural pattern
using CakePHP. Controllers manage HTTP requests and workflow coordination,
models handle business logic and database access through the ORM, and views
are responsible for presentation.

The **Factory Method design pattern** is applied through CakePHP’s Table Locator
to manage dynamic creation of database table objects. This approach promotes
loose coupling, scalability, and maintainability across service layers.

---

## Technologies Used
- Backend Framework: CakePHP 4.x
- Programming Language: PHP 8.0+
- Database: MySQL 8.0+
- Architecture Pattern: MVC
- Design Pattern: Factory Method
- Frontend: HTML, CSS, Bootstrap 5
- Notifications: Firebase (SMS & Email)
- Version Control: Git, GitHub

---

## User Roles
- **Super Admin**: Full system access, including user management, property
  assignment, contract administration, and payment oversight.
- **Landlord**: Management of assigned properties, rental units, tenants,
  contracts, and payment verification.
- **Tenant**: Viewing contracts, submitting rental payments, and accessing
  invoices and receipts.

---

## System Requirements
- PHP 8.0+
- MySQL 8.0+
- Composer
- Firebase account (for SMS and Email notifications)
- Apache/Nginx with `mod_rewrite` enabled

---

## Installation and Setup

### 1. Clone the repository
```bash
git clone https://github.com/tasinjaber/Rental-Management
cd rental-management
````

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment

Copy `config/app_local.php.example` to `config/app_local.php` and update:

```php
'Datasources' => [
    'default' => [
        'host' => 'localhost',
        'username' => 'your_db_user',
        'password' => 'your_db_password',
        'database' => 'rental_management',
    ],
],
'Firebase' => [
    'apiKey' => 'your-firebase-api-key',
    'authDomain' => 'your-project.firebaseapp.com',
    'projectId' => 'your-project-id',
],
```

### 4. Run database migrations

```bash
bin/cake migrations migrate
```

### 5. Seed database (optional)

```bash
bin/cake migrations seed --seed UsersSeeder
```

### 6. Set directory permissions

```bash
chmod -R 755 tmp logs webroot/uploads
```

### 7. Access the application

```
http://localhost/rental-management
```

---

## Default Credentials (After Seeding)

* **Admin**: [admin@rental.com](mailto:admin@rental.com) / admin123
* **Landlord**: [landlord@rental.com](mailto:landlord@rental.com) / landlord123
* **Tenant**: [tenant@rental.com](mailto:tenant@rental.com) / tenant123

---

## API Endpoints

### Firebase Phone Authentication

* `POST /api/firebase/send-otp` – Send OTP SMS

```json
{
  "phone_number": "+1234567890"
}
```

* `POST /api/firebase/verify-otp` – Verify OTP

```json
{
  "session_info": "session_info_from_send_otp",
  "code": "123456"
}
```

---

## Project Structure

```
rental-management/
├── config/              # Configuration files
│   ├── Migrations/      # Database migrations
│   └── Seeds/           # Database seeders
├── src/
│   ├── Controller/     # Controllers
│   ├── Model/          # Models and Entities
│   ├── Service/        # Service classes (Firebase, PDF)
│   └── Command/        # Console commands
├── templates/          # View templates
├── webroot/            # Public files
│   └── uploads/        # Uploaded files
└── tests/              # Unit tests
```

---

## Firebase Integration

### SMS Notifications

Firebase Phone Authentication is used to send OTP-based SMS notifications for:

* Payment verification
* Payment rejection
* Rent due reminders

### Email Notifications

Email notifications are handled via Firebase Cloud Functions:

* Payment verified
* Payment rejected
* Agreement signed
* Rent due reminders

### Firebase Cloud Functions Setup

1. Install Firebase CLI:

```bash
npm install -g firebase-tools
```

2. Initialize Firebase:

```bash
firebase init functions
```

3. Deploy functions:

```bash
firebase deploy --only functions
```
---
## Payment Methods
* **Cash**: Payment submission with optional receipt upload
* **Bank Transfer**: Transfer slip upload with reference number
* **Online Payment**: Currently disabled (planned for future work)

---
## Security Considerations
* Password hashing using PHP `password_hash()`
* CSRF protection enabled
* Role-based access control
* File upload validation
* SQL injection prevention via CakePHP ORM

---
## Academic Context
This project was developed as part of the course *Data Engineering and Distributed
Information Systems*. The focus of the project is on applying architectural
patterns, design principles, and secure database-driven web application
development practices.

---
## Usage Note
This project is intended for academic and educational purposes only.
---

## Support
For issues or questions related to the implementation, please open an issue in
this repository.

```
