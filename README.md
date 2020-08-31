# Pace

**Simple static site generation**

Using, [Laravel Blade](https://laravel.com/docs/7.x/blade),
       [Tailwind CSS](https://tailwindcss.com), and
       [Alpine.js](https://github.com/alpinejs/alpine)

## Installation

Create a new Pace project

```bash
composer create-project ellgreen/pace-template
```

Install the npm dependencies

```bash
npm install
```

Build the assets

```
npm run dev
```

## Serving the application

### Development

The following command will serve the site to http://localhost:8000

```bash
./pace serve
```

### Production

Build the site for production using the following command.
This will generate the static site into `build/`.

```bash
./pace build
```

## Application structure

A default Pace application will look like this:

```
.
+-- resources/
|   +-- views/
|       +-- components/
|           +-- layout/
|               +-- app.blade.php
|       +-- pages/
|           +-- index.blade.php
|           +-- about.blade.php
|   +-- css/
|       +-- app.css
|   +-- js/
|       +-- app.js
+-- build/
+-- build_prod/    
+-- pace
```
