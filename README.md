# Triya - Laravel + Vue3 Authentication System

A modern, dockerized full-stack application with Laravel 10 backend and Vue 3 frontend, featuring authentication via Laravel Sanctum.

## 🚀 Features

- **Backend**: Laravel 10 with Sanctum authentication
- **Frontend**: Vue 3 + TypeScript + Vite + TailwindCSS
- **Database**: MySQL 8.0 with phpMyAdmin
- **Authentication**: SPA cookie-based auth with CSRF protection
- **UI**: Modern, responsive design with Apple/SaaS aesthetics
- **State Management**: Pinia for Vue 3
- **Routing**: Vue Router with authentication guards

## 🛠️ Tech Stack

### Backend
- Laravel 10
- Laravel Sanctum
- MySQL 8.0
- PHP 8.2 + Nginx

### Frontend
- Vue 3 (Composition API)
- TypeScript
- Vite
- TailwindCSS
- Pinia
- Vue Router
- Axios

### DevOps
- Docker & Docker Compose
- Nginx reverse proxy
- phpMyAdmin

## 📋 Prerequisites

- Docker & Docker Compose
- Node.js 18+ (for local development)
- Git

## 🚀 Quick Start

### 1. Clone and Setup

```bash
git clone <repository-url>
cd Triya
```

### 2. Environment Configuration

Copy the environment example file:
```bash
cp docker/backend/env.example backend/.env
```

### 3. Start Services

```bash
docker-compose up -d
```

This will start:
- Backend: http://localhost:8080
- Frontend: http://localhost:5173
- Database: localhost:3306
- phpMyAdmin: http://localhost:8081

### 4. Backend Setup

```bash
# Enter backend container
docker exec -it triya_backend bash

# Install dependencies
composer install

# Generate application key
php artisan key:generate

# Run migrations and seed database
php artisan migrate --seed
```

### 5. Frontend Setup

```bash
# Enter frontend container
docker exec -it triya_frontend bash

# Install dependencies
npm install

# Start development server
npm run dev
```

## 🔐 Default Credentials

A development user is automatically created:

- **Email**: test@triya.app
- **Password**: password

## 🌐 Service URLs

| Service | URL | Description |
|---------|-----|-------------|
| Frontend | http://localhost:5173 | Vue 3 SPA |
| Backend API | http://localhost:8080 | Laravel API |
| phpMyAdmin | http://localhost:8081 | Database management |
| Database | localhost:3306 | MySQL database |

## 📁 Project Structure

```
Triya/
├── backend/                 # Laravel 10 application
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Auth/    # Authentication controllers
│   │   │   │   └── UserController.php
│   │   │   ├── Requests/    # Form requests
│   │   │   └── Resources/   # API resources
│   │   └── Models/
│   ├── routes/
│   │   └── api.php         # API routes
│   └── database/
│       └── seeders/        # Database seeders
├── frontend/               # Vue 3 application
│   ├── src/
│   │   ├── app/
│   │   │   ├── router/     # Vue Router configuration
│   │   │   └── store/      # Pinia stores
│   │   ├── components/ui/  # Reusable UI components
│   │   ├── pages/          # Page components
│   │   │   ├── auth/       # Authentication pages
│   │   │   └── profile/    # Profile pages
│   │   └── api/            # API client
├── docker/                 # Docker configuration
│   ├── backend/            # Backend Docker setup
│   └── frontend/           # Frontend Docker setup
└── docker-compose.yml      # Service orchestration
```

## 🔧 Development Workflow

### Git Workflow

```bash
# Create feature branch
git checkout -b feature/your-feature-name

# Make changes and commit
git add .
git commit -m "feat: add your feature description"

# Push and create PR
git push origin feature/your-feature-name
```

### Backend Development

```bash
# Enter backend container
docker exec -it triya_backend bash

# Run tests
php artisan test

# Clear caches
php artisan config:clear
php artisan cache:clear
```

### Frontend Development

```bash
# Enter frontend container
docker exec -it triya_frontend bash

# Run development server
npm run dev

# Build for production
npm run build
```

## 🐛 Troubleshooting

### CORS Issues
- Ensure `CORS_ALLOWED_ORIGINS` includes `http://localhost:5173`
- Check that `SANCTUM_STATEFUL_DOMAINS` includes `localhost:5173`

### CSRF Issues
- Frontend automatically fetches CSRF cookie before POST requests
- Verify `SESSION_DOMAIN` is set to `localhost`

### Database Connection
- Ensure MySQL container is running: `docker-compose ps`
- Check database credentials in `.env` file
- Verify network connectivity between containers

### Port Conflicts
- Check if ports 8080, 5173, or 8081 are already in use
- Modify ports in `docker-compose.yml` if needed

## 📚 API Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/sanctum/csrf-cookie` | Get CSRF cookie | No |
| POST | `/api/register` | User registration | No |
| POST | `/api/login` | User login | No |
| POST | `/api/logout` | User logout | Yes |
| GET | `/api/user` | Get user info | Yes |

## 🎨 UI Components

The frontend includes reusable UI components:

- `UiInput` - Form inputs with validation states
- `UiButton` - Buttons with variants and loading states
- `UiCard` - Content containers with headers/footers
- `UiAlert` - Alert messages with different types
- `UiSpinner` - Loading indicators

## 🔒 Security Features

- **Password Hashing**: Bcrypt with Laravel's built-in hashing
- **CSRF Protection**: Automatic CSRF token handling
- **Rate Limiting**: Built-in Laravel rate limiting
- **Session Security**: HTTP-only cookies, secure session handling
- **CORS Configuration**: Proper cross-origin resource sharing setup

## 📝 Environment Variables

Key environment variables for configuration:

```env
# Database
DB_HOST=db
DB_DATABASE=triya
DB_USERNAME=triya
DB_PASSWORD=triya

# Application
APP_URL=http://localhost:8080
SESSION_DOMAIN=localhost

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:5173

# CORS
CORS_ALLOWED_ORIGINS=http://localhost:5173
CORS_SUPPORTS_CREDENTIALS=true
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Check the troubleshooting section above
- Review Laravel and Vue.js documentation
