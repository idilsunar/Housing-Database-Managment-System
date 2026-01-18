# Housing-Database-Managment-System
This project was developed as part of **CS 306 - Database Management Systems** course at Sabancı University. It addresses the common problem of managing student housing data, which is often scattered across spreadsheets and emails, leading to data inconsistency, double booking, and lost maintenance requests.

The system integrates all housing and maintenance operations into a centralized database with:

-   **MySQL** for relational data (dorms, rooms, students, contracts, staff)
-   **MongoDB** for support ticket system
-   **PHP** for web interface and business logic
-   **XAMPP** for local development environment

### Problem Statement

Universities face challenges in managing:

-   Student dormitory assignments
-   Room availability and capacity
-   Housing contract tracking
-   Maintenance request workflows
-   Data consistency across departments

### Solution

A unified web-based system that enforces:

-   Clear key and participation constraints
-   Automated triggers for data integrity
-   Stored procedures for complex operations
-   Support ticket system for user assistance

* * *

## Features

### Database Management

-   **Dormitory Management**: Track multiple residence halls with gender policies
-   **Room Management**: Manage rooms with capacity, floor, and type information
-   **Student Registration**: Maintain student records with contact information
-   **Contract Handling**: Automated housing contract lifecycle management
-   **Maintenance Tracking**: Complete maintenance request workflow
-   **Staff Assignment**: Assign maintenance staff to requests

### Automation

-   **Database Triggers**: Automatic data validation and updates
-   **Stored Procedures**: Streamlined complex database operations
-   **Data Integrity**: Referential integrity through foreign key constraints

### Web Interface

-   **User Portal**:
    -   Interactive trigger testing
    -   Stored procedure execution
    -   Support ticket creation and tracking
-   **Admin Dashboard**:
    -   View all support tickets
    -   Manage and respond to requests
    -   Mark tickets as resolved
      
