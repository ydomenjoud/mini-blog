<?php

require __DIR__ . '/vendor/autoload.php';

# configuration
define('TEMPLATE_DIR', 'template/miniblog');
define('TEMPLATE_LAYOUT_POST', 'post.pug');

define('CACHE_DIR', 'cache');
define('CACHE_EXTENSION', '.html');

define('CONTENT_DIR', 'content');
define('CONTENT_EXTENSION', '.md');


# start page
$blog = new \MiniBlog\Blog();


