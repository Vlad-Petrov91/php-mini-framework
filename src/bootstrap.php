<?php

declare(strict_types=1);

$page = $_GET['page'] ?? 'home';

include dirname(__DIR__) . "/{$page}.php";
