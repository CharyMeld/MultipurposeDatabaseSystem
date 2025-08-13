# Archive Database Management System

A comprehensive system to manage users and candidates with their detailed biodata, applications, related documents, and results. Built using Laravel with a modern frontend stack for seamless staff and archive management.

---

## Features

- **User Management**: Create and manage users with different roles (Superadmin, Admin, User).
- **Candidates**: Manage candidate profiles including biodata, applications, documents, and results.
- **Menus with various modules:**
  - 🏠 Home
  - 👤 User Management
  - 👥 Candidates
  - 🏢 Admin Office
  - 📄 Office Documents
  - 🧑‍🏫 Exam Office
  - 📘 AGSM Resources
  - 💰 Finance
  - 📋 Head of Admin
  - 🧑 Officials
  - ⚙️ Settings

---

## Technology Stack

- Backend: PHP (Laravel Framework)
- Frontend: JavaScript, Vue.js, Bootstrap, CSS
- Database: MySQL
- Development Environment: XAMPP

---

## Installation

1. **Install XAMPP**  
   Download and install XAMPP from [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).

2. **Clone the repository**  
   ```bash
   git clone https://github.com/your-username/your-repo.git
   cd your-repo
Install dependencies

bash
Copy
Edit
composer install
npm install
npm run dev
Configure environment
Copy .env.example to .env and update database credentials:

bash
Copy
Edit
cp .env.example .env
Then edit .env file to set your database credentials.

Generate app key

bash
Copy
Edit
php artisan key:generate
Run migrations and seeders

bash
Copy
Edit
php artisan migrate --seed
Start the development server

bash
Copy
Edit
php artisan serve
User Roles and Access
Superadmin: Full control over the system.

Admin: Manage users, candidates, and office modules.

User: Limited access to candidate data and reports.

Usage
Log in with your credentials.

Navigate through the dashboard menus to manage users, candidates, documents, and other resources.

Use the settings menu for system configurations.

Contributing
Contributions are welcome! Please fork the repo and submit a pull request.

License
This project is licensed under the MIT License.

Contact
For questions or support, reach out to [charlesikeseh@gmail.com].
