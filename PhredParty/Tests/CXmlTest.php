<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CXmlTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEnterTd ()
    {
        $this->assertTrue(CXml::enterTd("<p>Tést &amp; paragraph.</p><a href='#fragment'>Other text</a>")->equals(
            "&lt;p&gt;Tést &amp;amp; paragraph.&lt;/p&gt;&lt;a href=&#039;#fragment&#039;&gt;Other text&lt;/a&gt;"));

        $this->assertTrue(
            CXml::enterTd("<p>Tést &amp; paragraph.</p><a href='#fragment'>Other text</a>", false)->equals(
            "&lt;p&gt;Tést &amp; paragraph.&lt;/p&gt;&lt;a href=&#039;#fragment&#039;&gt;Other text&lt;/a&gt;"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLeaveTd ()
    {
        $this->assertTrue(CXml::leaveTd(
            "&lt;p&gt;Tést &amp;amp; paragraph.&lt;/p&gt;&lt;a href=&#039;#fragment&#039;&gt;Other text&lt;/a&gt;")->
            equals("<p>Tést &amp; paragraph.</p><a href='#fragment'>Other text</a>"));

        $this->assertTrue(CXml::leaveTd(
            "&lt;p&gt;Tést &amp; paragraph.&lt;/p&gt;&lt;a href=&#039;#fragment&#039;&gt;Other text&lt;/a&gt;")->
            equals("<p>Tést & paragraph.</p><a href='#fragment'>Other text</a>"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEnterTdAll ()
    {
        $this->assertTrue(CXml::enterTdAll("<p>Tést &amp; paragraph.</p><a href='#fragment'>Other text</a>")->equals(
            "&lt;p&gt;T&eacute;st &amp;amp; paragraph.&lt;/p&gt;&lt;a href=&#039;#fragment&#039;&gt;" .
            "Other text&lt;/a&gt;"));

        $this->assertTrue(
            CXml::enterTdAll("<p>Tést &amp; paragraph.</p><a href='#fragment'>Other text</a>", false)->equals(
            "&lt;p&gt;T&eacute;st &amp; paragraph.&lt;/p&gt;&lt;a href=&#039;#fragment&#039;&gt;" .
            "Other text&lt;/a&gt;"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLeaveTdAll ()
    {
        $this->assertTrue(CXml::leaveTdAll(
            "&lt;p&gt;T&eacute;st &amp;amp; paragraph.&lt;/p&gt;&lt;a href=&#039;#fragment&#039;&gt;" .
            "Other text&lt;/a&gt;")->equals("<p>Tést &amp; paragraph.</p><a href='#fragment'>Other text</a>"));

        $this->assertTrue(
            CXml::leaveTdAll("&lt;p&gt;T&eacute;st &amp; paragraph.&lt;/p&gt;&lt;a href=&#039;#fragment&#039;&gt;" .
            "Other text&lt;/a&gt;", false)->equals("<p>Tést & paragraph.</p><a href='#fragment'>Other text</a>"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
