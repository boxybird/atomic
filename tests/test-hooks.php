<?php

if (defined('ATOMIC_TESTS_ENABLED') && ATOMIC_TESTS_ENABLED === true) {
    add_action('atomic/get/test-hook', function (int $post_id) {
        echo '<p id="results">HTMX GET request fragment - ID: '.$post_id.'</p>';
    });

    add_action('atomic/post/test-hook', function (int $post_id) {
        echo '<p id="results">HTMX POST request fragment - ID: '.$post_id.'</p>';
    });

    add_action('atomic/put/test-hook', function (int $post_id) {
        echo '<p id="results">HTMX PUT request fragment - ID: '.$post_id.'</p>';
    });

    add_action('atomic/patch/test-hook', function (int $post_id) {
        echo '<p id="results">HTMX PATCH request fragment - ID: '.$post_id.'</p>';
    });

    add_action('atomic/delete/test-hook', function (int $post_id) {
        echo '<p id="results">HTMX DELETE request fragment - ID: '.$post_id.'</p>';
    });
}
