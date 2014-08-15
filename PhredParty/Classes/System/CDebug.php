<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that is in charge of debugging.
 *
 * The CDebug class manages the semantic checks, which are used in the form of assertions, and processes errors that
 * are risen by assertions, if they are enabled, as well as errors that are encountered by the PHP runtime on its own,
 * such as a call to an undefined method or function (because of a typo), a missing argument for a method or function,
 * the use of an undefined variable or object property, and others. The class can process an error by logging the error
 * message into a log file and/or by sending a mail message about the error to the administrator. Assertions can be
 * enabled or disabled in the "Debug" configuration file or with `setAssertions...` methods of this class. By default,
 * logging and mailing are turned off.
 *
 * Assertions fall into two categories: level 1, which are known as "regular", and level 2, which are known as "heavy".
 * The level 2 assertions are in a minority compared to the level 1 ones and can only be active if the level 1
 * assertions are enabled, while the level 1 assertions are independent of the level 2 ones.
 *
 * **Defaults:**
 *
 * * The maximum number of latest records to keep in the error log is 1024.
 * * The maximum number of latest records to copy from the error log (if logging is enabled) into a mail message is 16.
 * * The maximum size of a displayed variable dump in an error message is 64 KB.
 * * Treat any failed assertion being as a fatal error that causes exiting the script is `false`.
 */

// Method signatures:
//   static bool isDebugModeOn ()
//   static bool assertionsLevel1 ()
//   static void setAssertionsLevel1 ($bEnable)
//   static bool assertionsLevel2 ()
//   static bool aL2 ()
//   static void setAssertionsLevel2 ($bEnable)
//   static void setLogging ($sErrorLogFp)
//   static void setMailing (CMail $oMail,
//     $iMinTimeBetweenSendMailHours = self::DEFAULT_MIN_TIME_BETWEEN_SEND_MAIL_HOURS)

/**
 * @ignore
 */

