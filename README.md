# Mkristoh Rental - Car Rental Website

A full-featured car rental website built with HTML, CSS, JavaScript, PHP, and Bootstrap. The system includes user registration, car browsing, rental booking, and an admin panel for management.

## Recent Updates

### Redirection Fixes (Latest)

- **Fixed all file redirections** to work properly from root directory
- **Removed incorrect `../` prefixes** from all redirect paths
- **Updated admin routing** to use `admin-dashboard.php` instead of subdirectory
- **Fixed JavaScript redirects** to use correct file extensions
- **Standardized configuration** to use `php-config.php` consistently

### Key Improvements

- All files now work correctly from a single root directory
- No subdirectory structure required
- Consistent file naming and paths
- Improved error handling and user feedback

## Features

### User Features

- **User Registration & Login**: Secure user authentication with PHP validation
- **Car Browsing**: View available cars with detailed information
- **Rental Booking**: Book cars with date selection and price calculation
- **Rental History**: View past and current rentals
- **Profile Management**: Update personal information
- **Rental Cancellation**: Cancel rentals within 1 hour of booking

### Admin Features

- **Dashboard Overview**: Statistics and recent activity
- **User Management**: View, edit, and manage user accounts
- **Car Management**: Add, edit, and remove cars from the fleet
- **Rental Management**: Manage all rental requests and statuses
- **Reports**: View revenue and rental statistics

### Technical Features

- **Responsive Design**: Mobile-friendly interface using Bootstrap 5
- **PHP Validation**: Server-side validation for all forms
- **MySQL Database**: Secure data storage with mysqli
- **Session Management**: Secure user sessions
- **Modern UI**: Beautiful design with car-themed background
- **AJAX Integration**: Dynamic content loading and form submissions
- **File Structure**: Organized single-directory structure for easy deployment

## Requirements

- **XAMPP** (Apache, MySQL, PHP)
- **PHP 7.4+**
- **MySQL 5.7+**
- **Web Browser** (Chrome, Firefox, Safari, Edge)

## Installation

### 1. Setup XAMPP

1. Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Start Apache and MySQL services
3. Open phpMyAdmin at `http://localhost/phpmyadmin`

### 2. Project Setup

1. Clone or download this project
2. Place the project folder in `C:\xampp\htdocs\rental\`
3. The project will be accessible at `http://localhost/rental/`

### 3. Database Setup

**Option A: Using the SQL file**

1. Open phpMyAdmin at `http://localhost/phpmyadmin`
2. Create a new database named `mkristoh_rental`
3. Import the `youngyou.sql` file into the database

**Option B: Using the setup script**

1. Navigate to `http://localhost/rental/setup-database.php`
2. The script will automatically create the database and tables
3. Sample data will be imported automatically

### 4. Configuration

1. Open `php-config.php` and verify database settings:

   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'mkristoh_rental');
   ```

2. If using different database credentials, update accordingly

### 5. First Access

1. Go to `http://localhost/rental/`
2. Login as admin using the default credentials:
   - **Email**: `admin@mkristohrental.com`
   - **Password**: `admin123`

## File Structure

```
rental/
├── index.php              # Home page with car listings
├── login.php              # User login page
├── register.php           # User registration page
├── dashboard.php          # User dashboard
├── admin-dashboard.php    # Admin dashboard
├── rental-form.php        # Car rental form
├── process-rental.php     # Process rental requests
├── cancel-rental.php      # Cancel rental functionality
├── update-profile.php     # User profile update
├── logout.php             # Logout functionality
├── footer.php             # Common footer
├── style.css              # Custom styles
├── main.js                # JavaScript functionality
├── php-config.php         # Database configuration
├── php-login.php          # Login processing
├── php-register.php       # Registration processing
├── setup-database.php     # Database setup script
├── youngyou.sql           # Database schema and sample data
└── README.md              # This file
```

## Database Schema

### Users Table

- `id` - Primary key (AUTO_INCREMENT)
- `first_name` - User's first name (VARCHAR 50)
- `last_name` - User's last name (VARCHAR 50)
- `email` - Unique email address (VARCHAR 100)
- `phone` - Phone number (VARCHAR 20)
- `address` - User's address (TEXT)
- `password` - Hashed password (VARCHAR 255)
- `role` - User role (user/admin) (ENUM)
- `created_at` - Account creation timestamp (TIMESTAMP)
- `updated_at` - Last update timestamp (TIMESTAMP)

### Cars Table

- `id` - Primary key (AUTO_INCREMENT)
- `name` - Car name/model (VARCHAR 100)
- `category` - Car category (Sedan, SUV, Luxury, etc.) (VARCHAR 50)
- `price` - Daily rental price (DECIMAL 10,2)
- `image_url` - Car image URL (VARCHAR 255)
- `features` - Car features (JSON)
- `engine` - Engine specifications (VARCHAR 100)
- `available` - Availability status (BOOLEAN)
- `created_at` - Car addition timestamp (TIMESTAMP)
- `updated_at` - Last update timestamp (TIMESTAMP)

