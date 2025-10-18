# Boarding Gigs Platform — Professional Build

A clean, student-friendly listing site where **Gig Posters** can post boarding/hostel/room offers and **Students** can browse and view details.  
This build keeps your original PHP + MySQL structure but improves **documentation, styles, and front-end validation** for a more professional look.

## Tech Stack

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP (procedural, MySQLi)
- **Database**: MySQL (phpMyAdmin or CLI)
- **Uploads**: Local filesystem (`/uploads`)

> ⚠️ Educational project. No real payment processing; card form is a mock for UX demonstration.

---

## Features

- User Registration & Login (roles: **poster** / **student**)
- Create & manage gigs (title, description, location, conditions, price, photo)
- Browse grid with modern cards, responsive layout
- Detailed gig page and mock “pay posting fee” flow
- Polished UI with consistent color system and spacing
- Client‑side validation with **Luhn check** for card numbers, stricter expiry rules, numeric input guards

---

## Quick Start (Local)

1. **Install a local server**
   - XAMPP (recommended) / WAMP / MAMP
   - Start **Apache** and **MySQL**

2. **Place the project**
   - Copy all files to your web root (e.g. `C:\xampp\htdocs\boarding_gigs\` on Windows)

3. **Create the database**
   - Open phpMyAdmin → create DB `boarding_gigs`
   - Import `database.sql` (found in the project root)

4. **Configure DB connection**
   - Open `db_config.php` and update credentials if needed:
     ```php
     $db_host = "localhost";
     $db_user = "root";
     $db_pass = "";
     $db_name = "boarding_gigs";
     $db_port = 3306; // change if your MySQL runs on a non-default port
     ```

5. **Ensure uploads directory exists**
   - Create `/uploads` and give write permission

6. **Run**
   - Open: `http://localhost/boarding_gigs/register.php`

---

## Project Structure

```
boarding_gigs/
├── database.sql
├── db_config.php
├── register.php
├── login.php
├── logout.php
├── index.php
├── post_gig.php
├── view_gig.php
├── payment.php
├── payment_success.php
├── style.css
├── script.js
├── uploads/            # uploaded images
└── .htaccess           # (optional) routing/cache headers
```

---

## Database Schema (Summary)

- **users**: id, username, email, password (MD5 for demo), user_type, created_at
- **gigs**: id, user_id, title, description, location, conditions, phone_number, photo, price, status, created_at
- **payments**: id, gig_id, user_id, card_name, card_number, amount, payment_date

> If you created `gigs` earlier without `phone_number`, re-run the `ALTER TABLE` line from `database.sql`.

---

## Security & Production Notes

This is a university lab project. For real deployments you MUST:
- Use **password_hash()** instead of MD5
- Use **prepared statements** (PDO/MySQLi) to prevent SQL injection
- Store **card data** with a PSP (Stripe, PayPal) — do not handle raw card numbers
- Add **CSRF tokens** to forms and stricter server-side validation
- Enforce input sanitization and file upload restrictions

---

## Changelog (Professional Build)

- Refreshed **UI** (typography, spacing, button hierarchy, cards)
- Reworked **CSS** with variables for theme colors and better responsiveness
- Enhanced **JS validation**: Luhn algorithm for cards, numeric masks for CVV, robust expiry checks (MM/YY & future date)
- Cleaned **README** and installation steps

**Last updated:** 2025-10-18
