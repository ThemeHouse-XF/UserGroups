<?php
$rootDir = XenForo_Autoloader::getInstance()->getRootDir();

$version = 0;
if ($handle = opendir($rootDir . '/ThemeHouse/Listener/FrontControllerPostView')) {
    while (false !== ($entry = readdir($handle))) {
        if (intval($entry) > $version)
            $version = intval($entry);
    }
}

require_once $rootDir . '/ThemeHouse/Listener/FrontControllerPostView/' . $version . '.php';