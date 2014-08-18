<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that lets you obtain various information on the system that PHP is running on, access PHP's configuration
 * options, set locks on files to ensure that a certain operation can be performed by only one script at a time, and
 * more.
 *
 * **You can refer to this class by its alias, which is** `Sys`.
 */

// Method signatures:
//   static CTime startTime ()
//   static CUStringObject documentRootDp ()
//   static CUStringObject temporaryFilesDp ()
//   static CUStringObject initScriptFp ()
//   static CUStringObject initScriptFn ()
//   static CUStringObject initScriptRelFp ()
//   static void cd ($directoryPath)
//   static CUStringObject pwd ()
//   static bool setLock ($lockFp, $wait)
//   static void unsetLock ($lockFp)
//   static int numFreeDiskBytes ($referenceDp = ".")
//   static int numTotalDiskBytes ($referenceDp = ".")
//   static int executionTimeLimit ()
//   static void setExecutionTimeLimit ($numSeconds)
//   static void removeExecutionTimeLimit ()
//   static int currentMemoryUsageInt ($inUnit = CUUnit::BYTE)
//   static float currentMemoryUsageFloat ($inUnit = CUUnit::BYTE)
//   static int peakMemoryUsageInt ($inUnit = CUUnit::BYTE)
//   static float peakMemoryUsageFloat ($inUnit = CUUnit::BYTE)
//   static void setMemoryUsageLimit ($numBytes)
//   static bool hasConfigOption ($optionName)
//   static CUStringObject configOption ($optionName)
//   static bool configOptionBool ($optionName)
//   static int configOptionInt ($optionName)
//   static float configOptionFloat ($optionName)
//   static int configOptionStorageInt ($optionName, $inUnit = CUUnit::BYTE)
//   static float configOptionStorageFloat ($optionName, $inUnit = CUUnit::BYTE)
//   static CUStringObject configOptionOrig ($optionName)
//   static bool configOptionOrigBool ($optionName)
//   static int configOptionOrigInt ($optionName)
//   static float configOptionOrigFloat ($optionName)
//   static int configOptionOrigStorageInt ($optionName, $inUnit = CUUnit::BYTE)
//   static float configOptionOrigStorageFloat ($optionName, $inUnit = CUUnit::BYTE)
//   static void setConfigOption ($optionName, $optionValue)
//   static void setConfigOptionBool ($optionName, $optionValue)
//   static void setConfigOptionInt ($optionName, $optionValue)
//   static void setConfigOptionFloat ($optionName, $optionValue)
//   static void restoreConfigOption ($optionName)
//   static bool isInCliMode ()
//   static CUStringObject hostName ()
//   static CUStringObject hostIp ()
//   static CUStringObject httpServerName ()
//   static CUStringObject gatewayInterface ()
//   static CUStringObject langVersion ()
//   static CUStringObject langSapi ()
//   static CUStringObject langInfo ()
//   static CUStringObject langExeBinaryFp ()
//   static CUStringObject osName ()
//   static CUStringObject osInfo ()

class CSystem extends CRootClass
{
    /**
     * `int` `64` If xdebug is being used, this constant should be correlated with the value of the xdebug's
     * "xdebug.max_nesting_level" option.
     *
     * @var int
     */
    const DEFAULT_MAX_RECURSION_DEPTH = 64;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the point in time when the initial script started to execute.
     *
     * @return CTime The point in time when the initial script started to execute.
     */

