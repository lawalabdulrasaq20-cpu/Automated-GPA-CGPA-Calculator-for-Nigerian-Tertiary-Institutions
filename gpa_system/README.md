# GPA & CGPA Calculator

A web-based academic result management system designed for Nigerian tertiary institutions that automates GPA and CGPA calculations using the Nigerian 5-point grading system.

## Features

- ✅ **Course Management**: Add, edit, and delete courses per semester
- ✅ **Nigerian Grading System**: Built-in 5-point scale (A=5, B=4, C=3, D=2, E=1, F=0)
- ✅ **Automatic GPA Calculation**: Instant semester GPA computation
- ✅ **CGPA Aggregation**: Cumulative GPA across all semesters
- ✅ **Academic Standing**: Automatic classification (First Class, Second Class, etc.)
- ✅ **User Authentication**: Secure login and registration system
- ✅ **Responsive Design**: Works on desktop, tablet, and mobile devices
- ✅ **Data Validation**: Prevents invalid entries and ensures data integrity

## Requirements

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Web Server (Apache/Nginx)
- Composer (for dependency management)

## Installation

### 1. Clone/Download the Project

```bash
cd /path/to/your/webserver/gpa-system
```

### 2. Configure Database

Create a MySQL database and import the schema:

```sql
mysql -u username -p database_name < database/schema.sql
```

### 3. Configure Environment

Copy `.env.example` to `.env` and update with your database credentials:

```bash
cp .env.example .env
```

Edit `.env` file:
```
DB_HOST=localhost
DB_NAME=gpa_system
DB_USER=your_username
DB_PASS=your_password
BASE_URL=http://localhost/gpa-system/public
```

### 4. Set Permissions

Ensure the web server has write permissions for the storage directory:

```bash
chmod -R 755 storage/
```

### 5. Access the Application

Open your web browser and navigate to:
```
http://localhost/gpa-system/public
```

## Default Login

- **Email**: admin@example.com
- **Password**: password

## Project Structure

```
gpa-system/
├── public/                  # Web-accessible files
│   ├── index.php           # Main dashboard
│   ├── login.php           # Login/Register page
│   ├── logout.php          # Session termination
│   └── assets/             # CSS, JS, images
│
├── app/                    # Application code
│   ├── Controllers/        # Request handlers
│   ├── Models/             # Data models
│   ├── Helpers/            # Utility classes
│   └── Views/              # Presentation templates
│
├── config/                 # Configuration files
│   ├── app.php            # Application settings
│   ├── database.php       # Database connection
│   └── grading.php        # Grading system config
│
├── database/               # Database files
│   └── schema.sql         # Database schema
│
├── storage/                # Generated files
│   └── pdf/               # PDF storage
│
└── .env                   # Environment variables
```

## Usage

### 1. Register/Login
- Create a new account or use the demo account
- Complete your profile with matriculation number, department, and level

### 2. Create Semesters
- Add semesters as you progress through your academic program
- Include academic session information (e.g., 2023/2024)

### 3. Add Courses
- For each semester, add your registered courses
- Enter course code, title, units, and grade
- System automatically calculates quality points

### 4. View Results
- GPA is calculated automatically for each semester
- CGPA updates automatically across all semesters
- View academic standing classification

### 5. Track Progress
- Monitor your academic performance over time
- Use CGPA to understand graduation prospects
- Plan course loads for optimal performance

## Nigerian Grading System

| Grade | Score Range | Points | Description |
|-------|-------------|--------|-------------|
| A     | 70 - 100    | 5      | Excellent   |
| B     | 60 - 69     | 4      | Very Good   |
| C     | 50 - 59     | 3      | Good        |
| D     | 45 - 49     | 2      | Pass        |
| E     | 40 - 44     | 1      | Low Pass    |
| F     | 0 - 39      | 0      | Fail        |

## Academic Standing

- **First Class**: CGPA ≥ 4.5
- **Second Class Upper**: 3.5 ≤ CGPA < 4.5
- **Second Class Lower**: 2.5 ≤ CGPA < 3.5
- **Third Class**: 1.5 ≤ CGPA < 2.5
- **Pass**: 1.0 ≤ CGPA < 1.5
- **Fail**: CGPA < 1.0

## Security Features

- Password hashing with bcrypt
- Session-based authentication
- CSRF protection
- Input validation and sanitization
- SQL injection prevention with prepared statements

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open source and available under the MIT License.

## Support

For support, please contact the development team or create an issue in the project repository.