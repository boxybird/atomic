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

    $builder = new BoxyBird\Atomic\HookBuilder;
    $builder
        ->action('atomic/get/test-builder-hook', function (int $post_id) {
            echo '<p id="results">HTMX GET request builder fragment - ID: '.$post_id.'</p>';
        })
        ->action('atomic/post/test-builder-hook', function (int $post_id) {
            echo '<p id="results">HTMX POST request builder fragment - ID: '.$post_id.'</p>';
        })
        ->action('atomic/put/test-builder-hook', function (int $post_id) {
            echo '<p id="results">HTMX PUT request builder fragment - ID: '.$post_id.'</p>';
        })
        ->action('atomic/patch/test-builder-hook', function (int $post_id) {
            echo '<p id="results">HTMX PATCH request builder fragment - ID: '.$post_id.'</p>';
        })
        ->action('atomic/delete/test-builder-hook', function (int $post_id) {
            echo '<p id="results">HTMX DELETE request builder fragment - ID: '.$post_id.'</p>';
        })
        ->when(function (array $hooks_used) {
            return true;
        });
}
