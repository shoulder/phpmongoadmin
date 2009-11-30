<?php

require dirname(__FILE__) . '/lib/mongoadmin.php';
MongoAdmin::loadConfig(dirname(__FILE__) . '/config.php');
MongoAdmin::dispatching();

