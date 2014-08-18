<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function vs ($dummy, $vars)
{
    // This internal function is used by assertions to put debugging information about the variables that are defined
    // at the point of a failing assertion and the object's properties at that point into a string to subsequently log
    // it into a file, send it by mail to the administrator etc.

    if ( assert_options(ASSERT_ACTIVE) !== 1 )
    {
        // Assertions are disabled, so nothing to do.
        return;
    }

    return CDebug::definedVarsToString($vars);
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