### Rentals Table

- `id` - Primary key (AUTO_INCREMENT)
- `user_id` - Foreign key to users table (INT)
- `car_id` - Foreign key to cars table (INT)
- `start_date` - Rental start date (DATE)
- `end_date` - Rental end date (DATE)
- `total_price` - Total rental cost (DECIMAL 10,2)
- `status` - Rental status (pending/confirmed/active/completed/cancelled) (ENUM)
- `pickup_location` - Pickup location (VARCHAR 100)
- `return_location` - Return location (VARCHAR 100)
- `notes` - Additional notes (TEXT)
- `created_at` - Rental creation timestamp (TIMESTAMP)
- `updated_at` - Last update timestamp (TIMESTAMP)

## Default Data

### Admin Account

- **Email**: `admin@mkristohrental.com`
- **Password**: `admin123`
- **Role**: Admin

### Sample Users

- **Email**: `john.doe@example.com` | **Password**: `Password123`
- **Email**: `jane.smith@example.com` | **Password**: `Password123`
- **Email**: `mike.johnson@example.com` | **Password**: `Password123`

### Sample Cars

- Toyota Camry (Sedan) - $50/day
- Honda CR-V (SUV) - $70/day
- BMW 3 Series (Luxury) - $120/day
- Ford Mustang (Sports) - $150/day
- Mercedes-Benz S-Class (Luxury) - $200/day
- Jeep Wrangler (Off-Road) - $80/day
- Tesla Model 3 (Electric) - $180/day
- Audi A4 (Sedan) - $90/day

## Usage

### For Users

1. **Register**: Create a new account at the registration page
2. **Login**: Access your account using email and password
3. **Browse Cars**: View available cars in the dashboard
4. **Rent a Car**: Select a car and choose rental dates
5. **View Rentals**: Check your rental history and status
6. **Update Profile**: Modify personal information
7. **Cancel Rentals**: Cancel bookings within 1 hour

### For Admins

1. **Login**: Use admin credentials to access admin panel
2. **Manage Users**: View and manage user accounts
3. **Manage Cars**: Add, edit, or remove cars from the fleet
4. **Manage Rentals**: Approve, reject, or update rental statuses
5. **View Reports**: Check revenue and rental statistics

## Security Features

- **Password Hashing**: All passwords are hashed using PHP's password_hash()
- **SQL Injection Prevention**: Prepared statements for all database queries
- **Input Sanitization**: All user inputs are sanitized
- **Session Management**: Secure session handling
- **Form Validation**: Server-side validation for all forms
- **CSRF Protection**: Form tokens and session validation
- **XSS Prevention**: Output escaping and input filtering

## API Endpoints

### User Management

- `POST /php-login.php` - User authentication
- `POST /php-register.php` - User registration
- `POST /update-profile.php` - Profile updates

### Rental Management

- `POST /process-rental.php` - Create new rental
- `POST /cancel-rental.php` - Cancel existing rental

### Admin Functions

- `GET /admin-dashboard.php` - Admin dashboard
- Various admin management functions

## Customization

### Adding New Cars

1. Login as admin
2. Go to "Manage Cars" section
3. Click "Add Car" button
4. Fill in car details and save

### Modifying Styles

- Edit `style.css` to customize the appearance
- The website uses Bootstrap 5 for responsive design
- Custom CSS classes are available for specific styling

### Database Configuration

- Edit `php-config.php` to modify database settings
- Default settings work with XAMPP's default configuration

### Adding New Features

1. Create new PHP files for additional functionality
2. Update navigation menus in dashboard files
3. Add corresponding database tables if needed
4. Update the configuration file for new settings

## Troubleshooting

### Common Issues

1. **Database Connection Error**

   - Ensure MySQL is running in XAMPP
   - Check database credentials in `php-config.php`
   - Verify database name exists

2. **Page Not Found (404)**

   - Ensure all files are in the correct directory
   - Check file permissions
   - Verify Apache is running

3. **Session Issues**

   - Clear browser cookies and cache
   - Check PHP session configuration
   - Verify session storage permissions

4. **Form Submission Errors**
   - Check PHP error logs
   - Verify form action URLs
   - Ensure all required fields are filled

### Error Logs

- **Apache Error Log**: `C:\xampp\apache\logs\error.log`
- **PHP Error Log**: Check XAMPP Control Panel for PHP error log location

## Performance Optimization

### Database Optimization

- Use indexes on frequently queried columns
- Optimize queries for large datasets
- Consider caching for static data

### Frontend Optimization

- Minify CSS and JavaScript files
- Optimize images for web
- Use CDN for external libraries

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open source and available under the MIT License.

## Support

For support and questions:

- Check the troubleshooting section above
- Review the code comments for implementation details
- Create an issue in the repository

## Version History

- **v2.0** - Redirection fixes, improved file structure
- **v1.0** - Initial release with basic functionality

---

**Mkristoh Rental** - Professional Car Rental Management System
