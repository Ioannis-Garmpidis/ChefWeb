# ChefWeb – A Social Network of Recipes

**ChefWeb** is a dynamic web application that allows users to share, view, and interact with recipes. It functions as a simple social network focused on cooking and food inspiration.

## 🧑‍🍳 Features

- User registration and login system (with session management)
- Guest access for viewing content
- Upload, view, and explore recipes
- Like and save recipes (with AJAX interactivity)
- Favorite recipes are saved in user profiles
- Dynamic navigation between recipes (next/previous)
- Interactive recipe display with images and descriptions
- Commenting system per recipe
- Fully responsive design and popup messages
- Simple and clean UI using custom CSS

## 🗂️ File Structure

- `/php/` – Contains all core PHP files for pages and functionality
- `/css/` – All style rules in one central CSS file
- `/media/` – Image files used across the site
- `ChefWeb.sql` – Database file with all necessary tables and sample data

## 💾 Database

The project uses a MySQL database with the following tables:

- `users` – Stores user info and credentials
- `recipes` – Stores recipe titles, content, images, etc.
- `likes` – Tracks likes per user per recipe
- `favorites` – Tracks saved (favorite) recipes per user
- `comments` – Stores user comments per recipe

To set up the database:

1. Import `ChefWeb.sql` via phpMyAdmin or MySQL CLI.
2. Make sure your `db_connection.php` points to the correct database credentials.

## ⚙️ Setup Instructions

1. Clone the repository:
   ```bash
   git clone https://github.com/Ioannis-Garmpidis/ChefWeb.git
