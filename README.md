# 🎓 BLI Website - Beacon Leadership Institute

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC34A?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js">
</p>

<p align="center">
  A modern, feature-rich platform for the Business Learning Institute, designed to connect learners with industry experts through events, courses, and professional development opportunities.
</p>

---

## 📋 Table of Contents

- [About](#about)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Development](#development)
- [Testing](#testing)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [License](#license)

---

## 🎯 About

The BLI Website is a comprehensive learning management platform that facilitates professional development through:

- **Event Management**: Create, manage, and promote educational events
- **Speaker Directory**: Connect with industry experts and thought leaders
- **Course Catalog**: Browse and enroll in professional development courses
- **Instructor Portal**: Manage teaching opportunities and applications
- **User Management**: Role-based access control for different user types
- **Video Integration**: Seamless integration with Mux and Vimeo for video content

---

## ✨ Features

### 🎪 **Event Management**
- Event creation and management
- Speaker applications and approval workflow
- Attendee registration and management
- Event calendar and scheduling
- iCalendar integration for calendar exports

### 👥 **User Management**
- Multi-role authentication (Students, Instructors, Speakers, Admins)
- User profiles and professional information
- Permission-based access control
- Speaker and instructor application workflows

### 📚 **Course System**
- Course catalog with categorization
- Enrollment management
- Progress tracking
- Multi-media content support

### 🎬 **Media Integration**
- Vimeo video management
- Optimized video delivery
- Video analytics and insights

### 🎨 **Modern UI/UX**
- Responsive design with Tailwind CSS 4.0
- Interactive components with Alpine.js
- Smooth animations with AOS (Animate On Scroll)
- Modern component library with FlyonUI
- Professional iconography with Lucide icons

---

## 🛠 Technology Stack

### **Backend**
- **Framework**: Laravel 12.x
- **Language**: PHP 8.2+
- **Database**: SQLite (development) / MySQL (production)
- **Authentication**: Laravel Sanctum
- **Permissions**: Spatie Laravel Permission
- **Video**: Vimeo API

### **Frontend**
- **CSS Framework**: Tailwind CSS 4.0
- **JavaScript**: Alpine.js 3.x
- **UI Components**: FlyonUI 2.4
- **Animations**: AOS (Animate On Scroll)
- **Icons**: Lucide Icons, Tabler Icons
- **Build Tool**: Vite 6.x

### **Development Tools**
- **Testing**: Pest PHP
- **Code Quality**: Laravel Pint
- **Development**: Laravel Sail
- **Monitoring**: Laravel Pail
- **Performance**: Laravel Boost

---

## 📋 Prerequisites

Ensure you have the following installed on your development machine:

- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **npm** >= 8.x
- **SQLite** (for development)
- **Git**

### Optional but Recommended:
- **Docker** (for Laravel Sail)
- **MySQL** (for production-like development)
- **Redis** (for caching and queues)

---

## 🚀 Installation

### 1. **Clone the Repository**
```bash
git clone https://github.com/emmieIO/bli-website.git
cd bli-website
```

### 2. **Install PHP Dependencies**
```bash
composer install
```

### 3. **Install Node Dependencies**
```bash
npm install
```

### 4. **Environment Setup**
```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. **Database Setup**
```bash
# Create and run migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed
```

### 6. **Build Frontend Assets**
```bash
# For development
npm run dev

# For production
npm run build
```

### 7. **Start the Development Server**
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

---

## ⚙️ Configuration

### **Environment Variables**

Key environment variables to configure:

```bash
# Application
APP_NAME="BLI Website"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database.sqlite

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-password

# Video Services

VIMEO_CLIENT=your-vimeo-client-id
VIMEO_SECRET=your-vimeo-secret

# Support
SUPPORT_MAIL=info@wizbizhubltd.com
```

### **Permissions Setup**
```bash
# Create storage link
php artisan storage:link

# Set proper permissions
chmod -R 775 storage bootstrap/cache
```

---

## 🎮 Usage

### **Quick Start Commands**

```bash
# Start all services (development)
composer run start

# Clear application cache
php artisan optimize:clear

# Run database migrations
php artisan migrate

# Seed sample data
php artisan db:seed

# Create a new admin user
php artisan make:admin-user
```

### **Common Tasks**

- **Access Admin Panel**: `/admin`
- **Speaker Registration**: `/become-a-speaker`
- **Instructor Applications**: `/become-an-instructor`
- **Event Listings**: `/events`
- **Course Catalog**: `/courses`

---

## 📁 Project Structure

```
bli-website/
├── app/
│   ├── Actions/           # Application actions
│   ├── Console/          # Artisan commands
│   ├── Enums/            # Application enums
│   ├── Events/           # Laravel events
│   ├── Http/             # Controllers, middleware, requests
│   ├── Models/           # Eloquent models
│   ├── Policies/         # Authorization policies
│   └── Services/         # Business logic services
├── database/
│   ├── factories/        # Model factories
│   ├── migrations/       # Database migrations
│   └── seeders/          # Database seeders
├── resources/
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── views/            # Blade templates
├── routes/
│   ├── web.php           # Web routes
│   ├── admin.php         # Admin routes
│   ├── courses.php       # Course routes
│   └── speakers.php      # Speaker routes
├── tests/                # Test files
└── public/               # Public assets
```

---

## 💻 Development

### **Development Workflow**

1. **Create Feature Branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make Changes**
   - Follow PSR-12 coding standards
   - Write tests for new features
   - Update documentation as needed

3. **Run Tests**
   ```bash
   php artisan test
   ```

4. **Code Quality Check**
   ```bash
   ./vendor/bin/pint
   ```

5. **Commit Changes**
   ```bash
   git add .
   git commit -m "Add: your feature description"
   ```

### **Available Scripts**

```bash
# Development server with hot reload
npm run dev

# Build for production
npm run build

# Run PHP tests
php artisan test

# Run specific test
php artisan test --filter=TestName

# Format code
./vendor/bin/pint

# Clear all caches
php artisan optimize:clear
```

---

## 🧪 Testing

### **Running Tests**

```bash
# Run all tests
php artisan test

# Run tests with coverage
php artisan test --coverage

# Run specific test suite
php artisan test tests/Feature/EventTest.php

# Run tests in parallel
php artisan test --parallel
```

### **Test Structure**
- **Unit Tests**: `/tests/Unit/` - Test individual classes and methods
- **Feature Tests**: `/tests/Feature/` - Test application workflows
- **Browser Tests**: `/tests/Browser/` - End-to-end testing with Laravel Dusk

---

## 🚢 Deployment

### **Production Deployment**

1. **Server Requirements**
   - PHP 8.2+
   - MySQL 8.0+
   - Redis (recommended)
   - SSL Certificate

2. **Deploy Steps**
   ```bash
   # Pull latest changes
   git pull origin main
   
   # Install dependencies
   composer install --optimize-autoloader --no-dev
   npm ci && npm run build
   
   # Run migrations
   php artisan migrate --force
   
   # Optimize application
   php artisan optimize
   ```

3. **Environment Setup**
   ```bash
   # Production environment
   APP_ENV=production
   APP_DEBUG=false
   
   # Configure database, mail, and other services
   ```

### **Performance Optimization**

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

---

## 🤝 Contributing

We welcome contributions from the community! Here's how you can help:

### **Getting Started**
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Write/update tests
5. Submit a pull request

### **Contribution Guidelines**
- Follow PSR-12 coding standards
- Write comprehensive tests
- Update documentation
- Use conventional commit messages
- Ensure all tests pass

### **Code Style**
- Use Laravel Pint for code formatting
- Follow Laravel best practices
- Write descriptive variable and method names
- Add type hints where appropriate

---

## 📄 License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

## 📞 Support

For support, please contact:

- **Email**: info@wizbizhubltd.com
- **Issues**: [GitHub Issues](https://github.com/emmieIO/bli-website/issues)
- **Documentation**: [Project Wiki](https://github.com/emmieIO/bli-website/wiki)

---

<p align="center">
  <strong>Built with ❤️ for the Business Learning Institute</strong>
</p>
