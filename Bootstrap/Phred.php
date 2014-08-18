<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt


/*

   OOOOOOOOOOOOOOOOOO      OOOOOOO     OOOOOOO   OOOOOOOOOOOOOOOOOO      @@@@@@@@@@@@@@@@@@@   @@@@@@@@@@@@@@@@
   OOOOOOOOOOOOOOOOOOOO    OOOOOOO     OOOOOOO   OOOOOOOOOOOOOOOOOOOO    @@@@@@@@@@@@@@@@@@@   @@@@@@@@@@@@@@@@@@
   OOOOOOOOOOOOOOOOOOOOO   OOOOOOO     OOOOOOO   OOOOOOOOOOOOOOOOOOOOO   @@@@@@@@@@@@@@@@@@@   @@@@@@@@@@@@@@@@@@@@
   OOOOOOO      OOOOOOOO   OOOOOOO     OOOOOOO   OOOOOOO      OOOOOOOO   @@@@@@@               @@@@@@      @@@@@@@@@
   OOOOOOO       OOOOOOO   OOOOOOO     OOOOOOO   OOOOOOO       OOOOOOO   @@@@@@@               @@@@@@        @@@@@@@
   OOOOOOO       OOOOOOO   OOOOOOOOOOOOOOOOOOO   OOOOOOO       OOOOOOO   @@@@@@@@@@@@@@@@@@    @@@@@@         @@@@@@@
   OOOOOOO      OOOOOOOO   OOOOOOOOOOOOOOOOOOO   OOOOOOO      OOOOOOOO   @@@@@@@@@@@@@@@@@@    @@@@@@         @@@@@@@
   OOOOOOOOOOOOOOOOOOOO    OOOOOOOOOOOOOOOOOOO   OOOOOOOOOOOOOOOOOOOO    @@@@@@@@@@@@@@@@@@    @@@@@@         @@@@@@@
   OOOOOOOOOOOOOOOOOO      OOOOOOOOOOOOOOOOOOO   OOOOOOOOOOOOOOOOOO      @@@@@@@@@@@@@@@@@@    @@@@@@         @@@@@@@
   OOOOOOOOOOOOOOO         OOOOOOO     OOOOOOO   OOOOOOOOOOOOOOOO        @@@@@@@               @@@@@@        @@@@@@@
   OOOOOOO                 OOOOOOO     OOOOOOO   OOOOOOO                 @@@@@@@               @@@@@@      @@@@@@@@@
   OOOOOOO                 OOOOOOO     OOOOOOO   OOOOOOO    @@@@@@@@     @@@@@@@@@@@@@@@@@@@   @@@@@@@@@@@@@@@@@@@@
   OOOOOOO                 OOOOOOO     OOOOOOO   OOOOOOO     @@@@@@@@    @@@@@@@@@@@@@@@@@@@   @@@@@@@@@@@@@@@@@@
   OOOOOOO                 OOOOOOO     OOOOOOO   OOOOOOO      @@@@@@@@   @@@@@@@@@@@@@@@@@@@   @@@@@@@@@@@@@@@@

*/


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Oh that memorable second when the request started being processed.

$GLOBALS["PHRED_START_UTIME"] = time();

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Define `$GLOBALS["PHRED_TESTS"]` if it hasn't been already defined by the unit testing module.

if ( !isset($GLOBALS["PHRED_TESTS"]) )
{
    $GLOBALS["PHRED_TESTS"] = false;
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Import information about the filesystem paths that are essential to the framework.

require __DIR__ . "/Paths.php";

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Import the third-party components via Composer.

require $GLOBALS["PHRED_PATH_TO_THIRD_PARTY"] . "/autoload.php";

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// The function to report whether the OOP mode is on or off, in an optimized way.

function is_oop_on ($trace = false)
{
    static $s_oopIsEnabled;
    if ( isset($s_oopIsEnabled) )
    {
        // Return the cached value.
        return $s_oopIsEnabled;
    }
    if ( CConfiguration::isInitialized() )
    {
        $s_oopIsEnabled = CConfiguration::appOption("enableOop");
        return $s_oopIsEnabled;
    }
    else
    {
        return false;
    }
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// A function used in the OOP wrapping of third-party components.

function _is_non_tp_call ()
{
    static $s_thirdPartyAbsDp;
    if ( !isset($s_thirdPartyAbsDp) )
    {
        $s_thirdPartyAbsDp = realpath($GLOBALS["PHRED_PATH_TO_THIRD_PARTY"]);
    }
    $backTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    if ( count($backTrace) < 2 )
    {
        return true;
    }
    return ( strpos($backTrace[1]["file"], $s_thirdPartyAbsDp) !== 0 );
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// If xdebug is used, tell it that the recursion depth of 100 is not always enough.

ini_set("xdebug.max_nesting_level", 4096);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Ready to initialize the framework.

CSystem::initializeFramework();

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Register the aliases for the core classes.

$GLOBALS["PHRED_CLASS_ALIASES"] = CConfiguration::option("classaliases");
foreach ($GLOBALS["PHRED_CLASS_ALIASES"] as $className => $alias)
{
    class_alias($className, $alias, true);
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Import OOP methods for the string type.

if ( function_exists("register_primitive_type_handler") )
{
    register_primitive_type_handler("string", "CUStringObject");
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
