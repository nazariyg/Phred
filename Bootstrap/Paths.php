<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// The path to the framework's root directory. By default, it is used to compose most of the subsequent paths.

$GLOBALS["PHRED_PATH_TO_FRAMEWORK_ROOT"] = __DIR__ . "/..";

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// The path to the Application directory.

$GLOBALS["PHRED_PATH_TO_APP"] = $GLOBALS["PHRED_PATH_TO_FRAMEWORK_ROOT"] . "/Application";

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// The path to the server's root directory where those files are usually located which are to be accessed by the HTTP
// server directly, without PHP in the middle.

$GLOBALS["PHRED_PATH_TO_SERVER_ROOT"] = $GLOBALS["PHRED_PATH_TO_APP"] . "/ServerRoot";

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// The path to the directory where any files or directories generated by the framework are to be located.

$GLOBALS["PHRED_PATH_TO_STORAGE"] = $GLOBALS["PHRED_PATH_TO_APP"] . "/Storage";

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// The path to the framework's source code.

$GLOBALS["PHRED_PATH_TO_SOURCE"] = $GLOBALS["PHRED_PATH_TO_FRAMEWORK_ROOT"] . "/PhredParty";

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// The path to the third-party code that is used by the framework.

$GLOBALS["PHRED_PATH_TO_THIRD_PARTY"] = $GLOBALS["PHRED_PATH_TO_FRAMEWORK_ROOT"] . "/ThirdParty";

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
