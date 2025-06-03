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
