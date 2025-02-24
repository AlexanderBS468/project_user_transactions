# Project «User Transactions Information»

This PHP-based application displays monthly transaction balances for users by calculating the difference between incoming and outgoing transactions. 
It demonstrates an object-oriented approach using a simple MVC (Model-View-Controller) pattern, with AJAX loading of data and a custom SPL autoloader.

## Features

- **User Management:** Lists users and allows selection via a form.
- **Transaction Balances:** Calculates monthly balances (incoming minus outgoing) for the selected user.
- **Object-Oriented Structure:** Organized into Models, Controllers, and Components.
- **Custom Router** – Routes requests without needing a framework, based on URL patterns.
- **AJAX Loading:** Loads transaction data asynchronously without full page reloads.
- **Built-In PHP Server:** Easily runnable using PHP's built-in server.
- **Dev Container Support:** Configured for CodeSandbox and other dev container environments.

## Project Structure

project-root/
├── .codesandbox/                # CodeSandbox configuration (tasks, etc.)
│   └──tasks.json                # Tasks
├── .devcontainer/
│   ├──Dockerfile                # Dockerfile for dev container (enabling intl, etc.)
│   └──devcontainer.json         # Dev container configuration for VS Code / CodeSandbox
├── core/
│   ├── controller/
│   │   └── usertransactions.php  # Handles user transaction logic
│   ├── functions/                # Additional PHP helper functions
│   ├── index.php                 # Main router or front controller
│   ├── model/
│   │   ├── appdb.php            # Database connection (PDO, singleton)
│   │   └── …                    # Other model classes
│   ├── pages/                   # Page-specific scripts or views
│   └── router/                  # Routing logic files
├── view/
│   ├── component/
│   │   └── usertransactionsinfo.php # Component for displaying user transaction info
│   └── main/                    # default template
│       ├── assets/              # assets template
│       ├── header.php           # Header layout
│       └── footer.php           # Footer layout
├── pages/
│       └── …                    # routing pages files
├── index.php                    # Entry point (can delegate to /core/index.php)
├── README.md                    # Project description and usage
└── … (other static files)

## Requirements

- **PHP 8.2+** (with SQLite and, optionally, the intl extension for date localization).
- **SQLite** – The project uses a `database.sqlite` file for data storage.
- **Dev Container / Docker** (optional) – If you want to run the project in a container with pre-installed dependencies.

## Installation & Setup

1. **Clone the repository** (or download the source code):
   ```
   git clone git@github.com:AlexanderBS468/project_user_transactions.git
   cd your-project
   ```
2.	Database Setup
	•	Make sure database.sqlite exists (or create it if necessary).
	•	Run any initialization script if you have one (for creating tables, seeding data, etc.). 
3.	Optional: Use a Dev Container
	•	If you use VS Code or CodeSandbox, open the project in the container. The provided Dockerfile and devcontainer.json handle installing extensions (like php-intl for format DateTime).
The extension is used in the component's render response.

##Running the Project

-Via PHP Built-in Server

1.	From the project root, run:
```
php -S localhost:8080 core/router/static.php
```

2.	Open http://localhost:8080 in your browser.

-Via CodeSandbox

1.	The file .codesandbox/tasks.json contains the start command (e.g., "php -S 0.0.0.0:8080 core/router/static.php").
2.	The sandbox will start automatically. Click “Open in New Window” or the browser preview to see the app.

Usage
	•	Select a user in the dropdown form.
	•	Click “Show” to load monthly transaction balances via AJAX.
	•	The data will be displayed without a full page reload.

Customization
	•	Change Styles: Modify view/main/assets/style.css.
	•	Add/Remove Controllers: Place new controllers in core/controller/.
	•	Add Models: Extend or create new classes in core/model/.
	•	Templates/Views: Update or create additional PHP templates in view/.

Troubleshooting
	•	Check if database.sqlite is initialized with the correct tables.
	•	Verify no file locks are preventing reads/writes (SQLite uses file locks).
	•	Inspect logs or error output for any PDO errors.
	•	Intl Extension:
	•	If date localization fails, ensure the php-intl extension is installed (the Dockerfile and devcontainer help with that).

##License

This project is for educational and demonstration purposes. Feel free to modify and adapt it to your needs.