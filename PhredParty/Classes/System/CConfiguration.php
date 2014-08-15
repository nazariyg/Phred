<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * A few static methods for getting and setting the configuration options of the web application.
 *
 * **You can refer to this class by its alias, which is** `Cfg`.
 *
 * The names and the default values of the application's configuration options are located in the files of the
 * Configuration directory that can be found inside the Application directory. The configuration options are organized
 * into categories and each category corresponds to a particular file in the Configuration directory and is named after
 * it. At the time of initialization, the options of each of the top-level configuration files are merged with the
 * options of the same-named file of the current environment if such file exists in the environment's directory inside
 * the Configuration/Environments directory. With `option` and `setOption` methods, a configuration option is accessed
 * by its path where the category's name is indicated by the first component. For instance, "Debug.logging.enable" path
 * refers to "logging.enable" option in the Debug category. Aliases are assigned for some categories so that e.g. "db"
 * and "Database" are used to refer to the same category. Currently, the category aliases are as follows:
 *
 * * app (Application)
 * * db (Database)
 * * sess (Session)
 * * upd (Updates)
 *
 * The `appOption` and `setAppOption` methods serve as shortcuts to the main configuration options that are located
 * under the "Application" category.
 */

// Method signatures:
//   static mixed option ($sPath)
//   static void setOption ($sPath, $xValue)
//   static mixed appOption ($sOptionName)
//   static void setAppOption ($sOptionName, $xValue)

class CConfiguration extends CRootClass
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of the application's configuration option specified by a global path.
     *
     * @param  string $sPath The global path to the option.
     *
     * @return mixed The option's value.
     */

    public static function option ($sPath)
    {
        assert( 'is_cstring($sPath)', vs(isset($this), get_defined_vars()) );

        $xValue = CMap::valueByPath(self::$ms_mConfig, $sPath);
        return ( is_bool($xValue) ) ? $xValue : oop_x($xValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the value of the application's configuration option specified by a global path.
     *
     * @param  string $sPath The global path to the option.
     * @param  mixed $xValue The new value.
     *
     * @return void
     */

    public static function setOption ($sPath, $xValue)
    {
        assert( 'is_cstring($sPath)', vs(isset($this), get_defined_vars()) );
        CMap::setValueByPath(self::$ms_mConfig, $sPath, $xValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of an application's configuration option under the "Application" branch.
     *
     * @param  string $sOptionName The name of the option, under the "Application" branch.
     *
     * @return mixed The option's value.
     */

    public static function appOption ($sOptionName)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );

        $xValue = self::$ms_mConfig[self::$ms_mConfigAliases["Application"]][$sOptionName];
        return ( is_bool($xValue) ) ? $xValue : oop_x($xValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the value of an application's configuration option under the "Application" branch.
     *
     * @param  string $sOptionName The name of the option, under the "Application" branch.
     * @param  mixed $xValue The new value.
     *
     * @return void
     */

    public static function setAppOption ($sOptionName, $xValue)
    {
        assert( 'is_cstring($sOptionName)', vs(isset($this), get_defined_vars()) );
        self::$ms_mConfig[self::$ms_mConfigAliases["Application"]][$sOptionName] = $xValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function isInitialized ()
    {
        return self::$ms_bIsInitialized;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function initialize ()
    {
        $sConfigDp = CFilePath::add($GLOBALS["PHRED_PATH_TO_APP"], "Configuration");
        $sConfigEnvsDp = CFilePath::add($sConfigDp, "Environments");

        $aConfigs = CArray::make();
        $sCurrEnv;

        if ( $GLOBALS["PHRED_TESTS"] )
        {
            $sCurrEnv = "tst";
        }

        // Main configuration files.
        $aConfigFps = CFile::findFiles(CFilePath::add($sConfigDp, "*.json"));
        $iNumConfigs = CArray::length($aConfigFps);
        for ($i = 0; $i < $iNumConfigs; $i++)
        {
            $sConfigFp = $aConfigFps[$i];
            $sConfigName = CFilePath::nameOnly($sConfigFp);
            self::readAndAddConfig($sConfigFp, $sConfigName, $aConfigs);

            if ( !isset($sCurrEnv) && CString::equals($sConfigName, "Application") )
            {
                $mConfig = CArray::last($aConfigs);
                $sCurrEnv = $mConfig[self::$ms_mConfigAliases["Application"]]["environment"];
            }
        }
        assert( 'is_cstring($sCurrEnv)', vs(isset($this), get_defined_vars()) );

        // The configuration files from the current environment's directory.
        $sCurrEnvDp = CFilePath::add($sConfigEnvsDp, $sCurrEnv);
        assert( 'CFile::exists($sCurrEnvDp)', vs(isset($this), get_defined_vars()) );
        $aConfigFps = CFile::findFiles(CFilePath::add($sCurrEnvDp, "*.json"));
        $iNumConfigs = CArray::length($aConfigFps);
        for ($i = 0; $i < $iNumConfigs; $i++)
        {
            $sConfigFp = $aConfigFps[$i];
            $sConfigName = CFilePath::nameOnly($sConfigFp);
            self::readAndAddConfig($sConfigFp, $sConfigName, $aConfigs);
        }

        self::$ms_mConfig = call_user_func_array("CMap::merge", CArray::toPArray($aConfigs));
        self::$ms_bIsInitialized = true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function readAndAddConfig ($sConfigFp, $sConfigName, $aConfigs)
    {
        if ( CMap::hasKey(self::$ms_mConfigAliases, $sConfigName) )
        {
            $sConfigName = self::$ms_mConfigAliases[$sConfigName];
        }
        $sConfigName = CString::toLowerCase($sConfigName);

        $sConfigJson = CFile::read($sConfigFp);
        $sConfigJson = CRegex::remove($sConfigJson, "/^\\h*\\/\\/.*/m");  // remove comments
        $sConfigJson = "{\"$sConfigName\": $sConfigJson}";

        $oJson = new CJson($sConfigJson);
        $bSuccess;
        $mConfig = $oJson->decode($bSuccess);
        assert( '$bSuccess', vs(isset($this), get_defined_vars()) );
        CArray::push($aConfigs, $mConfig);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected static $ms_bIsInitialized = false;
    protected static $ms_mConfig;

    // Some of the configuration names are just too lengthy.
    protected static $ms_mConfigAliases = [
        "Application" => "app",
        "Database" => "db",
        "Session" => "sess",
        "Updates" => "upd"];
}
