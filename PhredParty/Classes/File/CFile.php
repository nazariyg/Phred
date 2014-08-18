<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
//   static bool exists ($fileOrDirectoryPath)
//   static int size ($filePath)
//   static CUStringObject read ($filePath)
//   static void write ($filePath, $data)
//   static void append ($filePath, $data)
//   static void create ($filePath, $setPermissions = false)
//   static CUStringObject createTemporary ($inDirectoryPath = null,
//     $namePrefix = self::DEFAULT_TEMPORARY_FILE_PREFIX)
//   static void createDirectory ($directoryPath, $setPermissions = false)
//   static void copy ($fileOrDirectoryPath, $copyPath, $copyPermissions = true)
//   static void move ($fileOrDirectoryPath, $newPath)
//   static void rename ($fileOrDirectoryPath, $newName)
//   static void delete ($filePath)
//   static void deleteEmptyDirectory ($directoryPath)
//   static void deleteDirectoryRecursive ($directoryPath)
//   static CUStringObject permissions ($fileOrDirectoryPath)
//   static void setPermissions ($fileOrDirectoryPath, $permissions)
//   static CTime modificationTime ($filePath)
//   static bool isFile ($path)
//   static bool isDirectory ($path)
//   static CArrayObject listItems ($inDirectoryPath, $sort = false)
//   static CArrayObject listFiles ($inDirectoryPath, $sort = false)
//   static CArrayObject listDirectories ($inDirectoryPath, $sort = false)
//   static CArrayObject listItemsRecursive ($inDirectoryPath, $sort = false)
//   static CArrayObject listFilesRecursive ($inDirectoryPath, $sort = false)
//   static CArrayObject listDirectoriesRecursive ($inDirectoryPath, $sort = false)
//   static CArrayObject findItems ($wildcardPattern, $sort = false)
//   static CArrayObject findFiles ($wildcardPattern, $sort = false)
//   static CArrayObject findDirectories ($wildcardPattern, $sort = false)
//   static CArrayObject findItemsRecursive ($wildcardPattern, $sort = false)
//   static CArrayObject findFilesRecursive ($wildcardPattern, $sort = false)
//   static CArrayObject findDirectoriesRecursive ($wildcardPattern, $sort = false)
//   static CArrayObject reFindItems ($inDirectoryPath, $regexPattern, $sort = false)
//   static CArrayObject reFindFiles ($inDirectoryPath, $regexPattern, $sort = false)
//   static CArrayObject reFindDirectories ($inDirectoryPath, $regexPattern, $sort = false)
//   static CArrayObject reFindItemsRecursive ($inDirectoryPath, $regexPattern, $sort = false)
//   static CArrayObject reFindFilesRecursive ($inDirectoryPath, $regexPattern, $sort = false)
//   static CArrayObject reFindDirectoriesRecursive ($inDirectoryPath, $regexPattern, $sort = false)
//   static CArrayObject reFindItemsOnName ($inDirectoryPath, $regexPattern, $sort = false)
//   static CArrayObject reFindFilesOnName ($inDirectoryPath, $regexPattern, $sort = false)
//   static CArrayObject reFindDirectoriesOnName ($inDirectoryPath, $regexPattern, $sort = false)
//   static CArrayObject reFindItemsOnNameRecursive ($inDirectoryPath, $regexPattern, $sort = false)
//   static CArrayObject reFindFilesOnNameRecursive ($inDirectoryPath, $regexPattern, $sort = false)
//   static CArrayObject reFindDirectoriesOnNameRecursive ($inDirectoryPath, $regexPattern, $sort = false)
//   __construct ($filePath, $mode, $wrapper = self::NONE)
//   CUStringObject filePath ()
//   enum mode ()
//   resource systemResource ()
//   bool equals ($toFile)
//   CUStringObject readBytes ($quantity)
//   CUStringObject readAvailableBytes ($maxQuantity)
//   void writeBytes ($data, $maxQuantity = null)
//   int pos ()
//   void setPos ($pos)
//   void shiftPos ($shift)
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
     * @param  string $fileOrDirectoryPath The path to be checked (in case of a directory, its path is not required to
     * end with "/").
     *
     * @return bool `true` if the file or directory exists, `false` otherwise.
     */

    public static function exists ($fileOrDirectoryPath)
    {
        assert( 'is_cstring($fileOrDirectoryPath)', vs(isset($this), get_defined_vars()) );

        $fileOrDirectoryPath = CFilePath::frameworkPath($fileOrDirectoryPath);

        clearstatcache(true, $fileOrDirectoryPath);
        return file_exists($fileOrDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the size of a file.
     *
     * The method always reports on a file how it really is at the moment, without using any information that was
     * previously cached by the OS.
     *
     * @param  string $filePath The path to the file.
     *
     * @return int The size of the file, in bytes.
     */

    public static function size ($filePath)
    {
        assert( 'is_cstring($filePath)', vs(isset($this), get_defined_vars()) );

        $filePath = CFilePath::frameworkPath($filePath);

        clearstatcache(true, $filePath);
        $size = filesize($filePath);
        assert( 'is_int($size)', vs(isset($this), get_defined_vars()) );
        return $size;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Reads and returns the entire contents of a file.
     *
     * @param  string $filePath The path to the source file.
     *
     * @return CUStringObject The contents of the file.
     */

    public static function read ($filePath)
    {
        assert( 'is_cstring($filePath)', vs(isset($this), get_defined_vars()) );

        $filePath = CFilePath::frameworkPath($filePath);

        $data = file_get_contents($filePath, false);
        assert( 'is_cstring($data)', vs(isset($this), get_defined_vars()) );
        return $data;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Writes specified contents into a file, replacing any previous contents or creating the file if it doesn't exist.
     *
     * To prevent any other process from writing into the file during the operation, the method locks the file until
     * the operation is complete.
     *
     * @param  string $filePath The path to the destination file.
     * @param  data $data The contents to be written into the file.
     *
     * @return void
     */

    public static function write ($filePath, $data)
    {
        assert( 'is_cstring($filePath) && is_cstring($data)', vs(isset($this), get_defined_vars()) );

        $filePath = CFilePath::frameworkPath($filePath);

        $writtenByteQty = file_put_contents($filePath, $data, LOCK_EX);
        assert( 'is_int($writtenByteQty) && $writtenByteQty == CString::length($data)',
            vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Appends specified contents to the end of a file, creating the file if it doesn't exist.
     *
     * To prevent any other process from writing into the file during the operation, the method locks the file until
     * the operation is complete.
     *
     * @param  string $filePath The path to the destination file.
     * @param  data $data The contents to be appended to the file.
     *
     * @return void
     */

    public static function append ($filePath, $data)
    {
        assert( 'is_cstring($filePath) && is_cstring($data)', vs(isset($this), get_defined_vars()) );

        $filePath = CFilePath::frameworkPath($filePath);

        $writtenByteQty = file_put_contents($filePath, $data, LOCK_EX | FILE_APPEND);
        assert( 'is_int($writtenByteQty) && $writtenByteQty == CString::length($data)',
            vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an empty file at a specified path.
     *
     * If a file already exists at the specified path, it will be overwritten.
     *
     * @param  string $filePath The path at which the file is to be created.
     * @param  bool $setPermissions **OPTIONAL. Default is** `false`. Tells whether to set access permissions for the
     * file. If this parameter is `false`, then the permissions default to the ones determined by the OS's
     * configuration. And if it is `true`, then the file is created with the access permissions of "644", which means
     * read/write access for the file's owner (the user on whose behalf the application runs) and read-only access for
     * other users.
     *
     * @return void
     */

    public static function create ($filePath, $setPermissions = false)
    {
        assert( 'is_cstring($filePath)', vs(isset($this), get_defined_vars()) );

        $filePath = CFilePath::frameworkPath($filePath);

        // The `file_put_contents` function works faster than `touch`.
        $res = file_put_contents($filePath, "");
        assert( 'is_int($res)', vs(isset($this), get_defined_vars()) );
        if ( $setPermissions )
        {
            self::setPermissions($filePath, "644");
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
     * @param  string $inDirectoryPath **OPTIONAL. Default is** *the default directory for temporary files*. The path
     * to the directory where the file is to be created. If this parameter is omitted, the used directory is either the
     * OS's default directory for temporary files or, if specified, the directory read from the application's
     * configuration. If specified, the path is not required to end with "/".
     * @param  string $namePrefix **OPTIONAL. Default is** "tmp-". The name prefix for the file.
     *
     * @return CUStringObject The path to the created file.
     */

    public static function createTemporary ($inDirectoryPath = null,
        $namePrefix = self::DEFAULT_TEMPORARY_FILE_PREFIX)
    {
        assert( '(!isset($inDirectoryPath) || is_cstring($inDirectoryPath)) && is_cstring($namePrefix)',
            vs(isset($this), get_defined_vars()) );

        if ( isset($inDirectoryPath) )
        {
            $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);
        }
        else
        {
            $inDirectoryPath = CSystem::temporaryFilesDp();
        }

        $filePath = tempnam($inDirectoryPath, $namePrefix);
        assert( 'is_cstring($filePath)', vs(isset($this), get_defined_vars()) );
        self::setPermissions($filePath, self::DEFAULT_TEMPORARY_FILE_PERMISSIONS);
        return $filePath;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a directory at a specified path.
     *
     * If the path along the way mentions any other directories that don't exist, all of them will be created as well.
     *
     * @param  string $directoryPath The path at which the directory is to be created (not required to end with "/").
     * @param  bool $setPermissions **OPTIONAL. Default is** `false`. Tells whether to set access permissions for the
     * directory. If this parameter is `false`, then the permissions default to the ones determined by the OS's
     * configuration. And if it is `true`, then the directory is created with the access permissions of "755", which
     * means the ability to list items in the directory, add items to or remove items from the directory, and access
     * the items in the directory for the directory's owner (the user on whose behalf the application runs) and the
     * same level of access except adding/removing items for other users.
     *
     * @return void
     */

    public static function createDirectory ($directoryPath, $setPermissions = false)
    {
        assert( 'is_cstring($directoryPath)', vs(isset($this), get_defined_vars()) );

        $directoryPath = CFilePath::frameworkPath($directoryPath);

        $res = mkdir($directoryPath, 0777, true);
        assert( '$res', vs(isset($this), get_defined_vars()) );
        if ( $setPermissions )
        {
            self::setPermissions($directoryPath, "755");
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
     * @param  string $fileOrDirectoryPath The path to the file or directory to be copied (in case of a directory, its
     * path is not required to end with "/").
     * @param  string $copyPath The destination path.
     * @param  bool $copyPermissions **OPTIONAL. Default is** `true`. Tells whether the access permissions should be
     * copied too per each item.
     *
     * @return void
     */

    public static function copy ($fileOrDirectoryPath, $copyPath, $copyPermissions = true)
    {
        assert( 'is_cstring($fileOrDirectoryPath) && is_cstring($copyPath)', vs(isset($this), get_defined_vars()) );

        $fileOrDirectoryPath = CFilePath::frameworkPath($fileOrDirectoryPath);
        $copyPath = CFilePath::frameworkPath($copyPath);

        self::recurseCopy($fileOrDirectoryPath, $copyPath, $copyPermissions);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Moves a file or directory.
     *
     * If any file or directory already exists at the destination path, it will be overwritten.
     *
     * @param  string $fileOrDirectoryPath The path to the file or directory to be moved (in case of a directory, its
     * path is not required to end with "/").
     * @param  string $newPath The destination path.
     *
     * @return void
     */

    public static function move ($fileOrDirectoryPath, $newPath)
    {
        assert( 'is_cstring($fileOrDirectoryPath) && is_cstring($newPath)', vs(isset($this), get_defined_vars()) );

        $fileOrDirectoryPath = CFilePath::frameworkPath($fileOrDirectoryPath);
        $newPath = CFilePath::frameworkPath($newPath);

        $res = rename($fileOrDirectoryPath, $newPath);
        assert( '$res', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Renames a file or directory.
     *
     * If any file or directory already exists at the resulting path, it will be overwritten.
     *
     * @param  string $fileOrDirectoryPath The path to the file or directory to be renamed (in case of a directory,
     * its path is not required to end with "/").
     * @param  string $newName The new name for the file or directory.
     *
     * @return void
     */

    public static function rename ($fileOrDirectoryPath, $newName)
    {
        assert( 'is_cstring($fileOrDirectoryPath) && is_cstring($newName)', vs(isset($this), get_defined_vars()) );

        $newPath = CFilePath::add(CFilePath::directory($fileOrDirectoryPath), $newName);
        self::move($fileOrDirectoryPath, $newPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Deletes a file.
     *
     * Use either `deleteEmptyDirectory` or `deleteDirectoryRecursive` method to delete a directory.
     *
     * @param  string $filePath The path to the file to be deleted.
     *
     * @return void
     *
     * @link   #method_deleteEmptyDirectory deleteEmptyDirectory
     * @link   #method_deleteDirectoryRecursive deleteDirectoryRecursive
     */

    public static function delete ($filePath)
    {
        assert( 'is_cstring($filePath)', vs(isset($this), get_defined_vars()) );

        $filePath = CFilePath::frameworkPath($filePath);

        $res = unlink($filePath);
        assert( '$res', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Deletes a directory that is known to be empty.
     *
     * This method is a safeguard against data losses. Use `deleteDirectoryRecursive` method to delete a directory that
     * contains or may contain files or subdirectories.
     *
     * @param  string $directoryPath The path to the directory to be deleted (not required to end with "/").
     *
     * @return void
     *
     * @link   #method_deleteDirectoryRecursive deleteDirectoryRecursive
     */

    public static function deleteEmptyDirectory ($directoryPath)
    {
        assert( 'is_cstring($directoryPath)', vs(isset($this), get_defined_vars()) );

        $directoryPath = CFilePath::frameworkPath($directoryPath);

        $res = rmdir($directoryPath);
        assert( '$res', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Deletes a directory together with all of its files and subdirectories.
     *
     * @param  string $directoryPath The path to the directory to be deleted (not required to end with "/").
     *
     * @return void
     */

    public static function deleteDirectoryRecursive ($directoryPath)
    {
        assert( 'is_cstring($directoryPath)', vs(isset($this), get_defined_vars()) );

        $directoryPath = CFilePath::frameworkPath($directoryPath);

        self::recurseDeleteDirectory($directoryPath);
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
     * @param  string $fileOrDirectoryPath The path to the file or directory (in case of a directory, its path is not
     * required to end with "/").
     *
     * @return CUStringObject The access permissions used for the file or directory.
     */

    public static function permissions ($fileOrDirectoryPath)
    {
        assert( 'is_cstring($fileOrDirectoryPath)', vs(isset($this), get_defined_vars()) );

        $fileOrDirectoryPath = CFilePath::frameworkPath($fileOrDirectoryPath);

        clearstatcache(true, $fileOrDirectoryPath);
        $octalPerms = fileperms($fileOrDirectoryPath);
        return decoct($octalPerms & 0777);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets access permissions for a file or directory.
     *
     * The permissions are embodied in a string with three digits. For instance, the permissions on a file that is
     * allowed to be accessed by any user and with any intent are "777".
     *
     * @param  string $fileOrDirectoryPath The path to the file or directory (in case of a directory, its path is not
     * required to end with "/").
     * @param  string $permissions The access permissions to be used for the file or directory.
     *
     * @return void
     */

    public static function setPermissions ($fileOrDirectoryPath, $permissions)
    {
        assert( 'is_cstring($fileOrDirectoryPath) && is_cstring($permissions)',
            vs(isset($this), get_defined_vars()) );

        $fileOrDirectoryPath = CFilePath::frameworkPath($fileOrDirectoryPath);

        $octalPerms = octdec($permissions);
        $res = chmod($fileOrDirectoryPath, $octalPerms);
        assert( '$res', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the point in time when the file was last modified.
     *
     * The method always reports on a file how it really is at the moment, without using any information that was
     * previously cached by the OS.
     *
     * @param  string $filePath The path to the file.
     *
     * @return CTime The point in time when the file was last modified.
     */

    public static function modificationTime ($filePath)
    {
        assert( 'is_cstring($filePath)', vs(isset($this), get_defined_vars()) );

        $filePath = CFilePath::frameworkPath($filePath);

        clearstatcache(true, $filePath);
        $UTime = filemtime($filePath);
        assert( 'is_int($UTime)', vs(isset($this), get_defined_vars()) );
        return new CTime($UTime);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a path leads to a file.
     *
     * The method always reports on a path how it really is at the moment, without using any information that was
     * previously cached by the OS.
     *
     * @param  string $path The path to be checked.
     *
     * @return bool `true` if the path leads to a file, `false` in any other case.
     */

    public static function isFile ($path)
    {
        assert( 'is_cstring($path)', vs(isset($this), get_defined_vars()) );

        $path = CFilePath::frameworkPath($path);

        clearstatcache(true, $path);
        return is_file($path);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a path leads to a directory.
     *
     * The method always reports on a path how it really is at the moment, without using any information that was
     * previously cached by the OS.
     *
     * @param  string $path The path to be checked (not required to end with "/").
     *
     * @return bool `true` if the path leads to a directory, `false` in any other case.
     */

    public static function isDirectory ($path)
    {
        assert( 'is_cstring($path)', vs(isset($this), get_defined_vars()) );

        $path = CFilePath::frameworkPath($path);

        clearstatcache(true, $path);
        return is_dir($path);
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
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories contained in the directory specified.
     *
     * @link   #method_listItemsRecursive listItemsRecursive
     */

    public static function listItems ($inDirectoryPath, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_bool($sort)', vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        $paListingRes = scandir($inDirectoryPath, SCANDIR_SORT_NONE);
        assert( 'is_cmap($paListingRes)', vs(isset($this), get_defined_vars()) );
        $listingRes = CArray::fromPArray($paListingRes);
        $listingRes = CArray::filter($listingRes, function ($path)
            {
                return ( !CString::equals($path, "..") && !CString::equals($path, ".") );
            });
        $len = CArray::length($listingRes);
        for ($i = 0; $i < $len; $i++)
        {
            $listingRes[$i] = CFilePath::add($inDirectoryPath, $listingRes[$i]);
        }
        if ( $sort )
        {
            CArray::sortUStringsNatCi($listingRes);
        }
        return oop_a($listingRes);
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
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files contained in the directory specified.
     *
     * @link   #method_listFilesRecursive listFilesRecursive
     */

    public static function listFiles ($inDirectoryPath, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_bool($sort)', vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listItems($inDirectoryPath, $sort), "CFile::isFile"));
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
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories contained in the directory specified.
     *
     * @link   #method_listDirectoriesRecursive listDirectoriesRecursive
     */

    public static function listDirectories ($inDirectoryPath, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_bool($sort)', vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listItems($inDirectoryPath, $sort), "CFile::isDirectory"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files and subdirectories contained in a directory, in its subdirectories, and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories contained in the directory specified, in the
     * subdirectories of the directory, and so on.
     */

    public static function listItemsRecursive ($inDirectoryPath, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_bool($sort)', vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(self::recurseListItems($inDirectoryPath, $sort));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files contained in a directory, in its subdirectories, and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files contained in the directory specified, in the subdirectories of the
     * directory, and so on.
     */

    public static function listFilesRecursive ($inDirectoryPath, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_bool($sort)', vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listItemsRecursive($inDirectoryPath, $sort), "CFile::isFile"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the subdirectories contained in a directory, in its subdirectories, and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories contained in the directory specified, in the subdirectories
     * of the directory, and so on.
     */

    public static function listDirectoriesRecursive ($inDirectoryPath, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_bool($sort)', vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listItemsRecursive($inDirectoryPath, $sort), "CFile::isDirectory"));
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
     * @param  string $wildcardPattern The wildcard pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories found by the wildcard pattern specified.
     *
     * @link   #method_findItemsRecursive findItemsRecursive
     */

    public static function findItems ($wildcardPattern, $sort = false)
    {
        assert( 'is_cstring($wildcardPattern) && is_bool($sort)', vs(isset($this), get_defined_vars()) );

        $wildcardPattern = CFilePath::frameworkPath($wildcardPattern);

        $paSearchRes = glob($wildcardPattern, GLOB_NOSORT);
        assert( 'is_cmap($paSearchRes)', vs(isset($this), get_defined_vars()) );
        $searchRes = CArray::fromPArray($paSearchRes);
        if ( $sort )
        {
            CArray::sortUStringsNatCi($searchRes);
        }
        return oop_a($searchRes);
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
     * @param  string $wildcardPattern The wildcard pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files found by the wildcard pattern specified.
     *
     * @link   #method_findFilesRecursive findFilesRecursive
     */

    public static function findFiles ($wildcardPattern, $sort = false)
    {
        assert( 'is_cstring($wildcardPattern) && is_bool($sort)', vs(isset($this), get_defined_vars()) );

        $wildcardPattern = CFilePath::frameworkPath($wildcardPattern);

        return oop_a(CArray::filter(self::findItems($wildcardPattern, $sort), "CFile::isFile"));
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
     * @param  string $wildcardPattern The wildcard pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories found by the wildcard pattern specified.
     *
     * @link   #method_findDirectoriesRecursive findDirectoriesRecursive
     */

    public static function findDirectories ($wildcardPattern, $sort = false)
    {
        assert( 'is_cstring($wildcardPattern) && is_bool($sort)', vs(isset($this), get_defined_vars()) );

        $wildcardPattern = CFilePath::frameworkPath($wildcardPattern);

        $paSearchRes = glob($wildcardPattern, GLOB_NOSORT | GLOB_ONLYDIR);
        assert( 'is_cmap($paSearchRes)', vs(isset($this), get_defined_vars()) );
        $searchRes = CArray::fromPArray($paSearchRes);
        if ( $sort )
        {
            CArray::sortUStringsNatCi($searchRes);
        }
        return oop_a($searchRes);
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
     * @param  string $wildcardPattern The wildcard pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories found by the wildcard pattern specified,
     * including ones found in the subdirectories and so on.
     */

    public static function findItemsRecursive ($wildcardPattern, $sort = false)
    {
        assert( 'is_cstring($wildcardPattern) && is_bool($sort)', vs(isset($this), get_defined_vars()) );

        $wildcardPattern = CFilePath::frameworkPath($wildcardPattern);

        return oop_a(self::recurseFindItems(CFilePath::directory($wildcardPattern),
            CFilePath::name($wildcardPattern), $sort));
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
     * @param  string $wildcardPattern The wildcard pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files found by the wildcard pattern specified, including ones found in the
     * subdirectories and so on.
     */

    public static function findFilesRecursive ($wildcardPattern, $sort = false)
    {
        assert( 'is_cstring($wildcardPattern) && is_bool($sort)', vs(isset($this), get_defined_vars()) );

        $wildcardPattern = CFilePath::frameworkPath($wildcardPattern);

        return oop_a(CArray::filter(self::findItemsRecursive($wildcardPattern, $sort), "CFile::isFile"));
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
     * @param  string $wildcardPattern The wildcard pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories found by the wildcard pattern specified, including ones
     * found in the subdirectories and so on.
     */

    public static function findDirectoriesRecursive ($wildcardPattern, $sort = false)
    {
        assert( 'is_cstring($wildcardPattern) && is_bool($sort)', vs(isset($this), get_defined_vars()) );

        $wildcardPattern = CFilePath::frameworkPath($wildcardPattern);

        return oop_a(CArray::filter(self::findItemsRecursive($wildcardPattern, $sort), "CFile::isDirectory"));
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
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $regexPattern The regular expression pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories found by the regular expression pattern
     * specified.
     *
     * @link   #method_reFindItemsRecursive reFindItemsRecursive
     */

    public static function reFindItems ($inDirectoryPath, $regexPattern, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_cstring($regexPattern) && is_bool($sort)',
            vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listItems($inDirectoryPath, $sort), function ($path) use ($regexPattern)
            {
                return CRegex::find($path, $regexPattern);
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
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $regexPattern The regular expression pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files found by the regular expression pattern specified.
     *
     * @link   #method_reFindFilesRecursive reFindFilesRecursive
     */

    public static function reFindFiles ($inDirectoryPath, $regexPattern, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_cstring($regexPattern) && is_bool($sort)',
            vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listFiles($inDirectoryPath, $sort), function ($path) use ($regexPattern)
            {
                return CRegex::find($path, $regexPattern);
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
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $regexPattern The regular expression pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories found by the regular expression pattern specified.
     *
     * @link   #method_reFindDirectoriesRecursive reFindDirectoriesRecursive
     */

    public static function reFindDirectories ($inDirectoryPath, $regexPattern, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_cstring($regexPattern) && is_bool($sort)',
            vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listDirectories($inDirectoryPath, $sort),
            function ($path) use ($regexPattern)
            {
                return CRegex::find($path, $regexPattern);
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
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $regexPattern The regular expression pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories found by the regular expression pattern
     * specified, including ones found in the subdirectories of the specified directory and so on.
     */

    public static function reFindItemsRecursive ($inDirectoryPath, $regexPattern, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_cstring($regexPattern) && is_bool($sort)',
            vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listItemsRecursive($inDirectoryPath, $sort),
            function ($path) use ($regexPattern)
            {
                return CRegex::find($path, $regexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files found in a directory by a regular expression pattern, searching in the whole path
     * of every contained file with the pattern, also looking into the subdirectories of the directory and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $regexPattern The regular expression pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files found by the regular expression pattern specified, including ones
     * found in the subdirectories of the specified directory and so on.
     */

    public static function reFindFilesRecursive ($inDirectoryPath, $regexPattern, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_cstring($regexPattern) && is_bool($sort)',
            vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listFilesRecursive($inDirectoryPath, $sort),
            function ($path) use ($regexPattern)
            {
                return CRegex::find($path, $regexPattern);
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
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $regexPattern The regular expression pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories found by the regular expression pattern specified,
     * including ones found in the subdirectories of the specified directory and so on.
     */

    public static function reFindDirectoriesRecursive ($inDirectoryPath, $regexPattern, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_cstring($regexPattern) && is_bool($sort)',
            vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listDirectoriesRecursive($inDirectoryPath, $sort),
            function ($path) use ($regexPattern)
            {
                return CRegex::find($path, $regexPattern);
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
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $regexPattern The regular expression pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories found by the regular expression pattern
     * specified.
     *
     * @link   #method_reFindItemsOnNameRecursive reFindItemsOnNameRecursive
     */

    public static function reFindItemsOnName ($inDirectoryPath, $regexPattern, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_cstring($regexPattern) && is_bool($sort)',
            vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listItems($inDirectoryPath, $sort), function ($path) use ($regexPattern)
            {
                return CRegex::find(CFilePath::name($path), $regexPattern);
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
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $regexPattern The regular expression pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files found by the regular expression pattern specified.
     *
     * @link   #method_reFindFilesOnNameRecursive reFindFilesOnNameRecursive
     */

    public static function reFindFilesOnName ($inDirectoryPath, $regexPattern, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_cstring($regexPattern) && is_bool($sort)',
            vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listFiles($inDirectoryPath, $sort), function ($path) use ($regexPattern)
            {
                return CRegex::find(CFilePath::name($path), $regexPattern);
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
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $regexPattern The regular expression pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories found by the regular expression pattern specified.
     *
     * @link   #method_reFindDirectoriesOnNameRecursive reFindDirectoriesOnNameRecursive
     */

    public static function reFindDirectoriesOnName ($inDirectoryPath, $regexPattern, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_cstring($regexPattern) && is_bool($sort)',
            vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listDirectories($inDirectoryPath, $sort),
            function ($path) use ($regexPattern)
            {
                return CRegex::find(CFilePath::name($path), $regexPattern);
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
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $regexPattern The regular expression pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files and subdirectories found by the regular expression pattern
     * specified, including ones found in the subdirectories of the specified directory and so on.
     */

    public static function reFindItemsOnNameRecursive ($inDirectoryPath, $regexPattern, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_cstring($regexPattern) && is_bool($sort)',
            vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listItemsRecursive($inDirectoryPath, $sort),
            function ($path) use ($regexPattern)
            {
                return CRegex::find(CFilePath::name($path), $regexPattern);
            }));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the paths to the files found in a directory by a regular expression pattern, searching only in the name
     * of every contained file with the pattern, also looking into the subdirectories of the directory and so on.
     *
     * The returned paths are always absolute.
     *
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $regexPattern The regular expression pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the files found by the regular expression pattern specified, including ones
     * found in the subdirectories of the specified directory and so on.
     */

    public static function reFindFilesOnNameRecursive ($inDirectoryPath, $regexPattern, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_cstring($regexPattern) && is_bool($sort)',
            vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listFilesRecursive($inDirectoryPath, $sort),
            function ($path) use ($regexPattern)
            {
                return CRegex::find(CFilePath::name($path), $regexPattern);
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
     * @param  string $inDirectoryPath The path to the directory to be looked into (not required to end with "/").
     * @param  string $regexPattern The regular expression pattern to be used for searching.
     * @param  bool $sort **OPTIONAL. Default is** `false`. Tells whether the returned paths should be sorted, in the
     * ascending order.
     *
     * @return CArrayObject The paths to the subdirectories found by the regular expression pattern specified,
     * including ones found in the subdirectories of the specified directory and so on.
     */

    public static function reFindDirectoriesOnNameRecursive ($inDirectoryPath, $regexPattern, $sort = false)
    {
        assert( 'is_cstring($inDirectoryPath) && is_cstring($regexPattern) && is_bool($sort)',
            vs(isset($this), get_defined_vars()) );

        $inDirectoryPath = CFilePath::frameworkPath($inDirectoryPath);

        return oop_a(CArray::filter(self::listDirectoriesRecursive($inDirectoryPath, $sort),
            function ($path) use ($regexPattern)
            {
                return CRegex::find(CFilePath::name($path), $regexPattern);
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
     * @param  string $filePath The path to the file to be opened for access.
     * @param  enum $mode The access mode to be used for the file. Can be `READ`, `READ_WRITE`, `WRITE_NEW`,
     * `READ_WRITE_NEW`, `WRITE_APPEND`, and `READ_WRITE_APPEND`. See [Summary](#summary) for the description of a
     * mode.
     * @param  enum $wrapper **OPTIONAL. Default is** `NONE`. The wrapper to be used for accessing the file.
     * Currently, only `ZLIB` wrapper is supported.
     */

    public function __construct ($filePath, $mode, $wrapper = self::NONE)
    {
        assert( 'is_cstring($filePath) && is_enum($mode) && is_enum($wrapper)',
            vs(isset($this), get_defined_vars()) );

        $filePath = CFilePath::frameworkPath($filePath);

        $this->m_filePath = $filePath;
        $this->m_mode = $mode;

        $strMode;
        switch ( $mode )
        {
        case self::READ:
            $strMode = "rb";
            break;
        case self::READ_WRITE:
            $strMode = "r+b";
            break;
        case self::WRITE_NEW:
            $strMode = "wb";
            break;
        case self::READ_WRITE_NEW:
            $strMode = "w+b";
            break;
        case self::WRITE_APPEND:
            $strMode = "ab";
            break;
        case self::READ_WRITE_APPEND:
            $strMode = "a+b";
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $strWrapper;
        switch ( $wrapper )
        {
        case self::NONE:
            $strWrapper = "";
            break;
        case self::ZLIB:
            $strWrapper = "compress.zlib://";
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }
        $filePath = $strWrapper . $filePath;

        $this->m_file = fopen($filePath, $strMode);
        assert( 'is_resource($this->m_file)', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path to the file opened by an accessor.
     *
     * @return CUStringObject The path to the file.
     */

    public function filePath ()
    {
        return $this->m_filePath;
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
        return $this->m_mode;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the system resource of the file opened by an accessor.
     *
     * @return resource The system resource of the file.
     */

    public function systemResource ()
    {
        return $this->m_file;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an accessor is associated with the same file as another accessor.
     *
     * @param  CFile $toFile The second accessor for comparison.
     *
     * @return bool `true` if the two accessors are associated with the same file, `false` otherwise.
     */

    public function equals ($toFile)
    {
        // Parameter type hinting is not used for the purpose of interface compatibility.
        assert( 'is_cfile($toFile)', vs(isset($this), get_defined_vars()) );
        return CString::equals(CFilePath::absolute($this->m_filePath), CFilePath::absolute($toFile->m_filePath));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Reads a specified number of bytes from the file opened by an accessor and returns them.
     *
     * Bytes are read starting from the current reading/writing position.
     *
     * @param  int $quantity The number of bytes to be read (cannot be zero).
     *
     * @return CUStringObject The read bytes.
     */

    public function readBytes ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        assert( '$quantity > 0', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_mode == self::READ || ' .
                '$this->m_mode == self::READ_WRITE || ' .
                '$this->m_mode == self::READ_WRITE_NEW || ' .
                '$this->m_mode == self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_done', vs(isset($this), get_defined_vars()) );

        $data = fread($this->m_file, $quantity);
        assert( 'is_cstring($data) && CString::length($data) == $quantity', vs(isset($this), get_defined_vars()) );
        return $data;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Reads no more than a specified number of bytes from the file opened by an accessor and returns them.
     *
     * Bytes are read starting from the current reading/writing position and, even when there are less bytes left
     * available than indicated by the parameter's value, the operation will succeed and the bytes starting from the
     * current reading/writing position and up to the end of the file will be returned.
     *
     * @param  int $maxQuantity The maximum number of available bytes to be read (cannot be zero).
     *
     * @return CUStringObject The read bytes.
     */

    public function readAvailableBytes ($maxQuantity)
    {
        assert( 'is_int($maxQuantity)', vs(isset($this), get_defined_vars()) );
        assert( '$maxQuantity > 0', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_mode == self::READ || ' .
                '$this->m_mode == self::READ_WRITE || ' .
                '$this->m_mode == self::READ_WRITE_NEW || ' .
                '$this->m_mode == self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_done', vs(isset($this), get_defined_vars()) );

        $data = fread($this->m_file, $maxQuantity);
        assert( 'is_cstring($data)', vs(isset($this), get_defined_vars()) );
        return $data;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Writes specified bytes into the file opened by an accessor.
     *
     * Bytes are written starting from the current reading/writing position.
     *
     * @param  data $data The bytes to be written.
     * @param  int $maxQuantity **OPTIONAL. Default is** *all of the provided bytes*. The maximum number of bytes to
     * be written.
     *
     * @return void
     */

    public function writeBytes ($data, $maxQuantity = null)
    {
        assert( 'is_cstring($data) && (!isset($maxQuantity) || is_int($maxQuantity))',
            vs(isset($this), get_defined_vars()) );
        assert( '!isset($maxQuantity) || (0 <= $maxQuantity && $maxQuantity <= CString::length($data))',
            vs(isset($this), get_defined_vars()) );
        assert( '$this->m_mode == self::READ_WRITE || ' .
                '$this->m_mode == self::WRITE_NEW || ' .
                '$this->m_mode == self::READ_WRITE_NEW || ' .
                '$this->m_mode == self::WRITE_APPEND || ' .
                '$this->m_mode == self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_done', vs(isset($this), get_defined_vars()) );

        if ( !isset($maxQuantity) )
        {
            $writtenByteQty = fwrite($this->m_file, $data);
            assert( 'is_int($writtenByteQty) && $writtenByteQty == CString::length($data)',
                vs(isset($this), get_defined_vars()) );
        }
        else
        {
            $writtenByteQty = fwrite($this->m_file, $data, $maxQuantity);
            assert( 'is_int($writtenByteQty) && $writtenByteQty == $maxQuantity',
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
        assert( '$this->m_mode != self::WRITE_APPEND && ' .
                '$this->m_mode != self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_done', vs(isset($this), get_defined_vars()) );

        $pos = ftell($this->m_file);
        assert( 'is_int($pos)', vs(isset($this), get_defined_vars()) );
        return $pos;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the current reading/writing position of an accessor.
     *
     * Positions are zero-based, so the position of the first byte in a file is `0`, the position of the second byte is
     * `1`, and so on.
     *
     * @param  int $pos The new reading/writing position.
     *
     * @return void
     */

    public function setPos ($pos)
    {
        assert( 'is_int($pos)', vs(isset($this), get_defined_vars()) );
        assert( '$pos >= 0', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_mode != self::WRITE_APPEND && ' .
                '$this->m_mode != self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_done', vs(isset($this), get_defined_vars()) );

        $res = fseek($this->m_file, $pos, SEEK_SET);
        assert( '$res == 0', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts the current reading/writing position of an accessor in any direction by a specified number of bytes.
     *
     * @param  int $shift The positive or negative number of bytes by which the reading/writing position is to be
     * shifted.
     *
     * @return void
     */

    public function shiftPos ($shift)
    {
        assert( 'is_int($shift)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_mode != self::WRITE_APPEND && ' .
                '$this->m_mode != self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_done', vs(isset($this), get_defined_vars()) );

        $res = fseek($this->m_file, $shift, SEEK_CUR);
        assert( '$res == 0', vs(isset($this), get_defined_vars()) );
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
        assert( '$this->m_mode != self::WRITE_APPEND && ' .
                '$this->m_mode != self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_done', vs(isset($this), get_defined_vars()) );

        $res = rewind($this->m_file);
        assert( '$res', vs(isset($this), get_defined_vars()) );
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
        assert( '$this->m_mode != self::WRITE_APPEND && ' .
                '$this->m_mode != self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_done', vs(isset($this), get_defined_vars()) );

        $res = fseek($this->m_file, 0, SEEK_END);
        assert( '$res == 0', vs(isset($this), get_defined_vars()) );
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
        assert( '$this->m_mode != self::WRITE_APPEND && ' .
                '$this->m_mode != self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_done', vs(isset($this), get_defined_vars()) );

        return feof($this->m_file);
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
        assert( '$this->m_mode == self::READ_WRITE || ' .
                '$this->m_mode == self::WRITE_NEW || ' .
                '$this->m_mode == self::READ_WRITE_NEW || ' .
                '$this->m_mode == self::WRITE_APPEND || ' .
                '$this->m_mode == self::READ_WRITE_APPEND', vs(isset($this), get_defined_vars()) );
        assert( '!$this->m_done', vs(isset($this), get_defined_vars()) );

        $res = fflush($this->m_file);
        assert( '$res', vs(isset($this), get_defined_vars()) );
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
        assert( '!$this->m_done', vs(isset($this), get_defined_vars()) );
        $this->finalize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function __destruct ()
    {
        if ( !$this->m_done )
        {
            $this->finalize();
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseCopy ($fileOrDirectoryPath, $copyPath, $copyPermissions)
    {
        if ( is_link($fileOrDirectoryPath) )
        {
            // The source is a symlink, so make another symlink.
            $symlinkTargetPath = readlink($fileOrDirectoryPath);
            assert( 'is_cstring($symlinkTargetPath)', vs(isset($this), get_defined_vars()) );
            $res = symlink($symlinkTargetPath, $copyPath);
            assert( '$res', vs(isset($this), get_defined_vars()) );
            return;
        }

        if ( $copyPermissions )
        {
            clearstatcache(true, $fileOrDirectoryPath);
        }
        if ( is_file($fileOrDirectoryPath) )
        {
            // A file.
            $res = copy($fileOrDirectoryPath, $copyPath);
            assert( '$res', vs(isset($this), get_defined_vars()) );
            if ( $copyPermissions )
            {
                $res = chmod($copyPath, fileperms($fileOrDirectoryPath));
                assert( '$res', vs(isset($this), get_defined_vars()) );
            }
        }
        else
        {
            // A directory.

            if ( !is_dir($copyPath) )
            {
                // Create the destination directory.
                $res = mkdir($copyPath);
                assert( '$res', vs(isset($this), get_defined_vars()) );
            }
            if ( $copyPermissions )
            {
                $res = chmod($copyPath, fileperms($fileOrDirectoryPath));
                assert( '$res', vs(isset($this), get_defined_vars()) );
            }

            // Iterate through contained items.
            $dir = dir($fileOrDirectoryPath);
            assert( 'is_object($dir)', vs(isset($this), get_defined_vars()) );
            while ( false !== $item = $dir->read() )
            {
                if ( CString::equals($item, ".") || CString::equals($item, "..") )
                {
                    continue;
                }
                self::recurseCopy("$fileOrDirectoryPath/$item", "$copyPath/$item", $copyPermissions);
            }
            $dir->close();
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseDeleteDirectory ($directoryPath)
    {
        $items = self::listItems($directoryPath);
        $files = CArray::filter($items, "CFile::isFile");
        $dirs = CArray::filter($items, "CFile::isDirectory");
        $dirQty = CArray::length($dirs);
        for ($i = 0; $i < $dirQty; $i++)
        {
            self::recurseDeleteDirectory($dirs[$i]);
        }
        $fileQty = CArray::length($files);
        for ($i = 0; $i < $fileQty; $i++)
        {
            self::delete($files[$i]);
        }
        self::deleteEmptyDirectory($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseListItems ($directoryPath, $sort)
    {
        $items = self::listItems($directoryPath, $sort);
        $dirs = CArray::filter($items, "CFile::isDirectory");
        $dirQty = CArray::length($dirs);
        for ($i = 0; $i < $dirQty; $i++)
        {
            CArray::pushArray($items, self::recurseListItems($dirs[$i], $sort));
        }
        return $items;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseFindItems ($directoryPath, $wc, $sort)
    {
        $items = self::findItems(CFilePath::add($directoryPath, $wc), $sort);
        $dirs = self::findDirectories(CFilePath::add($directoryPath, "*"), $sort);
        $dirQty = CArray::length($dirs);
        for ($i = 0; $i < $dirQty; $i++)
        {
            CArray::pushArray($items, self::recurseFindItems($dirs[$i], $wc, $sort));
        }
        return $items;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function finalize ()
    {
        $res = fclose($this->m_file);
        assert( '$res', vs(isset($this), get_defined_vars()) );
        $this->m_done = true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_filePath;
    protected $m_mode;
    protected $m_file;
    protected $m_done = false;
}
