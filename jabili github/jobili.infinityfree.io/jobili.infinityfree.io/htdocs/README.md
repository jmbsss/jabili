# Jobili Store Management System

A PHP + MySQL web application for a small store, designed with a clean white interface and minimal red accents.

## Included features

- Login with separate owner and cashier roles
- Dashboard with daily sales, estimated gross profit, expenses, seven-day sales, recent transactions, and low-stock alerts
- POS checkout with cart, discount, payment method, change calculation, receipt, and automatic stock deduction
- Product catalog, SKU, category, selling price, cost, reorder level, stock adjustments, and inventory log
- Customer directory and utang tracking with partial payments
- Expense records and date filtering (owner-only changes)
- Date-filtered reports, transaction history, and print-to-PDF
- MySQL schema with foreign keys and prepared SQL statements
- Responsive layout for desktop and mobile

## Requirements

- XAMPP with Apache, PHP 8.1+ recommended, and MySQL/MariaDB
- A modern browser

## Installation (Windows / XAMPP)

1. Extract the `jobili_storee` folder into `C:\xampp\htdocs\`. The resulting path should be `C:\xampp\htdocs\jobili_storee`.
2. Open the XAMPP Control Panel and start **Apache** and **MySQL**.
3. Visit `http://localhost/phpmyadmin`.
4. Choose **Import**, select `install.sql` from the project folder, then click **Import/Go**. This creates the `jobili_storee` database and starter product categories.
5. Open `config.php` and confirm the database settings. Standard XAMPP uses username `root` and an empty password. If yours is different, update `DB_USER` and `DB_PASS`.
6. Visit `http://localhost/jobili_storee/setup.php`.
7. Create your owner account (display name can be `BSCS`) and cashier account. Choose passwords of at least 8 characters.
8. Sign in at `http://localhost/jobili_storee/login.php`.
9. **Security:** after creating accounts, delete `setup.php` from the project folder. Do not deploy the default XAMPP database credentials to a public server.
10. Add categories if needed in phpMyAdmin, then create products in **Products & Inventory**.

## How to use

1. Add products and their unit costs, selling prices, and initial stock.
2. Add customer records before recording an utang sale.
3. Use **POS Register** to add items, select payment, enter amount received, and complete checkout.
4. The sale is saved, quantities are deducted, inventory logs are created, and a receipt is available.
5. In **Customers & Utang**, record partial payments against open balances.
6. Owner account can record expenses and manage user accounts.
7. Use **Reports** to filter by date range and print or save reports as PDF.

## Important behavior / limitations

- Sales and stock deductions use a database transaction to avoid partially completed checkouts.
- Cash, GCash, and bank transfer are treated as paid methods. The amount received must cover the total; cash change is calculated.
- Utang sales require a customer and open a balance. Partial collections update the remaining balance; a fully paid balance changes to `paid`.
- Product archive is used instead of hard deletion to preserve sale history.
- Gross profit is an estimate based on product cost saved on each sale. Taxes, supplier purchase orders, returns/voids, barcode scanner integration, receipt printer integration, and multi-branch syncing are not included in this starter build.
- This project is intended for local learning/prototyping. Before public deployment, add HTTPS, server-side rate limiting, database backups, stronger access policies, and a production database user with limited permissions.

## Troubleshooting

- **Access denied for MySQL:** check `DB_USER` and `DB_PASS` in `config.php`.
- **Unknown database:** import `install.sql` in phpMyAdmin.
- **Page not found:** confirm the folder is under `C:\xampp\htdocs\jobili_storee` and open the URL with `/jobili_storee/`.
- **Blank / server error:** check Apache's PHP error log from XAMPP.
