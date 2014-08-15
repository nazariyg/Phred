<?php

require __DIR__ . "/Bootstrap/Phred.php";

echo passthru(CFilePath::frameworkPath("cd {{PHRED_PATH_TO_FRAMEWORK_ROOT}}; php ThirdParty/bin/phpunit --colors"));
