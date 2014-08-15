<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * A squad of static methods that track file system paths for various information.
 *
 * **You can refer to this class by its alias, which is** `Fp`.
 *
 * Except for `absolute` method, it is not required for any path that you provide to any method of the class to lead to
 * an existing file or directory.
 */

// Method signatures:
//   static CUStringObject name ($sPath)
//   static CUStringObject directory ($sPath)
//   static CUStringObject nameOnly ($sPath)
//   static CUStringObject extension ($sPath)
//   static CUStringObject lastExtension ($sPath)
//   static CUStringObject add ($sBasePath, $sComponent)
//   static CUStringObject normalize ($sPath, $bTargetIsExecutable = false)
//   static CUStringObject absolute ($sPath)
//   static bool isAbsolute ($sPath)
//   static CUStringObject frameworkPath ($sPath)

class CFilePath extends CRootClass
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the full name of the file or directory located at a specified path.
     *
     * The returned string contains the last component of the path i.e. everything what follows the last "/" in the
     * path, including any file extension(s), or the original path (after normalization) if there are no "/" in the
     * path.
     *
     * As special cases, the method returns "/", "..", or "." if the path (after normalization) is respectively "/",
     * "..", or ".".
     *
     * @param  string $sPath The path to the file or directory (can be absolute or relative).
     *
     * @return CUStringObject The full name of the file or directory.
     */

    public static function name ($sPath)
    {
        assert( 'is_cstring($sPath)', vs(isset($this), get_defined_vars()) );
        assert( '!CString::isEmpty($sPath)', vs(isset($this), get_defined_vars()) );

        $sPath = self::normalize($sPath);
        if ( CString::find($sPath, "/") )
        {
            $sFoundString;
            CRegex::find($sPath, "/(?<=\\/)[^\\/]*\\z/", $sFoundString);
            if ( CString::isEmpty($sFoundString) )
            {
                $sFoundString = "/";
            }
            return $sFoundString;
        }
        else
        {
            return $sPath;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path to the directory that contains the file or directory located at a specified path.
     *
     * The path should not be "/", "..", "." or be equivalent to any of them.
     *
     * @param  string $sPath The path to the file or directory (can be absolute or relative).
     *
     * @return CUStringObject The path to the directory (after normalization) that contains the file or directory
     * located at the path specified.
     */

    public static function directory ($sPath)
    {
        assert( 'is_cstring($sPath)', vs(isset($this), get_defined_vars()) );
        assert( '!CString::isEmpty($sPath)', vs(isset($this), get_defined_vars()) );

        $sPath = self::normalize($sPath);
        if ( CString::equals($sPath, "..") || CString::equals($sPath, ".") )
        {
            return ".";
        }
        if ( CString::find($sPath, "/") )
        {
            $sFoundString;
            CRegex::find($sPath, "/^.*(?=\\/)/", $sFoundString);
            if ( CString::isEmpty($sFoundString) )
            {
                $sFoundString = "/";
            }
            return $sFoundString;
        }
        else
        {
            return ".";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the name of the file or directory located at a specified path, without any extensions.
     *
     * As special cases, the method returns "/", "..", or "." if the path (after normalization) is respectively "/",
     * "..", or ".".
     *
     * @param  string $sPath The path to the file or directory (can be absolute or relative).
     *
     * @return CUStringObject The name of the file or directory, without any extensions.
     */

    public static function nameOnly ($sPath)
    {
        assert( 'is_cstring($sPath)', vs(isset($this), get_defined_vars()) );

        $sName = self::name($sPath);
        if ( CString::equals($sName, "..") || CString::equals($sName, ".") )
        {
            return $sName;
        }
        $sFoundString;
        CRegex::find($sName, "/^.*?(?=\\.|\\z)/", $sFoundString);
        return $sFoundString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the full extension of the file located at a specified path.
     *
     * For example, the full extension of "/path/to/file.tar.gz" is "tar.gz".
     *
     * @param  string $sPath The path to the file (can be absolute or relative).
     *
     * @return CUStringObject The full extension of the file.
     */

    public static function extension ($sPath)
    {
        assert( 'is_cstring($sPath)', vs(isset($this), get_defined_vars()) );
        assert( '!CString::isEmpty($sPath)', vs(isset($this), get_defined_vars()) );

        $sFoundString;
        if ( CRegex::find($sPath, "/(?<=\\.(?!\\.))[^\\/]+\\z/", $sFoundString) )
        {
            return $sFoundString;
        }
        else
        {
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns only the last extension of the file located at a specified path.
     *
     * For example, the last extension of "/path/to/file.tar.gz" is "gz".
     *
     * @param  string $sPath The path to the file (can be absolute or relative).
     *
     * @return CUStringObject The last extension of the file.
     */

    public static function lastExtension ($sPath)
    {
        assert( 'is_cstring($sPath)', vs(isset($this), get_defined_vars()) );
        assert( '!CString::isEmpty($sPath)', vs(isset($this), get_defined_vars()) );

        $sFoundString;
        if ( CRegex::find($sPath, "/(?<=\\.)[^\\/.]+\\z/", $sFoundString) )
        {
            return $sFoundString;
        }
        else
        {
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds a path component to a path, separating them with a slash if needed, and returns the combined path.
     *
     * If the specified path component already starts with "/", it still results in a single "/" as the separator. And,
     * as a special case, if the base path is empty, the returned string is the same as the specified path component.
     *
     * @param  string $sBasePath The base path (can be absolute or relative).
     * @param  string $sComponent The path component to be added to the base path (cannot be absolute).
     *
     * @return CUStringObject The combined path.
     */

    public static function add ($sBasePath, $sComponent)
    {
        assert( 'is_cstring($sBasePath) && is_cstring($sComponent)', vs(isset($this), get_defined_vars()) );
        assert( '!CString::isEmpty($sComponent)', vs(isset($this), get_defined_vars()) );

        if ( CString::isEmpty($sBasePath) )
        {
            return $sComponent;
        }
        if ( !CString::endsWith($sBasePath, "/") )
        {
            $sBasePath .= "/";
        }
        return $sBasePath . $sComponent;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes a path by removing any trailing slashes, any redundant slashes, any references to the current
     * directory, and any references to the parent directory where possible, and returns the new path.
     *
     * For example, "/path//./dir-a/.././to//../dir-b/" is normalized to "/path/dir-b".
     *
     * @param  string $sPath The path to be normalized (can be absolute or relative).
     * @param  bool $bTargetIsExecutable **OPTIONAL. Default is** `false`. Tells whether the path's target should be
     * treated as an executable so that, if the path starts with ".", the resulting path will start with "." too and
     * the "." will not be removed as a reference to the current directory.
     *
     * @return CUStringObject The normalized path.
     */

    public static function normalize ($sPath, $bTargetIsExecutable = false)
    {
        assert( 'is_cstring($sPath) && is_bool($bTargetIsExecutable)', vs(isset($this), get_defined_vars()) );
        assert( '!CString::isEmpty($sPath)', vs(isset($this), get_defined_vars()) );

        $sPath = CRegex::replace($sPath, "/\\/{2,}/", "/");  // normalize consecutive slashes
        $sPath = CString::stripEnd($sPath, "/");  // remove the trailing slash, if any
        if ( CString::isEmpty($sPath) )
        {
            return "/";
        }
        $sPath = CRegex::remove($sPath, "/\\/\\.(?=\\/|\\z)/");  // remove any "/." followed by a slash or at the end
        if ( CString::isEmpty($sPath) )
        {
            return "/";
        }
        if ( !$bTargetIsExecutable )
        {
            $sPath = CString::stripStart($sPath, "./");
        }

        $bPathIsAbsolute;

        if ( !CString::startsWith($sPath, "/") )
        {
            $bPathIsAbsolute = false;
        }
        else
        {
            $bPathIsAbsolute = true;
            $sPath = CString::substr($sPath, 1);
        }

        if ( !CString::find($sPath, "/") )
        {
            if ( $bPathIsAbsolute )
            {
                if ( !CString::equals($sPath, "..") )
                {
                    $sPath = "/$sPath";
                }
                else
                {
                    $sPath = "/";
                }
            }
            return $sPath;
        }

        // Recompose the path.
        $aComponents = CString::split($sPath, "/");
        $aNewComponents = CArray::make();
        $iLen = CArray::length($aComponents);
        for ($i = 0; $i < $iLen; $i++)
        {
            $sComp = $aComponents[$i];
            $sLastAddedComp = "";
            $bNoCompsAddedYet = CArray::isEmpty($aNewComponents);
            if ( !$bNoCompsAddedYet )
            {
                $sLastAddedComp = CArray::last($aNewComponents);
            }
            if ( CString::equals($sComp, "..") )
            {
                if ( $bNoCompsAddedYet ||
                     CString::equals($sLastAddedComp, "..") || CString::equals($sLastAddedComp, ".") )
                {
                    if ( !($bNoCompsAddedYet && $bPathIsAbsolute) )
                    {
                        CArray::push($aNewComponents, $sComp);
                    }
                }
                else
                {
                    CArray::pop($aNewComponents);
                }
            }
            else
            {
                CArray::push($aNewComponents, $sComp);
            }
        }
        $sPath = CArray::join($aNewComponents, "/");
        if ( $bPathIsAbsolute )
        {
            $sPath = "/$sPath";
        }
        else if ( CString::isEmpty($sPath) )
        {
            $sPath = ".";
        }
        return $sPath;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Asks the file system for the absolute path to a file or directory located at a specified relative path and
     * returns the answer.
     *
     * The file or directory should actually exist for the file system to be able to answer.
     *
     * @param  string $sPath The path to the file or directory (usually a relative one).
     *
     * @return CUStringObject The absolute path to the file or directory.
     */

    public static function absolute ($sPath)
    {
        assert( 'is_cstring($sPath)', vs(isset($this), get_defined_vars()) );
        assert( '!CString::isEmpty($sPath)', vs(isset($this), get_defined_vars()) );

        $sAbsPath = realpath($sPath);
        assert( 'is_cstring($sAbsPath)', vs(isset($this), get_defined_vars()) );
        return $sAbsPath;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a path is absolute.
     *
     * @param  string $sPath The path to be looked into.
     *
     * @return bool `true` if the path is absolute, `false` otherwise.
     */

    public static function isAbsolute ($sPath)
    {
        assert( 'is_cstring($sPath)', vs(isset($this), get_defined_vars()) );
        assert( '!CString::isEmpty($sPath)', vs(isset($this), get_defined_vars()) );

        return CString::startsWith($sPath, "/");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces any reference to one of the framework's special directories in a path with the directory's actual path
     * and returns the usable path.
     *
     * A framework's directory is referenced in a path by wrapping its ID into double curly braces, as in
     * "{{PHRED_PATH_TO_FRAMEWORK_ROOT}}", optionally with "/" after the reference.
     *
     * @param  string $sPath The path to the file or directory (can be absolute or relative).
     *
     * @return CUStringObject The usable path.
     */

    public static function frameworkPath ($sPath)
    {
        assert( '!isset($sPath) || is_cstring($sPath)', vs(isset($this), get_defined_vars()) );

        if ( !isset($sPath) )
        {
            return null;
        }

        // Replace every "{{EXAMPLE_PATH}}" in the path string with the value of "EXAMPLE_PATH" key from $GLOBALS
        // variable if such key exists in the variable.

        $bModified = false;
        $sPath = CRegex::replaceWithCallback($sPath, "/\\{\\{\\w+\\}\\}/", function ($mMatches) use (&$bModified)
            {
                $sPathVarName = CString::substr($mMatches[0], 2, CString::length($mMatches[0]) - 4);
                if ( isset($GLOBALS[$sPathVarName]) )
                {
                    $bModified = true;
                    return $GLOBALS[$sPathVarName] . "/";
                }
                else
                {
                    assert( 'false', vs(isset($this), get_defined_vars()) );
                    return $mMatches[0];
                }
            });
        if ( $bModified )
        {
            $sPath = CRegex::replace($sPath, "/\\/{2,}/", "/");
        }
        return $sPath;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
