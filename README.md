# Real-Time Shared To-Do List (Backend)

The PHP/MySQL backend for the shared to-do list. It handles registration, login and sessions, then signs the user into the React app by creating a Firebase custom token.

This is the **backend** repo. The to-do app itself lives in a separate repo: (https://github.com/Nathangotopps/todolist-frontend).

**Live demo:** https://yoyin-todo.xo.je (frontend: https://github.com/Nathangotopps/todolist-frontend).
The backend is hosted on InfinityFree's free plan (PHP 8.4, MySQL).

### Known limitations

- No password reset (free hosting has no outgoing email).
- Free-tier hosting means limited daily requests and occasional slowness.

## What it does

- Registration (`index.php`, `process.php`): stores students in MySQL with hashed passwords (`password_hash`)
- Login and logout (`login.php`, `logout.php`): session-based, using prepared statements and `password_verify`
- Dashboard (`dashboard.php`): after login, creates a Firebase custom token for the user's id and links to the React app as `?token=...&category=...&theme=...`
- Theme settings (`theme_config.php`) and background music for the pages

## Requirements

- PHP 8.3 with the **sodium** extension enabled (needed by the Firebase JWT library)
- MySQL or MariaDB with the `mysqli` extension
- [Composer](https://getcomposer.org/)
- A Firebase project with Authentication and Realtime Database enabled
- Apache (the setup below is for XAMPP on Windows)

Dependencies are `kreait/firebase-php` 8.x and `firebase/php-jwt`. PHP 8.3 is recommended so that you get versions of both without known vulnerabilities.

## Setup

1. **Put the project in your web root**, for example `C:\xampp\htdocs\MYSQL_work`.

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Create the database.** `schema.sql` creates the `school_portal` database and the `pupils` table (name, email, phone number, age, gender, password hash):
   ```bash
   mysql -u root -p < schema.sql
   ```
   To-do items are stored in Firebase, not MySQL.

4. **Add your Firebase service account key.**
   - In the Firebase/Google Cloud console, go to IAM & Admin → Service Accounts, choose the Firebase admin account and create a new JSON key.
   - Save it as `secure/firebase-service-account.json`.
   - **Never commit this file.** It is git-ignored. If a key is ever exposed, delete it in the Google Cloud console and create a new one.

5. **Protect the `secure/` folder.** `secure/.htaccess` contains `Require all denied`. Apache ignores `.htaccess` files unless the web root allows overrides. In `httpd.conf`, find the `<Directory "C:/xampp/htdocs">` block and set `AllowOverride All`. Then restart Apache.
   Check it works: opening `http://localhost/MYSQL_work/secure/firebase-service-account.json` in your browser should return **403 Forbidden**.

6. **Configure the connection and URLs.** Database settings (`localhost`, `root`, empty password) are set in `login.php` and `process.php`. Those defaults are fine for local XAMPP only, so use real credentials before hosting. In `dashboard.php`, change `$reactAppUrl` to your deployed frontend URL. All four category links use it.

7. Open `http://localhost/MYSQL_work/index.php`, register, log in and follow the dashboard links into the React app.

## Security notes

- Passwords are hashed, queries use prepared statements, and the Firebase key lives outside public access.
- Don't use the default `root` user with an empty password on a real server.
- The Firebase web config used by the React app is public by design. Realtime Database rules are what protect user data.

## License

The source code in this repository is released under the [MIT License](LICENSE).

The music and other media used by the app are **not** covered by that license.
They belong to their respective creators and are used under their own licenses.
See [CREDITS.md](CREDITS.md) for the full list and attribution.
