# SABAS - Sri Sairam Library Management System

Welcome to SABAS (Sri Sairam Library Management System), an advanced library management solution developed for Sri Sairam Engineering College. SABAS streamlines library operations, ensuring efficient cataloging, circulation, and resource management within our academic community.

## Features

- **User-Friendly Interface:** Intuitive and easy-to-navigate interface for both librarians and patrons.
- **Advanced Cataloging:** Effortless cataloging of books, journals, and multimedia resources with detailed metadata.
- **Circulation Management:** Simplified book checkouts, renewals, and returns for seamless circulation control.
- **Resource Tracking:** Keep track of resource availability, overdue items, and fine management for responsible usage.
- **Digital Resources:** Integration of e-books and digital resources, expanding the library's offerings to the digital realm.
- **Reporting and Analytics:** Generate insightful reports and analytics for data-driven decision-making in library administration.

## Tools Used

- **HTML:** For creating the structure and layout of the web pages.
- **CSS:** For styling the user interface and ensuring an appealing design.
- **JavaScript:** For adding interactivity and dynamic elements to the web pages.
- **PHP:** Server-side scripting language for backend operations and database interactions.
- **Tailwind CSS:** A utility-first CSS framework for streamlined and responsive designs.
- **MySQL:** Relational database management system for efficient data storage and retrieval.

## Installation Guide

### Prerequisites:

- XAMPP installed on your local machine. You can download XAMPP from [here](https://www.apachefriends.org/index.html).

### Installation Steps:

1. **Download and Install XAMPP:**
   - Download and install XAMPP following the instructions provided on the official website.

2. **Start XAMPP:**
   - Start the XAMPP control panel and ensure that Apache and MySQL services are running.

3. **Import the Database:**
   - Open phpMyAdmin in your browser by visiting `http://localhost/phpmyadmin`.
   - Create a new database named `library_management_system`.
   - Click on the `Import` tab and upload the SQL file for the database (assuming you have a file named `library_management_system.sql`). Click `Go` to import the database structure and data.

4. **Place the SABAS Files:**
   - Clone the SABAS repository or download the source code and place it in the `htdocs` directory inside the XAMPP installation folder. If you are using Windows, it could be something like `C:\xampp\htdocs\`.

5. **Configure Database Connection:**
   - Open the `config.php` file in the SABAS project folder.
   - Locate the database configuration section and ensure the database name is set to `library_management_system`.
   - Update the database username and password if different from the default XAMPP configurations.

6. **Access the Application:**
   - Open your browser and visit `http://localhost/sabas` or the appropriate URL where you placed the SABAS files.
   - You should see the login page of the SABAS Library Management System.

7. **Login as Admin:**
   - Use the following admin credentials to log in:
     - **Username:** admin
     - **Password:** password
   - You will have access to the admin panel, allowing you to manage the library resources and user accounts.

## Contributing

We welcome contributions from the open-source community to enhance SABAS further. If you have suggestions, bug reports, or feature requests, please feel free to create a GitHub issue or submit a pull request.

Join us in revolutionizing library management at Sri Sairam Engineering College with SABAS! 📚✨
