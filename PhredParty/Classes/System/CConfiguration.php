<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
//   static mixed option ($path)
//   static void setOption ($path, $value)
//   static mixed appOption ($optionName)
//   static void setAppOption ($optionName, $value)

class CConfiguration extends CRootClass
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of the application's configuration option specified by a global path.
     *
     * @param  string $path The global path to the option.
     *
     * @return mixed The option's value.
     */

    public static function option ($path)
    {
        assert( 'is_cstring($path)', vs(isset($this), get_defined_vars()) );

        $value = CMap::valueByPath(self::$ms_config, $path);
        return ( is_bool($value) ) ? $value : oop_x($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the value of the application's configuration option specified by a global path.
     *
     * @param  string $path The global path to the option.
     * @param  mixed $value The new value.
     *
     * @return void
     */

    public static function setOption ($path, $value)
    {
        assert( 'is_cstring($path)', vs(isset($this), get_defined_vars()) );
        CMap::setValueByPath(self::$ms_config, $path, $value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of an application's configuration option under the "Application" branch.
     *
     * @param  string $optionName The name of the option, under the "Application" branch.
     *
     * @return mixed The option's value.
     */

    public static function appOption ($optionName)
    {
        assert( 'is_cstring($optionName)', vs(isset($this), get_defined_vars()) );

        $value = self::$ms_config[self::$ms_configAliases["Application"]][$optionName];
        return ( is_bool($value) ) ? $value : oop_x($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the value of an application's configuration option under the "Application" branch.
     *
     * @param  string $optionName The name of the option, under the "Application" branch.
     * @param  mixed $value The new value.
     *
     * @return void
     */

    public static function setAppOption ($optionName, $value)
    {
        assert( 'is_cstring($optionName)', vs(isset($this), get_defined_vars()) );
        self::$ms_config[self::$ms_configAliases["Application"]][$optionName] = $value;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function isInitialized ()
    {
        return self::$ms_isInitialized;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function initialize ()
    {
        $configDp = CFilePath::add($GLOBALS["PHRED_PATH_TO_APP"], "Configuration");
        $configEnvsDp = CFilePath::add($configDp, "Environments");

        $configs = CArray::make();
        $currEnv;

        if ( $GLOBALS["PHRED_TESTS"] )
        {
            $currEnv = "tst";
        }

        // Main configuration files.
        $configFps = CFile::findFiles(CFilePath::add($configDp, "*.json"));
        $numConfigs = CArray::length($configFps);
        for ($i = 0; $i < $numConfigs; $i++)
        {
            $configFp = $configFps[$i];
            $configName = CFilePath::nameOnly($configFp);
            self::readAndAddConfig($configFp, $configName, $configs);

            if ( !isset($currEnv) && CString::equals($configName, "Application") )
            {
                $config = CArray::last($configs);
                $currEnv = $config[self::$ms_configAliases["Application"]]["environment"];
            }
        }
        assert( 'is_cstring($currEnv)', vs(isset($this), get_defined_vars()) );

        // The configuration files from the current environment's directory.
        $currEnvDp = CFilePath::add($configEnvsDp, $currEnv);
        assert( 'CFile::exists($currEnvDp)', vs(isset($this), get_defined_vars()) );
        $configFps = CFile::findFiles(CFilePath::add($currEnvDp, "*.json"));
        $numConfigs = CArray::length($configFps);
        for ($i = 0; $i < $numConfigs; $i++)
        {
            $configFp = $configFps[$i];
            $configName = CFilePath::nameOnly($configFp);
            self::readAndAddConfig($configFp, $configName, $configs);
        }

        self::$ms_config = call_user_func_array("CMap::merge", CArray::toPArray($configs));
        self::$ms_isInitialized = true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function readAndAddConfig ($configFp, $configName, $configs)
    {
        if ( CMap::hasKey(self::$ms_configAliases, $configName) )
        {
            $configName = self::$ms_configAliases[$configName];
        }
        $configName = CString::toLowerCase($configName);

        $configJson = CFile::read($configFp);
        $configJson = CRegex::remove($configJson, "/^\\h*\\/\\/.*/m");  // remove comments
        $configJson = "{\"$configName\": $configJson}";

        $json = new CJson($configJson);
        $success;
        $config = $json->decode($success);
        assert( '$success', vs(isset($this), get_defined_vars()) );
        CArray::push($configs, $config);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected static $ms_isInitialized = false;
    protected static $ms_config;

    // Some of the configuration names are just too lengthy.
    protected static $ms_configAliases = [
        "Application" => "app",
        "Database" => "db",
        "Session" => "sess",
        "Updates" => "upd"];
}
