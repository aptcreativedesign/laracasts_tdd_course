<p align="center"><a href="https://laracasts.com/" target="_blank"><img src="https://laracasts.com/images/logo/logo-white.svg" width="400" alt="Laravel Logo"></a></p>

## About Laracasts TDD Course

This repository contains the code for the Laracasts TDD course. The course is a 44 part series that covers the basics of TDD, and how to apply it to your Laravel applications.

- [Build A Laravel App With TDD](https://laracasts.com/series/build-a-laravel-app-with-tdd).

Create Birdboard: a minimal Basecamp-like project management app in Laravel 10.

Uses the Jetstream livewire scaffolding with Tailwind CSS.


## Installation
Create a new MySQL database and update the `.env` file with the database credentials.

```bash
composer install
php artisan migrate
npm install
```

## Mailpit ?


## Notes
- Feature and Unit test functions are named `test_` followed by the name of the feature or unit being tested.


- Factories are used to generate dummy data for testing purposes. The `database/factories` directory contains the factory definitions for the models. The `database/seeds` directory contains the seeders that use the factories to generate dummy data. 
- The way that factories are created via `tinker`differs from the course vide and can be found in the [Laravel documentation](https://laravel.com/docs/10.x/eloquent-factories).<br>
```bash
- php artisan tinker
- App\Models\Project::factory()->count(5)->create();
```
