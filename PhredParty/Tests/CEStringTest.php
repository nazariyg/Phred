<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CEStringTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testConvert ()
    {
        $sStringUtf8 = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $byStringLatin1 = CEString::convert($sStringUtf8, CEString::UTF8, CEString::LATIN1);
        $this->assertTrue($byStringLatin1->equalsBi("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73" .
            "\x6B\x6C\xE4\x6E\x67\x65\x20\x76\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73" .
            "\x69\x6E\x64\x20\x6E\x69\x78\x20\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65" .
            "\x6E\x2E"));

        $byStringLatin1 = u("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65" .
            "\x20\x76\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73\x69\x6E\x64\x20\x6E\x69" .
            "\x78\x20\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65\x6E\x2E");
        $sStringUtf8 = CEString::convert($byStringLatin1, CEString::LATIN1, CEString::UTF8);
        $this->assertTrue($sStringUtf8->equalsBi(
            "Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen."));

        $sStringUtf8 = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $sStringAscii = CEString::convert($sStringUtf8, CEString::UTF8, CEString::ASCII);
        $this->assertTrue($sStringAscii->equalsBi(
            "Asynchrone Basskl?nge vom Jazzquintett sind nix f?r spie?ige L?wen."));

        $sStringUtf8 = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $byStringUtf16 = CEString::convert($sStringUtf8, CEString::UTF8, "utf-16be");
        $this->assertTrue($byStringUtf16->equalsBi("\x00\x41\x00\x73\x00\x79\x00\x6E\x00\x63\x00\x68\x00\x72\x00" .
            "\x6F\x00\x6E\x00\x65\x00\x20\x00\x42\x00\x61\x00\x73\x00\x73\x00\x6B\x00\x6C\x00\xE4\x00\x6E\x00\x67" .
            "\x00\x65\x00\x20\x00\x76\x00\x6F\x00\x6D\x00\x20\x00\x4A\x00\x61\x00\x7A\x00\x7A\x00\x71\x00\x75\x00" .
            "\x69\x00\x6E\x00\x74\x00\x65\x00\x74\x00\x74\x00\x20\x00\x73\x00\x69\x00\x6E\x00\x64\x00\x20\x00\x6E" .
            "\x00\x69\x00\x78\x00\x20\x00\x66\x00\xFC\x00\x72\x00\x20\x00\x73\x00\x70\x00\x69\x00\x65\x00\xDF\x00" .
            "\x69\x00\x67\x00\x65\x00\x20\x00\x4C\x00\xF6\x00\x77\x00\x65\x00\x6E\x00\x2E"));

        $sStringUtf8 = u("\xFEAsynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.\xFF");
        $sStringUtf8 = CEString::convert($sStringUtf8, CEString::UTF8, CEString::UTF8);
        $this->assertTrue($sStringUtf8->equalsBi(
            "�Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.�"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testConvertLatin1ToUtf8 ()
    {
        $byStringLatin1 = u("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65" .
            "\x20\x76\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73\x69\x6E\x64\x20\x6E\x69" .
            "\x78\x20\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65\x6E\x2E");
        $sStringUtf8 = CEString::convertLatin1ToUtf8($byStringLatin1);
        $this->assertTrue($sStringUtf8->equalsBi(
            "Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen."));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFlattenUnicodeToAscii ()
    {
        $sStringUtf8 = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $sStringAscii = CEString::flattenUnicodeToAscii($sStringUtf8);
        $this->assertTrue($sStringAscii->equalsBi(
            "Asynchrone Bassklange vom Jazzquintett sind nix fur spiessige Lowen."));

        $sStringUtf8 = u("1a とりなくこゑす ゆめさませ みよあけわたる ひんかしを そらいろはえて おきつへに ほふねむれゐぬ もやのうち");
        $sStringAscii = CEString::flattenUnicodeToAscii($sStringUtf8);
        $this->assertTrue($sStringAscii->equalsBi(
            "1a torinakukowesu yumesamase miyoakewataru hinkashiwo sorairohaete okitsuheni hofunemurewinu moyanouchi"));

        $sStringUtf8 = u("1a とりなくこゑす ゆめさませ みよあけわたる ひんかしを そらいろはえて おきつへに ほふねむれゐぬ もやのうち");
        $sStringAscii = CEString::flattenUnicodeToAscii($sStringUtf8, false);
        $this->assertTrue($sStringAscii->equalsBi("1a        "));

        $sStringUtf8 = u("æß");
        $sStringAscii = CEString::flattenUnicodeToAscii($sStringUtf8);
        $this->assertTrue($sStringAscii->equalsBi("aess"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLooksLikeUtf8 ()
    {
        $sString = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $this->assertTrue(CEString::looksLikeUtf8($sString));

        $byString = u("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65\x20\x76" .
            "\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73\x69\x6E\x64\x20\x6E\x69\x78\x20" .
            "\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65\x6E\x2E");
        $this->assertFalse(CEString::looksLikeUtf8($byString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLooksLikeAscii ()
    {
        $sString = u("Hello there!");
        $this->assertTrue(CEString::looksLikeAscii($sString));

        $sString = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $this->assertFalse(CEString::looksLikeAscii($sString));

        $byString = u("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65\x20\x76" .
            "\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73\x69\x6E\x64\x20\x6E\x69\x78\x20" .
            "\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65\x6E\x2E");
        $this->assertFalse(CEString::looksLikeAscii($byString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLooksLikeLatin1 ()
    {
        $byString = u("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65\x20\x76" .
            "\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73\x69\x6E\x64\x20\x6E\x69\x78\x20" .
            "\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65\x6E\x2E");
        $this->assertTrue(CEString::looksLikeLatin1($byString));

        $sString = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $this->assertFalse(CEString::looksLikeLatin1($sString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testGuessEnc ()
    {
        $sString = u("Hello there!");
        $eEnc;
        $bSuccess = CEString::guessEnc($sString, $eEnc);
        $this->assertTrue( $bSuccess && $eEnc == CEString::UTF8 );

        $sString = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $eEnc;
        $bSuccess = CEString::guessEnc($sString, $eEnc);
        $this->assertTrue( $bSuccess && $eEnc == CEString::UTF8 );

        $byString = u("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65\x20\x76" .
            "\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73\x69\x6E\x64\x20\x6E\x69\x78\x20" .
            "\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65\x6E\x2E");
        $eEnc;
        $bSuccess = CEString::guessEnc($byString, $eEnc);
        $this->assertTrue( $bSuccess && $eEnc == CEString::LATIN1 );

        $byString = u("\xFE\x00\xFF");
        $eEnc;
        $bSuccess = CEString::guessEnc($byString, $eEnc);
        $this->assertFalse($bSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKnownPrimaryEncNames ()
    {
        $aEncNames = CEString::knownPrimaryEncNames();
        $this->assertTrue($aEncNames->find("UTF-8"));
        $this->assertTrue($aEncNames->find("UTF-16"));
        $this->assertTrue($aEncNames->find("ISO-8859-1"));
        $this->assertTrue($aEncNames->find("macos-0_2-10.2"));
        $this->assertFalse($aEncNames->find("csMacintosh"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEncNameKnown ()
    {
        $this->assertTrue(CEString::isEncNameKnown("utf-8"));
        $this->assertTrue(CEString::isEncNameKnown("utf_16"));
        $this->assertTrue(CEString::isEncNameKnown("latin1 "));  // with whitespace
        $this->assertTrue(CEString::isEncNameKnown(" csMacintosh"));  // with whitespace
        $this->assertFalse(CEString::isEncNameKnown("BMO-Enc-Time08"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEncNameAliases ()
    {
        $aAliases = CEString::encNameAliases("UTF-8");
        $this->assertTrue($aAliases->find("ibm-1208"));
        $this->assertTrue($aAliases->find("cp1208"));
        $this->assertTrue($aAliases->find("x-UTF_8J"));

        $aAliases = CEString::encNameAliases("x-UTF_8J");
        $this->assertTrue($aAliases->find("UTF-8"));
        $this->assertTrue($aAliases->find("ibm-1208"));
        $this->assertTrue($aAliases->find("cp1208"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAreEncNamesEquivalent ()
    {
        $this->assertTrue(CEString::areEncNamesEquivalent("utf8 ", "UTF-8"));  // with whitespace
        $this->assertTrue(CEString::areEncNamesEquivalent(" latin1 ", "ISO-8859-1"));  // with whitespace
        $this->assertTrue(CEString::areEncNamesEquivalent(" ascii ", "ANSI_X3.4-1986"));  // with whitespace
        $this->assertFalse(CEString::areEncNamesEquivalent("ascii", "oldskool"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEncNameUtf8 ()
    {
        $this->assertTrue(CEString::isEncNameUtf8("UTF-8 "));  // with whitespace
        $this->assertTrue(CEString::isEncNameUtf8(" utf8 "));  // with whitespace
        $this->assertTrue(CEString::isEncNameUtf8(" ibm1208 "));  // with whitespace
        $this->assertFalse(CEString::isEncNameUtf8("utf16"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEncNameAscii ()
    {
        $this->assertTrue(CEString::isEncNameAscii("ASCII "));  // with whitespace
        $this->assertTrue(CEString::isEncNameAscii(" us-ascii "));  // with whitespace
        $this->assertTrue(CEString::isEncNameAscii(" us "));  // with whitespace
        $this->assertFalse(CEString::isEncNameAscii("oldskool"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEncNameLatin1 ()
    {
        $this->assertTrue(CEString::isEncNameLatin1("ISO-8859-1 "));  // with whitespace
        $this->assertTrue(CEString::isEncNameLatin1(" latin1 "));  // with whitespace
        $this->assertTrue(CEString::isEncNameLatin1(" cp819 "));  // with whitespace
        $this->assertFalse(CEString::isEncNameLatin1("oldskool"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFixUtf8 ()
    {
        $sString = u("Asynchrone \x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65 vom Jazzquintett sind nix \x66\xFC\x72 " .
            "\x73\x70\x69\x65\xDF\x69\x67\x65 Löwen.");
        $sString = CEString::fixUtf8($sString);
        $this->assertTrue($sString->equalsBi(
            "Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen."));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFixUtf8More ()
    {
        $sString = u("FÃ©dÃ©ration Camerounaise de Football");
        $sString = CEString::fixUtf8More($sString);
        $this->assertTrue($sString->equalsBi("Fédération Camerounaise de Football"));

        $sString = u("FÃÂ©dÃÂ©ration Camerounaise de Football");
        $sString = CEString::fixUtf8More($sString);
        $this->assertTrue($sString->equalsBi("Fédération Camerounaise de Football"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
