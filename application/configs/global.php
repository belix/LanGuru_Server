<?php

define('APPLICATION_ENV_DEVELOP', 'development');
define('APPLICATION_ENV_PRODUCTION', 'production');

define('APPLICATION_ENV', APPLICATION_ENV_PRODUCTION);

define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/..'));
define('APPLICATION_ROOT', realpath(dirname(__FILE__) . '/../..'));

set_include_path(get_include_path() . PATH_SEPARATOR . APPLICATION_ROOT . '/library' . PATH_SEPARATOR . APPLICATION_ROOT . PATH_SEPARATOR . APPLICATION_PATH);