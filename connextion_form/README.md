# PHP Registration and Login Form

A small PHP application for creating an account, signing in, viewing a protected profile, and signing out. It uses a simple MVC-style structure, a MySQL/MariaDB database, PHP sessions, and custom CSS. Most form labels are in French.

## Features

- Registration with surname (`nom`), first name (`prenom`), email, login, and password.
- Password hashing with `password_hash()` and verification with `password_verify()`.
- Database queries using PDO prepared statements.
- Login using a login name and password.
- A protected profile displaying the signed-in user's name, email, and login.
- Logout that clears session data, destroys the session, and expires the session cookie.
- Database configuration loaded from a local `.env` file through `vlucas/phpdotenv`.

## Requirements

- A PHP-enabled web server. The setup below uses XAMPP on Windows with Apache.
- PHP with sessions, PDO, and the `pdo_mysql` extension enabled.
- MySQL or MariaDB.
- Composer, or the included `composer.phar`, to install PHP dependencies.

The current code passed PHP syntax checks with the local XAMPP PHP 8.2.12 installation. Composer's installed dependency platform checks also passed in that environment.

`package.json` lists `@dotenvx/dotenvx`, but the PHP application does not call it. Node.js, `npm install`, and a frontend build are not required for the current pages.

## Local setup with XAMPP

### 1. Place the project in the web directory

For the URLs used below, the application files should be located at:

```text
C:\xampp\htdocs\connection_form_php\connextion_form
```

Start **Apache** and **MySQL** from the XAMPP Control Panel.

### 2. Install PHP dependencies

In PowerShell, run:

```powershell
Set-Location 'C:\xampp\htdocs\connection_form_php\connextion_form'
& 'C:\xampp\php\php.exe' composer.phar install
```

If Composer and the correct PHP executable are already on your PATH, you can use `composer install` instead. Installation creates the `vendor/autoload.php` file required by `config/database.php`.

### 3. Create the database and table

