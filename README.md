# Pace

**Simple static site generation**

Using, [Laravel Blade](https://laravel.com/docs/7.x/blade),
       [Tailwind CSS](https://tailwindcss.com), and
       [Alpine.js](https://github.com/alpinejs/alpine)

## Installation

### Create a new Pace project

```bash
composer create-project ellgreen/pace-template blog
```

This will create a directory named `blog` with the scaffolding for your new Pace application

### Install the npm dependencies

```bash
npm install
```

### Building the static site

#### For development

The following command will build the site into `build/`

```bash
npm run dev
```

##### Watching for changes + BrowserSync

To watch for changes in the files, and automatically rebuild the site, use:

```bash
npm run watch
```

To disable BrowserSync, add `browserSync: false` to the config object passed to the Laravel mix Pace plugin

#### Building the site for production

The following command will build the production ready static site into `build_prod/`

```bash
npm run prod
```

## Serving the application

### Development

The following command will serve the site to http://localhost:8000

```bash
./pace serve
```

Use the `--prod` option to serve the production site

## Template

When you serve the initial template, it should look like this:

<p align="center">
    <img src="https://github.com/ellgreen/pace/blob/development/.assets/template-screenshot.png" width="800" title="Pace template screenshot" alt="Pace template screenshot">
</p>

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
