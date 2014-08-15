<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
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
        $sFilePath = CFile::createTemporary();

        $oFile = new CFile($sFilePath, CFile::WRITE_NEW);
        $oFile->writeBytes("Hello");
        $oFile->flush();
        $this->assertTrue(CFile::read($sFilePath)->equals("Hello"));
        $oFile->writeBytes(" there!");
        $oFile->flush();
        $this->assertTrue(CFile::read($sFilePath)->equals("Hello there!"));
        $oFile->done();  // only needed in these tests

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testExists ()
    {
        $sFilePath = CFile::createTemporary();

        $this->assertTrue(CFile::exists($sFilePath));
        $this->assertFalse(CFile::exists("/_nodir/_nofile"));

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSize ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::write($sFilePath, "Hello there!");
        $this->assertTrue( CFile::size($sFilePath) == 12 );

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRead ()
    {
        $sFilePath = CFile::createTemporary();

        file_put_contents($sFilePath, "Hello there!");
        $this->assertTrue(CFile::read($sFilePath)->equals("Hello there!"));

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWrite ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::write($sFilePath, "Hello there!");
        $this->assertTrue(u(file_get_contents($sFilePath))->equals("Hello there!"));

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAppend ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::append($sFilePath, "Hello there!");
        CFile::append($sFilePath, " You!");
        $this->assertTrue(CFile::read($sFilePath)->equals("Hello there! You!"));

        CFile::write($sFilePath, "ab");
        CFile::append($sFilePath, "cd");
        $this->assertTrue(CFile::read($sFilePath)->equals("abcd"));

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCreate ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::delete($sFilePath);
        CFile::create($sFilePath);
        $this->assertTrue(CFile::exists($sFilePath));

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCreateTemporary ()
    {
        $sFilePath = CFile::createTemporary();
        $this->assertTrue(CFile::exists($sFilePath));
        CFile::delete($sFilePath);

        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);
        $sFilePath = CFile::createTemporary($sDirectoryPath);
        $this->assertTrue(CFile::exists($sFilePath));
        CFile::delete($sFilePath);
        CFile::deleteEmptyDirectory($sDirectoryPath);

        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);
        $sFilePath = CFile::createTemporary($sDirectoryPath, "old-");
        $this->assertTrue( CFile::exists($sFilePath) && CFilePath::name($sFilePath)->startsWith("old-") );
        CFile::delete($sFilePath);
        CFile::deleteEmptyDirectory($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCreateDirectory ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);
        $this->assertTrue(CFile::exists($sDirectoryPath));
        CFile::deleteEmptyDirectory($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCopy ()
    {
        // A file.
        $sFilePath0 = CFile::createTemporary();
        $sFilePath1 = CFile::createTemporary();
        CFile::write($sFilePath0, "Hello there!");
        CFile::copy($sFilePath0, $sFilePath1);
        $this->assertTrue( CFile::exists($sFilePath0) && CFile::exists($sFilePath1) &&
            CFile::read($sFilePath1)->equals("Hello there!") );
        CFile::delete($sFilePath0);
        CFile::delete($sFilePath1);

        // A directory.
        $sDirectoryPath0 = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath0) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath0);
        }
        CFile::createDirectory($sDirectoryPath0);
        CFile::create(CFilePath::add($sDirectoryPath0, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath0, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath0, "file-a100"));
        $sDirectoryPath0Sub0 = CFilePath::add($sDirectoryPath0, "dir-a2");
        $sDirectoryPath0Sub1 = CFilePath::add($sDirectoryPath0, "dir-a10");
        CFile::createDirectory($sDirectoryPath0Sub0);
        CFile::create(CFilePath::add($sDirectoryPath0Sub0, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPath0Sub0, "file-a10"));
        CFile::createDirectory($sDirectoryPath0Sub1);
        CFile::create(CFilePath::add($sDirectoryPath0Sub1, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPath0Sub1, "file-a10"));
        $sDirectoryPath1 = "$sDirectoryPath0-copy";
        if ( CFile::exists($sDirectoryPath1) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath1);
        }
        CFile::copy($sDirectoryPath0, $sDirectoryPath1);
        $this->assertTrue(CFile::exists($sDirectoryPath1));
        $aPaths = CFile::listItemsRecursive($sDirectoryPath1);
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 9 );
        $this->assertTrue(
            $aPaths->find("/file-a3", $fnComparator) &&
            $aPaths->find("/file-a20", $fnComparator) &&
            $aPaths->find("/file-a100", $fnComparator) &&
            $aPaths->find("/dir-a2", $fnComparator) &&
            $aPaths->find("/dir-a10", $fnComparator) &&
            $aPaths->find("/dir-a2/file-a2", $fnComparator) &&
            $aPaths->find("/dir-a2/file-a10", $fnComparator) &&
            $aPaths->find("/dir-a10/file-a2", $fnComparator) &&
            $aPaths->find("/dir-a10/file-a10", $fnComparator) );
        CFile::deleteDirectoryRecursive($sDirectoryPath0);
        CFile::deleteDirectoryRecursive($sDirectoryPath1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMove ()
    {
        $sFilePath0 = CFile::createTemporary();
        $sFilePath1 = CFile::createTemporary();

        CFile::write($sFilePath0, "Hello there!");
        CFile::move($sFilePath0, $sFilePath1);
        $this->assertTrue( !CFile::exists($sFilePath0) && CFile::exists($sFilePath1) &&
            CFile::read($sFilePath1)->equals("Hello there!") );

        CFile::delete($sFilePath1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRename ()
    {
        $sFilePath0 = CFile::createTemporary();
        $sNewName = CFilePath::name($sFilePath0) . "-renamed";
        $sFilePath1 = CFilePath::add(CFilePath::directory($sFilePath0), $sNewName);
        CFile::write($sFilePath0, "Hello there!");
        CFile::rename($sFilePath0, $sNewName);
        $this->assertTrue( !CFile::exists($sFilePath0) && CFile::exists($sFilePath1) &&
            CFile::read($sFilePath1)->equals("Hello there!") );
        CFile::delete($sFilePath1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDelete ()
    {
        $sFilePath = CFile::createTemporary();
        CFile::delete($sFilePath);
        $this->assertFalse(CFile::exists($sFilePath));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDeleteEmptyDirectory ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);
        $this->assertTrue(CFile::exists($sDirectoryPath));
        CFile::deleteEmptyDirectory($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDeleteDirectoryRecursive ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::createTemporary($sDirectoryPath);
        $sDirectoryPathL2 = CFilePath::add($sDirectoryPath, "dir");
        CFile::createDirectory($sDirectoryPathL2);
        CFile::createTemporary($sDirectoryPathL2);
        CFile::deleteDirectoryRecursive($sDirectoryPath);
        $this->assertTrue( !CFile::exists($sDirectoryPathL2) && !CFile::exists($sDirectoryPath) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPermissions ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::setPermissions($sFilePath, "432");
        $this->assertTrue(CFile::permissions($sFilePath)->equals("432"));

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetPermissions ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::setPermissions($sFilePath, "432");
        $this->assertTrue(CFile::permissions($sFilePath)->equals("432"));

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testModificationTime ()
    {
        $sFilePath = CFile::createTemporary();

        $oModTime = CFile::modificationTime($sFilePath);
        $this->assertTrue( $oModTime->diffInSeconds(CTime::now()) <= 5 );

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsFile ()
    {
        $sFilePath = CFile::createTemporary();
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        $this->assertTrue( CFile::exists($sFilePath) && CFile::isFile($sFilePath) );
        $this->assertTrue( CFile::exists($sDirectoryPath) && !CFile::isFile($sDirectoryPath) );

        CFile::delete($sFilePath);
        CFile::deleteEmptyDirectory($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsDirectory ()
    {
        $sFilePath = CFile::createTemporary();
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        $this->assertTrue( CFile::exists($sFilePath) && !CFile::isDirectory($sFilePath) );
        $this->assertTrue( CFile::exists($sDirectoryPath) && CFile::isDirectory($sDirectoryPath) );

        CFile::delete($sFilePath);
        CFile::deleteEmptyDirectory($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testListItems ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a10"));

        $aPaths = CFile::listItems($sDirectoryPath);
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 5 );
        $this->assertTrue(
            $aPaths->find("/file-a3", $fnComparator) &&
            $aPaths->find("/file-a20", $fnComparator) &&
            $aPaths->find("/file-a100", $fnComparator) &&
            $aPaths->find("/dir-a2", $fnComparator) &&
            $aPaths->find("/dir-a10", $fnComparator) );

        $aPaths = CFile::listItems($sDirectoryPath, true);
        $this->assertTrue( $aPaths->length() == 5 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-a2") &&
            $aPaths[1]->endsWith("/dir-a10") &&
            $aPaths[2]->endsWith("/file-a3") &&
            $aPaths[3]->endsWith("/file-a20") &&
            $aPaths[4]->endsWith("/file-a100") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testListFiles ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a10"));

        $aPaths = CFile::listFiles($sDirectoryPath);
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 3 );
        $this->assertTrue(
            $aPaths->find("/file-a3", $fnComparator) &&
            $aPaths->find("/file-a20", $fnComparator) &&
            $aPaths->find("/file-a100", $fnComparator) );

        $aPaths = CFile::listFiles($sDirectoryPath, true);
        $this->assertTrue( $aPaths->length() == 3 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/file-a3") &&
            $aPaths[1]->endsWith("/file-a20") &&
            $aPaths[2]->endsWith("/file-a100") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testListDirectories ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a10"));

        $aPaths = CFile::listDirectories($sDirectoryPath);
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 2 );
        $this->assertTrue(
            $aPaths->find("/dir-a2", $fnComparator) &&
            $aPaths->find("/dir-a10", $fnComparator) );

        $aPaths = CFile::listDirectories($sDirectoryPath, true);
        $this->assertTrue( $aPaths->length() == 2 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-a2") &&
            $aPaths[1]->endsWith("/dir-a10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testListItemsRecursive ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        $sDirectoryPathSub0 = CFilePath::add($sDirectoryPath, "dir-a2");
        $sDirectoryPathSub1 = CFilePath::add($sDirectoryPath, "dir-a10");
        CFile::createDirectory($sDirectoryPathSub0);
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub1);
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a10"));

        $aPaths = CFile::listItemsRecursive($sDirectoryPath);
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 9 );
        $this->assertTrue(
            $aPaths->find("/file-a3", $fnComparator) &&
            $aPaths->find("/file-a20", $fnComparator) &&
            $aPaths->find("/file-a100", $fnComparator) &&
            $aPaths->find("/dir-a2", $fnComparator) &&
            $aPaths->find("/dir-a10", $fnComparator) &&
            $aPaths->find("/dir-a2/file-a2", $fnComparator) &&
            $aPaths->find("/dir-a2/file-a10", $fnComparator) &&
            $aPaths->find("/dir-a10/file-a2", $fnComparator) &&
            $aPaths->find("/dir-a10/file-a10", $fnComparator) );

        $aPaths = CFile::listItemsRecursive($sDirectoryPath, true);
        $this->assertTrue( $aPaths->length() == 9 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-a2") &&
            $aPaths[1]->endsWith("/dir-a10") &&
            $aPaths[2]->endsWith("/file-a3") &&
            $aPaths[3]->endsWith("/file-a20") &&
            $aPaths[4]->endsWith("/file-a100") &&
            $aPaths[5]->endsWith("/dir-a2/file-a2") &&
            $aPaths[6]->endsWith("/dir-a2/file-a10") &&
            $aPaths[7]->endsWith("/dir-a10/file-a2") &&
            $aPaths[8]->endsWith("/dir-a10/file-a10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testListFilesRecursive ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        $sDirectoryPathSub0 = CFilePath::add($sDirectoryPath, "dir-a2");
        $sDirectoryPathSub1 = CFilePath::add($sDirectoryPath, "dir-a10");
        CFile::createDirectory($sDirectoryPathSub0);
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub1);
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a10"));

        $aPaths = CFile::listFilesRecursive($sDirectoryPath);
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 7 );
        $this->assertTrue(
            $aPaths->find("/file-a3", $fnComparator) &&
            $aPaths->find("/file-a20", $fnComparator) &&
            $aPaths->find("/file-a100", $fnComparator) &&
            $aPaths->find("/dir-a2/file-a2", $fnComparator) &&
            $aPaths->find("/dir-a2/file-a10", $fnComparator) &&
            $aPaths->find("/dir-a10/file-a2", $fnComparator) &&
            $aPaths->find("/dir-a10/file-a10", $fnComparator) );

        $aPaths = CFile::listFilesRecursive($sDirectoryPath, true);
        $this->assertTrue( $aPaths->length() == 7 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/file-a3") &&
            $aPaths[1]->endsWith("/file-a20") &&
            $aPaths[2]->endsWith("/file-a100") &&
            $aPaths[3]->endsWith("/dir-a2/file-a2") &&
            $aPaths[4]->endsWith("/dir-a2/file-a10") &&
            $aPaths[5]->endsWith("/dir-a10/file-a2") &&
            $aPaths[6]->endsWith("/dir-a10/file-a10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testListDirectoriesRecursive ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        $sDirectoryPathSub0 = CFilePath::add($sDirectoryPath, "dir-a2");
        $sDirectoryPathSub1 = CFilePath::add($sDirectoryPath, "dir-a10");
        CFile::createDirectory($sDirectoryPathSub0);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub0, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub0, "dir-a10"));
        CFile::createDirectory($sDirectoryPathSub1);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub1, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub1, "dir-a10"));

        $aPaths = CFile::listDirectoriesRecursive($sDirectoryPath);
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 6 );
        $this->assertTrue(
            $aPaths->find("/dir-a2", $fnComparator) &&
            $aPaths->find("/dir-a10", $fnComparator) &&
            $aPaths->find("/dir-a2/dir-a2", $fnComparator) &&
            $aPaths->find("/dir-a2/dir-a10", $fnComparator) &&
            $aPaths->find("/dir-a10/dir-a2", $fnComparator) &&
            $aPaths->find("/dir-a10/dir-a10", $fnComparator) );

        $aPaths = CFile::listDirectoriesRecursive($sDirectoryPath, true);
        $this->assertTrue( $aPaths->length() == 6 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-a2") &&
            $aPaths[1]->endsWith("/dir-a10") &&
            $aPaths[2]->endsWith("/dir-a2/dir-a2") &&
            $aPaths[3]->endsWith("/dir-a2/dir-a10") &&
            $aPaths[4]->endsWith("/dir-a10/dir-a2") &&
            $aPaths[5]->endsWith("/dir-a10/dir-a10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindItems ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b10"));

        $aPaths = CFile::findItems(CFilePath::add($sDirectoryPath, "*-b*"));
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 5 );
        $this->assertTrue(
            $aPaths->find("/file-b3", $fnComparator) &&
            $aPaths->find("/file-b20", $fnComparator) &&
            $aPaths->find("/file-b100", $fnComparator) &&
            $aPaths->find("/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10", $fnComparator) );

        $aPaths = CFile::findItems(CFilePath::add($sDirectoryPath, "*-b*"), true);
        $this->assertTrue( $aPaths->length() == 5 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-b2") &&
            $aPaths[1]->endsWith("/dir-b10") &&
            $aPaths[2]->endsWith("/file-b3") &&
            $aPaths[3]->endsWith("/file-b20") &&
            $aPaths[4]->endsWith("/file-b100") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindFiles ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b10"));

        $aPaths = CFile::findFiles(CFilePath::add($sDirectoryPath, "*-b*"));
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 3 );
        $this->assertTrue(
            $aPaths->find("/file-b3", $fnComparator) &&
            $aPaths->find("/file-b20", $fnComparator) &&
            $aPaths->find("/file-b100", $fnComparator) );

        $aPaths = CFile::findFiles(CFilePath::add($sDirectoryPath, "*-b*"), true);
        $this->assertTrue( $aPaths->length() == 3 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/file-b3") &&
            $aPaths[1]->endsWith("/file-b20") &&
            $aPaths[2]->endsWith("/file-b100") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindDirectories ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b10"));

        $aPaths = CFile::findDirectories(CFilePath::add($sDirectoryPath, "*-b*"));
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 2 );
        $this->assertTrue(
            $aPaths->find("/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10", $fnComparator) );

        $aPaths = CFile::findDirectories(CFilePath::add($sDirectoryPath, "*-b*"), true);
        $this->assertTrue( $aPaths->length() == 2 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-b2") &&
            $aPaths[1]->endsWith("/dir-b10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindItemsRecursive ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        $sDirectoryPathSub0 = CFilePath::add($sDirectoryPath, "dir-a2");
        $sDirectoryPathSub1 = CFilePath::add($sDirectoryPath, "dir-a10");
        $sDirectoryPathSub2 = CFilePath::add($sDirectoryPath, "dir-b2");
        $sDirectoryPathSub3 = CFilePath::add($sDirectoryPath, "dir-b10");
        CFile::createDirectory($sDirectoryPathSub0);
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub1);
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub2);
        CFile::create(CFilePath::add($sDirectoryPathSub2, "file-b2"));
        CFile::create(CFilePath::add($sDirectoryPathSub2, "file-b10"));
        CFile::createDirectory($sDirectoryPathSub3);
        CFile::create(CFilePath::add($sDirectoryPathSub3, "file-b2"));
        CFile::create(CFilePath::add($sDirectoryPathSub3, "file-b10"));

        $aPaths = CFile::findItemsRecursive(CFilePath::add($sDirectoryPath, "*-b*"));
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 9 );
        $this->assertTrue(
            $aPaths->find("/file-b3", $fnComparator) &&
            $aPaths->find("/file-b20", $fnComparator) &&
            $aPaths->find("/file-b100", $fnComparator) &&
            $aPaths->find("/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10", $fnComparator) &&
            $aPaths->find("/dir-b2/file-b2", $fnComparator) &&
            $aPaths->find("/dir-b2/file-b10", $fnComparator) &&
            $aPaths->find("/dir-b10/file-b2", $fnComparator) &&
            $aPaths->find("/dir-b10/file-b10", $fnComparator) );

        $aPaths = CFile::findItemsRecursive(CFilePath::add($sDirectoryPath, "*-b*"), true);
        $this->assertTrue( $aPaths->length() == 9 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-b2") &&
            $aPaths[1]->endsWith("/dir-b10") &&
            $aPaths[2]->endsWith("/file-b3") &&
            $aPaths[3]->endsWith("/file-b20") &&
            $aPaths[4]->endsWith("/file-b100") &&
            $aPaths[5]->endsWith("/dir-b2/file-b2") &&
            $aPaths[6]->endsWith("/dir-b2/file-b10") &&
            $aPaths[7]->endsWith("/dir-b10/file-b2") &&
            $aPaths[8]->endsWith("/dir-b10/file-b10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindFilesRecursive ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        $sDirectoryPathSub0 = CFilePath::add($sDirectoryPath, "dir-a2");
        $sDirectoryPathSub1 = CFilePath::add($sDirectoryPath, "dir-a10");
        $sDirectoryPathSub2 = CFilePath::add($sDirectoryPath, "dir-b2");
        $sDirectoryPathSub3 = CFilePath::add($sDirectoryPath, "dir-b10");
        CFile::createDirectory($sDirectoryPathSub0);
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub1);
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub2);
        CFile::create(CFilePath::add($sDirectoryPathSub2, "file-b2"));
        CFile::create(CFilePath::add($sDirectoryPathSub2, "file-b10"));
        CFile::createDirectory($sDirectoryPathSub3);
        CFile::create(CFilePath::add($sDirectoryPathSub3, "file-b2"));
        CFile::create(CFilePath::add($sDirectoryPathSub3, "file-b10"));

        $aPaths = CFile::findFilesRecursive(CFilePath::add($sDirectoryPath, "*-b*"));
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 7 );
        $this->assertTrue(
            $aPaths->find("/file-b3", $fnComparator) &&
            $aPaths->find("/file-b20", $fnComparator) &&
            $aPaths->find("/file-b100", $fnComparator) &&
            $aPaths->find("/dir-b2/file-b2", $fnComparator) &&
            $aPaths->find("/dir-b2/file-b10", $fnComparator) &&
            $aPaths->find("/dir-b10/file-b2", $fnComparator) &&
            $aPaths->find("/dir-b10/file-b10", $fnComparator) );

        $aPaths = CFile::findFilesRecursive(CFilePath::add($sDirectoryPath, "*-b*"), true);
        $this->assertTrue( $aPaths->length() == 7 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/file-b3") &&
            $aPaths[1]->endsWith("/file-b20") &&
            $aPaths[2]->endsWith("/file-b100") &&
            $aPaths[3]->endsWith("/dir-b2/file-b2") &&
            $aPaths[4]->endsWith("/dir-b2/file-b10") &&
            $aPaths[5]->endsWith("/dir-b10/file-b2") &&
            $aPaths[6]->endsWith("/dir-b10/file-b10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindDirectoriesRecursive ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        $sDirectoryPathSub0 = CFilePath::add($sDirectoryPath, "dir-a2");
        $sDirectoryPathSub1 = CFilePath::add($sDirectoryPath, "dir-a10");
        $sDirectoryPathSub2 = CFilePath::add($sDirectoryPath, "dir-b2");
        $sDirectoryPathSub3 = CFilePath::add($sDirectoryPath, "dir-b10");
        CFile::createDirectory($sDirectoryPathSub0);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub0, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub0, "dir-a10"));
        CFile::createDirectory($sDirectoryPathSub1);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub1, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub1, "dir-a10"));
        CFile::createDirectory($sDirectoryPathSub2);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub2, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub2, "dir-b10"));
        CFile::createDirectory($sDirectoryPathSub3);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub3, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub3, "dir-b10"));

        $aPaths = CFile::findDirectoriesRecursive(CFilePath::add($sDirectoryPath, "*-b*"));
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 6 );
        $this->assertTrue(
            $aPaths->find("/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10", $fnComparator) &&
            $aPaths->find("/dir-b2/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b2/dir-b10", $fnComparator) &&
            $aPaths->find("/dir-b10/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10/dir-b10", $fnComparator) );

        $aPaths = CFile::findDirectoriesRecursive(CFilePath::add($sDirectoryPath, "*-b*"), true);
        $this->assertTrue( $aPaths->length() == 6 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-b2") &&
            $aPaths[1]->endsWith("/dir-b10") &&
            $aPaths[2]->endsWith("/dir-b2/dir-b2") &&
            $aPaths[3]->endsWith("/dir-b2/dir-b10") &&
            $aPaths[4]->endsWith("/dir-b10/dir-b2") &&
            $aPaths[5]->endsWith("/dir-b10/dir-b10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindItems ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b10"));

        $aPaths = CFile::reFindItems($sDirectoryPath, "/-b.*\\z/");
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 5 );
        $this->assertTrue(
            $aPaths->find("/file-b3", $fnComparator) &&
            $aPaths->find("/file-b20", $fnComparator) &&
            $aPaths->find("/file-b100", $fnComparator) &&
            $aPaths->find("/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10", $fnComparator) );

        $aPaths = CFile::reFindItems($sDirectoryPath, "/-b.*\\z/", true);
        $this->assertTrue( $aPaths->length() == 5 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-b2") &&
            $aPaths[1]->endsWith("/dir-b10") &&
            $aPaths[2]->endsWith("/file-b3") &&
            $aPaths[3]->endsWith("/file-b20") &&
            $aPaths[4]->endsWith("/file-b100") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindFiles ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b10"));

        $aPaths = CFile::reFindFiles($sDirectoryPath, "/-b.*\\z/");
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 3 );
        $this->assertTrue(
            $aPaths->find("/file-b3", $fnComparator) &&
            $aPaths->find("/file-b20", $fnComparator) &&
            $aPaths->find("/file-b100", $fnComparator) );

        $aPaths = CFile::reFindFiles($sDirectoryPath, "/-b.*\\z/", true);
        $this->assertTrue( $aPaths->length() == 3 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/file-b3") &&
            $aPaths[1]->endsWith("/file-b20") &&
            $aPaths[2]->endsWith("/file-b100") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindDirectories ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b10"));

        $aPaths = CFile::reFindDirectories($sDirectoryPath, "/-b.*\\z/");
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 2 );
        $this->assertTrue(
            $aPaths->find("/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10", $fnComparator) );

        $aPaths = CFile::reFindDirectories($sDirectoryPath, "/-b.*\\z/", true);
        $this->assertTrue( $aPaths->length() == 2 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-b2") &&
            $aPaths[1]->endsWith("/dir-b10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindItemsRecursive ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        $sDirectoryPathSub0 = CFilePath::add($sDirectoryPath, "dir-a2");
        $sDirectoryPathSub1 = CFilePath::add($sDirectoryPath, "dir-a10");
        $sDirectoryPathSub2 = CFilePath::add($sDirectoryPath, "dir-b2");
        $sDirectoryPathSub3 = CFilePath::add($sDirectoryPath, "dir-b10");
        CFile::createDirectory($sDirectoryPathSub0);
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub1);
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub2);
        CFile::create(CFilePath::add($sDirectoryPathSub2, "file-b2"));
        CFile::create(CFilePath::add($sDirectoryPathSub2, "file-b10"));
        CFile::createDirectory($sDirectoryPathSub3);
        CFile::create(CFilePath::add($sDirectoryPathSub3, "file-b2"));
        CFile::create(CFilePath::add($sDirectoryPathSub3, "file-b10"));

        $aPaths = CFile::reFindItemsRecursive($sDirectoryPath, "/-b.*\\z/");
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 9 );
        $this->assertTrue(
            $aPaths->find("/file-b3", $fnComparator) &&
            $aPaths->find("/file-b20", $fnComparator) &&
            $aPaths->find("/file-b100", $fnComparator) &&
            $aPaths->find("/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10", $fnComparator) &&
            $aPaths->find("/dir-b2/file-b2", $fnComparator) &&
            $aPaths->find("/dir-b2/file-b10", $fnComparator) &&
            $aPaths->find("/dir-b10/file-b2", $fnComparator) &&
            $aPaths->find("/dir-b10/file-b10", $fnComparator) );

        $aPaths = CFile::reFindItemsRecursive($sDirectoryPath, "/-b.*\\z/", true);
        $this->assertTrue( $aPaths->length() == 9 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-b2") &&
            $aPaths[1]->endsWith("/dir-b10") &&
            $aPaths[2]->endsWith("/file-b3") &&
            $aPaths[3]->endsWith("/file-b20") &&
            $aPaths[4]->endsWith("/file-b100") &&
            $aPaths[5]->endsWith("/dir-b2/file-b2") &&
            $aPaths[6]->endsWith("/dir-b2/file-b10") &&
            $aPaths[7]->endsWith("/dir-b10/file-b2") &&
            $aPaths[8]->endsWith("/dir-b10/file-b10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindFilesRecursive ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        $sDirectoryPathSub0 = CFilePath::add($sDirectoryPath, "dir-a2");
        $sDirectoryPathSub1 = CFilePath::add($sDirectoryPath, "dir-a10");
        $sDirectoryPathSub2 = CFilePath::add($sDirectoryPath, "dir-b2");
        $sDirectoryPathSub3 = CFilePath::add($sDirectoryPath, "dir-b10");
        CFile::createDirectory($sDirectoryPathSub0);
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub1);
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub2);
        CFile::create(CFilePath::add($sDirectoryPathSub2, "file-b2"));
        CFile::create(CFilePath::add($sDirectoryPathSub2, "file-b10"));
        CFile::createDirectory($sDirectoryPathSub3);
        CFile::create(CFilePath::add($sDirectoryPathSub3, "file-b2"));
        CFile::create(CFilePath::add($sDirectoryPathSub3, "file-b10"));

        $aPaths = CFile::reFindFilesRecursive($sDirectoryPath, "/-b.*\\z/");
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 7 );
        $this->assertTrue(
            $aPaths->find("/file-b3", $fnComparator) &&
            $aPaths->find("/file-b20", $fnComparator) &&
            $aPaths->find("/file-b100", $fnComparator) &&
            $aPaths->find("/dir-b2/file-b2", $fnComparator) &&
            $aPaths->find("/dir-b2/file-b10", $fnComparator) &&
            $aPaths->find("/dir-b10/file-b2", $fnComparator) &&
            $aPaths->find("/dir-b10/file-b10", $fnComparator) );

        $aPaths = CFile::reFindFilesRecursive($sDirectoryPath, "/-b.*\\z/", true);
        $this->assertTrue( $aPaths->length() == 7 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/file-b3") &&
            $aPaths[1]->endsWith("/file-b20") &&
            $aPaths[2]->endsWith("/file-b100") &&
            $aPaths[3]->endsWith("/dir-b2/file-b2") &&
            $aPaths[4]->endsWith("/dir-b2/file-b10") &&
            $aPaths[5]->endsWith("/dir-b10/file-b2") &&
            $aPaths[6]->endsWith("/dir-b10/file-b10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindDirectoriesRecursive ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        $sDirectoryPathSub0 = CFilePath::add($sDirectoryPath, "dir-a2");
        $sDirectoryPathSub1 = CFilePath::add($sDirectoryPath, "dir-a10");
        $sDirectoryPathSub2 = CFilePath::add($sDirectoryPath, "dir-b2");
        $sDirectoryPathSub3 = CFilePath::add($sDirectoryPath, "dir-b10");
        CFile::createDirectory($sDirectoryPathSub0);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub0, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub0, "dir-a10"));
        CFile::createDirectory($sDirectoryPathSub1);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub1, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub1, "dir-a10"));
        CFile::createDirectory($sDirectoryPathSub2);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub2, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub2, "dir-b10"));
        CFile::createDirectory($sDirectoryPathSub3);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub3, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub3, "dir-b10"));

        $aPaths = CFile::reFindDirectoriesRecursive($sDirectoryPath, "/-b.*\\z/");
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 6 );
        $this->assertTrue(
            $aPaths->find("/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10", $fnComparator) &&
            $aPaths->find("/dir-b2/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b2/dir-b10", $fnComparator) &&
            $aPaths->find("/dir-b10/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10/dir-b10", $fnComparator) );

        $aPaths = CFile::reFindDirectoriesRecursive($sDirectoryPath, "/-b.*\\z/", true);
        $this->assertTrue( $aPaths->length() == 6 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-b2") &&
            $aPaths[1]->endsWith("/dir-b10") &&
            $aPaths[2]->endsWith("/dir-b2/dir-b2") &&
            $aPaths[3]->endsWith("/dir-b2/dir-b10") &&
            $aPaths[4]->endsWith("/dir-b10/dir-b2") &&
            $aPaths[5]->endsWith("/dir-b10/dir-b10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindItemsOnName ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b10"));

        $aPaths = CFile::reFindItemsOnName($sDirectoryPath, "/^.*-b/");
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 5 );
        $this->assertTrue(
            $aPaths->find("/file-b3", $fnComparator) &&
            $aPaths->find("/file-b20", $fnComparator) &&
            $aPaths->find("/file-b100", $fnComparator) &&
            $aPaths->find("/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10", $fnComparator) );

        $aPaths = CFile::reFindItemsOnName($sDirectoryPath, "/^.*-b/", true);
        $this->assertTrue( $aPaths->length() == 5 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-b2") &&
            $aPaths[1]->endsWith("/dir-b10") &&
            $aPaths[2]->endsWith("/file-b3") &&
            $aPaths[3]->endsWith("/file-b20") &&
            $aPaths[4]->endsWith("/file-b100") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindFilesOnName ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b10"));

        $aPaths = CFile::reFindFilesOnName($sDirectoryPath, "/^.*-b/");
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 3 );
        $this->assertTrue(
            $aPaths->find("/file-b3", $fnComparator) &&
            $aPaths->find("/file-b20", $fnComparator) &&
            $aPaths->find("/file-b100", $fnComparator) );

        $aPaths = CFile::reFindFilesOnName($sDirectoryPath, "/^.*-b/", true);
        $this->assertTrue( $aPaths->length() == 3 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/file-b3") &&
            $aPaths[1]->endsWith("/file-b20") &&
            $aPaths[2]->endsWith("/file-b100") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindDirectoriesOnName ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-a10"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPath, "dir-b10"));

        $aPaths = CFile::reFindDirectoriesOnName($sDirectoryPath, "/^.*-b/");
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 2 );
        $this->assertTrue(
            $aPaths->find("/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10", $fnComparator) );

        $aPaths = CFile::reFindDirectoriesOnName($sDirectoryPath, "/^.*-b/", true);
        $this->assertTrue( $aPaths->length() == 2 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-b2") &&
            $aPaths[1]->endsWith("/dir-b10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindItemsOnNameRecursive ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        $sDirectoryPathSub0 = CFilePath::add($sDirectoryPath, "dir-a2");
        $sDirectoryPathSub1 = CFilePath::add($sDirectoryPath, "dir-a10");
        $sDirectoryPathSub2 = CFilePath::add($sDirectoryPath, "dir-b2");
        $sDirectoryPathSub3 = CFilePath::add($sDirectoryPath, "dir-b10");
        CFile::createDirectory($sDirectoryPathSub0);
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub1);
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub2);
        CFile::create(CFilePath::add($sDirectoryPathSub2, "file-b2"));
        CFile::create(CFilePath::add($sDirectoryPathSub2, "file-b10"));
        CFile::createDirectory($sDirectoryPathSub3);
        CFile::create(CFilePath::add($sDirectoryPathSub3, "file-b2"));
        CFile::create(CFilePath::add($sDirectoryPathSub3, "file-b10"));

        $aPaths = CFile::reFindItemsOnNameRecursive($sDirectoryPath, "/^.*-b/");
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 9 );
        $this->assertTrue(
            $aPaths->find("/file-b3", $fnComparator) &&
            $aPaths->find("/file-b20", $fnComparator) &&
            $aPaths->find("/file-b100", $fnComparator) &&
            $aPaths->find("/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10", $fnComparator) &&
            $aPaths->find("/dir-b2/file-b2", $fnComparator) &&
            $aPaths->find("/dir-b2/file-b10", $fnComparator) &&
            $aPaths->find("/dir-b10/file-b2", $fnComparator) &&
            $aPaths->find("/dir-b10/file-b10", $fnComparator) );

        $aPaths = CFile::reFindItemsOnNameRecursive($sDirectoryPath, "/^.*-b/", true);
        $this->assertTrue( $aPaths->length() == 9 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-b2") &&
            $aPaths[1]->endsWith("/dir-b10") &&
            $aPaths[2]->endsWith("/file-b3") &&
            $aPaths[3]->endsWith("/file-b20") &&
            $aPaths[4]->endsWith("/file-b100") &&
            $aPaths[5]->endsWith("/dir-b2/file-b2") &&
            $aPaths[6]->endsWith("/dir-b2/file-b10") &&
            $aPaths[7]->endsWith("/dir-b10/file-b2") &&
            $aPaths[8]->endsWith("/dir-b10/file-b10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindFilesOnNameRecursive ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        $sDirectoryPathSub0 = CFilePath::add($sDirectoryPath, "dir-a2");
        $sDirectoryPathSub1 = CFilePath::add($sDirectoryPath, "dir-a10");
        $sDirectoryPathSub2 = CFilePath::add($sDirectoryPath, "dir-b2");
        $sDirectoryPathSub3 = CFilePath::add($sDirectoryPath, "dir-b10");
        CFile::createDirectory($sDirectoryPathSub0);
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub0, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub1);
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a2"));
        CFile::create(CFilePath::add($sDirectoryPathSub1, "file-a10"));
        CFile::createDirectory($sDirectoryPathSub2);
        CFile::create(CFilePath::add($sDirectoryPathSub2, "file-b2"));
        CFile::create(CFilePath::add($sDirectoryPathSub2, "file-b10"));
        CFile::createDirectory($sDirectoryPathSub3);
        CFile::create(CFilePath::add($sDirectoryPathSub3, "file-b2"));
        CFile::create(CFilePath::add($sDirectoryPathSub3, "file-b10"));

        $aPaths = CFile::reFindFilesOnNameRecursive($sDirectoryPath, "/^.*-b/");
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 7 );
        $this->assertTrue(
            $aPaths->find("/file-b3", $fnComparator) &&
            $aPaths->find("/file-b20", $fnComparator) &&
            $aPaths->find("/file-b100", $fnComparator) &&
            $aPaths->find("/dir-b2/file-b2", $fnComparator) &&
            $aPaths->find("/dir-b2/file-b10", $fnComparator) &&
            $aPaths->find("/dir-b10/file-b2", $fnComparator) &&
            $aPaths->find("/dir-b10/file-b10", $fnComparator) );

        $aPaths = CFile::reFindFilesOnNameRecursive($sDirectoryPath, "/^.*-b/", true);
        $this->assertTrue( $aPaths->length() == 7 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/file-b3") &&
            $aPaths[1]->endsWith("/file-b20") &&
            $aPaths[2]->endsWith("/file-b100") &&
            $aPaths[3]->endsWith("/dir-b2/file-b2") &&
            $aPaths[4]->endsWith("/dir-b2/file-b10") &&
            $aPaths[5]->endsWith("/dir-b10/file-b2") &&
            $aPaths[6]->endsWith("/dir-b10/file-b10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindDirectoriesOnNameRecursive ()
    {
        $sDirectoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_sTempDirName);
        if ( CFile::exists($sDirectoryPath) )
        {
            CFile::deleteDirectoryRecursive($sDirectoryPath);
        }
        CFile::createDirectory($sDirectoryPath);

        CFile::create(CFilePath::add($sDirectoryPath, "file-a3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-a100"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b3"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b20"));
        CFile::create(CFilePath::add($sDirectoryPath, "file-b100"));
        $sDirectoryPathSub0 = CFilePath::add($sDirectoryPath, "dir-a2");
        $sDirectoryPathSub1 = CFilePath::add($sDirectoryPath, "dir-a10");
        $sDirectoryPathSub2 = CFilePath::add($sDirectoryPath, "dir-b2");
        $sDirectoryPathSub3 = CFilePath::add($sDirectoryPath, "dir-b10");
        CFile::createDirectory($sDirectoryPathSub0);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub0, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub0, "dir-a10"));
        CFile::createDirectory($sDirectoryPathSub1);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub1, "dir-a2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub1, "dir-a10"));
        CFile::createDirectory($sDirectoryPathSub2);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub2, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub2, "dir-b10"));
        CFile::createDirectory($sDirectoryPathSub3);
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub3, "dir-b2"));
        CFile::createDirectory(CFilePath::add($sDirectoryPathSub3, "dir-b10"));

        $aPaths = CFile::reFindDirectoriesOnNameRecursive($sDirectoryPath, "/^.*-b/");
        $fnComparator = function ($sArrayString, $sFindString)
            {
                return $sArrayString->endsWith($sFindString);
            };
        $this->assertTrue( $aPaths->length() == 6 );
        $this->assertTrue(
            $aPaths->find("/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10", $fnComparator) &&
            $aPaths->find("/dir-b2/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b2/dir-b10", $fnComparator) &&
            $aPaths->find("/dir-b10/dir-b2", $fnComparator) &&
            $aPaths->find("/dir-b10/dir-b10", $fnComparator) );

        $aPaths = CFile::reFindDirectoriesOnNameRecursive($sDirectoryPath, "/^.*-b/", true);
        $this->assertTrue( $aPaths->length() == 6 );
        $this->assertTrue(
            $aPaths[0]->endsWith("/dir-b2") &&
            $aPaths[1]->endsWith("/dir-b10") &&
            $aPaths[2]->endsWith("/dir-b2/dir-b2") &&
            $aPaths[3]->endsWith("/dir-b2/dir-b10") &&
            $aPaths[4]->endsWith("/dir-b10/dir-b2") &&
            $aPaths[5]->endsWith("/dir-b10/dir-b10") );

        CFile::deleteDirectoryRecursive($sDirectoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $sFilePath = CFile::createTemporary();

        $oFile = new CFile($sFilePath, CFile::READ);
        $this->assertTrue(is_resource($oFile->systemResource()));
        $oFile->done();  // only needed in these tests

        $oFile = new CFile($sFilePath, CFile::READ_WRITE);
        $this->assertTrue(is_resource($oFile->systemResource()));
        $oFile->done();  // only needed in these tests

        $oFile = new CFile($sFilePath, CFile::WRITE_NEW);
        $this->assertTrue(is_resource($oFile->systemResource()));
        $oFile->done();  // only needed in these tests

        $oFile = new CFile($sFilePath, CFile::READ_WRITE_NEW);
        $this->assertTrue(is_resource($oFile->systemResource()));
        $oFile->done();  // only needed in these tests

        $oFile = new CFile($sFilePath, CFile::WRITE_APPEND);
        $this->assertTrue(is_resource($oFile->systemResource()));
        $oFile->done();  // only needed in these tests

        $oFile = new CFile($sFilePath, CFile::READ_WRITE_APPEND);
        $this->assertTrue(is_resource($oFile->systemResource()));
        $oFile->done();  // only needed in these tests

        $oFile = new CFile($sFilePath, CFile::READ, CFile::ZLIB);
        $this->assertTrue(is_resource($oFile->systemResource()));
        $oFile->done();  // only needed in these tests

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFilePath ()
    {
        $sFilePath = CFile::createTemporary();

        $oFile = new CFile($sFilePath, CFile::READ);
        $this->assertTrue(CFilePath::name($oFile->filePath())->startsWith(CFile::DEFAULT_TEMPORARY_FILE_PREFIX));
        $oFile->done();  // only needed in these tests

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMode ()
    {
        $sFilePath = CFile::createTemporary();

        $oFile = new CFile($sFilePath, CFile::READ);
        $this->assertTrue( $oFile->mode() == CFile::READ );
        $oFile->done();  // only needed in these tests

        $oFile = new CFile($sFilePath, CFile::WRITE_NEW);
        $this->assertTrue( $oFile->mode() == CFile::WRITE_NEW );
        $oFile->done();  // only needed in these tests

        $oFile = new CFile($sFilePath, CFile::WRITE_APPEND);
        $this->assertTrue( $oFile->mode() == CFile::WRITE_APPEND );
        $oFile->done();  // only needed in these tests

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSystemResource ()
    {
        $sFilePath = CFile::createTemporary();

        $oFile = new CFile($sFilePath, CFile::READ);
        $this->assertTrue(is_resource($oFile->systemResource()));
        $oFile->done();  // only needed in these tests

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        $sFilePath0 = CFile::createTemporary();
        $sFilePath1 = CFile::createTemporary();

        $oFile0 = new CFile($sFilePath0, CFile::READ);
        $oFile1 = new CFile($sFilePath0, CFile::READ);
        $this->assertTrue($oFile0->equals($oFile1));
        $oFile0->done();  // only needed in these tests
        $oFile1->done();  // only needed in these tests

        $oFile0 = new CFile($sFilePath0, CFile::READ);
        $oFile1 = new CFile($sFilePath1, CFile::READ);
        $this->assertFalse($oFile0->equals($oFile1));
        $oFile0->done();  // only needed in these tests
        $oFile1->done();  // only needed in these tests

        CFile::delete($sFilePath0);
        CFile::delete($sFilePath1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReadBytes ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::write($sFilePath, "Hello there!");
        $oFile = new CFile($sFilePath, CFile::READ);
        $byData = $oFile->readBytes(5);
        $this->assertTrue($byData->equals("Hello"));
        $byData = $oFile->readBytes(7);
        $this->assertTrue($byData->equals(" there!"));
        $oFile->done();  // only needed in these tests

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReadAvailableBytes ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::write($sFilePath, "Hello there!");
        $oFile = new CFile($sFilePath, CFile::READ);
        $byData = $oFile->readAvailableBytes(5);
        $this->assertTrue($byData->equals("Hello"));
        $byData = $oFile->readAvailableBytes(50);
        $this->assertTrue($byData->equals(" there!"));
        $oFile->done();  // only needed in these tests

        CFile::write($sFilePath, "Hello there!");
        $oFile = new CFile($sFilePath, CFile::READ);
        $byData = $oFile->readAvailableBytes(100);
        $this->assertTrue($byData->equals("Hello there!"));
        $oFile->done();  // only needed in these tests

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWriteBytes ()
    {
        $sFilePath = CFile::createTemporary();

        $oFile = new CFile($sFilePath, CFile::WRITE_NEW);
        $oFile->writeBytes("Hello");
        $oFile->writeBytes(" there!");
        $oFile->done();  // only needed in these tests
        $this->assertTrue(CFile::read($sFilePath)->equals("Hello there!"));

        $oFile = new CFile($sFilePath, CFile::WRITE_NEW);
        $oFile->writeBytes("Hello Hello", 5);
        $oFile->writeBytes(" there! there!", 7);
        $oFile->done();  // only needed in these tests
        $this->assertTrue(CFile::read($sFilePath)->equals("Hello there!"));

        // WRITE_APPEND
        $oFile = new CFile($sFilePath, CFile::WRITE_NEW);
        $oFile->writeBytes("Hello");
        $oFile->done();  // only needed in these tests
        $oFile = new CFile($sFilePath, CFile::WRITE_APPEND);
        $oFile->writeBytes(" there!");
        $oFile->done();  // only needed in these tests
        $this->assertTrue(CFile::read($sFilePath)->equals("Hello there!"));

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPos ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::write($sFilePath, "Hello there!");
        $oFile = new CFile($sFilePath, CFile::READ);
        $this->assertTrue( $oFile->pos() == 0 );
        $oFile->readBytes(5);
        $this->assertTrue( $oFile->pos() == 5 );
        $oFile->readBytes(7);
        $this->assertTrue( $oFile->pos() == 12 );
        $oFile->done();  // only needed in these tests

        $oFile = new CFile($sFilePath, CFile::WRITE_NEW);
        $this->assertTrue( $oFile->pos() == 0 );
        $oFile->writeBytes("Hello");
        $this->assertTrue( $oFile->pos() == 5 );
        $oFile->writeBytes(" there!");
        $this->assertTrue( $oFile->pos() == 12 );
        $oFile->done();  // only needed in these tests

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetPos ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::write($sFilePath, "Hello there!");
        $oFile = new CFile($sFilePath, CFile::READ);
        $oFile->setPos(2);
        $byData = $oFile->readBytes(6);
        $this->assertTrue($byData->equals("llo th"));
        $oFile->setPos(11);
        $byData = $oFile->readBytes(1);
        $this->assertTrue($byData->equals("!"));
        $oFile->done();  // only needed in these tests

        $oFile = new CFile($sFilePath, CFile::WRITE_NEW);
        $oFile->writeBytes("Hello");
        $oFile->setPos(3);
        $oFile->writeBytes("there!");
        $oFile->done();  // only needed in these tests
        $this->assertTrue(CFile::read($sFilePath)->equals("Helthere!"));

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftPos ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::write($sFilePath, "Hello there!");
        $oFile = new CFile($sFilePath, CFile::READ);
        $oFile->shiftPos(2);
        $byData = $oFile->readBytes(6);
        $this->assertTrue($byData->equals("llo th"));
        $oFile->shiftPos(-2);
        $byData = $oFile->readBytes(2);
        $this->assertTrue($byData->equals("th"));
        $oFile->shiftPos(3);
        $byData = $oFile->readBytes(1);
        $this->assertTrue($byData->equals("!"));
        $oFile->done();  // only needed in these tests

        $oFile = new CFile($sFilePath, CFile::WRITE_NEW);
        $oFile->writeBytes("Hello");
        $oFile->shiftPos(-2);
        $oFile->writeBytes("there!");
        $oFile->done();  // only needed in these tests
        $this->assertTrue(CFile::read($sFilePath)->equals("Helthere!"));

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetPosToStart ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::write($sFilePath, "Hello there!");
        $oFile = new CFile($sFilePath, CFile::READ);
        $byData = $oFile->readBytes(5);
        $this->assertTrue($byData->equals("Hello"));
        $oFile->setPosToStart();
        $byData = $oFile->readBytes(2);
        $this->assertTrue($byData->equals("He"));
        $oFile->done();  // only needed in these tests

        $oFile = new CFile($sFilePath, CFile::WRITE_NEW);
        $oFile->writeBytes("Hello");
        $oFile->setPosToStart();
        $oFile->writeBytes("th");
        $oFile->done();  // only needed in these tests
        $this->assertTrue(CFile::read($sFilePath)->equals("thllo"));

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetPosToEnd ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::write($sFilePath, "Hello there!");
        $oFile = new CFile($sFilePath, CFile::READ);
        $oFile->setPosToEnd();
        $oFile->shiftPos(-6);
        $byData = $oFile->readBytes(6);
        $this->assertTrue($byData->equals("there!"));
        $oFile->done();  // only needed in these tests

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsPosPastEnd ()
    {
        $sFilePath = CFile::createTemporary();

        CFile::write($sFilePath, "Hello");
        $oFile = new CFile($sFilePath, CFile::READ);
        $oFile->readBytes(5);
        $this->assertFalse($oFile->isPosPastEnd());
        $oFile->done();  // only needed in these tests

        CFile::write($sFilePath, "Hello");
        $oFile = new CFile($sFilePath, CFile::READ);
        $oFile->setPosToEnd();
        $this->assertFalse($oFile->isPosPastEnd());
        $oFile->done();  // only needed in these tests

        CFile::write($sFilePath, "Hello");
        $oFile = new CFile($sFilePath, CFile::READ);
        $oFile->readBytes(5);
        $byData = $oFile->readAvailableBytes(5);
        $this->assertTrue( $oFile->isPosPastEnd() && $byData->equals("") );
        $oFile->done();  // only needed in these tests

        CFile::delete($sFilePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected static $ms_sTempDirName = "test-dir-ivr27kwb";
}
