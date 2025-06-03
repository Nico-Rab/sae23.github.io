# Ventilometer Web Application

## Overview
This web application displays real‐time and historical wind data (“ventilometer”) for first‐year R&T (Networks & Telecommunications) students. It maps the primary and secondary residences of each student, retrieves daily wind information via the OpenWeather API, stores all data in a MySQL database, and presents both current and historical wind conditions for each student.

## Features
- **Data Input & Storage**
  - Import student details (first name, last name, primary/secondary addresses, postal codes, group) from JSON or CSV files, or enter manually via a web interface.
  - Fetch wind speed and direction for each address using the OpenWeather API.
  - Store all student and wind data in a relational database (MySQL).

- **Interactive Front End**
  - Auto‐complete search bar to quickly find a student by name.
  - Dynamic display of current wind speed and direction for both primary and secondary addresses.
  - Visualization of historical wind data with timestamps.

- **Back‐End Functionality**
  - PHP scripts for database connectivity, CRUD operations, and OpenWeather API calls.
  - Scheduled updates to record “wind of the day” automatically.
  - Error handling for undefined variables and missing data.

- **Responsive Design & UX**
  - Clean, custom CSS styling.
  - Graceful handling of edge cases (e.g., invalid postal codes or missing API key).

## Technology Stack
- **Front End:**  
  - HTML5  
  - CSS3  
  - JavaScript (vanilla)  
  - Bootstrap (for responsive layouts)

- **Back End:**  
  - PHP 7+  
  - Apache (RT-Serv hosting)

- **Database:**  
  - MySQL (relational schema with tables for students, addresses, wind records)

- **APIs:**  
  - OpenWeather API (wind speed & direction)

- **Development Tools:**  
  - Visual Studio Code (with “Live Server”/“Go Live” extension)  
  - Git (version control)

## Installation

