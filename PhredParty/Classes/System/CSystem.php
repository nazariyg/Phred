<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
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
//   static void cd ($sDirectoryPath)
//   static CUStringObject pwd ()
//   static bool setLock ($sLockFp, $bWait)
//   static void unsetLock ($sLockFp)
//   static int numFreeDiskBytes ($sReferenceDp = ".")
//   static int numTotalDiskBytes ($sReferenceDp = ".")
//   static int executionTimeLimit ()
//   static void setExecutionTimeLimit ($iNumSeconds)
//   static void removeExecutionTimeLimit ()
//   static int currentMemoryUsageInt ($eInUnit = CUUnit::BYTE)
//   static float currentMemoryUsageFloat ($eInUnit = CUUnit::BYTE)
//   static int peakMemoryUsageInt ($eInUnit = CUUnit::BYTE)
//   static float peakMemoryUsageFloat ($eInUnit = CUUnit::BYTE)
//   static void setMemoryUsageLimit ($iNumBytes)
//   static bool hasConfigOption ($sOptionName)
//   static CUStringObject configOption ($sOptionName)
//   static bool configOptionBool ($sOptionName)
//   static int configOptionInt ($sOptionName)
//   static float configOptionFloat ($sOptionName)
//   static int configOptionStorageInt ($sOptionName, $eInUnit = CUUnit::BYTE)
//   static float configOptionStorageFloat ($sOptionName, $eInUnit = CUUnit::BYTE)
//   static CUStringObject configOptionOrig ($sOptionName)
//   static bool configOptionOrigBool ($sOptionName)
//   static int configOptionOrigInt ($sOptionName)
//   static float configOptionOrigFloat ($sOptionName)
//   static int configOptionOrigStorageInt ($sOptionName, $eInUnit = CUUnit::BYTE)
//   static float configOptionOrigStorageFloat ($sOptionName, $eInUnit = CUUnit::BYTE)
//   static void setConfigOption ($sOptionName, $sOptionValue)
//   static void setConfigOptionBool ($sOptionName, $bOptionValue)
//   static void setConfigOptionInt ($sOptionName, $iOptionValue)
//   static void setConfigOptionFloat ($sOptionName, $fOptionValue)
//   static void restoreConfigOption ($sOptionName)
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
        $sDp;
        $sCustomTempFilesDp = CConfiguration::appOption("customTemporaryFilesDirectoryPath");
        if ( CString::isEmpty($sCustomTempFilesDp) )
        {
            // Return the OS's temporary files directory.
            $sDp = sys_get_temp_dir();
        }
        else
        {
            // Return the custom temporary files directory.
            $sDp = $sCustomTempFilesDp;
        }
        $sDp = CString::stripEnd($sDp, "/");  // just in case
        return $sDp;
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
     * @param  string $sDirectoryPath The path to the new current working directory.
     *
     * @return void
     */

    public static function cd ($sDirectoryPath)
    {
        assert( 'is_cstring($sDirectoryPath)', vs(isset($this), get_defined_vars()) );

        $bRes = chdir($sDirectoryPath);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path to the current working directory.
     *
     * @return CUStringObject The path to the current working directory.
     */

    public static function pwd ()
    {
        $sWd = getcwd();
        assert( 'is_cstring($sWd)', vs(isset($this), get_defined_vars()) );
        return $sWd;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tries to set a lock on a file so that any other process that attempts to lock the same file either waits for the
     * file to get unlocked or continues without the lock.
     *
     * @param  string $sLockFp The path to the file to be locked.
     * @param  bool $bWait If the file appears to be already locked, this parameter tells whether to wait for the file
     * to get unlocked (`true`) or to return immediately (`false`).
     *
     * @return bool `true` if the file was successfully locked, `false` otherwise.
     */

    public static function setLock ($sLockFp, $bWait)
    {
        assert( 'is_cstring($sLockFp) && is_bool($bWait)', vs(isset($this), get_defined_vars()) );

        if ( !CFile::exists($sLockFp) )
        {
            // Use `touch` instead of `CFile::create`.
            touch($sLockFp);
        }
        $rcFile = fopen($sLockFp, "r+");
        if ( flock($rcFile, ( $bWait ) ? LOCK_EX : LOCK_EX | LOCK_NB) )
        {
            if ( !isset(self::$ms_mFhFromLockFp) )
            {
                self::$ms_mFhFromLockFp = CMap::make();
            }
            $sLockFpAbs = CFilePath::absolute($sLockFp);
            self::$ms_mFhFromLockFp[$sLockFpAbs] = $rcFile;
            return true;
        }
        else
        {
            fclose($rcFile);
            return false;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes the lock that was previously set on a file.
     *
     * @param  string $sLockFp The path to the file to be unlocked.
     *
     * @return void
     */

    public static function unsetLock ($sLockFp)
    {
        assert( 'is_cstring($sLockFp)', vs(isset($this), get_defined_vars()) );

        $sLockFpAbs = CFilePath::absolute($sLockFp);
        $rcFile = self::$ms_mFhFromLockFp[$sLockFpAbs];
        flock($rcFile, LOCK_UN);
        fclose($rcFile);
        CMap::remove(self::$ms_mFhFromLockFp, $sLockFpAbs);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the amount of free space available on the filesystem or a disk partition.
     *
     * @param  string $sReferenceDp **OPTIONAL. Default is** ".". The path to the reference directory on the filesystem
     * or the disk partition of interest.
     *
     * @return int The amount of free space available on the filesystem or the disk partition, in bytes.
     */

    public static function numFreeDiskBytes ($sReferenceDp = ".")
    {
        assert( 'is_cstring($sReferenceDp)', vs(isset($this), get_defined_vars()) );

        $fNumBytes = disk_free_space($sReferenceDp);
        assert( 'is_float($fNumBytes)', vs(isset($this), get_defined_vars()) );
        if ( $fNumBytes < (float)CMathi::INT_MAX )
        {
            return (int)$fNumBytes;
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
     * @param  string $sReferenceDp **OPTIONAL. Default is** ".". The path to the reference directory on the filesystem
     * or the disk partition of interest.
     *
     * @return int The amount of total space on the filesystem or the disk partition, in bytes.
     */

    public static function numTotalDiskBytes ($sReferenceDp = ".")
    {
        assert( 'is_cstring($sReferenceDp)', vs(isset($this), get_defined_vars()) );

        $fNumBytes = disk_total_space($sReferenceDp);
        assert( 'is_float($fNumBytes)', vs(isset($this), get_defined_vars()) );
        if ( $fNumBytes < (float)CMathi::INT_MAX )
        {
            return (int)$fNumBytes;
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
     * @param  int $iNumSeconds The maximum amount of time for which the script is allowed to execute, in seconds.
     *
     * @return void
     */

    public static function setExecutionTimeLimit ($iNumSeconds)
    {
        assert( 'is_int($iNumSeconds)', vs(isset($this), get_defined_vars()) );
        assert( '$iNumSeconds > 0', vs(isset($this), get_defined_vars()) );

        self::setConfigOptionInt("max_execution_time", $iNumSeconds);
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
     * @param  enum $eInUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return int The amount of memory currently allocated to PHP.
     */

    public static function currentMemoryUsageInt ($eInUnit = CUUnit::BYTE)
    {
        assert( 'is_enum($eInUnit)', vs(isset($this), get_defined_vars()) );

        $iByteQty = memory_get_usage(true);
        return CUUnit::convertStoragei($iByteQty, CUUnit::BYTE, $eInUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the amount of memory currently allocated to PHP, as a floating-point value.
     *
     * @param  enum $eInUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return float The amount of memory currently allocated to PHP.
     */

    public static function currentMemoryUsageFloat ($eInUnit = CUUnit::BYTE)
    {
        assert( 'is_enum($eInUnit)', vs(isset($this), get_defined_vars()) );

        $iByteQty = memory_get_usage(true);
        return CUUnit::convertStoragef((float)$iByteQty, CUUnit::BYTE, $eInUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the peak amount of memory allocated to PHP to this moment, as an integer value.
     *
     * @param  enum $eInUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return int The peak amount of memory allocated to PHP to this moment.
     */

    public static function peakMemoryUsageInt ($eInUnit = CUUnit::BYTE)
    {
        assert( 'is_enum($eInUnit)', vs(isset($this), get_defined_vars()) );

        $iByteQty = memory_get_peak_usage(true);
        return CUUnit::convertStoragei($iByteQty, CUUnit::BYTE, $eInUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the peak amount of memory allocated to PHP to this moment, as a floating-point value.
     *
     * @param  enum $eInUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return float The peak amount of memory allocated to PHP to this moment.
     */

    public static function peakMemoryUsageFloat ($eInUnit = CUUnit::BYTE)
    {
        assert( 'is_enum($eInUnit)', vs(isset($this), get_defined_vars()) );

        $iByteQty = memory_get_peak_usage(true);
        return CUUnit::convertStoragef((float)$iByteQty, CUUnit::BYTE, $eInUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the maximum amount of memory the script is allowed to allocate.
     *
     * @param  int $iNumBytes The maximum amount of memory the script is allowed to allocate, in bytes.
     *
     * @return void
     */

    public static function setMemoryUsageLimit ($iNumBytes)
    {
        assert( 'is_int($iNumBytes)', vs(isset($this), get_defined_vars()) );
        assert( '$iNumBytes > 0', vs(isset($this), get_defined_vars()) );

        self::setConfigOptionInt("memory_limit", $iNumBytes);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the PHP's configuration options include an option with a specified name.
     *
     * @param  string $sOptionName The name of the option to be looked for.
     *
     * @return bool `true` if the PHP's configuration options include an option with the name specified, `false`
     * otherwise.
     */

    public static function hasConfigOption ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );

        $xRes = ini_get($sOptionName);
        return ( $xRes !== false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified PHP's configuration option, as a string.
     *
     * @param  string $sOptionName The name of the option.
     *
     * @return CUStringObject The value of the option.
     */

    public static function configOption ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );

        $sOptionValue = ini_get($sOptionName);
        assert( 'is_cstring($sOptionValue)', vs(isset($this), get_defined_vars()) );
        return $sOptionValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified PHP's configuration option, as a boolean value.
     *
     * @param  string $sOptionName The name of the option.
     *
     * @return bool The value of the option.
     */

    public static function configOptionBool ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        return CString::toBool(self::configOption($sOptionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified PHP's configuration option, as an integer value.
     *
     * @param  string $sOptionName The name of the option.
     *
     * @return int The value of the option.
     */

    public static function configOptionInt ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        return CString::toInt(self::configOption($sOptionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified PHP's configuration option, as a floating-point value.
     *
     * @param  string $sOptionName The name of the option.
     *
     * @return float The value of the option.
     */

    public static function configOptionFloat ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        return CString::toFloat(self::configOption($sOptionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified PHP's configuration option that is related to digital storage, as an integer
     * value.
     *
     * @param  string $sOptionName The name of the option.
     * @param  enum $eInUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return int The value of the option.
     */

    public static function configOptionStorageInt ($sOptionName, $eInUnit = CUUnit::BYTE)
    {
        assert( 'is_cstring($sOptionName) && is_enum($eInUnit)', vs(isset($this), get_defined_vars()) );

        $sOptionValue = self::configOption($sOptionName);
        $eSrcUnit = self::storageUnitFromOptionValue($sOptionValue);
        $sValue;
        $bFound = CRegex::find($sOptionValue, "/^\\d+/", $sValue);
        assert( '$bFound', vs(isset($this), get_defined_vars()) );
        $iValue = CString::toInt($sValue);
        return CUUnit::convertStoragei($iValue, $eSrcUnit, $eInUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified PHP's configuration option that is related to digital storage, as a
     * floating-point value.
     *
     * @param  string $sOptionName The name of the option.
     * @param  enum $eInUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return float The value of the option.
     */

    public static function configOptionStorageFloat ($sOptionName, $eInUnit = CUUnit::BYTE)
    {
        assert( 'is_cstring($sOptionName) && is_enum($eInUnit)', vs(isset($this), get_defined_vars()) );

        $sOptionValue = self::configOption($sOptionName);
        $eSrcUnit = self::storageUnitFromOptionValue($sOptionValue);
        $sValue;
        $bFound = CRegex::find($sOptionValue, "/^\\d+/", $sValue);
        assert( '$bFound', vs(isset($this), get_defined_vars()) );
        $fValue = CString::toFloat($sValue);
        return CUUnit::convertStoragef($fValue, $eSrcUnit, $eInUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the original value of a specified PHP's configuration option, as a string.
     *
     * @param  string $sOptionName The name of the option.
     *
     * @return CUStringObject The value of the option.
     */

    public static function configOptionOrig ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );

        $sOptionValue = get_cfg_var($sOptionName);
        assert( 'is_cstring($sOptionValue)', vs(isset($this), get_defined_vars()) );
        return $sOptionValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the original value of a specified PHP's configuration option, as a boolean value.
     *
     * @param  string $sOptionName The name of the option.
     *
     * @return bool The value of the option.
     */

    public static function configOptionOrigBool ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        return CString::toBool(self::configOptionOrig($sOptionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the original value of a specified PHP's configuration option, as an integer value.
     *
     * @param  string $sOptionName The name of the option.
     *
     * @return int The value of the option.
     */

    public static function configOptionOrigInt ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        return CString::toInt(self::configOptionOrig($sOptionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the original value of a specified PHP's configuration option, as a floating-point value.
     *
     * @param  string $sOptionName The name of the option.
     *
     * @return float The value of the option.
     */

    public static function configOptionOrigFloat ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        return CString::toFloat(self::configOptionOrig($sOptionName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the original value of a specified PHP's configuration option that is related to digital storage, as an
     * integer value.
     *
     * @param  string $sOptionName The name of the option.
     * @param  enum $eInUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return int The value of the option.
     */

    public static function configOptionOrigStorageInt ($sOptionName, $eInUnit = CUUnit::BYTE)
    {
        assert( 'is_cstring($sOptionName) && is_enum($eInUnit)', vs(isset($this), get_defined_vars()) );

        $sOptionValue = self::configOptionOrig($sOptionName);
        $eSrcUnit = self::storageUnitFromOptionValue($sOptionValue);
        $sValue;
        $bFound = CRegex::find($sOptionValue, "/^\\d+/", $sValue);
        assert( '$bFound', vs(isset($this), get_defined_vars()) );
        $iValue = CString::toInt($sValue);
        return CUUnit::convertStoragei($iValue, $eSrcUnit, $eInUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the original value of a specified PHP's configuration option that is related to digital storage, as a
     * floating-point value.
     *
     * @param  string $sOptionName The name of the option.
     * @param  enum $eInUnit **OPTIONAL. Default is** `CUUnit::BYTE`. The storage unit for the output value.
     *
     * @return float The value of the option.
     */

    public static function configOptionOrigStorageFloat ($sOptionName, $eInUnit = CUUnit::BYTE)
    {
        assert( 'is_cstring($sOptionName) && is_enum($eInUnit)', vs(isset($this), get_defined_vars()) );

        $sOptionValue = self::configOptionOrig($sOptionName);
        $eSrcUnit = self::storageUnitFromOptionValue($sOptionValue);
        $sValue;
        $bFound = CRegex::find($sOptionValue, "/^\\d+/", $sValue);
        assert( '$bFound', vs(isset($this), get_defined_vars()) );
        $fValue = CString::toFloat($sValue);
        return CUUnit::convertStoragef($fValue, $eSrcUnit, $eInUnit);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the value of a PHP's configuration option to a string.
     *
     * @param  string $sOptionName The name of the option.
     * @param  string $sOptionValue The option's new value.
     *
     * @return void
     */

    public static function setConfigOption ($sOptionName, $sOptionValue)
    {
        assert( 'is_cstring($sOptionName) && is_cstring($sOptionValue)', vs(isset($this), get_defined_vars()) );

        $xRes = ini_set($sOptionName, $sOptionValue);
        assert( 'is_cstring($xRes)', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the value of a PHP's configuration option to a boolean value.
     *
     * @param  string $sOptionName The name of the option.
     * @param  bool $bOptionValue The option's new value.
     *
     * @return void
     */

    public static function setConfigOptionBool ($sOptionName, $bOptionValue)
    {
        assert( 'is_cstring($sOptionName) && is_bool($bOptionValue)', vs(isset($this), get_defined_vars()) );
        self::setConfigOption($sOptionName, CString::fromBool10($bOptionValue));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the value of a PHP's configuration option to an integer value.
     *
     * @param  string $sOptionName The name of the option.
     * @param  int $iOptionValue The option's new value.
     *
     * @return void
     */

    public static function setConfigOptionInt ($sOptionName, $iOptionValue)
    {
        assert( 'is_cstring($sOptionName) && is_int($iOptionValue)', vs(isset($this), get_defined_vars()) );
        self::setConfigOption($sOptionName, CString::fromInt($iOptionValue));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the value of a PHP's configuration option to a floating-point value.
     *
     * @param  string $sOptionName The name of the option.
     * @param  float $fOptionValue The option's new value.
     *
     * @return void
     */

    public static function setConfigOptionFloat ($sOptionName, $fOptionValue)
    {
        assert( 'is_cstring($sOptionName) && is_float($fOptionValue)', vs(isset($this), get_defined_vars()) );
        self::setConfigOption($sOptionName, CString::fromFloat($fOptionValue));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Restores a PHP's configuration option to its original value.
     *
     * @param  string $sOptionName The name of the option.
     *
     * @return void
     */

    public static function restoreConfigOption ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        ini_restore($sOptionName);
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
        $sCurrEnv;
        if ( !$GLOBALS["PHRED_TESTS"] )
        {
            // Try to get the name of the environment in which the application is currently running without the risk of
            // encountering an error or triggering an assertion, which could otherwise reveal sensitive debugging
            // information if "display_errors" happens to be enabled in php.ini.
            $sAppConfigFp = $GLOBALS["PHRED_PATH_TO_APP"] . "/Configuration" . "/Application.json";
            if ( file_exists($sAppConfigFp) )
            {
                $sAppConfig = file_get_contents($sAppConfigFp);
                if ( is_string($sAppConfig) )
                {
                    $mMatches;
                    $xRes = preg_match("/^\\h*\"environment\"\\s*:\\s*\"(\\w+)\"/m", $sAppConfig, $mMatches);
                    if ( $xRes === 1 )
                    {
                        $sCurrEnv = $mMatches[1];
                    }
                }
            }
        }
        else
        {
            $sCurrEnv = "tst";
        }

        if ( isset($sCurrEnv) )
        {
            // Based on the current environment, set some debugging options to temporary values for the time while the
            // configuration is not yet read.
            if ( strcasecmp($sCurrEnv, "dev") == 0 )
            {
                // Development.
                ini_set("display_errors", "On");
                CDebug::setAssertionsLevel1(true);
                CDebug::setAssertionsLevel2(false);
            }
            else if ( strcasecmp($sCurrEnv, "pro") == 0 )
            {
                // Production.
                ini_set("display_errors", "Off");
                CDebug::setAssertionsLevel1(false);
                CDebug::setAssertionsLevel2(false);
            }
            else if ( strcasecmp($sCurrEnv, "tst") == 0 )
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
        $eErrorReportingLevel = constant(CConfiguration::option("debug.errorReportingLevel"));
        error_reporting($eErrorReportingLevel);

        // Process the configuration options that are related to debugging.

        $mDebug = CConfiguration::option("debug");

        // Assertions and the conditions on which they get active.
        $bAssertionsAreActive = false;
        $bAssertionsAreEnabled = $mDebug["enableAssertions"];
        if ( $bAssertionsAreEnabled )
        {
            $sAssertionsAreActiveBasedOn = $mDebug["assertionsAreActiveBasedOn"];
            if ( CString::equalsCi($sAssertionsAreActiveBasedOn, "always") )
            {
                // Always.
                $bAssertionsAreActive = true;
            }
            else  // on a time condition
            {
                // Reference time zone.
                $oRefTimeZone;
                $sTimeZoneName = $mDebug["referenceTimeZone"];
                if ( !CString::isEmpty($sTimeZoneName) )
                {
                    $oRefTimeZone = new CTimeZone($sTimeZoneName);
                }
                else
                {
                    $oRefTimeZone = CTimeZone::makeUtc();
                }

                if ( CString::equalsCi($sAssertionsAreActiveBasedOn, "hour") )
                {
                    // Current time.
                    $oCurrTime = CTime::now();

                    // Hour.
                    $aHourRanges = $mDebug["assertionsAreActiveWithinHourRange"];
                    $iCurrHour = $oCurrTime->hourInTimeZone($oRefTimeZone);
                    if ( !is_carray($aHourRanges[0]) )
                    {
                        $aHourRanges = CArray::fromElements($aHourRanges);
                    }
                    $iLen = CArray::length($aHourRanges);
                    for ($i = 0; $i < $iLen; $i++)
                    {
                        $mRange = $aHourRanges[$i];
                        assert( 'is_int($mRange[0]) && is_int($mRange[1])', vs(isset($this), get_defined_vars()) );
                        if ( $mRange[0] <= $iCurrHour && $iCurrHour <= $mRange[1] )
                        {
                            $bAssertionsAreActive = true;
                            break;
                        }
                    }
                }
                else if ( CString::equalsCi($sAssertionsAreActiveBasedOn, "dayOfWeek") )
                {
                    // Current time.
                    $oCurrTime = CTime::now();

                    // Day of week.
                    $aDaysOfWeek = $mDebug["assertionsAreActiveOnDaysOfWeek"];
                    if ( !is_carray($aDaysOfWeek) )
                    {
                        $aDaysOfWeek = CArray::fromElements($aDaysOfWeek);
                    }
                    $eCurrDayOfWeek = $oCurrTime->dayOfWeekInTimeZone($oRefTimeZone);
                    $sCurrDayOfWeekShort;
                    switch ( $eCurrDayOfWeek )
                    {
                    case CTime::SUNDAY:
                        $sCurrDayOfWeekShort = "sun";
                        break;
                    case CTime::MONDAY:
                        $sCurrDayOfWeekShort = "mon";
                        break;
                    case CTime::TUESDAY:
                        $sCurrDayOfWeekShort = "tue";
                        break;
                    case CTime::WEDNESDAY:
                        $sCurrDayOfWeekShort = "wed";
                        break;
                    case CTime::THURSDAY:
                        $sCurrDayOfWeekShort = "thu";
                        break;
                    case CTime::FRIDAY:
                        $sCurrDayOfWeekShort = "fri";
                        break;
                    case CTime::SATURDAY:
                        $sCurrDayOfWeekShort = "sat";
                        break;
                    }
                    $iLen = CArray::length($aDaysOfWeek);
                    for ($i = 0; $i < $iLen; $i++)
                    {
                        $sDow = $aDaysOfWeek[$i];
                        assert( '!CString::isEmpty($sDow)', vs(isset($this), get_defined_vars()) );
                        if ( CString::equalsCi($sCurrDayOfWeekShort, $sDow) )
                        {
                            $bAssertionsAreActive = true;
                            break;
                        }
                    }
                }
                else if ( CString::equalsCi($sAssertionsAreActiveBasedOn, "dayOfYear") )
                {
                    // Day of year.
                    $iMultiplier = $mDebug["assertionsAreActiveOnEveryDayOfYearMultipleOf"];
                    assert( 'is_int($iMultiplier)', vs(isset($this), get_defined_vars()) );
                    $iCurrDayOfYear = CTime::currentDayOfYearInTimeZone($oRefTimeZone);
                    if ( $iCurrDayOfYear % $iMultiplier == 0 )
                    {
                        $bAssertionsAreActive = true;
                    }
                }
                else
                {
                    assert( 'false', vs(isset($this), get_defined_vars()) );
                }
            }
        }
        if ( $bAssertionsAreActive )
        {
            // Enable level 1 assertions.
            CDebug::setAssertionsLevel1(true);

            $bEnableAssertionsLevel2 = $mDebug["enableAssertionsLevel2"];
            if ( $bEnableAssertionsLevel2 )
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
        $mLogging = $mDebug["logging"];
        $bLoggingIsEnabled = $mLogging["enable"];
        if ( $bLoggingIsEnabled )
        {
            $sLogFp = $mLogging["logFilePath"];
            assert( '!CString::isEmpty($sLogFp)', vs(isset($this), get_defined_vars()) );
            $sLogFp = CFilePath::frameworkPath($sLogFp);
            CDebug::setLogging($sLogFp);
        }

        // Mailing.
        $mMailing = $mDebug["mailing"];
        $bMailingIsEnabled = $mMailing["enable"];
        if ( $bMailingIsEnabled )
        {
            $mAdminMail = CConfiguration::option("admin.mail");
            $sTo = $mAdminMail["to"];
            $sFrom = $mAdminMail["from"];
            $sTransport = $mAdminMail["transport"];
            assert( '!CString::isEmpty($sTo) && !CString::isEmpty($sFrom) && !CString::isEmpty($sTransport)',
                vs(isset($this), get_defined_vars()) );

            $iMinTimeBetweenSendMailHours = $mMailing["minTimeBetweenSendMailHours"];
            assert( 'is_int($iMinTimeBetweenSendMailHours)', vs(isset($this), get_defined_vars()) );

            $oMail;
            if ( CString::equalsCi($sTransport, "smtp") )
            {
                $sSmtpOutgoingServer = $mAdminMail["smtpOutgoingServer"];
                $sSmtpUsername = $mAdminMail["smtpUsername"];
                $sSmtpPassword = $mAdminMail["smtpPassword"];
                assert( '!CString::isEmpty($sSmtpOutgoingServer) && !CString::isEmpty($sSmtpUsername) && ' .
                        '!CString::isEmpty($sSmtpPassword)', vs(isset($this), get_defined_vars()) );
                $oMail = CMail::makeSmtp($sSmtpOutgoingServer, $sSmtpUsername, $sSmtpPassword, $sFrom, $sTo);
            }
            else if ( CString::equalsCi($sTransport, "system") )
            {
                $oMail = CMail::makeSystem($sFrom, $sTo);
            }
            else
            {
                assert( 'false', vs(isset($this), get_defined_vars()) );
            }
            CDebug::setMailing($oMail, $iMinTimeBetweenSendMailHours);
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

        $mUpdates = CConfiguration::option("updates");
        $bUpdatesAreEnabled = $mUpdates["enable"];
        if ( $bUpdatesAreEnabled )
        {
            $iMinTimeBetweenDoUpdatesDays = $mUpdates["minTimeBetweenDoUpdatesDays"];
            $mComponents = $mUpdates["components"];
            assert( 'is_int($iMinTimeBetweenDoUpdatesDays)', vs(isset($this), get_defined_vars()) );

            // Logging.
            $mLogging = $mUpdates["logging"];
            $bLoggingIsEnabled = $mLogging["enable"];
            $sLogFp = $mLogging["logFilePath"];
            if ( $bLoggingIsEnabled )
            {
                assert( '!CString::isEmpty($sLogFp)', vs(isset($this), get_defined_vars()) );
                $sLogFp = CFilePath::frameworkPath($sLogFp);
                CShell::setLogging($sLogFp);
            }

            // Mailing.
            $mMailing = $mUpdates["mailing"];
            $bMailingIsEnabled = $mMailing["enable"];
            if ( $bMailingIsEnabled )
            {
                $mAdminMail = CConfiguration::option("admin.mail");
                $sTo = $mAdminMail["to"];
                $sFrom = $mAdminMail["from"];
                $sTransport = $mAdminMail["transport"];
                assert( '!CString::isEmpty($sTo) && !CString::isEmpty($sFrom) && !CString::isEmpty($sTransport)',
                    vs(isset($this), get_defined_vars()) );

                $oMail;
                if ( CString::equalsCi($sTransport, "smtp") )
                {
                    $sSmtpOutgoingServer = $mAdminMail["smtpOutgoingServer"];
                    $sSmtpUsername = $mAdminMail["smtpUsername"];
                    $sSmtpPassword = $mAdminMail["smtpPassword"];
                    assert( '!CString::isEmpty($sSmtpOutgoingServer) && !CString::isEmpty($sSmtpUsername) && ' .
                            '!CString::isEmpty($sSmtpPassword)', vs(isset($this), get_defined_vars()) );
                    $oMail = CMail::makeSmtp($sSmtpOutgoingServer, $sSmtpUsername, $sSmtpPassword, $sFrom, $sTo);
                }
                else if ( CString::equalsCi($sTransport, "system") )
                {
                    $oMail = CMail::makeSystem($sFrom, $sTo);
                }
                else
                {
                    assert( 'false', vs(isset($this), get_defined_vars()) );
                }
                CShell::setMailing($oMail);
            }

            $sThirdPartyDp = $GLOBALS["PHRED_PATH_TO_THIRD_PARTY"];
            $sLastUpdateTimeFp = CFilePath::add($sThirdPartyDp, self::$ms_sThirdPartyLastUpdateTimeFn);

            // Read the file containing the Unix seconds of the last update time stamp (if exists) and compare that
            // time with the current time.
            $iNumDaysSinceLastUpdate;
            if ( CFile::exists($sLastUpdateTimeFp) )
            {
                $oLastUpdateTime = new CTime(CString::toInt(CFile::read($sLastUpdateTimeFp)));
                $oCurrTime = CTime::now();
                if ( $oLastUpdateTime->isBefore($oCurrTime) )
                {
                    $iNumDaysSinceLastUpdate = $oCurrTime->diffInDays($oLastUpdateTime);
                    if ( $iNumDaysSinceLastUpdate < $iMinTimeBetweenDoUpdatesDays )
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

            $sDate = CShell::currentDate();
            CShell::say("Started on $sDate.");

            if ( isset($iNumDaysSinceLastUpdate) )
            {
                CShell::say("It has been $iNumDaysSinceLastUpdate day(s) since last successful update.");
            }

            $sConcurrLockFp = CFilePath::add($sThirdPartyDp, self::$ms_sThirdPartyConcurrLockFn);

            // Try locking the operation.
            if ( !self::setLock($sConcurrLockFp, false) )
            {
                assert( 'false', vs(isset($this), get_defined_vars()) );
                CShell::onError(false, "Could not obtain a lock on the operation.");
                CShell::writeToLog("\n");
                return false;
            }

            $bPhpConfigNeedsReload = false;
            $iTotalNumComponents = CMap::length($mComponents);
            $iNumComponentsUpdated = 0;

            // The Browser Capabilities Project (BrowsCap).
            if ( CMap::hasKey($mComponents, "browsCap") )
            {
                $mBrowsCap = $mComponents["browsCap"];

                $bSkip = $mBrowsCap["skip"];
                if ( !$bSkip )
                {
                    CShell::say("Updating the Browser Capabilities Project (BrowsCap) ...");

                    $sLookupFileUrl = $mBrowsCap["lookupFileUrl"];
                    assert( '!CString::isEmpty($sLookupFileUrl)', vs(isset($this), get_defined_vars()) );

                    // Component-related constants.
                    static $s_sConfigOptName = "browscap";
                    static $s_iLookupFileDownloadTimeoutSeconds = 120;

                    if ( self::hasConfigOption($s_sConfigOptName) )
                    {
                        $sBrowsCapLookupFp = CString::trim(self::configOption($s_sConfigOptName));
                        if ( !CString::isEmpty($sBrowsCapLookupFp) )
                        {
                            $sBrowsCapDp = CFilePath::directory($sBrowsCapLookupFp);

                            CShell::say("Downloading a BrowsCap lookup file from '$sLookupFileUrl' ...");

                            $sTemporaryFp = CFile::createTemporary($sBrowsCapDp);
                            $bDownloadRes = CInetRequest::downloadFile($sLookupFileUrl, $sTemporaryFp,
                                $s_iLookupFileDownloadTimeoutSeconds);
                            if ( $bDownloadRes )
                            {
                                // After the file is downloaded into a temporary one, move it to the destination,
                                // safely replacing the existing file, if any.
                                CFile::move($sTemporaryFp, $sBrowsCapLookupFp);
                                $iNumComponentsUpdated++;
                                $bPhpConfigNeedsReload = true;

                                $fDownloadedFileSizeKB = CUUnit::convertStoragef(
                                    (float)CFile::size($sBrowsCapLookupFp), CUUnit::BYTE, CUUnit::KILOBYTE);
                                $fDownloadedFileSizeKB = CMathf::round($fDownloadedFileSizeKB, 2);
                                CShell::say("Done. The downloaded file is $fDownloadedFileSizeKB KB in size.");
                            }
                            else
                            {
                                CShell::onError(false,
                                    "Could not download a BrowsCap lookup file from '$sLookupFileUrl'.");
                            }

                            // Just in case, check for any temporary files that could have been left by any previous
                            // operations in the directory.
                            $aLeftoverFiles = CFile::findFiles(CFilePath::add($sBrowsCapDp,
                                CFile::DEFAULT_TEMPORARY_FILE_PREFIX . "*"));
                            if ( !CArray::isEmpty($aLeftoverFiles) )
                            {
                                // Cleanup the directory from the temporary files.
                                $iLen = CArray::length($aLeftoverFiles);
                                for ($i = 0; $i < $iLen; $i++)
                                {
                                    CFile::delete($aLeftoverFiles[$i]);
                                }
                            }
                        }
                        else
                        {
                            CShell::onError(false, "Could not read the value of '$s_sConfigOptName' option " .
                                "in the PHP CLI configuration file.");
                        }
                    }
                    else
                    {
                        CShell::onError(false,
                            "Could not find '$s_sConfigOptName' option in the PHP CLI configuration file.");
                    }
                }
                else
                {
                    CShell::say("Skipping the Browser Capabilities Project (BrowsCap).");
                }
            }

            // All the components have been processed. Unlock the operation.
            self::unsetLock($sConcurrLockFp);

            $sDate = CShell::currentDate();
            if ( $iNumComponentsUpdated != 0 )
            {
                // One or more third-party components have been updated. Put a time stamp on the directory where the
                // components are located.
                CFile::write($sLastUpdateTimeFp, CString::fromInt(CTime::currentUTime()));

                if ( $iNumComponentsUpdated == $iTotalNumComponents )
                {
                    CShell::speak("Success. All $iTotalNumComponents third-party component(s)");
                }
                else
                {
                    CShell::speak(
                        "Partial success. $iNumComponentsUpdated out of $iTotalNumComponents third-party component(s)");
                }
                CShell::say("have been updated. Completed on $sDate.");
            }
            else
            {
                CShell::say("No third-party components have been updated. Completed on $sDate.");
            }

            return $bPhpConfigNeedsReload;
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

        $oTimeoutPause = new CTimeoutPause();

        CShell::speak("Processing third-party components ...");

        $aTpDps = CFile::listDirectories(CFilePath::absolute($GLOBALS["PHRED_PATH_TO_THIRD_PARTY"]));
        $aIgnorePackages = CConfiguration::option("upd.thirdPartyOopWrappingIgnorePackages");
        $aIgnorePackagesL2 = CArray::filter($aIgnorePackages, function ($sPackage)
            {
                return CString::find($sPackage, "/");
            });
        $aNewTpDps = CArray::make();
        $iLen = CArray::length($aTpDps);
        for ($i = 0; $i < $iLen; $i++)
        {
            $sTpDp = $aTpDps[$i];

            $sDirName = CFilePath::name($sTpDp);
            if ( !CArray::find($aIgnorePackages, $sDirName) )
            {
                $bDpHasL2DirsToIgnore = CArray::find($aIgnorePackagesL2, $sDirName, function ($sPackageL2, $sDirName)
                    {
                        return CString::equals(CFilePath::directory($sPackageL2), $sDirName);
                    });
                if ( !$bDpHasL2DirsToIgnore )
                {
                    CArray::push($aNewTpDps, $sTpDp);
                }
                else
                {
                    $aTpSubDps = CFile::listDirectories($sTpDp);
                    $aTpSubDps = CArray::filter($aTpSubDps, function ($sSubDp) use ($aIgnorePackagesL2)
                        {
                            return !CArray::find($aIgnorePackagesL2, $sSubDp, function ($sPackageL2, $sSubDp)
                                {
                                    return CString::endsWith($sSubDp, $sPackageL2);
                                });
                        });
                    CArray::pushArray($aNewTpDps, $aTpSubDps);
                }
            }
        }
        $aTpDps = $aNewTpDps;

        $bWrapProtectedMethods = CConfiguration::option("upd.thirdPartyOopWrappingInProtectedMethods");
        $bWrapPrivateMethods = CConfiguration::option("upd.thirdPartyOopWrappingInPrivateMethods");

        static $s_sStdPhpTag = "<?php";

        static $s_fProgressResolution = 0.05;

        $iPrevProgressDivR = 0;
        $iTpDpsLen = CArray::length($aTpDps);
        for ($i0 = 0; $i0 < $iTpDpsLen; $i0++)
        {
            $aTpFps = CFile::reFindFilesRecursive($aTpDps[$i0], "/\\.php\\d?\\z/");

            $iTpFpsLen = CArray::length($aTpFps);
            for ($i1 = 0; $i1 < $iTpFpsLen; $i1++)
            {
                $sFileCode = CFile::read($aTpFps[$i1]);

                if ( !CString::find($sFileCode, self::$ms_sThirdPartyAlreadyOopWrappedMark) )
                {
                    $oParser = new PhpParser\Parser(new PhpParser\Lexer());
                    try
                    {
                        // Parse the code.
                        $mStatements = $oParser->parse($sFileCode);

                        // Wrap the code into OOP.
                        $oTraverser = new PhpParser\NodeTraverser();
                        $oMainVisitor = new CMainVisitor($bWrapProtectedMethods, $bWrapPrivateMethods);
                        $oTraverser->addVisitor($oMainVisitor);
                        $mStatements = $oTraverser->traverse($mStatements);
                        $sWrappedCode = (new PhpParser\PrettyPrinter\Standard())->prettyPrint($mStatements);

                        $iPhpTagPos = CString::indexOf($sWrappedCode, $s_sStdPhpTag);
                        if ( $iPhpTagPos == -1 )
                        {
                            $sWrappedCode = "$s_sStdPhpTag\n\n$sWrappedCode";
                            $iPhpTagPos = 0;
                        }
                        $sWrappedCode = CString::insert($sWrappedCode, $iPhpTagPos + CString::length($s_sStdPhpTag),
                            "\n\n" . self::$ms_sThirdPartyAlreadyOopWrappedMark);

                        // Save.
                        CFile::write($aTpFps[$i1], $sWrappedCode);
                    }
                    catch (PhpParser\Error $oParserError)
                    {
                        CShell::say("\nPhpParser: " . $aTpFps[$i1] . ", at line " . $oParserError->getRawLine() . ": " .
                            $oParserError->getRawMessage());
                    }
                }

                $fProgress = (float)($i0/$iTpDpsLen + 1/$iTpDpsLen*$i1/$iTpFpsLen);
                $iProgressDivR = CMathi::floor($fProgress/$s_fProgressResolution);
                if ( $iProgressDivR != $iPrevProgressDivR )
                {
                    $iPerc = CMathi::round($iProgressDivR*$s_fProgressResolution*100);
                    CShell::speak("$iPerc%");
                }
                $iPrevProgressDivR = $iProgressDivR;
            }
        }
        CShell::speak("100%");

        CShell::say("Done.");

        $oTimeoutPause->end();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function storageUnitFromOptionValue ($sOptionValue)
    {
        $sOptionValue = CString::toLowerCase($sOptionValue);
        if ( CString::endsWith($sOptionValue, "k") )
        {
            return CUUnit::KILOBYTE;
        }
        if ( CString::endsWith($sOptionValue, "m") )
        {
            return CUUnit::MEGABYTE;
        }
        if ( CString::endsWith($sOptionValue, "g") )
        {
            return CUUnit::GIGABYTE;
        }
        if ( CString::endsWith($sOptionValue, "t") )
        {
            return CUUnit::TERABYTE;
        }
        return CUUnit::BYTE;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected static $ms_mFhFromLockFp;

    // Defaults.
    protected static $ms_sThirdPartyLastUpdateTimeFn = "phred-last-update-time";
    protected static $ms_sThirdPartyConcurrLockFn = "phred-update-lock";
    protected static $ms_sThirdPartyAlreadyOopWrappedMark =
        "/*\n * The code in this file has been wrapped into OOP with PhpParser.\n * github.com/nikic/PHP-Parser\n */";
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Support for `onThirdPartyUpdateByPackageManager` method.

/**
 * @ignore
 */
class CMainVisitor extends PhpParser\NodeVisitorAbstract
{
    public function __construct ($bWrapProtectedMethods, $bWrapPrivateMethods)
    {
        $this->m_bWrapProtectedMethods = $bWrapProtectedMethods;
        $this->m_bWrapPrivateMethods = $bWrapPrivateMethods;
    }

    public function enterNode (PhpParser\Node $oNode)
    {
        if ( $oNode instanceof PhpParser\Node\Stmt\Class_ ||
             $oNode instanceof PhpParser\Node\Stmt\Trait_ )
        {
            $this->m_iNumEnteredClassesOrTraits++;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Interface_ )
        {
            $this->m_iNumEnteredInterfaces++;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\ClassMethod )
        {
            $this->m_iNumEnteredMethods++;
        }
        else if ( $oNode instanceof PhpParser\Node\Expr\Closure )
        {
            $this->m_iNumEnteredClosures++;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Function_ )
        {
            $this->m_iNumEnteredFunctions++;
        }
    }

    public function leaveNode (PhpParser\Node $oNode)
    {
        if ( $oNode instanceof PhpParser\Node\Stmt\Class_ ||
             $oNode instanceof PhpParser\Node\Stmt\Trait_ )
        {
            $iNumEnteredClassesOrTraits = $this->m_iNumEnteredClassesOrTraits;
            $this->m_iNumEnteredClassesOrTraits--;

            if ( !($iNumEnteredClassesOrTraits == 1 &&
                   $this->m_iNumEnteredInterfaces == 0 &&
                   $this->m_iNumEnteredMethods == 0 &&
                   $this->m_iNumEnteredClosures == 0 &&
                   $this->m_iNumEnteredFunctions == 0) )
            {
                return;
            }

            $oClassOrTrait = $oNode;

            $mStatements = [$oClassOrTrait];

            $oPropertyVisitor = new CPropertyVisitor();
            $oTraverser = new PhpParser\NodeTraverser();
            $oTraverser->addVisitor($oPropertyVisitor);
            $mStatements = $oTraverser->traverse($mStatements);

            $oClassOrTrait = $mStatements[0];

            $oMethodVisitor = new CMethodVisitor($this->m_bWrapProtectedMethods, $this->m_bWrapPrivateMethods,
                $oClassOrTrait instanceof PhpParser\Node\Stmt\Trait_);
            $oTraverser = new PhpParser\NodeTraverser();
            $oTraverser->addVisitor($oMethodVisitor);
            $mStatements = $oTraverser->traverse($mStatements);

            $oClassOrTrait = $mStatements[0];

            if ( isset($oClassOrTrait->stmts) && !CMap::isEmpty($oClassOrTrait->stmts) &&
                 $oPropertyVisitor->hasPropsToWrap() )
            {
                $oGetSetMMethodModifVisitor = new CGetSetMMethodModifVisitor($oPropertyVisitor->propsToWrap());
                $oTraverser = new PhpParser\NodeTraverser();
                $oTraverser->addVisitor($oGetSetMMethodModifVisitor);
                $mStatements = $oTraverser->traverse($mStatements);

                $oClassOrTrait = $mStatements[0];

                if ( !$oPropertyVisitor->hasGetMMethod() ||
                     !$oPropertyVisitor->hasSetMMethod() )
                {
                    $oPrecStmt;
                    $bLastMethodFound = false;
                    $iLenMinusOne = CMap::length($oClassOrTrait->stmts) - 1;
                    for ($i = $iLenMinusOne; $i >= 0; $i--)
                    {
                        $oStmt = $oClassOrTrait->stmts[$i];

                        if ( $oStmt instanceof PhpParser\Node\Stmt\ClassMethod )
                        {
                            $bLastMethodFound = true;
                            $oPrecStmt = $oStmt;
                            break;
                        }
                    }
                    if ( !$bLastMethodFound )
                    {
                        $oPrecStmt = $oClassOrTrait->stmts[$iLenMinusOne];
                    }

                    if ( !$oPropertyVisitor->hasGetMMethod() )
                    {
                        $oPrecStmt->setAttribute("_insertGetMMethodAfterMe", true);
                    }
                    if ( !$oPropertyVisitor->hasSetMMethod() )
                    {
                        $oPrecStmt->setAttribute("_insertSetMMethodAfterMe", true);
                    }

                    $oGetSetMMethodAddingVisitor = new CGetSetMMethodAddingVisitor($oPropertyVisitor->propsToWrap());
                    $oTraverser = new PhpParser\NodeTraverser();
                    $oTraverser->addVisitor($oGetSetMMethodAddingVisitor);
                    $mStatements = $oTraverser->traverse($mStatements);
                }
            }

            return $mStatements;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Interface_ )
        {
            $this->m_iNumEnteredInterfaces--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\ClassMethod )
        {
            $this->m_iNumEnteredMethods--;
        }
        else if ( $oNode instanceof PhpParser\Node\Expr\Closure )
        {
            $this->m_iNumEnteredClosures--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Function_ )
        {
            $iNumEnteredFunctions = $this->m_iNumEnteredFunctions;
            $this->m_iNumEnteredFunctions--;

            if ( !($this->m_iNumEnteredClassesOrTraits == 0 &&
                   $this->m_iNumEnteredInterfaces == 0 &&
                   $this->m_iNumEnteredMethods == 0 &&
                   $this->m_iNumEnteredClosures == 0 &&
                   $iNumEnteredFunctions == 1) )
            {
                return;
            }

            $oFunction = $oNode;

            if ( !isset($oFunction->stmts) || CMap::isEmpty($oFunction->stmts) )
            {
                return;
            }

            $bHasParams = false;
            $aParams = CArray::make();
            $bHasParamsByRef = false;
            $aParamsByRef = CArray::make();
            if ( isset($oFunction->params) && !CMap::isEmpty($oFunction->params) )
            {
                $bHasParams = true;
                $aParams = CArray::fromPArray($oFunction->params);
                $aParamsByRef = CArray::filter($aParams, function ($oParam)
                    {
                        return $oParam->byRef;
                    });
                $bHasParamsByRef = !CArray::isEmpty($aParamsByRef);
            }

            $oFunction->stmts[0]->setAttribute("_imFirstStmtInMethodOrFunction", true);
            $oFunction->stmts[CMap::length($oFunction->stmts) - 1]->setAttribute("_imLastStmtInMethodOrFunction", true);

            $mStatements = [$oFunction];

            $oFunctionFirstLastStmtVisitor = new CMethodOrFunctionFirstLastStmtVisitor($bHasParams, $aParams,
                $bHasParamsByRef, $aParamsByRef);
            $oTraverser = new PhpParser\NodeTraverser();
            $oTraverser->addVisitor($oFunctionFirstLastStmtVisitor);
            $mStatements = $oTraverser->traverse($mStatements);

            $oFunction = $mStatements[0];

            $oReturnVisitor = new CReturnVisitor($bHasParams, $aParams, $bHasParamsByRef, $aParamsByRef,
                $oFunction->byRef, CReturnVisitor::SUBJ_FUNCTION);
            $oTraverser = new PhpParser\NodeTraverser();
            $oTraverser->addVisitor($oReturnVisitor);
            $mStatements = $oTraverser->traverse($mStatements);

            return $mStatements;
        }
    }

    protected $m_bWrapProtectedMethods;
    protected $m_bWrapPrivateMethods;

    protected $m_iNumEnteredClassesOrTraits = 0;
    protected $m_iNumEnteredInterfaces = 0;
    protected $m_iNumEnteredMethods = 0;
    protected $m_iNumEnteredClosures = 0;
    protected $m_iNumEnteredFunctions = 0;

    protected static $ms_sIsFwCallFuncName = "_is_non_tp_call";
    protected static $ms_sFromOopFuncName = "_from_oop_tp";
    protected static $ms_sToOopFuncName = "_to_oop_tp";
    protected static $ms_sIsFwCallVarName = "_is_non_tp_call_v";
    protected static $ms_sPreToOopReturnVarName = "_pre_to_oop_r_v";
    protected static $ms_sByRefGVarName = "_dummy_by_ref";
}

/**
 * @ignore
 */
class CPropertyVisitor extends CMainVisitor
{
    public function __construct ()
    {
        $this->m_aPropsToWrap = CArray::make();
    }

    public function leaveNode (PhpParser\Node $oNode)
    {
        if ( $oNode instanceof PhpParser\Node\Stmt\Class_ ||
             $oNode instanceof PhpParser\Node\Stmt\Trait_ )
        {
            $this->m_iNumEnteredClassesOrTraits--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Interface_ )
        {
            $this->m_iNumEnteredInterfaces--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\ClassMethod )
        {
            $iNumEnteredMethods = $this->m_iNumEnteredMethods;
            $this->m_iNumEnteredMethods--;

            if ( !($this->m_iNumEnteredClassesOrTraits == 1 &&
                   $this->m_iNumEnteredInterfaces == 0 &&
                   $iNumEnteredMethods == 1 &&
                   $this->m_iNumEnteredClosures == 0 &&
                   $this->m_iNumEnteredFunctions == 0) )
            {
                return;
            }

            $oMethod = $oNode;

            if ( CString::equalsCi($oMethod->name, "__get") )
            {
                $this->m_bHasGetMMethod = true;
            }
            else if ( CString::equalsCi($oMethod->name, "__set") )
            {
                $this->m_bHasSetMMethod = true;
            }
        }
        else if ( $oNode instanceof PhpParser\Node\Expr\Closure )
        {
            $this->m_iNumEnteredClosures--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Function_ )
        {
            $this->m_iNumEnteredFunctions--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Property )
        {
            if ( !($this->m_iNumEnteredClassesOrTraits == 1 &&
                   $this->m_iNumEnteredInterfaces == 0 &&
                   $this->m_iNumEnteredMethods == 0 &&
                   $this->m_iNumEnteredClosures == 0 &&
                   $this->m_iNumEnteredFunctions == 0) )
            {
                return;
            }

            $oProperty = $oNode;

            if ( $oProperty->isProtected() ||
                 $oProperty->isPrivate() ||
                 $oProperty->isStatic() )
            {
                return;
            }

            if ( !isset($oProperty->props) || CMap::isEmpty($oProperty->props) )
            {
                return;
            }

            foreach ($oProperty->props as $oProp)
            {
                CArray::push($this->m_aPropsToWrap, $oProp->name);
            }

            $oProperty->type = PhpParser\Node\Stmt\Class_::MODIFIER_PROTECTED;
            return $oProperty;
        }
    }

    public function hasPropsToWrap ()
    {
        return !CArray::isEmpty($this->m_aPropsToWrap);
    }

    public function propsToWrap ()
    {
        return $this->m_aPropsToWrap;
    }

    public function hasGetMMethod ()
    {
        return $this->m_bHasGetMMethod;
    }

    public function hasSetMMethod ()
    {
        return $this->m_bHasSetMMethod;
    }

    private $m_aPropsToWrap;
    private $m_bHasGetMMethod = false;
    private $m_bHasSetMMethod = false;
}

/**
 * @ignore
 */
class CMethodVisitor extends CMainVisitor
{
    public function __construct ($bWrapProtectedMethods, $bWrapPrivateMethods, $bIsTrait)
    {
        parent::__construct($bWrapProtectedMethods, $bWrapPrivateMethods);
        $this->m_bIsTrait = $bIsTrait;
    }

    public function leaveNode (PhpParser\Node $oNode)
    {
        if ( $oNode instanceof PhpParser\Node\Stmt\Class_ ||
             $oNode instanceof PhpParser\Node\Stmt\Trait_ )
        {
            $this->m_iNumEnteredClassesOrTraits--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Interface_ )
        {
            $this->m_iNumEnteredInterfaces--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\ClassMethod )
        {
            $iNumEnteredMethods = $this->m_iNumEnteredMethods;
            $this->m_iNumEnteredMethods--;

            if ( !($this->m_iNumEnteredClassesOrTraits == 1 &&
                   $this->m_iNumEnteredInterfaces == 0 &&
                   $iNumEnteredMethods == 1 &&
                   $this->m_iNumEnteredClosures == 0 &&
                   $this->m_iNumEnteredFunctions == 0) )
            {
                return;
            }

            $oMethod = $oNode;

            if ( ($oMethod->isProtected() && !$this->m_bWrapProtectedMethods && !$this->m_bIsTrait) ||
                 ($oMethod->isPrivate() && !$this->m_bWrapPrivateMethods && !$this->m_bIsTrait) ||
                 $oMethod->isAbstract())
            {
                return;
            }

            if ( !isset($oMethod->stmts) || CMap::isEmpty($oMethod->stmts) )
            {
                return;
            }

            $bHasParams = false;
            $aParams = CArray::make();
            $bHasParamsByRef = false;
            $aParamsByRef = CArray::make();
            if ( isset($oMethod->params) && !CMap::isEmpty($oMethod->params) )
            {
                $bHasParams = true;
                $aParams = CArray::fromPArray($oMethod->params);
                $aParamsByRef = CArray::filter($aParams, function ($oParam)
                    {
                        return $oParam->byRef;
                    });
                $bHasParamsByRef = !CArray::isEmpty($aParamsByRef);
            }

            $oMethod->stmts[0]->setAttribute("_imFirstStmtInMethodOrFunction", true);
            $oMethod->stmts[CMap::length($oMethod->stmts) - 1]->setAttribute("_imLastStmtInMethodOrFunction", true);

            $mStatements = [$oMethod];

            $oMethodFirstLastStmtVisitor = new CMethodOrFunctionFirstLastStmtVisitor($bHasParams, $aParams,
                $bHasParamsByRef, $aParamsByRef);
            $oTraverser = new PhpParser\NodeTraverser();
            $oTraverser->addVisitor($oMethodFirstLastStmtVisitor);
            $mStatements = $oTraverser->traverse($mStatements);

            $oMethod = $mStatements[0];

            $oReturnVisitor = new CReturnVisitor($bHasParams, $aParams, $bHasParamsByRef, $aParamsByRef,
                $oMethod->byRef, CReturnVisitor::SUBJ_METHOD);
            $oTraverser = new PhpParser\NodeTraverser();
            $oTraverser->addVisitor($oReturnVisitor);
            $mStatements = $oTraverser->traverse($mStatements);

            return $mStatements;
        }
        else if ( $oNode instanceof PhpParser\Node\Expr\Closure )
        {
            $this->m_iNumEnteredClosures--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Function_ )
        {
            $this->m_iNumEnteredFunctions--;
        }
    }

    private $m_bIsTrait;
}

/**
 * @ignore
 */
class CMethodOrFunctionFirstLastStmtVisitor extends CMainVisitor
{
    public function __construct ($bHasParams, $aParams, $bHasParamsByRef, $aParamsByRef)
    {
        $this->m_bHasParams = $bHasParams;
        $this->m_aParams = $aParams;
        $this->m_bHasParamsByRef = $bHasParamsByRef;
        $this->m_aParamsByRef = $aParamsByRef;
    }

    public function leaveNode (PhpParser\Node $oNode)
    {
        $mStatements = [$oNode];
        $bModif = false;

        if ( $oNode->hasAttribute("_imFirstStmtInMethodOrFunction") )
        {
            $mPreStatements = CMap::make();
            $oAssignment = new PhpParser\Node\Expr\Assign(
                new PhpParser\Node\Expr\Variable(self::$ms_sIsFwCallVarName),
                new PhpParser\Node\Expr\FuncCall(
                new PhpParser\Node\Name(self::$ms_sIsFwCallFuncName)));
            CMap::insertValue($mPreStatements, $oAssignment);
            if ( $this->m_bHasParams )
            {
                $mPreSubStatements = CMap::make();
                $iLen = CArray::length($this->m_aParams);
                for ($i = 0; $i < $iLen; $i++)
                {
                    $oParam = $this->m_aParams[$i];

                    $oAssignment = new PhpParser\Node\Expr\Assign(
                        new PhpParser\Node\Expr\Variable($oParam->name),
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_sFromOopFuncName),
                        [new PhpParser\Node\Expr\Variable($oParam->name)]));
                    CMap::insertValue($mPreSubStatements, $oAssignment);
                }
                $oIf = new PhpParser\Node\Stmt\If_(
                    new PhpParser\Node\Expr\Variable(self::$ms_sIsFwCallVarName),
                    ["stmts" => $mPreSubStatements]);
                CMap::insertValue($mPreStatements, $oIf);
            }

            foreach ($mStatements as $oStmt)
            {
                CMap::insertValue($mPreStatements, $oStmt);
            }
            $mStatements = $mPreStatements;

            $bModif = true;
        }

        if ( $oNode->hasAttribute("_imLastStmtInMethodOrFunction") &&
             !($oNode instanceof PhpParser\Node\Stmt\Return_) &&
             $this->m_bHasParamsByRef )
        {
            $mSubStatements = CMap::make();
            $iLen = CArray::length($this->m_aParamsByRef);
            for ($i = 0; $i < $iLen; $i++)
            {
                $oParam = $this->m_aParamsByRef[$i];

                $oAssignment = new PhpParser\Node\Expr\Assign(
                    new PhpParser\Node\Expr\Variable($oParam->name),
                    new PhpParser\Node\Expr\FuncCall(
                    new PhpParser\Node\Name(self::$ms_sToOopFuncName),
                    [new PhpParser\Node\Expr\Variable($oParam->name)]));
                CMap::insertValue($mSubStatements, $oAssignment);
            }
            $oIf = new PhpParser\Node\Stmt\If_(
                new PhpParser\Node\Expr\Variable(self::$ms_sIsFwCallVarName),
                ["stmts" => $mSubStatements]);
            CMap::insertValue($mStatements, $oIf);

            $bModif = true;
        }

        if ( $bModif )
        {
            return $mStatements;
        }
    }

    private $m_bHasParams;
    private $m_aParams;
    private $m_bHasParamsByRef;
    private $m_aParamsByRef;
}

/**
 * @ignore
 */
class CReturnVisitor extends CMainVisitor
{
    const SUBJ_METHOD = 0;
    const SUBJ_FUNCTION = 1;

    public function __construct ($bHasParams, $aParams, $bHasParamsByRef, $aParamsByRef, $bReturnsAreByRef, $eSubj)
    {
        $this->m_bHasParams = $bHasParams;
        $this->m_aParams = $aParams;
        $this->m_bHasParamsByRef = $bHasParamsByRef;
        $this->m_aParamsByRef = $aParamsByRef;
        $this->m_bReturnsAreByRef = $bReturnsAreByRef;
        $this->m_eSubj = $eSubj;
    }

    public function leaveNode (PhpParser\Node $oNode)
    {
        if ( $oNode instanceof PhpParser\Node\Stmt\Class_ ||
             $oNode instanceof PhpParser\Node\Stmt\Trait_ )
        {
            $this->m_iNumEnteredClassesOrTraits--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Interface_ )
        {
            $this->m_iNumEnteredInterfaces--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\ClassMethod )
        {
            $this->m_iNumEnteredMethods--;
        }
        else if ( $oNode instanceof PhpParser\Node\Expr\Closure )
        {
            $this->m_iNumEnteredClosures--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Function_ )
        {
            $this->m_iNumEnteredFunctions--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Return_ )
        {
            if ( $this->m_eSubj == self::SUBJ_METHOD )
            {
                if ( !($this->m_iNumEnteredClassesOrTraits == 0 &&
                       $this->m_iNumEnteredInterfaces == 0 &&
                       $this->m_iNumEnteredMethods == 1 &&
                       $this->m_iNumEnteredClosures == 0 &&
                       $this->m_iNumEnteredFunctions == 0) )
                {
                    return;
                }
            }
            else  // SUBJ_FUNCTION
            {
                if ( !($this->m_iNumEnteredClassesOrTraits == 0 &&
                       $this->m_iNumEnteredInterfaces == 0 &&
                       $this->m_iNumEnteredMethods == 0 &&
                       $this->m_iNumEnteredClosures == 0 &&
                       $this->m_iNumEnteredFunctions == 1) )
                {
                    return;
                }
            }

            $oOrigReturn = $oNode;

            // `is_null` accounts for any hidden implementation details of `__isset` magic method used by NodeAbstract.
            if ( !isset($oOrigReturn->expr) || is_null($oOrigReturn->expr) )
            {
                // A bare `return`.
                if ( $this->m_bHasParamsByRef )
                {
                    $mStatements = CMap::make();
                    $mSubStatements = CMap::make();
                    $iLen = CArray::length($this->m_aParamsByRef);
                    for ($i = 0; $i < $iLen; $i++)
                    {
                        $oParam = $this->m_aParamsByRef[$i];

                        $oAssignment = new PhpParser\Node\Expr\Assign(
                            new PhpParser\Node\Expr\Variable($oParam->name),
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_sToOopFuncName),
                            [new PhpParser\Node\Expr\Variable($oParam->name)]));
                        CMap::insertValue($mSubStatements, $oAssignment);
                    }
                    $oIf = new PhpParser\Node\Stmt\If_(
                        new PhpParser\Node\Expr\Variable(self::$ms_sIsFwCallVarName),
                        ["stmts" => $mSubStatements]);
                    CMap::insertValue($mStatements, $oIf);
                    CMap::insertValue($mStatements, $oOrigReturn);
                    return $mStatements;
                }
            }
            else
            {
                // A `return` with an expression.
                if ( !$this->m_bReturnsAreByRef )
                {
                    if ( !$this->m_bHasParamsByRef )
                    {
                        $mStatements = CMap::make();

                        $mSubStatements0 = [$oOrigReturn];

                        $mSubStatements1 = CMap::make();
                        $oReturn = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_sToOopFuncName),
                            [$oOrigReturn->expr]));
                        CMap::insertValue($mSubStatements1, $oReturn);
                        $oElse = new PhpParser\Node\Stmt\Else_($mSubStatements1);

                        $oIf = new PhpParser\Node\Stmt\If_(
                            new PhpParser\Node\Expr\BooleanNot(
                            new PhpParser\Node\Expr\Variable(self::$ms_sIsFwCallVarName)),
                            ["stmts" => $mSubStatements0, "else" => $oElse]);
                        CMap::insertValue($mStatements, $oIf);

                        return $mStatements;
                    }
                    else
                    {
                        $mStatements = CMap::make();

                        $mSubStatements0 = [$oOrigReturn];

                        $mSubStatements1 = CMap::make();
                        $oAssignment = new PhpParser\Node\Expr\Assign(
                            new PhpParser\Node\Expr\Variable(self::$ms_sPreToOopReturnVarName),
                            $oOrigReturn->expr);
                        CMap::insertValue($mSubStatements1, $oAssignment);
                        $iLen = CArray::length($this->m_aParamsByRef);
                        for ($i = 0; $i < $iLen; $i++)
                        {
                            $oParam = $this->m_aParamsByRef[$i];

                            $oAssignment = new PhpParser\Node\Expr\Assign(
                                new PhpParser\Node\Expr\Variable($oParam->name),
                                new PhpParser\Node\Expr\FuncCall(
                                new PhpParser\Node\Name(self::$ms_sToOopFuncName),
                                [new PhpParser\Node\Expr\Variable($oParam->name)]));
                            CMap::insertValue($mSubStatements1, $oAssignment);
                        }
                        $oReturn = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_sToOopFuncName),
                            [new PhpParser\Node\Expr\Variable(self::$ms_sPreToOopReturnVarName)]));
                        CMap::insertValue($mSubStatements1, $oReturn);
                        $oElse = new PhpParser\Node\Stmt\Else_($mSubStatements1);

                        $oIf = new PhpParser\Node\Stmt\If_(
                            new PhpParser\Node\Expr\BooleanNot(
                            new PhpParser\Node\Expr\Variable(self::$ms_sIsFwCallVarName)),
                            ["stmts" => $mSubStatements0, "else" => $oElse]);
                        CMap::insertValue($mStatements, $oIf);

                        return $mStatements;
                    }
                }
                else
                {
                    if ( !$this->m_bHasParamsByRef )
                    {
                        $mStatements = CMap::make();

                        $mSubStatements0 = [$oOrigReturn];

                        $mSubStatements1 = CMap::make();
                        $oAssignment = new PhpParser\Node\Expr\Assign(
                            new PhpParser\Node\Expr\ArrayDimFetch(
                            new PhpParser\Node\Expr\Variable("GLOBALS"),
                            new PhpParser\Node\Scalar\String(self::$ms_sByRefGVarName)),
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_sToOopFuncName),
                            [$oOrigReturn->expr]));
                        CMap::insertValue($mSubStatements1, $oAssignment);
                        $oReturn = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\ArrayDimFetch(
                            new PhpParser\Node\Expr\Variable("GLOBALS"),
                            new PhpParser\Node\Scalar\String(self::$ms_sByRefGVarName)));
                        CMap::insertValue($mSubStatements1, $oReturn);
                        $oElse = new PhpParser\Node\Stmt\Else_($mSubStatements1);

                        $oIf = new PhpParser\Node\Stmt\If_(
                            new PhpParser\Node\Expr\BooleanNot(
                            new PhpParser\Node\Expr\Variable(self::$ms_sIsFwCallVarName)),
                            ["stmts" => $mSubStatements0, "else" => $oElse]);
                        CMap::insertValue($mStatements, $oIf);

                        return $mStatements;
                    }
                    else
                    {
                        $mStatements = CMap::make();

                        $mSubStatements0 = [$oOrigReturn];

                        $mSubStatements1 = CMap::make();
                        $oAssignment = new PhpParser\Node\Expr\Assign(
                            new PhpParser\Node\Expr\ArrayDimFetch(
                            new PhpParser\Node\Expr\Variable("GLOBALS"),
                            new PhpParser\Node\Scalar\String(self::$ms_sByRefGVarName)),
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_sToOopFuncName),
                            [$oOrigReturn->expr]));
                        CMap::insertValue($mSubStatements1, $oAssignment);
                        $iLen = CArray::length($this->m_aParamsByRef);
                        for ($i = 0; $i < $iLen; $i++)
                        {
                            $oParam = $this->m_aParamsByRef[$i];

                            $oAssignment = new PhpParser\Node\Expr\Assign(
                                new PhpParser\Node\Expr\Variable($oParam->name),
                                new PhpParser\Node\Expr\FuncCall(
                                new PhpParser\Node\Name(self::$ms_sToOopFuncName),
                                [new PhpParser\Node\Expr\Variable($oParam->name)]));
                            CMap::insertValue($mSubStatements1, $oAssignment);
                        }
                        $oReturn = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\ArrayDimFetch(
                            new PhpParser\Node\Expr\Variable("GLOBALS"),
                            new PhpParser\Node\Scalar\String(self::$ms_sByRefGVarName)));
                        CMap::insertValue($mSubStatements1, $oReturn);
                        $oElse = new PhpParser\Node\Stmt\Else_($mSubStatements1);

                        $oIf = new PhpParser\Node\Stmt\If_(
                            new PhpParser\Node\Expr\BooleanNot(
                            new PhpParser\Node\Expr\Variable(self::$ms_sIsFwCallVarName)),
                            ["stmts" => $mSubStatements0, "else" => $oElse]);
                        CMap::insertValue($mStatements, $oIf);

                        return $mStatements;
                    }
                }
            }
        }
    }

    private $m_bHasParams;
    private $m_aParams;
    private $m_bHasParamsByRef;
    private $m_aParamsByRef;
    private $m_bReturnsAreByRef;
    private $m_eSubj;
}

/**
 * @ignore
 */
class CGetSetMMethodModifVisitor extends CMainVisitor
{
    public function __construct ($aPropsToWrap)
    {
        $this->m_aPropsToWrap = $aPropsToWrap;
    }

    public function leaveNode (PhpParser\Node $oNode)
    {
        if ( $oNode instanceof PhpParser\Node\Stmt\Class_ ||
             $oNode instanceof PhpParser\Node\Stmt\Trait_ )
        {
            $this->m_iNumEnteredClassesOrTraits--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Interface_ )
        {
            $this->m_iNumEnteredInterfaces--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\ClassMethod )
        {
            $iNumEnteredMethods = $this->m_iNumEnteredMethods;
            $this->m_iNumEnteredMethods--;

            if ( !($this->m_iNumEnteredClassesOrTraits == 1 &&
                   $this->m_iNumEnteredInterfaces == 0 &&
                   $iNumEnteredMethods == 1 &&
                   $this->m_iNumEnteredClosures == 0 &&
                   $this->m_iNumEnteredFunctions == 0) )
            {
                return;
            }

            $oMethod = $oNode;

            if ( CString::equalsCi($oMethod->name, "__get") && CMap::length($oMethod->params) >= 1 )
            {
                $mStatements = CMap::make();

                $iLen = CArray::length($this->m_aPropsToWrap);
                for ($i = 0; $i < $iLen; $i++)
                {
                    $sPropName = $this->m_aPropsToWrap[$i];

                    $oSubCondition = new PhpParser\Node\Expr\BooleanNot(
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_sIsFwCallFuncName)));
                    $oSubIf;
                    if ( !$oMethod->byRef )
                    {
                        $oReturn0 = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\PropertyFetch(
                            new PhpParser\Node\Expr\Variable("this"), $sPropName));
                        $oReturn1 = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_sToOopFuncName),
                            [new PhpParser\Node\Expr\PropertyFetch(
                            new PhpParser\Node\Expr\Variable("this"), $sPropName)]));
                        $oElse = new PhpParser\Node\Stmt\Else_([$oReturn1]);
                        $oSubIf = new PhpParser\Node\Stmt\If_($oSubCondition,
                            ["stmts" => [$oReturn0], "else" => $oElse]);
                    }
                    else
                    {
                        $mSubStatements0 = CMap::make();
                        $oReturn = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\PropertyFetch(
                            new PhpParser\Node\Expr\Variable("this"), $sPropName));
                        CMap::insertValue($mSubStatements0, $oReturn);

                        $mSubStatements1 = CMap::make();
                        $oAssignment = new PhpParser\Node\Expr\Assign(
                            new PhpParser\Node\Expr\ArrayDimFetch(
                            new PhpParser\Node\Expr\Variable("GLOBALS"),
                            new PhpParser\Node\Scalar\String(self::$ms_sByRefGVarName)),
                            new PhpParser\Node\Expr\FuncCall(
                            new PhpParser\Node\Name(self::$ms_sToOopFuncName),
                            [new PhpParser\Node\Expr\PropertyFetch(
                            new PhpParser\Node\Expr\Variable("this"), $sPropName)]));
                        CMap::insertValue($mSubStatements1, $oAssignment);
                        $oReturn = new PhpParser\Node\Stmt\Return_(
                            new PhpParser\Node\Expr\ArrayDimFetch(
                            new PhpParser\Node\Expr\Variable("GLOBALS"),
                            new PhpParser\Node\Scalar\String(self::$ms_sByRefGVarName)));
                        CMap::insertValue($mSubStatements1, $oReturn);
                        $oElse = new PhpParser\Node\Stmt\Else_($mSubStatements1);
                        $oSubIf = new PhpParser\Node\Stmt\If_($oSubCondition,
                            ["stmts" => $mSubStatements0, "else" => $oElse]);
                    }

                    $oCondition = new PhpParser\Node\Expr\BinaryOp\Identical(
                        new PhpParser\Node\Expr\Variable($oMethod->params[0]->name),
                        new PhpParser\Node\Scalar\String($sPropName));
                    $oIf = new PhpParser\Node\Stmt\If_($oCondition, ["stmts" => [$oSubIf]]);
                    CMap::insertValue($mStatements, $oIf);
                }

                if ( isset($oMethod->stmts) )
                {
                    foreach ($oMethod->stmts as $oStmt)
                    {
                        CMap::insertValue($mStatements, $oStmt);
                    }
                }

                $oMethod->stmts = $mStatements;
                return $oMethod;
            }
            else if ( CString::equalsCi($oMethod->name, "__set") && CMap::length($oMethod->params) >= 2 )
            {
                $mStatements = CMap::make();

                $iLen = CArray::length($this->m_aPropsToWrap);
                for ($i = 0; $i < $iLen; $i++)
                {
                    $sPropName = $this->m_aPropsToWrap[$i];

                    $oSubCondition = new PhpParser\Node\Expr\BooleanNot(
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_sIsFwCallFuncName)));
                    $oAssignment0 = new PhpParser\Node\Expr\Assign(
                        new PhpParser\Node\Expr\PropertyFetch(
                        new PhpParser\Node\Expr\Variable("this"), $sPropName),
                        new PhpParser\Node\Expr\Variable($oMethod->params[1]->name));
                    $oAssignment1 = new PhpParser\Node\Expr\Assign(
                        new PhpParser\Node\Expr\PropertyFetch(
                        new PhpParser\Node\Expr\Variable("this"), $sPropName),
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_sFromOopFuncName),
                        [new PhpParser\Node\Expr\Variable($oMethod->params[1]->name)]));
                    $oElse = new PhpParser\Node\Stmt\Else_([$oAssignment1]);
                    $oSubIf = new PhpParser\Node\Stmt\If_($oSubCondition,
                        ["stmts" => [$oAssignment0], "else" => $oElse]);

                    $oCondition = new PhpParser\Node\Expr\BinaryOp\Identical(
                        new PhpParser\Node\Expr\Variable($oMethod->params[0]->name),
                        new PhpParser\Node\Scalar\String($sPropName));
                    $oIf = new PhpParser\Node\Stmt\If_($oCondition, ["stmts" => [$oSubIf]]);
                    CMap::insertValue($mStatements, $oIf);
                }

                if ( isset($oMethod->stmts) )
                {
                    foreach ($oMethod->stmts as $oStmt)
                    {
                        CMap::insertValue($mStatements, $oStmt);
                    }
                }

                $oMethod->stmts = $mStatements;
                return $oMethod;
            }
        }
        else if ( $oNode instanceof PhpParser\Node\Expr\Closure )
        {
            $this->m_iNumEnteredClosures--;
        }
        else if ( $oNode instanceof PhpParser\Node\Stmt\Function_ )
        {
            $this->m_iNumEnteredFunctions--;
        }
    }

    private $m_aPropsToWrap;
}

/**
 * @ignore
 */
class CGetSetMMethodAddingVisitor extends CMainVisitor
{
    public function __construct ($aPropsToWrap)
    {
        $this->m_aPropsToWrap = $aPropsToWrap;
    }

    public function leaveNode (PhpParser\Node $oNode)
    {
        if ( $oNode->hasAttribute("_insertGetMMethodAfterMe") ||
             $oNode->hasAttribute("_insertSetMMethodAfterMe") )
        {
            $mStatements = [$oNode];

            if ( $oNode->hasAttribute("_insertGetMMethodAfterMe") )
            {
                $mSubStatements = CMap::make();
                $iLen = CArray::length($this->m_aPropsToWrap);
                for ($i = 0; $i < $iLen; $i++)
                {
                    $sPropName = $this->m_aPropsToWrap[$i];

                    $oSubCondition = new PhpParser\Node\Expr\BooleanNot(
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_sIsFwCallFuncName)));
                    $oReturn0 = new PhpParser\Node\Stmt\Return_(
                        new PhpParser\Node\Expr\PropertyFetch(
                        new PhpParser\Node\Expr\Variable("this"), $sPropName));
                    $oReturn1 = new PhpParser\Node\Stmt\Return_(
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_sToOopFuncName),
                        [new PhpParser\Node\Expr\PropertyFetch(
                        new PhpParser\Node\Expr\Variable("this"), $sPropName)]));
                    $oElse = new PhpParser\Node\Stmt\Else_([$oReturn1]);
                    $oSubIf = new PhpParser\Node\Stmt\If_($oSubCondition,
                        ["stmts" => [$oReturn0], "else" => $oElse]);

                    $oCondition = new PhpParser\Node\Expr\BinaryOp\Identical(
                        new PhpParser\Node\Expr\Variable("name"),
                        new PhpParser\Node\Scalar\String($sPropName));
                    $oIf = new PhpParser\Node\Stmt\If_($oCondition, ["stmts" => [$oSubIf]]);
                    CMap::insertValue($mSubStatements, $oIf);
                }
                $oMethod = new PhpParser\Node\Stmt\ClassMethod("__get", [
                    "type" => PhpParser\Node\Stmt\Class_::MODIFIER_PUBLIC,
                    "byRef" => true,
                    "params" => [new PhpParser\Node\Param("name")],
                    "stmts" => $mSubStatements]);
                CMap::insertValue($mStatements, $oMethod);
            }

            if ( $oNode->hasAttribute("_insertSetMMethodAfterMe") )
            {
                $mSubStatements = CMap::make();
                $iLen = CArray::length($this->m_aPropsToWrap);
                for ($i = 0; $i < $iLen; $i++)
                {
                    $sPropName = $this->m_aPropsToWrap[$i];

                    $oSubCondition = new PhpParser\Node\Expr\BooleanNot(
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_sIsFwCallFuncName)));
                    $oAssignment0 = new PhpParser\Node\Expr\Assign(
                        new PhpParser\Node\Expr\PropertyFetch(
                        new PhpParser\Node\Expr\Variable("this"), $sPropName),
                        new PhpParser\Node\Expr\Variable("value"));
                    $oAssignment1 = new PhpParser\Node\Expr\Assign(
                        new PhpParser\Node\Expr\PropertyFetch(
                        new PhpParser\Node\Expr\Variable("this"), $sPropName),
                        new PhpParser\Node\Expr\FuncCall(
                        new PhpParser\Node\Name(self::$ms_sFromOopFuncName),
                        [new PhpParser\Node\Expr\Variable("value")]));
                    $oElse = new PhpParser\Node\Stmt\Else_([$oAssignment1]);
                    $oSubIf = new PhpParser\Node\Stmt\If_($oSubCondition,
                        ["stmts" => [$oAssignment0], "else" => $oElse]);

                    $oCondition = new PhpParser\Node\Expr\BinaryOp\Identical(
                        new PhpParser\Node\Expr\Variable("name"),
                        new PhpParser\Node\Scalar\String($sPropName));
                    $oIf = new PhpParser\Node\Stmt\If_($oCondition, ["stmts" => [$oSubIf]]);
                    CMap::insertValue($mSubStatements, $oIf);
                }
                $oMethod = new PhpParser\Node\Stmt\ClassMethod("__set", [
                    "type" => PhpParser\Node\Stmt\Class_::MODIFIER_PUBLIC,
                    "params" => [new PhpParser\Node\Param("name"), new PhpParser\Node\Param("value")],
                    "stmts" => $mSubStatements]);
                CMap::insertValue($mStatements, $oMethod);
            }

            return $mStatements;
        }
    }

    private $m_aPropsToWrap;
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
