# PHP Authentication System

A small PHP authentication application using MariaDB through XAMPP with MySQL-compatible SQL with a French-language interface. Users can create an account, log in, view a protected profile, and log out. The code uses an MVC-style organization with separate controllers, models, views, configuration, and CSS files.

This is a learning and portfolio project. It is not presented as production-ready software.

## Features

### Implemented

- Account registration with name, first name, email, login, and password fields
- Duplicate-login check before account creation
- Password hashing with `password_hash()` and `PASSWORD_DEFAULT`
- Password verification with `password_verify()`
- PDO prepared statements for user queries and inserts
- PHP session-based authentication
- Protected profile page that redirects unauthenticated visitors to login
- Profile output escaped with `htmlspecialchars(..., ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`
- Logout that clears the session, destroys it, expires the session cookie, and redirects to login
- Failed-password recording for an existing login in `tentativeconnexion`
- MariaDB/MySQL-compatible trigger-based limit of three recorded failed attempts per login during one hour
- `PDOException` handling when the trigger rejects a failed-attempt insert
- Separate CSS files for the login, registration, and profile pages

### Not implemented

- CSRF tokens
- Session ID regeneration after login
- Explicit secure, HttpOnly, and SameSite session-cookie configuration
- IP-based rate limiting
- Email verification or password reset
- Automated tests

## Technologies

- PHP
- HTML5 and CSS3
- PHP sessions
- PDO with the MySQL driver
- MariaDB via XAMPP (MySQL-compatible)
- Apache through XAMPP for local development
- Composer with `vlucas/phpdotenv` for environment-based database configuration

`package.json` also lists `@dotenvx/dotenvx`, but the application database configuration currently uses PHP dotenv through Composer.

## Project Structure

```text
connextion_form/
|-- config/
|   |-- database.php
|   `-- session.php
|-- database/
|   `-- schema.sql
|-- Controllers/
|   |-- ControleurConnexion.php
|   |-- ControleurDeconnexion.php
|   |-- ControleurInscription.php
|   `-- ControleurProfile.php
|-- Models/
|   |-- InscriptionModel.php
|   `-- UtilisateurModel.php
|-- View/
|   |-- connexion/
|   |   |-- FormulaireConnexion.php
|   |   `-- FormulaireInscription.php
|   `-- Profile.php
|-- public/
|   `-- css/
|       |-- FormulaireConnexion.css
|       |-- FormulaireInscription.css
|       `-- Profile.css
|-- index.php
|-- composer.json
|-- package.json
`-- README.md
```

- `database/schema.sql` contains the versioned database schema and trigger definition.
- `Controllers/` receives the request, calls the relevant model or session helper, and selects a view.
- `Models/` contains registration and authentication database operations.
- `View/` contains the login, registration, and profile HTML/PHP templates.
- `public/css/` contains the page stylesheets.
- `index.php` is the front controller and routes requests using the `page` query parameter.
- `config/database.php` loads `.env` values and creates the PDO connection.
- `config/session.php` starts the PHP session and provides login, redirect, and session-user helpers.
- `composer.json` declares the PHP dotenv dependency. `package.json` contains the npm dependency metadata.

## Routes

| URL | Purpose |
| --- | --- |
| `index.php?page=connexion` | Display and process login |
| `index.php?page=inscription` | Display and process registration |
| `index.php?page=profile` | Display the protected profile |
| `index.php?page=deconnexion` | Clear the session and return to login |

An absent or unknown `page` value loads the login controller.

## Application Flow

```text
Browser
  -> index.php
  -> Controller
  -> Model or session helper
  -> Database, when needed
  -> View
  -> Browser
```

`index.php` selects a controller from `?page=`. Controllers process form requests and load views. Models perform database operations through PDO.

## Authentication Flow

```text
Login form
  -> ControleurConnexion.php
  -> UtilisateurModel::authenticate()
  -> SELECT the user by login using a prepared statement
  -> password_verify()
  -> Store user data in $_SESSION
  -> Redirect to index.php?page=profile
