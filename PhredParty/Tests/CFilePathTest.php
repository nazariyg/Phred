<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CFilePathTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testName ()
    {
        $this->assertTrue(CFilePath::name("/path/to/file.png")->equals("file.png"));
        $this->assertTrue(CFilePath::name("/path/to/file.tar.gz")->equals("file.tar.gz"));
        $this->assertTrue(CFilePath::name("./path/to/file.png")->equals("file.png"));
        $this->assertTrue(CFilePath::name("../path/to/file.png")->equals("file.png"));
        $this->assertTrue(CFilePath::name("/path/to/dir")->equals("dir"));
        $this->assertTrue(CFilePath::name("/path/to/dir/")->equals("dir"));
        $this->assertTrue(CFilePath::name("/path/to/dir//")->equals("dir"));
        $this->assertTrue(CFilePath::name("file.png")->equals("file.png"));
        $this->assertTrue(CFilePath::name("dir")->equals("dir"));
        $this->assertTrue(CFilePath::name("/path//to///file.png")->equals("file.png"));
        $this->assertTrue(CFilePath::name("/")->equals("/"));
        $this->assertTrue(CFilePath::name(".")->equals("."));
        $this->assertTrue(CFilePath::name("..")->equals(".."));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDirectory ()
    {
        $this->assertTrue(CFilePath::directory("/path/to/file.png")->equals("/path/to"));
        $this->assertTrue(CFilePath::directory("/path/to/file.tar.gz")->equals("/path/to"));
        $this->assertTrue(CFilePath::directory("./path/to/file.png")->equals("path/to"));
        $this->assertTrue(CFilePath::directory("../path/to/file.png")->equals("../path/to"));
        $this->assertTrue(CFilePath::directory("../path/to/../file.png")->equals("../path"));
        $this->assertTrue(CFilePath::directory("file.png")->equals("."));
        $this->assertTrue(CFilePath::directory("/path//to///file.png")->equals("/path/to"));
        $this->assertTrue(CFilePath::directory("/path/./to//./file.png")->equals("/path/to"));
        $this->assertTrue(CFilePath::directory("/")->equals("/"));
        $this->assertTrue(CFilePath::directory("/..")->equals("/"));
        $this->assertTrue(CFilePath::directory("/../..")->equals("/"));
        $this->assertTrue(CFilePath::directory(".")->equals("."));
        $this->assertTrue(CFilePath::directory("..")->equals("."));
        $this->assertTrue(CFilePath::directory("../dir")->equals(".."));
        $this->assertTrue(CFilePath::directory("../../dir")->equals("../.."));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNameOnly ()
    {
        $this->assertTrue(CFilePath::nameOnly("/path/to/file.png")->equals("file"));
        $this->assertTrue(CFilePath::nameOnly("/path/to/file.tar.gz")->equals("file"));
        $this->assertTrue(CFilePath::nameOnly("/path/to/file")->equals("file"));
        $this->assertTrue(CFilePath::nameOnly("./path/to/file.png")->equals("file"));
        $this->assertTrue(CFilePath::nameOnly("../path/to/file.png")->equals("file"));
        $this->assertTrue(CFilePath::nameOnly("../path/to/file")->equals("file"));
        $this->assertTrue(CFilePath::nameOnly("/path/to/dir")->equals("dir"));
        $this->assertTrue(CFilePath::nameOnly("/path/to/dir/")->equals("dir"));
        $this->assertTrue(CFilePath::nameOnly("/path/to/dir//")->equals("dir"));
        $this->assertTrue(CFilePath::nameOnly("/path/to/dir.a/")->equals("dir"));
        $this->assertTrue(CFilePath::nameOnly("file.png")->equals("file"));
        $this->assertTrue(CFilePath::nameOnly("file.tar.gz")->equals("file"));
        $this->assertTrue(CFilePath::nameOnly("dir")->equals("dir"));
        $this->assertTrue(CFilePath::nameOnly("/path//to///file.png")->equals("file"));
        $this->assertTrue(CFilePath::nameOnly("/")->equals("/"));
        $this->assertTrue(CFilePath::nameOnly(".")->equals("."));
        $this->assertTrue(CFilePath::nameOnly("..")->equals(".."));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testExtension ()
    {
        $this->assertTrue(CFilePath::extension("/path/to/file.png")->equals("png"));
        $this->assertTrue(CFilePath::extension("/path/to/file.tar.gz")->equals("tar.gz"));
        $this->assertTrue(CFilePath::extension("/path/to/file.sql.tar.gz")->equals("sql.tar.gz"));
        $this->assertTrue(CFilePath::extension("/path/t.o/file")->equals(""));
        $this->assertTrue(CFilePath::extension("/path/t.o/dir")->equals(""));
        $this->assertTrue(CFilePath::extension("/path/t.o/dir/")->equals(""));
        $this->assertTrue(CFilePath::extension("/path/t.o/dir//")->equals(""));
        $this->assertTrue(CFilePath::extension("file.png")->equals("png"));
        $this->assertTrue(CFilePath::extension("file")->equals(""));
        $this->assertTrue(CFilePath::extension("dir")->equals(""));
        $this->assertTrue(CFilePath::extension("/")->equals(""));
        $this->assertTrue(CFilePath::extension(".")->equals(""));
        $this->assertTrue(CFilePath::extension("..")->equals(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLastExtension ()
    {
        $this->assertTrue(CFilePath::lastExtension("/path/to/file.png")->equals("png"));
        $this->assertTrue(CFilePath::lastExtension("/path/to/file.tar.gz")->equals("gz"));
        $this->assertTrue(CFilePath::lastExtension("/path/to/file.sql.tar.gz")->equals("gz"));
        $this->assertTrue(CFilePath::lastExtension("/path/t.o/file")->equals(""));
        $this->assertTrue(CFilePath::lastExtension("/path/t.o/dir")->equals(""));
        $this->assertTrue(CFilePath::lastExtension("/path/t.o/dir/")->equals(""));
        $this->assertTrue(CFilePath::lastExtension("/path/t.o/dir//")->equals(""));
        $this->assertTrue(CFilePath::lastExtension("file.png")->equals("png"));
        $this->assertTrue(CFilePath::lastExtension("file")->equals(""));
        $this->assertTrue(CFilePath::lastExtension("dir")->equals(""));
        $this->assertTrue(CFilePath::lastExtension("/")->equals(""));
        $this->assertTrue(CFilePath::lastExtension(".")->equals(""));
        $this->assertTrue(CFilePath::lastExtension("..")->equals(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAdd ()
    {
        $this->assertTrue(CFilePath::add("/path/to", "file.png")->equals("/path/to/file.png"));
        $this->assertTrue(CFilePath::add("/path/to", "dir/file.png")->equals("/path/to/dir/file.png"));
        $this->assertTrue(CFilePath::add("/path/to/", "file.png")->equals("/path/to/file.png"));
        $this->assertTrue(CFilePath::add("", "file.png")->equals("file.png"));
        $this->assertTrue(CFilePath::add("/", "path/to/dir")->equals("/path/to/dir"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalize ()
    {
        $this->assertTrue(CFilePath::normalize("/path/to//file.png")->equals("/path/to/file.png"));
        $this->assertTrue(CFilePath::normalize("/path//to///file.png")->equals("/path/to/file.png"));
        $this->assertTrue(CFilePath::normalize("/path/to/dir/")->equals("/path/to/dir"));
        $this->assertTrue(CFilePath::normalize("/path//to/dir//")->equals("/path/to/dir"));
        $this->assertTrue(CFilePath::normalize("//path///to////dir")->equals("/path/to/dir"));
        $this->assertTrue(CFilePath::normalize("/")->equals("/"));
        $this->assertTrue(CFilePath::normalize("//")->equals("/"));
        $this->assertTrue(CFilePath::normalize("/path/./to/././dir")->equals("/path/to/dir"));
        $this->assertTrue(CFilePath::normalize("/path/./to/././dir/.")->equals("/path/to/dir"));
        $this->assertTrue(CFilePath::normalize("./path/./to/././dir/.")->equals("path/to/dir"));
        $this->assertTrue(CFilePath::normalize("/.")->equals("/"));
        $this->assertTrue(CFilePath::normalize("/./")->equals("/"));
        $this->assertTrue(CFilePath::normalize("/./.")->equals("/"));
        $this->assertTrue(CFilePath::normalize("././.")->equals("."));
        $this->assertTrue(CFilePath::normalize("./././")->equals("."));
        $this->assertTrue(CFilePath::normalize("path/to/dir/")->equals("path/to/dir"));
        $this->assertTrue(CFilePath::normalize("path/to/./dir/")->equals("path/to/dir"));
        $this->assertTrue(CFilePath::normalize("/dir")->equals("/dir"));
        $this->assertTrue(CFilePath::normalize("/dir/")->equals("/dir"));
        $this->assertTrue(CFilePath::normalize("/..")->equals("/"));
        $this->assertTrue(CFilePath::normalize("/../..")->equals("/"));
        $this->assertTrue(CFilePath::normalize("dir")->equals("dir"));
        $this->assertTrue(CFilePath::normalize("dir/")->equals("dir"));
        $this->assertTrue(CFilePath::normalize("/path/to/../dir")->equals("/path/dir"));
        $this->assertTrue(CFilePath::normalize("/path/to/../..")->equals("/"));
        $this->assertTrue(CFilePath::normalize("/path/to/../../")->equals("/"));
        $this->assertTrue(CFilePath::normalize("/path/to/../../../..")->equals("/"));
        $this->assertTrue(CFilePath::normalize("/./path/./to/../dir")->equals("/path/dir"));
        $this->assertTrue(CFilePath::normalize("/./path/./to//../../dir")->equals("/dir"));
        $this->assertTrue(CFilePath::normalize("/path/././dir-a/../to/../dir-b")->equals("/path/dir-b"));
        $this->assertTrue(CFilePath::normalize("/path//./dir-a/.././to//../dir-b/")->equals("/path/dir-b"));
        $this->assertTrue(CFilePath::normalize("path/to/../dir")->equals("path/dir"));
        $this->assertTrue(CFilePath::normalize("path/to/../..")->equals("."));
        $this->assertTrue(CFilePath::normalize("path/to/../../")->equals("."));
        $this->assertTrue(CFilePath::normalize("path/./to/../dir")->equals("path/dir"));
        $this->assertTrue(CFilePath::normalize("./path/./to//../../dir")->equals("dir"));
        $this->assertTrue(CFilePath::normalize("./.././..")->equals("../.."));
        $this->assertTrue(CFilePath::normalize("path/././dir-a/../to/../dir-b")->equals("path/dir-b"));
        $this->assertTrue(CFilePath::normalize("path/././dir-a/../to/../dir-b/../..")->equals("."));
        $this->assertTrue(CFilePath::normalize("path/././dir-a/../to/../dir-b/../../")->equals("."));
        $this->assertTrue(CFilePath::normalize("path/././dir-a/../to/../dir-b/../../..")->equals(".."));
        $this->assertTrue(CFilePath::normalize("path/././dir-a/../to/../dir-b/../../../..")->equals("../.."));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAbsolute ()
    {
        $directoryPath = CFilePath::add(CSystem::temporaryFilesDp(),
            CFile::DEFAULT_TEMPORARY_FILE_PREFIX . self::$ms_tempDirName);
        if ( CFile::exists($directoryPath) )
        {
            CFile::deleteDirectoryRecursive($directoryPath);
        }
        CFile::createDirectory($directoryPath);

        $filePathAbs = CFilePath::add($directoryPath, "file");
        CFile::create($filePathAbs);
        CSystem::cd($directoryPath);
        $this->assertTrue(CFilePath::absolute("file")->equals(CFilePath::absolute($filePathAbs)));
        $this->assertTrue(CFilePath::absolute("./file")->equals(CFilePath::absolute($filePathAbs)));

        CFile::deleteDirectoryRecursive($directoryPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsAbsolute ()
    {
        $this->assertTrue(CFilePath::isAbsolute("/path/to/file.png"));
        $this->assertFalse(CFilePath::isAbsolute("file.png"));
        $this->assertFalse(CFilePath::isAbsolute("path/to/file.png"));
        $this->assertTrue(CFilePath::isAbsolute("/"));
        $this->assertFalse(CFilePath::isAbsolute("."));
        $this->assertFalse(CFilePath::isAbsolute(".."));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFrameworkPath ()
    {
        $this->assertTrue(
            CFilePath::absolute(CFilePath::frameworkPath("{{PHRED_PATH_TO_APP}}/Tests"))->equals(
            CFilePath::absolute(CFilePath::add($GLOBALS["PHRED_PATH_TO_APP"], "/Tests"))));
        $this->assertTrue(
            CFilePath::absolute(CFilePath::frameworkPath("{{PHRED_PATH_TO_FRAMEWORK_ROOT}}/Application/Tests"))->equals(
            CFilePath::absolute(CFilePath::add($GLOBALS["PHRED_PATH_TO_FRAMEWORK_ROOT"], "/Application/Tests"))));
        $this->assertTrue(
            CFilePath::absolute(CFilePath::frameworkPath("{{PHRED_PATH_TO_APP}}Tests"))->equals(
            CFilePath::absolute(CFilePath::add($GLOBALS["PHRED_PATH_TO_APP"], "/Tests"))));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected static $ms_tempDirName = "test-dir-ivr27kwb";
}
