# Business Listing & Rating System

## Overview

The **Business Listing & Rating System** is a web application built using **Core PHP, MySQL, jQuery, AJAX, Bootstrap, and the Raty jQuery plugin.**

It allows users to manage businesses (CRUD operations) and submit ratings for businesses with real-time updates.

The project focuses on clean backend logic, proper database design, third-party plugin integration, and seamless UI updates without page refresh.

This project was developed as a **machine test assignment** to demonstrate practical full-stack development skills using a non-framework PHP stack.

---

## Tech Stack

- PHP (Core PHP, no framework)
- MySQL
- jQuery
- AJAX
- Bootstrap 5
- Raty jQuery Plugin
- HTML5 / CSS (Tailwind utility classes for styling)

---

## Features

### Business Management (CRUD)
- Business listing in tabular format
- Add business using Bootstrap modal (AJAX-based)
- Edit business with pre-filled modal data
- Delete business with confirmation modal
- Real-time table updates (no page refresh)

### Rating System
- Star-based rating using Raty jQuery Plugin
- Rating scale: **0–5**
- Half-star support (e.g. 4.5)
- Read-only average rating display in listing
- Clickable stars to open rating modal

### Rating Logic
- Users submit:
    - Name
    - Email
    - Phone
    - Rating
- If **email OR phone already exists** for the same business:
    - Existing rating is **updated**
- Otherwise:
    - New rating is **inserted**
- Average rating is recalculated dynamically using SQL aggregation

### Real-Time Updates
- Average rating updates instantly after submission
- UI reflects changes immediately
- No page reload required

---

## Database Structure

### businesses

```text
| Column | Type |
|------|------|
| id | INT (PK, AUTO_INCREMENT) |
| name | VARCHAR |
| address | VARCHAR |
| phone | VARCHAR |
| email | VARCHAR |
| created_at | TIMESTAMP |
```

### ratings

```text
| Column | Type |
|------|------|
| id | INT (PK, AUTO_INCREMENT) |
| business_id | INT (FK → businesses.id) |
| name | VARCHAR |
| email | VARCHAR |
| phone | VARCHAR |
| rating | DECIMAL(2,1) |
| created_at | TIMESTAMP |
```

- Foreign key constraint with `ON DELETE CASCADE`
- Average rating calculated using `AVG(rating)`

---

## Project Structure

```text
business-rating-sytem/
├── ajax/
│   ├── business_add.php
│   ├── business_delete.php
│   ├── business_update.php
│   ├── rating_add_update.php
├── assets/
│   ├── images/
│   │   ├── raty/
│   │   │   ├── star-half.png
│   │   │   ├── star-off.png
│   │   │   ├── star-on.png
│   ├── js/
│   │   ├── app.js
├── config/
│   ├── db.php
├── includes/
│   ├── footer.py
│   ├── header.py
├── sql/
│   ├── database.sql
├── .env.example
├── .gitignore
├── index.php
├── README.md
```

---

## Setup Instructions

### Prerequisites

- PHP 8.x
- MySQL
- Apache (XAMPP / WAMP / LAMP)

### Installation Steps

1. Clone the repository (or extract zip)
2. Place project inside web root (`htdocs` for XAMPP)
3. Create database and tables
4. Configure environment variables
5. Run the application in browser

### Database Setup

1. Create Database:

```text
CREATE DATABASE <YOUR_DB_NAME> CHARACTER SET utf8mb4;
USE <YOUR_DB_NAME>;
```

2. Import Tables From:

```text
sql/database.sql
```

---

## Environment Variables

Create a `.env` file using the `.env.example` file as reference.

Required variable:

- DB_HOST="YOUR_DB_HOST"
- DB_PORT="YOUR_DB_PORT"
- DB_NAME="YOUR_DB_NAME"
- DB_USER="YOUR_DB_USER"
- DB_PASS="YOUR_DB_PASSWORD"

---

## Running the Application

Open browser and visit:

```text
http://localhost/business-rating-system/
```

---

## Testing Checklist

- [x] Business listing loads correctly
- [x] Add business without page refresh
- [x] Edit business with pre-filled modal
- [x] Delete business with confirmation modal
- [x] Rating modal opens on star click
- [x] Half-star ratings supported
- [x] Rating overwrite logic works (email OR phone)
- [x] Average rating updates in real time
- [x] Page reload shows persisted data

---

## Key Design Decisions

- Average rating is **not stored** in database
    - Calculated using SQL `AVG()` for accuracy
- Shared modal used for add & edit to reduce duplication
- Prepared statements used for SQL safety
- Event delegation used for dynamic elements
- Raty `scoreName` used for reliable rating submission

---

## Submission Checklist

- [x] Source code included
- [x] Database SQL file provided
- [x] Environment configuration documented
- [x] No hard-coded credentials
- [x] Application runs locally without errors
- [x] All functional requirements implemented
- [x] README documentation is complete

---

## Notes

This project demonstrates practical usage of **Core PHP with AJAX**, real-world UI handling, and clean database interaction without relying on frameworks.

---

## Author

**Kailash Bhagchandani**