    public static function startTime ()
    {
        return new CTime($GLOBALS["PHRED_START_UTIME"]);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path to the Document Root directory.
     *
     * @return CUStringObject The path to the Document Root directory.
     */

    public static function documentRootDp ()
    {
        return CString::stripEnd($_SERVER["DOCUMENT_ROOT"], "/");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path to the default directory for temporary files.
     *
     * The returned path is the path to either the OS's temporary files directory or the directory specified in the
     * application's configuration.
     *
     * @return CUStringObject The path to the default directory for temporary files.
     */

    public static function temporaryFilesDp ()
    {
        $dp;
        $customTempFilesDp = CConfiguration::appOption("customTemporaryFilesDirectoryPath");
        if ( CString::isEmpty($customTempFilesDp) )
        {
            // Return the OS's temporary files directory.
            $dp = sys_get_temp_dir();
        }
        else
        {
            // Return the custom temporary files directory.
            $dp = $customTempFilesDp;
        }
        $dp = CString::stripEnd($dp, "/");  // just in case
        return $dp;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path to the file of the initially run script.
     *
     * @return CUStringObject The path to the file of the initially run script.
     */

    public static function initScriptFp ()
    {
        return $_SERVER["SCRIPT_FILENAME"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the name of the file of the initially run script.
     *
     * @return CUStringObject The name of the file of the initially run script.
     */

    public static function initScriptFn ()
    {
        return CFilePath::name(self::initScriptFp());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path to the file of the initially run script, relative to the Document Root directory.
     *
     * @return CUStringObject The path to the file of the initially run script, relative to the Document Root
     * directory.
     */

    public static function initScriptRelFp ()
    {
        return CString::stripStart($_SERVER["SCRIPT_NAME"], "/");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Changes the current working directory to a specified path.
     *
     * @param  string $directoryPath The path to the new current working directory.
     *
     * @return void
     */

    public static function cd ($directoryPath)
    {
        assert( 'is_cstring($directoryPath)', vs(isset($this), get_defined_vars()) );

        $res = chdir($directoryPath);
        assert( '$res', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path to the current working directory.
     *
     * @return CUStringObject The path to the current working directory.
     */

    public static function pwd ()
    {
        $wd = getcwd();
        assert( 'is_cstring($wd)', vs(isset($this), get_defined_vars()) );
        return $wd;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tries to set a lock on a file so that any other process that attempts to lock the same file either waits for the
     * file to get unlocked or continues without the lock.
     *
     * @param  string $lockFp The path to the file to be locked.
     * @param  bool $wait If the file appears to be already locked, this parameter tells whether to wait for the file
     * to get unlocked (`true`) or to return immediately (`false`).
     *
     * @return bool `true` if the file was successfully locked, `false` otherwise.
     */

    public static function setLock ($lockFp, $wait)
    {
        assert( 'is_cstring($lockFp) && is_bool($wait)', vs(isset($this), get_defined_vars()) );

        if ( !CFile::exists($lockFp) )
        {
            // Use `touch` instead of `CFile::create`.
            touch($lockFp);
        }
        $file = fopen($lockFp, "r+");
        if ( flock($file, ( $wait ) ? LOCK_EX : LOCK_EX | LOCK_NB) )
        {
            if ( !isset(self::$ms_fhFromLockFp) )
            {
                self::$ms_fhFromLockFp = CMap::make();
            }
            $lockFpAbs = CFilePath::absolute($lockFp);
            self::$ms_fhFromLockFp[$lockFpAbs] = $file;
            return true;
        }
        else
        {
            fclose($file);
            return false;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes the lock that was previously set on a file.
     *
     * @param  string $lockFp The path to the file to be unlocked.
     *
     * @return void
     */

    public static function unsetLock ($lockFp)
    {
        assert( 'is_cstring($lockFp)', vs(isset($this), get_defined_vars()) );

        $lockFpAbs = CFilePath::absolute($lockFp);
        $file = self::$ms_fhFromLockFp[$lockFpAbs];
        flock($file, LOCK_UN);
        fclose($file);
        CMap::remove(self::$ms_fhFromLockFp, $lockFpAbs);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the amount of free space available on the filesystem or a disk partition.
     *
     * @param  string $referenceDp **OPTIONAL. Default is** ".". The path to the reference directory on the filesystem
     * or the disk partition of interest.
     *
     * @return int The amount of free space available on the filesystem or the disk partition, in bytes.
     */

    public static function numFreeDiskBytes ($referenceDp = ".")
    {
        assert( 'is_cstring($referenceDp)', vs(isset($this), get_defined_vars()) );

        $numBytes = disk_free_space($referenceDp);
        assert( 'is_float($numBytes)', vs(isset($this), get_defined_vars()) );
        if ( $numBytes < (float)CMathi::INT_MAX )
        {
            return (int)$numBytes;
        }
        else
        {
            return CMathi::INT_MAX;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the amount of total space on the filesystem or a disk partition.
     *
     * @param  string $referenceDp **OPTIONAL. Default is** ".". The path to the reference directory on the filesystem
     * or the disk partition of interest.
     *
     * @return int The amount of total space on the filesystem or the disk partition, in bytes.
     */

    public static function numTotalDiskBytes ($referenceDp = ".")
    {
        assert( 'is_cstring($referenceDp)', vs(isset($this), get_defined_vars()) );

        $numBytes = disk_total_space($referenceDp);
        assert( 'is_float($numBytes)', vs(isset($this), get_defined_vars()) );
        if ( $numBytes < (float)CMathi::INT_MAX )
        {
            return (int)$numBytes;
        }
        else
        {
            return CMathi::INT_MAX;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the maximum amount of time for which the script is allowed to execute.
     *
     * @return int The maximum amount of time for which the script is allowed to execute, in seconds.
     */

    public static function executionTimeLimit ()
    {
        return self::configOptionInt("max_execution_time");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the maximum amount of time for which the script is allowed to execute.
     *
     * @param  int $numSeconds The maximum amount of time for which the script is allowed to execute, in seconds.
     *
     * @return void
     */

    public static function setExecutionTimeLimit ($numSeconds)
    {
        assert( 'is_int($numSeconds)', vs(isset($this), get_defined_vars()) );
        assert( '$numSeconds > 0', vs(isset($this), get_defined_vars()) );

        self::setConfigOptionInt("max_execution_time", $numSeconds);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes any limit that is set on the amount of time for which the script is allowed to execute.
     *
     * @return void
     */

    public static function removeExecutionTimeLimit ()
    {
        self::setConfigOptionInt("max_execution_time", 0);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the amount of memory currently allocated to PHP, as an integer value.
     *
     * @param  enum $inUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return int The amount of memory currently allocated to PHP.
     */

    public static function currentMemoryUsageInt ($inUnit = CUUnit::BYTE)
    {
        assert( 'is_enum($inUnit)', vs(isset($this), get_defined_vars()) );

        $byteQty = memory_get_usage(true);
        return CUUnit::convertStoragei($byteQty, CUUnit::BYTE, $inUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the amount of memory currently allocated to PHP, as a floating-point value.
     *
     * @param  enum $inUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return float The amount of memory currently allocated to PHP.
     */

    public static function currentMemoryUsageFloat ($inUnit = CUUnit::BYTE)
    {
        assert( 'is_enum($inUnit)', vs(isset($this), get_defined_vars()) );

        $byteQty = memory_get_usage(true);
        return CUUnit::convertStoragef((float)$byteQty, CUUnit::BYTE, $inUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the peak amount of memory allocated to PHP to this moment, as an integer value.
     *
     * @param  enum $inUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return int The peak amount of memory allocated to PHP to this moment.
     */

    public static function peakMemoryUsageInt ($inUnit = CUUnit::BYTE)
    {
        assert( 'is_enum($inUnit)', vs(isset($this), get_defined_vars()) );

        $byteQty = memory_get_peak_usage(true);
        return CUUnit::convertStoragei($byteQty, CUUnit::BYTE, $inUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the peak amount of memory allocated to PHP to this moment, as a floating-point value.
     *
     * @param  enum $inUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return float The peak amount of memory allocated to PHP to this moment.
     */

    public static function peakMemoryUsageFloat ($inUnit = CUUnit::BYTE)
    {
        assert( 'is_enum($inUnit)', vs(isset($this), get_defined_vars()) );

        $byteQty = memory_get_peak_usage(true);
        return CUUnit::convertStoragef((float)$byteQty, CUUnit::BYTE, $inUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the maximum amount of memory the script is allowed to allocate.
     *
     * @param  int $numBytes The maximum amount of memory the script is allowed to allocate, in bytes.
     *
     * @return void
     */

    public static function setMemoryUsageLimit ($numBytes)
    {
        assert( 'is_int($numBytes)', vs(isset($this), get_defined_vars()) );
        assert( '$numBytes > 0', vs(isset($this), get_defined_vars()) );

        self::setConfigOptionInt("memory_limit", $numBytes);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the PHP's configuration options include an option with a specified name.
     *
     * @param  string $optionName The name of the option to be looked for.
     *
     * @return bool `true` if the PHP's configuration options include an option with the name specified, `false`
     * otherwise.
     */

    public static function hasConfigOption ($optionName)
    {
        assert( 'is_cstring($optionName)', vs(isset($this), get_defined_vars()) );

        $res = ini_get($optionName);
        return ( $res !== false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified PHP's configuration option, as a string.
     *
     * @param  string $optionName The name of the option.
     *
     * @return CUStringObject The value of the option.
     */

    public static function configOption ($optionName)
    {
        assert( 'is_cstring($optionName)', vs(isset($this), get_defined_vars()) );

        $optionValue = ini_get($optionName);
        assert( 'is_cstring($optionValue)', vs(isset($this), get_defined_vars()) );
        return $optionValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified PHP's configuration option, as a boolean value.
     *
     * @param  string $optionName The name of the option.
     *
     * @return bool The value of the option.
     */

    public static function configOptionBool ($optionName)
    {
        assert( 'is_cstring($optionName)', vs(isset($this), get_defined_vars()) );
        return CString::toBool(self::configOption($optionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified PHP's configuration option, as an integer value.
     *
     * @param  string $optionName The name of the option.
     *
     * @return int The value of the option.
     */

    public static function configOptionInt ($optionName)
    {
        assert( 'is_cstring($optionName)', vs(isset($this), get_defined_vars()) );
        return CString::toInt(self::configOption($optionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified PHP's configuration option, as a floating-point value.
     *
     * @param  string $optionName The name of the option.
     *
     * @return float The value of the option.
     */

    public static function configOptionFloat ($optionName)
    {
        assert( 'is_cstring($optionName)', vs(isset($this), get_defined_vars()) );
        return CString::toFloat(self::configOption($optionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified PHP's configuration option that is related to digital storage, as an integer
     * value.
     *
     * @param  string $optionName The name of the option.
     * @param  enum $inUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return int The value of the option.
     */

    public static function configOptionStorageInt ($optionName, $inUnit = CUUnit::BYTE)
    {
        assert( 'is_cstring($optionName) && is_enum($inUnit)', vs(isset($this), get_defined_vars()) );

        $optionValue = self::configOption($optionName);
        $srcUnit = self::storageUnitFromOptionValue($optionValue);
        $strValue;
        $found = CRegex::find($optionValue, "/^\\d+/", $strValue);
        assert( '$found', vs(isset($this), get_defined_vars()) );
        $value = CString::toInt($strValue);
        return CUUnit::convertStoragei($value, $srcUnit, $inUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified PHP's configuration option that is related to digital storage, as a
     * floating-point value.
     *
     * @param  string $optionName The name of the option.
     * @param  enum $inUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return float The value of the option.
     */

    public static function configOptionStorageFloat ($optionName, $inUnit = CUUnit::BYTE)
    {
        assert( 'is_cstring($optionName) && is_enum($inUnit)', vs(isset($this), get_defined_vars()) );

        $optionValue = self::configOption($optionName);
        $srcUnit = self::storageUnitFromOptionValue($optionValue);
        $strValue;
        $found = CRegex::find($optionValue, "/^\\d+/", $strValue);
        assert( '$found', vs(isset($this), get_defined_vars()) );
        $value = CString::toFloat($strValue);
        return CUUnit::convertStoragef($value, $srcUnit, $inUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the original value of a specified PHP's configuration option, as a string.
     *
     * @param  string $optionName The name of the option.
     *
     * @return CUStringObject The value of the option.
     */

    public static function configOptionOrig ($optionName)
    {
        assert( 'is_cstring($optionName)', vs(isset($this), get_defined_vars()) );

        $optionValue = get_cfg_var($optionName);
        assert( 'is_cstring($optionValue)', vs(isset($this), get_defined_vars()) );
        return $optionValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the original value of a specified PHP's configuration option, as a boolean value.
     *
     * @param  string $optionName The name of the option.
     *
     * @return bool The value of the option.
     */

    public static function configOptionOrigBool ($optionName)
    {
        assert( 'is_cstring($optionName)', vs(isset($this), get_defined_vars()) );
        return CString::toBool(self::configOptionOrig($optionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the original value of a specified PHP's configuration option, as an integer value.
     *
     * @param  string $optionName The name of the option.
     *
     * @return int The value of the option.
     */

    public static function configOptionOrigInt ($optionName)
    {
        assert( 'is_cstring($optionName)', vs(isset($this), get_defined_vars()) );
        return CString::toInt(self::configOptionOrig($optionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the original value of a specified PHP's configuration option, as a floating-point value.
     *
     * @param  string $optionName The name of the option.
     *
     * @return float The value of the option.
     */

    public static function configOptionOrigFloat ($optionName)
    {
        assert( 'is_cstring($optionName)', vs(isset($this), get_defined_vars()) );
        return CString::toFloat(self::configOptionOrig($optionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the original value of a specified PHP's configuration option that is related to digital storage, as an
     * integer value.
     *
     * @param  string $optionName The name of the option.
     * @param  enum $inUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return int The value of the option.
     */

    public static function configOptionOrigStorageInt ($optionName, $inUnit = CUUnit::BYTE)
    {
        assert( 'is_cstring($optionName) && is_enum($inUnit)', vs(isset($this), get_defined_vars()) );

        $optionValue = self::configOptionOrig($optionName);
        $srcUnit = self::storageUnitFromOptionValue($optionValue);
        $strValue;
        $found = CRegex::find($optionValue, "/^\\d+/", $strValue);
        assert( '$found', vs(isset($this), get_defined_vars()) );
        $value = CString::toInt($strValue);
        return CUUnit::convertStoragei($value, $srcUnit, $inUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the original value of a specified PHP's configuration option that is related to digital storage, as a
     * floating-point value.
     *
     * @param  string $optionName The name of the option.
     * @param  enum $inUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return float The value of the option.
     */

    public static function configOptionOrigStorageFloat ($optionName, $inUnit = CUUnit::BYTE)
    {
        assert( 'is_cstring($optionName) && is_enum($inUnit)', vs(isset($this), get_defined_vars()) );

        $optionValue = self::configOptionOrig($optionName);
        $srcUnit = self::storageUnitFromOptionValue($optionValue);
        $strValue;
        $found = CRegex::find($optionValue, "/^\\d+/", $strValue);
        assert( '$found', vs(isset($this), get_defined_vars()) );
        $value = CString::toFloat($strValue);
        return CUUnit::convertStoragef($value, $srcUnit, $inUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the value of a PHP's configuration option to a string.
     *
     * @param  string $optionName The name of the option.
     * @param  string $optionValue The option's new value.
     *
     * @return void
     */

    public static function setConfigOption ($optionName, $optionValue)
    {
        assert( 'is_cstring($optionName) && is_cstring($optionValue)', vs(isset($this), get_defined_vars()) );

        $res = ini_set($optionName, $optionValue);
        assert( 'is_cstring($res)', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the value of a PHP's configuration option to a boolean value.
     *
     * @param  string $optionName The name of the option.
     * @param  bool $optionValue The option's new value.
     *
     * @return void
     */

    public static function setConfigOptionBool ($optionName, $optionValue)
    {
        assert( 'is_cstring($optionName) && is_bool($optionValue)', vs(isset($this), get_defined_vars()) );
        self::setConfigOption($optionName, CString::fromBool10($optionValue));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the value of a PHP's configuration option to an integer value.
     *
     * @param  string $optionName The name of the option.
     * @param  int $optionValue The option's new value.
     *
     * @return void
     */

    public static function setConfigOptionInt ($optionName, $optionValue)
    {
        assert( 'is_cstring($optionName) && is_int($optionValue)', vs(isset($this), get_defined_vars()) );
        self::setConfigOption($optionName, CString::fromInt($optionValue));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the value of a PHP's configuration option to a floating-point value.
     *
     * @param  string $optionName The name of the option.
     * @param  float $optionValue The option's new value.
     *
     * @return void
     */

    public static function setConfigOptionFloat ($optionName, $optionValue)
    {
        assert( 'is_cstring($optionName) && is_float($optionValue)', vs(isset($this), get_defined_vars()) );
        self::setConfigOption($optionName, CString::fromFloat($optionValue));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Restores a PHP's configuration option to its original value.
     *
     * @param  string $optionName The name of the option.
     *
     * @return void
     */

    public static function restoreConfigOption ($optionName)
    {
        assert( 'is_cstring($optionName)', vs(isset($this), get_defined_vars()) );
        ini_restore($optionName);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the script is running in the CLI mode.
     *
     * @return bool `true` if the script is running in the CLI mode, `false` if not.
     */

    public static function isInCliMode ()
    {
        return CString::equalsCi(PHP_SAPI, "cli");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the hostname of the local server.
     *
     * The returned value is usually different for each virtual host.
     *
     * @return CUStringObject The hostname of the local server.
     */

    public static function hostName ()
    {
        return $_SERVER["SERVER_NAME"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the IP address of the local server.
     *
     * @return CUStringObject The IP address of the local server.
     */

    public static function hostIp ()
    {
        return $_SERVER["SERVER_ADDR"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the name of the front-end software used to handle HTTP requests on the server.
     *
     * @return CUStringObject The name of the HTTP server.
     */

    public static function httpServerName ()
    {
        return $_SERVER["SERVER_SOFTWARE"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the version of the CGI specification used by the HTTP server.
     *
     * @return CUStringObject The version of the CGI specification used by the HTTP server.
     */

    public static function gatewayInterface ()
    {
        return $_SERVER["GATEWAY_INTERFACE"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the used version of PHP language.
     *
     * @return CUStringObject The used version of PHP language.
     */

    public static function langVersion ()
    {
        return PHP_VERSION;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the name of the PHP's Server API.
     *
     * @return CUStringObject The name of the PHP's Server API.
     */

    public static function langSapi ()
    {
        return PHP_SAPI;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the "phpinfo" about the used installation of PHP.
     *
     * @return CUStringObject The "phpinfo" about the used installation of PHP.
     */

    public static function langInfo ()
    {
        return phpinfo();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path to the PHP's executable.
     *
     * @return CUStringObject The path to the PHP's executable.
     */

    public static function langExeBinaryFp ()
    {
        return PHP_BINARY;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the name of the operating system PHP was built on.
     *
     * @return CUStringObject The name of the operating system PHP was built on.
     */

    public static function osName ()
    {
        return PHP_OS;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns information about the operating system PHP is running on.
     *
     * @return CUStringObject Information about the operating system PHP is running on.
     */

    public static function osInfo ()
    {
        return php_uname();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function initializeFramework ()
    {
        $currEnv;
        if ( !$GLOBALS["PHRED_TESTS"] )
        {
            // Try to get the name of the environment in which the application is currently running without the risk of
            // encountering an error or triggering an assertion, which could otherwise reveal sensitive debugging
            // information if "display_errors" happens to be enabled in php.ini.
            $appConfigFp = $GLOBALS["PHRED_PATH_TO_APP"] . "/Configuration" . "/Application.json";
            if ( file_exists($appConfigFp) )
            {
                $appConfig = file_get_contents($appConfigFp);
                if ( is_string($appConfig) )
                {
                    $matches;
                    $res = preg_match("/^\\h*\"environment\"\\s*:\\s*\"(\\w+)\"/m", $appConfig, $matches);
                    if ( $res === 1 )
                    {
                        $currEnv = $matches[1];
                    }
                }
            }
        }
        else
        {
            $currEnv = "tst";
        }

        if ( isset($currEnv) )
        {
            // Based on the current environment, set some debugging options to temporary values for the time while the
            // configuration is not yet read.
            if ( strcasecmp($currEnv, "dev") == 0 )
            {
                // Development.
                ini_set("display_errors", "On");
                CDebug::setAssertionsLevel1(true);
                CDebug::setAssertionsLevel2(false);
            }
            else if ( strcasecmp($currEnv, "pro") == 0 )
            {
                // Production.
                ini_set("display_errors", "Off");
                CDebug::setAssertionsLevel1(false);
                CDebug::setAssertionsLevel2(false);
            }
            else if ( strcasecmp($currEnv, "tst") == 0 )
            {
                // Testing.
                ini_set("display_errors", "On");
                CDebug::setAssertionsLevel1(true);
                CDebug::setAssertionsLevel2(true);
            }
            else
            {
                // Unknown environment.
                error_reporting(E_ALL);
                ini_set("display_errors", "On");
            }
        }
        else
        {
            error_reporting(E_ALL);
            ini_set("display_errors", "On");
            trigger_error("Could not read the name of the current environment.", E_USER_ERROR);
        }

        // Read all the configuration options.
        CConfiguration::initialize();

        // Set whether error messages should be shown is the output.
        if ( CConfiguration::option("debug.displayErrors") )
        {
            ini_set("display_errors", "On");
        }
        else
        {
            ini_set("display_errors", "Off");
        }

        // Set the error reporting level.
        $errorReportingLevel = constant(CConfiguration::option("debug.errorReportingLevel"));
        error_reporting($errorReportingLevel);

        // Process the configuration options that are related to debugging.

        $debug = CConfiguration::option("debug");

        // Assertions and the conditions on which they get active.
        $assertionsAreActive = false;
        $assertionsAreEnabled = $debug["enableAssertions"];
        if ( $assertionsAreEnabled )
        {
            $assertionsAreActiveBasedOn = $debug["assertionsAreActiveBasedOn"];
            if ( CString::equalsCi($assertionsAreActiveBasedOn, "always") )
            {
                // Always.
                $assertionsAreActive = true;
            }
            else  // on a time condition
            {
                // Reference time zone.
                $refTimeZone;
                $timeZoneName = $debug["referenceTimeZone"];
                if ( !CString::isEmpty($timeZoneName) )
                {
                    $refTimeZone = new CTimeZone($timeZoneName);
                }
                else
                {
                    $refTimeZone = CTimeZone::makeUtc();
                }

                if ( CString::equalsCi($assertionsAreActiveBasedOn, "hour") )
                {
                    // Current time.
                    $currTime = CTime::now();

                    // Hour.
                    $hourRanges = $debug["assertionsAreActiveWithinHourRange"];
                    $currHour = $currTime->hourInTimeZone($refTimeZone);
                    if ( !is_carray($hourRanges[0]) )
                    {
                        $hourRanges = CArray::fromElements($hourRanges);
                    }
                    $len = CArray::length($hourRanges);
                    for ($i = 0; $i < $len; $i++)
                    {
                        $range = $hourRanges[$i];
                        assert( 'is_int($range[0]) && is_int($range[1])', vs(isset($this), get_defined_vars()) );
                        if ( $range[0] <= $currHour && $currHour <= $range[1] )
                        {
                            $assertionsAreActive = true;
                            break;
                        }
                    }
                }
                else if ( CString::equalsCi($assertionsAreActiveBasedOn, "dayOfWeek") )
                {
                    // Current time.
                    $currTime = CTime::now();

                    // Day of week.
                    $daysOfWeek = $debug["assertionsAreActiveOnDaysOfWeek"];
                    if ( !is_carray($daysOfWeek) )
                    {
                        $daysOfWeek = CArray::fromElements($daysOfWeek);
                    }
                    $currDayOfWeek = $currTime->dayOfWeekInTimeZone($refTimeZone);
                    $currDayOfWeekShort;
                    switch ( $currDayOfWeek )
                    {
                    case CTime::SUNDAY:
                        $currDayOfWeekShort = "sun";
                        break;
                    case CTime::MONDAY:
                        $currDayOfWeekShort = "mon";
                        break;
                    case CTime::TUESDAY:
                        $currDayOfWeekShort = "tue";
                        break;
                    case CTime::WEDNESDAY:
                        $currDayOfWeekShort = "wed";
                        break;
                    case CTime::THURSDAY:
                        $currDayOfWeekShort = "thu";
                        break;
                    case CTime::FRIDAY:
                        $currDayOfWeekShort = "fri";
                        break;
                    case CTime::SATURDAY:
                        $currDayOfWeekShort = "sat";
                        break;
                    }
                    $len = CArray::length($daysOfWeek);
                    for ($i = 0; $i < $len; $i++)
                    {
                        $dow = $daysOfWeek[$i];
                        assert( '!CString::isEmpty($dow)', vs(isset($this), get_defined_vars()) );
                        if ( CString::equalsCi($currDayOfWeekShort, $dow) )
                        {
                            $assertionsAreActive = true;
                            break;
                        }
                    }
                }
                else if ( CString::equalsCi($assertionsAreActiveBasedOn, "dayOfYear") )
                {
                    // Day of year.
                    $multiplier = $debug["assertionsAreActiveOnEveryDayOfYearMultipleOf"];
                    assert( 'is_int($multiplier)', vs(isset($this), get_defined_vars()) );
                    $currDayOfYear = CTime::currentDayOfYearInTimeZone($refTimeZone);
                    if ( $currDayOfYear % $multiplier == 0 )
                    {
                        $assertionsAreActive = true;
                    }
                }
                else
                {
                    assert( 'false', vs(isset($this), get_defined_vars()) );
                }
            }
        }
        if ( $assertionsAreActive )
        {
            // Enable level 1 assertions.
            CDebug::setAssertionsLevel1(true);

            $enableAssertionsLevel2 = $debug["enableAssertionsLevel2"];
            if ( $enableAssertionsLevel2 )
            {
                // Enable level 2 assertions.
                CDebug::setAssertionsLevel2(true);
            }
        }
        else
        {
            CDebug::setAssertionsLevel1(false);
            CDebug::setAssertionsLevel2(false);
        }

        // Logging.
        $logging = $debug["logging"];
        $loggingIsEnabled = $logging["enable"];
        if ( $loggingIsEnabled )
        {
            $logFp = $logging["logFilePath"];
            assert( '!CString::isEmpty($logFp)', vs(isset($this), get_defined_vars()) );
            $logFp = CFilePath::frameworkPath($logFp);
            CDebug::setLogging($logFp);
        }

        // Mailing.
        $mailing = $debug["mailing"];
        $mailingIsEnabled = $mailing["enable"];
        if ( $mailingIsEnabled )
        {
            $adminMail = CConfiguration::option("admin.mail");
            $to = $adminMail["to"];
            $from = $adminMail["from"];
            $transport = $adminMail["transport"];
            assert( '!CString::isEmpty($to) && !CString::isEmpty($from) && !CString::isEmpty($transport)',
                vs(isset($this), get_defined_vars()) );

            $minTimeBetweenSendMailHours = $mailing["minTimeBetweenSendMailHours"];
            assert( 'is_int($minTimeBetweenSendMailHours)', vs(isset($this), get_defined_vars()) );

            $mail;
            if ( CString::equalsCi($transport, "smtp") )
            {
                $smtpOutgoingServer = $adminMail["smtpOutgoingServer"];
                $smtpUsername = $adminMail["smtpUsername"];
                $smtpPassword = $adminMail["smtpPassword"];
                assert( '!CString::isEmpty($smtpOutgoingServer) && !CString::isEmpty($smtpUsername) && ' .
                        '!CString::isEmpty($smtpPassword)', vs(isset($this), get_defined_vars()) );
                $mail = CMail::makeSmtp($smtpOutgoingServer, $smtpUsername, $smtpPassword, $from, $to);
            }
            else if ( CString::equalsCi($transport, "system") )
            {
                $mail = CMail::makeSystem($from, $to);
            }
            else
            {
                assert( 'false', vs(isset($this), get_defined_vars()) );
            }
            CDebug::setMailing($mail, $minTimeBetweenSendMailHours);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function maybeUpdateThirdParty ()
    {
        if ( !self::isInCliMode() )
        {
            // This method can be run in CLI mode only.
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return false;
        }

        $updates = CConfiguration::option("updates");
        $updatesAreEnabled = $updates["enable"];
        if ( $updatesAreEnabled )
        {
            $minTimeBetweenDoUpdatesDays = $updates["minTimeBetweenDoUpdatesDays"];
            $components = $updates["components"];
            assert( 'is_int($minTimeBetweenDoUpdatesDays)', vs(isset($this), get_defined_vars()) );

            // Logging.
            $logging = $updates["logging"];
            $loggingIsEnabled = $logging["enable"];
            $logFp = $logging["logFilePath"];
            if ( $loggingIsEnabled )
            {
                assert( '!CString::isEmpty($logFp)', vs(isset($this), get_defined_vars()) );
                $logFp = CFilePath::frameworkPath($logFp);
                CShell::setLogging($logFp);
            }

            // Mailing.
            $mailing = $updates["mailing"];
            $mailingIsEnabled = $mailing["enable"];
            if ( $mailingIsEnabled )
            {
                $adminMail = CConfiguration::option("admin.mail");
                $to = $adminMail["to"];
                $from = $adminMail["from"];
                $transport = $adminMail["transport"];
                assert( '!CString::isEmpty($to) && !CString::isEmpty($from) && !CString::isEmpty($transport)',
                    vs(isset($this), get_defined_vars()) );

                $mail;
                if ( CString::equalsCi($transport, "smtp") )
                {
                    $smtpOutgoingServer = $adminMail["smtpOutgoingServer"];
                    $smtpUsername = $adminMail["smtpUsername"];
                    $smtpPassword = $adminMail["smtpPassword"];
                    assert( '!CString::isEmpty($smtpOutgoingServer) && !CString::isEmpty($smtpUsername) && ' .
                            '!CString::isEmpty($smtpPassword)', vs(isset($this), get_defined_vars()) );
                    $mail = CMail::makeSmtp($smtpOutgoingServer, $smtpUsername, $smtpPassword, $from, $to);
                }
                else if ( CString::equalsCi($transport, "system") )
                {
                    $mail = CMail::makeSystem($from, $to);
                }
                else
                {
                    assert( 'false', vs(isset($this), get_defined_vars()) );
                }
                CShell::setMailing($mail);
            }

            $thirdPartyDp = $GLOBALS["PHRED_PATH_TO_THIRD_PARTY"];
            $lastUpdateTimeFp = CFilePath::add($thirdPartyDp, self::$ms_thirdPartyLastUpdateTimeFn);

            // Read the file containing the Unix seconds of the last update time stamp (if exists) and compare that
            // time with the current time.
            $numDaysSinceLastUpdate;
            if ( CFile::exists($lastUpdateTimeFp) )
            {
                $lastUpdateTime = new CTime(CString::toInt(CFile::read($lastUpdateTimeFp)));
                $currTime = CTime::now();
                if ( $lastUpdateTime->isBefore($currTime) )
                {
                    $numDaysSinceLastUpdate = $currTime->diffInDays($lastUpdateTime);
                    if ( $numDaysSinceLastUpdate < $minTimeBetweenDoUpdatesDays )
                    {
                        // It is too early for updates yet.
                        return false;
                    }
                }
                else
                {
                    assert( 'false', vs(isset($this), get_defined_vars()) );
                }
            }

            $date = CShell::currentDate();
            CShell::say("Started on $date.");

            if ( isset($numDaysSinceLastUpdate) )
            {
                CShell::say("It has been $numDaysSinceLastUpdate day(s) since last successful update.");
            }

            $concurrLockFp = CFilePath::add($thirdPartyDp, self::$ms_thirdPartyConcurrLockFn);

            // Try locking the operation.
            if ( !self::setLock($concurrLockFp, false) )
            {
                assert( 'false', vs(isset($this), get_defined_vars()) );
                CShell::onError(false, "Could not obtain a lock on the operation.");
                CShell::writeToLog("\n");
                return false;
            }

            $phpConfigNeedsReload = false;
            $totalNumComponents = CMap::length($components);
            $numComponentsUpdated = 0;

            // The Browser Capabilities Project (BrowsCap).
            if ( CMap::hasKey($components, "browsCap") )
            {
                $browsCap = $components["browsCap"];

                $skip = $browsCap["skip"];
                if ( !$skip )
                {
                    CShell::say("Updating the Browser Capabilities Project (BrowsCap) ...");

                    $lookupFileUrl = $browsCap["lookupFileUrl"];
                    assert( '!CString::isEmpty($lookupFileUrl)', vs(isset($this), get_defined_vars()) );

                    // Component-related constants.
                    static $s_configOptName = "browscap";
                    static $s_lookupFileDownloadTimeoutSeconds = 120;

                    if ( self::hasConfigOption($s_configOptName) )
                    {
                        $browsCapLookupFp = CString::trim(self::configOption($s_configOptName));
                        if ( !CString::isEmpty($browsCapLookupFp) )
                        {
                            $browsCapDp = CFilePath::directory($browsCapLookupFp);

                            CShell::say("Downloading a BrowsCap lookup file from '$lookupFileUrl' ...");

                            $temporaryFp = CFile::createTemporary($browsCapDp);
                            $downloadRes = CInetRequest::downloadFile($lookupFileUrl, $temporaryFp,
                                $s_lookupFileDownloadTimeoutSeconds);
                            if ( $downloadRes )
                            {
                                // After the file is downloaded into a temporary one, move it to the destination,
                                // safely replacing the existing file, if any.
                                CFile::move($temporaryFp, $browsCapLookupFp);
                                $numComponentsUpdated++;
                                $phpConfigNeedsReload = true;

                                $downloadedFileSizeKB = CUUnit::convertStoragef(
                                    (float)CFile::size($browsCapLookupFp), CUUnit::BYTE, CUUnit::KILOBYTE);
                                $downloadedFileSizeKB = CMathf::round($downloadedFileSizeKB, 2);
                                CShell::say("Done. The downloaded file is $downloadedFileSizeKB KB in size.");
                            }
                            else
                            {
                                CShell::onError(false,
                                    "Could not download a BrowsCap lookup file from '$lookupFileUrl'.");
                            }

                            // Just in case, check for any temporary files that could have been left by any previous
                            // operations in the directory.
                            $leftoverFiles = CFile::findFiles(CFilePath::add($browsCapDp,
                                CFile::DEFAULT_TEMPORARY_FILE_PREFIX . "*"));
                            if ( !CArray::isEmpty($leftoverFiles) )
                            {
                                // Cleanup the directory from the temporary files.
                                $len = CArray::length($leftoverFiles);
                                for ($i = 0; $i < $len; $i++)
                                {
                                    CFile::delete($leftoverFiles[$i]);
                                }
                            }
                        }
                        else
                        {
                            CShell::onError(false, "Could not read the value of '$s_configOptName' option " .
                                "in the PHP CLI configuration file.");
                        }
                    }
                    else
                    {
                        CShell::onError(false,
                            "Could not find '$s_configOptName' option in the PHP CLI configuration file.");
                    }
                }
                else
                {
                    CShell::say("Skipping the Browser Capabilities Project (BrowsCap).");
                }
            }

            // All the components have been processed. Unlock the operation.
            self::unsetLock($concurrLockFp);

            $date = CShell::currentDate();
            if ( $numComponentsUpdated != 0 )
            {
                // One or more third-party components have been updated. Put a time stamp on the directory where the
                // components are located.
                CFile::write($lastUpdateTimeFp, CString::fromInt(CTime::currentUTime()));

                if ( $numComponentsUpdated == $totalNumComponents )
                {
                    CShell::speak("Success. All $totalNumComponents third-party component(s)");
                }
                else
                {
                    CShell::speak(
                        "Partial success. $numComponentsUpdated out of $totalNumComponents third-party component(s)");
                }
                CShell::say("have been updated. Completed on $date.");
            }
            else
            {
                CShell::say("No third-party components have been updated. Completed on $date.");
            }

            return $phpConfigNeedsReload;
        }
        else
        {
            return false;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function onThirdPartyUpdateByPackageManager ()
    {
        if ( !self::isInCliMode() )
        {
            // This method can be run in CLI mode only.
            assert( 'false', vs(isset($this), get_defined_vars()) );
        }

        $timeoutPause = new CTimeoutPause();

        CShell::speak("Processing third-party components ...");

        $tpDps = CFile::listDirectories(CFilePath::absolute($GLOBALS["PHRED_PATH_TO_THIRD_PARTY"]));
        $ignorePackages = CConfiguration::option("upd.thirdPartyOopWrappingIgnorePackages");
        $ignorePackagesL2 = CArray::filter($ignorePackages, function ($package)
            {
                return CString::find($package, "/");
            });
        $newTpDps = CArray::make();
        $len = CArray::length($tpDps);
        for ($i = 0; $i < $len; $i++)
        {
            $tpDp = $tpDps[$i];

            $dirName = CFilePath::name($tpDp);
            if ( !CArray::find($ignorePackages, $dirName) )
            {
                $dpHasL2DirsToIgnore = CArray::find($ignorePackagesL2, $dirName, function ($packageL2, $dirName)
                    {
                        return CString::equals(CFilePath::directory($packageL2), $dirName);
                    });
                if ( !$dpHasL2DirsToIgnore )
                {
                    CArray::push($newTpDps, $tpDp);
                }
                else
                {
                    $tpSubDps = CFile::listDirectories($tpDp);
                    $tpSubDps = CArray::filter($tpSubDps, function ($subDp) use ($ignorePackagesL2)
                        {
                            return !CArray::find($ignorePackagesL2, $subDp, function ($packageL2, $subDp)
                                {
                                    return CString::endsWith($subDp, $packageL2);
                                });
                        });
                    CArray::pushArray($newTpDps, $tpSubDps);
                }
            }
        }
        $tpDps = $newTpDps;

        $wrapProtectedMethods = CConfiguration::option("upd.thirdPartyOopWrappingInProtectedMethods");
        $wrapPrivateMethods = CConfiguration::option("upd.thirdPartyOopWrappingInPrivateMethods");

        static $s_stdPhpTag = "<?php";

        static $s_progressResolution = 0.05;

        $prevProgressDivR = 0;
        $tpDpsLen = CArray::length($tpDps);
        for ($i0 = 0; $i0 < $tpDpsLen; $i0++)
        {
            $tpFps = CFile::reFindFilesRecursive($tpDps[$i0], "/\\.php\\d?\\z/");

            $tpFpsLen = CArray::length($tpFps);
            for ($i1 = 0; $i1 < $tpFpsLen; $i1++)
            {
                $fileCode = CFile::read($tpFps[$i1]);

                if ( !CString::find($fileCode, self::$ms_thirdPartyAlreadyOopWrappedMark) )
                {
                    $parser = new PhpParser\Parser(new PhpParser\Lexer());
                    try
                    {
                        // Parse the code.
                        $statements = $parser->parse($fileCode);

                        // Wrap the code into OOP.
                        $traverser = new PhpParser\NodeTraverser();
                        $mainVisitor = new CMainVisitor($wrapProtectedMethods, $wrapPrivateMethods);
                        $traverser->addVisitor($mainVisitor);
                        $statements = $traverser->traverse($statements);
                        $wrappedCode = (new PhpParser\PrettyPrinter\Standard())->prettyPrint($statements);

                        $phpTagPos = CString::indexOf($wrappedCode, $s_stdPhpTag);
                        if ( $phpTagPos == -1 )
                        {
                            $wrappedCode = "$s_stdPhpTag\n\n$wrappedCode";
                            $phpTagPos = 0;
                        }
                        $wrappedCode = CString::insert($wrappedCode, $phpTagPos + CString::length($s_stdPhpTag),
                            "\n\n" . self::$ms_thirdPartyAlreadyOopWrappedMark);

                        // Save.
                        CFile::write($tpFps[$i1], $wrappedCode);
                    }
                    catch (PhpParser\Error $parserError)
                    {
                        CShell::say("\nPhpParser: " . $tpFps[$i1] . ", at line " . $parserError->getRawLine() . ": " .
                            $parserError->getRawMessage());
                    }
                }

                $progress = (float)($i0/$tpDpsLen + 1/$tpDpsLen*$i1/$tpFpsLen);
                $progressDivR = CMathi::floor($progress/$s_progressResolution);
                if ( $progressDivR != $prevProgressDivR )
                {
                    $perc = CMathi::round($progressDivR*$s_progressResolution*100);
                    CShell::speak("$perc%");
                }
                $prevProgressDivR = $progressDivR;
            }
        }
        CShell::speak("100%");

        CShell::say("Done.");

        $timeoutPause->end();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function storageUnitFromOptionValue ($optionValue)
    {
        $optionValue = CString::toLowerCase($optionValue);
        if ( CString::endsWith($optionValue, "k") )
        {
            return CUUnit::KILOBYTE;
        }
        if ( CString::endsWith($optionValue, "m") )
        {
            return CUUnit::MEGABYTE;
        }
        if ( CString::endsWith($optionValue, "g") )
        {
            return CUUnit::GIGABYTE;
        }
        if ( CString::endsWith($optionValue, "t") )
        {
            return CUUnit::TERABYTE;
        }
        return CUUnit::BYTE;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected static $ms_fhFromLockFp;

    // Defaults.
    protected static $ms_thirdPartyLastUpdateTimeFn = "phred-last-update-time";
    protected static $ms_thirdPartyConcurrLockFn = "phred-update-lock";
    protected static $ms_thirdPartyAlreadyOopWrappedMark =
        "/*\n * The code in this file has been wrapped into OOP with PhpParser.\n * github.com/nikic/PHP-Parser\n */";
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Support for `onThirdPartyUpdateByPackageManager` method.

/**
 * @ignore
 */
class CMainVisitor extends PhpParser\NodeVisitorAbstract
{
    public function __construct ($wrapProtectedMethods, $wrapPrivateMethods)
    {
        $this->m_wrapProtectedMethods = $wrapProtectedMethods;
        $this->m_wrapPrivateMethods = $wrapPrivateMethods;
    }

    public function enterNode (PhpParser\Node $node)
    {
        if ( $node instanceof PhpParser\Node\Stmt\Class_ ||
             $node instanceof PhpParser\Node\Stmt\Trait_ )
        {
            $this->m_numEnteredClassesOrTraits++;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Interface_ )
        {
            $this->m_numEnteredInterfaces++;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\ClassMethod )
        {
            $this->m_numEnteredMethods++;
        }
        else if ( $node instanceof PhpParser\Node\Expr\Closure )
        {
            $this->m_numEnteredClosures++;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Function_ )
        {
            $this->m_numEnteredFunctions++;
        }
    }

    public function leaveNode (PhpParser\Node $node)
    {
        if ( $node instanceof PhpParser\Node\Stmt\Class_ ||
             $node instanceof PhpParser\Node\Stmt\Trait_ )
        {
            $numEnteredClassesOrTraits = $this->m_numEnteredClassesOrTraits;
            $this->m_numEnteredClassesOrTraits--;

            if ( !($numEnteredClassesOrTraits == 1 &&
                   $this->m_numEnteredInterfaces == 0 &&
                   $this->m_numEnteredMethods == 0 &&
                   $this->m_numEnteredClosures == 0 &&
                   $this->m_numEnteredFunctions == 0) )
            {
                return;
            }

            $classOrTrait = $node;

            $statements = [$classOrTrait];

            $propertyVisitor = new CPropertyVisitor();
            $traverser = new PhpParser\NodeTraverser();
            $traverser->addVisitor($propertyVisitor);
            $statements = $traverser->traverse($statements);

            $classOrTrait = $statements[0];

            $methodVisitor = new CMethodVisitor($this->m_wrapProtectedMethods, $this->m_wrapPrivateMethods,
                $classOrTrait instanceof PhpParser\Node\Stmt\Trait_);
            $traverser = new PhpParser\NodeTraverser();
            $traverser->addVisitor($methodVisitor);
            $statements = $traverser->traverse($statements);

            $classOrTrait = $statements[0];

            if ( isset($classOrTrait->stmts) && !CMap::isEmpty($classOrTrait->stmts) &&
                 $propertyVisitor->hasPropsToWrap() )
            {
                $getSetMMethodModifVisitor = new CGetSetMMethodModifVisitor($propertyVisitor->propsToWrap());
                $traverser = new PhpParser\NodeTraverser();
                $traverser->addVisitor($getSetMMethodModifVisitor);
                $statements = $traverser->traverse($statements);

                $classOrTrait = $statements[0];

                if ( !$propertyVisitor->hasGetMMethod() ||
                     !$propertyVisitor->hasSetMMethod() )
                {
                    $precStmt;
                    $lastMethodFound = false;
                    $lenMinusOne = CMap::length($classOrTrait->stmts) - 1;
                    for ($i = $lenMinusOne; $i >= 0; $i--)
                    {
                        $stmt = $classOrTrait->stmts[$i];

                        if ( $stmt instanceof PhpParser\Node\Stmt\ClassMethod )
                        {
                            $lastMethodFound = true;
                            $precStmt = $stmt;
                            break;
                        }
                    }
                    if ( !$lastMethodFound )
                    {
                        $precStmt = $classOrTrait->stmts[$lenMinusOne];
                    }

                    if ( !$propertyVisitor->hasGetMMethod() )
                    {
                        $precStmt->setAttribute("_insertGetMMethodAfterMe", true);
                    }
                    if ( !$propertyVisitor->hasSetMMethod() )
                    {
                        $precStmt->setAttribute("_insertSetMMethodAfterMe", true);
                    }

                    $getSetMMethodAddingVisitor = new CGetSetMMethodAddingVisitor($propertyVisitor->propsToWrap());
                    $traverser = new PhpParser\NodeTraverser();
                    $traverser->addVisitor($getSetMMethodAddingVisitor);
                    $statements = $traverser->traverse($statements);
                }
            }

            return $statements;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Interface_ )
        {
            $this->m_numEnteredInterfaces--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\ClassMethod )
        {
            $this->m_numEnteredMethods--;
        }
        else if ( $node instanceof PhpParser\Node\Expr\Closure )
        {
            $this->m_numEnteredClosures--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Function_ )
        {
            $numEnteredFunctions = $this->m_numEnteredFunctions;
            $this->m_numEnteredFunctions--;

            if ( !($this->m_numEnteredClassesOrTraits == 0 &&
                   $this->m_numEnteredInterfaces == 0 &&
                   $this->m_numEnteredMethods == 0 &&
                   $this->m_numEnteredClosures == 0 &&
                   $numEnteredFunctions == 1) )
            {
                return;
            }

            $function = $node;

            if ( !isset($function->stmts) || CMap::isEmpty($function->stmts) )
            {
                return;
            }

            $hasParams = false;
            $params = CArray::make();
            $hasParamsByRef = false;
            $paramsByRef = CArray::make();
            if ( isset($function->params) && !CMap::isEmpty($function->params) )
            {
                $hasParams = true;
                $params = CArray::fromPArray($function->params);
                $paramsByRef = CArray::filter($params, function ($param)
                    {
                        return $param->byRef;
                    });
                $hasParamsByRef = !CArray::isEmpty($paramsByRef);
            }

            $function->stmts[0]->setAttribute("_imFirstStmtInMethodOrFunction", true);
            $function->stmts[CMap::length($function->stmts) - 1]->setAttribute("_imLastStmtInMethodOrFunction", true);

            $statements = [$function];

            $functionFirstLastStmtVisitor = new CMethodOrFunctionFirstLastStmtVisitor($hasParams, $params,
                $hasParamsByRef, $paramsByRef);
            $traverser = new PhpParser\NodeTraverser();
            $traverser->addVisitor($functionFirstLastStmtVisitor);
            $statements = $traverser->traverse($statements);

            $function = $statements[0];

            $returnVisitor = new CReturnVisitor($hasParams, $params, $hasParamsByRef, $paramsByRef,
                $function->byRef, CReturnVisitor::SUBJ_FUNCTION);
            $traverser = new PhpParser\NodeTraverser();
            $traverser->addVisitor($returnVisitor);
            $statements = $traverser->traverse($statements);

            return $statements;
        }
    }

    protected $m_wrapProtectedMethods;
    protected $m_wrapPrivateMethods;

    protected $m_numEnteredClassesOrTraits = 0;
    protected $m_numEnteredInterfaces = 0;
    protected $m_numEnteredMethods = 0;
    protected $m_numEnteredClosures = 0;
    protected $m_numEnteredFunctions = 0;

    protected static $ms_isFwCallFuncName = "_is_non_tp_call";
    protected static $ms_fromOopFuncName = "_from_oop_tp";
    protected static $ms_toOopFuncName = "_to_oop_tp";
    protected static $ms_isFwCallVarName = "_is_non_tp_call_v";
    protected static $ms_preToOopReturnVarName = "_pre_to_oop_r_v";
    protected static $ms_byRefGVarName = "_dummy_by_ref";
}

/**
 * @ignore
 */
class CPropertyVisitor extends CMainVisitor
{
    public function __construct ()
    {
        $this->m_propsToWrap = CArray::make();
    }

    public function leaveNode (PhpParser\Node $node)
    {
        if ( $node instanceof PhpParser\Node\Stmt\Class_ ||
             $node instanceof PhpParser\Node\Stmt\Trait_ )
        {
            $this->m_numEnteredClassesOrTraits--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Interface_ )
        {
            $this->m_numEnteredInterfaces--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\ClassMethod )
        {
            $numEnteredMethods = $this->m_numEnteredMethods;
            $this->m_numEnteredMethods--;

            if ( !($this->m_numEnteredClassesOrTraits == 1 &&
                   $this->m_numEnteredInterfaces == 0 &&
                   $numEnteredMethods == 1 &&
                   $this->m_numEnteredClosures == 0 &&
                   $this->m_numEnteredFunctions == 0) )
            {
                return;
            }

            $method = $node;

            if ( CString::equalsCi($method->name, "__get") )
            {
                $this->m_hasGetMMethod = true;
            }
            else if ( CString::equalsCi($method->name, "__set") )
            {
                $this->m_hasSetMMethod = true;
            }
        }
        else if ( $node instanceof PhpParser\Node\Expr\Closure )
        {
            $this->m_numEnteredClosures--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Function_ )
        {
            $this->m_numEnteredFunctions--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Property )
        {
            if ( !($this->m_numEnteredClassesOrTraits == 1 &&
                   $this->m_numEnteredInterfaces == 0 &&
                   $this->m_numEnteredMethods == 0 &&
                   $this->m_numEnteredClosures == 0 &&
                   $this->m_numEnteredFunctions == 0) )
            {
                return;
            }

            $property = $node;

            if ( $property->isProtected() ||
                 $property->isPrivate() ||
                 $property->isStatic() )
            {
                return;
            }

            if ( !isset($property->props) || CMap::isEmpty($property->props) )
            {
                return;
            }

            foreach ($property->props as $prop)
            {
                CArray::push($this->m_propsToWrap, $prop->name);
            }

            $property->type = PhpParser\Node\Stmt\Class_::MODIFIER_PROTECTED;
            return $property;
        }
    }

    public function hasPropsToWrap ()
    {
        return !CArray::isEmpty($this->m_propsToWrap);
    }

    public function propsToWrap ()
    {
        return $this->m_propsToWrap;
    }

    public function hasGetMMethod ()
    {
        return $this->m_hasGetMMethod;
    }

    public function hasSetMMethod ()
    {
        return $this->m_hasSetMMethod;
    }

    private $m_propsToWrap;
    private $m_hasGetMMethod = false;
    private $m_hasSetMMethod = false;
}

/**
 * @ignore
 */
class CMethodVisitor extends CMainVisitor
{
    public function __construct ($wrapProtectedMethods, $wrapPrivateMethods, $isTrait)
    {
        parent::__construct($wrapProtectedMethods, $wrapPrivateMethods);
        $this->m_isTrait = $isTrait;
    }

    public function leaveNode (PhpParser\Node $node)
    {
        if ( $node instanceof PhpParser\Node\Stmt\Class_ ||
             $node instanceof PhpParser\Node\Stmt\Trait_ )
        {
            $this->m_numEnteredClassesOrTraits--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Interface_ )
        {
            $this->m_numEnteredInterfaces--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\ClassMethod )
        {
            $numEnteredMethods = $this->m_numEnteredMethods;
            $this->m_numEnteredMethods--;

            if ( !($this->m_numEnteredClassesOrTraits == 1 &&
                   $this->m_numEnteredInterfaces == 0 &&
                   $numEnteredMethods == 1 &&
                   $this->m_numEnteredClosures == 0 &&
                   $this->m_numEnteredFunctions == 0) )
            {
                return;
            }

            $method = $node;

            if ( ($method->isProtected() && !$this->m_wrapProtectedMethods && !$this->m_isTrait) ||
                 ($method->isPrivate() && !$this->m_wrapPrivateMethods && !$this->m_isTrait) ||
                 $method->isAbstract())
            {
                return;
            }

            if ( !isset($method->stmts) || CMap::isEmpty($method->stmts) )
            {
                return;
            }

            $hasParams = false;
            $params = CArray::make();
            $hasParamsByRef = false;
            $paramsByRef = CArray::make();
            if ( isset($method->params) && !CMap::isEmpty($method->params) )
            {
                $hasParams = true;
                $params = CArray::fromPArray($method->params);
                $paramsByRef = CArray::filter($params, function ($param)
                    {
                        return $param->byRef;
                    });
                $hasParamsByRef = !CArray::isEmpty($paramsByRef);
            }

            $method->stmts[0]->setAttribute("_imFirstStmtInMethodOrFunction", true);
            $method->stmts[CMap::length($method->stmts) - 1]->setAttribute("_imLastStmtInMethodOrFunction", true);

            $statements = [$method];

            $methodFirstLastStmtVisitor = new CMethodOrFunctionFirstLastStmtVisitor($hasParams, $params,
                $hasParamsByRef, $paramsByRef);
            $traverser = new PhpParser\NodeTraverser();
            $traverser->addVisitor($methodFirstLastStmtVisitor);
            $statements = $traverser->traverse($statements);

            $method = $statements[0];

            $returnVisitor = new CReturnVisitor($hasParams, $params, $hasParamsByRef, $paramsByRef,
                $method->byRef, CReturnVisitor::SUBJ_METHOD);
            $traverser = new PhpParser\NodeTraverser();
            $traverser->addVisitor($returnVisitor);
            $statements = $traverser->traverse($statements);

            return $statements;
        }
        else if ( $node instanceof PhpParser\Node\Expr\Closure )
        {
            $this->m_numEnteredClosures--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Function_ )
        {
            $this->m_numEnteredFunctions--;
        }
    }

    private $m_isTrait;
}

/**
 * @ignore
 */
class CMethodOrFunctionFirstLastStmtVisitor extends CMainVisitor
{
    public function __construct ($hasParams, $params, $hasParamsByRef, $paramsByRef)
    {
        $this->m_hasParams = $hasParams;
        $this->m_params = $params;
        $this->m_hasParamsByRef = $hasParamsByRef;
        $this->m_paramsByRef = $paramsByRef;
    }

    public function leaveNode (PhpParser\Node $node)
    {
        $statements = [$node];
        $modif = false;

        if ( $node->hasAttribute("_imFirstStmtInMethodOrFunction") )
        {
            $preStatements = CMap::make();
            $assignment = new PhpParser\Node\Expr\Assign(
                new PhpParser\Node\Expr\Variable(self::$ms_isFwCallVarName),
                new PhpParser\Node\Expr\FuncCall(
                new PhpParser\Node\Name(self::$ms_isFwCallFuncName)));
            CMap::insertValue($preStatements, $assignment);
            if ( $this->m_hasParams )
            {
                $preSubStatements = CMap::make();
                $len = CArray::length($this->m_params);
                for ($i = 0; $i < $len; $i++)
                {
                    $param = $this->m_params[$i];

                    $assignment = new PhpParser\Node\Expr\Assign(
                        new PhpParser\Node\Expr\Variable($param->name),
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_fromOopFuncName),
                        [new PhpParser\Node\Expr\Variable($param->name)]));
                    CMap::insertValue($preSubStatements, $assignment);
                }
                $if = new PhpParser\Node\Stmt\If_(
                    new PhpParser\Node\Expr\Variable(self::$ms_isFwCallVarName),
                    ["stmts" => $preSubStatements]);
                CMap::insertValue($preStatements, $if);
            }

            foreach ($statements as $stmt)
            {
                CMap::insertValue($preStatements, $stmt);
            }
            $statements = $preStatements;

            $modif = true;
        }

        if ( $node->hasAttribute("_imLastStmtInMethodOrFunction") &&
             !($node instanceof PhpParser\Node\Stmt\Return_) &&
             $this->m_hasParamsByRef )
        {
            $subStatements = CMap::make();
            $len = CArray::length($this->m_paramsByRef);
            for ($i = 0; $i < $len; $i++)
            {
                $param = $this->m_paramsByRef[$i];

                $assignment = new PhpParser\Node\Expr\Assign(
                    new PhpParser\Node\Expr\Variable($param->name),
                    new PhpParser\Node\Expr\FuncCall(
                    new PhpParser\Node\Name(self::$ms_toOopFuncName),
                    [new PhpParser\Node\Expr\Variable($param->name)]));
                CMap::insertValue($subStatements, $assignment);
            }
            $if = new PhpParser\Node\Stmt\If_(
                new PhpParser\Node\Expr\Variable(self::$ms_isFwCallVarName),
                ["stmts" => $subStatements]);
            CMap::insertValue($statements, $if);

            $modif = true;
        }

        if ( $modif )
        {
            return $statements;
        }
    }

    private $m_hasParams;
    private $m_params;
    private $m_hasParamsByRef;
    private $m_paramsByRef;
}

/**
 * @ignore
 */
class CReturnVisitor extends CMainVisitor
{
    const SUBJ_METHOD = 0;
    const SUBJ_FUNCTION = 1;

    public function __construct ($hasParams, $params, $hasParamsByRef, $paramsByRef, $returnsAreByRef, $subj)
    {
        $this->m_hasParams = $hasParams;
        $this->m_params = $params;
        $this->m_hasParamsByRef = $hasParamsByRef;
        $this->m_paramsByRef = $paramsByRef;
        $this->m_returnsAreByRef = $returnsAreByRef;
        $this->m_subj = $subj;
    }

    public function leaveNode (PhpParser\Node $node)
    {
        if ( $node instanceof PhpParser\Node\Stmt\Class_ ||
             $node instanceof PhpParser\Node\Stmt\Trait_ )
        {
            $this->m_numEnteredClassesOrTraits--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Interface_ )
        {
            $this->m_numEnteredInterfaces--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\ClassMethod )
        {
            $this->m_numEnteredMethods--;
        }
        else if ( $node instanceof PhpParser\Node\Expr\Closure )
        {
            $this->m_numEnteredClosures--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Function_ )
        {
            $this->m_numEnteredFunctions--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Return_ )
        {
            if ( $this->m_subj == self::SUBJ_METHOD )
            {
                if ( !($this->m_numEnteredClassesOrTraits == 0 &&
                       $this->m_numEnteredInterfaces == 0 &&
                       $this->m_numEnteredMethods == 1 &&
                       $this->m_numEnteredClosures == 0 &&
                       $this->m_numEnteredFunctions == 0) )
                {
                    return;
                }
            }
            else  // SUBJ_FUNCTION
            {
                if ( !($this->m_numEnteredClassesOrTraits == 0 &&
                       $this->m_numEnteredInterfaces == 0 &&
                       $this->m_numEnteredMethods == 0 &&
                       $this->m_numEnteredClosures == 0 &&
                       $this->m_numEnteredFunctions == 1) )
                {
                    return;
                }
            }

            $origReturn = $node;

            // `is_null` accounts for any hidden implementation details of `__isset` magic method used by NodeAbstract.
            if ( !isset($origReturn->expr) || is_null($origReturn->expr) )
            {
                // A bare `return`.
                if ( $this->m_hasParamsByRef )
                {
                    $statements = CMap::make();
                    $subStatements = CMap::make();
                    $len = CArray::length($this->m_paramsByRef);
                    for ($i = 0; $i < $len; $i++)
                    {
                        $param = $this->m_paramsByRef[$i];

                        $assignment = new PhpParser\Node\Expr\Assign(
                            new PhpParser\Node\Expr\Variable($param->name),
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_toOopFuncName),
                            [new PhpParser\Node\Expr\Variable($param->name)]));
                        CMap::insertValue($subStatements, $assignment);
                    }
                    $if = new PhpParser\Node\Stmt\If_(
                        new PhpParser\Node\Expr\Variable(self::$ms_isFwCallVarName),
                        ["stmts" => $subStatements]);
                    CMap::insertValue($statements, $if);
                    CMap::insertValue($statements, $origReturn);
                    return $statements;
                }
            }
            else
            {
                // A `return` with an expression.
                if ( !$this->m_returnsAreByRef )
                {
                    if ( !$this->m_hasParamsByRef )
                    {
                        $statements = CMap::make();

                        $subStatements0 = [$origReturn];

                        $subStatements1 = CMap::make();
                        $return = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_toOopFuncName),
                            [$origReturn->expr]));
                        CMap::insertValue($subStatements1, $return);
                        $else = new PhpParser\Node\Stmt\Else_($subStatements1);

                        $if = new PhpParser\Node\Stmt\If_(
                            new PhpParser\Node\Expr\BooleanNot(
                            new PhpParser\Node\Expr\Variable(self::$ms_isFwCallVarName)),
                            ["stmts" => $subStatements0, "else" => $else]);
                        CMap::insertValue($statements, $if);

                        return $statements;
                    }
                    else
                    {
                        $statements = CMap::make();

                        $subStatements0 = [$origReturn];

                        $subStatements1 = CMap::make();
                        $assignment = new PhpParser\Node\Expr\Assign(
                            new PhpParser\Node\Expr\Variable(self::$ms_preToOopReturnVarName),
                            $origReturn->expr);
                        CMap::insertValue($subStatements1, $assignment);
                        $len = CArray::length($this->m_paramsByRef);
                        for ($i = 0; $i < $len; $i++)
                        {
                            $param = $this->m_paramsByRef[$i];

                            $assignment = new PhpParser\Node\Expr\Assign(
                                new PhpParser\Node\Expr\Variable($param->name),
                                new PhpParser\Node\Expr\FuncCall(
                                new PhpParser\Node\Name(self::$ms_toOopFuncName),
                                [new PhpParser\Node\Expr\Variable($param->name)]));
                            CMap::insertValue($subStatements1, $assignment);
                        }
                        $return = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_toOopFuncName),
                            [new PhpParser\Node\Expr\Variable(self::$ms_preToOopReturnVarName)]));
                        CMap::insertValue($subStatements1, $return);
                        $else = new PhpParser\Node\Stmt\Else_($subStatements1);

                        $if = new PhpParser\Node\Stmt\If_(
                            new PhpParser\Node\Expr\BooleanNot(
                            new PhpParser\Node\Expr\Variable(self::$ms_isFwCallVarName)),
                            ["stmts" => $subStatements0, "else" => $else]);
                        CMap::insertValue($statements, $if);

                        return $statements;
                    }
                }
                else
                {
                    if ( !$this->m_hasParamsByRef )
                    {
                        $statements = CMap::make();

                        $subStatements0 = [$origReturn];

                        $subStatements1 = CMap::make();
                        $assignment = new PhpParser\Node\Expr\Assign(
                            new PhpParser\Node\Expr\ArrayDimFetch(
                            new PhpParser\Node\Expr\Variable("GLOBALS"),
                            new PhpParser\Node\Scalar\String(self::$ms_byRefGVarName)),
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_toOopFuncName),
                            [$origReturn->expr]));
                        CMap::insertValue($subStatements1, $assignment);
                        $return = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\ArrayDimFetch(
                            new PhpParser\Node\Expr\Variable("GLOBALS"),
                            new PhpParser\Node\Scalar\String(self::$ms_byRefGVarName)));
                        CMap::insertValue($subStatements1, $return);
                        $else = new PhpParser\Node\Stmt\Else_($subStatements1);

                        $if = new PhpParser\Node\Stmt\If_(
                            new PhpParser\Node\Expr\BooleanNot(
                            new PhpParser\Node\Expr\Variable(self::$ms_isFwCallVarName)),
                            ["stmts" => $subStatements0, "else" => $else]);
                        CMap::insertValue($statements, $if);

                        return $statements;
                    }
                    else
                    {
                        $statements = CMap::make();

                        $subStatements0 = [$origReturn];

                        $subStatements1 = CMap::make();
                        $assignment = new PhpParser\Node\Expr\Assign(
                            new PhpParser\Node\Expr\ArrayDimFetch(
                            new PhpParser\Node\Expr\Variable("GLOBALS"),
                            new PhpParser\Node\Scalar\String(self::$ms_byRefGVarName)),
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_toOopFuncName),
                            [$origReturn->expr]));
                        CMap::insertValue($subStatements1, $assignment);
                        $len = CArray::length($this->m_paramsByRef);
                        for ($i = 0; $i < $len; $i++)
                        {
                            $param = $this->m_paramsByRef[$i];

                            $assignment = new PhpParser\Node\Expr\Assign(
                                new PhpParser\Node\Expr\Variable($param->name),
                                new PhpParser\Node\Expr\FuncCall(
                                new PhpParser\Node\Name(self::$ms_toOopFuncName),
                                [new PhpParser\Node\Expr\Variable($param->name)]));
                            CMap::insertValue($subStatements1, $assignment);
                        }
                        $return = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\ArrayDimFetch(
                            new PhpParser\Node\Expr\Variable("GLOBALS"),
                            new PhpParser\Node\Scalar\String(self::$ms_byRefGVarName)));
                        CMap::insertValue($subStatements1, $return);
                        $else = new PhpParser\Node\Stmt\Else_($subStatements1);

                        $if = new PhpParser\Node\Stmt\If_(
                            new PhpParser\Node\Expr\BooleanNot(
                            new PhpParser\Node\Expr\Variable(self::$ms_isFwCallVarName)),
                            ["stmts" => $subStatements0, "else" => $else]);
                        CMap::insertValue($statements, $if);

                        return $statements;
                    }
                }
            }
        }
    }

    private $m_hasParams;
    private $m_params;
    private $m_hasParamsByRef;
    private $m_paramsByRef;
    private $m_returnsAreByRef;
    private $m_subj;
}

/**
 * @ignore
 */
class CGetSetMMethodModifVisitor extends CMainVisitor
{
    public function __construct ($propsToWrap)
    {
        $this->m_propsToWrap = $propsToWrap;
    }

    public function leaveNode (PhpParser\Node $node)
    {
        if ( $node instanceof PhpParser\Node\Stmt\Class_ ||
             $node instanceof PhpParser\Node\Stmt\Trait_ )
        {
            $this->m_numEnteredClassesOrTraits--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Interface_ )
        {
            $this->m_numEnteredInterfaces--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\ClassMethod )
        {
            $numEnteredMethods = $this->m_numEnteredMethods;
            $this->m_numEnteredMethods--;

            if ( !($this->m_numEnteredClassesOrTraits == 1 &&
                   $this->m_numEnteredInterfaces == 0 &&
                   $numEnteredMethods == 1 &&
                   $this->m_numEnteredClosures == 0 &&
                   $this->m_numEnteredFunctions == 0) )
            {
                return;
            }

            $method = $node;

            if ( CString::equalsCi($method->name, "__get") && CMap::length($method->params) >= 1 )
            {
                $statements = CMap::make();

                $len = CArray::length($this->m_propsToWrap);
                for ($i = 0; $i < $len; $i++)
                {
                    $propName = $this->m_propsToWrap[$i];

                    $subCondition = new PhpParser\Node\Expr\BooleanNot(
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_isFwCallFuncName)));
                    $subIf;
                    if ( !$method->byRef )
                    {
                        $return0 = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\PropertyFetch(
                            new PhpParser\Node\Expr\Variable("this"), $propName));
                        $return1 = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_toOopFuncName),
                            [new PhpParser\Node\Expr\PropertyFetch(
                            new PhpParser\Node\Expr\Variable("this"), $propName)]));
                        $else = new PhpParser\Node\Stmt\Else_([$return1]);
                        $subIf = new PhpParser\Node\Stmt\If_($subCondition,
                            ["stmts" => [$return0], "else" => $else]);
                    }
                    else
                    {
                        $subStatements0 = CMap::make();
                        $return = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\PropertyFetch(
                            new PhpParser\Node\Expr\Variable("this"), $propName));
                        CMap::insertValue($subStatements0, $return);

                        $subStatements1 = CMap::make();
                        $assignment = new PhpParser\Node\Expr\Assign(
                            new PhpParser\Node\Expr\ArrayDimFetch(
                            new PhpParser\Node\Expr\Variable("GLOBALS"),
                            new PhpParser\Node\Scalar\String(self::$ms_byRefGVarName)),
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_toOopFuncName),
                            [new PhpParser\Node\Expr\PropertyFetch(
                            new PhpParser\Node\Expr\Variable("this"), $propName)]));
                        CMap::insertValue($subStatements1, $assignment);
                        $return = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\ArrayDimFetch(
                            new PhpParser\Node\Expr\Variable("GLOBALS"),
                            new PhpParser\Node\Scalar\String(self::$ms_byRefGVarName)));
                        CMap::insertValue($subStatements1, $return);
                        $else = new PhpParser\Node\Stmt\Else_($subStatements1);
                        $subIf = new PhpParser\Node\Stmt\If_($subCondition,
                            ["stmts" => $subStatements0, "else" => $else]);
                    }

                    $condition = new PhpParser\Node\Expr\BinaryOp\Identical(
                        new PhpParser\Node\Expr\Variable($method->params[0]->name),
                        new PhpParser\Node\Scalar\String($propName));
                    $if = new PhpParser\Node\Stmt\If_($condition, ["stmts" => [$subIf]]);
                    CMap::insertValue($statements, $if);
                }

                if ( isset($method->stmts) )
                {
                    foreach ($method->stmts as $stmt)
                    {
                        CMap::insertValue($statements, $stmt);
                    }
                }

                $method->stmts = $statements;
                return $method;
            }
            else if ( CString::equalsCi($method->name, "__set") && CMap::length($method->params) >= 2 )
            {
                $statements = CMap::make();

                $len = CArray::length($this->m_propsToWrap);
                for ($i = 0; $i < $len; $i++)
                {
                    $propName = $this->m_propsToWrap[$i];

                    $subCondition = new PhpParser\Node\Expr\BooleanNot(
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_isFwCallFuncName)));
                    $assignment0 = new PhpParser\Node\Expr\Assign(
                        new PhpParser\Node\Expr\PropertyFetch(
                        new PhpParser\Node\Expr\Variable("this"), $propName),
                        new PhpParser\Node\Expr\Variable($method->params[1]->name));
                    $assignment1 = new PhpParser\Node\Expr\Assign(
                        new PhpParser\Node\Expr\PropertyFetch(
                        new PhpParser\Node\Expr\Variable("this"), $propName),
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_fromOopFuncName),
                        [new PhpParser\Node\Expr\Variable($method->params[1]->name)]));
                    $else = new PhpParser\Node\Stmt\Else_([$assignment1]);
                    $subIf = new PhpParser\Node\Stmt\If_($subCondition,
                        ["stmts" => [$assignment0], "else" => $else]);

                    $condition = new PhpParser\Node\Expr\BinaryOp\Identical(
                        new PhpParser\Node\Expr\Variable($method->params[0]->name),
                        new PhpParser\Node\Scalar\String($propName));
                    $if = new PhpParser\Node\Stmt\If_($condition, ["stmts" => [$subIf]]);
                    CMap::insertValue($statements, $if);
                }

                if ( isset($method->stmts) )
                {
                    foreach ($method->stmts as $stmt)
                    {
                        CMap::insertValue($statements, $stmt);
                    }
                }

                $method->stmts = $statements;
                return $method;
            }
        }
        else if ( $node instanceof PhpParser\Node\Expr\Closure )
        {
            $this->m_numEnteredClosures--;
        }
        else if ( $node instanceof PhpParser\Node\Stmt\Function_ )
        {
            $this->m_numEnteredFunctions--;
        }
    }

    private $m_propsToWrap;
}

/**
 * @ignore
 */
class CGetSetMMethodAddingVisitor extends CMainVisitor
{
    public function __construct ($propsToWrap)
    {
        $this->m_propsToWrap = $propsToWrap;
    }

    public function leaveNode (PhpParser\Node $node)
    {
        if ( $node->hasAttribute("_insertGetMMethodAfterMe") ||
             $node->hasAttribute("_insertSetMMethodAfterMe") )
        {
            $statements = [$node];

            if ( $node->hasAttribute("_insertGetMMethodAfterMe") )
            {
                $subStatements = CMap::make();
                $len = CArray::length($this->m_propsToWrap);
                for ($i = 0; $i < $len; $i++)
                {
                    $propName = $this->m_propsToWrap[$i];

                    $subCondition = new PhpParser\Node\Expr\BooleanNot(
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_isFwCallFuncName)));
                    $return0 = new PhpParser\Node\Stmt\Return_(
                        new PhpParser\Node\Expr\PropertyFetch(
                        new PhpParser\Node\Expr\Variable("this"), $propName));
                    $return1 = new PhpParser\Node\Stmt\Return_(
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_toOopFuncName),
                        [new PhpParser\Node\Expr\PropertyFetch(
                        new PhpParser\Node\Expr\Variable("this"), $propName)]));
                    $else = new PhpParser\Node\Stmt\Else_([$return1]);
                    $subIf = new PhpParser\Node\Stmt\If_($subCondition,
                        ["stmts" => [$return0], "else" => $else]);

                    $condition = new PhpParser\Node\Expr\BinaryOp\Identical(
                        new PhpParser\Node\Expr\Variable("name"),
                        new PhpParser\Node\Scalar\String($propName));
                    $if = new PhpParser\Node\Stmt\If_($condition, ["stmts" => [$subIf]]);
                    CMap::insertValue($subStatements, $if);
                }
                $method = new PhpParser\Node\Stmt\ClassMethod("__get", [
                    "type" => PhpParser\Node\Stmt\Class_::MODIFIER_PUBLIC,
                    "byRef" => true,
                    "params" => [new PhpParser\Node\Param("name")],
                    "stmts" => $subStatements]);
                CMap::insertValue($statements, $method);
            }

            if ( $node->hasAttribute("_insertSetMMethodAfterMe") )
            {
                $subStatements = CMap::make();
                $len = CArray::length($this->m_propsToWrap);
                for ($i = 0; $i < $len; $i++)
                {
                    $propName = $this->m_propsToWrap[$i];

                    $subCondition = new PhpParser\Node\Expr\BooleanNot(
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_isFwCallFuncName)));
                    $assignment0 = new PhpParser\Node\Expr\Assign(
                        new PhpParser\Node\Expr\PropertyFetch(
                        new PhpParser\Node\Expr\Variable("this"), $propName),
                        new PhpParser\Node\Expr\Variable("value"));
                    $assignment1 = new PhpParser\Node\Expr\Assign(
                        new PhpParser\Node\Expr\PropertyFetch(
                        new PhpParser\Node\Expr\Variable("this"), $propName),
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_fromOopFuncName),
                        [new PhpParser\Node\Expr\Variable("value")]));
                    $else = new PhpParser\Node\Stmt\Else_([$assignment1]);
                    $subIf = new PhpParser\Node\Stmt\If_($subCondition,
                        ["stmts" => [$assignment0], "else" => $else]);

                    $condition = new PhpParser\Node\Expr\BinaryOp\Identical(
                        new PhpParser\Node\Expr\Variable("name"),
                        new PhpParser\Node\Scalar\String($propName));
                    $if = new PhpParser\Node\Stmt\If_($condition, ["stmts" => [$subIf]]);
                    CMap::insertValue($subStatements, $if);
                }
                $method = new PhpParser\Node\Stmt\ClassMethod("__set", [
                    "type" => PhpParser\Node\Stmt\Class_::MODIFIER_PUBLIC,
                    "params" => [new PhpParser\Node\Param("name"), new PhpParser\Node\Param("value")],
                    "stmts" => $subStatements]);
                CMap::insertValue($statements, $method);
            }

            return $statements;
        }
    }

    private $m_propsToWrap;
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
