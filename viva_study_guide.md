# 🏥 Hospital Management System (Medora HMS) - Viva Study Guide

This guide covers everything you need to know to answer questions about the **Medora Hospital Management System** project.

---

## 🚀 1. Technology Stack (The "What")
You must know the tools used to build this:
- **Backend Framework**: **Laravel 11+** (PHP-based framework).
- **Database**: **PostgreSQL** (Relational Database Management System).
- **Frontend Styling**: **Vanilla CSS & Bootstrap 5** (Responsive UI).
- **Icons**: **Font Awesome**.
- **Animations**: **AOS (Animate On Scroll)**.
- **Deployment**: **Laravel Cloud** & **GitHub**.

---

## 🏛️ 2. System Architecture (The "How It Works")
We use the **MVC Pattern** (Model-View-Controller):
1.  **Model**: Handles the database logic (e.g., `User.php`, `Appointment.php`).
2.  **View**: The user interface (e.g., `home.blade.php`, `admin/dashboard.blade.php`).
3.  **Controller**: The "Brain" that connects the two (e.g., `AdminAppointmentController.php`).
4.  **Middleware**: Security guards that check roles (e.g., `IsAdmin.php`, `LabStaffMiddleware.php`).

---

## 👥 3. User Roles & Permissions
The system has **6 distinct roles**:
1.  **Admin**: Manages doctors, staff, medicines, and the entire system.
2.  **Doctor**: Views appointments, prescribes medicine, and requests lab tests.
3.  **Patient**: Books appointments, uploads external prescriptions, and views lab reports.
4.  **Pharmacist**: Responds to prescriptions and manages medicine inventory.
5.  **Lab Staff (Technician)**: Conducts lab tests, enters parameter values, and generates reports.
6.  **Receptionist**: Handles front-desk registrations and schedules.

---

## 💉 4. Core Features & Workflows

### A. Appointment Booking Flow
1. Patient selects a **Specialization** (e.g., Cardiology).
2. System filters available **Doctors**.
3. Patient picks a **Date & Time Slot** (System checks doctor schedules).
4. A **Token Number** is generated relative to the daily count.

### B. Prescription & Pharmacy Flow
1. Doctor writes a prescription (Medicines + Dosage).
2. Pharmacist sees the prescription in their dashboard.
3. Once fulfilled, medicine **Quantity** is decremented from inventory.
4. Patients can also **Upload** external prescriptions (PDF/Images).

### C. Laboratory Reporting Flow
1. Doctor requests a **Test Type** (e.g., CBC).
2. System uses **Template Logic**: Every Test Type has a set of **Parameters** (e.g., Hemoglobin, WBC).
3. Lab Staff enters the **Actual Value** for each parameter.
4. System compares values against **Min/Max Reference Ranges**.
5. A **Professional Report** is generated as a PDF or printable web view.

---

## 📊 5. Database Schema (Key Tables)
- **`users`**: Central table for all logins (stores `email`, `password`, and `role`).
- **`patients`/`doctors`/`lab_staff`**: "Profile" tables linked to the `users` table via `user_id`.
- **`appointments`**: Stores date, time, and status of consultations.
- **`medicines`**: Inventory management (name, quantity, price).
- **`lab_tests`**: General record of a test request.
- **`lab_report_values`**: The specific "data points" (e.g., actual Hemoglobin level) for a report.

---

## 🛠️ 6. Key Challenges Solved (Viva "Brownie Points")
Explain these to show you really built it:
1.  **Soft Deletes**: We used `SoftDeletes` so that deleting a doctor doesn't break old appointment records (they remain in the DB but are hidden from active lists).
2.  **Session Security**: Configured the **`database` session driver** for cloud deployment to prevent the "419 Page Expired" errors common on serverless platforms.
3.  **PostgreSQL Compatibility**: Adjusted migrations to handle PostgreSQL-specific sequence syncs and identity columns.
4.  **Role Synchronization**: Renamed roles like `lab_staff` to `labstaff` (no underscore) to comply with strict database check constraints on the cloud.

---

## ☁️ 7. Deployment & DevOps
- **Source Control**: **Git/GitHub** for versioning.
- **CI/CD**: **GitHub Actions** automatically deploys code to the cloud on every "Push".
- **Storage**: Linked the `public/storage` folder (`artisan storage:link`) to make uploaded prescriptions visible to browsers.

---

## ❓ 8. Potential Viva Questions
- **Q: Why use Laravel?**
  - A: It provides built-in tools for auth, routing, and security (Eloqent ORM, Blade templating).
