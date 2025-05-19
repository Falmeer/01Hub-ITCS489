# 01Hub - Computer Components Store

This is the term project for ITCS489 - Software Engineering II, developed at the University of Bahrain.  
01Hub is a fictional e-commerce platform focused on computer components and accessories.

## Project Overview

01Hub simulates a real-world online shop with multiple roles:
- Customer: Browse, search, review, and order components.
- Staff (Supplier): Manage products and orders.
- Admin: Create supplier accounts and monitor platform activity.

### Technologies Used
- PHP (Vanilla, OOP)
- MySQL (Relational Database)
- HTML5, CSS3, Bootstrap 5
- Font Awesome
- JavaScript (AJAX-based product search)
- MVC Architecture (Custom lightweight framework)

## Folder Structure

app/
├── controllers/ # Application controllers (e.g., ProductController, CheckoutController)
├── models/ # Database logic (e.g., ProductModel, OrderModel)
├── views/ # Templates for all pages and user roles
├── core/ # Base Controller, Router, Database connection
public/
├── index.php # Front controller
├── images/ # Uploaded product images
├── css/ # Optional custom styles
├── js/ # Optional custom scripts

## How to Run Locally

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/01Hub-ITCS489.git
Import the database:

Use phpMyAdmin or MySQL CLI to import components.sql.

Ensure the database is named components or update the connection info in Database.php.

Set up local server:

Place the project in htdocs/ (XAMPP) or www/ (WAMP).

Start Apache and MySQL.

Access in browser:

http://localhost/01Hub-ITCS489/public/index.php
Sample login credentials:

Admin: admin / abc123

Customer: Register using the website

Supplier: Created by admin via dashboard

Features
Secure registration and login with hashed passwords

Shopping cart and stock validation at checkout

Live product search and category filtering with AJAX

Customer reviews with average rating display

Staff dashboard for managing products and orders

Admin dashboard to monitor data and create staff accounts

Clear separation of concerns using MVC structure

Team Members
Fawaz Almeer - Group Leader

Abdullah Moatazbellah

Mohammed Janahi

Omar Adnan

Course Information
Course: ITCS489 - Software Engineering II

Instructor: Dr. Taher Saleh

University: University of Bahrain

Semester: Spring 2025

License
This project is for educational purposes only. Not intended for commercial deployment.