class CDebug extends CRootClass
{
    const DEFAULT_MIN_TIME_BETWEEN_SEND_MAIL_HOURS = 3;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function isDebugModeOn ()
    {
        return self::assertionsLevel1();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function assertionsLevel1 ()
    {
        // Checks whether the level 1 assertions are enabled.
        return ( assert_options(ASSERT_ACTIVE) === 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function setAssertionsLevel1 ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );

        assert_options(ASSERT_ACTIVE, $bEnable);
        if ( self::$ms_bAnyFailedAssertionIsFatal && $bEnable )
        {
            assert_options(ASSERT_BAIL, true);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function assertionsLevel2 ()
    {
        // Checks whether the level 2 assertions are enabled.
        return ( self::$ms_bAssertionsLevel2 && self::assertionsLevel1() );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function aL2 ()
    {
        // An alias for `assertionsLevel2`.
        return self::assertionsLevel2();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function setAssertionsLevel2 ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );

        if ( $bEnable )
        {
            self::setAssertionsLevel1(true);
        }
        self::$ms_bAssertionsLevel2 = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function setLogging ($sErrorLogFp)
    {
        // Enables the logging of error messages into the file located at the specified path.

        assert( 'is_cstring($sErrorLogFp)', vs(isset($this), get_defined_vars()) );

        self::$ms_bLogging = true;
        self::$ms_sErrorLogFp = $sErrorLogFp;

        self::registerHandlers();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function setMailing (CMail $oMail,
        $iMinTimeBetweenSendMailHours = self::DEFAULT_MIN_TIME_BETWEEN_SEND_MAIL_HOURS)
    {
        // Enables mailing about encountered errors with the provided CMail object.

        assert( 'is_int($iMinTimeBetweenSendMailHours)', vs(isset($this), get_defined_vars()) );
        assert( '$iMinTimeBetweenSendMailHours >= 0', vs(isset($this), get_defined_vars()) );

        self::$ms_bMailing = true;
        self::$ms_oMail = $oMail;
        self::$ms_iMinTimeBetweenSendMailHours = $iMinTimeBetweenSendMailHours;

        self::registerHandlers();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function definedVarsToString ($mVars)
    {
        if ( empty($mVars) )
        {
            return "";
        }

        // Convert the associative array to string.
        $sVars = var_export($mVars, true);

        // Do some cleanup.
        $sVars = preg_replace("/^\\s*\\w+\\s*\\(\\s*/", "\n  ", $sVars);
        $sVars = preg_replace("/\\s*,?\\s*\\)\\s*\\z/", "", $sVars);
        $sVars = preg_replace("/\\n(?! {2}\\S)\\h*/", " ", $sVars);

        // See if the resulting string is not too big.
        if ( strlen($sVars) > self::$ms_iDebugVarStringMaxLength )
        {
            // The string is exceeding the limit, so shorten it to the maximum allowed length.
            $sVars = substr($sVars, 0, self::$ms_iDebugVarStringMaxLength - 3);
            $sVars .= "...";
        }

        // Put a marker indicating it is a variable dump.
        $sVars .= self::$ms_sAssertVarsMarker;

        return $sVars;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function errorHandler ($eErrorLevel, $sErrorDesc, $sFilePath, $iLine)
    {
        // If any error processing is enabled, this function is called by the PHP runtime on a failed assertion or
        // runtime error.

        settype($eErrorLevel, "int");
        settype($iLine, "int");

        $bReportThisError = true;
        if ( CConfiguration::isInitialized() )
        {
            $eErrorReportingLevel = constant(CConfiguration::option("debug.errorReportingLevel"));
            if ( $eErrorLevel & $eErrorReportingLevel == 0 )
            {
                $bReportThisError = false;
            }
        }
        if ( $bReportThisError )
        {
            // Avoid double reporting of the same error if the method registered with `register_shutdown_function` is
            // called by the runtime after an error occurred.
            $mErrorFileAndLine = [];
            $mErrorFileAndLine["file"] = $sFilePath;
            $mErrorFileAndLine["line"] = $iLine;
            self::$ms_mProcessedErrors[] = $mErrorFileAndLine;

            $sTime = gmdate(self::$ms_sLogRecordDateTimePattern, time());

            $sFileRelPath = str_replace(realpath($GLOBALS["PHRED_PATH_TO_FRAMEWORK_ROOT"]) . "/", "", $sFilePath);

            $bIsAssertWithVars = ( is_int(strpos($sErrorDesc, self::$ms_sAssertVarsMarker)) );

            $sErrorDesc = preg_replace("/\\n{2,}/", "\n", $sErrorDesc);
            if ( !$bIsAssertWithVars )
            {
                // In case of an assert without vars.
                $sErrorDesc = preg_replace("/^assert\\(\\)\\s*:\\s*/", "", $sErrorDesc);
            }
            else
            {
                $sErrorDesc = preg_replace(
                    "/^[^\\n]*\\n(.*)" . self::$ms_sAssertVarsMarker . "\\s*:\\s*([\"'][^\\n]*)\\s*\\z/s",
                    "$2 ($sErrorLevel)\n$1", $sErrorDesc);
                if ( preg_match("/^\\s*assert/i", $sErrorDesc) !== 1 )
                {
                    $sErrorDesc = "Assertion $sErrorDesc";
                }
            }

            $sErrorLevel;
            switch ( $eErrorLevel )
            {
            case E_ERROR:  // 1
                $sErrorLevel = "E_ERROR";
                break;
            case E_WARNING:  // 2
                $sErrorLevel = "E_WARNING";
                break;
            case E_PARSE:  // 4
                $sErrorLevel = "E_PARSE";
                break;
            case E_NOTICE:  // 8
                $sErrorLevel = "E_NOTICE";
                break;
            case E_CORE_ERROR:  // 16
                $sErrorLevel = "E_CORE_ERROR";
                break;
            case E_CORE_WARNING:  // 32
                $sErrorLevel = "E_CORE_WARNING";
                break;
            case E_COMPILE_ERROR:  // 64
                $sErrorLevel = "E_COMPILE_ERROR";
                break;
            case E_COMPILE_WARNING:  // 128
                $sErrorLevel = "E_COMPILE_WARNING";
                break;
            case E_USER_ERROR:  // 256
                $sErrorLevel = "E_USER_ERROR";
                break;
            case E_USER_WARNING:  // 512
                $sErrorLevel = "E_USER_WARNING";
                break;
            case E_USER_NOTICE:  // 1024
                $sErrorLevel = "E_USER_NOTICE";
                break;
            case E_STRICT:  // 2048
                $sErrorLevel = "E_STRICT";
                break;
            case E_RECOVERABLE_ERROR:  // 4096
                $sErrorLevel = "E_RECOVERABLE_ERROR";
                break;
            case E_DEPRECATED:  // 8192
                $sErrorLevel = "E_DEPRECATED";
                break;
            case E_USER_DEPRECATED:  // 16384
                $sErrorLevel = "E_USER_DEPRECATED";
                break;
            default:
                $sErrorLevel = (string)$eErrorLevel;
                break;
            }

            // Backtrace.
            ob_start();
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $sBacktrace = ob_get_clean();
            $sBacktrace = preg_replace("/\\n{2,}/", "\n", $sBacktrace);
            $sBacktrace = preg_replace("/^\\s*#.*?(?=#)/s", "", $sBacktrace);  // remove the first line that is useless
            $sBacktrace = str_replace(realpath($GLOBALS["PHRED_PATH_TO_FRAMEWORK_ROOT"]) . "/", "", $sBacktrace);
            $sBacktrace = preg_replace("/\\s+\\z/", "", $sBacktrace);

            // Format the error message.
            if ( !$bIsAssertWithVars )
            {
                $sErrorMessage =
                    "[Time:] $sTime\n\n[Location:] $sFileRelPath, line $iLine\n\n$sErrorDesc ($sErrorLevel)\n\n" .
                    "[Backtrace:]\n$sBacktrace\n";
            }
            else
            {
                $sErrorMessage =
                    "[Time:] $sTime\n\n[Location:] $sFileRelPath, line $iLine\n\n$sErrorDesc\n\n" .
                    "[Backtrace:]\n$sBacktrace\n";
            }

            $sLogRecordSep = rtrim(str_repeat("- ", 60));
            $sLeSep = "\n\n$sLogRecordSep\n\n";

            if ( self::$ms_bLogging )
            {
                // Log the record.
                $sLogRecord = "$sErrorMessage\n$sLogRecordSep\n\n";
                if ( file_exists(self::$ms_sErrorLogFp) )
                {
                    // Check if log rotation needs to be performed for the error log.
                    $sLog = file_get_contents(self::$ms_sErrorLogFp);
                    $iNumRecords = substr_count($sLog, $sLeSep);
                    if ( $iNumRecords >= self::$ms_iMaxNumRecordsInLog )
                    {
                        $iNumDelFirstRecords = $iNumRecords - self::$ms_iMaxNumRecordsInLog + 1;
                        $iPos;
                        for ($i = 0; $i < $iNumDelFirstRecords; $i++)
                        {
                            $iPos = ( !isset($iPos) ) ? 0 : $iPos + 1;
                            $iPos = strpos($sLog, $sLeSep, $iPos);
                        }
                        $sLog = substr($sLog, $iPos);
                        $sLog = "\n$sLogRecordSep\n\n" . preg_replace("/^$sLeSep/", "", $sLog);

                        file_put_contents(self::$ms_sErrorLogFp, $sLog);
                    }
                }
                else
                {
                    file_put_contents(self::$ms_sErrorLogFp, "\n$sLogRecordSep\n\n");
                }
                file_put_contents(self::$ms_sErrorLogFp, $sLogRecord, FILE_APPEND);
            }

            if ( self::$ms_bMailing )
            {
                $bDoMail = true;

                $sDestDp = sys_get_temp_dir();
                $sDocRoot = ( isset($_SERVER["DOCUMENT_ROOT"]) ) ? $_SERVER["DOCUMENT_ROOT"] : "";
                $sDocRootHash = hash("crc32", $sDocRoot, false);
                $sLastMailTimeFp = $sDestDp . "/" . self::$ms_sLastMailingTimeFnPrefix . $sDocRootHash;

                if ( file_exists($sLastMailTimeFp) )
                {
                    $iLastMailTime = (int)file_get_contents($sLastMailTimeFp);
                    $iLastMailTimeDiffSeconds = time() - $iLastMailTime;

                    // If the difference is negative, which is wrong, the mailing should not be canceled.
                    if ( $iLastMailTimeDiffSeconds >= 0 )
                    {
                        if ( $iLastMailTimeDiffSeconds < self::$ms_iMinTimeBetweenSendMailHours*3600 )
                        {
                            // Too little time has passed since the last mailing, so cancel this one.
                            $bDoMail = false;
                        }
                    }
                }

                if ( $bDoMail )
                {
                    // Notify about the error by mail.

                    // Format the subject.
                    $sSubject = "Something curious was encountered on ";  // alternative: ""
                    $sServerName = ( isset($_SERVER["SERVER_NAME"]) ) ? $_SERVER["SERVER_NAME"] : "";
                    $sServerIp = ( isset($_SERVER["SERVER_ADDR"]) ) ? $_SERVER["SERVER_ADDR"] : "";
                    $sDocRoot = ( isset($_SERVER["DOCUMENT_ROOT"]) ) ? basename($_SERVER["DOCUMENT_ROOT"]) : "";
                    if ( $sServerName !== "" )
                    {
                        $sSubject .= $sServerName;
                        if ( $sServerIp !== "" )
                        {
                            $sSubject .= " ($sServerIp)";
                        }
                        if ( $sDocRoot !== "" )
                        {
                            $sSubject .= " in ";
                        }
                        else
                        {
                            $sSubject .= "";  // alternative: ": "
                        }
                    }
                    if ( $sDocRoot !== "" )
                    {
                        $sSubject .= "'$sDocRoot'";  // alternative: "'$sDocRoot': "
                    }
                    // Alternative:
                    // $sSubject .= "PHP Encountered a Problem";

                    // Compose the message.
                    $sMailMessage = "$sSubject\n\n$sErrorMessage";
                    if ( self::$ms_bLogging && file_exists(self::$ms_sErrorLogFp) )
                    {
                        // Add the latest log records to the message.
                        $sLog = file_get_contents(self::$ms_sErrorLogFp);
                        $sLog .= "\n";  // to be able to use `-1` in `strrpos` below
                        $iLogLength = strlen($sLog);
                        $xRes = strrpos($sLog, $sLeSep, -1);
                        if ( is_int($xRes) )
                        {
                            for ($i = 0; $i < self::$ms_iMaxNumLatestLogRecordsInMailMessage; $i++)
                            {
                                $xRes = strrpos($sLog, $sLeSep, $xRes - $iLogLength - 1);
                                if ( !is_int($xRes) )
                                {
                                    break;
                                }
                            }
                            if ( !is_int($xRes) )
                            {
                                $xRes = 0;
                            }
                            $sLatestRecords = substr($sLog, $xRes);
                            $sLatestRecords = trim($sLatestRecords);

                            $sMailMessage .=
                                "\n\n\n\n[Latest error log records, chronologically:]\n\n$sLatestRecords\n";
                        }
                    }

                    // Send.
                    self::$ms_oMail->setSubject($sSubject);
                    self::$ms_oMail->setBody($sMailMessage);
                    self::$ms_oMail->disableWordWrapping();
                    $iNumMailsSent = self::$ms_oMail->send();

                    if ( $iNumMailsSent > 0 )
                    {
                        file_put_contents($sLastMailTimeFp, time());
                    }
                }
            }
        }

        // If a value other than `false` was returned, the error would not be shown in the output, regardless of
        // whether or not the PHP's "display_errors" option was enabled or disabled.
        return false;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function fatalErrorHandler()
    {
        $mError = error_get_last();

        // Avoid double reporting of the same error if this method (registered with `register_shutdown_function`) is
        // called by the runtime after an error occurred.
        $mErrorFileAndLine = [];
        $mErrorFileAndLine["file"] = $mError["file"];
        $mErrorFileAndLine["line"] = $mError["line"];
        if ( in_array($mErrorFileAndLine, self::$ms_mProcessedErrors) )
        {
            return;
        }

        if ( isset($mError) )
        {
            $sFile = $mError["file"];
            $iLine = (int)$mError["line"];
            $sErrorDesc = $mError["message"];
            $eErrorLevel = (int)$mError["type"];

            self::errorHandler($eErrorLevel, $sErrorDesc, $sFile, $iLine);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function registerHandlers ()
    {
        static $s_bAlreadyRegistered = false;
        if ( $s_bAlreadyRegistered )
        {
            return;
        }

        $sClassName = get_called_class();
        set_error_handler("$sClassName::errorHandler");
        register_shutdown_function("$sClassName::fatalErrorHandler");

        $s_bAlreadyRegistered = true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // Properties and defaults.
    protected static $ms_bAssertionsLevel2 = false;
    protected static $ms_bLogging = false;
    protected static $ms_sErrorLogFp;
    protected static $ms_bMailing = false;
    protected static $ms_oMail;
    protected static $ms_iMinTimeBetweenSendMailHours;
    protected static $ms_iMaxNumRecordsInLog = 1024;
    protected static $ms_sLogRecordDateTimePattern = "Y-m-d H:i:s e";
    protected static $ms_iMaxNumLatestLogRecordsInMailMessage = 16;
    protected static $ms_iDebugVarStringMaxLength = 65536;  // 64 KB
    protected static $ms_bAnyFailedAssertionIsFatal = false;
    protected static $ms_sLastMailingTimeFnPrefix = "tmp-phred-on-error-last-mailing-time-for-docroot-";
    protected static $ms_sAssertVarsMarker = "\t \t\t \t \t\t \t \n";
    protected static $ms_mProcessedErrors = [];
}
