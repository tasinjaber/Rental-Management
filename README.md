# Rental Management System
A complete Rental Management System built with CakePHP 4.x, MySQL, and Firebase integration for SMS and Email notifications.

## Project Overview
This repository presents a complete Web-Based Rental Management System developed as part of an academic project. The system is built to handle the full rental lifecycle, starting from property and unit management to tenant handling, rental contracts, payment tracking, and automated reminders.

The main goal of this project is to showcase how modern web development technologies, clean software architecture, and well-known design patterns can be applied to solve real-world problems through a web-based information system.

The application is developed using CakePHP 4.x as the backend framework, MySQL for database management, and Firebase services to support notification features such as SMS and email alerts.

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
The system is designed following the Model–View–Controller (MVC) architectural pattern provided by CakePHP. Controllers are responsible for handling user requests and coordinating workflows, models manage business logic and database interactions through the ORM, and views focus on presenting data to users in a clear and responsive way.

To improve flexibility and maintainability, the Factory Method design pattern is utilized through CakePHP’s Table Locator. This allows dynamic creation and management of database table objects, helping to keep the system loosely coupled, scalable, and easier to extend in the future.

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