```

For an existing login and a wrong password, the model inserts a record into `tentativeconnexion`. If that insert is rejected by the database trigger, the model catches `PDOException` and returns the maximum-attempts message.

## Registration Flow

1. The registration form submits `nom`, `prenom`, `email`, `login`, and `mdp` with POST.
2. The controller trims the text fields and checks that the required values are not empty.
3. `InscriptionModel` checks whether the login already exists.
4. The password is transformed with `password_hash($mdp, PASSWORD_DEFAULT)`.
5. The model inserts the account with a PDO prepared statement.
6. The login form is displayed after successful registration.

The current server-side validation does not perform full email-format, length, or password-strength validation.

## Failed Login Attempt Protection

When an existing user submits the wrong password, the application attempts to insert the login into `TentativeConnexion` (referenced in PHP as `tentativeconnexion`). The MariaDB/MySQL-compatible `BEFORE INSERT` trigger counts recent records for that login. If three attempts already exist in the previous hour, the trigger raises SQLSTATE `45000`; PHP catches the resulting `PDOException` and reports the limit message.

```text
Wrong password
  -> INSERT login into TentativeConnexion
  -> BEFORE INSERT trigger
  -> COUNT(*) for NEW.login during the last hour
  -> count >= 3?
       yes -> SIGNAL SQLSTATE '45000' -> PDOException in PHP
       no  -> store the failed-attempt record
```

This is not complete brute-force protection. The current implementation only controls insertion of recorded failures for an existing login. It does not stop `password_verify()` from running after three failures, does not block a correct password after three failures, does not record unknown logins, and does not limit attempts by IP address.

## Security

### Password Security

Registration stores a password hash created with `password_hash()` and `PASSWORD_DEFAULT`. Login compares the submitted password with that hash using `password_verify()`. Plaintext passwords are not intentionally stored by the application.

### SQL Injection Protection

Database queries use PDO prepared statements with bound parameters for login lookup, registration, and failed-attempt insertion. This separates user input from SQL syntax.

### XSS Protection

The profile view escapes session values with:

```php
htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
```

This protects the displayed profile fields from being interpreted as HTML. Error messages in the current login and registration views are output directly, so output escaping is not applied consistently throughout the application.

### Login Attempt Protection

A database `BEFORE INSERT` trigger limits stored failed-password records to three per login in a rolling one-hour window. The trigger uses `NEW.login`, counts matching recent rows, and raises SQLSTATE `45000` when the limit is reached.

### Session Security

The application starts a PHP session, stores authenticated user information in `$_SESSION`, checks `user_id` before serving the profile, and clears and destroys the session during logout. Session ID regeneration after login and explicit cookie-hardening options are not currently implemented.

## Database

The project is currently tested with MariaDB through XAMPP and uses MySQL-compatible SQL. The database contains at least these tables:

### `Utilisateur`

Stores the account fields used by the application: `id`, `nom`, `prenom`, `email`, `login`, and the password hash in `mdp`.

### `TentativeConnexion`

Stores failed-password records with a login and a timestamp such as `date_tentative`. The trigger uses these records to count attempts from the last hour. The PHP model uses the lowercase spelling `tentativeconnexion`; keep table-name casing consistent on case-sensitive systems.

The repository includes `database/schema.sql` as the versioned place for the database structure and trigger definition.

## Trigger

The relevant database logic is conceptually:

```sql
BEFORE INSERT ON TentativeConnexion

COUNT(*)
WHERE login = NEW.login
  AND date_tentative >= NOW() - INTERVAL 1 HOUR

IF count >= 3
  -> SIGNAL SQLSTATE '45000'