Open [local phpMyAdmin](http://localhost/phpmyadmin/) and use its SQL tab to run the following for a fresh installation:

```sql
CREATE DATABASE IF NOT EXISTS ex01
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE ex01;

CREATE TABLE Utilisateur (
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    login VARCHAR(50) DEFAULT NULL,
    email VARCHAR(80) NOT NULL,
    mdp VARCHAR(250) NOT NULL,
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

Skip table creation if you already have the application's table. `id` must be an auto-incrementing primary key because registration does not provide an ID in its insert query. `mdp` stores the password hash, not the original password.

The included [form.sql](form.sql) is an alternative database dump containing the table definition and existing account records. It does not create or select the database, so select the target database before importing it. It also contains `DROP TABLE IF EXISTS utilisateur`, which replaces that table and its data. Do not import it over data you need to keep. The schema above lets you start without importing the dump's account records.

The dump names the table `utilisateur`, while PHP queries use `Utilisateur`. This works with the current Windows database setup; see the portability notes below for systems that distinguish table-name case.

### 4. Configure the database connection

Create or edit `.env` beside `index.php` using these exact lowercase variable names:

```dotenv
servername=localhost
username=root
password=
dbname=ex01
```

This example assumes a local database account named `root` with an empty password. Replace the values with your own database credentials. Keep real credentials out of version control.

`config/database.php` reads these variables and creates the PDO connection. The current connection uses `charset=utf8`, enables exceptions for database errors, and returns fetched rows as associative arrays.

### 5. Open the application

- [Login](http://localhost/connection_form_php/connextion_form/index.php?page=connexion)
- [Registration](http://localhost/connection_form_php/connextion_form/index.php?page=inscription)
- [Profile](http://localhost/connection_form_php/connextion_form/index.php?page=profile) — requires login.

Use these URLs through Apache rather than opening a PHP file directly from the filesystem. Adjust the path if you installed the project in another directory.

## Project structure

```text
connextion_form/
|-- index.php                         # Routes requests using ?page=
|-- config/
|   |-- database.php                  # Environment variables and PDO connection
|   `-- session.php                   # Session startup, login checks, redirects
|-- Controllers/
|   |-- ControleurConnexion.php       # Processes login and fills the session
|   |-- ControleurInscription.php     # Validates required fields and registers users
|   |-- ControleurProfile.php         # Protects the profile and reads session data
|   `-- ControleurDeconnexion.php     # Ends the session and returns to login
|-- Models/
|   |-- InscriptionModel.php           # Hashes passwords and inserts users
|   `-- UtilisateurModel.php          # Finds users and checks their passwords
|-- View/
|   |-- connexion/
|   |   |-- FormulaireConnexion.php
|   |   `-- FormulaireIncription.php
|   `-- Profile.php
|-- public/css/                       # Styles for the two forms
|-- .env                              # Local database settings
|-- composer.json / composer.lock     # PHP dependency definitions
|-- composer.phar                     # Bundled Composer executable
|-- package.json / package-lock.json  # dotenvx dependency, unused by the PHP flow
|-- form.sql                          # Database dump with account records
`-- vendor/                           # Installed PHP dependencies
```

## Request flow

`index.php` loads the database connection and session helpers, then chooses a controller from the `page` query parameter. Missing or unrecognized page values open the login controller.

| Page parameter | Controller | Behavior |
| --- | --- | --- |
| `connexion` | `ControleurConnexion.php` | Displays the login form; handles login on POST. Already signed-in users are redirected to the profile. |
| `inscription` | `ControleurInscription.php` | Displays the registration form on GET; validates and inserts a user on POST. |
| `profile` | `ControleurProfile.php` | Requires a signed-in session and displays its user details. |
| `deconnexion` | `ControleurDeconnexion.php` | Clears the session and returns to login. The profile button sends POST, although the controller does not restrict the request method. |

### Registration

The form sends `nom`, `prenom`, `email`, `login`, and `mdp`. The controller trims the text fields and rejects empty required values. `IncriptionModel::createUser()` hashes `mdp` and inserts the account with a prepared statement.

After insertion, the controller includes the login view. It does not automatically sign in the new user or redirect the browser, so the address remains `?page=inscription`. A `$success` variable is set but is not displayed by the current login view.

### Login and profile

The login form sends `login` and `password`. `UtilisateurModel::authenticate()` finds an account by login and verifies the submitted password against its stored hash.

On success, the controller stores `user_id`, `nom`, `prenom`, `email`, and `login` in `$_SESSION`, then redirects to the profile. `require_login()` redirects visitors without `user_id` to login; signed-in users continue to the profile view.

## Manual verification

1. Open the registration URL directly and create an account with a new test login.
2. In phpMyAdmin, inspect the `Utilisateur` table. Confirm that the account exists, has an automatically assigned `id`, and has a hash in `mdp`.
3. Sign in with that login and password. Confirm that the profile displays the matching name, email, and login.
4. Click **Disconnect**. Confirm that the login page appears and reopening the profile redirects back to login.
5. Try an incorrect password. Confirm that an error is displayed and access to the profile is not granted.

For a local dependency check, run:

```powershell
& 'C:\xampp\php\php.exe' composer.phar check-platform-reqs
```

There is no automated application test suite configured. While preparing this README, all 12 application PHP files passed syntax checks and the installed Composer dependencies passed platform checks. Those checks do not verify the complete browser workflow; use the manual steps above for that.

## Known limitations and troubleshooting

- **Required-field error when opening registration from the login page:** the registration button on the login page sends an empty POST request. The controller treats it as a registration submission. Opening `?page=inscription` directly with GET avoids that initial error.
- **Duplicate accounts:** neither the supplied schema nor the registration code enforces unique logins or emails. Registration also lacks server-side email-format, field-length, and password-strength validation beyond its empty-field checks.
- **Refreshing after registration:** the login view is rendered as the response to the registration POST. Refreshing and resubmitting that request can create another record.
- **Missing login on an older session:** sessions created before the login field was stored will not gain it automatically. Log out and sign in again, or test in a fresh private browser session.
- **`could not find driver`:** check that the PHP executable running the application has `pdo_mysql` enabled. Your terminal's `php` command may point to a different installation from XAMPP's PHP.
- **Portability:** login controller includes use `formulaireConnexion.php`, the profile controller includes `profile.php`, and the login view links to `formulaireConnexion.css`. The actual filenames start with capital letters. These references, and the table-name case difference in the dump, need consistent casing on case-sensitive systems.
- **HTML output:** profile values are printed without HTML escaping, allowing stored user input to be interpreted as markup. Escape displayed user data before making the application available to untrusted users.
- **Session and form protection:** login does not regenerate the session ID, forms have no CSRF tokens, and there is no login rate limiting. These protections are not implemented in the current project.
- **Database errors:** connection failures display exception details, and registration insert errors have no application-level handler. Error handling needs improvement before deployment.