- **Q: How do you protect routes?**
  - A: Using **Middleware**. Only users with the `admin` role can access `/admin/*` routes.
- **Q: What happens if a file upload is not visible?**
  - A: Usually means the symbolic link in the public folder is missing (`storage:link`).
- **Q: How does the system handle concurrent appointments?**
  - A: It checks the `appointment_date` and `doctor_id` before confirming a booking to avoid double-booking.

---

## 📅 9. How Doctor Availability Works (Deep Dive)
Explaning the "Schedule & Slot" logic:
1.  **Staff Input**: Admin sets a doctor’s working hours for each day of the week (e.g., Monday 09:00 - 17:00).
2.  **Day-of-Week Mapping**: When a patient picks a date (e.g., March 31, 2026), Laravel's `Carbon` library identifies it as a "Tuesday."
3.  **Schedule Lookup**: The system queries the `doctor_schedules` table for that specific doctor on a "Tuesday."
4.  **Dynamic Slot Generation**: The `getSlots()` function loops from the **Start Time** to the **End Time** in increments of the **Slot Duration** (e.g., every 15 minutes).
5.  **Appointment Sanitization**: Before displaying slots, the system checks the `appointments` table for that Doctor/Date. If a slot is already booked, it is marked as `booked: true` in the JSON response.
6.  **Frontend Logic**: The UI (Blade/JavaScript) disables "Booked" slots and only allows the patient to click "Available" ones.

## 🏥 10. Role-Based Access Control (RBAC)
**Question: How does the system restrict a Patient from entering an Admin page?**
1.  **Middleware Strategy**: We use Laravel **Middleware** (e.g., `IsAdmin`, `IsPatient`).
2.  **Route Protection**: In `routes/web.php`, every sensitive group of routes is wrapped in a `middleware()` function.
3.  **Login Logic**: When a user logs in, the `AuthenticatedSessionController` (or `AdminAuthController`) checks the `role` column in the `users` table.
4.  **Automatic Redirect**: If a user is authorized, they go to their specific dashboard; if not, they are redirected back to the login page with a `403 Unauthorized` error.

---

## 🔬 11. Lab Report "Template" Logic
**Question: How does the system know which values to ask for in a lab report?**
1.  **Master Tables**: We have two linked tables: `lab_test_types` (e.g., CBC) and `lab_test_parameters_master` (e.g., Hemoglobin).
2.  **Relationship**: One Test Type has **Many** Parameters.
3.  **Dynamic Form**: When the Lab Staff clicks "Upload Report," the system finds the parameters for that specific test and generates a dynamic HTML list of input fields.
4.  **Data Storage**: The actual values entered (e.g., `14.5`) are stored in `lab_report_values`, which references both the test and the parameter.
5.  **Reference Comparison**: On the final report, the system pulls the **Min/Max** ranges from the Master table and displays them next to the patient’s result for comparison.

---

## 💊 12. Pharmacy Inventory Control
**Question: What happens to the stock when a medicine is dispensed?**
1.  **Medicine Table**: Stores the current `quantity` (stock level).
2.  **Dispensing Action**: When the Pharmacist marks a prescription as "Dispensed" in their dashboard:
    *   The system iterates through every medicine in that prescription.
    *   It executes a `decrement('quantity', amount)` command on the `Medicine` model.
3.  **Low Stock Tracking**: If the quantity reaches zero, the admin can see this in their dashboard to re-order stock.

---

## 📂 13. File Storage & Uploads
**Question: How are external prescriptions handled?**
1.  **Disk Selection**: We use the **`public` disk** in Laravel to store images and PDFs.
2.  **File Naming**: Laravel automatically generates a unique filename to avoid overwrites.
3.  **Path Storage**: We store the *relative path* (e.g., `uploaded_prescriptions/abc.jpg`) in the database.
4.  **Symbolic Linking**: Since the `storage` folder is private, we run `php artisan storage:link`. This creates a shortcut from `public/storage` to the private folder, making files securely viewable in the browser.

---

## 🗑️ 14. Soft Deletes (The Safety Net)
**Question: Why use Soft Deletes instead of permanently deleting records?**
1.  **Data Integrity**: If you delete a Doctor who has 100 historical appointments, a standard delete would make those appointment records "orphaned" (point to a null doctor).
2.  **The Fix**: With `SoftDeletes`, the record is still in the database but has a `deleted_at` timestamp.
3.  **Eager Loading**: We use `withTrashed()` in our controllers (e.g., `LabStaffController`) to ensure old reports still show the doctor’s name, even if that doctor is no longer active at the hospital.

---
**Medora HMS - Delivering Healthcare through Technology.**
