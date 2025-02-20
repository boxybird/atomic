# Atomic

Atomic integrates [HTMX](https://htmx.org/) fragments into WordPress, enabling dynamic partial page updates without full
reloads.

## Installation

```
composer require boxybird/atomic
```

> Location: /wp-config.php

```php
define('ATOMIC_ENCRYPTION_KEY', 'SOME_RANDOM_16_CHARACTER_STRING');
```

## Theme/Plugin Setup

Whether you're installing this package in a theme or plugin, you must define base URL of the install.

```php
// Theme example:
require_once __DIR__.'/vendor/autoload.php';
BoxyBird\Atomic\Atomic::init(get_stylesheet_directory_uri());

// Plugin example:
require_once __DIR__.'/vendor/autoload.php';
BoxyBird\Atomic\Atomic::init(plugin_dir_url(__FILE__));
```

## Flush Permalinks

> Location: /wp-admin/options-permalink.php

Visit and refresh permalinks by clicking **"Save Changes"** button

## Usage

```HTML

<button hx-get="/atomic/v1/my-hook" hx-target="#results">GET REQUEST</button>
<div id="results"></div>
```

```PHP
add_action('atomic/get/my-hook', function (int $post_id) {
    echo "<p>HTMX GET request fragmnt - ID: {$post_id}</p>";
});
```

---

```HTML

<button hx-get="/atomic/v1/my-other-hook" hx-target="#results">GET REQUEST</button>
<div id="results"></div>
```

```PHP
add_action('atomic/get/my-other-hook', function (int $post_id) {
    echo "<p>HTMX GET request fragment - ID: {$post_id}</p>";
});
```

---

```HTML

<button hx-post="/atomic/v1/my-hook" hx-target="#results">POST REQUEST</button>
<div id="results"></div>
```

```PHP
add_action('atomic/post/my-hook', function (int $post_id) {
    get_template_part('fragments/post', 'my-template', args: ['post_id' => $post_id]);
});
```

---

```HTML

<button hx-put="/atomic/v1/my-hook">PUT REQUEST</button>
<button hx-patch="/atomic/v1/my-hook">PATCH REQUEST</button>
<button hx-delete="/atomic/v1/my-hook">DELETE REQUEST</button>
```

```PHP
add_action('atomic/put/my-hook', ...);
add_action('atomic/patch/my-hook', ...);
add_action('atomic/delete/my-hook', ...);
```