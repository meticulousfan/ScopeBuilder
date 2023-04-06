## Steps to Setup the project locally
<p>First clone dev branch on your system using following command</p>

Step 1 - Run git clone

```sh
git clone --single branch --branch <branchname> <remote - repo - url>
```
Example:

```sh
git clone --single-branch --branch dev git@github.com:mind2matterteam/ScopeBuilder.git
```

Step 2 - Open the cloned folder and copy ```scopebuilder.sql``` to get the SQL.
Create a database and databse user/password and import the SQL into your database.

Step 3 - Connection 
Go to the folder application using cd command on your cmd or terminal. 

Run composer install on your cmd or terminal

Copy ```.env.example``` file to ```.env``` on the root folder. You can type copy .env.example .env if using command prompt Windows or 

```sh
cp .env.example .env 
```

if using terminal, Ubuntu

Open your .env file and change the database name ```DB_DATABASE```, username ```DB_USERNAME``` and password ```DB_PASSWORD``` field to correspond to your configuration in Steo 2.

Also change SMTP details and Stripe details to yours if you choose to.

Run 

```sh
php artisan key:generate
```

Run 

```sh
php artisan migrate
```
Run the following command to seed the database with admin credentials

```sh
php artisan seed
```

Run 

```sh
php artisan serve
```

Go to http://localhost:8000/

This will open the client portal. 

Admin and Developer portals are accessible at /admin and /developer respectively. Create Client and Developer accounts and verify via emails to proceed. [Mailtrap](https://mailtrap.io) is a good option to set SMTP details for testing.
