<?php

use Post\Forwarder\Config;

require_once __DIR__ . "/autoload.php";
include 'app/facebookForwarder.php';

$fbf = new FacebookForwarder();
$fbf->getGroupFeed(Config::PACIFIC_NW_2017_ID);
