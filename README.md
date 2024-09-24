<p align="center">
    <a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

<p align="center">
    <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Project Description

This project is built with the [Laravel](https://laravel.com) framework, which provides an expressive and elegant syntax, easing common tasks like routing, authentication, and database migrations. The project includes various features like user authentication, dynamic content display, and other powerful tools that streamline web application development.

## About the Project

### 1. API Endpoints

#### Books:
- Create a new book.
- Retrieve a list of books.
- Retrieve details of a specific book.
- Update a book.
- Delete a book.

#### Authors:
- Create a new author.
- Retrieve a list of authors.
- Retrieve details of a specific author.
- Update an author.
- Delete an author.

### 2. Authentication
- Implement HTTPOnly cookies for authentication.
- Secure API endpoints so that only authenticated users can perform CRUD operations.

### 3. Validation and Error Handling
- Validate incoming requests for creating and updating books and authors.
- Provide appropriate error messages for invalid requests.

### 4. Database
- Design a MySQL database schema to store books and authors.
- Use Laravel’s Eloquent ORM to interact with the database.

## Setup Instructions

To set up this project locally, follow the steps below:

1. **Clone the Repository:**
    ```bash
    git clone https://github.com/your-repository-link.git
    cd project-directory
    ```

2. **Install Dependencies:**
    Ensure that you have [Composer](https://getcomposer.org/) installed. Then run:
    ```bash
    composer install
    ```

3. **Copy the .env file:**
    ```bash
    cp .env.example .env
    ```

4. **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```

5. **Set up Database:**
    - Update your `.env` file with your database credentials.
    - Run the migrations to set up the database:
      ```bash
      php artisan migrate
      ```

6. **Serve the Application:**
    Run the Laravel development server:
    ```bash
    php artisan serve
    ```

Now, the project should be up and running at [http://localhost:8000](http://localhost:8000).

## Documentation

- Code is well-documented, and you can find comments in the codebase explaining key logic.
- Detailed API documentation can be found in the [docs](./docs) directory.
  
## Demo

Once deployed, you can access the live demo here: **[Live Demo](#)** (Replace `#` with your live demo URL).

## Learning Laravel

Laravel has extensive documentation to get you started, including [Laravel Bootcamp](https://bootcamp.laravel.com) and [Laracasts](https://laracasts.com) for video tutorials.

## Contributing

Thank you for considering contributing! Check out the [contribution guide](https://laravel.com/docs/contributions) for more information.

## License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT).
