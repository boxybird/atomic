# Atomic

Atomic integrates [HTMX](https://htmx.org/) fragments into WordPress, enabling dynamic partial page updates without full reloads.

## Installation

```
cd wp-content/plugins
git clone https://github.com/boxybird/atomic atomic-htmx
cd atomic-htmx
composer install
```

> Location: /wp-admin/plugins.php

Activate plugin

> Location: /wp-admin/options-permalink.php

Visit and refresh permalinks by clicking **"Save Changes"** button

## Important

By default, this package pre-bundles the HTMX library. If you site already has HTMX installed, you should dequeue this
packages version to avoid conflicts.

> Location: /your-theme/functions.php

```php
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_script('atomic-htmx-script');
});
```

## Usage

```HTML
<button hx-get="/atomic/v1/my-hook" hx-target="#results">GET REQUEST</button>
<div id="results"></div>
```

```PHP
add_action('atomic/get/my-hook', function (int $post_id) {
    echo "<p>HTMX GET request fragmennt - ID: {$post_id}</p>";
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