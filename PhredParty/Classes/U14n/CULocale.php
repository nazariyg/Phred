<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class of any locale.
 *
 * **You can refer to this class by its alias, which is** `ULoc`.
 *
 * "Locale" is a term that designates a group of users with similar cultural and linguistic expectations for
 * human-computer interaction.
 *
 * A locale is identified by its name, which is a string consisting of one or more components. The primary components
 * of a locale name is the language code (two letters, usually in lowercase) and the country/region code (two letters,
 * usually in uppercase). The components in a locale name are separated with "_". For example, "de_BE" locale name
 * refers to the German language with all of the peculiarities of its usage in the country of Belgium. Additional
 * components in a locale name can be the code of the writing script placed in between of the language code and the
 * country/region code (four letters, as in e.g. "sr_Latn_RS"), one or more variants (sub-locales) indicated after the
 * country/region code (usually in uppercase), and locale metadata in the form of keyword-value pairs that, if present,
 * conclude the locale name separated from everything else with "@" and use ";" to delimit one "keyword=value" from
 * another.
 *
 * For more detailed information on locales, you can look into
 * [ICU User Guide](http://userguide.icu-project.org/locale).
 */

// Method signatures:
//   __construct ($sLocaleName)
//   static CULocale fromCountryCode ($sCode)
//   static CULocale fromComponents ($sLanguage, $sRegion = null, $sScript = null/*, variants...*/)
//   static CULocale fromRfc2616 ($sLocaleName, &$rbSuccess = null)
//   static CULocale makeDefault ()
//   CUStringObject name ()
//   CUStringObject languageCode ()
//   bool hasRegionCode ()
//   CUStringObject regionCode ()
//   bool hasScriptCode ()
//   CUStringObject scriptCode ()
//   bool hasVariants ()
//   CArrayObject variants ()
//   bool hasKeywords ()
//   CMapObject keywords ()
//   void components (&$rsLanguage, &$rsRegion = null, &$rsScript = null, &$raVariants = null, &$rmKeywords = null)
//   CUStringObject dispName (CULocale $oInLocale = null)
//   CUStringObject dispLanguage (CULocale $oInLocale = null)
//   CUStringObject dispRegion (CULocale $oInLocale = null)
//   CUStringObject dispScript (CULocale $oInLocale = null)
//   CUStringObject dispVariants (CULocale $oInLocale = null)
//   void addKeyword ($sKeyword, $sValue)
//   bool equals ($oToLocale)
//   static CArrayObject knownCountryCodes ()
//   static bool isCountryCodeKnown ($sCode)
//   static CUStringObject countryEnNameForCountryCode ($sCode)
//   static CUStringObject currencyForCountryCode ($sCode)
//   static bool isValid ($sLocaleName)
//   static CUStringObject defaultLocaleName ()

class CULocale extends CRootClass implements IEquality
{
    // ISO 639 locale codes: just the language, possibly with the script.
    /**
     * `string` "af"
     *
     * @var string
     */
    const AFRIKAANS = "af";
    /**
     * `string` "ak"
     *
     * @var string
     */
    const AKAN = "ak";
    /**
     * `string` "sq"
     *
     * @var string
     */
    const ALBANIAN = "sq";
    /**
     * `string` "am"
     *
     * @var string
     */
    const AMHARIC = "am";
    /**
     * `string` "ar"
     *
     * @var string
     */
    const ARABIC = "ar";
    /**
     * `string` "hy"
     *
     * @var string
     */
    const ARMENIAN = "hy";
    /**
     * `string` "as"
     *
     * @var string
     */
    const ASSAMESE = "as";
    /**
     * `string` "az"
     *
     * @var string
     */
    const AZERBAIJANI = "az";
    /**
     * `string` "az_Cyrl"
     *
     * @var string
     */
    const AZERBAIJANI_CYRILLIC = "az_Cyrl";
    /**
     * `string` "az_Latn"
     *
     * @var string
     */
    const AZERBAIJANI_LATIN = "az_Latn";
    /**
     * `string` "bm"
     *
     * @var string
     */
    const BAMBARA = "bm";
    /**
     * `string` "bas"
     *
     * @var string
     */
    const BASAA = "bas";
    /**
     * `string` "eu"
     *
     * @var string
     */
    const BASQUE = "eu";
    /**
     * `string` "be"
     *
     * @var string
     */
    const BELARUSIAN = "be";
    /**
     * `string` "bem"
     *
     * @var string
     */
    const BEMBA = "bem";
    /**
     * `string` "bn"
     *
     * @var string
     */
    const BENGALI = "bn";
    /**
     * `string` "bs"
     *
     * @var string
     */
    const BOSNIAN = "bs";
    /**
     * `string` "br"
     *
     * @var string
     */
    const BRETON = "br";
    /**
     * `string` "bg"
     *
     * @var string
     */
    const BULGARIAN = "bg";
    /**
     * `string` "my"
     *
     * @var string
     */
    const BURMESE = "my";
    /**
     * `string` "ca"
     *
     * @var string
     */
    const CATALAN = "ca";
    /**
     * `string` "chr"
     *
     * @var string
     */
    const CHEROKEE = "chr";
    /**
     * `string` "zh"
     *
     * @var string
     */
    const CHINESE = "zh";
    /**
     * `string` "zh_Hans"
     *
     * @var string
     */
    const CHINESE_SIMPLIFIED = "zh_Hans";
    /**
     * `string` "zh_Hant"
     *
     * @var string
     */
    const CHINESE_TRADITIONAL = "zh_Hant";
    /**
     * `string` "kw"
     *
     * @var string
     */
    const CORNISH = "kw";
    /**
     * `string` "hr"
     *
     * @var string
     */
    const CROATIAN = "hr";
    /**
     * `string` "cs"
     *
     * @var string
     */
    const CZECH = "cs";
    /**
     * `string` "da"
     *
     * @var string
     */
    const DANISH = "da";
    /**
     * `string` "dua"
     *
     * @var string
     */
    const DUALA = "dua";
    /**
     * `string` "nl"
     *
     * @var string
     */
    const DUTCH = "nl";
    /**
     * `string` "en"
     *
     * @var string
     */
    const ENGLISH = "en";
    /**
     * `string` "eo"
     *
     * @var string
     */
    const ESPERANTO = "eo";
    /**
     * `string` "et"
     *
     * @var string
     */
    const ESTONIAN = "et";
    /**
     * `string` "ee"
     *
     * @var string
     */
    const EWE = "ee";
    /**
     * `string` "ewo"
     *
     * @var string
     */
    const EWONDO = "ewo";
    /**
     * `string` "fo"
     *
     * @var string
     */
    const FAROESE = "fo";
    /**
     * `string` "fi"
     *
     * @var string
     */
    const FINNISH = "fi";
    /**
     * `string` "fr"
     *
     * @var string
     */
    const FRENCH = "fr";
    /**
     * `string` "ff"
     *
     * @var string
     */
    const FULAH = "ff";
    /**
     * `string` "gl"
     *
     * @var string
     */
    const GALICIAN = "gl";
    /**
     * `string` "lg"
     *
     * @var string
     */
    const GANDA = "lg";
    /**
     * `string` "ka"
     *
     * @var string
     */
    const GEORGIAN = "ka";
    /**
     * `string` "de"
     *
     * @var string
     */
    const GERMAN = "de";
    /**
     * `string` "el"
     *
     * @var string
     */
    const GREEK = "el";
    /**
     * `string` "gu"
     *
     * @var string
     */
    const GUJARATI = "gu";
    /**
     * `string` "ha"
     *
     * @var string
     */
    const HAUSA = "ha";
    /**
     * `string` "ha_Latn"
     *
     * @var string
     */
    const HAUSA_LATIN = "ha_Latn";
    /**
     * `string` "haw"
     *
     * @var string
     */
    const HAWAIIAN = "haw";
    /**
     * `string` "he"
     *
     * @var string
     */
    const HEBREW = "he";
    /**
     * `string` "hi"
     *
     * @var string
     */
    const HINDI = "hi";
    /**
     * `string` "hu"
     *
     * @var string
     */
    const HUNGARIAN = "hu";
    /**
     * `string` "is"
     *
     * @var string
     */
    const ICELANDIC = "is";
    /**
     * `string` "ig"
     *
     * @var string
     */
    const IGBO = "ig";
    /**
     * `string` "id"
     *
     * @var string
     */
    const INDONESIAN = "id";
    /**
     * `string` "ga"
     *
     * @var string
     */
    const IRISH = "ga";
    /**
     * `string` "it"
     *
     * @var string
     */
    const ITALIAN = "it";
    /**
     * `string` "ja"
     *
     * @var string
     */
    const JAPANESE = "ja";
    /**
     * `string` "kab"
     *
     * @var string
     */
    const KABYLE = "kab";
    /**
     * `string` "kl"
     *
     * @var string
     */
    const KALAALLISUT = "kl";
    /**
     * `string` "kam"
     *
     * @var string
     */
    const KAMBA = "kam";
    /**
     * `string` "kn"
     *
     * @var string
     */
    const KANNADA = "kn";
    /**
     * `string` "kk"
     *
     * @var string
     */
    const KAZAKH = "kk";
    /**
     * `string` "kk_Cyrl"
     *
     * @var string
     */
    const KAZAKH_CYRILLIC = "kk_Cyrl";
    /**
     * `string` "km"
     *
     * @var string
     */
    const KHMER = "km";
    /**
     * `string` "ki"
     *
     * @var string
     */
    const KIKUYU = "ki";
    /**
     * `string` "rw"
     *
     * @var string
     */
    const KINYARWANDA = "rw";
    /**
     * `string` "kok"
     *
     * @var string
     */
    const KONKANI = "kok";
    /**
     * `string` "ko"
     *
     * @var string
     */
    const KOREAN = "ko";
    /**
     * `string` "lv"
     *
     * @var string
     */
    const LATVIAN = "lv";
    /**
     * `string` "ln"
     *
     * @var string
     */
    const LINGALA = "ln";
    /**
     * `string` "lt"
     *
     * @var string
     */
    const LITHUANIAN = "lt";
    /**
     * `string` "lu"
     *
     * @var string
     */
    const LUBA_KATANGA = "lu";
    /**
     * `string` "luo"
     *
     * @var string
     */
    const LUO = "luo";
    /**
     * `string` "mk"
     *
     * @var string
     */
    const MACEDONIAN = "mk";
    /**
     * `string` "mg"
     *
     * @var string
     */
    const MALAGASY = "mg";
    /**
     * `string` "ms"
     *
     * @var string
     */
    const MALAY = "ms";
    /**
     * `string` "ml"
     *
     * @var string
     */
    const MALAYALAM = "ml";
    /**
     * `string` "mt"
     *
     * @var string
     */
    const MALTESE = "mt";
    /**
     * `string` "gv"
     *
     * @var string
     */
    const MANX = "gv";
    /**
     * `string` "mr"
     *
     * @var string
     */
    const MARATHI = "mr";
    /**
     * `string` "mas"
     *
     * @var string
     */
    const MASAI = "mas";
    /**
     * `string` "ne"
     *
     * @var string
     */
    const NEPALI = "ne";
    /**
     * `string` "nd"
     *
     * @var string
     */
    const NORTH_NDEBELE = "nd";
    /**
     * `string` "nb"
     *
     * @var string
     */
    const NORWEGIAN_BOKMAL = "nb";
    /**
     * `string` "nn"
     *
     * @var string
     */
    const NORWEGIAN_NYNORSK = "nn";
    /**
     * `string` "nyn"
     *
     * @var string
     */
    const NYANKOLE = "nyn";
    /**
     * `string` "or"
     *
     * @var string
     */
    const ORIYA = "or";
    /**
     * `string` "om"
     *
     * @var string
     */
    const OROMO = "om";
    /**
     * `string` "ps"
     *
     * @var string
     */
    const PASHTO = "ps";
    /**
     * `string` "fa"
     *
     * @var string
     */
    const PERSIAN = "fa";
    /**
     * `string` "pl"
     *
     * @var string
     */
    const POLISH = "pl";
    /**
     * `string` "pt"
     *
     * @var string
     */
    const PORTUGUESE = "pt";
    /**
     * `string` "pa"
     *
     * @var string
     */
    const PUNJABI = "pa";
    /**
     * `string` "pa_Arab"
     *
     * @var string
     */
    const PUNJABI_ARABIC = "pa_Arab";
    /**
     * `string` "pa_Guru"
     *
     * @var string
     */
    const PUNJABI_GURMUKHI = "pa_Guru";
    /**
     * `string` "ro"
     *
     * @var string
     */
    const ROMANIAN = "ro";
    /**
     * `string` "rm"
     *
     * @var string
     */
    const ROMANSH = "rm";
    /**
     * `string` "rn"
     *
     * @var string
     */
    const RUNDI = "rn";
    /**
     * `string` "ru"
     *
     * @var string
     */
    const RUSSIAN = "ru";
    /**
     * `string` "sg"
     *
     * @var string
     */
    const SANGO = "sg";
    /**
     * `string` "sr"
     *
     * @var string
     */
    const SERBIAN = "sr";
    /**
     * `string` "sr_Cyrl"
     *
     * @var string
     */
    const SERBIAN_CYRILLIC = "sr_Cyrl";
    /**
     * `string` "sr_Latn"
     *
     * @var string
     */
    const SERBIAN_LATIN = "sr_Latn";
    /**
     * `string` "sn"
     *
     * @var string
     */
    const SHONA = "sn";
    /**
     * `string` "ii"
     *
     * @var string
     */
    const SICHUAN_YI = "ii";
    /**
     * `string` "si"
     *
     * @var string
     */
    const SINHALA = "si";
    /**
     * `string` "sk"
     *
     * @var string
     */
    const SLOVAK = "sk";
    /**
     * `string` "sl"
     *
     * @var string
     */
    const SLOVENIAN = "sl";
    /**
     * `string` "so"
     *
     * @var string
     */
    const SOMALI = "so";
    /**
     * `string` "es"
     *
     * @var string
     */
    const SPANISH = "es";
    /**
     * `string` "sw"
     *
     * @var string
     */
    const SWAHILI = "sw";
    /**
     * `string` "sv"
     *
     * @var string
     */
    const SWEDISH = "sv";
    /**
     * `string` "ta"
     *
     * @var string
     */
    const TAMIL = "ta";
    /**
     * `string` "te"
     *
     * @var string
     */
    const TELUGU = "te";
    /**
     * `string` "th"
     *
     * @var string
     */
    const THAI = "th";
    /**
     * `string` "bo"
     *
     * @var string
     */
    const TIBETAN = "bo";
    /**
     * `string` "ti"
     *
     * @var string
     */
    const TIGRINYA = "ti";
    /**
     * `string` "to"
     *
     * @var string
     */
    const TONGAN = "to";
    /**
     * `string` "tr"
     *
     * @var string
     */
    const TURKISH = "tr";
    /**
     * `string` "uk"
     *
     * @var string
     */
    const UKRAINIAN = "uk";
    /**
     * `string` "ur"
     *
     * @var string
     */
    const URDU = "ur";
    /**
     * `string` "uz"
     *
     * @var string
     */
    const UZBEK = "uz";
    /**
     * `string` "uz_Arab"
     *
     * @var string
     */
    const UZBEK_ARABIC = "uz_Arab";
    /**
     * `string` "uz_Cyrl"
     *
     * @var string
     */
    const UZBEK_CYRILLIC = "uz_Cyrl";
    /**
     * `string` "uz_Latn"
     *
     * @var string
     */
    const UZBEK_LATIN = "uz_Latn";
    /**
     * `string` "vai"
     *
     * @var string
     */
    const VAI = "vai";
    /**
     * `string` "vai_Latn"
     *
     * @var string
     */
    const VAI_LATIN = "vai_Latn";
    /**
     * `string` "vai_Vaii"
     *
     * @var string
     */
    const VAI_VAI = "vai_Vaii";
    /**
     * `string` "vi"
     *
     * @var string
     */
    const VIETNAMESE = "vi";
    /**
     * `string` "cy"
     *
     * @var string
     */
    const WELSH = "cy";
    /**
     * `string` "yo"
     *
     * @var string
     */
    const YORUBA = "yo";
    /**
     * `string` "zu"
     *
     * @var string
     */
    const ZULU = "zu";

    // ISO 639 locale codes: the language with the region, possibly with the script.
    /**
     * `string` "af_NA"
     *
     * @var string
     */
    const AFRIKAANS_NAMIBIA = "af_NA";
    /**
     * `string` "af_ZA"
     *
     * @var string
     */
    const AFRIKAANS_SOUTH_AFRICA = "af_ZA";
    /**
     * `string` "ak_GH"
     *
     * @var string
     */
    const AKAN_GHANA = "ak_GH";
    /**
     * `string` "sq_AL"
     *
     * @var string
     */
    const ALBANIAN_ALBANIA = "sq_AL";
    /**
     * `string` "am_ET"
     *
     * @var string
     */
    const AMHARIC_ETHIOPIA = "am_ET";
    /**
     * `string` "ar_DZ"
     *
     * @var string
     */
    const ARABIC_ALGERIA = "ar_DZ";
    /**
     * `string` "ar_BH"
     *
     * @var string
     */
    const ARABIC_BAHRAIN = "ar_BH";
    /**
     * `string` "ar_EG"
     *
     * @var string
     */
    const ARABIC_EGYPT = "ar_EG";
    /**
     * `string` "ar_IQ"
     *
     * @var string
     */
    const ARABIC_IRAQ = "ar_IQ";
    /**
     * `string` "ar_JO"
     *
     * @var string
     */
    const ARABIC_JORDAN = "ar_JO";
    /**
     * `string` "ar_KW"
     *
     * @var string
     */
    const ARABIC_KUWAIT = "ar_KW";
    /**
     * `string` "ar_LB"
     *
     * @var string
     */
    const ARABIC_LEBANON = "ar_LB";
    /**
     * `string` "ar_LY"
     *
     * @var string
     */
    const ARABIC_LIBYA = "ar_LY";
    /**
     * `string` "ar_MA"
     *
     * @var string
     */
    const ARABIC_MOROCCO = "ar_MA";
    /**
     * `string` "ar_OM"
     *
     * @var string
     */
    const ARABIC_OMAN = "ar_OM";
    /**
     * `string` "ar_QA"
     *
     * @var string
     */
    const ARABIC_QATAR = "ar_QA";
    /**
     * `string` "ar_SA"
     *
     * @var string
     */
    const ARABIC_SAUDI_ARABIA = "ar_SA";
    /**
     * `string` "ar_SD"
     *
     * @var string
     */
    const ARABIC_SUDAN = "ar_SD";
    /**
     * `string` "ar_SY"
     *
     * @var string
     */
    const ARABIC_SYRIA = "ar_SY";
    /**
     * `string` "ar_TN"
     *
     * @var string
     */
    const ARABIC_TUNISIA = "ar_TN";
    /**
     * `string` "ar_AE"
     *
     * @var string
     */
    const ARABIC_UNITED_ARAB_EMIRATES = "ar_AE";
    /**
     * `string` "ar_YE"
     *
     * @var string
     */
    const ARABIC_YEMEN = "ar_YE";
    /**
     * `string` "hy_AM"
     *
     * @var string
     */
    const ARMENIAN_ARMENIA = "hy_AM";
    /**
     * `string` "as_IN"
     *
     * @var string
     */
    const ASSAMESE_INDIA = "as_IN";
    /**
     * `string` "az_Cyrl_AZ"
     *
     * @var string
     */
    const AZERBAIJANI_CYRILLIC_AZERBAIJAN = "az_Cyrl_AZ";
    /**
     * `string` "az_Latn_AZ"
     *
     * @var string
     */
    const AZERBAIJANI_LATIN_AZERBAIJAN = "az_Latn_AZ";
    /**
     * `string` "bm_ML"
     *
     * @var string
     */
    const BAMBARA_MALI = "bm_ML";
    /**
     * `string` "bas_CM"
     *
     * @var string
     */
    const BASAA_CAMEROON = "bas_CM";
    /**
     * `string` "eu_ES"
     *
     * @var string
     */
    const BASQUE_SPAIN = "eu_ES";
    /**
     * `string` "be_BY"
     *
     * @var string
     */
    const BELARUSIAN_BELARUS = "be_BY";
    /**
     * `string` "bem_ZM"
     *
     * @var string
     */
    const BEMBA_ZAMBIA = "bem_ZM";
    /**
     * `string` "bn_BD"
     *
     * @var string
     */
    const BENGALI_BANGLADESH = "bn_BD";
    /**
     * `string` "bn_IN"
     *
     * @var string
     */
    const BENGALI_INDIA = "bn_IN";
    /**
     * `string` "bs_BA"
     *
     * @var string
     */
    const BOSNIAN_BOSNIA_AND_HERZEGOVINA = "bs_BA";
    /**
     * `string` "br_FR"
     *
     * @var string
     */
    const BRETON_FRANCE = "br_FR";
    /**
     * `string` "bg_BG"
     *
     * @var string
     */
    const BULGARIAN_BULGARIA = "bg_BG";
    /**
     * `string` "my_MM"
     *
     * @var string
     */
    const BURMESE_MYANMAR = "my_MM";
    /**
     * `string` "ca_ES"
     *
     * @var string
     */
    const CATALAN_SPAIN = "ca_ES";
    /**
     * `string` "chr_US"
     *
     * @var string
     */
    const CHEROKEE_UNITED_STATES = "chr_US";
    /**
     * `string` "zh_Hans_CN"
     *
     * @var string
     */
    const CHINESE_SIMPLIFIED_CHINA = "zh_Hans_CN";
    /**
     * `string` "zh_Hans_HK"
     *
     * @var string
     */
    const CHINESE_SIMPLIFIED_HONG_KONG = "zh_Hans_HK";
    /**
     * `string` "zh_Hans_MO"
     *
     * @var string
     */
    const CHINESE_SIMPLIFIED_MACAU = "zh_Hans_MO";
    /**
     * `string` "zh_Hans_SG"
     *
     * @var string
     */
    const CHINESE_SIMPLIFIED_SINGAPORE = "zh_Hans_SG";
    /**
     * `string` "zh_Hant_HK"
     *
     * @var string
     */
    const CHINESE_TRADITIONAL_HONG_KONG = "zh_Hant_HK";
    /**
     * `string` "zh_Hant_MO"
     *
     * @var string
     */
    const CHINESE_TRADITIONAL_MACAU = "zh_Hant_MO";
    /**
     * `string` "zh_Hant_TW"
     *
     * @var string
     */
    const CHINESE_TRADITIONAL_TAIWAN = "zh_Hant_TW";
    /**
     * `string` "kw_GB"
     *
     * @var string
     */
    const CORNISH_UNITED_KINGDOM = "kw_GB";
    /**
     * `string` "hr_HR"
     *
     * @var string
     */
    const CROATIAN_CROATIA = "hr_HR";
    /**
     * `string` "cs_CZ"
     *
     * @var string
     */
    const CZECH_CZECH_REPUBLIC = "cs_CZ";
    /**
     * `string` "da_DK"
     *
     * @var string
     */
    const DANISH_DENMARK = "da_DK";
    /**
     * `string` "dua_CM"
     *
     * @var string
     */
    const DUALA_CAMEROON = "dua_CM";
    /**
     * `string` "nl_AW"
     *
     * @var string
     */
    const DUTCH_ARUBA = "nl_AW";
    /**
     * `string` "nl_BE"
     *
     * @var string
     */
    const DUTCH_BELGIUM = "nl_BE";
    /**
     * `string` "nl_CW"
     *
     * @var string
     */
    const DUTCH_CURACAO = "nl_CW";
    /**
     * `string` "nl_NL"
     *
     * @var string
     */
    const DUTCH_NETHERLANDS = "nl_NL";
    /**
     * `string` "nl_SX"
     *
     * @var string
     */
    const DUTCH_SINT_MAARTEN = "nl_SX";
    /**
     * `string` "en_AS"
     *
     * @var string
     */
    const ENGLISH_AMERICAN_SAMOA = "en_AS";
    /**
     * `string` "en_AU"
     *
     * @var string
     */
    const ENGLISH_AUSTRALIA = "en_AU";
    /**
     * `string` "en_BB"
     *
     * @var string
     */
    const ENGLISH_BARBADOS = "en_BB";
    /**
     * `string` "en_BE"
     *
     * @var string
     */
    const ENGLISH_BELGIUM = "en_BE";
    /**
     * `string` "en_BZ"
     *
     * @var string
     */
    const ENGLISH_BELIZE = "en_BZ";
    /**
     * `string` "en_BM"
     *
     * @var string
     */
    const ENGLISH_BERMUDA = "en_BM";
    /**
     * `string` "en_BW"
     *
     * @var string
     */
    const ENGLISH_BOTSWANA = "en_BW";
    /**
     * `string` "en_CA"
     *
     * @var string
     */
    const ENGLISH_CANADA = "en_CA";
    /**
     * `string` "en_GU"
     *
     * @var string
     */
    const ENGLISH_GUAM = "en_GU";
    /**
     * `string` "en_GY"
     *
     * @var string
     */
    const ENGLISH_GUYANA = "en_GY";
    /**
     * `string` "en_HK"
     *
     * @var string
     */
    const ENGLISH_HONG_KONG = "en_HK";
    /**
     * `string` "en_IN"
     *
     * @var string
     */
    const ENGLISH_INDIA = "en_IN";
    /**
     * `string` "en_IE"
     *
     * @var string
     */
    const ENGLISH_IRELAND = "en_IE";
    /**
     * `string` "en_JM"
     *
     * @var string
     */
    const ENGLISH_JAMAICA = "en_JM";
    /**
     * `string` "en_MT"
     *
     * @var string
     */
    const ENGLISH_MALTA = "en_MT";
    /**
     * `string` "en_MH"
     *
     * @var string
     */
    const ENGLISH_MARSHALL_ISLANDS = "en_MH";
    /**
     * `string` "en_MU"
     *
     * @var string
     */
    const ENGLISH_MAURITIUS = "en_MU";
    /**
     * `string` "en_NA"
     *
     * @var string
     */
    const ENGLISH_NAMIBIA = "en_NA";
    /**
     * `string` "en_NZ"
     *
     * @var string
     */
    const ENGLISH_NEW_ZEALAND = "en_NZ";
    /**
     * `string` "en_MP"
     *
     * @var string
     */
    const ENGLISH_NORTHERN_MARIANA_ISLANDS = "en_MP";
    /**
     * `string` "en_PK"
     *
     * @var string
     */
    const ENGLISH_PAKISTAN = "en_PK";
    /**
     * `string` "en_PH"
     *
     * @var string
     */
    const ENGLISH_PHILIPPINES = "en_PH";
    /**
     * `string` "en_SG"
     *
     * @var string
     */
    const ENGLISH_SINGAPORE = "en_SG";
    /**
     * `string` "en_ZA"
     *
     * @var string
     */
    const ENGLISH_SOUTH_AFRICA = "en_ZA";
    /**
     * `string` "en_TT"
     *
     * @var string
     */
    const ENGLISH_TRINIDAD_AND_TOBAGO = "en_TT";
    /**
     * `string` "en_UM"
     *
     * @var string
     */
    const ENGLISH_US_MINOR_OUTLYING_ISLANDS = "en_UM";
    /**
     * `string` "en_VI"
     *
     * @var string
     */
    const ENGLISH_US_VIRGIN_ISLANDS = "en_VI";
    /**
     * `string` "en_GB"
     *
     * @var string
     */
    const ENGLISH_UNITED_KINGDOM = "en_GB";
    /**
     * `string` "en_US"
     *
     * @var string
     */
    const ENGLISH_UNITED_STATES = "en_US";
    /**
     * `string` "en_US_POSIX"
     *
     * @var string
     */
    const ENGLISH_UNITED_STATES_COMPUTER = "en_US_POSIX";
    /**
     * `string` "en_ZW"
     *
     * @var string
     */
    const ENGLISH_ZIMBABWE = "en_ZW";
    /**
     * `string` "et_EE"
     *
     * @var string
     */
    const ESTONIAN_ESTONIA = "et_EE";
    /**
     * `string` "ee_GH"
     *
     * @var string
     */
    const EWE_GHANA = "ee_GH";
    /**
     * `string` "ee_TG"
     *
     * @var string
     */
    const EWE_TOGO = "ee_TG";
    /**
     * `string` "ewo_CM"
     *
     * @var string
     */
    const EWONDO_CAMEROON = "ewo_CM";
    /**
     * `string` "fo_FO"
     *
     * @var string
     */
    const FAROESE_FAROE_ISLANDS = "fo_FO";
    /**
     * `string` "fi_FI"
     *
     * @var string
     */
    const FINNISH_FINLAND = "fi_FI";
    /**
     * `string` "fr_BE"
     *
     * @var string
     */
    const FRENCH_BELGIUM = "fr_BE";
    /**
     * `string` "fr_BJ"
     *
     * @var string
     */
    const FRENCH_BENIN = "fr_BJ";
    /**
     * `string` "fr_BF"
     *
     * @var string
     */
    const FRENCH_BURKINA_FASO = "fr_BF";
    /**
     * `string` "fr_BI"
     *
     * @var string
     */
    const FRENCH_BURUNDI = "fr_BI";
    /**
     * `string` "fr_CM"
     *
     * @var string
     */
    const FRENCH_CAMEROON = "fr_CM";
    /**
     * `string` "fr_CA"
     *
     * @var string
     */
    const FRENCH_CANADA = "fr_CA";
    /**
     * `string` "fr_CF"
     *
     * @var string
     */
    const FRENCH_CENTRAL_AFRICAN_REPUBLIC = "fr_CF";
    /**
     * `string` "fr_TD"
     *
     * @var string
     */
    const FRENCH_CHAD = "fr_TD";
    /**
     * `string` "fr_KM"
     *
     * @var string
     */
    const FRENCH_COMOROS = "fr_KM";
    /**
     * `string` "fr_CG"
     *
     * @var string
     */
    const FRENCH_CONGO_BRAZZAVILLE = "fr_CG";
    /**
     * `string` "fr_CD"
     *
     * @var string
     */
    const FRENCH_CONGO_KINSHASA = "fr_CD";
    /**
     * `string` "fr_CI"
     *
     * @var string
     */
    const FRENCH_COTE_DIVOIRE = "fr_CI";
    /**
     * `string` "fr_DJ"
     *
     * @var string
     */
    const FRENCH_DJIBOUTI = "fr_DJ";
    /**
     * `string` "fr_GQ"
     *
     * @var string
     */
    const FRENCH_EQUATORIAL_GUINEA = "fr_GQ";
    /**
     * `string` "fr_FR"
     *
     * @var string
     */
    const FRENCH_FRANCE = "fr_FR";
    /**
     * `string` "fr_GF"
     *
     * @var string
     */
    const FRENCH_FRENCH_GUIANA = "fr_GF";
    /**
     * `string` "fr_GA"
     *
     * @var string
     */
    const FRENCH_GABON = "fr_GA";
    /**
     * `string` "fr_GP"
     *
     * @var string
     */
    const FRENCH_GUADELOUPE = "fr_GP";
    /**
     * `string` "fr_GN"
     *
     * @var string
     */
    const FRENCH_GUINEA = "fr_GN";
    /**
     * `string` "fr_LU"
     *
     * @var string
     */
    const FRENCH_LUXEMBOURG = "fr_LU";
    /**
     * `string` "fr_MG"
     *
     * @var string
     */
    const FRENCH_MADAGASCAR = "fr_MG";
    /**
     * `string` "fr_ML"
     *
     * @var string
     */
    const FRENCH_MALI = "fr_ML";
    /**
     * `string` "fr_MQ"
     *
     * @var string
     */
    const FRENCH_MARTINIQUE = "fr_MQ";
    /**
     * `string` "fr_YT"
     *
     * @var string
     */
    const FRENCH_MAYOTTE = "fr_YT";
    /**
     * `string` "fr_MC"
     *
     * @var string
     */
    const FRENCH_MONACO = "fr_MC";
    /**
     * `string` "fr_NE"
     *
     * @var string
     */
    const FRENCH_NIGER = "fr_NE";
    /**
     * `string` "fr_RE"
     *
     * @var string
     */
    const FRENCH_REUNION = "fr_RE";
    /**
     * `string` "fr_RW"
     *
     * @var string
     */
    const FRENCH_RWANDA = "fr_RW";
    /**
     * `string` "fr_BL"
     *
     * @var string
     */
    const FRENCH_SAINT_BARTHELEMY = "fr_BL";
    /**
     * `string` "fr_MF"
     *
     * @var string
     */
    const FRENCH_SAINT_MARTIN = "fr_MF";
    /**
     * `string` "fr_SN"
     *
     * @var string
     */
    const FRENCH_SENEGAL = "fr_SN";
    /**
     * `string` "fr_CH"
     *
     * @var string
     */
    const FRENCH_SWITZERLAND = "fr_CH";
    /**
     * `string` "fr_TG"
     *
     * @var string
     */
    const FRENCH_TOGO = "fr_TG";
    /**
     * `string` "ff_SN"
     *
     * @var string
     */
    const FULAH_SENEGAL = "ff_SN";
    /**
     * `string` "gl_ES"
     *
     * @var string
     */
    const GALICIAN_SPAIN = "gl_ES";
    /**
     * `string` "lg_UG"
     *
     * @var string
     */
    const GANDA_UGANDA = "lg_UG";
    /**
     * `string` "ka_GE"
     *
     * @var string
     */
    const GEORGIAN_GEORGIA = "ka_GE";
    /**
     * `string` "de_AT"
     *
     * @var string
     */
    const GERMAN_AUSTRIA = "de_AT";
    /**
     * `string` "de_BE"
     *
     * @var string
     */
    const GERMAN_BELGIUM = "de_BE";
    /**
     * `string` "de_DE"
     *
     * @var string
     */
    const GERMAN_GERMANY = "de_DE";
    /**
     * `string` "de_LI"
     *
     * @var string
     */
    const GERMAN_LIECHTENSTEIN = "de_LI";
    /**
     * `string` "de_LU"
     *
     * @var string
     */
    const GERMAN_LUXEMBOURG = "de_LU";
    /**
     * `string` "de_CH"
     *
     * @var string
     */
    const GERMAN_SWITZERLAND = "de_CH";
    /**
     * `string` "el_CY"
     *
     * @var string
     */
    const GREEK_CYPRUS = "el_CY";
    /**
     * `string` "el_GR"
     *
     * @var string
     */
    const GREEK_GREECE = "el_GR";
    /**
     * `string` "gu_IN"
     *
     * @var string
     */
    const GUJARATI_INDIA = "gu_IN";
    /**
     * `string` "ha_Latn_GH"
     *
     * @var string
     */
    const HAUSA_LATIN_GHANA = "ha_Latn_GH";
    /**
     * `string` "ha_Latn_NE"
     *
     * @var string
     */
    const HAUSA_LATIN_NIGER = "ha_Latn_NE";
    /**
     * `string` "ha_Latn_NG"
     *
     * @var string
     */
    const HAUSA_LATIN_NIGERIA = "ha_Latn_NG";
    /**
     * `string` "haw_US"
     *
     * @var string
     */
    const HAWAIIAN_UNITED_STATES = "haw_US";
    /**
     * `string` "he_IL"
     *
     * @var string
     */
    const HEBREW_ISRAEL = "he_IL";
    /**
     * `string` "hi_IN"
     *
     * @var string
     */
    const HINDI_INDIA = "hi_IN";
    /**
     * `string` "hu_HU"
     *
     * @var string
     */
    const HUNGARIAN_HUNGARY = "hu_HU";
    /**
     * `string` "is_IS"
     *
     * @var string
     */
    const ICELANDIC_ICELAND = "is_IS";
    /**
     * `string` "ig_NG"
     *
     * @var string
     */
    const IGBO_NIGERIA = "ig_NG";
    /**
     * `string` "id_ID"
     *
     * @var string
     */
    const INDONESIAN_INDONESIA = "id_ID";
    /**
     * `string` "ga_IE"
     *
     * @var string
     */
    const IRISH_IRELAND = "ga_IE";
    /**
     * `string` "it_IT"
     *
     * @var string
     */
    const ITALIAN_ITALY = "it_IT";
    /**
     * `string` "it_CH"
     *
     * @var string
     */
    const ITALIAN_SWITZERLAND = "it_CH";
    /**
     * `string` "ja_JP"
     *
     * @var string
     */
    const JAPANESE_JAPAN = "ja_JP";
    /**
     * `string` "kab_DZ"
     *
     * @var string
     */
    const KABYLE_ALGERIA = "kab_DZ";
    /**
     * `string` "kl_GL"
     *
     * @var string
     */
    const KALAALLISUT_GREENLAND = "kl_GL";
    /**
     * `string` "kam_KE"
     *
     * @var string
     */
    const KAMBA_KENYA = "kam_KE";
    /**
     * `string` "kn_IN"
     *
     * @var string
     */
    const KANNADA_INDIA = "kn_IN";
    /**
     * `string` "kk_Cyrl_KZ"
     *
     * @var string
     */
    const KAZAKH_CYRILLIC_KAZAKHSTAN = "kk_Cyrl_KZ";
    /**
     * `string` "km_KH"
     *
     * @var string
     */
    const KHMER_CAMBODIA = "km_KH";
    /**
     * `string` "ki_KE"
     *
     * @var string
     */
    const KIKUYU_KENYA = "ki_KE";
    /**
     * `string` "rw_RW"
     *
     * @var string
     */
    const KINYARWANDA_RWANDA = "rw_RW";
    /**
     * `string` "kok_IN"
     *
     * @var string
     */
    const KONKANI_INDIA = "kok_IN";
    /**
     * `string` "ko_KR"
     *
     * @var string
     */
    const KOREAN_SOUTH_KOREA = "ko_KR";
    /**
     * `string` "lv_LV"
     *
     * @var string
     */
    const LATVIAN_LATVIA = "lv_LV";
    /**
     * `string` "ln_CG"
     *
     * @var string
     */
    const LINGALA_CONGO_BRAZZAVILLE = "ln_CG";
    /**
     * `string` "ln_CD"
     *
     * @var string
     */
    const LINGALA_CONGO_KINSHASA = "ln_CD";
    /**
     * `string` "lt_LT"
     *
     * @var string
     */
    const LITHUANIAN_LITHUANIA = "lt_LT";
    /**
     * `string` "lu_CD"
     *
     * @var string
     */
    const LUBA_KATANGA_CONGO_KINSHASA = "lu_CD";
    /**
     * `string` "luo_KE"
     *
     * @var string
     */
    const LUO_KENYA = "luo_KE";
    /**
     * `string` "mk_MK"
     *
     * @var string
     */
    const MACEDONIAN_MACEDONIA = "mk_MK";
    /**
     * `string` "mg_MG"
     *
     * @var string
     */
    const MALAGASY_MADAGASCAR = "mg_MG";
    /**
     * `string` "ms_BN"
     *
     * @var string
     */
    const MALAY_BRUNEI = "ms_BN";
    /**
     * `string` "ms_MY"
     *
     * @var string
     */
    const MALAY_MALAYSIA = "ms_MY";
    /**
     * `string` "ml_IN"
     *
     * @var string
     */
    const MALAYALAM_INDIA = "ml_IN";
    /**
     * `string` "mt_MT"
     *
     * @var string
     */
    const MALTESE_MALTA = "mt_MT";
    /**
     * `string` "gv_GB"
     *
     * @var string
     */
    const MANX_UNITED_KINGDOM = "gv_GB";
    /**
     * `string` "mr_IN"
     *
     * @var string
     */
    const MARATHI_INDIA = "mr_IN";
    /**
     * `string` "mas_KE"
     *
     * @var string
     */
    const MASAI_KENYA = "mas_KE";
    /**
     * `string` "mas_TZ"
     *
     * @var string
     */
    const MASAI_TANZANIA = "mas_TZ";
    /**
     * `string` "ne_IN"
     *
     * @var string
     */
    const NEPALI_INDIA = "ne_IN";
    /**
     * `string` "ne_NP"
     *
     * @var string
     */
    const NEPALI_NEPAL = "ne_NP";
    /**
     * `string` "nd_ZW"
     *
     * @var string
     */
    const NORTH_NDEBELE_ZIMBABWE = "nd_ZW";
    /**
     * `string` "nb_NO"
     *
     * @var string
     */
    const NORWEGIAN_BOKMAL_NORWAY = "nb_NO";
    /**
     * `string` "nn_NO"
     *
     * @var string
     */
    const NORWEGIAN_NYNORSK_NORWAY = "nn_NO";
    /**
     * `string` "nyn_UG"
     *
     * @var string
     */
    const NYANKOLE_UGANDA = "nyn_UG";
    /**
     * `string` "or_IN"
     *
     * @var string
     */
    const ORIYA_INDIA = "or_IN";
    /**
     * `string` "om_ET"
     *
     * @var string
     */
    const OROMO_ETHIOPIA = "om_ET";
    /**
     * `string` "om_KE"
     *
     * @var string
     */
    const OROMO_KENYA = "om_KE";
    /**
     * `string` "ps_AF"
     *
     * @var string
     */
    const PASHTO_AFGHANISTAN = "ps_AF";
    /**
     * `string` "fa_AF"
     *
     * @var string
     */
    const PERSIAN_AFGHANISTAN = "fa_AF";
    /**
     * `string` "fa_IR"
     *
     * @var string
     */
    const PERSIAN_IRAN = "fa_IR";
    /**
     * `string` "pl_PL"
     *
     * @var string
     */
    const POLISH_POLAND = "pl_PL";
    /**
     * `string` "pt_AO"
     *
     * @var string
     */
    const PORTUGUESE_ANGOLA = "pt_AO";
    /**
     * `string` "pt_BR"
     *
     * @var string
     */
    const PORTUGUESE_BRAZIL = "pt_BR";
    /**
     * `string` "pt_GW"
     *
     * @var string
     */
    const PORTUGUESE_GUINEA_BISSAU = "pt_GW";
    /**
     * `string` "pt_MZ"
     *
     * @var string
     */
    const PORTUGUESE_MOZAMBIQUE = "pt_MZ";
    /**
     * `string` "pt_PT"
     *
     * @var string
     */
    const PORTUGUESE_PORTUGAL = "pt_PT";
    /**
     * `string` "pt_ST"
     *
     * @var string
     */
    const PORTUGUESE_SAO_TOME_AND_PRINCIPE = "pt_ST";
    /**
     * `string` "pa_Arab_PK"
     *
     * @var string
     */
    const PUNJABI_ARABIC_PAKISTAN = "pa_Arab_PK";
    /**
     * `string` "pa_Guru_IN"
     *
     * @var string
     */
    const PUNJABI_GURMUKHI_INDIA = "pa_Guru_IN";
    /**
     * `string` "ro_MD"
     *
     * @var string
     */
    const ROMANIAN_MOLDOVA = "ro_MD";
    /**
     * `string` "ro_RO"
     *
     * @var string
     */
    const ROMANIAN_ROMANIA = "ro_RO";
    /**
     * `string` "rm_CH"
     *
     * @var string
     */
    const ROMANSH_SWITZERLAND = "rm_CH";
    /**
     * `string` "rn_BI"
     *
     * @var string
     */
    const RUNDI_BURUNDI = "rn_BI";
    /**
     * `string` "ru_MD"
     *
     * @var string
     */
    const RUSSIAN_MOLDOVA = "ru_MD";
    /**
     * `string` "ru_RU"
     *
     * @var string
     */
    const RUSSIAN_RUSSIA = "ru_RU";
    /**
     * `string` "ru_UA"
     *
     * @var string
     */
    const RUSSIAN_UKRAINE = "ru_UA";
    /**
     * `string` "sg_CF"
     *
     * @var string
     */
    const SANGO_CENTRAL_AFRICAN_REPUBLIC = "sg_CF";
    /**
     * `string` "sr_Cyrl_BA"
     *
     * @var string
     */
    const SERBIAN_CYRILLIC_BOSNIA_AND_HERZEGOVINA = "sr_Cyrl_BA";
    /**
     * `string` "sr_Cyrl_ME"
     *
     * @var string
     */
    const SERBIAN_CYRILLIC_MONTENEGRO = "sr_Cyrl_ME";
    /**
     * `string` "sr_Cyrl_RS"
     *
     * @var string
     */
    const SERBIAN_CYRILLIC_SERBIA = "sr_Cyrl_RS";
    /**
     * `string` "sr_Latn_BA"
     *
     * @var string
     */
    const SERBIAN_LATIN_BOSNIA_AND_HERZEGOVINA = "sr_Latn_BA";
    /**
     * `string` "sr_Latn_ME"
     *
     * @var string
     */
    const SERBIAN_LATIN_MONTENEGRO = "sr_Latn_ME";
    /**
     * `string` "sr_Latn_RS"
     *
     * @var string
     */
    const SERBIAN_LATIN_SERBIA = "sr_Latn_RS";
    /**
     * `string` "sn_ZW"
     *
     * @var string
     */
    const SHONA_ZIMBABWE = "sn_ZW";
    /**
     * `string` "ii_CN"
     *
     * @var string
     */
    const SICHUAN_YI_CHINA = "ii_CN";
    /**
     * `string` "si_LK"
     *
     * @var string
     */
    const SINHALA_SRI_LANKA = "si_LK";
    /**
     * `string` "sk_SK"
     *
     * @var string
     */
    const SLOVAK_SLOVAKIA = "sk_SK";
    /**
     * `string` "sl_SI"
     *
     * @var string
     */
    const SLOVENIAN_SLOVENIA = "sl_SI";
    /**
     * `string` "so_DJ"
     *
     * @var string
     */
    const SOMALI_DJIBOUTI = "so_DJ";
    /**
     * `string` "so_ET"
     *
     * @var string
     */
    const SOMALI_ETHIOPIA = "so_ET";
    /**
     * `string` "so_KE"
     *
     * @var string
     */
    const SOMALI_KENYA = "so_KE";
    /**
     * `string` "so_SO"
     *
     * @var string
     */
    const SOMALI_SOMALIA = "so_SO";
    /**
     * `string` "es_AR"
     *
     * @var string
     */
    const SPANISH_ARGENTINA = "es_AR";
    /**
     * `string` "es_BO"
     *
     * @var string
     */
    const SPANISH_BOLIVIA = "es_BO";
    /**
     * `string` "es_CL"
     *
     * @var string
     */
    const SPANISH_CHILE = "es_CL";
    /**
     * `string` "es_CO"
     *
     * @var string
     */
    const SPANISH_COLOMBIA = "es_CO";
    /**
     * `string` "es_CR"
     *
     * @var string
     */
    const SPANISH_COSTA_RICA = "es_CR";
    /**
     * `string` "es_CU"
     *
     * @var string
     */
    const SPANISH_CUBA = "es_CU";
    /**
     * `string` "es_DO"
     *
     * @var string
     */
    const SPANISH_DOMINICAN_REPUBLIC = "es_DO";
    /**
     * `string` "es_EC"
     *
     * @var string
     */
    const SPANISH_ECUADOR = "es_EC";
    /**
     * `string` "es_SV"
     *
     * @var string
     */
    const SPANISH_EL_SALVADOR = "es_SV";
    /**
     * `string` "es_GQ"
     *
     * @var string
     */
    const SPANISH_EQUATORIAL_GUINEA = "es_GQ";
    /**
     * `string` "es_GT"
     *
     * @var string
     */
    const SPANISH_GUATEMALA = "es_GT";
    /**
     * `string` "es_HN"
     *
     * @var string
     */
    const SPANISH_HONDURAS = "es_HN";
    /**
     * `string` "es_MX"
     *
     * @var string
     */
    const SPANISH_MEXICO = "es_MX";
    /**
     * `string` "es_NI"
     *
     * @var string
     */
    const SPANISH_NICARAGUA = "es_NI";
    /**
     * `string` "es_PA"
     *
     * @var string
     */
    const SPANISH_PANAMA = "es_PA";
    /**
     * `string` "es_PY"
     *
     * @var string
     */
    const SPANISH_PARAGUAY = "es_PY";
    /**
     * `string` "es_PE"
     *
     * @var string
     */
    const SPANISH_PERU = "es_PE";
    /**
     * `string` "es_PR"
     *
     * @var string
     */
    const SPANISH_PUERTO_RICO = "es_PR";
    /**
     * `string` "es_ES"
     *
     * @var string
     */
    const SPANISH_SPAIN = "es_ES";
    /**
     * `string` "es_US"
     *
     * @var string
     */
    const SPANISH_UNITED_STATES = "es_US";
    /**
     * `string` "es_UY"
     *
     * @var string
     */
    const SPANISH_URUGUAY = "es_UY";
    /**
     * `string` "es_VE"
     *
     * @var string
     */
    const SPANISH_VENEZUELA = "es_VE";
    /**
     * `string` "sw_KE"
     *
     * @var string
     */
    const SWAHILI_KENYA = "sw_KE";
    /**
     * `string` "sw_TZ"
     *
     * @var string
     */
    const SWAHILI_TANZANIA = "sw_TZ";
    /**
     * `string` "sv_FI"
     *
     * @var string
     */
    const SWEDISH_FINLAND = "sv_FI";
    /**
     * `string` "sv_SE"
     *
     * @var string
     */
    const SWEDISH_SWEDEN = "sv_SE";
    /**
     * `string` "ta_IN"
     *
     * @var string
     */
    const TAMIL_INDIA = "ta_IN";
    /**
     * `string` "ta_LK"
     *
     * @var string
     */
    const TAMIL_SRI_LANKA = "ta_LK";
    /**
     * `string` "te_IN"
     *
     * @var string
     */
    const TELUGU_INDIA = "te_IN";
    /**
     * `string` "th_TH"
     *
     * @var string
     */
    const THAI_THAILAND = "th_TH";
    /**
     * `string` "bo_CN"
     *
     * @var string
     */
    const TIBETAN_CHINA = "bo_CN";
    /**
     * `string` "bo_IN"
     *
     * @var string
     */
    const TIBETAN_INDIA = "bo_IN";
    /**
     * `string` "ti_ER"
     *
     * @var string
     */
    const TIGRINYA_ERITREA = "ti_ER";
    /**
     * `string` "ti_ET"
     *
     * @var string
     */
    const TIGRINYA_ETHIOPIA = "ti_ET";
    /**
     * `string` "to_TO"
     *
     * @var string
     */
    const TONGAN_TONGA = "to_TO";
    /**
     * `string` "tr_TR"
     *
     * @var string
     */
    const TURKISH_TURKEY = "tr_TR";
    /**
     * `string` "uk_UA"
     *
     * @var string
     */
    const UKRAINIAN_UKRAINE = "uk_UA";
    /**
     * `string` "ur_IN"
     *
     * @var string
     */
    const URDU_INDIA = "ur_IN";
    /**
     * `string` "ur_PK"
     *
     * @var string
     */
    const URDU_PAKISTAN = "ur_PK";
    /**
     * `string` "uz_Arab_AF"
     *
     * @var string
     */
    const UZBEK_ARABIC_AFGHANISTAN = "uz_Arab_AF";
    /**
     * `string` "uz_Cyrl_UZ"
     *
     * @var string
     */
    const UZBEK_CYRILLIC_UZBEKISTAN = "uz_Cyrl_UZ";
    /**
     * `string` "uz_Latn_UZ"
     *
     * @var string
     */
    const UZBEK_LATIN_UZBEKISTAN = "uz_Latn_UZ";
    /**
     * `string` "vai_Latn_LR"
     *
     * @var string
     */
    const VAI_LATIN_LIBERIA = "vai_Latn_LR";
    /**
     * `string` "vai_Vaii_LR"
     *
     * @var string
     */
    const VAI_VAI_LIBERIA = "vai_Vaii_LR";
    /**
     * `string` "vi_VN"
     *
     * @var string
     */
    const VIETNAMESE_VIETNAM = "vi_VN";
    /**
     * `string` "cy_GB"
     *
     * @var string
     */
    const WELSH_UNITED_KINGDOM = "cy_GB";
    /**
     * `string` "yo_NG"
     *
     * @var string
     */
    const YORUBA_NIGERIA = "yo_NG";
    /**
     * `string` "zu_ZA"
     *
     * @var string
     */
    const ZULU_SOUTH_AFRICA = "zu_ZA";

    // ISO 3166 country codes. ISO 639 set of country/region codes is a subset of these codes.
    /**
     * `string` "AF" Afghanistan.
     *
     * @var string
     */
    const AFGHANISTAN = "AF";
    /**
     * `string` "AX" Aland Islands.
     *
     * @var string
     */
    const ALAND_ISLANDS = "AX";
    /**
     * `string` "AL" Albania.
     *
     * @var string
     */
    const ALBANIA = "AL";
    /**
     * `string` "DZ" Algeria.
     *
     * @var string
     */
    const ALGERIA = "DZ";
    /**
     * `string` "AS" American Samoa.
     *
     * @var string
     */
    const AMERICAN_SAMOA = "AS";
    /**
     * `string` "AD" Andorra.
     *
     * @var string
     */
    const ANDORRA = "AD";
    /**
     * `string` "AO" Angola.
     *
     * @var string
     */
    const ANGOLA = "AO";
    /**
     * `string` "AI" Anguilla.
     *
     * @var string
     */
    const ANGUILLA = "AI";
    /**
     * `string` "AQ" Antarctica.
     *
     * @var string
     */
    const ANTARCTICA = "AQ";
    /**
     * `string` "AG" Antigua and Barbuda.
     *
     * @var string
     */
    const ANTIGUA_AND_BARBUDA = "AG";
    /**
     * `string` "AR" Argentina.
     *
     * @var string
     */
    const ARGENTINA = "AR";
    /**
     * `string` "AM" Armenia.
     *
     * @var string
     */
    const ARMENIA = "AM";
    /**
     * `string` "AW" Aruba.
     *
     * @var string
     */
    const ARUBA = "AW";
    /**
     * `string` "AU" Australia.
     *
     * @var string
     */
    const AUSTRALIA = "AU";
    /**
     * `string` "AT" Austria.
     *
     * @var string
     */
    const AUSTRIA = "AT";
    /**
     * `string` "AZ" Azerbaijan.
     *
     * @var string
     */
    const AZERBAIJAN = "AZ";
    /**
     * `string` "BS" Bahamas.
     *
     * @var string
     */
    const BAHAMAS = "BS";
    /**
     * `string` "BH" Bahrain.
     *
     * @var string
     */
    const BAHRAIN = "BH";
    /**
     * `string` "BD" Bangladesh.
     *
     * @var string
     */
    const BANGLADESH = "BD";
    /**
     * `string` "BB" Barbados.
     *
     * @var string
     */
    const BARBADOS = "BB";
    /**
     * `string` "BY" Belarus.
     *
     * @var string
     */
    const BELARUS = "BY";
    /**
     * `string` "BE" Belgium.
     *
     * @var string
     */
    const BELGIUM = "BE";
    /**
     * `string` "BZ" Belize.
     *
     * @var string
     */
    const BELIZE = "BZ";
    /**
     * `string` "BJ" Benin.
     *
     * @var string
     */
    const BENIN = "BJ";
    /**
     * `string` "BM" Bermuda.
     *
     * @var string
     */
    const BERMUDA = "BM";
    /**
     * `string` "BT" Bhutan.
     *
     * @var string
     */
    const BHUTAN = "BT";
    /**
     * `string` "BO" Bolivia.
     *
     * @var string
     */
    const BOLIVIA = "BO";
    /**
     * `string` "BQ" Bonaire, Saint Eustatius and Saba.
     *
     * @var string
     */
    const BONAIRE_SAINT_EUSTATIUS_AND_SABA = "BQ";
    /**
     * `string` "BA" Bosnia and Herzegovina.
     *
     * @var string
     */
    const BOSNIA_AND_HERZEGOVINA = "BA";
    /**
     * `string` "BW" Botswana.
     *
     * @var string
     */
    const BOTSWANA = "BW";
    /**
     * `string` "BV" Bouvet Island.
     *
     * @var string
     */
    const BOUVET_ISLAND = "BV";
    /**
     * `string` "BR" Brazil.
     *
     * @var string
     */
    const BRAZIL = "BR";
    /**
     * `string` "IO" British Indian Ocean Territory.
     *
     * @var string
     */
    const BRITISH_INDIAN_OCEAN_TERRITORY = "IO";
    /**
     * `string` "BN" Brunei Darussalam.
     *
     * @var string
     */
    const BRUNEI = "BN";
    /**
     * `string` "BG" Bulgaria.
     *
     * @var string
     */
    const BULGARIA = "BG";
    /**
     * `string` "BF" Burkina Faso.
     *
     * @var string
     */
    const BURKINA_FASO = "BF";
    /**
     * `string` "BI" Burundi.
     *
     * @var string
     */
    const BURUNDI = "BI";
    /**
     * `string` "KH" Cambodia.
     *
     * @var string
     */
    const CAMBODIA = "KH";
    /**
     * `string` "CM" Cameroon.
     *
     * @var string
     */
    const CAMEROON = "CM";
    /**
     * `string` "CA" Canada.
     *
     * @var string
     */
    const CANADA = "CA";
    /**
     * `string` "CV" Cape Verde.
     *
     * @var string
     */
    const CAPE_VERDE = "CV";
    /**
     * `string` "KY" Cayman Islands.
     *
     * @var string
     */
    const CAYMAN_ISLANDS = "KY";
    /**
     * `string` "CF" Central African Republic.
     *
     * @var string
     */
    const CENTRAL_AFRICAN_REPUBLIC = "CF";
    /**
     * `string` "TD" Chad.
     *
     * @var string
     */
    const CHAD = "TD";
    /**
     * `string` "CL" Chile.
     *
     * @var string
     */
    const CHILE = "CL";
    /**
     * `string` "CN" China.
     *
     * @var string
     */
    const CHINA = "CN";
    /**
     * `string` "CX" Christmas Island.
     *
     * @var string
     */
    const CHRISTMAS_ISLAND = "CX";
    /**
     * `string` "CC" Cocos (Keeling) Islands.
     *
     * @var string
     */
    const COCOS_ISLANDS = "CC";
    /**
     * `string` "CO" Colombia.
     *
     * @var string
     */
    const COLOMBIA = "CO";
    /**
     * `string` "KM" Comoros.
     *
     * @var string
     */
    const COMOROS = "KM";
    /**
     * `string` "CG" Congo.
     *
     * @var string
     */
    const CONGO = "CG";
    /**
     * `string` "CD" Congo, The Democratic Republic of the.
     *
     * @var string
     */
    const CONGO_THE_DEMOCRATIC_REPUBLIC_OF_THE = "CD";
    /**
     * `string` "CK" Cook Islands.
     *
     * @var string
     */
    const COOK_ISLANDS = "CK";
    /**
     * `string` "CR" Costa Rica.
     *
     * @var string
     */
    const COSTA_RICA = "CR";
    /**
     * `string` "CI" Cote d'Ivoire.
     *
     * @var string
     */
    const COTE_DIVOIRE = "CI";
    /**
     * `string` "HR" Croatia.
     *
     * @var string
     */
    const CROATIA = "HR";
    /**
     * `string` "CU" Cuba.
     *
     * @var string
     */
    const CUBA = "CU";
    /**
     * `string` "CW" Curacao.
     *
     * @var string
     */
    const CURACAO = "CW";
    /**
     * `string` "CY" Cyprus.
     *
     * @var string
     */
    const CYPRUS = "CY";
    /**
     * `string` "CZ" Czech Republic.
     *
     * @var string
     */
    const CZECH_REPUBLIC = "CZ";
    /**
     * `string` "DK" Denmark.
     *
     * @var string
     */
    const DENMARK = "DK";
    /**
     * `string` "DJ" Djibouti.
     *
     * @var string
     */
    const DJIBOUTI = "DJ";
    /**
     * `string` "DM" Dominica.
     *
     * @var string
     */
    const DOMINICA = "DM";
    /**
     * `string` "DO" Dominican Republic.
     *
     * @var string
     */
    const DOMINICAN_REPUBLIC = "DO";
    /**
     * `string` "EC" Ecuador.
     *
     * @var string
     */
    const ECUADOR = "EC";
    /**
     * `string` "EG" Egypt.
     *
     * @var string
     */
    const EGYPT = "EG";
    /**
     * `string` "SV" El Salvador.
     *
     * @var string
     */
    const EL_SALVADOR = "SV";
    /**
     * `string` "GQ" Equatorial Guinea.
     *
     * @var string
     */
    const EQUATORIAL_GUINEA = "GQ";
    /**
     * `string` "ER" Eritrea.
     *
     * @var string
     */
    const ERITREA = "ER";
    /**
     * `string` "EE" Estonia.
     *
     * @var string
     */
    const ESTONIA = "EE";
    /**
     * `string` "ET" Ethiopia.
     *
     * @var string
     */
    const ETHIOPIA = "ET";
    /**
     * `string` "FK" Falkland Islands (Malvinas).
     *
     * @var string
     */
    const FALKLAND_ISLANDS = "FK";
    /**
     * `string` "FO" Faroe Islands.
     *
     * @var string
     */
    const FAROE_ISLANDS = "FO";
    /**
     * `string` "FJ" Fiji.
     *
     * @var string
     */
    const FIJI = "FJ";
    /**
     * `string` "FI" Finland.
     *
     * @var string
     */
    const FINLAND = "FI";
    /**
     * `string` "FR" France.
     *
     * @var string
     */
    const FRANCE = "FR";
    /**
     * `string` "GF" French Guiana.
     *
     * @var string
     */
    const FRENCH_GUIANA = "GF";
    /**
     * `string` "PF" French Polynesia.
     *
     * @var string
     */
    const FRENCH_POLYNESIA = "PF";
    /**
     * `string` "TF" French Southern Territories.
     *
     * @var string
     */
    const FRENCH_SOUTHERN_TERRITORIES = "TF";
    /**
     * `string` "GA" Gabon.
     *
     * @var string
     */
    const GABON = "GA";
    /**
     * `string` "GM" Gambia.
     *
     * @var string
     */
    const GAMBIA = "GM";
    /**
     * `string` "GE" Georgia.
     *
     * @var string
     */
    const GEORGIA = "GE";
    /**
     * `string` "DE" Germany.
     *
     * @var string
     */
    const GERMANY = "DE";
    /**
     * `string` "GH" Ghana.
     *
     * @var string
     */
    const GHANA = "GH";
    /**
     * `string` "GI" Gibraltar.
     *
     * @var string
     */
    const GIBRALTAR = "GI";
    /**
     * `string` "GR" Greece.
     *
     * @var string
     */
    const GREECE = "GR";
    /**
     * `string` "GL" Greenland.
     *
     * @var string
     */
    const GREENLAND = "GL";
    /**
     * `string` "GD" Grenada.
     *
     * @var string
     */
    const GRENADA = "GD";
    /**
     * `string` "GP" Guadeloupe.
     *
     * @var string
     */
    const GUADELOUPE = "GP";
    /**
     * `string` "GU" Guam.
     *
     * @var string
     */
    const GUAM = "GU";
    /**
     * `string` "GT" Guatemala.
     *
     * @var string
     */
    const GUATEMALA = "GT";
    /**
     * `string` "GG" Guernsey.
     *
     * @var string
     */
    const GUERNSEY = "GG";
    /**
     * `string` "GN" Guinea.
     *
     * @var string
     */
    const GUINEA = "GN";
    /**
     * `string` "GW" Guinea-Bissau.
     *
     * @var string
     */
    const GUINEA_BISSAU = "GW";
    /**
     * `string` "GY" Guyana.
     *
     * @var string
     */
    const GUYANA = "GY";
    /**
     * `string` "HT" Haiti.
     *
     * @var string
     */
    const HAITI = "HT";
    /**
     * `string` "HM" Heard Island and McDonald Islands.
     *
     * @var string
     */
    const HEARD_ISLAND_AND_MCDONALD_ISLANDS = "HM";
    /**
     * `string` "HN" Honduras.
     *
     * @var string
     */
    const HONDURAS = "HN";
    /**
     * `string` "HK" Hong Kong.
     *
     * @var string
     */
    const HONG_KONG = "HK";
    /**
     * `string` "HU" Hungary.
     *
     * @var string
     */
    const HUNGARY = "HU";
    /**
     * `string` "IS" Iceland.
     *
     * @var string
     */
    const ICELAND = "IS";
    /**
     * `string` "IN" India.
     *
     * @var string
     */
    const INDIA = "IN";
    /**
     * `string` "ID" Indonesia.
     *
     * @var string
     */
    const INDONESIA = "ID";
    /**
     * `string` "IR" Iran, Islamic Republic of.
     *
     * @var string
     */
    const IRAN_ISLAMIC_REPUBLIC_OF = "IR";
    /**
     * `string` "IQ" Iraq.
     *
     * @var string
     */
    const IRAQ = "IQ";
    /**
     * `string` "IE" Ireland.
     *
     * @var string
     */
    const IRELAND = "IE";
    /**
     * `string` "IM" Isle of Man.
     *
     * @var string
     */
    const ISLE_OF_MAN = "IM";
    /**
     * `string` "IL" Israel.
     *
     * @var string
     */
    const ISRAEL = "IL";
    /**
     * `string` "IT" Italy.
     *
     * @var string
     */
    const ITALY = "IT";
    /**
     * `string` "JM" Jamaica.
     *
     * @var string
     */
    const JAMAICA = "JM";
    /**
     * `string` "JP" Japan.
     *
     * @var string
     */
    const JAPAN = "JP";
    /**
     * `string` "JE" Jersey.
     *
     * @var string
     */
    const JERSEY = "JE";
    /**
     * `string` "JO" Jordan.
     *
     * @var string
     */
    const JORDAN = "JO";
    /**
     * `string` "KZ" Kazakhstan.
     *
     * @var string
     */
    const KAZAKHSTAN = "KZ";
    /**
     * `string` "KE" Kenya.
     *
     * @var string
     */
    const KENYA = "KE";
    /**
     * `string` "KI" Kiribati.
     *
     * @var string
     */
    const KIRIBATI = "KI";
    /**
     * `string` "KP" Korea, Democratic People's Republic of.
     *
     * @var string
     */
    const KOREA_DEMOCRATIC_PEOPLES_REPUBLIC_OF = "KP";
    /**
     * `string` "KR" Korea, Republic of.
     *
     * @var string
     */
    const KOREA_REPUBLIC_OF = "KR";
    /**
     * `string` "KW" Kuwait.
     *
     * @var string
     */
    const KUWAIT = "KW";
    /**
     * `string` "KG" Kyrgyzstan.
     *
     * @var string
     */
    const KYRGYZSTAN = "KG";
    /**
     * `string` "LA" Lao People's Democratic Republic.
     *
     * @var string
     */
    const LAO_PEOPLES_DEMOCRATIC_REPUBLIC = "LA";
    /**
     * `string` "LV" Latvia.
     *
     * @var string
     */
    const LATVIA = "LV";
    /**
     * `string` "LB" Lebanon.
     *
     * @var string
     */
    const LEBANON = "LB";
    /**
     * `string` "LS" Lesotho.
     *
     * @var string
     */
    const LESOTHO = "LS";
    /**
     * `string` "LR" Liberia.
     *
     * @var string
     */
    const LIBERIA = "LR";
    /**
     * `string` "LY" Libyan Arab Jamahiriya.
     *
     * @var string
     */
    const LIBYAN_ARAB_JAMAHIRIYA = "LY";
    /**
     * `string` "LI" Liechtenstein.
     *
     * @var string
     */
    const LIECHTENSTEIN = "LI";
    /**
     * `string` "LT" Lithuania.
     *
     * @var string
     */
    const LITHUANIA = "LT";
    /**
     * `string` "LU" Luxembourg.
     *
     * @var string
     */
    const LUXEMBOURG = "LU";
    /**
     * `string` "MO" Macau.
     *
     * @var string
     */
    const MACAU = "MO";
    /**
     * `string` "MK" Macedonia.
     *
     * @var string
     */
    const MACEDONIA = "MK";
    /**
     * `string` "MG" Madagascar.
     *
     * @var string
     */
    const MADAGASCAR = "MG";
    /**
     * `string` "MW" Malawi.
     *
     * @var string
     */
    const MALAWI = "MW";
    /**
     * `string` "MY" Malaysia.
     *
     * @var string
     */
    const MALAYSIA = "MY";
    /**
     * `string` "MV" Maldives.
     *
     * @var string
     */
    const MALDIVES = "MV";
    /**
     * `string` "ML" Mali.
     *
     * @var string
     */
    const MALI = "ML";
    /**
     * `string` "MT" Malta.
     *
     * @var string
     */
    const MALTA = "MT";
    /**
     * `string` "MH" Marshall Islands.
     *
     * @var string
     */
    const MARSHALL_ISLANDS = "MH";
    /**
     * `string` "MQ" Martinique.
     *
     * @var string
     */
    const MARTINIQUE = "MQ";
    /**
     * `string` "MR" Mauritania.
     *
     * @var string
     */
    const MAURITANIA = "MR";
    /**
     * `string` "MU" Mauritius.
     *
     * @var string
     */
    const MAURITIUS = "MU";
    /**
     * `string` "YT" Mayotte.
     *
     * @var string
     */
    const MAYOTTE = "YT";
    /**
     * `string` "MX" Mexico.
     *
     * @var string
     */
    const MEXICO = "MX";
    /**
     * `string` "FM" Micronesia, Federated States of.
     *
     * @var string
     */
    const MICRONESIA_FEDERATED_STATES_OF = "FM";
    /**
     * `string` "MD" Moldova, Republic of.
     *
     * @var string
     */
    const MOLDOVA_REPUBLIC_OF = "MD";
    /**
     * `string` "MC" Monaco.
     *
     * @var string
     */
    const MONACO = "MC";
    /**
     * `string` "MN" Mongolia.
     *
     * @var string
     */
    const MONGOLIA = "MN";
    /**
     * `string` "ME" Montenegro.
     *
     * @var string
     */
    const MONTENEGRO = "ME";
    /**
     * `string` "MS" Montserrat.
     *
     * @var string
     */
    const MONTSERRAT = "MS";
    /**
     * `string` "MA" Morocco.
     *
     * @var string
     */
    const MOROCCO = "MA";
    /**
     * `string` "MZ" Mozambique.
     *
     * @var string
     */
    const MOZAMBIQUE = "MZ";
    /**
     * `string` "MM" Myanmar.
     *
     * @var string
     */
    const MYANMAR = "MM";
    /**
     * `string` "NA" Namibia.
     *
     * @var string
     */
    const NAMIBIA = "NA";
    /**
     * `string` "NR" Nauru.
     *
     * @var string
     */
    const NAURU = "NR";
    /**
     * `string` "NP" Nepal.
     *
     * @var string
     */
    const NEPAL = "NP";
    /**
     * `string` "NL" Netherlands.
     *
     * @var string
     */
    const NETHERLANDS = "NL";
    /**
     * `string` "NC" New Caledonia.
     *
     * @var string
     */
    const NEW_CALEDONIA = "NC";
    /**
     * `string` "NZ" New Zealand.
     *
     * @var string
     */
    const NEW_ZEALAND = "NZ";
    /**
     * `string` "NI" Nicaragua.
     *
     * @var string
     */
    const NICARAGUA = "NI";
    /**
     * `string` "NE" Niger.
     *
     * @var string
     */
    const NIGER = "NE";
    /**
     * `string` "NG" Nigeria.
     *
     * @var string
     */
    const NIGERIA = "NG";
    /**
     * `string` "NU" Niue.
     *
     * @var string
     */
    const NIUE = "NU";
    /**
     * `string` "NF" Norfolk Island.
     *
     * @var string
     */
    const NORFOLK_ISLAND = "NF";
    /**
     * `string` "MP" Northern Mariana Islands.
     *
     * @var string
     */
    const NORTHERN_MARIANA_ISLANDS = "MP";
    /**
     * `string` "NO" Norway.
     *
     * @var string
     */
    const NORWAY = "NO";
    /**
     * `string` "OM" Oman.
     *
     * @var string
     */
    const OMAN = "OM";
    /**
     * `string` "PK" Pakistan.
     *
     * @var string
     */
    const PAKISTAN = "PK";
    /**
     * `string` "PW" Palau.
     *
     * @var string
     */
    const PALAU = "PW";
    /**
     * `string` "PS" Palestinian Territory.
     *
     * @var string
     */
    const PALESTINIAN_TERRITORY = "PS";
    /**
     * `string` "PA" Panama.
     *
     * @var string
     */
    const PANAMA = "PA";
    /**
     * `string` "PG" Papua New Guinea.
     *
     * @var string
     */
    const PAPUA_NEW_GUINEA = "PG";
    /**
     * `string` "PY" Paraguay.
     *
     * @var string
     */
    const PARAGUAY = "PY";
    /**
     * `string` "PE" Peru.
     *
     * @var string
     */
    const PERU = "PE";
    /**
     * `string` "PH" Philippines.
     *
     * @var string
     */
    const PHILIPPINES = "PH";
    /**
     * `string` "PN" Pitcairn.
     *
     * @var string
     */
    const PITCAIRN = "PN";
    /**
     * `string` "PL" Poland.
     *
     * @var string
     */
    const POLAND = "PL";
    /**
     * `string` "PT" Portugal.
     *
     * @var string
     */
    const PORTUGAL = "PT";
    /**
     * `string` "PR" Puerto Rico.
     *
     * @var string
     */
    const PUERTO_RICO = "PR";
    /**
     * `string` "QA" Qatar.
     *
     * @var string
     */
    const QATAR = "QA";
    /**
     * `string` "RE" Reunion.
     *
     * @var string
     */
    const REUNION = "RE";
    /**
     * `string` "RO" Romania.
     *
     * @var string
     */
    const ROMANIA = "RO";
    /**
     * `string` "RU" Russian Federation.
     *
     * @var string
     */
    const RUSSIAN_FEDERATION = "RU";
    /**
     * `string` "RW" Rwanda.
     *
     * @var string
     */
    const RWANDA = "RW";
    /**
     * `string` "BL" Saint Bartelemey.
     *
     * @var string
     */
    const SAINT_BARTELEMEY = "BL";
    /**
     * `string` "SH" Saint Helena.
     *
     * @var string
     */
    const SAINT_HELENA = "SH";
    /**
     * `string` "KN" Saint Kitts and Nevis.
     *
     * @var string
     */
    const SAINT_KITTS_AND_NEVIS = "KN";
    /**
     * `string` "LC" Saint Lucia.
     *
     * @var string
     */
    const SAINT_LUCIA = "LC";
    /**
     * `string` "MF" Saint Martin.
     *
     * @var string
     */
    const SAINT_MARTIN = "MF";
    /**
     * `string` "PM" Saint Pierre and Miquelon.
     *
     * @var string
     */
    const SAINT_PIERRE_AND_MIQUELON = "PM";
    /**
     * `string` "VC" Saint Vincent and the Grenadines.
     *
     * @var string
     */
    const SAINT_VINCENT_AND_THE_GRENADINES = "VC";
    /**
     * `string` "WS" Samoa.
     *
     * @var string
     */
    const SAMOA = "WS";
    /**
     * `string` "SM" San Marino.
     *
     * @var string
     */
    const SAN_MARINO = "SM";
    /**
     * `string` "ST" Sao Tome and Principe.
     *
     * @var string
     */
    const SAO_TOME_AND_PRINCIPE = "ST";
    /**
     * `string` "SA" Saudi Arabia.
     *
     * @var string
     */
    const SAUDI_ARABIA = "SA";
    /**
     * `string` "SN" Senegal.
     *
     * @var string
     */
    const SENEGAL = "SN";
    /**
     * `string` "RS" Serbia.
     *
     * @var string
     */
    const SERBIA = "RS";
    /**
     * `string` "SC" Seychelles.
     *
     * @var string
     */
    const SEYCHELLES = "SC";
    /**
     * `string` "SL" Sierra Leone.
     *
     * @var string
     */
    const SIERRA_LEONE = "SL";
    /**
     * `string` "SG" Singapore.
     *
     * @var string
     */
    const SINGAPORE = "SG";
    /**
     * `string` "SX" Sint Maarten.
     *
     * @var string
     */
    const SINT_MAARTEN = "SX";
    /**
     * `string` "SK" Slovakia.
     *
     * @var string
     */
    const SLOVAKIA = "SK";
    /**
     * `string` "SI" Slovenia.
     *
     * @var string
     */
    const SLOVENIA = "SI";
    /**
     * `string` "SB" Solomon Islands.
     *
     * @var string
     */
    const SOLOMON_ISLANDS = "SB";
    /**
     * `string` "SO" Somalia.
     *
     * @var string
     */
    const SOMALIA = "SO";
    /**
     * `string` "ZA" South Africa.
     *
     * @var string
     */
    const SOUTH_AFRICA = "ZA";
    /**
     * `string` "GS" South Georgia and the South Sandwich Islands.
     *
     * @var string
     */
    const SOUTH_GEORGIA_AND_THE_SOUTH_SANDWICH_ISLANDS = "GS";
    /**
     * `string` "SS" South Sudan.
     *
     * @var string
     */
    const SOUTH_SUDAN = "SS";
    /**
     * `string` "ES" Spain.
     *
     * @var string
     */
    const SPAIN = "ES";
    /**
     * `string` "LK" Sri Lanka.
     *
     * @var string
     */
    const SRI_LANKA = "LK";
    /**
     * `string` "SD" Sudan.
     *
     * @var string
     */
    const SUDAN = "SD";
    /**
     * `string` "SR" Suriname.
     *
     * @var string
     */
    const SURINAME = "SR";
    /**
     * `string` "SJ" Svalbard and Jan Mayen.
     *
     * @var string
     */
    const SVALBARD_AND_JAN_MAYEN = "SJ";
    /**
     * `string` "SZ" Swaziland.
     *
     * @var string
     */
    const SWAZILAND = "SZ";
    /**
     * `string` "SE" Sweden.
     *
     * @var string
     */
    const SWEDEN = "SE";
    /**
     * `string` "CH" Switzerland.
     *
     * @var string
     */
    const SWITZERLAND = "CH";
    /**
     * `string` "SY" Syrian Arab Republic.
     *
     * @var string
     */
    const SYRIAN_ARAB_REPUBLIC = "SY";
    /**
     * `string` "TW" Taiwan.
     *
     * @var string
     */
    const TAIWAN = "TW";
    /**
     * `string` "TJ" Tajikistan.
     *
     * @var string
     */
    const TAJIKISTAN = "TJ";
    /**
     * `string` "TZ" Tanzania, United Republic of.
     *
     * @var string
     */
    const TANZANIA_UNITED_REPUBLIC_OF = "TZ";
    /**
     * `string` "TH" Thailand.
     *
     * @var string
     */
    const THAILAND = "TH";
    /**
     * `string` "TL" Timor-Leste.
     *
     * @var string
     */
    const TIMOR_LESTE = "TL";
    /**
     * `string` "TG" Togo.
     *
     * @var string
     */
    const TOGO = "TG";
    /**
     * `string` "TK" Tokelau.
     *
     * @var string
     */
    const TOKELAU = "TK";
    /**
     * `string` "TO" Tonga.
     *
     * @var string
     */
    const TONGA = "TO";
    /**
     * `string` "TT" Trinidad and Tobago.
     *
     * @var string
     */
    const TRINIDAD_AND_TOBAGO = "TT";
    /**
     * `string` "TN" Tunisia.
     *
     * @var string
     */
    const TUNISIA = "TN";
    /**
     * `string` "TR" Turkey.
     *
     * @var string
     */
    const TURKEY = "TR";
    /**
     * `string` "TM" Turkmenistan.
     *
     * @var string
     */
    const TURKMENISTAN = "TM";
    /**
     * `string` "TC" Turks and Caicos Islands.
     *
     * @var string
     */
    const TURKS_AND_CAICOS_ISLANDS = "TC";
    /**
     * `string` "TV" Tuvalu.
     *
     * @var string
     */
    const TUVALU = "TV";
    /**
     * `string` "UG" Uganda.
     *
     * @var string
     */
    const UGANDA = "UG";
    /**
     * `string` "UA" Ukraine.
     *
     * @var string
     */
    const UKRAINE = "UA";
    /**
     * `string` "AE" United Arab Emirates.
     *
     * @var string
     */
    const UNITED_ARAB_EMIRATES = "AE";
    /**
     * `string` "GB" United Kingdom.
     *
     * @var string
     */
    const UNITED_KINGDOM = "GB";
    /**
     * `string` "US" United States.
     *
     * @var string
     */
    const UNITED_STATES = "US";
    /**
     * `string` "UM" United States Minor Outlying Islands.
     *
     * @var string
     */
    const UNITED_STATES_MINOR_OUTLYING_ISLANDS = "UM";
    /**
     * `string` "UY" Uruguay.
     *
     * @var string
     */
    const URUGUAY = "UY";
    /**
     * `string` "UZ" Uzbekistan.
     *
     * @var string
     */
    const UZBEKISTAN = "UZ";
    /**
     * `string` "VU" Vanuatu.
     *
     * @var string
     */
    const VANUATU = "VU";
    /**
     * `string` "VA" Holy See (Vatican City State).
     *
     * @var string
     */
    const VATICAN_CITY_STATE = "VA";
    /**
     * `string` "VE" Venezuela.
     *
     * @var string
     */
    const VENEZUELA = "VE";
    /**
     * `string` "VN" Vietnam.
     *
     * @var string
     */
    const VIETNAM = "VN";
    /**
     * `string` "VG" Virgin Islands, British.
     *
     * @var string
     */
    const VIRGIN_ISLANDS_BRITISH = "VG";
    /**
     * `string` "VI" Virgin Islands, U.S..
     *
     * @var string
     */
    const VIRGIN_ISLANDS_US = "VI";
    /**
     * `string` "WF" Wallis and Futuna.
     *
     * @var string
     */
    const WALLIS_AND_FUTUNA = "WF";
    /**
     * `string` "EH" Western Sahara.
     *
     * @var string
     */
    const WESTERN_SAHARA = "EH";
    /**
     * `string` "YE" Yemen.
     *
     * @var string
     */
    const YEMEN = "YE";
    /**
     * `string` "ZM" Zambia.
     *
     * @var string
     */
    const ZAMBIA = "ZM";
    /**
     * `string` "ZW" Zimbabwe.
     *
     * @var string
     */
    const ZIMBABWE = "ZW";

    /**
     * `string` "USD" The code of the default currency.
     *
     * @var string
     */
    const DEFAULT_CURRENCY = "USD";

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a locale from a locale name.
     *
     * @param  string $sLocaleName The name of the locale (case-insensitive).
     */

    public function __construct ($sLocaleName)
    {
        assert( 'is_cstring($sLocaleName)', vs(isset($this), get_defined_vars()) );
        assert( 'self::isValid($sLocaleName)', vs(isset($this), get_defined_vars()) );

        $this->m_sName = Locale::canonicalize($sLocaleName);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a locale from a country/region code.
     *
     * @param  string $sCode The two-letter ISO 3166 (or ISO 639) country/region code (case-insensitive).
     *
     * @return CULocale The new locale.
     */

    public static function fromCountryCode ($sCode)
    {
        assert( 'is_cstring($sCode)', vs(isset($this), get_defined_vars()) );
        return new self(DULocale::$CountryCodeToInfo[CString::toUpperCase($sCode)]["locale"]);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a locale from a language code and, optionally, a country/region code, a script code, and variants.
     *
     * If the locale is to indicate any variants, the variants follow the script code's argument when calling this
     * method.
     *
     * @param  string $sLanguage The two-letter ISO 639 language code (case-insensitive).
     * @param  string $sRegion **OPTIONAL. Default is** *none*. The two-letter ISO 3166 (or ISO 639) country/region
     * code (case-insensitive).
     * @param  string $sScript **OPTIONAL. Default is** *none*. The four-letter script code (case-insensitive).
     *
     * @return CULocale The new locale.
     */

    public static function fromComponents ($sLanguage, $sRegion = null, $sScript = null/*, variants...*/)
    {
        assert( 'is_cstring($sLanguage) && (!isset($sRegion) || is_cstring($sRegion)) && ' .
                '(!isset($sScript) || is_cstring($sScript))', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($sLanguage, "/^[a-z]{2,3}\\\\z/i")', vs(isset($this), get_defined_vars()) );
        assert( '!isset($sRegion) || CRegex::find($sRegion, "/^[a-z]{2,3}\\\\z/i")',
            vs(isset($this), get_defined_vars()) );
        assert( '!isset($sScript) || CRegex::find($sScript, "/^[a-z]{4}\\\\z/i")',
            vs(isset($this), get_defined_vars()) );
        $iFuncNumArgs = func_num_args();
        assert( '$iFuncNumArgs - 3 <= 15', vs(isset($this), get_defined_vars()) );  // the variants are limited by 15

        $mSubtags = CMap::make();
        $mSubtags[Locale::LANG_TAG] = $sLanguage;
        if ( isset($sRegion) )
        {
            $mSubtags[Locale::REGION_TAG] = $sRegion;
        }
        if ( isset($sScript) )
        {
            $mSubtags[Locale::SCRIPT_TAG] = $sScript;
        }
        $iVariantIdx = 0;
        for ($i = 3; $i < func_num_args(); $i++)
        {
            $sVariant = func_get_arg($i);
            assert( 'is_cstring($sVariant)', vs(isset($this), get_defined_vars()) );
            $mSubtags[Locale::VARIANT_TAG . $iVariantIdx] = $sVariant;
            $iVariantIdx++;
        }
        $sLocaleName = Locale::composeLocale($mSubtags);
        return new self($sLocaleName);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tries to create a locale based on the value of an HTTP "Accept-Language" header.
     *
     * @param  string $sLocaleName The name of the locale (case-insensitive, possibly with "-" used instead of "_").
     * @param  reference $rbSuccess **OPTIONAL. OUTPUT.** After an object is constructed, this parameter tells whether
     * the provided string was successfully parsed as a valid locale name.
     *
     * @return CULocale The new locale.
     */

    public static function fromRfc2616 ($sLocaleName, &$rbSuccess = null)
    {
        assert( 'is_cstring($sLocaleName)', vs(isset($this), get_defined_vars()) );

        $sResLocaleName = Locale::acceptFromHttp($sLocaleName);
        if ( is_cstring($sResLocaleName) )
        {
            $rbSuccess = true;
            return new self($sResLocaleName);
        }
        else
        {
            $rbSuccess = false;
            return self::makeDefault();
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an instance of the application's default locale.
     *
     * @return CULocale The new locale.
     */

    public static function makeDefault ()
    {
        return new self(self::defaultLocaleName());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the name of a locale.
     *
     * @return CUStringObject The locale's name.
     */

    public function name ()
    {
        return $this->m_sName;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the language code of a locale.
     *
     * @return CUStringObject The locale's two-letter language code (always lowercased).
     */

    public function languageCode ()
    {
        return Locale::getPrimaryLanguage($this->m_sName);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a locale has a country/region code.
     *
     * @return bool `true` if the locale has a country/region code, `false` otherwise.
     */

    public function hasRegionCode ()
    {
        return !CString::isEmpty(Locale::getRegion($this->m_sName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the country/region code of a locale.
     *
     * @return CUStringObject The locale's two-letter country/region code (always uppercased).
     */

    public function regionCode ()
    {
        assert( '$this->hasRegionCode()', vs(isset($this), get_defined_vars()) );
        return Locale::getRegion($this->m_sName);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a locale has a script code.
     *
     * @return bool `true` if the locale has a script code, `false` otherwise.
     */

    public function hasScriptCode ()
    {
        return !CString::isEmpty(Locale::getScript($this->m_sName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the script code of a locale.
     *
     * @return CUStringObject The locale's four-letter script code (always titlecased).
     */

    public function scriptCode ()
    {
        assert( '$this->hasScriptCode()', vs(isset($this), get_defined_vars()) );
        return Locale::getScript($this->m_sName);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a locale has any variants.
     *
     * @return bool `true` if the locale has any variants, `false` otherwise.
     */

    public function hasVariants ()
    {
        $mVariants = Locale::getAllVariants($this->m_sName);
        return ( is_cmap($mVariants) && !CMap::isEmpty($mVariants) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the variants of a locale.
     *
     * @return CArrayObject The locale's variants of type `CUStringObject` (always uppercased).
     */

    public function variants ()
    {
        assert( '$this->hasVariants()', vs(isset($this), get_defined_vars()) );

        $mVariants = Locale::getAllVariants($this->m_sName);
        if ( is_cmap($mVariants) )
        {
            return oop_a(CArray::fromPArray($mVariants));
        }
        else
        {
            return oop_a(CArray::make());
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a locale has any keyword-value pairs.
     *
     * @return bool `true` if the locale has any keyword-value pairs, `false` otherwise.
     */

    public function hasKeywords ()
    {
        $mKeywords = Locale::getKeywords($this->m_sName);
        return ( is_cmap($mKeywords) && !CMap::isEmpty($mKeywords) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the keyword-value pairs of a locale.
     *
     * @return CMapObject The locale's keyword-value pairs, with values of type `CUStringObject`.
     */

    public function keywords ()
    {
        assert( '$this->hasKeywords()', vs(isset($this), get_defined_vars()) );

        $mKeywords = Locale::getKeywords($this->m_sName);
        return oop_m(( is_cmap($mKeywords) ) ? $mKeywords : CMap::make());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Parses a locale into components.
     *
     * @param  reference $rsLanguage The two-letter language code of type `CUStringObject` (always lowercased).
     * @param  reference $rsRegion **OPTIONAL. OUTPUT.** The two-letter country/region code of type `CUStringObject`
     * (always uppercased).
     * @param  reference $rsScript **OPTIONAL. OUTPUT.** The four-letter script code of type `CUStringObject` (always
     * titlecased).
     * @param  reference $raVariants **OPTIONAL. OUTPUT.** The variants of type `CArrayObject` with elements of type
     * `CUStringObject` (always uppercased).
     * @param  reference $rmKeywords **OPTIONAL. OUTPUT.** The keyword-value pairs of type `CMapObject` with values of
     * type `CUStringObject`.
     *
     * @return void
     */

    public function components (&$rsLanguage, &$rsRegion = null, &$rsScript = null, &$raVariants = null,
        &$rmKeywords = null)
    {
        $mSubtags = Locale::parseLocale($this->m_sName);
        if ( CMap::hasKey($mSubtags, Locale::LANG_TAG) )
        {
            $rsLanguage = $mSubtags[Locale::LANG_TAG];
        }
        if ( CMap::hasKey($mSubtags, Locale::REGION_TAG) )
        {
            $rsRegion = $mSubtags[Locale::REGION_TAG];
        }
        if ( CMap::hasKey($mSubtags, Locale::SCRIPT_TAG) )
        {
            $rsScript = $mSubtags[Locale::SCRIPT_TAG];
        }
        $raVariants = CArray::make();
        for ($i = 0; $i < 15; $i++)
        {
            $sKey = Locale::VARIANT_TAG . $i;
            if ( CMap::hasKey($mSubtags, $sKey) )
            {
                CArray::push($raVariants, $mSubtags[$sKey]);
            }
        }
        $raVariants = oop_a($raVariants);
        $rmKeywords = oop_m($this->keywords($this->m_sName));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the name of a locale after localizing it in the default or some other locale.
     *
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * name is to be localized.
     *
     * @return CUStringObject The locale's localized name.
     */

    public function dispName (CULocale $oInLocale = null)
    {
        $sInLocale = ( isset($oInLocale) ) ? $oInLocale->m_sName : self::defaultLocaleName();
        return Locale::getDisplayName($this->m_sName, $sInLocale);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the language of a locale after localizing it in the default or some other locale.
     *
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * language is to be localized.
     *
     * @return CUStringObject The locale's localized language.
     */

    public function dispLanguage (CULocale $oInLocale = null)
    {
        $sInLocale = ( isset($oInLocale) ) ? $oInLocale->m_sName : self::defaultLocaleName();
        return Locale::getDisplayLanguage($this->m_sName, $sInLocale);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the country/region of a locale after localizing it in the default or some other locale.
     *
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * country/region is to be localized.
     *
     * @return CUStringObject The locale's localized country/region.
     */

    public function dispRegion (CULocale $oInLocale = null)
    {
        $sInLocale = ( isset($oInLocale) ) ? $oInLocale->m_sName : self::defaultLocaleName();
        return Locale::getDisplayRegion($this->m_sName, $sInLocale);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the script of a locale after localizing it in the default or some other locale.
     *
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * script is to be localized.
     *
     * @return CUStringObject The locale's localized script.
     */

    public function dispScript (CULocale $oInLocale = null)
    {
        $sInLocale = ( isset($oInLocale) ) ? $oInLocale->m_sName : self::defaultLocaleName();
        return Locale::getDisplayScript($this->m_sName, $sInLocale);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the variants of a locale after localizing them in the default or some other locale.
     *
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * variants are to be localized.
     *
     * @return CUStringObject The locale's localized variants, as a single string.
     */

    public function dispVariants (CULocale $oInLocale = null)
    {
        $sInLocale = ( isset($oInLocale) ) ? $oInLocale->m_sName : self::defaultLocaleName();
        return Locale::getDisplayVariant($this->m_sName, $sInLocale);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds a keyword-value pair to a locale.
     *
     * @param  string $sKeyword The keyword.
     * @param  string $sValue The value.
     *
     * @return void
     */

    public function addKeyword ($sKeyword, $sValue)
    {
        assert( 'is_cstring($sKeyword) && is_cstring($sValue)', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($sKeyword, "/^\\\\w+\\\\z/")', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($sValue, "/^\\\\w+\\\\z/")', vs(isset($this), get_defined_vars()) );

        $this->m_sName .=
            (( !CString::find($this->m_sName, "@") ) ? "@" : ";") . CString::toLowerCase($sKeyword) . "=" . $sValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a locale is equal to another locale, comparing them by normalized names.
     *
     * @param  CULocale $oToLocale The second locale for comparison.
     *
     * @return bool `true` if *this* locale is equal to the second locale, `false` otherwise.
     */

    public function equals ($oToLocale)
    {
        // Parameter type hinting is not used for the purpose of interface compatibility.
        assert( 'is_culocale($oToLocale)', vs(isset($this), get_defined_vars()) );
        return CString::equalsCi($this->m_sName, $oToLocale->m_sName);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the known country/region codes as an array.
     *
     * @return CArrayObject The known country/region codes of type `CUStringObject`.
     */

    public static function knownCountryCodes ()
    {
        return oop_a(CMap::keys(DULocale::$CountryCodeToInfo));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a country/region code is known.
     *
     * @param  string $sCode The two-letter ISO 3166 (or ISO 639) country/region code to be looked for
     * .
     *
     * @return bool `true` if the country/region code is known, `false` otherwise.
     */

    public static function isCountryCodeKnown ($sCode)
    {
        assert( 'is_cstring($sCode)', vs(isset($this), get_defined_vars()) );
        return CMap::hasKey(DULocale::$CountryCodeToInfo, CString::toUpperCase($sCode));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the country name for a specified country/region code, in English.
     *
     * @param  string $sCode The two-letter ISO 3166 (or ISO 639) country/region code (case-insensitive).
     *
     * @return CUStringObject The country name for the country/region code.
     */

    public static function countryEnNameForCountryCode ($sCode)
    {
        assert( 'is_cstring($sCode)', vs(isset($this), get_defined_vars()) );
        return DULocale::$CountryCodeToInfo[CString::toUpperCase($sCode)]["enName"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the code of the currency that is default for a specified country/region code.
     *
     * @param  string $sCode The two-letter ISO 3166 (or ISO 639) country/region code (case-insensitive).
     *
     * @return CUStringObject The three-letter currency code for the country/region code.
     */

    public static function currencyForCountryCode ($sCode)
    {
        assert( 'is_cstring($sCode)', vs(isset($this), get_defined_vars()) );

        $sCode = CString::toUpperCase($sCode);
        $oLocale = self::fromCountryCode($sCode);
        if ( !$oLocale->hasRegionCode() )
        {
            return self::DEFAULT_CURRENCY;
        }
        $oNumberFormatter = new NumberFormatter($oLocale->m_sName, NumberFormatter::CURRENCY);
        return $oNumberFormatter->getSymbol(NumberFormatter::INTL_CURRENCY_SYMBOL);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a locale name is valid and known.
     *
     * Scripts, variants, and keyword-value pairs are ignored.
     *
     * @param  string $sLocaleName The locale name to be looked into.
     *
     * @return bool `true` if the locale name is valid and known, `false` otherwise.
     */

    public static function isValid ($sLocaleName)
    {
        assert( 'is_cstring($sLocaleName)', vs(isset($this), get_defined_vars()) );

        if ( !CRegex::findGroups($sLocaleName,
             "/^([a-z]{2,3}(?![^_\\-]))(?|[_\\-]([a-z]{2,3}(?![^_\\-]))|[_\\-][a-z]{4}(?![^_\\-])[_\\-]([a-z]{2,3}" .
             "(?![^_\\-]))|(?:\\z|[_\\-][a-z])).*\\z(?<=[a-z0-9])/i", $aFoundGroups) )
        {
            return false;
        }

        $sRfc2616 = $aFoundGroups[0];
        if ( CArray::length($aFoundGroups) > 1 )
        {
            $sRfc2616 .= "-" . $aFoundGroups[1];
        }
        return is_cstring(Locale::acceptFromHttp($sRfc2616));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the name of the application's default locale.
     *
     * @return CUStringObject The name of the application's default locale.
     */

    public static function defaultLocaleName ()
    {
        return CConfiguration::appOption("defaultLocale");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_sName;
}
