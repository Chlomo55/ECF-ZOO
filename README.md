
# Zoo Web Application

## Introduction
This project is a web application for a zoo, designed to allow visitors to view animal details, habitats, services, and visitor reviews. It also includes sections for employees and veterinarians to manage animals, track their health, and interact with the zoo system.

## Features
- Visitor access to animal details, habitats, and services
- Administrator access to manage animals, habitats, services, employees, and veterinarians
- Employee and veterinarian login areas to manage animal health, food consumption, and visitor reviews
- Statistics for the most viewed animals
- Responsive design for desktop and mobile

## Installation

### Prerequisites
1. **PHP**: Version 7.4 or higher
2. **MySQL**: For the relational database
3. **MongoDB**: For non-relational data (statistics)
4. **Composer**: For dependency management
5. **Web server**: Apache or Nginx

### Steps

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/zoo-web-app.git
   cd zoo-web-app
   ```

2. Install dependencies using Composer:
   ```bash
   composer install
   ```

3. Create the MySQL database:
   ```sql
   CREATE DATABASE zoo_db;
   ```

4. Import the provided SQL file:
   ```bash
   mysql -u username -p zoo_db < zoo.sql
   ```

5. Configure your environment in `pdo.php`:
   ```php
   $pdo = new PDO("mysql:host=localhost;dbname=zoo_db", "yourusername", "yourpassword");
   ```

6. Configure MongoDB for statistics tracking in the file `stats.php`.

7. Run the application on your local server (e.g., with Apache):
   - Place the project files in the web directory (e.g., `/var/www/html/zoo-web-app`).
   - Access it via `http://localhost/zoo-web-app`.

## User Credentials

### Administrator:
- **Username**: admin@zoo.com
- **Password**: admin123

### Employee:
- **Username**: employe@zoo.com
- **Password**: employe123

### Veterinarian:
- **Username**: veterinaire@zoo.com
- **Password**: vet123

## License
This project is licensed under the MIT License.
