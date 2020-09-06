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

<img src="https://github.com/ellgreen/pace/blob/master/.assets/template-screenshot.png" width="800" title="Pace template screenshot" alt="Pace template screenshot">

You can view the template here:

https://github.com/ellgreen/pace-template

## Markdown

Using markdown in Pace is easy, just create a new page with the `.md` extension and write some markdown.

### Embedding Markdown in Blade

To embed markdown in a blade view you can add the `extends` option to the yml front matter like so:

```markdown
---
extends: components.layout.post
---

# This is my post

* **bold**
* *italic*
* `code`
```

The rendered markdown will then be made available to the template as the `$markdown` variable.

### Adding metadata

You can pass extra metadata from the markdown to the view it extends like so:

```markdown
---
extends: components.layout.post
title: This is my post
---

Post content goes **here**
```

Then it can be accessed in the blade view like this:

```blade
<section>
    <h1>{{ $page->title }}</h1>

    <x-markdown :markdown="$markdown" class="mt-6" />
</section>
```
