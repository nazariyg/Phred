<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that greatly simplifies writing CLI scripts in PHP by facilitating messaging, logging and mailing, script
 * option retrieval, input capturing, and command execution in both CLI and regular modes.
 *
 * For a script that was run in the CLI mode, this class makes it easier to retrieve the options with which the script
 * was run and their values, and to issue messages into the output, such as comments and error messages. It also
 * automates the logging of such messages into a file and mailing about errors to the administrator, assists in
 * retrieving user input, and, for both CLI and regular modes, improves the execution of commands and command
 * pipelines.
 */

// Method signatures:
//   static void setLogging ($sLogFp)
//   static void setMailing (CMail $oMail)
//   static bool hasOption ($sOptionName)
//   static bool hasOptionWithValue ($sOptionName)
//   static bool hasLongOption ($sOptionName)
//   static bool hasLongOptionWithValue ($sOptionName)
//   static CUStringObject valueForOption ($sOptionName)
//   static CUStringObject valueForLongOption ($sOptionName)
//   static CArrayObject valuesForOption ($sOptionName)
//   static CArrayObject valuesForLongOption ($sOptionName)
//   static bool valueForOptionBool ($sOptionName)
//   static bool valueForLongOptionBool ($sOptionName)
//   static int valueForOptionInt ($sOptionName)
//   static int valueForLongOptionInt ($sOptionName)
//   static CUStringObject scriptFp ()
//   static CUStringObject scriptDp ()
//   static CUStringObject currentDate ()
//   static CUStringObject user ()
//   static void exitIfNotRoot ()
//   static void say ($sMessage, $bWriteToLog = true)
//   static void speak ($sMessage, $bWriteToLog = true)
//   static CUStringObject getInput ()
//   static CUStringObject getInputSecretly ()
//   static CUStringObject execCommand ($sCommand, &$rbSuccess = null)
//   static CUStringObject execCommandPipe ($sCommands, &$rbSuccess = null)
//   static CUStringObject execCommandM ($sCommand, $xMessage, $bExitOnFail = true)
//   static CUStringObject execCommandPipeM ($sCommands, $xMessage, $bExitOnFail = true)
//   static void exitOkM ($sMessage)
//   static void errorM ($sMessage, $iLineNum = null)
//   static void warningM ($sMessage, $iLineNum = null)
//   static void writeToLog ($sString)
//   static void sendMail ($sMessage)
//   static void exitScript ($bStatusIsOk, $bWriteNewlineToLog = true)

