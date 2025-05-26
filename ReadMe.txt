/project-root
│
├── /api
│   ├── /employee
│   │   ├── create.php
│   │   ├── login.php
│   │   └── list.php
│   │
│   ├── /project
│   │   ├── create.php
│   │   ├── list.php
│   │   └── update.php
│   │
│   ├── /task
│   │   ├── create.php
│   │   ├── assign.php
│   │   ├── list.php
│   │   ├── /comments
│   │   │   ├── post.php        // Threaded + @mention support
│   │   │   └── read.php
│   │   ├── /files
│   │   │   ├── upload.php      // File upload API
│   │   │   └── list.php
│   │   └── reminders.php       // Scheduled task alerts (cron-compatible)
│
├── /config
│   ├── db.php                  // DB connection
│   ├── auth.php                // JWT or session-based auth
│   ├── roles.php               // Role-based middleware logic
│   └── config.php              // Global constants, file paths, etc.
│
├── /middleware
│   ├── authMiddleware.php      // Auth check
│   └── rbacMiddleware.php      // Role-based access control
│
├── /logs
│   └── admin_actions.log       // Logs stored here
│
├── /uploads
│   └── /tasks                  // Task file uploads
│
├── /utils
│   ├── response.php            // Standard API responses
│   ├── validate.php            // Input validation functions
│   └── logger.php              // Admin action logger
│
└── index.php                   // Entry or test route


🔵 1. config – Start Here
Create these files first:

db.php – DB connection using PDO

config.php – Global constants like file paths, allowed roles

roles.php – Role definitions (e.g., manager = 1, lead = 2)

✅ Purpose: Sets the base to access DB and constants from any file.

🔵 2. utils
response.php – Utility for consistent JSON responses

validate.php – Input sanitization and custom validation

logger.php – Admin logging function

✅ Purpose: Helps you avoid rewriting code for every API response, logging, etc.

🔵 3. middleware
authMiddleware.php – Auth check (session or JWT)

rbacMiddleware.php – Role-based access control (e.g., only manager can create projects)

✅ Purpose: Protect your APIs using reusable functions.

🔵 4. logs
admin_actions.log – Create the file, and logger.php will write to it.

✅ Purpose: Logging system is ready from Day 1.

🔵 5. uploads
/tasks/ folder – Create this for file uploads later

Make sure it’s writable (chmod 755)

✅ Purpose: Prepare structure for file upload feature (even if added later).

🔵 6. api
Now begin actual API module coding in this order:

🔹 employee/
create.php – Registration

login.php – Auth

list.php – List employees

⬆ This unlocks login & role-based access for rest of the modules.

🔹 project/
create.php – Manager only

list.php – All roles

update.php – Manager only

🔹 task/
create.php – Manager & Lead

assign.php – Assign to employee

list.php – Task list by role or project

🔹 task/comments/
post.php – Add comment (with parent ID for threading)

read.php – Read threaded comments

🔹 task/files/
upload.php – Upload task-related files

list.php – List all files for a task

🔹 task/reminders.php
API to return overdue or upcoming tasks

✅ Final: index.php (optional)
Only use index.php if you want to route or test APIs manually. Otherwise, direct access to /api/*/*.php is clean and fine.