1. **Clone the repository**  
   ```bash
   git clone https://github.com/your-username/ventilometer.git
   cd ventilometer
Ventilometer Web Application
Overview
This web application displays real‐time and historical wind data (“ventilometer”) for first‐year R&T (Networks & Telecommunications) students. It maps the primary and secondary residences of each student, retrieves daily wind information via the OpenWeather API, stores all data in a MySQL database, and presents both current and historical wind conditions for each student.

Features
Data Input & Storage

Import student details (first name, last name, primary/secondary addresses, postal codes, group) from JSON or CSV files, or enter manually via a web interface.

Fetch wind speed and direction for each address using the OpenWeather API.

Store all student and wind data in a relational database (MySQL).

Interactive Front End

Auto‐complete search bar to quickly find a student by name.

Dynamic display of current wind speed and direction for both primary and secondary addresses.

Visualization of historical wind data with timestamps.

Back‐End Functionality

PHP scripts for database connectivity, CRUD operations, and OpenWeather API calls.

Scheduled updates to record “wind of the day” automatically.

Error handling for undefined variables and missing data.

Responsive Design & UX

Clean, custom CSS styling.

Graceful handling of edge cases (e.g., invalid postal codes or missing API key).

Technology Stack
Front End:

HTML5

CSS3

JavaScript (vanilla)

Bootstrap (for responsive layouts)

Back End:

PHP 7+

Apache (RT-Serv hosting)

Database:

MySQL (relational schema with tables for students, addresses, wind records)

APIs:

OpenWeather API (wind speed & direction)

Development Tools:

Visual Studio Code (with “Live Server”/“Go Live” extension)

Git (version control)

Installation
Clone the repository

bash
Copy
Edit
git clone https://github.com/your-username/ventilometer.git
cd ventilometer
Set up the MySQL database

Create a new MySQL database (e.g., ventilometer_db).

Import the provided SQL schema (database/schema.sql) to create tables:

sql
Copy
Edit
mysql -u your_user -p ventilometer_db < database/schema.sql
Configure database connection

Copy config/config.example.php to config/config.php.

Update the database credentials (hostname, username, password, database name).

Obtain an OpenWeather API key

Register at OpenWeather and generate an API key.

In config/config.php, set:

php
Copy
Edit
define('OPENWEATHER_API_KEY', 'your_api_key_here');
Deploy to RT-Serv (or any PHP-compatible host)

Upload all project files to your server's public_html (or equivalent) directory.

Make sure PHP 7+ and MySQL extensions are enabled.

Verify setup

Open your browser and navigate to http://your-server/ventilometer/.

You should see the home page with the student search interface.

Usage
Add students (if not imported via JSON/CSV):

Log in to the admin interface (if implemented) or use the “Import” page.

Provide first name, last name, primary address, secondary address, postal codes, and group.

Search for a student:

Start typing a student’s first or last name in the search bar.

Select the student from the auto‐complete list.

View wind data:

The application displays:

Current wind speed & direction for the student’s primary address.

Current wind speed & direction for the student’s secondary address.

Historical wind records (by date) stored in the database.

Automatic daily updates:

A scheduled cron job (configured separately on the server) triggers the PHP script that fetches and stores daily wind data for all addresses in the database.

Database Schema
Conceptual Model (MCD)
scss
Copy
Edit
Etudiants
│
├─ idE       (PK)
├─ Prenom
├─ Nom
├─ ville1
├─ ville2
├─ adresse1
├─ adresse2
├─ cp1
├─ cp2
├─ groupe
└─ …  
scss
Copy
Edit
WindRecords
│
├─ idW       (PK)
├─ student_id (FK→Etudiants.idE)
├─ location_type (ENUM: 'primary','secondary')
├─ wind_speed
├─ wind_direction
├─ record_date (DATE)
└─ …  
Physical Model (MPD)
sql
Copy
Edit
CREATE TABLE Etudiants (
  idE INT AUTO_INCREMENT PRIMARY KEY,
  Prenom VARCHAR(50) NOT NULL,
  Nom VARCHAR(50) NOT NULL,
  adresse1 VARCHAR(255) NOT NULL,
  cp1 VARCHAR(10) NOT NULL,
  ville1 VARCHAR(100) NOT NULL,
  adresse2 VARCHAR(255),
  cp2 VARCHAR(10),
  ville2 VARCHAR(100),
  groupe VARCHAR(10) NOT NULL
);

CREATE TABLE WindRecords (
  idW INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  location_type ENUM('primary','secondary') NOT NULL,
  wind_speed DECIMAL(5,2) NOT NULL,
  wind_direction VARCHAR(10) NOT NULL,
  record_date DATE NOT NULL,
  FOREIGN KEY (student_id) REFERENCES Etudiants(idE)
);
Project Structure
pgsql
Copy
Edit
ventilometer/
├─ config/
│  ├─ config.example.php
│  └─ config.php          # Database credentials & API key
├─ database/
│  ├─ schema.sql          # SQL script to create tables
│  └─ seed_data.sql       # (Optional) sample data import
├─ public/
│  ├─ index.php           # Homepage & search interface
│  ├─ student.php         # Displays wind data for a specific student
│  ├─ import.php          # JSON/CSV import utility
│  ├─ assets/
│  │  ├─ css/
│  │  │  └─ styles.css
│  │  └─ js/
│  │     └─ scripts.js
│  └─ vendor/             # (Optional) third-party libraries
├─ src/
│  ├─ db.php              # Database connection class
│  ├─ models/
│  │  ├─ Student.php      # Student model & CRUD methods
│  │  └─ WindRecord.php   # WindRecord model & CRUD methods
│  └─ services/
│     └─ OpenWeatherService.php  # Fetches wind data from API
├─ tests/                 # PHPUnit or custom tests (if available)
├─ README.md
└─ .gitignore
Team & Responsibilities
Fatih Kurul

Collected student information into Excel.

Designed and created the initial database schema.

Implemented API‐driven wind data retrieval functions in PHP.

Nicolas Rabergeau

Created PowerPoint presentations and wireframes.

Designed the initial HTML/CSS layout.

Added front‐end styling and responsive design.

Yanis Gangnant

Developed back‐end PHP logic and database integration.

Set up server-side scripts for CRUD operations and scheduled API calls.

Handled error‐checking and data validation.

Development Phases
Planning & Modeling

Defined requirements and created Conceptual (MCD) and Physical (MPD) data models.

Drew flowcharts and UI mockups to clarify data flows and user interactions.

Environment Setup

Chose Visual Studio Code with “Live Server” extension for rapid iteration.

Verified server configuration on RT-Serv using simple “Hello World” PHP scripts.

Task Distribution & Initial Implementation

Database creation, front-end mockups, and back-end scaffolding developed in parallel.

Integrated PHP scripts with the database for initial CRUD tests.

API Integration & Feature Expansion

Registered for an OpenWeather API key.

Wrote PHP functions to fetch and display wind speed/direction for each address.

Iterated on CSS to improve usability and fixed PHP variable‐scope issues.

Finalization & Testing

Added auto-complete search for student names.

Ensured each wind reading is stored with a timestamp.

Conducted end-to-end testing to verify data accuracy and UI/UX flow.

Lessons Learned
Technical Skills:

Gained hands-on experience with HTML, CSS, JavaScript, PHP, SQL, and third-party APIs.

Learned best practices for database schema design and API integration.

Project Management:

Practiced creating clear data models (MCD/MPD), writing and tracking to-do lists, and coordinating tasks in a small team.

Teamwork & Collaboration:

Resolved PHP variable-scope issues through pair debugging.

Refined database relations and iterated on UI/UX based on peer feedback.

Developed autonomy and communication skills essential for professional software development.

License
This project is released under the MIT License.
