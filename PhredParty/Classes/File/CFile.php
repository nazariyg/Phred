<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The home of the static methods that check on files and directories as to whether they exist and on their attributes,
 * read from and write to files, create files and directories, copy, move, rename, and delete files and directories,
 * list items in directories, search for items in directories with wildcards or regular expressions, as well as the
 * class of file accessors that perform sequential read/write operations on files.
 *
 * **You can refer to this class by its alias, which is** `Fl`.
 *
 * As usual, you can refer to a file or directory with an absolute or relative path. However, relative paths wouldn't
 * be called so if they didn't depend on the current directory, which you can set with `cd` method of the
 * [CSystem](CSystem.html) class. So there are certain advantages to the use of absolute paths whenever feasible.
 *
 * An object of the CFile class rather represents an accessor to a file than a file on its own. Creating a file
 * accessor by constructing a CFile object lets you open a file for a session of read/write operations on its contents.
 * Any such accessor keeps in mind the current reading/writing position, which is measured in bytes and points to the
 * byte starting at which the next portion of data is going to be read from or written to.
 */

// Method signatures:
//   static bool exists ($sFileOrDirectoryPath)
//   static int size ($sFilePath)
//   static CUStringObject read ($sFilePath)
//   static void write ($sFilePath, $byData)
//   static void append ($sFilePath, $byData)
//   static void create ($sFilePath, $bSetPermissions = false)
//   static CUStringObject createTemporary ($sInDirectoryPath = null,
//     $sNamePrefix = self::DEFAULT_TEMPORARY_FILE_PREFIX)
//   static void createDirectory ($sDirectoryPath, $bSetPermissions = false)
//   static void copy ($sFileOrDirectoryPath, $sCopyPath, $bCopyPermissions = true)
//   static void move ($sFileOrDirectoryPath, $sNewPath)
//   static void rename ($sFileOrDirectoryPath, $sNewName)
//   static void delete ($sFilePath)
//   static void deleteEmptyDirectory ($sDirectoryPath)
//   static void deleteDirectoryRecursive ($sDirectoryPath)
//   static CUStringObject permissions ($sFileOrDirectoryPath)
//   static void setPermissions ($sFileOrDirectoryPath, $sPermissions)
//   static CTime modificationTime ($sFilePath)
//   static bool isFile ($sPath)
//   static bool isDirectory ($sPath)
//   static CArrayObject listItems ($sInDirectoryPath, $bSort = false)
//   static CArrayObject listFiles ($sInDirectoryPath, $bSort = false)
//   static CArrayObject listDirectories ($sInDirectoryPath, $bSort = false)
//   static CArrayObject listItemsRecursive ($sInDirectoryPath, $bSort = false)
//   static CArrayObject listFilesRecursive ($sInDirectoryPath, $bSort = false)
//   static CArrayObject listDirectoriesRecursive ($sInDirectoryPath, $bSort = false)
//   static CArrayObject findItems ($sWildcardPattern, $bSort = false)
//   static CArrayObject findFiles ($sWildcardPattern, $bSort = false)
//   static CArrayObject findDirectories ($sWildcardPattern, $bSort = false)
//   static CArrayObject findItemsRecursive ($sWildcardPattern, $bSort = false)
//   static CArrayObject findFilesRecursive ($sWildcardPattern, $bSort = false)
//   static CArrayObject findDirectoriesRecursive ($sWildcardPattern, $bSort = false)
//   static CArrayObject reFindItems ($sInDirectoryPath, $sRegexPattern, $bSort = false)
//   static CArrayObject reFindFiles ($sInDirectoryPath, $sRegexPattern, $bSort = false)
//   static CArrayObject reFindDirectories ($sInDirectoryPath, $sRegexPattern, $bSort = false)
//   static CArrayObject reFindItemsRecursive ($sInDirectoryPath, $sRegexPattern, $bSort = false)
//   static CArrayObject reFindFilesRecursive ($sInDirectoryPath, $sRegexPattern, $bSort = false)
//   static CArrayObject reFindDirectoriesRecursive ($sInDirectoryPath, $sRegexPattern, $bSort = false)
//   static CArrayObject reFindItemsOnName ($sInDirectoryPath, $sRegexPattern, $bSort = false)
//   static CArrayObject reFindFilesOnName ($sInDirectoryPath, $sRegexPattern, $bSort = false)
//   static CArrayObject reFindDirectoriesOnName ($sInDirectoryPath, $sRegexPattern, $bSort = false)
//   static CArrayObject reFindItemsOnNameRecursive ($sInDirectoryPath, $sRegexPattern, $bSort = false)
//   static CArrayObject reFindFilesOnNameRecursive ($sInDirectoryPath, $sRegexPattern, $bSort = false)
//   static CArrayObject reFindDirectoriesOnNameRecursive ($sInDirectoryPath, $sRegexPattern, $bSort = false)
//   __construct ($sFilePath, $eMode, $eWrapper = self::NONE)
//   CUStringObject filePath ()
//   enum mode ()
//   resource systemResource ()
//   bool equals ($oToFile)
//   CUStringObject readBytes ($iQuantity)
//   CUStringObject readAvailableBytes ($iMaxQuantity)
//   void writeBytes ($byData, $iMaxQuantity = null)
//   int pos ()
//   void setPos ($iPos)
//   void shiftPos ($iShift)
//   void setPosToStart ()
//   void setPosToEnd ()
//   bool isPosPastEnd ()
//   void flush ()
//   void done ()

class CFile extends CRootClass implements IEquality
{
    // File access modes.
    /**
     * `enum` Open the file for reading only, setting the reading position to the start of the file.
     *
     * @var enum
     */
    const READ = 0;
    /**
     * `enum` Open the file for both reading and writing, setting the reading/writing position to the start of the
     * file.
     *
     * @var enum
     */
    const READ_WRITE = 1;
    /**
     * `enum` Open the file for writing only, creating the file if it doesn't exist or removing all contents from the
     * file if it does.
     *
     * @var enum
     */
    const WRITE_NEW = 2;
    /**
     * `enum` Open the file for both reading and writing, creating the file if it doesn't exist or removing all
     * contents from the file if it does.
     *
     * @var enum
     */
    const READ_WRITE_NEW = 3;
    /**
     * `enum` Open the file for writing only, setting the writing position to the end of the file or creating the file
     * if it doesn't exist.
     *
     * @var enum
     */
    const WRITE_APPEND = 4;
    /**
     * `enum` Open the file for both reading and writing, setting the reading/writing position to the end of the file
     * or creating the file if it doesn't exist.
     *
     * @var enum
     */
    const READ_WRITE_APPEND = 5;

    // Wrappers.
    /**
     * `enum` Don't use any wrappers.
     *
     * @var enum
     */
    const NONE = 0;
    /**
     * `enum` Use the zlib wrapper: "compress.zlib://".
     *
     * @var enum
     */
    const ZLIB = 1;

    // Some of this information may be useful for classes that utilize temporary files.
    /**
     * `string` "tmp-" The default name prefix for any temporary file created with `createTemporary` method.
     *
     * @var string
     */
    const DEFAULT_TEMPORARY_FILE_PREFIX = "tmp-";
    /**
     * `string` "644" The default access permissions for any temporary file created with `createTemporary` method.
     *
     * @var string
     */
    const DEFAULT_TEMPORARY_FILE_PERMISSIONS = "644";

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a file or directory exists.
     *
     * The method always reports on a file or directory how it really is at the moment, without using any information
     * that was previously cached by the OS.
     *
     * @param  string $sFileOrDirectoryPath The path to be checked (in case of a directory, its path is not required to
     * end with "/").
     *
     * @return bool `true` if the file or directory exists, `false` otherwise.
     */

