<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CFileTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFlush ()
    {
        $filePath = CFile::createTemporary();

        $file = new CFile($filePath, CFile::WRITE_NEW);
        $file->writeBytes("Hello");
        $file->flush();
        $this->assertTrue(CFile::read($filePath)->equals("Hello"));
        $file->writeBytes(" there!");
        $file->flush();
        $this->assertTrue(CFile::read($filePath)->equals("Hello there!"));
        $file->done();  // only needed in these tests

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testExists ()
    {
        $filePath = CFile::createTemporary();

        $this->assertTrue(CFile::exists($filePath));
        $this->assertFalse(CFile::exists("/_nodir/_nofile"));

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSize ()
    {
        $filePath = CFile::createTemporary();

        CFile::write($filePath, "Hello there!");
        $this->assertTrue( CFile::size($filePath) == 12 );

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRead ()
    {
        $filePath = CFile::createTemporary();

        file_put_contents($filePath, "Hello there!");
        $this->assertTrue(CFile::read($filePath)->equals("Hello there!"));

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWrite ()
    {
        $filePath = CFile::createTemporary();

        CFile::write($filePath, "Hello there!");
        $this->assertTrue(u(file_get_contents($filePath))->equals("Hello there!"));

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAppend ()
    {
        $filePath = CFile::createTemporary();

        CFile::append($filePath, "Hello there!");
        CFile::append($filePath, " You!");
        $this->assertTrue(CFile::read($filePath)->equals("Hello there! You!"));

        CFile::write($filePath, "ab");
        CFile::append($filePath, "cd");
        $this->assertTrue(CFile::read($filePath)->equals("abcd"));

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCreate ()
    {
        $filePath = CFile::createTemporary();

        CFile::delete($filePath);
        CFile::create($filePath);
        $this->assertTrue(CFile::exists($filePath));

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCreateTemporary ()
    {
        $filePath = CFile::createTemporary();
        $this->assertTrue(CFile::exists($filePath));
        CFile::delete($filePath);

        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);
        $filePath = CFile::createTemporary($directoryPath);
        $this->assertTrue(CFile::exists($filePath));
        CFile::delete($filePath);
        CFile::deleteEmptyDirectory($directoryPath);

        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);
        $filePath = CFile::createTemporary($directoryPath, "old-");
        $this->assertTrue( CFile::exists($filePath) && CFilePath::name($filePath)->startsWith("old-") );
        CFile::delete($filePath);
        CFile::deleteEmptyDirectory($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCreateDirectory ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);
        $this->assertTrue(CFile::exists($directoryPath));
        CFile::deleteEmptyDirectory($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCopy ()
    {
        // A file.
        $filePath0 = CFile::createTemporary();
        $filePath1 = CFile::createTemporary();
        CFile::write($filePath0, "Hello there!");
        CFile::copy($filePath0, $filePath1);
        $this->assertTrue( CFile::exists($filePath0) && CFile::exists($filePath1) &&
            CFile::read($filePath1)->equals("Hello there!") );
        CFile::delete($filePath0);
        CFile::delete($filePath1);

        // A directory.
        $directoryPath0 = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath0) )
        {
            CFile::deleteDirectoryRecursive($directoryPath0);
        }
        CFile::createDirectory($directoryPath0);
        CFile::create(CFilePath::add($directoryPath0, "file-a3"));
        CFile::create(CFilePath::add($directoryPath0, "file-a20"));
        CFile::create(CFilePath::add($directoryPath0, "file-a100"));
        $directoryPath0Sub0 = CFilePath::add($directoryPath0, "dir-a2");
        $directoryPath0Sub1 = CFilePath::add($directoryPath0, "dir-a10");
        CFile::createDirectory($directoryPath0Sub0);
        CFile::create(CFilePath::add($directoryPath0Sub0, "file-a2"));
        CFile::create(CFilePath::add($directoryPath0Sub0, "file-a10"));
        CFile::createDirectory($directoryPath0Sub1);
        CFile::create(CFilePath::add($directoryPath0Sub1, "file-a2"));
        CFile::create(CFilePath::add($directoryPath0Sub1, "file-a10"));
        $directoryPath1 = "$directoryPath0-copy";
        if ( CFile::exists($directoryPath1) )
        {
            CFile::deleteDirectoryRecursive($directoryPath1);
        }
        CFile::copy($directoryPath0, $directoryPath1);
        $this->assertTrue(CFile::exists($directoryPath1));
        $paths = CFile::listItemsRecursive($directoryPath1);
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 9 );
        $this->assertTrue(
            $paths->find("/file-a3", $comparator) &&
            $paths->find("/file-a20", $comparator) &&
            $paths->find("/file-a100", $comparator) &&
            $paths->find("/dir-a2", $comparator) &&
            $paths->find("/dir-a10", $comparator) &&
            $paths->find("/dir-a2/file-a2", $comparator) &&
            $paths->find("/dir-a2/file-a10", $comparator) &&
            $paths->find("/dir-a10/file-a2", $comparator) &&
            $paths->find("/dir-a10/file-a10", $comparator) );
        CFile::deleteDirectoryRecursive($directoryPath0);
        CFile::deleteDirectoryRecursive($directoryPath1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMove ()
    {
        $filePath0 = CFile::createTemporary();
        $filePath1 = CFile::createTemporary();

        CFile::write($filePath0, "Hello there!");
        CFile::move($filePath0, $filePath1);
        $this->assertTrue( !CFile::exists($filePath0) && CFile::exists($filePath1) &&
            CFile::read($filePath1)->equals("Hello there!") );

        CFile::delete($filePath1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRename ()
    {
        $filePath0 = CFile::createTemporary();
        $newName = CFilePath::name($filePath0) . "-renamed";
        $filePath1 = CFilePath::add(CFilePath::directory($filePath0), $newName);
        CFile::write($filePath0, "Hello there!");
        CFile::rename($filePath0, $newName);
        $this->assertTrue( !CFile::exists($filePath0) && CFile::exists($filePath1) &&
            CFile::read($filePath1)->equals("Hello there!") );
        CFile::delete($filePath1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDelete ()
    {
        $filePath = CFile::createTemporary();
        CFile::delete($filePath);
        $this->assertFalse(CFile::exists($filePath));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDeleteEmptyDirectory ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);
        $this->assertTrue(CFile::exists($directoryPath));
        CFile::deleteEmptyDirectory($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDeleteDirectoryRecursive ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::createTemporary($directoryPath);
        $directoryPathL2 = CFilePath::add($directoryPath, "dir");
        CFile::createDirectory($directoryPathL2);
        CFile::createTemporary($directoryPathL2);
        CFile::deleteDirectoryRecursive($directoryPath);
        $this->assertTrue( !CFile::exists($directoryPathL2) && !CFile::exists($directoryPath) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPermissions ()
    {
        $filePath = CFile::createTemporary();

        CFile::setPermissions($filePath, "432");
        $this->assertTrue(CFile::permissions($filePath)->equals("432"));

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetPermissions ()
    {
        $filePath = CFile::createTemporary();

        CFile::setPermissions($filePath, "432");
        $this->assertTrue(CFile::permissions($filePath)->equals("432"));

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testModificationTime ()
    {
        $filePath = CFile::createTemporary();

        $modTime = CFile::modificationTime($filePath);
        $this->assertTrue( $modTime->diffInSeconds(CTime::now()) <= 5 );

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsFile ()
    {
        $filePath = CFile::createTemporary();
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        $this->assertTrue( CFile::exists($filePath) && CFile::isFile($filePath) );
        $this->assertTrue( CFile::exists($directoryPath) && !CFile::isFile($directoryPath) );

        CFile::delete($filePath);
        CFile::deleteEmptyDirectory($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsDirectory ()
    {
        $filePath = CFile::createTemporary();
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        $this->assertTrue( CFile::exists($filePath) && !CFile::isDirectory($filePath) );
        $this->assertTrue( CFile::exists($directoryPath) && CFile::isDirectory($directoryPath) );

        CFile::delete($filePath);
        CFile::deleteEmptyDirectory($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testListItems ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a10"));

        $paths = CFile::listItems($directoryPath);
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 5 );
        $this->assertTrue(
            $paths->find("/file-a3", $comparator) &&
            $paths->find("/file-a20", $comparator) &&
            $paths->find("/file-a100", $comparator) &&
            $paths->find("/dir-a2", $comparator) &&
            $paths->find("/dir-a10", $comparator) );

        $paths = CFile::listItems($directoryPath, true);
        $this->assertTrue( $paths->length() == 5 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-a2") &&
            $paths[1]->endsWith("/dir-a10") &&
            $paths[2]->endsWith("/file-a3") &&
            $paths[3]->endsWith("/file-a20") &&
            $paths[4]->endsWith("/file-a100") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testListFiles ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a10"));

        $paths = CFile::listFiles($directoryPath);
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 3 );
        $this->assertTrue(
            $paths->find("/file-a3", $comparator) &&
            $paths->find("/file-a20", $comparator) &&
            $paths->find("/file-a100", $comparator) );

        $paths = CFile::listFiles($directoryPath, true);
        $this->assertTrue( $paths->length() == 3 );
        $this->assertTrue(
            $paths[0]->endsWith("/file-a3") &&
            $paths[1]->endsWith("/file-a20") &&
            $paths[2]->endsWith("/file-a100") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testListDirectories ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a10"));

        $paths = CFile::listDirectories($directoryPath);
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 2 );
        $this->assertTrue(
            $paths->find("/dir-a2", $comparator) &&
            $paths->find("/dir-a10", $comparator) );

        $paths = CFile::listDirectories($directoryPath, true);
        $this->assertTrue( $paths->length() == 2 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-a2") &&
            $paths[1]->endsWith("/dir-a10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testListItemsRecursive ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        $directoryPathSub0 = CFilePath::add($directoryPath, "dir-a2");
        $directoryPathSub1 = CFilePath::add($directoryPath, "dir-a10");
        CFile::createDirectory($directoryPathSub0);
        CFile::create(CFilePath::add($directoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub0, "file-a10"));
        CFile::createDirectory($directoryPathSub1);
        CFile::create(CFilePath::add($directoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub1, "file-a10"));

        $paths = CFile::listItemsRecursive($directoryPath);
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 9 );
        $this->assertTrue(
            $paths->find("/file-a3", $comparator) &&
            $paths->find("/file-a20", $comparator) &&
            $paths->find("/file-a100", $comparator) &&
            $paths->find("/dir-a2", $comparator) &&
            $paths->find("/dir-a10", $comparator) &&
            $paths->find("/dir-a2/file-a2", $comparator) &&
            $paths->find("/dir-a2/file-a10", $comparator) &&
            $paths->find("/dir-a10/file-a2", $comparator) &&
            $paths->find("/dir-a10/file-a10", $comparator) );

        $paths = CFile::listItemsRecursive($directoryPath, true);
        $this->assertTrue( $paths->length() == 9 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-a2") &&
            $paths[1]->endsWith("/dir-a10") &&
            $paths[2]->endsWith("/file-a3") &&
            $paths[3]->endsWith("/file-a20") &&
            $paths[4]->endsWith("/file-a100") &&
            $paths[5]->endsWith("/dir-a2/file-a2") &&
            $paths[6]->endsWith("/dir-a2/file-a10") &&
            $paths[7]->endsWith("/dir-a10/file-a2") &&
            $paths[8]->endsWith("/dir-a10/file-a10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testListFilesRecursive ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        $directoryPathSub0 = CFilePath::add($directoryPath, "dir-a2");
        $directoryPathSub1 = CFilePath::add($directoryPath, "dir-a10");
        CFile::createDirectory($directoryPathSub0);
        CFile::create(CFilePath::add($directoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub0, "file-a10"));
        CFile::createDirectory($directoryPathSub1);
        CFile::create(CFilePath::add($directoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub1, "file-a10"));

        $paths = CFile::listFilesRecursive($directoryPath);
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 7 );
        $this->assertTrue(
            $paths->find("/file-a3", $comparator) &&
            $paths->find("/file-a20", $comparator) &&
            $paths->find("/file-a100", $comparator) &&
            $paths->find("/dir-a2/file-a2", $comparator) &&
            $paths->find("/dir-a2/file-a10", $comparator) &&
            $paths->find("/dir-a10/file-a2", $comparator) &&
            $paths->find("/dir-a10/file-a10", $comparator) );

        $paths = CFile::listFilesRecursive($directoryPath, true);
        $this->assertTrue( $paths->length() == 7 );
        $this->assertTrue(
            $paths[0]->endsWith("/file-a3") &&
            $paths[1]->endsWith("/file-a20") &&
            $paths[2]->endsWith("/file-a100") &&
            $paths[3]->endsWith("/dir-a2/file-a2") &&
            $paths[4]->endsWith("/dir-a2/file-a10") &&
            $paths[5]->endsWith("/dir-a10/file-a2") &&
            $paths[6]->endsWith("/dir-a10/file-a10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testListDirectoriesRecursive ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        $directoryPathSub0 = CFilePath::add($directoryPath, "dir-a2");
        $directoryPathSub1 = CFilePath::add($directoryPath, "dir-a10");
        CFile::createDirectory($directoryPathSub0);
        CFile::createDirectory(CFilePath::add($directoryPathSub0, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub0, "dir-a10"));
        CFile::createDirectory($directoryPathSub1);
        CFile::createDirectory(CFilePath::add($directoryPathSub1, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub1, "dir-a10"));

        $paths = CFile::listDirectoriesRecursive($directoryPath);
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 6 );
        $this->assertTrue(
            $paths->find("/dir-a2", $comparator) &&
            $paths->find("/dir-a10", $comparator) &&
            $paths->find("/dir-a2/dir-a2", $comparator) &&
            $paths->find("/dir-a2/dir-a10", $comparator) &&
            $paths->find("/dir-a10/dir-a2", $comparator) &&
            $paths->find("/dir-a10/dir-a10", $comparator) );

        $paths = CFile::listDirectoriesRecursive($directoryPath, true);
        $this->assertTrue( $paths->length() == 6 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-a2") &&
            $paths[1]->endsWith("/dir-a10") &&
            $paths[2]->endsWith("/dir-a2/dir-a2") &&
            $paths[3]->endsWith("/dir-a2/dir-a10") &&
            $paths[4]->endsWith("/dir-a10/dir-a2") &&
            $paths[5]->endsWith("/dir-a10/dir-a10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindItems ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b10"));

        $paths = CFile::findItems(CFilePath::add($directoryPath, "*-b*"));
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 5 );
        $this->assertTrue(
            $paths->find("/file-b3", $comparator) &&
            $paths->find("/file-b20", $comparator) &&
            $paths->find("/file-b100", $comparator) &&
            $paths->find("/dir-b2", $comparator) &&
            $paths->find("/dir-b10", $comparator) );

        $paths = CFile::findItems(CFilePath::add($directoryPath, "*-b*"), true);
        $this->assertTrue( $paths->length() == 5 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-b2") &&
            $paths[1]->endsWith("/dir-b10") &&
            $paths[2]->endsWith("/file-b3") &&
            $paths[3]->endsWith("/file-b20") &&
            $paths[4]->endsWith("/file-b100") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindFiles ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b10"));

        $paths = CFile::findFiles(CFilePath::add($directoryPath, "*-b*"));
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 3 );
        $this->assertTrue(
            $paths->find("/file-b3", $comparator) &&
            $paths->find("/file-b20", $comparator) &&
            $paths->find("/file-b100", $comparator) );

        $paths = CFile::findFiles(CFilePath::add($directoryPath, "*-b*"), true);
        $this->assertTrue( $paths->length() == 3 );
        $this->assertTrue(
            $paths[0]->endsWith("/file-b3") &&
            $paths[1]->endsWith("/file-b20") &&
            $paths[2]->endsWith("/file-b100") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindDirectories ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b10"));

        $paths = CFile::findDirectories(CFilePath::add($directoryPath, "*-b*"));
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 2 );
        $this->assertTrue(
            $paths->find("/dir-b2", $comparator) &&
            $paths->find("/dir-b10", $comparator) );

        $paths = CFile::findDirectories(CFilePath::add($directoryPath, "*-b*"), true);
        $this->assertTrue( $paths->length() == 2 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-b2") &&
            $paths[1]->endsWith("/dir-b10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindItemsRecursive ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        $directoryPathSub0 = CFilePath::add($directoryPath, "dir-a2");
        $directoryPathSub1 = CFilePath::add($directoryPath, "dir-a10");
        $directoryPathSub2 = CFilePath::add($directoryPath, "dir-b2");
        $directoryPathSub3 = CFilePath::add($directoryPath, "dir-b10");
        CFile::createDirectory($directoryPathSub0);
        CFile::create(CFilePath::add($directoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub0, "file-a10"));
        CFile::createDirectory($directoryPathSub1);
        CFile::create(CFilePath::add($directoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub1, "file-a10"));
        CFile::createDirectory($directoryPathSub2);
        CFile::create(CFilePath::add($directoryPathSub2, "file-b2"));
        CFile::create(CFilePath::add($directoryPathSub2, "file-b10"));
        CFile::createDirectory($directoryPathSub3);
        CFile::create(CFilePath::add($directoryPathSub3, "file-b2"));
        CFile::create(CFilePath::add($directoryPathSub3, "file-b10"));

        $paths = CFile::findItemsRecursive(CFilePath::add($directoryPath, "*-b*"));
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 9 );
        $this->assertTrue(
            $paths->find("/file-b3", $comparator) &&
            $paths->find("/file-b20", $comparator) &&
            $paths->find("/file-b100", $comparator) &&
            $paths->find("/dir-b2", $comparator) &&
            $paths->find("/dir-b10", $comparator) &&
            $paths->find("/dir-b2/file-b2", $comparator) &&
            $paths->find("/dir-b2/file-b10", $comparator) &&
            $paths->find("/dir-b10/file-b2", $comparator) &&
            $paths->find("/dir-b10/file-b10", $comparator) );

        $paths = CFile::findItemsRecursive(CFilePath::add($directoryPath, "*-b*"), true);
        $this->assertTrue( $paths->length() == 9 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-b2") &&
            $paths[1]->endsWith("/dir-b10") &&
            $paths[2]->endsWith("/file-b3") &&
            $paths[3]->endsWith("/file-b20") &&
            $paths[4]->endsWith("/file-b100") &&
            $paths[5]->endsWith("/dir-b2/file-b2") &&
            $paths[6]->endsWith("/dir-b2/file-b10") &&
            $paths[7]->endsWith("/dir-b10/file-b2") &&
            $paths[8]->endsWith("/dir-b10/file-b10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindFilesRecursive ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        $directoryPathSub0 = CFilePath::add($directoryPath, "dir-a2");
        $directoryPathSub1 = CFilePath::add($directoryPath, "dir-a10");
        $directoryPathSub2 = CFilePath::add($directoryPath, "dir-b2");
        $directoryPathSub3 = CFilePath::add($directoryPath, "dir-b10");
        CFile::createDirectory($directoryPathSub0);
        CFile::create(CFilePath::add($directoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub0, "file-a10"));
        CFile::createDirectory($directoryPathSub1);
        CFile::create(CFilePath::add($directoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub1, "file-a10"));
        CFile::createDirectory($directoryPathSub2);
        CFile::create(CFilePath::add($directoryPathSub2, "file-b2"));
        CFile::create(CFilePath::add($directoryPathSub2, "file-b10"));
        CFile::createDirectory($directoryPathSub3);
        CFile::create(CFilePath::add($directoryPathSub3, "file-b2"));
        CFile::create(CFilePath::add($directoryPathSub3, "file-b10"));

        $paths = CFile::findFilesRecursive(CFilePath::add($directoryPath, "*-b*"));
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 7 );
        $this->assertTrue(
            $paths->find("/file-b3", $comparator) &&
            $paths->find("/file-b20", $comparator) &&
            $paths->find("/file-b100", $comparator) &&
            $paths->find("/dir-b2/file-b2", $comparator) &&
            $paths->find("/dir-b2/file-b10", $comparator) &&
            $paths->find("/dir-b10/file-b2", $comparator) &&
            $paths->find("/dir-b10/file-b10", $comparator) );

        $paths = CFile::findFilesRecursive(CFilePath::add($directoryPath, "*-b*"), true);
        $this->assertTrue( $paths->length() == 7 );
        $this->assertTrue(
            $paths[0]->endsWith("/file-b3") &&
            $paths[1]->endsWith("/file-b20") &&
            $paths[2]->endsWith("/file-b100") &&
            $paths[3]->endsWith("/dir-b2/file-b2") &&
            $paths[4]->endsWith("/dir-b2/file-b10") &&
            $paths[5]->endsWith("/dir-b10/file-b2") &&
            $paths[6]->endsWith("/dir-b10/file-b10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindDirectoriesRecursive ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        $directoryPathSub0 = CFilePath::add($directoryPath, "dir-a2");
        $directoryPathSub1 = CFilePath::add($directoryPath, "dir-a10");
        $directoryPathSub2 = CFilePath::add($directoryPath, "dir-b2");
        $directoryPathSub3 = CFilePath::add($directoryPath, "dir-b10");
        CFile::createDirectory($directoryPathSub0);
        CFile::createDirectory(CFilePath::add($directoryPathSub0, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub0, "dir-a10"));
        CFile::createDirectory($directoryPathSub1);
        CFile::createDirectory(CFilePath::add($directoryPathSub1, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub1, "dir-a10"));
        CFile::createDirectory($directoryPathSub2);
        CFile::createDirectory(CFilePath::add($directoryPathSub2, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub2, "dir-b10"));
        CFile::createDirectory($directoryPathSub3);
        CFile::createDirectory(CFilePath::add($directoryPathSub3, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub3, "dir-b10"));

        $paths = CFile::findDirectoriesRecursive(CFilePath::add($directoryPath, "*-b*"));
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 6 );
        $this->assertTrue(
            $paths->find("/dir-b2", $comparator) &&
            $paths->find("/dir-b10", $comparator) &&
            $paths->find("/dir-b2/dir-b2", $comparator) &&
            $paths->find("/dir-b2/dir-b10", $comparator) &&
            $paths->find("/dir-b10/dir-b2", $comparator) &&
            $paths->find("/dir-b10/dir-b10", $comparator) );

        $paths = CFile::findDirectoriesRecursive(CFilePath::add($directoryPath, "*-b*"), true);
        $this->assertTrue( $paths->length() == 6 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-b2") &&
            $paths[1]->endsWith("/dir-b10") &&
            $paths[2]->endsWith("/dir-b2/dir-b2") &&
            $paths[3]->endsWith("/dir-b2/dir-b10") &&
            $paths[4]->endsWith("/dir-b10/dir-b2") &&
            $paths[5]->endsWith("/dir-b10/dir-b10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindItems ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b10"));

        $paths = CFile::reFindItems($directoryPath, "/-b.*\\z/");
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 5 );
        $this->assertTrue(
            $paths->find("/file-b3", $comparator) &&
            $paths->find("/file-b20", $comparator) &&
            $paths->find("/file-b100", $comparator) &&
            $paths->find("/dir-b2", $comparator) &&
            $paths->find("/dir-b10", $comparator) );

        $paths = CFile::reFindItems($directoryPath, "/-b.*\\z/", true);
        $this->assertTrue( $paths->length() == 5 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-b2") &&
            $paths[1]->endsWith("/dir-b10") &&
            $paths[2]->endsWith("/file-b3") &&
            $paths[3]->endsWith("/file-b20") &&
            $paths[4]->endsWith("/file-b100") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindFiles ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b10"));

        $paths = CFile::reFindFiles($directoryPath, "/-b.*\\z/");
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 3 );
        $this->assertTrue(
            $paths->find("/file-b3", $comparator) &&
            $paths->find("/file-b20", $comparator) &&
            $paths->find("/file-b100", $comparator) );

        $paths = CFile::reFindFiles($directoryPath, "/-b.*\\z/", true);
        $this->assertTrue( $paths->length() == 3 );
        $this->assertTrue(
            $paths[0]->endsWith("/file-b3") &&
            $paths[1]->endsWith("/file-b20") &&
            $paths[2]->endsWith("/file-b100") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindDirectories ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b10"));

        $paths = CFile::reFindDirectories($directoryPath, "/-b.*\\z/");
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 2 );
        $this->assertTrue(
            $paths->find("/dir-b2", $comparator) &&
            $paths->find("/dir-b10", $comparator) );

        $paths = CFile::reFindDirectories($directoryPath, "/-b.*\\z/", true);
        $this->assertTrue( $paths->length() == 2 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-b2") &&
            $paths[1]->endsWith("/dir-b10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindItemsRecursive ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        $directoryPathSub0 = CFilePath::add($directoryPath, "dir-a2");
        $directoryPathSub1 = CFilePath::add($directoryPath, "dir-a10");
        $directoryPathSub2 = CFilePath::add($directoryPath, "dir-b2");
        $directoryPathSub3 = CFilePath::add($directoryPath, "dir-b10");
        CFile::createDirectory($directoryPathSub0);
        CFile::create(CFilePath::add($directoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub0, "file-a10"));
        CFile::createDirectory($directoryPathSub1);
        CFile::create(CFilePath::add($directoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub1, "file-a10"));
        CFile::createDirectory($directoryPathSub2);
        CFile::create(CFilePath::add($directoryPathSub2, "file-b2"));
        CFile::create(CFilePath::add($directoryPathSub2, "file-b10"));
        CFile::createDirectory($directoryPathSub3);
        CFile::create(CFilePath::add($directoryPathSub3, "file-b2"));
        CFile::create(CFilePath::add($directoryPathSub3, "file-b10"));

        $paths = CFile::reFindItemsRecursive($directoryPath, "/-b.*\\z/");
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 9 );
        $this->assertTrue(
            $paths->find("/file-b3", $comparator) &&
            $paths->find("/file-b20", $comparator) &&
            $paths->find("/file-b100", $comparator) &&
            $paths->find("/dir-b2", $comparator) &&
            $paths->find("/dir-b10", $comparator) &&
            $paths->find("/dir-b2/file-b2", $comparator) &&
            $paths->find("/dir-b2/file-b10", $comparator) &&
            $paths->find("/dir-b10/file-b2", $comparator) &&
            $paths->find("/dir-b10/file-b10", $comparator) );

        $paths = CFile::reFindItemsRecursive($directoryPath, "/-b.*\\z/", true);
        $this->assertTrue( $paths->length() == 9 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-b2") &&
            $paths[1]->endsWith("/dir-b10") &&
            $paths[2]->endsWith("/file-b3") &&
            $paths[3]->endsWith("/file-b20") &&
            $paths[4]->endsWith("/file-b100") &&
            $paths[5]->endsWith("/dir-b2/file-b2") &&
            $paths[6]->endsWith("/dir-b2/file-b10") &&
            $paths[7]->endsWith("/dir-b10/file-b2") &&
            $paths[8]->endsWith("/dir-b10/file-b10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindFilesRecursive ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        $directoryPathSub0 = CFilePath::add($directoryPath, "dir-a2");
        $directoryPathSub1 = CFilePath::add($directoryPath, "dir-a10");
        $directoryPathSub2 = CFilePath::add($directoryPath, "dir-b2");
        $directoryPathSub3 = CFilePath::add($directoryPath, "dir-b10");
        CFile::createDirectory($directoryPathSub0);
        CFile::create(CFilePath::add($directoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub0, "file-a10"));
        CFile::createDirectory($directoryPathSub1);
        CFile::create(CFilePath::add($directoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub1, "file-a10"));
        CFile::createDirectory($directoryPathSub2);
        CFile::create(CFilePath::add($directoryPathSub2, "file-b2"));
        CFile::create(CFilePath::add($directoryPathSub2, "file-b10"));
        CFile::createDirectory($directoryPathSub3);
        CFile::create(CFilePath::add($directoryPathSub3, "file-b2"));
        CFile::create(CFilePath::add($directoryPathSub3, "file-b10"));

        $paths = CFile::reFindFilesRecursive($directoryPath, "/-b.*\\z/");
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 7 );
        $this->assertTrue(
            $paths->find("/file-b3", $comparator) &&
            $paths->find("/file-b20", $comparator) &&
            $paths->find("/file-b100", $comparator) &&
            $paths->find("/dir-b2/file-b2", $comparator) &&
            $paths->find("/dir-b2/file-b10", $comparator) &&
            $paths->find("/dir-b10/file-b2", $comparator) &&
            $paths->find("/dir-b10/file-b10", $comparator) );

        $paths = CFile::reFindFilesRecursive($directoryPath, "/-b.*\\z/", true);
        $this->assertTrue( $paths->length() == 7 );
        $this->assertTrue(
            $paths[0]->endsWith("/file-b3") &&
            $paths[1]->endsWith("/file-b20") &&
            $paths[2]->endsWith("/file-b100") &&
            $paths[3]->endsWith("/dir-b2/file-b2") &&
            $paths[4]->endsWith("/dir-b2/file-b10") &&
            $paths[5]->endsWith("/dir-b10/file-b2") &&
            $paths[6]->endsWith("/dir-b10/file-b10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindDirectoriesRecursive ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        $directoryPathSub0 = CFilePath::add($directoryPath, "dir-a2");
        $directoryPathSub1 = CFilePath::add($directoryPath, "dir-a10");
        $directoryPathSub2 = CFilePath::add($directoryPath, "dir-b2");
        $directoryPathSub3 = CFilePath::add($directoryPath, "dir-b10");
        CFile::createDirectory($directoryPathSub0);
        CFile::createDirectory(CFilePath::add($directoryPathSub0, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub0, "dir-a10"));
        CFile::createDirectory($directoryPathSub1);
        CFile::createDirectory(CFilePath::add($directoryPathSub1, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub1, "dir-a10"));
        CFile::createDirectory($directoryPathSub2);
        CFile::createDirectory(CFilePath::add($directoryPathSub2, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub2, "dir-b10"));
        CFile::createDirectory($directoryPathSub3);
        CFile::createDirectory(CFilePath::add($directoryPathSub3, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub3, "dir-b10"));

        $paths = CFile::reFindDirectoriesRecursive($directoryPath, "/-b.*\\z/");
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 6 );
        $this->assertTrue(
            $paths->find("/dir-b2", $comparator) &&
            $paths->find("/dir-b10", $comparator) &&
            $paths->find("/dir-b2/dir-b2", $comparator) &&
            $paths->find("/dir-b2/dir-b10", $comparator) &&
            $paths->find("/dir-b10/dir-b2", $comparator) &&
            $paths->find("/dir-b10/dir-b10", $comparator) );

        $paths = CFile::reFindDirectoriesRecursive($directoryPath, "/-b.*\\z/", true);
        $this->assertTrue( $paths->length() == 6 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-b2") &&
            $paths[1]->endsWith("/dir-b10") &&
            $paths[2]->endsWith("/dir-b2/dir-b2") &&
            $paths[3]->endsWith("/dir-b2/dir-b10") &&
            $paths[4]->endsWith("/dir-b10/dir-b2") &&
            $paths[5]->endsWith("/dir-b10/dir-b10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindItemsOnName ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b10"));

        $paths = CFile::reFindItemsOnName($directoryPath, "/^.*-b/");
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 5 );
        $this->assertTrue(
            $paths->find("/file-b3", $comparator) &&
            $paths->find("/file-b20", $comparator) &&
            $paths->find("/file-b100", $comparator) &&
            $paths->find("/dir-b2", $comparator) &&
            $paths->find("/dir-b10", $comparator) );

        $paths = CFile::reFindItemsOnName($directoryPath, "/^.*-b/", true);
        $this->assertTrue( $paths->length() == 5 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-b2") &&
            $paths[1]->endsWith("/dir-b10") &&
            $paths[2]->endsWith("/file-b3") &&
            $paths[3]->endsWith("/file-b20") &&
            $paths[4]->endsWith("/file-b100") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindFilesOnName ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b10"));

        $paths = CFile::reFindFilesOnName($directoryPath, "/^.*-b/");
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 3 );
        $this->assertTrue(
            $paths->find("/file-b3", $comparator) &&
            $paths->find("/file-b20", $comparator) &&
            $paths->find("/file-b100", $comparator) );

        $paths = CFile::reFindFilesOnName($directoryPath, "/^.*-b/", true);
        $this->assertTrue( $paths->length() == 3 );
        $this->assertTrue(
            $paths[0]->endsWith("/file-b3") &&
            $paths[1]->endsWith("/file-b20") &&
            $paths[2]->endsWith("/file-b100") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindDirectoriesOnName ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPath, "dir-b10"));

        $paths = CFile::reFindDirectoriesOnName($directoryPath, "/^.*-b/");
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 2 );
        $this->assertTrue(
            $paths->find("/dir-b2", $comparator) &&
            $paths->find("/dir-b10", $comparator) );

        $paths = CFile::reFindDirectoriesOnName($directoryPath, "/^.*-b/", true);
        $this->assertTrue( $paths->length() == 2 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-b2") &&
            $paths[1]->endsWith("/dir-b10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindItemsOnNameRecursive ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        $directoryPathSub0 = CFilePath::add($directoryPath, "dir-a2");
        $directoryPathSub1 = CFilePath::add($directoryPath, "dir-a10");
        $directoryPathSub2 = CFilePath::add($directoryPath, "dir-b2");
        $directoryPathSub3 = CFilePath::add($directoryPath, "dir-b10");
        CFile::createDirectory($directoryPathSub0);
        CFile::create(CFilePath::add($directoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub0, "file-a10"));
        CFile::createDirectory($directoryPathSub1);
        CFile::create(CFilePath::add($directoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub1, "file-a10"));
        CFile::createDirectory($directoryPathSub2);
        CFile::create(CFilePath::add($directoryPathSub2, "file-b2"));
        CFile::create(CFilePath::add($directoryPathSub2, "file-b10"));
        CFile::createDirectory($directoryPathSub3);
        CFile::create(CFilePath::add($directoryPathSub3, "file-b2"));
        CFile::create(CFilePath::add($directoryPathSub3, "file-b10"));

        $paths = CFile::reFindItemsOnNameRecursive($directoryPath, "/^.*-b/");
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 9 );
        $this->assertTrue(
            $paths->find("/file-b3", $comparator) &&
            $paths->find("/file-b20", $comparator) &&
            $paths->find("/file-b100", $comparator) &&
            $paths->find("/dir-b2", $comparator) &&
            $paths->find("/dir-b10", $comparator) &&
            $paths->find("/dir-b2/file-b2", $comparator) &&
            $paths->find("/dir-b2/file-b10", $comparator) &&
            $paths->find("/dir-b10/file-b2", $comparator) &&
            $paths->find("/dir-b10/file-b10", $comparator) );

        $paths = CFile::reFindItemsOnNameRecursive($directoryPath, "/^.*-b/", true);
        $this->assertTrue( $paths->length() == 9 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-b2") &&
            $paths[1]->endsWith("/dir-b10") &&
            $paths[2]->endsWith("/file-b3") &&
            $paths[3]->endsWith("/file-b20") &&
            $paths[4]->endsWith("/file-b100") &&
            $paths[5]->endsWith("/dir-b2/file-b2") &&
            $paths[6]->endsWith("/dir-b2/file-b10") &&
            $paths[7]->endsWith("/dir-b10/file-b2") &&
            $paths[8]->endsWith("/dir-b10/file-b10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindFilesOnNameRecursive ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        $directoryPathSub0 = CFilePath::add($directoryPath, "dir-a2");
        $directoryPathSub1 = CFilePath::add($directoryPath, "dir-a10");
        $directoryPathSub2 = CFilePath::add($directoryPath, "dir-b2");
        $directoryPathSub3 = CFilePath::add($directoryPath, "dir-b10");
        CFile::createDirectory($directoryPathSub0);
        CFile::create(CFilePath::add($directoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub0, "file-a10"));
        CFile::createDirectory($directoryPathSub1);
        CFile::create(CFilePath::add($directoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($directoryPathSub1, "file-a10"));
        CFile::createDirectory($directoryPathSub2);
        CFile::create(CFilePath::add($directoryPathSub2, "file-b2"));
        CFile::create(CFilePath::add($directoryPathSub2, "file-b10"));
        CFile::createDirectory($directoryPathSub3);
        CFile::create(CFilePath::add($directoryPathSub3, "file-b2"));
        CFile::create(CFilePath::add($directoryPathSub3, "file-b10"));

        $paths = CFile::reFindFilesOnNameRecursive($directoryPath, "/^.*-b/");
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 7 );
        $this->assertTrue(
            $paths->find("/file-b3", $comparator) &&
            $paths->find("/file-b20", $comparator) &&
            $paths->find("/file-b100", $comparator) &&
            $paths->find("/dir-b2/file-b2", $comparator) &&
            $paths->find("/dir-b2/file-b10", $comparator) &&
            $paths->find("/dir-b10/file-b2", $comparator) &&
            $paths->find("/dir-b10/file-b10", $comparator) );

        $paths = CFile::reFindFilesOnNameRecursive($directoryPath, "/^.*-b/", true);
        $this->assertTrue( $paths->length() == 7 );
        $this->assertTrue(
            $paths[0]->endsWith("/file-b3") &&
            $paths[1]->endsWith("/file-b20") &&
            $paths[2]->endsWith("/file-b100") &&
            $paths[3]->endsWith("/dir-b2/file-b2") &&
            $paths[4]->endsWith("/dir-b2/file-b10") &&
            $paths[5]->endsWith("/dir-b10/file-b2") &&
            $paths[6]->endsWith("/dir-b10/file-b10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindDirectoriesOnNameRecursive ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        CFile::create(CFilePath::add($directoryPath, "file-a3"));
        CFile::create(CFilePath::add($directoryPath, "file-a20"));
        CFile::create(CFilePath::add($directoryPath, "file-a100"));
        CFile::create(CFilePath::add($directoryPath, "file-b3"));
        CFile::create(CFilePath::add($directoryPath, "file-b20"));
        CFile::create(CFilePath::add($directoryPath, "file-b100"));
        $directoryPathSub0 = CFilePath::add($directoryPath, "dir-a2");
        $directoryPathSub1 = CFilePath::add($directoryPath, "dir-a10");
        $directoryPathSub2 = CFilePath::add($directoryPath, "dir-b2");
        $directoryPathSub3 = CFilePath::add($directoryPath, "dir-b10");
        CFile::createDirectory($directoryPathSub0);
        CFile::createDirectory(CFilePath::add($directoryPathSub0, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub0, "dir-a10"));
        CFile::createDirectory($directoryPathSub1);
        CFile::createDirectory(CFilePath::add($directoryPathSub1, "dir-a2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub1, "dir-a10"));
        CFile::createDirectory($directoryPathSub2);
        CFile::createDirectory(CFilePath::add($directoryPathSub2, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub2, "dir-b10"));
        CFile::createDirectory($directoryPathSub3);
        CFile::createDirectory(CFilePath::add($directoryPathSub3, "dir-b2"));
        CFile::createDirectory(CFilePath::add($directoryPathSub3, "dir-b10"));

        $paths = CFile::reFindDirectoriesOnNameRecursive($directoryPath, "/^.*-b/");
        $comparator = function ($arrayString, $findString)
            {
                return $arrayString->endsWith($findString);
            };
        $this->assertTrue( $paths->length() == 6 );
        $this->assertTrue(
            $paths->find("/dir-b2", $comparator) &&
            $paths->find("/dir-b10", $comparator) &&
            $paths->find("/dir-b2/dir-b2", $comparator) &&
            $paths->find("/dir-b2/dir-b10", $comparator) &&
            $paths->find("/dir-b10/dir-b2", $comparator) &&
            $paths->find("/dir-b10/dir-b10", $comparator) );

        $paths = CFile::reFindDirectoriesOnNameRecursive($directoryPath, "/^.*-b/", true);
        $this->assertTrue( $paths->length() == 6 );
        $this->assertTrue(
            $paths[0]->endsWith("/dir-b2") &&
            $paths[1]->endsWith("/dir-b10") &&
            $paths[2]->endsWith("/dir-b2/dir-b2") &&
            $paths[3]->endsWith("/dir-b2/dir-b10") &&
            $paths[4]->endsWith("/dir-b10/dir-b2") &&
            $paths[5]->endsWith("/dir-b10/dir-b10") );

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $filePath = CFile::createTemporary();

        $file = new CFile($filePath, CFile::READ);
        $this->assertTrue(is_resource($file->systemResource()));
        $file->done();  // only needed in these tests

        $file = new CFile($filePath, CFile::READ_WRITE);
        $this->assertTrue(is_resource($file->systemResource()));
        $file->done();  // only needed in these tests

        $file = new CFile($filePath, CFile::WRITE_NEW);
        $this->assertTrue(is_resource($file->systemResource()));
        $file->done();  // only needed in these tests

        $file = new CFile($filePath, CFile::READ_WRITE_NEW);
        $this->assertTrue(is_resource($file->systemResource()));
        $file->done();  // only needed in these tests

        $file = new CFile($filePath, CFile::WRITE_APPEND);
        $this->assertTrue(is_resource($file->systemResource()));
        $file->done();  // only needed in these tests

        $file = new CFile($filePath, CFile::READ_WRITE_APPEND);
        $this->assertTrue(is_resource($file->systemResource()));
        $file->done();  // only needed in these tests

        $file = new CFile($filePath, CFile::READ, CFile::ZLIB);
        $this->assertTrue(is_resource($file->systemResource()));
        $file->done();  // only needed in these tests

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFilePath ()
    {
        $filePath = CFile::createTemporary();

        $file = new CFile($filePath, CFile::READ);
        $this->assertTrue(CFilePath::name($file->filePath())->startsWith(CFile::DEFAULT_TEMPORARY_FILE_PREFIX));
        $file->done();  // only needed in these tests

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMode ()
    {
        $filePath = CFile::createTemporary();

        $file = new CFile($filePath, CFile::READ);
        $this->assertTrue( $file->mode() == CFile::READ );
        $file->done();  // only needed in these tests

        $file = new CFile($filePath, CFile::WRITE_NEW);
        $this->assertTrue( $file->mode() == CFile::WRITE_NEW );
        $file->done();  // only needed in these tests

        $file = new CFile($filePath, CFile::WRITE_APPEND);
        $this->assertTrue( $file->mode() == CFile::WRITE_APPEND );
        $file->done();  // only needed in these tests

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSystemResource ()
    {
        $filePath = CFile::createTemporary();

        $file = new CFile($filePath, CFile::READ);
        $this->assertTrue(is_resource($file->systemResource()));
        $file->done();  // only needed in these tests

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        $filePath0 = CFile::createTemporary();
        $filePath1 = CFile::createTemporary();

        $file0 = new CFile($filePath0, CFile::READ);
        $file1 = new CFile($filePath0, CFile::READ);
        $this->assertTrue($file0->equals($file1));
        $file0->done();  // only needed in these tests
        $file1->done();  // only needed in these tests

        $file0 = new CFile($filePath0, CFile::READ);
        $file1 = new CFile($filePath1, CFile::READ);
        $this->assertFalse($file0->equals($file1));
        $file0->done();  // only needed in these tests
        $file1->done();  // only needed in these tests

        CFile::delete($filePath0);
        CFile::delete($filePath1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReadBytes ()
    {
        $filePath = CFile::createTemporary();

        CFile::write($filePath, "Hello there!");
        $file = new CFile($filePath, CFile::READ);
        $data = $file->readBytes(5);
        $this->assertTrue($data->equals("Hello"));
        $data = $file->readBytes(7);
        $this->assertTrue($data->equals(" there!"));
        $file->done();  // only needed in these tests

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReadAvailableBytes ()
    {
        $filePath = CFile::createTemporary();

        CFile::write($filePath, "Hello there!");
        $file = new CFile($filePath, CFile::READ);
        $data = $file->readAvailableBytes(5);
        $this->assertTrue($data->equals("Hello"));
        $data = $file->readAvailableBytes(50);
        $this->assertTrue($data->equals(" there!"));
        $file->done();  // only needed in these tests

        CFile::write($filePath, "Hello there!");
        $file = new CFile($filePath, CFile::READ);
        $data = $file->readAvailableBytes(100);
        $this->assertTrue($data->equals("Hello there!"));
        $file->done();  // only needed in these tests

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWriteBytes ()
    {
        $filePath = CFile::createTemporary();

        $file = new CFile($filePath, CFile::WRITE_NEW);
        $file->writeBytes("Hello");
        $file->writeBytes(" there!");
        $file->done();  // only needed in these tests
        $this->assertTrue(CFile::read($filePath)->equals("Hello there!"));

        $file = new CFile($filePath, CFile::WRITE_NEW);
        $file->writeBytes("Hello Hello", 5);
        $file->writeBytes(" there! there!", 7);
        $file->done();  // only needed in these tests
        $this->assertTrue(CFile::read($filePath)->equals("Hello there!"));

        // WRITE_APPEND
        $file = new CFile($filePath, CFile::WRITE_NEW);
        $file->writeBytes("Hello");
        $file->done();  // only needed in these tests
        $file = new CFile($filePath, CFile::WRITE_APPEND);
        $file->writeBytes(" there!");
        $file->done();  // only needed in these tests
        $this->assertTrue(CFile::read($filePath)->equals("Hello there!"));

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPos ()
    {
        $filePath = CFile::createTemporary();

        CFile::write($filePath, "Hello there!");
        $file = new CFile($filePath, CFile::READ);
        $this->assertTrue( $file->pos() == 0 );
        $file->readBytes(5);
        $this->assertTrue( $file->pos() == 5 );
        $file->readBytes(7);
        $this->assertTrue( $file->pos() == 12 );
        $file->done();  // only needed in these tests

        $file = new CFile($filePath, CFile::WRITE_NEW);
        $this->assertTrue( $file->pos() == 0 );
        $file->writeBytes("Hello");
        $this->assertTrue( $file->pos() == 5 );
        $file->writeBytes(" there!");
        $this->assertTrue( $file->pos() == 12 );
        $file->done();  // only needed in these tests

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetPos ()
    {
        $filePath = CFile::createTemporary();

        CFile::write($filePath, "Hello there!");
        $file = new CFile($filePath, CFile::READ);
        $file->setPos(2);
        $data = $file->readBytes(6);
        $this->assertTrue($data->equals("llo th"));
        $file->setPos(11);
        $data = $file->readBytes(1);
        $this->assertTrue($data->equals("!"));
        $file->done();  // only needed in these tests

        $file = new CFile($filePath, CFile::WRITE_NEW);
        $file->writeBytes("Hello");
        $file->setPos(3);
        $file->writeBytes("there!");
        $file->done();  // only needed in these tests
        $this->assertTrue(CFile::read($filePath)->equals("Helthere!"));

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftPos ()
    {
        $filePath = CFile::createTemporary();

        CFile::write($filePath, "Hello there!");
        $file = new CFile($filePath, CFile::READ);
        $file->shiftPos(2);
        $data = $file->readBytes(6);
        $this->assertTrue($data->equals("llo th"));
        $file->shiftPos(-2);
        $data = $file->readBytes(2);
        $this->assertTrue($data->equals("th"));
        $file->shiftPos(3);
        $data = $file->readBytes(1);
        $this->assertTrue($data->equals("!"));
        $file->done();  // only needed in these tests

        $file = new CFile($filePath, CFile::WRITE_NEW);
        $file->writeBytes("Hello");
        $file->shiftPos(-2);
        $file->writeBytes("there!");
        $file->done();  // only needed in these tests
        $this->assertTrue(CFile::read($filePath)->equals("Helthere!"));

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetPosToStart ()
    {
        $filePath = CFile::createTemporary();

        CFile::write($filePath, "Hello there!");
        $file = new CFile($filePath, CFile::READ);
        $data = $file->readBytes(5);
        $this->assertTrue($data->equals("Hello"));
        $file->setPosToStart();
        $data = $file->readBytes(2);
        $this->assertTrue($data->equals("He"));
        $file->done();  // only needed in these tests

        $file = new CFile($filePath, CFile::WRITE_NEW);
        $file->writeBytes("Hello");
        $file->setPosToStart();
        $file->writeBytes("th");
        $file->done();  // only needed in these tests
        $this->assertTrue(CFile::read($filePath)->equals("thllo"));

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetPosToEnd ()
    {
        $filePath = CFile::createTemporary();

        CFile::write($filePath, "Hello there!");
        $file = new CFile($filePath, CFile::READ);
        $file->setPosToEnd();
        $file->shiftPos(-6);
        $data = $file->readBytes(6);
        $this->assertTrue($data->equals("there!"));
        $file->done();  // only needed in these tests

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsPosPastEnd ()
    {
        $filePath = CFile::createTemporary();

        CFile::write($filePath, "Hello");
        $file = new CFile($filePath, CFile::READ);
        $file->readBytes(5);
        $this->assertFalse($file->isPosPastEnd());
        $file->done();  // only needed in these tests

        CFile::write($filePath, "Hello");
        $file = new CFile($filePath, CFile::READ);
        $file->setPosToEnd();
        $this->assertFalse($file->isPosPastEnd());
        $file->done();  // only needed in these tests

        CFile::write($filePath, "Hello");
        $file = new CFile($filePath, CFile::READ);
        $file->readBytes(5);
        $data = $file->readAvailableBytes(5);
        $this->assertTrue( $file->isPosPastEnd() && $data->equals("") );
        $file->done();  // only needed in these tests

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected static $ms_tempDirName = "test-dir-ivr27kwb";
}
