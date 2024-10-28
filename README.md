# Gigs API

Gigs API is Restful API for the Gigs platform built using PHP/Laravel.
This API is for a platform for listing gigs. A gig is a job that lasts a certain period of time, offten short-term.

All the API calls (Routes) except Login and Register.
## Features

- **User Register**: API call for creating User.
- **User Login**: API call for Login previously created User.
- **User Profile**: API call for getting profile data for the Authenticated User.
- **User Update**: API call for updating User details for the Authenticated User.
- **Company Create**: API call for creating Company for the Authenticated User.
- **Company Update**: API call for updating Company created for the Authenticated User.
- **Company Delete**: API call for deleting Company created for the Authenticated User.
- **Companies List**: API call for listing all Companies for the Authenticated User
- **Gig Store**: API call for creating Gig, for a Company that is created by Authenticated User.
- **Gig Update**: API call for updating Gig data, for a Company that is created by Authenticated User.
- **Gig Delete**: API call for deleting Gig for a Company that is created by Authenticated User.
- **Gigs List**": API call for listing all Gigs for the Company for the Authenticated User.
- **Gigs List (Filter)**: API call for filtering Gigs by 'company', 'progress', or 'status'
ex. `?company_id=1&progress=started&status=posted`
- **Gigs List (Search)**: API call for filtering Gigs by search input that matches with Gig Name or Gig Description
ex. `?search=Marjan`
- **Gigs List (Filter and Search)**: API call for filter and search for a Gigs in the same time
ex. `?company_id=1&progress=started&status=posted&search=Description`

## Getting Started

### Prerequisites
- PHP and Laravel installed on your system
- A web server environment (such as XAMPP or Laravel Sail)

### Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/marjankolev94/primeyear-app.git

2. Navigate to the project directory:
   ```bash
   cd gigs-api

3. Install dependencies:
   ```bash
   composer install

4. Set up environment variables:
- Copy .env.example to .env and configure your database and application settings as needed.

5. Run migrations:
   ```bash
   php artisan migrate

6. Serve the application
   ```bash
   php artisan serve

   There is no visual presentation of this app.

### Usage
- Postman can be used for testing the API calls
### Technologies Used
- PHP: Server-side scripting language.
- Laravel: PHP framework for building modern web applications.
- Sanctum: Used for Authentication
- Hash: Facade is used for Hashing passwords in the Database
- Auth: Facade is used for interaction with the Users
### Contributing
Contributions are welcome! Please fork the repository and create a pull request with any improvements or bug fixes.

### License
This project is open-source and available under the MIT License.