class CShell extends CRootClass
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Enables the logging of messages issued with `say` and `speak` methods and error messages into a file located at
     * a specified path.
     *
     * @param  string $sLogFp The path to the log file.
     *
     * @return void
     */

    public static function setLogging ($sLogFp)
    {
        assert( 'is_cstring($sLogFp)', vs(isset($this), get_defined_vars()) );

        $sLogFp = CFilePath::frameworkPath($sLogFp);

        self::$ms_sLogFp = $sLogFp;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Enables mailing about occurred errors with a specified message template.
     *
     * @param  CMail $oMail The message template to be used for sending emails.
     *
     * @return void
     */

    public static function setMailing (CMail $oMail)
    {
        self::$ms_oMail = $oMail;
        self::$ms_oMail->setSubject("PHP Running in CLI Mode Encountered a Problem after Starting '" .
            CSystem::initScriptFn() . "'");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the script was run with a specified short option, e.g. "-o".
     *
     * @param  string $sOptionName The name of the option, excluding "-".
     *
     * @return bool `true` if the script was run with the option specified, `false` otherwise.
     */

    public static function hasOption ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );

        $mOpt = getopt("$sOptionName::");
        return ( is_cmap($mOpt) && CMap::hasKey($mOpt, $sOptionName) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the script was run with a specified short option and the option had a value assigned to it,
     * e.g. "-o=value".
     *
     * @param  string $sOptionName The name of the option, excluding "-".
     *
     * @return bool `true` if the script was run with the option specified and the option had a value assigned to it,
     * `false` otherwise.
     */

    public static function hasOptionWithValue ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );

        $mOpt = getopt("$sOptionName:");
        return ( is_cmap($mOpt) && CMap::hasKey($mOpt, $sOptionName) && $mOpt[$sOptionName] !== false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the script was run with a specified long option, e.g. "--option".
     *
     * @param  string $sOptionName The name of the option, excluding "-".
     *
     * @return bool `true` if the script was run with the option specified, `false` otherwise.
     */

    public static function hasLongOption ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );

        $mOpt = getopt("", ["$sOptionName::"]);
        return ( is_cmap($mOpt) && CMap::hasKey($mOpt, $sOptionName) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the script was run with a specified long option and the option had a value assigned to it,
     * e.g. "--option=value".
     *
     * @param  string $sOptionName The name of the option, excluding "-".
     *
     * @return bool `true` if the script was run with the option specified and the option had a value assigned to it,
     * `false` otherwise.
     */

    public static function hasLongOptionWithValue ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );

        $mOpt = getopt("", ["$sOptionName:"]);
        return ( is_cmap($mOpt) && CMap::hasKey($mOpt, $sOptionName) && $mOpt[$sOptionName] !== false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified short option with which the script was run, as a string.
     *
     * @param  string $sOptionName The name of the option, excluding "-".
     *
     * @return CUStringObject The value of the option specified.
     */

    public static function valueForOption ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        assert( 'self::hasOptionWithValue($sOptionName)', vs(isset($this), get_defined_vars()) );

        $mOpt = getopt("$sOptionName:");
        $sValue = $mOpt[$sOptionName];
        assert( 'is_cstring($sValue)', vs(isset($this), get_defined_vars()) );
        return $sValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified long option with which the script was run, as a string.
     *
     * @param  string $sOptionName The name of the option, excluding "-".
     *
     * @return CUStringObject The value of the option specified.
     */

    public static function valueForLongOption ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        assert( 'self::hasLongOptionWithValue($sOptionName)', vs(isset($this), get_defined_vars()) );

        $mOpt = getopt("", ["$sOptionName:"]);
        $sValue = $mOpt[$sOptionName];
        assert( 'is_cstring($sValue)', vs(isset($this), get_defined_vars()) );
        return $sValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns all the values assigned to a specified short option with which the script was run,
     * e.g. "-o=value1 -o=value2".
     *
     * @param  string $sOptionName The name of the option, excluding "-".
     *
     * @return CArrayObject The values of the option specified, where each value is of type `CUStringObject`.
     */

    public static function valuesForOption ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        assert( 'self::hasOptionWithValue($sOptionName)', vs(isset($this), get_defined_vars()) );

        $mOpt = getopt("$sOptionName:");
        $xValues = $mOpt[$sOptionName];
        return oop_a(( is_cmap($xValues) ) ? CArray::fromPArray($xValues) : CArray::fromElements($xValues));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns all the values assigned to a specified long option with which the script was run,
     * e.g. "--option=value1 --option=value2".
     *
     * @param  string $sOptionName The name of the option, excluding "-".
     *
     * @return CArrayObject The values of the option specified, where each value is of type `CUStringObject`.
     */

    public static function valuesForLongOption ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        assert( 'self::hasLongOptionWithValue($sOptionName)', vs(isset($this), get_defined_vars()) );

        $mOpt = getopt("", ["$sOptionName:"]);
        $xValues = $mOpt[$sOptionName];
        return oop_a(( is_cmap($xValues) ) ? CArray::fromPArray($xValues) : CArray::fromElements($xValues));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified short option with which the script was run, as a boolean value.
     *
     * If the option's value is "1", "true", "yes", or "on", case-insensitively, it's recognized as `true`, and as
     * `false` for any other value.
     *
     * @param  string $sOptionName The name of the option, excluding "-".
     *
     * @return bool `true` for "1", "true", "yes", and "on", case-insensitively, `false` otherwise.
     */

    public static function valueForOptionBool ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        return CString::toBool(self::valueForOption($sOptionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified long option with which the script was run, as a boolean value.
     *
     * If the option's value is "1", "true", "yes", or "on", case-insensitively, it's recognized as `true`, and as
     * `false` for any other value.
     *
     * @param  string $sOptionName The name of the option, excluding "-".
     *
     * @return bool `true` for "1", "true", "yes", and "on", case-insensitively, `false` otherwise.
     */

    public static function valueForLongOptionBool ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        return CString::toBool(self::valueForLongOption($sOptionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified short option with which the script was run, as an integer value.
     *
     * @param  string $sOptionName The name of the option, excluding "-".
     *
     * @return int The value of the option specified.
     */

    public static function valueForOptionInt ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        return CString::toInt(self::valueForOption($sOptionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified long option with which the script was run, as an integer value.
     *
     * @param  string $sOptionName The name of the option, excluding "-".
     *
     * @return int The value of the option specified.
     */

    public static function valueForLongOptionInt ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        return CString::toInt(self::valueForLongOption($sOptionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path to the file of the running script.
     *
     * @return CUStringObject The path to the file of the running script.
     */

    public static function scriptFp ()
    {
        return CSystem::initScriptFp();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path to the directory where the running script is located.
     *
     * @return CUStringObject The path to the directory where the running script is located.
     */

    public static function scriptDp ()
    {
        return CFilePath::directory(CSystem::initScriptFp());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a string with the current date and time formatted by the shell to allow for consistency with other logs
     * on the system.
     *
     * @return CUStringObject The current date and time formatted by the shell.
     */

    public static function currentDate ()
    {
        $sDate = self::execCommand("date");
        $sDate = CString::trim($sDate);
        return $sDate;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the name of the user on whose behalf the script is running.
     *
     * Getting the user's name in this way is more reliable than `$_SERVER["USER"]`.
     *
     * @return CUStringObject The name of the user on whose behalf the script is running.
     */

    public static function user ()
    {
        $sFoundString;
        $bFound = CRegex::find(self::execCommand("whoami"), "/\\S+/", $sFoundString);
        assert( '$bFound', vs(isset($this), get_defined_vars()) );
        return $sFoundString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Exits the script issuing an error message if the script's user is lacking root privileges.
     *
     * @return void
     */

    public static function exitIfNotRoot ()
    {
        $sUser = self::user();
        if ( !CString::equals($sUser, "root") )
        {
            $sMessage = "This script requires root privileges to run, while the current user is $sUser.";
            self::onError(true, $sMessage);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Echoes a message into the output and, if logging is enabled, into the log file, making a new line after the
     * message's text unless the message is already ending in a newline.
     *
     * @param  string $sMessage The message to be echoed.
     * @param  bool $bWriteToLog **OPTIONAL. Default is** `true`. If logging is enabled, tells whether the message
     * should also be written to the log file.
     *
     * @return void
     */

    public static function say ($sMessage, $bWriteToLog = true)
    {
        assert( 'is_cstring($sMessage) && is_bool($bWriteToLog)', vs(isset($this), get_defined_vars()) );

        if ( !CString::endsWith($sMessage, "\n") )
        {
            $sMessage .= "\n";
        }
        echo $sMessage;
        if ( $bWriteToLog && isset(self::$ms_sLogFp) )
        {
            self::writeToLog($sMessage);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Echoes a message into the output and, if logging is enabled, into the log file, adding a space after the
     * message's text unless the message is already ending in a space.
     *
     * @param  string $sMessage The message to be echoed.
     * @param  bool $bWriteToLog **OPTIONAL. Default is** `true`. If logging is enabled, tells whether the message
     * should also be written to the log file.
     *
     * @return void
     */

    public static function speak ($sMessage, $bWriteToLog = true)
    {
        assert( 'is_cstring($sMessage) && is_bool($bWriteToLog)', vs(isset($this), get_defined_vars()) );

        if ( !CString::endsWith($sMessage, " ") )
        {
            $sMessage .= " ";
        }
        echo $sMessage;
        if ( $bWriteToLog && isset(self::$ms_sLogFp) )
        {
            self::writeToLog($sMessage);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Waits for and returns a keyboard input from the user.
     *
     * @return CUStringObject The captured keyboard input.
     */

    public static function getInput ()
    {
        $sInput = fgets(STDIN);
        $sInput = CRegex::remove($sInput, "/\\n+\\z/");
        return $sInput;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Waits for and returns a keyboard input from the user, in a way that typed characters appear hidden on the
     * screen.
     *
     * @return CUStringObject The captured keyboard input.
     */

    public static function getInputSecretly ()
    {
        $sInput = self::execCommand("stty -echo; head -n1; stty echo");
        $sInput = CRegex::remove($sInput, "/\\n+\\z/");
        return $sInput;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Executes a command and returns the output.
     *
     * @param  string $sCommand The command to be executed.
     * @param  reference $rbSuccess **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value tells whether the command was run successfully.
     *
     * @return CUStringObject The output of the command.
     */

    public static function execCommand ($sCommand, &$rbSuccess = null)
    {
        assert( 'is_cstring($sCommand)', vs(isset($this), get_defined_vars()) );

        ob_start();
        $iCommandExitCode;
        passthru($sCommand, $iCommandExitCode);
        $sOutput = ob_get_clean();
        $rbSuccess = ( $iCommandExitCode == 0 );
        return $sOutput;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Executes a command pipeline, e.g. "command1 | command2", and returns the output.
     *
     * Using this method instead of `execCommand` method ensures that, if a command in the pipeline fails, so does the
     * entire pipeline.
     *
     * @param  string $sCommands The command pipeline to be executed.
     * @param  reference $rbSuccess **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value tells whether the pipeline was run successfully.
     *
     * @return CUStringObject The output of the command pipeline.
     */

    public static function execCommandPipe ($sCommands, &$rbSuccess = null)
    {
        assert( 'is_cstring($sCommands)', vs(isset($this), get_defined_vars()) );

        $sCommands = CString::replace($sCommands, "\"", "'");
        $sCommand = "bash -c \"set -o pipefail && $sCommands\"";
        return self::execCommand($sCommand, $rbSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Executes a command and returns the output, issuing a specified error message if the command fails.
     *
     * @param  string $sCommand The command to be executed.
     * @param  mixed $xMessage The error message to be issued if the command fails or the number of the line at which
     * the error occurred (obtained using `__LINE__` magic constant). In the latter case, the error message is
     * generated automatically.
     * @param  bool $bExitOnFail **OPTIONAL. Default is** `true`. Tells whether to exit the script if the command did
     * not succeed.
     *
     * @return CUStringObject The output of the command.
     */

    public static function execCommandM ($sCommand, $xMessage, $bExitOnFail = true)
    {
        assert( 'is_cstring($sCommand) && (is_cstring($xMessage) || is_int($xMessage)) && is_bool($bExitOnFail)',
            vs(isset($this), get_defined_vars()) );
        settype($xMessage, "string");

        $bCommandSuccess;
        $sOutput = self::execCommand($sCommand, $bCommandSuccess);
        if ( !$bCommandSuccess )
        {
            if ( CRegex::find($xMessage, "/^\\d+\\z/") )
            {
                // The message is a line number to be reported.
                $xMessage =
                    "The script encountered an error while executing command:\n$sCommand\nReported line: $xMessage.";
            }
            self::onError($bExitOnFail, $xMessage);
        }
        return $sOutput;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Executes a command pipeline, e.g. "command1 | command2", and returns the output, issuing a specified error
     * message if the pipeline fails.
     *
     * Using this method instead of `execCommandM` method ensures that, if a command in the pipeline fails, so does the
     * entire pipeline.
     *
     * @param  string $sCommands The command pipeline to be executed.
     * @param  mixed $xMessage The error message to be issued if the pipeline fails or the number of the line at which
     * the error occurred (obtained using `__LINE__` magic constant). In the latter case, the error message is
     * generated automatically.
     * @param  bool $bExitOnFail **OPTIONAL. Default is** `true`. Tells whether to exit the script if the pipeline did
     * not succeed.
     *
     * @return CUStringObject The output of the command pipeline.
     */

    public static function execCommandPipeM ($sCommands, $xMessage, $bExitOnFail = true)
    {
        assert( 'is_cstring($sCommands) && (is_cstring($xMessage) || is_int($xMessage)) && is_bool($bExitOnFail)',
            vs(isset($this), get_defined_vars()) );
        settype($xMessage, "string");

        $bCommandsSuccess;
        $sOutput = self::execCommandPipe($sCommands, $bCommandsSuccess);
        if ( !$bCommandsSuccess )
        {
            if ( CRegex::find($xMessage, "/^\\d+\\z/") )
            {
                // The message is a line number to be reported.
                $xMessage = "The script encountered an error while executing a command pipe:" .
                    "\n$sCommands\nReported line: $xMessage.";
            }
            self::onError($bExitOnFail, $xMessage);
        }
        return $sOutput;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Exits the script with the success code, issuing a specified message.
     *
     * @param  string $sMessage The message to be issued.
     *
     * @return void
     */

    public static function exitOkM ($sMessage)
    {
        assert( 'is_cstring($sMessage)', vs(isset($this), get_defined_vars()) );

        $sMessage .= ( !CString::find($sMessage, "\n") ) ? " " : "\n";
        $sMessage .= "Exiting.";
        self::say($sMessage);
        self::exitScript(true);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Reports an error with a specified message and exits the script.
     *
     * @param  string $sMessage The error message to be issued.
     * @param  int $iLineNum **OPTIONAL.** The number of the line at which the error occurred.
     *
     * @return void
     */

    public static function errorM ($sMessage, $iLineNum = null)
    {
        assert( 'is_cstring($sMessage) && (!isset($iLineNum) || is_int($iLineNum))',
            vs(isset($this), get_defined_vars()) );

        if ( isset($iLineNum) )
        {
            $sMessage .= ( !CString::find($sMessage, "\n") ) ? " " : "\n";
            $sMessage .= "Reported line: $iLineNum.";
        }
        self::onError(true, $sMessage);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Reports an error with a specified message but does not exit the script.
     *
     * @param  string $sMessage The error message to be issued.
     * @param  int $iLineNum **OPTIONAL.** The number of the line at which the error occurred.
     *
     * @return void
     */

    public static function warningM ($sMessage, $iLineNum = null)
    {
        assert( 'is_cstring($sMessage) && (!isset($iLineNum) || is_int($iLineNum))',
            vs(isset($this), get_defined_vars()) );

        $sMessage = "Warning: $sMessage";
        if ( isset($iLineNum) )
        {
            $sMessage .= ( !CString::find($sMessage, "\n") ) ? " " : "\n";
            $sMessage .= "Reported line: $iLineNum.";
        }
        self::onError(false, $sMessage);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * If logging is enabled, writes a string into the log file.
     *
     * @param  string $sString The string to be logged.
     *
     * @return void
     */

    public static function writeToLog ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        assert( 'isset(self::$ms_sLogFp)', vs(isset($this), get_defined_vars()) );

        CFile::append(self::$ms_sLogFp, $sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * If mailing is enabled, sends a message out.
     *
     * @param  string $sMessage The message to be sent.
     *
     * @return void
     */

    public static function sendMail ($sMessage)
    {
        assert( 'is_cstring($sMessage)', vs(isset($this), get_defined_vars()) );
        assert( 'isset(self::$ms_oMail)', vs(isset($this), get_defined_vars()) );

        // Send.
        self::$ms_oMail->setBody($sMessage);
        self::$ms_oMail->disableWordWrapping();
        self::$ms_oMail->send();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Exits the script with a specified status.
     *
     * @param  bool $bStatusIsOk `true` if the script has run successfully, `false` if not.
     * @param  bool $bWriteNewlineToLog **OPTIONAL. Default is** `true`. If logging is enabled, tells whether the
     * current entries in the log file should be concluded with a newline.
     *
     * @return void
     */

    public static function exitScript ($bStatusIsOk, $bWriteNewlineToLog = true)
    {
        assert( 'is_bool($bStatusIsOk) && is_bool($bWriteNewlineToLog)', vs(isset($this), get_defined_vars()) );

        if ( $bWriteNewlineToLog && isset(self::$ms_sLogFp) )
        {
            self::writeToLog("\n");
        }
        $iExitCode = ( $bStatusIsOk ) ? 0 : 1;
        exit($iExitCode);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function onError ($bExit = true, $sMessage)
    {
        if ( $bExit )
        {
            if ( !CString::isEmpty($sMessage) )
            {
                $sMessage .= ( !CString::find($sMessage, "\n") ) ? " " : "\n";
            }
            $sMessage .= "Exiting.";
        }
        if ( !CString::isEmpty($sMessage) )
        {
            self::say($sMessage);
        }
        if ( isset(self::$ms_oMail) )
        {
            self::sendMail($sMessage);
        }
        if ( $bExit )
        {
            self::exitScript(false);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected static $ms_sLogFp;
    protected static $ms_oMail;
}
