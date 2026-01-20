# Pet Adoption Management System

A robust, functional **Pet Adoption Management System** built with **Procedural PHP** and **MySQL**. This application demonstrates core web development concepts including authentication, CRUD operations, database management, security best practices, and dynamic content handling.

## ✨ Features

### Core Features
* **Authentication:** Secure Login and Registration system using MySQL.
* **Role-Based Access:**
    * **Admin:** Full access to manage pets, users, and review adoption requests.
    * **User (Adopter):** Browse pets, manage profile, submit adoption requests, and track status.
* **User Feedback:** Session-based Flash Messages for immediate success/error notifications.
* **Responsive Design:** Optimized for both desktop and mobile interfaces.

### Pet Management (MySQL)
* **CRUD Operations:** Admins can Add, Edit, Delete (securely), and View pet listings.
* **Image Handling:** Secure upload and management of pet images.
* **Search & Filter:** Filter pets by **Category** (Cat, Dog, Bird) or **Breed**.
* **Status Tracking:** Real-time tracking of pet availability (Available / Adopted).

### Adoption System (MySQL)
* **Adoption Lifecycle:** User Request -> Admin Review -> Approval/Rejection -> Status Update.
* **Transaction Safety:** Ensures a pet cannot be requested by multiple users simultaneously if already in process.
* **Request History:** Users can view their past adoption requests and current application status.

### 🛠 Tech Stack

* **Backend:** PHP (Procedural Style)
* **Database:** MySQL (Users, Pets, Adoption Requests)
* **Frontend:** HTML5, CSS3, JavaScript
* **Server:** Apache (via XAMPP)

## 📂 Project Structure

```text
/project-root
    /admin          # Admin dashboard and management logic
    /user           # User dashboard and request logic
    /includes       # Database connection & helper functions (db_connect.php)
    /assets         # Static files
        /css        # Stylesheets
        /js         # JavaScript files
        /images     # Pet images and uploads
    /database       # SQL Schema (pet_adoption_db.sql)
    index.php       # Landing page / Home
    login.php       # Authentication entry point
