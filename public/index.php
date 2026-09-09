<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/database.php';
$container = require_once dirname(__DIR__) . '/config/container.php';

require_once dirname(__DIR__) . '/routes/index.php';