```

The trigger rejects the fourth stored failure within the one-hour window. It does not itself authenticate users or prevent successful login with the correct password.

## Installation

1. Install XAMPP with Apache, PHP, and MySQL/MariaDB.
2. Clone or place the project under the XAMPP `htdocs` directory.
3. Start Apache and MySQL/MariaDB from the XAMPP control panel.
4. Create the database, then import `database/schema.sql` to create the required tables and trigger.
5. Install PHP dependencies from the project directory:

   ```bash
   composer install
   ```

6. Create a local `.env` file beside `index.php` with your own values:

   ```dotenv
   servername=localhost
   username=your_database_user
   password=your_database_password
   dbname=your_database_name
   ```

   Never commit real credentials. Configure the web server so `.env` cannot be downloaded.

7. Open the application through Apache, for example:

   ```text
   http://localhost/connection_form_php/connextion_form/index.php
   ```

The exact URL depends on the local folder name. Do not open the PHP files directly from the filesystem; the application expects to run through a PHP-enabled web server.

## How to Test

Use a dedicated local database and test account. Do not use real passwords or production data.

1. **Registration:** open `page=inscription`, submit valid non-empty values, and confirm that the account is created.
2. **Successful login:** log in with the new account and confirm that the profile page opens.
3. **Wrong password:** use the correct login with an incorrect password and confirm that an invalid-credentials message is shown.
4. **Failed attempts:** repeat the wrong-password test and inspect `TentativeConnexion` to confirm that failed attempts for an existing login are recorded.
5. **Trigger behavior:** after three recent recorded failures for one login, submit another wrong password and confirm that SQLSTATE `45000` is raised by the trigger and handled by PHP.
6. **Logout:** select logout and confirm that the session is cleared and the profile redirects to login.
7. **Profile protection:** open `page=profile` without an authenticated session and confirm that it redirects to login.
8. **XSS output escaping:** register or use a harmless value such as `<b>Test</b>` in a profile field. It should be displayed as text, not interpreted as bold HTML.

## Known Limitations

- A login that is not found currently returns `null` from the model while the controller expects an array, so this path can produce a PHP warning instead of a structured authentication error.
- The failed-login trigger limits recorded failed-password inserts, not all login attempts. A correct password can still authenticate after three previous failures.
- Unknown logins are not inserted into `TentativeConnexion`, so the mechanism does not cover repeated attempts against nonexistent accounts.
- The current protection does not apply IP-based rate limiting.
- CSRF tokens are not currently implemented for state-changing forms.
- The session ID is not regenerated after authentication, and Secure, HttpOnly, and SameSite session-cookie options are not explicitly configured.
- Registration validation checks required fields but does not yet fully validate email format, field lengths, or password strength.
- Profile values are escaped, but output escaping is not yet applied consistently to every user-controlled message.
- Database-level uniqueness for login or email is not established by the application code.
- Database connection errors can still expose internal details in development.
- The project does not yet include automated unit, integration, or end-to-end tests.
- Some referenced filenames or table names use different capitalization conventions, which may cause issues on case-sensitive systems.
- The interface and messages are primarily in French.

## Future Improvements

The following are future improvements and are **not currently implemented**:

- Add CSRF tokens and server-side request validation to every state-changing form.
- Regenerate the session ID after successful login and configure secure session cookies.
- Enforce authentication throttling or temporary lockout consistently, including unknown logins and IP-based limits.
- Add database constraints such as unique login/email rules and stronger validation for account data.
- Escape all user-controlled output, including form error messages.
- Replace browser-visible database errors with generic user messages and server-side logs.
- Add automated tests for registration, authentication, sessions, the trigger, and output escaping.
- Add email verification and password-reset workflows if the project grows beyond local learning use.

## What I Learned

This project provided practice with:

- PHP request handling and PHP sessions
- MVC-style separation between controllers, models, and views
- PDO and prepared statements
- Password hashing and password verification
- Authentication and protected routes
- MariaDB/MySQL-compatible triggers and `BEFORE INSERT`
- Trigger values such as `NEW.login`
- `SIGNAL SQLSTATE '45000'` and PDO exception handling
- HTML output escaping and basic XSS prevention
- Debugging the interaction between PHP and the database

## Author

Roman Salamzada
