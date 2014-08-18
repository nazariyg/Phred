<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
        $stringUtf8 = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $stringLatin1 = CEString::convert($stringUtf8, CEString::UTF8, CEString::LATIN1);
        $this->assertTrue($stringLatin1->equalsBi("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73" .
            "\x6B\x6C\xE4\x6E\x67\x65\x20\x76\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73" .
            "\x69\x6E\x64\x20\x6E\x69\x78\x20\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65" .
            "\x6E\x2E"));

        $stringLatin1 = u("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65" .
            "\x20\x76\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73\x69\x6E\x64\x20\x6E\x69" .
            "\x78\x20\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65\x6E\x2E");
        $stringUtf8 = CEString::convert($stringLatin1, CEString::LATIN1, CEString::UTF8);
        $this->assertTrue($stringUtf8->equalsBi(
            "Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen."));

        $stringUtf8 = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $stringAscii = CEString::convert($stringUtf8, CEString::UTF8, CEString::ASCII);
        $this->assertTrue($stringAscii->equalsBi(
            "Asynchrone Basskl?nge vom Jazzquintett sind nix f?r spie?ige L?wen."));

        $stringUtf8 = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $stringUtf16 = CEString::convert($stringUtf8, CEString::UTF8, "utf-16be");
        $this->assertTrue($stringUtf16->equalsBi("\x00\x41\x00\x73\x00\x79\x00\x6E\x00\x63\x00\x68\x00\x72\x00" .
            "\x6F\x00\x6E\x00\x65\x00\x20\x00\x42\x00\x61\x00\x73\x00\x73\x00\x6B\x00\x6C\x00\xE4\x00\x6E\x00\x67" .
            "\x00\x65\x00\x20\x00\x76\x00\x6F\x00\x6D\x00\x20\x00\x4A\x00\x61\x00\x7A\x00\x7A\x00\x71\x00\x75\x00" .
            "\x69\x00\x6E\x00\x74\x00\x65\x00\x74\x00\x74\x00\x20\x00\x73\x00\x69\x00\x6E\x00\x64\x00\x20\x00\x6E" .
            "\x00\x69\x00\x78\x00\x20\x00\x66\x00\xFC\x00\x72\x00\x20\x00\x73\x00\x70\x00\x69\x00\x65\x00\xDF\x00" .
            "\x69\x00\x67\x00\x65\x00\x20\x00\x4C\x00\xF6\x00\x77\x00\x65\x00\x6E\x00\x2E"));

        $stringUtf8 = u("\xFEAsynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.\xFF");
        $stringUtf8 = CEString::convert($stringUtf8, CEString::UTF8, CEString::UTF8);
        $this->assertTrue($stringUtf8->equalsBi(
            "�Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.�"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testConvertLatin1ToUtf8 ()
    {
        $stringLatin1 = u("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65" .
            "\x20\x76\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73\x69\x6E\x64\x20\x6E\x69" .
            "\x78\x20\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65\x6E\x2E");
        $stringUtf8 = CEString::convertLatin1ToUtf8($stringLatin1);
        $this->assertTrue($stringUtf8->equalsBi(
            "Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen."));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFlattenUnicodeToAscii ()
    {
        $stringUtf8 = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $stringAscii = CEString::flattenUnicodeToAscii($stringUtf8);
        $this->assertTrue($stringAscii->equalsBi(
            "Asynchrone Bassklange vom Jazzquintett sind nix fur spiessige Lowen."));

        $stringUtf8 = u("1a とりなくこゑす ゆめさませ みよあけわたる ひんかしを そらいろはえて おきつへに ほふねむれゐぬ もやのうち");
        $stringAscii = CEString::flattenUnicodeToAscii($stringUtf8);
        $this->assertTrue($stringAscii->equalsBi(
            "1a torinakukowesu yumesamase miyoakewataru hinkashiwo sorairohaete okitsuheni hofunemurewinu moyanouchi"));

        $stringUtf8 = u("1a とりなくこゑす ゆめさませ みよあけわたる ひんかしを そらいろはえて おきつへに ほふねむれゐぬ もやのうち");
        $stringAscii = CEString::flattenUnicodeToAscii($stringUtf8, false);
        $this->assertTrue($stringAscii->equalsBi("1a        "));

        $stringUtf8 = u("æß");
        $stringAscii = CEString::flattenUnicodeToAscii($stringUtf8);
        $this->assertTrue($stringAscii->equalsBi("aess"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLooksLikeUtf8 ()
    {
        $string = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $this->assertTrue(CEString::looksLikeUtf8($string));

        $escString = u("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65\x20\x76" .
            "\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73\x69\x6E\x64\x20\x6E\x69\x78\x20" .
            "\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65\x6E\x2E");
        $this->assertFalse(CEString::looksLikeUtf8($escString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLooksLikeAscii ()
    {
        $string = u("Hello there!");
        $this->assertTrue(CEString::looksLikeAscii($string));

        $string = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $this->assertFalse(CEString::looksLikeAscii($string));

        $escString = u("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65\x20\x76" .
            "\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73\x69\x6E\x64\x20\x6E\x69\x78\x20" .
            "\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65\x6E\x2E");
        $this->assertFalse(CEString::looksLikeAscii($escString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLooksLikeLatin1 ()
    {
        $escString = u("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65\x20\x76" .
            "\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73\x69\x6E\x64\x20\x6E\x69\x78\x20" .
            "\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65\x6E\x2E");
        $this->assertTrue(CEString::looksLikeLatin1($escString));

        $string = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $this->assertFalse(CEString::looksLikeLatin1($string));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testGuessEnc ()
    {
        $string = u("Hello there!");
        $enc;
        $success = CEString::guessEnc($string, $enc);
        $this->assertTrue( $success && $enc == CEString::UTF8 );

        $string = u("Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen.");
        $enc;
        $success = CEString::guessEnc($string, $enc);
        $this->assertTrue( $success && $enc == CEString::UTF8 );

        $string = u("\x41\x73\x79\x6E\x63\x68\x72\x6F\x6E\x65\x20\x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65\x20\x76" .
            "\x6F\x6D\x20\x4A\x61\x7A\x7A\x71\x75\x69\x6E\x74\x65\x74\x74\x20\x73\x69\x6E\x64\x20\x6E\x69\x78\x20" .
            "\x66\xFC\x72\x20\x73\x70\x69\x65\xDF\x69\x67\x65\x20\x4C\xF6\x77\x65\x6E\x2E");
        $enc;
        $success = CEString::guessEnc($string, $enc);
        $this->assertTrue( $success && $enc == CEString::LATIN1 );

        $string = u("\xFE\x00\xFF");
        $enc;
        $success = CEString::guessEnc($string, $enc);
        $this->assertFalse($success);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKnownPrimaryEncNames ()
    {
        $encNames = CEString::knownPrimaryEncNames();
        $this->assertTrue($encNames->find("UTF-8"));
        $this->assertTrue($encNames->find("UTF-16"));
        $this->assertTrue($encNames->find("ISO-8859-1"));
        $this->assertTrue($encNames->find("macos-0_2-10.2"));
        $this->assertFalse($encNames->find("csMacintosh"));
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
        $aliases = CEString::encNameAliases("UTF-8");
        $this->assertTrue($aliases->find("ibm-1208"));
        $this->assertTrue($aliases->find("cp1208"));
        $this->assertTrue($aliases->find("x-UTF_8J"));

        $aliases = CEString::encNameAliases("x-UTF_8J");
        $this->assertTrue($aliases->find("UTF-8"));
        $this->assertTrue($aliases->find("ibm-1208"));
        $this->assertTrue($aliases->find("cp1208"));
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
        $string = u("Asynchrone \x42\x61\x73\x73\x6B\x6C\xE4\x6E\x67\x65 vom Jazzquintett sind nix \x66\xFC\x72 " .
            "\x73\x70\x69\x65\xDF\x69\x67\x65 Löwen.");
        $string = CEString::fixUtf8($string);
        $this->assertTrue($string->equalsBi(
            "Asynchrone Bassklänge vom Jazzquintett sind nix für spießige Löwen."));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFixUtf8More ()
    {
        $string = u("FÃ©dÃ©ration Camerounaise de Football");
        $string = CEString::fixUtf8More($string);
        $this->assertTrue($string->equalsBi("Fédération Camerounaise de Football"));

        $string = u("FÃÂ©dÃÂ©ration Camerounaise de Football");
        $string = CEString::fixUtf8More($string);
        $this->assertTrue($string->equalsBi("Fédération Camerounaise de Football"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
