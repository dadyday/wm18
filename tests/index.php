<?php
require_once __DIR__.'/../vendor/autoload.php';

Tracy\Debugger::enable();
Tracy\Debugger::$maxDepth = 8;
Tracy\Debugger::$maxLength = 200;

include 'services.Test.php';
#include 'staticBug.Test.php';
