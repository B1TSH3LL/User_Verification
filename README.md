# Login / Signup User Verification System

# Installation
1) Download xampp from official website.
2) Run the exe file and install
3) Follow the installation wizard (Next>Next>Finish)
4) Wait until complete
5) Start My Sql And Apache Service
6) Test in browser. Visit : http://localhost

# Setup Database
1)Visit : http://localhost/phpmyadmin/

2)Click On New Database.

3)Create New Database With Name "demo".

# SQL Query
1)Run The Below SQL Query In The New Database.
```
CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```
Please check out the tutorial on (SQL CREATE TABLE statement)[https://www.tutorialrepublic.com/sql-tutorial/sql-create-table-statement.php] for the detailed information about syntax for creating tables in MySQL database system.

# Deployment
1)Paste The Code Files In C:\xampp\htdocs

In Case There Are Other Files You Can Also Delete Them

VIST : http://localhost
