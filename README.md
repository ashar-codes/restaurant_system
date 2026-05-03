# 🍽️ Saffron – Restaurant Management System

A full-stack restaurant management system built using **PHP, MySQL, HTML, and CSS**.
The system supports multiple user roles and simulates real-world restaurant operations including ordering, delivery, and administration.

---

## 🚀 Live Demo

🌐 http://saffron.42web.io/login.php

---

## 📌 Features

### 👤 Authentication & Roles

* User registration and login system
* Role-based access control
* Multiple roles per user (Customer, Admin, Manager, Rider, Waiter)

---

### 🧑‍🍳 Customer

* Browse menu items
* Add items to cart
* Place orders (delivery or dine-in)
* Choose payment method (cash/card)

---

### 👨‍💼 Manager

* Create staff accounts (Admin, Waiter, Rider)
* Assign roles to existing users
* Manage user access

---

### 🛠️ Admin

* Add, update, delete menu items (CRUD)
* View all orders
* Update order status (Pending → Preparing → Delivered)
* Assign riders to delivery orders

---

### 🚴 Rider

* View assigned delivery orders
* Mark orders as delivered

---

### 🧑‍🍽️ Waiter

* Take dine-in orders using table numbers
* Select menu items and quantities
* Place orders for customers inside restaurant

---

## 🧱 Tech Stack

* **Frontend:** HTML, CSS
* **Backend:** PHP
* **Database:** MySQL
* **Hosting:** InfinityFree

---

## 🗄️ Database Structure

Key tables:

* `users` – stores user accounts
* `roles` – list of roles
* `user_roles` – many-to-many mapping
* `menu` – food items
* `orders` – order details
* `order_items` – items inside each order

---

## 🔐 Security Notes

⚠️ Current version uses plain-text passwords (for learning purposes).

Recommended improvements:

* Use `password_hash()` and `password_verify()`
* Use prepared statements to prevent SQL injection

---

## 🎨 UI Design

* Dark theme with warm restaurant colors
* Card-based layout
* Responsive structure
* Consistent design across all roles

---

## ⚙️ Setup Instructions

### 1. Clone or Download Project

```bash
git clone https://github.com/your-username/restaurant-system.git
```

---

### 2. Setup Database

* Create MySQL database
* Import provided `.sql` file

---

### 3. Configure Connection

Edit `connect.php`:

```php
$conn = mysqli_connect("HOST", "USERNAME", "PASSWORD", "DATABASE");
```

---

### 4. Run Project

Using XAMPP:

```text
http://localhost/your_project/login.php
```

---

## 🌍 Deployment

Deployed using **InfinityFree**:

* Upload files to `htdocs`
* Create MySQL database
* Update `connect.php` with live credentials

---

## 🔮 Future Improvements

* Payment gateway integration
* Real-time order tracking
* Image upload for menu items
* Admin analytics dashboard
* Mobile app version

---

## 👨‍💻 Author

**Muhammad Ashar**

---

## ⭐ Project Status

✔ Completed core system
✔ Fully deployed
🔧 Open for improvements and enhancements

---

## 📣 Feedback

Feel free to suggest improvements or report issues!
