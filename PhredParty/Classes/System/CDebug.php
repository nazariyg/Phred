<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
//   static void setAssertionsLevel1 ($enable)
//   static bool assertionsLevel2 ()
//   static bool aL2 ()
//   static void setAssertionsLevel2 ($enable)
//   static void setLogging ($errorLogFp)
//   static void setMailing (CMail $mail,
//     $minTimeBetweenSendMailHours = self::DEFAULT_MIN_TIME_BETWEEN_SEND_MAIL_HOURS)

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
    public static function setAssertionsLevel1 ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );

        assert_options(ASSERT_ACTIVE, $enable);
        if ( self::$ms_anyFailedAssertionIsFatal && $enable )
        {
            assert_options(ASSERT_BAIL, true);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function assertionsLevel2 ()
    {
        // Checks whether the level 2 assertions are enabled.
        return ( self::$ms_assertionsLevel2 && self::assertionsLevel1() );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function aL2 ()
    {
        // An alias for `assertionsLevel2`.
        return self::assertionsLevel2();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function setAssertionsLevel2 ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );

        if ( $enable )
        {
            self::setAssertionsLevel1(true);
        }
        self::$ms_assertionsLevel2 = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function setLogging ($errorLogFp)
    {
        // Enables the logging of error messages into the file located at the specified path.

        assert( 'is_cstring($errorLogFp)', vs(isset($this), get_defined_vars()) );

        self::$ms_logging = true;
        self::$ms_errorLogFp = $errorLogFp;

        self::registerHandlers();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function setMailing (CMail $mail,
        $minTimeBetweenSendMailHours = self::DEFAULT_MIN_TIME_BETWEEN_SEND_MAIL_HOURS)
    {
        // Enables mailing about encountered errors with the provided CMail object.

        assert( 'is_int($minTimeBetweenSendMailHours)', vs(isset($this), get_defined_vars()) );
        assert( '$minTimeBetweenSendMailHours >= 0', vs(isset($this), get_defined_vars()) );

        self::$ms_mailing = true;
        self::$ms_mail = $mail;
        self::$ms_minTimeBetweenSendMailHours = $minTimeBetweenSendMailHours;

        self::registerHandlers();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function definedVarsToString ($vars)
    {
        if ( empty($vars) )
        {
            return "";
        }

        // Convert the associative array to string.
        $strVars = var_export($vars, true);

        // Do some cleanup.
        $strVars = preg_replace("/^\\s*\\w+\\s*\\(\\s*/", "\n  ", $strVars);
        $strVars = preg_replace("/\\s*,?\\s*\\)\\s*\\z/", "", $strVars);
        $strVars = preg_replace("/\\n(?! {2}\\S)\\h*/", " ", $strVars);

        // See if the resulting string is not too big.
        if ( strlen($strVars) > self::$ms_debugVarStringMaxLength )
        {
            // The string is exceeding the limit, so shorten it to the maximum allowed length.
            $strVars = substr($strVars, 0, self::$ms_debugVarStringMaxLength - 3);
            $strVars .= "...";
        }

        // Put a marker indicating it is a variable dump.
        $strVars .= self::$ms_assertVarsMarker;

        return $strVars;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public static function errorHandler ($errorLevel, $errorDesc, $filePath, $line)
    {
        // If any error processing is enabled, this function is called by the PHP runtime on a failed assertion or
        // runtime error.

        settype($errorLevel, "int");
        settype($line, "int");

        $reportThisError = true;
        if ( CConfiguration::isInitialized() )
        {
            $errorReportingLevel = constant(CConfiguration::option("debug.errorReportingLevel"));
            if ( $errorLevel & $errorReportingLevel == 0 )
            {
                $reportThisError = false;
            }
        }
        if ( $reportThisError )
        {
            // Avoid double reporting of the same error if the method registered with `register_shutdown_function` is
            // called by the runtime after an error occurred.
            $errorFileAndLine = [];
            $errorFileAndLine["file"] = $filePath;
            $errorFileAndLine["line"] = $line;
            self::$ms_processedErrors[] = $errorFileAndLine;

            $strErrorLevel;
            switch ( $errorLevel )
            {
            case E_ERROR:  // 1
                $strErrorLevel = "E_ERROR";
                break;
            case E_WARNING:  // 2
                $strErrorLevel = "E_WARNING";
                break;
            case E_PARSE:  // 4
                $strErrorLevel = "E_PARSE";
                break;
            case E_NOTICE:  // 8
                $strErrorLevel = "E_NOTICE";
                break;
            case E_CORE_ERROR:  // 16
                $strErrorLevel = "E_CORE_ERROR";
                break;
            case E_CORE_WARNING:  // 32
                $strErrorLevel = "E_CORE_WARNING";
                break;
            case E_COMPILE_ERROR:  // 64
                $strErrorLevel = "E_COMPILE_ERROR";
                break;
            case E_COMPILE_WARNING:  // 128
                $strErrorLevel = "E_COMPILE_WARNING";
                break;
            case E_USER_ERROR:  // 256
                $strErrorLevel = "E_USER_ERROR";
                break;
            case E_USER_WARNING:  // 512
                $strErrorLevel = "E_USER_WARNING";
                break;
            case E_USER_NOTICE:  // 1024
                $strErrorLevel = "E_USER_NOTICE";
                break;
            case E_STRICT:  // 2048
                $strErrorLevel = "E_STRICT";
                break;
            case E_RECOVERABLE_ERROR:  // 4096
                $strErrorLevel = "E_RECOVERABLE_ERROR";
                break;
            case E_DEPRECATED:  // 8192
                $strErrorLevel = "E_DEPRECATED";
                break;
            case E_USER_DEPRECATED:  // 16384
                $strErrorLevel = "E_USER_DEPRECATED";
                break;
            default:
                $strErrorLevel = (string)$errorLevel;
                break;
            }

            $time = gmdate(self::$ms_logRecordDateTimePattern, time());

            $fileRelPath = str_replace(realpath($GLOBALS["PHRED_PATH_TO_FRAMEWORK_ROOT"]) . "/", "", $filePath);

            $isAssertWithVars = ( is_int(strpos($errorDesc, self::$ms_assertVarsMarker)) );

            $errorDesc = preg_replace("/\\n{2,}/", "\n", $errorDesc);
            if ( !$isAssertWithVars )
            {
                // In case of an assert without vars.
                $errorDesc = preg_replace("/^assert\\(\\)\\s*:\\s*/", "", $errorDesc);
            }
            else
            {
                $errorDesc = preg_replace(
                    "/^[^\\n]*\\n(.*)" . self::$ms_assertVarsMarker . "\\s*:\\s*([\"'][^\\n]*)\\s*\\z/s",
                    "$2 ($strErrorLevel)\n$1", $errorDesc);
                if ( preg_match("/^\\s*assert/i", $errorDesc) !== 1 )
                {
                    $errorDesc = "Assertion $errorDesc";
                }
            }

            // Backtrace.
            ob_start();
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $backtrace = ob_get_clean();
            $backtrace = preg_replace("/\\n{2,}/", "\n", $backtrace);
            $backtrace = preg_replace("/^\\s*#.*?(?=#)/s", "", $backtrace);  // remove the first line that is useless
            $backtrace = str_replace(realpath($GLOBALS["PHRED_PATH_TO_FRAMEWORK_ROOT"]) . "/", "", $backtrace);
            $backtrace = preg_replace("/\\s+\\z/", "", $backtrace);

            // Format the error message.
            if ( !$isAssertWithVars )
            {
                $errorMessage =
                    "[Time:] $time\n\n[Location:] $fileRelPath, line $line\n\n$errorDesc ($strErrorLevel)\n\n" .
                    "[Backtrace:]\n$backtrace\n";
            }
            else
            {
                $errorMessage =
                    "[Time:] $time\n\n[Location:] $fileRelPath, line $line\n\n$errorDesc\n\n" .
                    "[Backtrace:]\n$backtrace\n";
            }

            $logRecordSep = rtrim(str_repeat("- ", 60));
            $leSep = "\n\n$logRecordSep\n\n";

            if ( self::$ms_logging )
            {
                // Log the record.
                $logRecord = "$errorMessage\n$logRecordSep\n\n";
                if ( file_exists(self::$ms_errorLogFp) )
                {
                    // Check if log rotation needs to be performed for the error log.
                    $log = file_get_contents(self::$ms_errorLogFp);
                    $numRecords = substr_count($log, $leSep);
                    if ( $numRecords >= self::$ms_maxNumRecordsInLog )
                    {
                        $numDelFirstRecords = $numRecords - self::$ms_maxNumRecordsInLog + 1;
                        $pos;
                        for ($i = 0; $i < $numDelFirstRecords; $i++)
                        {
                            $pos = ( !isset($pos) ) ? 0 : $pos + 1;
                            $pos = strpos($log, $leSep, $pos);
                        }
                        $log = substr($log, $pos);
                        $log = "\n$logRecordSep\n\n" . preg_replace("/^$leSep/", "", $log);

                        file_put_contents(self::$ms_errorLogFp, $log);
                    }
                }
                else
                {
                    file_put_contents(self::$ms_errorLogFp, "\n$logRecordSep\n\n");
                }
                file_put_contents(self::$ms_errorLogFp, $logRecord, FILE_APPEND);
            }

            if ( self::$ms_mailing )
            {
                $doMail = true;

                $destDp = sys_get_temp_dir();
                $docRoot = ( isset($_SERVER["DOCUMENT_ROOT"]) ) ? $_SERVER["DOCUMENT_ROOT"] : "";
                $docRootHash = hash("crc32", $docRoot, false);
                $lastMailTimeFp = $destDp . "/" . self::$ms_lastMailingTimeFnPrefix . $docRootHash;

                if ( file_exists($lastMailTimeFp) )
                {
                    $lastMailTime = (int)file_get_contents($lastMailTimeFp);
                    $lastMailTimeDiffSeconds = time() - $lastMailTime;

                    // If the difference is negative, which is wrong, the mailing should not be canceled.
                    if ( $lastMailTimeDiffSeconds >= 0 )
                    {
                        if ( $lastMailTimeDiffSeconds < self::$ms_minTimeBetweenSendMailHours*3600 )
                        {
                            // Too little time has passed since the last mailing, so cancel this one.
                            $doMail = false;
                        }
                    }
                }

                if ( $doMail )
                {
                    // Notify about the error by mail.

                    // Format the subject.
                    $subject = "Something curious was encountered on ";  // alternative: ""
                    $serverName = ( isset($_SERVER["SERVER_NAME"]) ) ? $_SERVER["SERVER_NAME"] : "";
                    $serverIp = ( isset($_SERVER["SERVER_ADDR"]) ) ? $_SERVER["SERVER_ADDR"] : "";
                    $docRoot = ( isset($_SERVER["DOCUMENT_ROOT"]) ) ? basename($_SERVER["DOCUMENT_ROOT"]) : "";
                    if ( $serverName !== "" )
                    {
                        $subject .= $serverName;
                        if ( $serverIp !== "" )
                        {
                            $subject .= " ($serverIp)";
                        }
                        if ( $docRoot !== "" )
                        {
                            $subject .= " in ";
                        }
                        else
                        {
                            $subject .= "";  // alternative: ": "
                        }
                    }
                    if ( $docRoot !== "" )
                    {
                        $subject .= "'$docRoot'";  // alternative: "'$docRoot': "
                    }
                    // Alternative:
                    // $subject .= "PHP Encountered a Problem";

                    // Compose the message.
                    $mailMessage = "$subject\n\n$errorMessage";
                    if ( self::$ms_logging && file_exists(self::$ms_errorLogFp) )
                    {
                        // Add the latest log records to the message.
                        $log = file_get_contents(self::$ms_errorLogFp);
                        $log .= "\n";  // to be able to use `-1` in `strrpos` below
                        $logLength = strlen($log);
                        $res = strrpos($log, $leSep, -1);
                        if ( is_int($res) )
                        {
                            for ($i = 0; $i < self::$ms_maxNumLatestLogRecordsInMailMessage; $i++)
                            {
                                $res = strrpos($log, $leSep, $res - $logLength - 1);
                                if ( !is_int($res) )
                                {
                                    break;
                                }
                            }
                            if ( !is_int($res) )
                            {
                                $res = 0;
                            }
                            $latestRecords = substr($log, $res);
                            $latestRecords = trim($latestRecords);

                            $mailMessage .=
                                "\n\n\n\n[Latest error log records, chronologically:]\n\n$latestRecords\n";
                        }
                    }

                    // Send.
                    self::$ms_mail->setSubject($subject);
                    self::$ms_mail->setBody($mailMessage);
                    self::$ms_mail->disableWordWrapping();
                    $numMailsSent = self::$ms_mail->send();

                    if ( $numMailsSent > 0 )
                    {
                        file_put_contents($lastMailTimeFp, time());
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
        $error = error_get_last();

        // Avoid double reporting of the same error if this method (registered with `register_shutdown_function`) is
        // called by the runtime after an error occurred.
        $errorFileAndLine = [];
        $errorFileAndLine["file"] = $error["file"];
        $errorFileAndLine["line"] = $error["line"];
        if ( in_array($errorFileAndLine, self::$ms_processedErrors) )
        {
            return;
        }

        if ( isset($error) )
        {
            $file = $error["file"];
            $line = (int)$error["line"];
            $errorDesc = $error["message"];
            $errorLevel = (int)$error["type"];

            self::errorHandler($errorLevel, $errorDesc, $file, $line);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function registerHandlers ()
    {
        static $s_alreadyRegistered = false;
        if ( $s_alreadyRegistered )
        {
            return;
        }

        $className = get_called_class();
        set_error_handler("$className::errorHandler");
        register_shutdown_function("$className::fatalErrorHandler");

        $s_alreadyRegistered = true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // Properties and defaults.
    protected static $ms_assertionsLevel2 = false;
    protected static $ms_logging = false;
    protected static $ms_errorLogFp;
    protected static $ms_mailing = false;
    protected static $ms_mail;
    protected static $ms_minTimeBetweenSendMailHours;
    protected static $ms_maxNumRecordsInLog = 1024;
    protected static $ms_logRecordDateTimePattern = "Y-m-d H:i:s e";
    protected static $ms_maxNumLatestLogRecordsInMailMessage = 16;
    protected static $ms_debugVarStringMaxLength = 65536;  // 64 KB
    protected static $ms_anyFailedAssertionIsFatal = false;
    protected static $ms_lastMailingTimeFnPrefix = "tmp-phred-on-error-last-mailing-time-for-docroot-";
    protected static $ms_assertVarsMarker = "\t \t\t \t \t\t \t \n";
    protected static $ms_processedErrors = [];
}
