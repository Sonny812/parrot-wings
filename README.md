# PW Application

## Local deployment
### Without Docker

#### Requirements
*   **PHP 7.4**
*   **Symfony CLI**
*   **MySQL 5.7 or 8.0**
*   **[Mercure Hub](https://mercure.rocks/)**

##### Deploy Mercure Hub
Download the Mercure Hub binary and run it.
```shell script
./mercure --jwt-key='!ChangeMe!' --addr='localhost:3000' --allow-anonymous --cors-allowed-origins='*'
```  
Arguments:
- `--jwt-key` - any string
- `--addr` - address of host with any free port.

##### Deploy backend
```shell script
cd backend

composer install

cp .env .env.local

# edit .env.local

php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

symfony server:start --allow-http --no-tls --port=8000 # Instead of 8000, you can use any free port
```

You have to set the next env variables:
- MERCURE_SECRET - the key that you used when you deployed Mercure.
- MERCURE_JWT_TOKEN - JWT for the Mercure key. The default value is valid JWT for the string `!ChangeMe!`.
- MERCURE_PUBLISH_URL - the address where running your Mercure Hub.
- DATABASE_URL - DSN string for your MySQL.

##### Deploy frontend
```shell script
cd frontend

yarn # or npm i

cp .env .env.local

# edit .env.local
```
You need to set the address where your backend is running to the `APP_REACT_API_URL` variable.

Then you have to run `yarn start` or `npm run start` and the application will be available at http://localhost:3000

### With Docker
#### Requirements
- Docker
- Docker Compose

To run the application with Docker you have to .env by copying the .env.dist file at the root of the project
and set values for *_PORT variables. These can be any free ports.  
Then run `docker-compose up`.   
The app will be available at the port that you specified at the `FRONTEND_PORT` variable.

Admin user credentials:
`admin@example.com : admin`

## PW Application Overview
The application is for Parrot Wings (PW, “internal money”) transfer between system users.

The application will be very “polite” and will inform a user of any problems (i.e. login not successful, not enough PW to remit the transaction, etc.)

### User registration 
Any person on Earth can register with the service for free, providing their Name (e.g. John Smith), valid email (e.g. jsmith@gmail.com) and password. 

When a new user registers, the System will verify, that the user has provided a unique (not previously registered in the system) email, and also provided human name and a password. These 3 fields are mandatory. Password is to be typed twice for justification. No email verification required.

On successful registration every User will be awarded with 500 (five hundred) PW starting balance.

### Logging in 
Users login to the system using their email and password.

Users will be able to Log out.

No password recovery, change password, etc. functions required.

### PW
The system will allow users to perform the following operations:

1. See their Name and current PW balance always on screen
2. Create a new transaction. To make a new transaction (PW payment) a user will
    * Choose the recipient by querying the  User list by name (autocomplete). 
    * When a recipient chosen, entering the PW transaction amount. The system will check that the transaction amount is not greater than the current user balance.
    * Committing the transaction. Once transaction succeeded, the recipient account will be credited (PW++) by the entered amount of PW, and the payee account debited (PW--) for the same amount of PW. The system shall display PW balance changes immediately to the user.
3. (Optional) Create a new transaction as a copy from a list of their existing transactions: create a handy UI for a user to browse their recent transactions, and select a transaction as a basis for a new transaction. Once old transaction selected, all its details (recipient, PW amount) will be copied to the new transaction.
4. Review a list (history) of their transactions. A list of transactions will show the most recent transactions on top of the list and display the following info for each transaction:
    * Date/Time of the transaction
    * Correspondent Name
    * Transaction amount, (Debit/Credit  for PW transferred)
    * Resulting balance
    
(Optional) Implement filtering and/or sorting of transaction list by date, correspondent name and amount. 
### Administrative Panel
The system will allow administrative user to perform the following operations:

1. List/View/Edit users with BAN option; List should be filterable and sortable
2. List/View/Edit PW transactions; List should be filterable and sortable

Administrative user should be predefined.
