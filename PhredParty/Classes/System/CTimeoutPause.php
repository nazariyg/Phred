<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * This class helps in putting the PHP's built-in timeout on hold when needed.
 *
 * Constructing an object of this class disables the script's execution timeout to allow for time consuming operations,
 * such as HTTP requests, downloads etc., and ending the "pause" restores the execution time limit by setting it to the
 * value that, calculated from the previous value and the amount of time passed before the "pause" was made, would be
 * equivalent to the operation(s) not having taken any time.
 */

// Method signatures:
//   __construct ()
//   void end ()

/**
 * @ignore
 */

class CTimeoutPause extends CRootClass
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function __construct ()
    {
        // Remember the script's current execution time limit and the amount of time that has elapsed since the script
        // started.
        $this->m_iExeTimeLimitBeforePause = CSystem::executionTimeLimit();
        $this->m_iSecondsElaplsedBeforePause = CTime::now()->diffInSeconds(CSystem::startTime());

        // Remove the existing execution time limit.
        CSystem::removeExecutionTimeLimit();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function end ()
    {
        // Set the script's execution time limit to the previous value but without the time that elapsed before the
        // "pause" was made (simply setting it to the previous value would reset the timer).
        $iNewExecutionTimeLimit = $this->m_iExeTimeLimitBeforePause - $this->m_iSecondsElaplsedBeforePause;
        if ( $iNewExecutionTimeLimit <= 0 )  // avoid the special value of zero
        {
            $iNewExecutionTimeLimit = 1;
        }
        CSystem::setExecutionTimeLimit($iNewExecutionTimeLimit);

        $this->m_bDone = true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function __destruct ()
    {
        if ( !$this->m_bDone )
        {
            // A timeout pause should explicitly be ended with `end` method, but have to do it manually now.
            $this->end();
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_iExeTimeLimitBeforePause;
    protected $m_iSecondsElaplsedBeforePause;
    protected $m_bDone = false;
}
