# Application Management REST API

A comprehensive REST API built with Laravel 11 for managing job applications, CVs, and job offers. The system enables candidates to create profiles, upload their CVs, and apply for job offers, while recruiters can post and manage job listings.

## 🚀 Features

### User Management
- Registration & Authentication (Sanctum)
- Role-based access control (Candidates/Recruiters)
- Profile management (skills, contact details)

### CV Management
- Upload CVs (PDF, DOCX, max 5MB)
- Store documents securely
- Access previously uploaded CVs

### Job Offer Management
- Create, read, update, and delete job offers
- Filter job offers by category, location, and contract type
- Detailed job descriptions with salary information

### Application System
- Apply for jobs with existing CVs
- Apply to multiple offers in one click
- Application status tracking

### Asynchronous Processing
- Email confirmations after applications
- Weekly CSV report generation for recruiters
- Background job processing for performance

## ⚙️ Technologies Used

- **Framework**: Laravel 11
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **File Storage**: Local storage with extensibility for AWS S3
- **Queue System**: Laravel Queues with Database driver
- **API Documentation**: [[API-DOCUMENTATION.md](https://github.com/YassineElHassani/Application-Management-REST-API/blob/main/API-DOCUMENTATION.md)]

## 📋 Requirements

- PHP 8.2+
- Composer
- MySQL
- [Other requirements]

## 🔧 Installation

1. Clone the repository:
```bash
git clone https://github.com/YassineElHassani/Application-Management-REST-API.git
```

```bash
cd Application-Management-REST-API
```

2. Install dependencies:
```bash
composer install
```

3. Copy the example environment file and modify it according to your needs:
```bash
cp .env.example .env
```

4. Generate an application key:
```bash
php artisan key:generate
```

5. Run migrations:
```bash
php artisan migrate
```

6. Configure storage for CV uploads:
```bash
php artisan storage:link
```

7. Start the development server:
```bash
php artisan serve
```

## 🔑 API Endpoints

### Authentication
- `POST /api/register` - Register a new user
- `POST /api/login` - Login and get token
- `POST /api/logout` - Logout (requires authentication)

### Profile Management
- `GET /api/profile` - Get user profile
- `PUT /api/profile` - Update user profile

### CV Management
- `GET /api/cvs` - List user's CVs
- `POST /api/cvs` - Upload a new CV
- `GET /api/cvs/{id}` - View CV details
- `DELETE /api/cvs/{id}` - Delete a CV

### Job Offers
- `GET /api/job-offers` - List all job offers
- `POST /api/job-offers` - Create a job offer (recruiter only)
- `GET /api/job-offers/{id}` - View job offer details
- `PUT /api/job-offers/{id}` - Update a job offer (recruiter only)
- `DELETE /api/job-offers/{id}` - Delete a job offer (recruiter only)

### Applications
- `GET /api/applications` - List user's applications
- `POST /api/applications` - Apply for a job
- `GET /api/applications/{id}` - View application details
- `PUT /api/applications/{id}` - Update application status (recruiter only)
- `POST /api/apply-multiple` - Apply to multiple job offers

## 🔐 Authentication

This API uses Laravel Sanctum for token-based authentication. To authenticate requests, include the token in the Authorization header:

```
Authorization: Bearer {your_token}
```