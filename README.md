# HakimGizawEMR-
# Custom EMR System for Hakim Gizaw University Hospital

## Overview

This repository hosts the source code for a customized Electronic Medical Record (EMR) system designed for Hakim Gizaw University Hospital. Our solution enhances an open-source EMR by integrating a feature-rich patient portal and doctor portal tailored to Ethiopian healthcare needs. The system offers multi-language support, robust appointment management with SMS reminders, AI-powered consultation, and integrated telemedicine—all built using PHP, HTML, CSS, JavaScript, and Node.js Socket.

## Features

### Patient Portal
- **Direct Registration & Access:**
  - New patients can register directly through the patient portal.
  - During registration, patients are prompted to choose whether they want access to the portal.
  - If they opt in, a username and password are generated for secure login.
- **Dashboard:**
  - **Patient Profile:** Displays personal information and medical history.
  - **Vital Signs:** Shows vital signs data fetched from the database (entered via the doctor's portal).
  - **Prescriptions:** Lists prescription details managed by doctors.
  - **Request Appointment:** Patients can request appointments; these requests are sent directly to the doctor’s portal.
  - **AI Consultation:** Utilizes the open-source DeepSeek R1 1.5b model to provide preliminary consultation insights.
  - **Telemedicine Consultation:** Enables real-time video consultations (video solution is already deployed) for doctor-patient interactions.
- **Multi-Language Support:**
  - Patients can switch between Amharic, Oromo, Tigrigna, and English, making the portal accessible to a diverse user base.

### Doctor Portal
- **Appointment Management:**
  - Appointment requests submitted by patients are displayed in the doctor’s portal.
  - Doctors can review and approve appointments.
  - Approved appointments are saved permanently in the database.
- **SMS Reminders:**
  - An automatic SMS reminder is sent to patients upon appointment approval.
- **Clinical Data Entry:**
  - Doctors enter and update patient profiles, vital signs, and prescriptions, which are then reflected in the patient portal.

### Consultation Services
- **AI Consultation:**
  - Provides AI-driven insights using DeepSeek R1 1.5b.
- **Video Consultation:**
  - Real-time video consultations are available to both doctors and patients through the deployed telemedicine solution.

## Built With

- **PHP**
- **HTML**
- **CSS**
- **JavaScript**
- **Node.js Socket**

## Installation

### Prerequisites
- [Git](https://git-scm.com/)
- A web server with PHP support (Apache)
- A supported database (MySQL, PostgreSQL)
- [Node.js](https://nodejs.org/) (for Node.js Socket functionality)
- Other dependencies as specified in the project configuration files

### Steps
1. **Clone the Repository:**
   ```bash
   git clone https://github.com/Kaletsidikk/HakimGizawEMR-
   cd HakimGizawEMR-
   
2. **Place in Web Server Directory:**

- If using XAMPP, place the extracted project folder inside the htdocs folder.
3. **Install Node.js Dependencies:**
   ```bash
   npm install

4. **Setup the Database:**

- Open your browser and navigate to http://localhost/phpmyadmin.
- Create a new database using the name specified in the project documentation.
- Import the provided SQL file from the DATABASE FILE folder to set up the necessary tables and schema.

5. **Run the Application:**

- Start your PHP server.
- If applicable, start the Node.js server:
- Open your browser and navigate to http://localhost/HakimgizawEMR/

# Usage
- Patient Registration & Portal Access
- Patients register directly through the patient portal.
- During registration, they are asked if they want portal access. If so, a username and password are created for them.
Once logged in, patients can view their profile, vital signs, prescriptions, request appointments, and access consultation services (AI and video).
# Appointment Management
- Patients request appointments through the portal.
- Appointment requests are forwarded to the doctor’s portal where doctors review and approve them.--
- Approved appointments are saved to the database, and an automatic SMS reminder is sent to the patient’s phone.
# Consultation Services
- AI Consultation: Offers AI-driven preliminary insights via DeepSeek R1 1.5b.
- Video Consultation: Enables real-time interactions between doctors and patients using the deployed telemedicine solution.
# Contributing
Contributions are welcome! Please fork this repository and submit pull requests for any improvements or bug fixes. For significant changes, kindly open an issue first to discuss your ideas.

# License
This project is licensed under the MIT License - see the [LICENSE](https://github.com/Kaletsidikk/HakimGizawEMR-/blob/main/LICENSE) file for details.

# Contact
For any questions or suggestions, please contact:

Email: kalukelayneh@gmail.com