    public static function exists ($sFileOrDirectoryPath)
    {
        assert( 'is_cstring($sFileOrDirectoryPath)', vs(isset($this), get_defined_vars()) );

        $sFileOrDirectoryPath = CFilePath::frameworkPath($sFileOrDirectoryPath);

        clearstatcache(true, $sFileOrDirectoryPath);
        return file_exists($sFileOrDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the size of a file.
     *
     * The method always reports on a file how it really is at the moment, without using any information that was
     * previously cached by the OS.
     *
     * @param  string $sFilePath The path to the file.
     *
     * @return int The size of the file, in bytes.
     */

    public static function size ($sFilePath)
    {
        assert( 'is_cstring($sFilePath)', vs(isset($this), get_defined_vars()) );

        $sFilePath = CFilePath::frameworkPath($sFilePath);

        clearstatcache(true, $sFilePath);
        $iSize = filesize($sFilePath);
        assert( 'is_int($iSize)', vs(isset($this), get_defined_vars()) );
        return $iSize;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Reads and returns the entire contents of a file.
     *
     * @param  string $sFilePath The path to the source file.
     *
     * @return CUStringObject The contents of the file.
     */

    public static function read ($sFilePath)
    {
        assert( 'is_cstring($sFilePath)', vs(isset($this), get_defined_vars()) );

        $sFilePath = CFilePath::frameworkPath($sFilePath);

        $byData = file_get_contents($sFilePath, false);
        assert( 'is_cstring($byData)', vs(isset($this), get_defined_vars()) );
        return $byData;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Writes specified contents into a file, replacing any previous contents or creating the file if it doesn't exist.
     *
     * To prevent any other process from writing into the file during the operation, the method locks the file until
     * the operation is complete.
     *
     * @param  string $sFilePath The path to the destination file.
     * @param  data $byData The contents to be written into the file.
     *
     * @return void
     */

    public static function write ($sFilePath, $byData)
    {
        assert( 'is_cstring($sFilePath) && is_cstring($byData)', vs(isset($this), get_defined_vars()) );

        $sFilePath = CFilePath::frameworkPath($sFilePath);

        $iWrittenByteQty = file_put_contents($sFilePath, $byData, LOCK_EX);
        assert( 'is_int($iWrittenByteQty) && $iWrittenByteQty == CString::length($byData)',
            vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Appends specified contents to the end of a file, creating the file if it doesn't exist.
     *
     * To prevent any other process from writing into the file during the operation, the method locks the file until
     * the operation is complete.
     *
     * @param  string $sFilePath The path to the destination file.
     * @param  data $byData The contents to be appended to the file.
     *
     * @return void
     */

    public static function append ($sFilePath, $byData)
    {
        assert( 'is_cstring($sFilePath) && is_cstring($byData)', vs(isset($this), get_defined_vars()) );

        $sFilePath = CFilePath::frameworkPath($sFilePath);

        $iWrittenByteQty = file_put_contents($sFilePath, $byData, LOCK_EX | FILE_APPEND);
        assert( 'is_int($iWrittenByteQty) && $iWrittenByteQty == CString::length($byData)',
            vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an empty file at a specified path.
     *
     * If a file already exists at the specified path, it will be overwritten.
     *
     * @param  string $sFilePath The path at which the file is to be created.
     * @param  bool $bSetPermissions **OPTIONAL. Default is** `false`. Tells whether to set access permissions for the
     * file. If this parameter is `false`, then the permissions default to the ones determined by the OS's
     * configuration. And if it is `true`, then the file is created with the access permissions of "644", which means
     * read/write access for the file's owner (the user on whose behalf the application runs) and read-only access for
     * other users.
     *
     * @return void
     */

    public static function create ($sFilePath, $bSetPermissions = false)
    {
        assert( 'is_cstring($sFilePath)', vs(isset($this), get_defined_vars()) );

        $sFilePath = CFilePath::frameworkPath($sFilePath);

        // The `file_put_contents` function works faster than `touch`.
        $iRes = file_put_contents($sFilePath, "");
        assert( 'is_int($iRes)', vs(isset($this), get_defined_vars()) );
        if ( $bSetPermissions )
        {
            self::setPermissions($sFilePath, "644");
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an empty file with a unique name in the default directory for temporary files or in a specified
     * directory, and returns the path to the created file.
     *
     * After creating the file, the method sets the file's access permissions as "644", which means read/write access
     * for the file's owner (the user on whose behalf the application runs) and read-only access for other users.
     *
     * @param  string $sInDirectoryPath **OPTIONAL. Default is** *the default directory for temporary files*. The path
     * to the directory where the file is to be created. If this parameter is omitted, the used directory is either the
     * OS's default directory for temporary files or, if specified, the directory read from the application's
     * configuration. If specified, the path is not required to end with "/".
     * @param  string $sNamePrefix **OPTIONAL. Default is** "tmp-". The name prefix for the file.
     *
     * @return CUStringObject The path to the created file.
     */

    public static function createTemporary ($sInDirectoryPath = null,
        $sNamePrefix = self::DEFAULT_TEMPORARY_FILE_PREFIX)
    {
        assert( '(!isset($sInDirectoryPath) || is_cstring($sInDirectoryPath)) && is_cstring($sNamePrefix)',
            vs(isset($this), get_defined_vars()) );

        if ( isset($sInDirectoryPath) )
        {
            $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);
        }
        else
        {
            $sInDirectoryPath = CSystem::temporaryFilesDp();
        }

        $sFilePath = tempnam($sInDirectoryPath, $sNamePrefix);
        assert( 'is_cstring($sFilePath)', vs(isset($this), get_defined_vars()) );
        self::setPermissions($sFilePath, self::DEFAULT_TEMPORARY_FILE_PERMISSIONS);
        return $sFilePath;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a directory at a specified path.
     *
     * If the path along the way mentions any other directories that don't exist, all of them will be created as well.
     *
     * @param  string $sDirectoryPath The path at which the directory is to be created (not required to end with "/").
     * @param  bool $bSetPermissions **OPTIONAL. Default is** `false`. Tells whether to set access permissions for the
     * directory. If this parameter is `false`, then the permissions default to the ones determined by the OS's
     * configuration. And if it is `true`, then the directory is created with the access permissions of "755", which
     * means the ability to list items in the directory, add items to or remove items from the directory, and access
     * the items in the directory for the directory's owner (the user on whose behalf the application runs) and the
     * same level of access except adding/removing items for other users.
     *
     * @return void
     */

    public static function createDirectory ($sDirectoryPath, $bSetPermissions = false)
    {
        assert( 'is_cstring($sDirectoryPath)', vs(isset($this), get_defined_vars()) );

        $sDirectoryPath = CFilePath::frameworkPath($sDirectoryPath);

        $bRes = mkdir($sDirectoryPath, 0777, true);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
        if ( $bSetPermissions )
        {
            self::setPermissions($sDirectoryPath, "755");
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Copies a file or directory.
     *
     * If any file or directory already exists at the destination path, it will be overwritten.
     *
     * When copying a directory, it is copied recursively so that any subdirectories, including all of their files and
     * subdirectories and so on, are all copied as well, maintaining the directory hierarchy.
     *
     * @param  string $sFileOrDirectoryPath The path to the file or directory to be copied (in case of a directory, its
     * path is not required to end with "/").
     * @param  string $sCopyPath The destination path.
     * @param  bool $bCopyPermissions **OPTIONAL. Default is** `true`. Tells whether the access permissions should be
     * copied too per each item.
     *
     * @return void
     */

    public static function copy ($sFileOrDirectoryPath, $sCopyPath, $bCopyPermissions = true)
    {
        assert( 'is_cstring($sFileOrDirectoryPath) && is_cstring($sCopyPath)', vs(isset($this), get_defined_vars()) );

        $sFileOrDirectoryPath = CFilePath::frameworkPath($sFileOrDirectoryPath);
        $sCopyPath = CFilePath::frameworkPath($sCopyPath);

        self::recurseCopy($sFileOrDirectoryPath, $sCopyPath, $bCopyPermissions);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Moves a file or directory.
     *
     * If any file or directory already exists at the destination path, it will be overwritten.
     *
     * @param  string $sFileOrDirectoryPath The path to the file or directory to be moved (in case of a directory, its
     * path is not required to end with "/").
     * @param  string $sNewPath The destination path.
     *
     * @return void
     */

    public static function move ($sFileOrDirectoryPath, $sNewPath)
    {
        assert( 'is_cstring($sFileOrDirectoryPath) && is_cstring($sNewPath)', vs(isset($this), get_defined_vars()) );

        $sFileOrDirectoryPath = CFilePath::frameworkPath($sFileOrDirectoryPath);
        $sNewPath = CFilePath::frameworkPath($sNewPath);

        $bRes = rename($sFileOrDirectoryPath, $sNewPath);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Renames a file or directory.
     *
     * If any file or directory already exists at the resulting path, it will be overwritten.
     *
     * @param  string $sFileOrDirectoryPath The path to the file or directory to be renamed (in case of a directory,
     * its path is not required to end with "/").
     * @param  string $sNewName The new name for the file or directory.
     *
     * @return void
     */

    public static function rename ($sFileOrDirectoryPath, $sNewName)
    {
        assert( 'is_cstring($sFileOrDirectoryPath) && is_cstring($sNewName)', vs(isset($this), get_defined_vars()) );

        $sNewPath = CFilePath::add(CFilePath::directory($sFileOrDirectoryPath), $sNewName);
        self::move($sFileOrDirectoryPath, $sNewPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Deletes a file.
     *
     * Use either `deleteEmptyDirectory` or `deleteDirectoryRecursive` method to delete a directory.
     *
     * @param  string $sFilePath The path to the file to be deleted.
     *
     * @return void
     *
     * @link   #method_deleteEmptyDirectory deleteEmptyDirectory
     * @link   #method_deleteDirectoryRecursive deleteDirectoryRecursive
     */

    public static function delete ($sFilePath)
    {
        assert( 'is_cstring($sFilePath)', vs(isset($this), get_defined_vars()) );

        $sFilePath = CFilePath::frameworkPath($sFilePath);

        $bRes = unlink($sFilePath);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Deletes a directory that is known to be empty.
     *
     * This method is a safeguard against data losses. Use `deleteDirectoryRecursive` method to delete a directory that
     * contains or may contain files or subdirectories.
     *
     * @param  string $sDirectoryPath The path to the directory to be deleted (not required to end with "/").
     *
     * @return void
     *
     * @link   #method_deleteDirectoryRecursive deleteDirectoryRecursive
     */

    public static function deleteEmptyDirectory ($sDirectoryPath)
    {
        assert( 'is_cstring($sDirectoryPath)', vs(isset($this), get_defined_vars()) );

        $sDirectoryPath = CFilePath::frameworkPath($sDirectoryPath);

        $bRes = rmdir($sDirectoryPath);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Deletes a directory together with all of its files and subdirectories.
     *
     * @param  string $sDirectoryPath The path to the directory to be deleted (not required to end with "/").
     *
     * @return void
     */

    public static function deleteDirectoryRecursive ($sDirectoryPath)
    {
        assert( 'is_cstring($sDirectoryPath)', vs(isset($this), get_defined_vars()) );

        $sDirectoryPath = CFilePath::frameworkPath($sDirectoryPath);

        self::recurseDeleteDirectory($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the access permissions for a file or directory.
     *
     * The permissions are embodied in a string with three digits. For instance, the permissions on a file that is
     * allowed to be accessed by any user and with any intent are "777".
     *
     * The method always reports on a file or directory how it really is at the moment, without using any information
     * that was previously cached by the OS.
     *
     * @param  string $sFileOrDirectoryPath The path to the file or directory (in case of a directory, its path is not
     * required to end with "/").
     *
     * @return CUStringObject The access permissions used for the file or directory.
     */

    public static function permissions ($sFileOrDirectoryPath)
    {
        assert( 'is_cstring($sFileOrDirectoryPath)', vs(isset($this), get_defined_vars()) );

        $sFileOrDirectoryPath = CFilePath::frameworkPath($sFileOrDirectoryPath);

        clearstatcache(true, $sFileOrDirectoryPath);
        $iOctalPerms = fileperms($sFileOrDirectoryPath);
        return decoct($iOctalPerms & 0777);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets access permissions for a file or directory.
     *
     * The permissions are embodied in a string with three digits. For instance, the permissions on a file that is
     * allowed to be accessed by any user and with any intent are "777".
     *
     * @param  string $sFileOrDirectoryPath The path to the file or directory (in case of a directory, its path is not
     * required to end with "/").
     * @param  string $sPermissions The access permissions to be used for the file or directory.
     *
     * @return void
     */

    public static function setPermissions ($sFileOrDirectoryPath, $sPermissions)
    {
        assert( 'is_cstring($sFileOrDirectoryPath) && is_cstring($sPermissions)',
            vs(isset($this), get_defined_vars()) );

        $sFileOrDirectoryPath = CFilePath::frameworkPath($sFileOrDirectoryPath);

        $iOctalPerms = octdec($sPermissions);
        $bRes = chmod($sFileOrDirectoryPath, $iOctalPerms);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the point in time when the file was last modified.
     *
     * The method always reports on a file how it really is at the moment, without using any information that was
     * previously cached by the OS.
     *
     * @param  string $sFilePath The path to the file.
     *
     * @return CTime The point in time when the file was last modified.
     */

    public static function modificationTime ($sFilePath)
    {
        assert( 'is_cstring($sFilePath)', vs(isset($this), get_defined_vars()) );

        $sFilePath = CFilePath::frameworkPath($sFilePath);

        clearstatcache(true, $sFilePath);
        $iUTime = filemtime($sFilePath);
        assert( 'is_int($iUTime)', vs(isset($this), get_defined_vars()) );
        return new CTime($iUTime);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a path leads to a file.
     *
     * The method always reports on a path how it really is at the moment, without using any information that was
     * previously cached by the OS.
     *
     * @param  string $sPath The path to be checked.
     *
     * @return bool `true` if the path leads to a file, `false` in any other case.
     */

    public static function isFile ($sPath)
    {
        assert( 'is_cstring($sPath)', vs(isset($this), get_defined_vars()) );

        $sPath = CFilePath::frameworkPath($sPath);

        clearstatcache(true, $sPath);
        return is_file($sPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a path leads to a directory.
     *
     * The method always reports on a path how it really is at the moment, without using any information that was
     * previously cached by the OS.
     *
     * @param  string $sPath The path to be checked (not required to end with "/").
     *
     * @return bool `true` if the path leads to a directory, `false` in any other case.
     */

    public static function isDirectory ($sPath)
    {
        assert( 'is_cstring($sPath)', vs(isset($this), get_defined_vars()) );

        $sPath = CFilePath::frameworkPath($sPath);

        clearstatcache(true, $sPath);
        return is_dir($sPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files and subdirectories contained in a directory.
     *
     * To find out what items are also contained in the subdirectories of the specified directory and so on, you can
     * use `listItemsRecursive` method.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories contained in the directory specified.
     *
     * @link   #method_listItemsRecursive listItemsRecursive
     */

    public static function listItems ($sInDirectoryPath, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_bool($bSort)', vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        $mListingRes = scandir($sInDirectoryPath, SCANDIR_SORT_NONE);
        assert( 'is_cmap($mListingRes)', vs(isset($this), get_defined_vars()) );
        $aListingRes = CArray::fromPArray($mListingRes);
        $aListingRes = CArray::filter($aListingRes, function ($sPath)
            {
                return ( !CString::equals($sPath, "..") && !CString::equals($sPath, ".") );
            });
        $iLen = CArray::length($aListingRes);
        for ($i = 0; $i < $iLen; $i++)
        {
            $aListingRes[$i] = CFilePath::add($sInDirectoryPath, $aListingRes[$i]);
        }
        if ( $bSort )
        {
            CArray::sortUStringsNatCi($aListingRes);
        }
        return oop_a($aListingRes);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files contained in a directory.
     *
     * To find out what files are also contained in the subdirectories of the specified directory and so on, you can
     * use `listFilesRecursive` method.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files contained in the directory specified.
     *
     * @link   #method_listFilesRecursive listFilesRecursive
     */

    public static function listFiles ($sInDirectoryPath, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_bool($bSort)', vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listItems($sInDirectoryPath, $bSort), "CFile::isFile"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the subdirectories contained in a directory.
     *
     * To find out what subdirectories are also contained in the subdirectories of the specified directory and so on,
     * you can use `listDirectoriesRecursive` method.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories contained in the directory specified.
     *
     * @link   #method_listDirectoriesRecursive listDirectoriesRecursive
     */

    public static function listDirectories ($sInDirectoryPath, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_bool($bSort)', vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listItems($sInDirectoryPath, $bSort), "CFile::isDirectory"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files and subdirectories contained in a directory, in its subdirectories, and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories contained in the directory specified, in the
     * subdirectories of the directory, and so on.
     */

    public static function listItemsRecursive ($sInDirectoryPath, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_bool($bSort)', vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(self::recurseListItems($sInDirectoryPath, $bSort));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files contained in a directory, in its subdirectories, and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files contained in the directory specified, in the subdirectories of the
     * directory, and so on.
     */

    public static function listFilesRecursive ($sInDirectoryPath, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_bool($bSort)', vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listItemsRecursive($sInDirectoryPath, $bSort), "CFile::isFile"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the subdirectories contained in a directory, in its subdirectories, and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories contained in the directory specified, in the subdirectories
     * of the directory, and so on.
     */

    public static function listDirectoriesRecursive ($sInDirectoryPath, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_bool($bSort)', vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listItemsRecursive($sInDirectoryPath, $bSort), "CFile::isDirectory"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files and subdirectories found by a wildcard pattern.
     *
     * Wildcard patterns use "\*" to match zero or more arbitrary characters, except the character of "/", and "?" to
     * match any single arbitrary character, again except the character of "/". For example, "\*.png" pattern searches
     * for all PNG images in the *current* directory, and "/path/to/any\*subdir/\*thumb-year-201?\*" searches for all
     * thumbnail images dated by the years of 2010-2019 and that are contained in the directories with a path matching
     * the rest of the pattern. Distinct from regular expressions, wildcard patterns need to be anchored at both ends,
     * so if the pattern in the last example was not concluded with "\*", no images would be found. Wildcards also
     * support character classes, such as "[abc]" and "[0-9]" as well as "[^abc]" and "[^0-9]", which however is only a
     * small portion of what regular expressions can do.
     *
     * To also search among the items that are contained in the subdirectories of the specified wildcard pattern and so
     * on, you can use `findItemsRecursive` method.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sWildcardPattern The wildcard pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories found by the wildcard pattern specified.
     *
     * @link   #method_findItemsRecursive findItemsRecursive
     */

    public static function findItems ($sWildcardPattern, $bSort = false)
    {
        assert( 'is_cstring($sWildcardPattern) && is_bool($bSort)', vs(isset($this), get_defined_vars()) );

        $sWildcardPattern = CFilePath::frameworkPath($sWildcardPattern);

        $mSearchRes = glob($sWildcardPattern, GLOB_NOSORT);
        assert( 'is_cmap($mSearchRes)', vs(isset($this), get_defined_vars()) );
        $aSearchRes = CArray::fromPArray($mSearchRes);
        if ( $bSort )
        {
            CArray::sortUStringsNatCi($aSearchRes);
        }
        return oop_a($aSearchRes);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files found by a wildcard pattern.
     *
     * Wildcard patterns use "\*" to match zero or more arbitrary characters, except the character of "/", and "?" to
     * match any single arbitrary character, again except the character of "/". For example, "\*.png" pattern searches
     * for all PNG images in the *current* directory, and "/path/to/any\*subdir/\*thumb-year-201?\*" searches for all
     * thumbnail images dated by the years of 2010-2019 and that are contained in the directories with a path matching
     * the rest of the pattern. Distinct from regular expressions, wildcard patterns need to be anchored at both ends,
     * so if the pattern in the last example was not concluded with "\*", no images would be found. Wildcards also
     * support character classes, such as "[abc]" and "[0-9]" as well as "[^abc]" and "[^0-9]", which however is only a
     * small portion of what regular expressions can do.
     *
     * To also search among the files that are contained in the subdirectories of the specified wildcard pattern and so
     * on, you can use `findFilesRecursive` method.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sWildcardPattern The wildcard pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files found by the wildcard pattern specified.
     *
     * @link   #method_findFilesRecursive findFilesRecursive
     */

    public static function findFiles ($sWildcardPattern, $bSort = false)
    {
        assert( 'is_cstring($sWildcardPattern) && is_bool($bSort)', vs(isset($this), get_defined_vars()) );

        $sWildcardPattern = CFilePath::frameworkPath($sWildcardPattern);

        return oop_a(CArray::filter(self::findItems($sWildcardPattern, $bSort), "CFile::isFile"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the subdirectories found by a wildcard pattern.
     *
     * Wildcard patterns use "\*" to match zero or more arbitrary characters, except the character of "/", and "?" to
     * match any single arbitrary character, again except the character of "/". For example, "\*.png" pattern searches
     * for all PNG images in the *current* directory, and "/path/to/any\*subdir/\*thumb-year-201?\*" searches for all
     * thumbnail images dated by the years of 2010-2019 and that are contained in the directories with a path matching
     * the rest of the pattern. Distinct from regular expressions, wildcard patterns need to be anchored at both ends,
     * so if the pattern in the last example was not concluded with "\*", no images would be found. Wildcards also
     * support character classes, such as "[abc]" and "[0-9]" as well as "[^abc]" and "[^0-9]", which however is only a
     * small portion of what regular expressions can do.
     *
     * To also search among the subdirectories that are contained in the subdirectories of the specified wildcard
     * pattern and so on, you can use `findDirectoriesRecursive` method.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sWildcardPattern The wildcard pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories found by the wildcard pattern specified.
     *
     * @link   #method_findDirectoriesRecursive findDirectoriesRecursive
     */

    public static function findDirectories ($sWildcardPattern, $bSort = false)
    {
        assert( 'is_cstring($sWildcardPattern) && is_bool($bSort)', vs(isset($this), get_defined_vars()) );

        $sWildcardPattern = CFilePath::frameworkPath($sWildcardPattern);

        $mSearchRes = glob($sWildcardPattern, GLOB_NOSORT | GLOB_ONLYDIR);
        assert( 'is_cmap($mSearchRes)', vs(isset($this), get_defined_vars()) );
        $aSearchRes = CArray::fromPArray($mSearchRes);
        if ( $bSort )
        {
            CArray::sortUStringsNatCi($aSearchRes);
        }
        return oop_a($aSearchRes);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files and subdirectories found by a wildcard pattern, also searching in the
     * subdirectories and so on.
     *
     * Wildcard patterns use "\*" to match zero or more arbitrary characters, except the character of "/", and "?" to
     * match any single arbitrary character, again except the character of "/". For example, "\*.png" pattern searches
     * for all PNG images in the *current* directory, and "/path/to/any\*subdir/\*thumb-year-201?\*" searches for all
     * thumbnail images dated by the years of 2010-2019 and that are contained in the directories with a path matching
     * the rest of the pattern. Distinct from regular expressions, wildcard patterns need to be anchored at both ends,
     * so if the pattern in the last example was not concluded with "\*", no images would be found. Wildcards also
     * support character classes, such as "[abc]" and "[0-9]" as well as "[^abc]" and "[^0-9]", which however is only a
     * small portion of what regular expressions can do.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sWildcardPattern The wildcard pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories found by the wildcard pattern specified,
     * including ones found in the subdirectories and so on.
     */

    public static function findItemsRecursive ($sWildcardPattern, $bSort = false)
    {
        assert( 'is_cstring($sWildcardPattern) && is_bool($bSort)', vs(isset($this), get_defined_vars()) );

        $sWildcardPattern = CFilePath::frameworkPath($sWildcardPattern);

        return oop_a(self::recurseFindItems(CFilePath::directory($sWildcardPattern),
            CFilePath::name($sWildcardPattern), $bSort));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files found by a wildcard pattern, also searching in the subdirectories and so on.
     *
     * Wildcard patterns use "\*" to match zero or more arbitrary characters, except the character of "/", and "?" to
     * match any single arbitrary character, again except the character of "/". For example, "\*.png" pattern searches
     * for all PNG images in the *current* directory, and "/path/to/any\*subdir/\*thumb-year-201?\*" searches for all
     * thumbnail images dated by the years of 2010-2019 and that are contained in the directories with a path matching
     * the rest of the pattern. Distinct from regular expressions, wildcard patterns need to be anchored at both ends,
     * so if the pattern in the last example was not concluded with "\*", no images would be found. Wildcards also
     * support character classes, such as "[abc]" and "[0-9]" as well as "[^abc]" and "[^0-9]", which however is only a
     * small portion of what regular expressions can do.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sWildcardPattern The wildcard pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files found by the wildcard pattern specified, including ones found in the
     * subdirectories and so on.
     */

    public static function findFilesRecursive ($sWildcardPattern, $bSort = false)
    {
        assert( 'is_cstring($sWildcardPattern) && is_bool($bSort)', vs(isset($this), get_defined_vars()) );

        $sWildcardPattern = CFilePath::frameworkPath($sWildcardPattern);

        return oop_a(CArray::filter(self::findItemsRecursive($sWildcardPattern, $bSort), "CFile::isFile"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the subdirectories found by a wildcard pattern, also searching in the subdirectories and so
     * on.
     *
     * Wildcard patterns use "\*" to match zero or more arbitrary characters, except the character of "/", and "?" to
     * match any single arbitrary character, again except the character of "/". For example, "\*.png" pattern searches
     * for all PNG images in the *current* directory, and "/path/to/any\*subdir/\*thumb-year-201?\*" searches for all
     * thumbnail images dated by the years of 2010-2019 and that are contained in the directories with a path matching
     * the rest of the pattern. Distinct from regular expressions, wildcard patterns need to be anchored at both ends,
     * so if the pattern in the last example was not concluded with "\*", no images would be found. Wildcards also
     * support character classes, such as "[abc]" and "[0-9]" as well as "[^abc]" and "[^0-9]", which however is only a
     * small portion of what regular expressions can do.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sWildcardPattern The wildcard pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories found by the wildcard pattern specified, including ones
     * found in the subdirectories and so on.
     */

    public static function findDirectoriesRecursive ($sWildcardPattern, $bSort = false)
    {
        assert( 'is_cstring($sWildcardPattern) && is_bool($bSort)', vs(isset($this), get_defined_vars()) );

        $sWildcardPattern = CFilePath::frameworkPath($sWildcardPattern);

        return oop_a(CArray::filter(self::findItemsRecursive($sWildcardPattern, $bSort), "CFile::isDirectory"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files and subdirectories found in a directory by a regular expression pattern,
     * searching in the whole path of every contained item with the pattern.
     *
     * To also search among the items that are contained in the subdirectories of a directory and so on, you can use
     * `reFindItemsRecursive` method.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $sRegexPattern The regular expression pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories found by the regular expression pattern
     * specified.
     *
     * @link   #method_reFindItemsRecursive reFindItemsRecursive
     */

    public static function reFindItems ($sInDirectoryPath, $sRegexPattern, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_cstring($sRegexPattern) && is_bool($bSort)',
            vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listItems($sInDirectoryPath, $bSort), function ($sPath) use ($sRegexPattern)
            {
                return CRegex::find($sPath, $sRegexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files found in a directory by a regular expression pattern, searching in the whole path
     * of every contained file with the pattern.
     *
     * To also search among the files that are contained in the subdirectories of a directory and so on, you can use
     * `reFindFilesRecursive` method.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $sRegexPattern The regular expression pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files found by the regular expression pattern specified.
     *
     * @link   #method_reFindFilesRecursive reFindFilesRecursive
     */

    public static function reFindFiles ($sInDirectoryPath, $sRegexPattern, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_cstring($sRegexPattern) && is_bool($bSort)',
            vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listFiles($sInDirectoryPath, $bSort), function ($sPath) use ($sRegexPattern)
            {
                return CRegex::find($sPath, $sRegexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the subdirectories found in a directory by a regular expression pattern, searching in the
     * whole path of every contained subdirectory with the pattern.
     *
     * To also search among the subdirectories that are contained in the subdirectories of a directory and so on, you
     * can use `reFindDirectoriesRecursive` method.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $sRegexPattern The regular expression pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories found by the regular expression pattern specified.
     *
     * @link   #method_reFindDirectoriesRecursive reFindDirectoriesRecursive
     */

    public static function reFindDirectories ($sInDirectoryPath, $sRegexPattern, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_cstring($sRegexPattern) && is_bool($bSort)',
            vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listDirectories($sInDirectoryPath, $bSort),
            function ($sPath) use ($sRegexPattern)
            {
                return CRegex::find($sPath, $sRegexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files and subdirectories found in a directory by a regular expression pattern,
     * searching in the whole path of every contained item with the pattern, also looking into the subdirectories of
     * the directory and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $sRegexPattern The regular expression pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories found by the regular expression pattern
     * specified, including ones found in the subdirectories of the specified directory and so on.
     */

    public static function reFindItemsRecursive ($sInDirectoryPath, $sRegexPattern, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_cstring($sRegexPattern) && is_bool($bSort)',
            vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listItemsRecursive($sInDirectoryPath, $bSort),
            function ($sPath) use ($sRegexPattern)
            {
                return CRegex::find($sPath, $sRegexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files found in a directory by a regular expression pattern, searching in the whole path
     * of every contained file with the pattern, also looking into the subdirectories of the directory and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $sRegexPattern The regular expression pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files found by the regular expression pattern specified, including ones
     * found in the subdirectories of the specified directory and so on.
     */

    public static function reFindFilesRecursive ($sInDirectoryPath, $sRegexPattern, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_cstring($sRegexPattern) && is_bool($bSort)',
            vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listFilesRecursive($sInDirectoryPath, $bSort),
            function ($sPath) use ($sRegexPattern)
            {
                return CRegex::find($sPath, $sRegexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the subdirectories found in a directory by a regular expression pattern, searching in the
     * whole path of every contained subdirectory with the pattern, also looking into the subdirectories of the
     * directory and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $sRegexPattern The regular expression pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories found by the regular expression pattern specified,
     * including ones found in the subdirectories of the specified directory and so on.
     */

    public static function reFindDirectoriesRecursive ($sInDirectoryPath, $sRegexPattern, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_cstring($sRegexPattern) && is_bool($bSort)',
            vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listDirectoriesRecursive($sInDirectoryPath, $bSort),
            function ($sPath) use ($sRegexPattern)
            {
                return CRegex::find($sPath, $sRegexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files and subdirectories found in a directory by a regular expression pattern,
     * searching only in the name of every contained item with the pattern.
     *
     * To also search among the items that are contained in the subdirectories of a directory and so on, you can use
     * `reFindItemsOnNameRecursive` method.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $sRegexPattern The regular expression pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories found by the regular expression pattern
     * specified.
     *
     * @link   #method_reFindItemsOnNameRecursive reFindItemsOnNameRecursive
     */

    public static function reFindItemsOnName ($sInDirectoryPath, $sRegexPattern, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_cstring($sRegexPattern) && is_bool($bSort)',
            vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listItems($sInDirectoryPath, $bSort), function ($sPath) use ($sRegexPattern)
            {
                return CRegex::find(CFilePath::name($sPath), $sRegexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files found in a directory by a regular expression pattern, searching only in the name
     * of every contained file with the pattern.
     *
     * To also search among the files that are contained in the subdirectories of a directory and so on, you can use
     * `reFindFilesOnNameRecursive` method.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $sRegexPattern The regular expression pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files found by the regular expression pattern specified.
     *
     * @link   #method_reFindFilesOnNameRecursive reFindFilesOnNameRecursive
     */

    public static function reFindFilesOnName ($sInDirectoryPath, $sRegexPattern, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_cstring($sRegexPattern) && is_bool($bSort)',
            vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listFiles($sInDirectoryPath, $bSort), function ($sPath) use ($sRegexPattern)
            {
                return CRegex::find(CFilePath::name($sPath), $sRegexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the subdirectories found in a directory by a regular expression pattern, searching only in
     * the name of every contained subdirectory with the pattern.
     *
     * To also search among the subdirectories that are contained in the subdirectories of a directory and so on, you
     * can use `reFindDirectoriesOnNameRecursive` method.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $sRegexPattern The regular expression pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories found by the regular expression pattern specified.
     *
     * @link   #method_reFindDirectoriesOnNameRecursive reFindDirectoriesOnNameRecursive
     */

    public static function reFindDirectoriesOnName ($sInDirectoryPath, $sRegexPattern, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_cstring($sRegexPattern) && is_bool($bSort)',
            vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listDirectories($sInDirectoryPath, $bSort),
            function ($sPath) use ($sRegexPattern)
            {
                return CRegex::find(CFilePath::name($sPath), $sRegexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files and subdirectories found in a directory by a regular expression pattern,
     * searching only in the name of every contained item with the pattern, also looking into the subdirectories of the
     * directory and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $sRegexPattern The regular expression pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories found by the regular expression pattern
     * specified, including ones found in the subdirectories of the specified directory and so on.
     */

    public static function reFindItemsOnNameRecursive ($sInDirectoryPath, $sRegexPattern, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_cstring($sRegexPattern) && is_bool($bSort)',
            vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listItemsRecursive($sInDirectoryPath, $bSort),
            function ($sPath) use ($sRegexPattern)
            {
                return CRegex::find(CFilePath::name($sPath), $sRegexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files found in a directory by a regular expression pattern, searching only in the name
     * of every contained file with the pattern, also looking into the subdirectories of the directory and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $sRegexPattern The regular expression pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files found by the regular expression pattern specified, including ones
     * found in the subdirectories of the specified directory and so on.
     */

    public static function reFindFilesOnNameRecursive ($sInDirectoryPath, $sRegexPattern, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_cstring($sRegexPattern) && is_bool($bSort)',
            vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listFilesRecursive($sInDirectoryPath, $bSort),
            function ($sPath) use ($sRegexPattern)
            {
                return CRegex::find(CFilePath::name($sPath), $sRegexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the subdirectories found in a directory by a regular expression pattern, searching only in
     * the name of every contained subdirectory with the pattern, also looking into the subdirectories of the directory
     * and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $sInDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $sRegexPattern The regular expression pattern to be used for searching.
     * @param  bool $bSort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories found by the regular expression pattern specified,
     * including ones found in the subdirectories of the specified directory and so on.
     */

    public static function reFindDirectoriesOnNameRecursive ($sInDirectoryPath, $sRegexPattern, $bSort = false)
    {
        assert( 'is_cstring($sInDirectoryPath) && is_cstring($sRegexPattern) && is_bool($bSort)',
            vs(isset($this), get_defined_vars()) );

        $sInDirectoryPath = CFilePath::frameworkPath($sInDirectoryPath);

        return oop_a(CArray::filter(self::listDirectoriesRecursive($sInDirectoryPath, $bSort),
            function ($sPath) use ($sRegexPattern)
            {
                return CRegex::find(CFilePath::name($sPath), $sRegexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a file accessor for performing sequential read/write operations on a file.
     *
     * The purpose of this constructor is to open the file in the specified access mode. If the access mode is `READ`
     * or `READ_WRITE`, the file should already exist when calling the constructor. With the rest of the modes, the
     * file will be created if doesn't exist.
     *
     * The opened file will be closed and the file system's resources will be freed automatically when the object is no
     * longer needed and gets destructed by the runtime, or when `done` method is called on the object to close the
     * file manually.
     *
     * @param  string $sFilePath The path to the file to be opened for access.
     * @param  enum $eMode The access mode to be used for the file. Can be `READ`, `READ_WRITE`, `WRITE_NEW`,
     * `READ_WRITE_NEW`, `WRITE_APPEND`, and `READ_WRITE_APPEND`. See [Summary](#summary) for the description of a
     * mode.
     * @param  enum $eWrapper **OPTIONAL. Default is** `NONE`. The wrapper to be used for accessing the file.
     * Currently, only `ZLIB` wrapper is supported.
     */

    public function __construct ($sFilePath, $eMode, $eWrapper = self::NONE)
    {
        assert( 'is_cstring($sFilePath) && is_enum($eMode) && is_enum($eWrapper)',
            vs(isset($this), get_defined_vars()) );

        $sFilePath = CFilePath::frameworkPath($sFilePath);

        $this->m_sFilePath = $sFilePath;
        $this->m_eMode = $eMode;

        $sMode;
        switch ( $eMode )
        {
        case self::READ:
            $sMode = "rb";
            break;
        case self::READ_WRITE:
            $sMode = "r+b";
            break;
        case self::WRITE_NEW:
            $sMode = "wb";
            break;
        case self::READ_WRITE_NEW:
            $sMode = "w+b";
            break;
        case self::WRITE_APPEND:
            $sMode = "ab";
            break;
        case self::READ_WRITE_APPEND:
            $sMode = "a+b";
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $sWrapper;
        switch ( $eWrapper )
        {
        case self::NONE:
            $sWrapper = "";
            break;
        case self::ZLIB:
            $sWrapper = "compress.zlib://";
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }
        $sFilePath = $sWrapper . $sFilePath;

        $this->m_rcFile = fopen($sFilePath, $sMode);
        assert( 'is_resource($this->m_rcFile)', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path to the file opened by an accessor.
     *
     * @return CUStringObject The path to the file.
     */

    public function filePath ()
    {
        return $this->m_sFilePath;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the access mode in which an accessor opened its file.
     *
     * @return enum The access mode that was used to open the file. Can be `READ`, `READ_WRITE`, `WRITE_NEW`,
     * `READ_WRITE_NEW`, `WRITE_APPEND`, and `READ_WRITE_APPEND` (see [Summary](#summary)).
     */

    public function mode ()
    {
        return $this->m_eMode;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the system resource of the file opened by an accessor.
     *
     * @return resource The system resource of the file.
     */

    public function systemResource ()
    {
        return $this->m_rcFile;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an accessor is associated with the same file as another accessor.
     *
     * @param  CFile $oToFile The second accessor for comparison.
     *
     * @return bool `true` if the two accessors are associated with the same file, `false` otherwise.
     */

    public function equals ($oToFile)
    {
        // Parameter type hinting is not used for the purpose of interface compatibility.
        assert( 'is_cfile($oToFile)', vs(isset($this), get_defined_vars()) );
        return CString::equals(CFilePath::absolute($this->m_sFilePath), CFilePath::absolute($oToFile->m_sFilePath));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Reads a specified number of bytes from the file opened by an accessor and returns them.
     *
     * Bytes are read starting from the current reading/writing position.
     *
     * @param  int $iQuantity The number of bytes to be read (cannot be zero).
     *
     * @return CUStringObject The read bytes.
     */

    public function readBytes ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        assert( '$iQuantity > 0', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eMode == self::READ || ' .
                '$this->m_eMode == self::READ_WRITE || ' .
                '$this->m_eMode == self::READ_WRITE_NEW || ' .
                '$this->m_eMode == self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_bDone', vs(isset($this), get_defined_vars()) );

        $byData = fread($this->m_rcFile, $iQuantity);
        assert( 'is_cstring($byData) && CString::length($byData) == $iQuantity', vs(isset($this), get_defined_vars()) );
        return $byData;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Reads no more than a specified number of bytes from the file opened by an accessor and returns them.
     *
     * Bytes are read starting from the current reading/writing position and, even when there are less bytes left
     * available than indicated by the parameter's value, the operation will succeed and the bytes starting from the
     * current reading/writing position and up to the end of the file will be returned.
     *
     * @param  int $iMaxQuantity The maximum number of available bytes to be read (cannot be zero).
     *
     * @return CUStringObject The read bytes.
     */

    public function readAvailableBytes ($iMaxQuantity)
    {
        assert( 'is_int($iMaxQuantity)', vs(isset($this), get_defined_vars()) );
        assert( '$iMaxQuantity > 0', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eMode == self::READ || ' .
                '$this->m_eMode == self::READ_WRITE || ' .
                '$this->m_eMode == self::READ_WRITE_NEW || ' .
                '$this->m_eMode == self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_bDone', vs(isset($this), get_defined_vars()) );

        $byData = fread($this->m_rcFile, $iMaxQuantity);
        assert( 'is_cstring($byData)', vs(isset($this), get_defined_vars()) );
        return $byData;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Writes specified bytes into the file opened by an accessor.
     *
     * Bytes are written starting from the current reading/writing position.
     *
     * @param  data $byData The bytes to be written.
     * @param  int $iMaxQuantity **OPTIONAL. Default is** *all of the provided bytes*. The maximum number of bytes to
     * be written.
     *
     * @return void
     */

    public function writeBytes ($byData, $iMaxQuantity = null)
    {
        assert( 'is_cstring($byData) && (!isset($iMaxQuantity) || is_int($iMaxQuantity))',
            vs(isset($this), get_defined_vars()) );
        assert( '!isset($iMaxQuantity) || (0 <= $iMaxQuantity && $iMaxQuantity <= CString::length($byData))',
            vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eMode == self::READ_WRITE || ' .
                '$this->m_eMode == self::WRITE_NEW || ' .
                '$this->m_eMode == self::READ_WRITE_NEW || ' .
                '$this->m_eMode == self::WRITE_APPEND || ' .
                '$this->m_eMode == self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_bDone', vs(isset($this), get_defined_vars()) );

        if ( !isset($iMaxQuantity) )
        {
            $iWrittenByteQty = fwrite($this->m_rcFile, $byData);
            assert( 'is_int($iWrittenByteQty) && $iWrittenByteQty == CString::length($byData)',
                vs(isset($this), get_defined_vars()) );
        }
        else
        {
            $iWrittenByteQty = fwrite($this->m_rcFile, $byData, $iMaxQuantity);
            assert( 'is_int($iWrittenByteQty) && $iWrittenByteQty == $iMaxQuantity',
                vs(isset($this), get_defined_vars()) );
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the current reading/writing position of an accessor.
     *
     * Positions are zero-based, so the position of the first byte in a file is `0`, the position of the second byte is
     * `1`, and so on.
     *
     * @return int The current reading/writing position.
     */

    public function pos ()
    {
        assert( '$this->m_eMode != self::WRITE_APPEND && ' .
                '$this->m_eMode != self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_bDone', vs(isset($this), get_defined_vars()) );

        $iPos = ftell($this->m_rcFile);
        assert( 'is_int($iPos)', vs(isset($this), get_defined_vars()) );
        return $iPos;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the current reading/writing position of an accessor.
     *
     * Positions are zero-based, so the position of the first byte in a file is `0`, the position of the second byte is
     * `1`, and so on.
     *
     * @param  int $iPos The new reading/writing position.
     *
     * @return void
     */

    public function setPos ($iPos)
    {
        assert( 'is_int($iPos)', vs(isset($this), get_defined_vars()) );
        assert( '$iPos >= 0', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eMode != self::WRITE_APPEND && ' .
                '$this->m_eMode != self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_bDone', vs(isset($this), get_defined_vars()) );

        $iRes = fseek($this->m_rcFile, $iPos, SEEK_SET);
        assert( '$iRes == 0', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts the current reading/writing position of an accessor in any direction by a specified number of bytes.
     *
     * @param  int $iShift The positive or negative number of bytes by which the reading/writing position is to be
     * shifted.
     *
     * @return void
     */

    public function shiftPos ($iShift)
    {
        assert( 'is_int($iShift)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eMode != self::WRITE_APPEND && ' .
                '$this->m_eMode != self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_bDone', vs(isset($this), get_defined_vars()) );

        $iRes = fseek($this->m_rcFile, $iShift, SEEK_CUR);
        assert( '$iRes == 0', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the current reading/writing position of an accessor to the very start of the file.
     *
     * With this method, the current reading/writing position is set to `0`.
     *
     * @return void
     */

    public function setPosToStart ()
    {
        assert( '$this->m_eMode != self::WRITE_APPEND && ' .
                '$this->m_eMode != self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_bDone', vs(isset($this), get_defined_vars()) );

        $bRes = rewind($this->m_rcFile);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the current reading/writing position of an accessor to the very end of the file.
     *
     * With this method, the current reading/writing position is set to the size of the file in bytes, which is the
     * position of the last byte in the file plus `1`.
     *
     * @return void
     */

    public function setPosToEnd ()
    {
        assert( '$this->m_eMode != self::WRITE_APPEND && ' .
                '$this->m_eMode != self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_bDone', vs(isset($this), get_defined_vars()) );

        $iRes = fseek($this->m_rcFile, 0, SEEK_END);
        assert( '$iRes == 0', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the current reading/writing position of an accessor has reached the end-of-file position.
     *
     * As a long-established behavior, the end-of-file (EOF) position is reached not after reading the last byte from
     * the file but after reading the last byte and then attempting to read more bytes (with `readAvailableBytes`
     * method).
     *
     * @return bool `true` if the end-of-file position has been reached, `false` otherwise.
     */

    public function isPosPastEnd ()
    {
        assert( '$this->m_eMode != self::WRITE_APPEND && ' .
                '$this->m_eMode != self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_bDone', vs(isset($this), get_defined_vars()) );

        return feof($this->m_rcFile);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Forces any data that was buffered during the previous write operation(s) with an accessor to be written into the
     * file immediately.
     *
     * @return void
     */

    public function flush ()
    {
        assert( '$this->m_eMode == self::READ_WRITE || ' .
                '$this->m_eMode == self::WRITE_NEW || ' .
                '$this->m_eMode == self::READ_WRITE_NEW || ' .
                '$this->m_eMode == self::WRITE_APPEND || ' .
                '$this->m_eMode == self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_bDone', vs(isset($this), get_defined_vars()) );

        $bRes = fflush($this->m_rcFile);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Manually closes the file opened by an accessor.
     *
     * Usually, there is no need to call this method after you are done with a file because the file will be closed and
     * the allocated resources will be freed automatically when the accessor is no longer needed and its object is
     * destroyed by the runtime.
     *
     * @return void
     */

    public function done ()
    {
        assert( '!$this->m_bDone', vs(isset($this), get_defined_vars()) );
        $this->finalize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function __destruct ()
    {
        if ( !$this->m_bDone )
        {
            $this->finalize();
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseCopy ($sFileOrDirectoryPath, $sCopyPath, $bCopyPermissions)
    {
        if ( is_link($sFileOrDirectoryPath) )
        {
            // The source is a symlink, so make another symlink.
            $sSymlinkTargetPath = readlink($sFileOrDirectoryPath);
            assert( 'is_cstring($sSymlinkTargetPath)', vs(isset($this), get_defined_vars()) );
            $bRes = symlink($sSymlinkTargetPath, $sCopyPath);
            assert( '$bRes', vs(isset($this), get_defined_vars()) );
            return;
        }

        if ( $bCopyPermissions )
        {
            clearstatcache(true, $sFileOrDirectoryPath);
        }
        if ( is_file($sFileOrDirectoryPath) )
        {
            // A file.
            $bRes = copy($sFileOrDirectoryPath, $sCopyPath);
            assert( '$bRes', vs(isset($this), get_defined_vars()) );
            if ( $bCopyPermissions )
            {
                $bRes = chmod($sCopyPath, fileperms($sFileOrDirectoryPath));
                assert( '$bRes', vs(isset($this), get_defined_vars()) );
            }
        }
        else
        {
            // A directory.

            if ( !is_dir($sCopyPath) )
            {
                // Create the destination directory.
                $bRes = mkdir($sCopyPath);
                assert( '$bRes', vs(isset($this), get_defined_vars()) );
            }
            if ( $bCopyPermissions )
            {
                $bRes = chmod($sCopyPath, fileperms($sFileOrDirectoryPath));
                assert( '$bRes', vs(isset($this), get_defined_vars()) );
            }

            // Iterate through contained items.
            $oDir = dir($sFileOrDirectoryPath);
            assert( 'is_object($oDir)', vs(isset($this), get_defined_vars()) );
            while ( false !== $sItem = $oDir->read() )
            {
                if ( CString::equals($sItem, ".") || CString::equals($sItem, "..") )
                {
                    continue;
                }
                self::recurseCopy("$sFileOrDirectoryPath/$sItem", "$sCopyPath/$sItem", $bCopyPermissions);
            }
            $oDir->close();
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseDeleteDirectory ($sDirectoryPath)
    {
        $aItems = self::listItems($sDirectoryPath);
        $aFiles = CArray::filter($aItems, "CFile::isFile");
        $aDirs = CArray::filter($aItems, "CFile::isDirectory");
        $iDirQty = CArray::length($aDirs);
        for ($i = 0; $i < $iDirQty; $i++)
        {
            self::recurseDeleteDirectory($aDirs[$i]);
        }
        $iFileQty = CArray::length($aFiles);
        for ($i = 0; $i < $iFileQty; $i++)
        {
            self::delete($aFiles[$i]);
        }
        self::deleteEmptyDirectory($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseListItems ($sDirectoryPath, $bSort)
    {
        $aItems = self::listItems($sDirectoryPath, $bSort);
        $aDirs = CArray::filter($aItems, "CFile::isDirectory");
        $iDirQty = CArray::length($aDirs);
        for ($i = 0; $i < $iDirQty; $i++)
        {
            CArray::pushArray($aItems, self::recurseListItems($aDirs[$i], $bSort));
        }
        return $aItems;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseFindItems ($sDirectoryPath, $sWc, $bSort)
    {
        $aItems = self::findItems(CFilePath::add($sDirectoryPath, $sWc), $bSort);
        $aDirs = self::findDirectories(CFilePath::add($sDirectoryPath, "*"), $bSort);
        $iDirQty = CArray::length($aDirs);
        for ($i = 0; $i < $iDirQty; $i++)
        {
            CArray::pushArray($aItems, self::recurseFindItems($aDirs[$i], $sWc, $bSort));
        }
        return $aItems;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function finalize ()
    {
        $bRes = fclose($this->m_rcFile);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
        $this->m_bDone = true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_sFilePath;
    protected $m_eMode;
    protected $m_rcFile;
    protected $m_bDone = false;
}
