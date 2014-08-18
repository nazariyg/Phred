<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class DULocale
{
    // The mapping of the country/region code to the default locale and other associated information.
    //
    // Mostly usable for finding out the default language of service when only the geo-location is known. For some
    // geopolitical reasons, a small number of country/region codes are mapped to a locale with a different
    // country/region:
    //   Aland Islands (AX -> FI)
    //   Bhutan (BT -> CN)
    //   Christmas Island (CX -> AU)
    //   Cook Islands (CK -> NZ)
    //   Gibraltar (GI -> GB)
    //   Guernsey (GG -> GB)
    //   Isle of Man (IM -> GB)
    //   Jersey (JE -> GB)
    //   Niue (NU -> NZ)
    //   Norfolk Island (NF -> AU)
    //   Saint Pierre and Miquelon (PM -> CA)
    //   Svalbard and Jan Mayen (SJ -> NO)
    //   Tokelau (TK -> NZ)
    //
    // Countries/regions that, for the lack of the corresponding locale, got mapped to a locale with just the language
    // and without region information:
    //   Afghanistan (Pashto)
    //   Andorra (Catalan)
    //   Anguilla (English)
    //   Antarctica (English)
    //   Antigua and Barbuda (English)
    //   Bahamas (English)
    //   Bonaire, Saint Eustatius and Saba (Dutch)
    //   Bouvet Island (Norwegian Nynorsk)
    //   British Indian Ocean Territory (English)
    //   Cape Verde (Portuguese)
    //   Cayman Islands (English)
    //   Cocos (Keeling) Islands (English)
    //   Dominica (English)
    //   Falkland Islands (Malvinas) (English)
    //   Fiji (English)
    //   French Polynesia (French)
    //   French Southern Territories (French)
    //   Gambia (English)
    //   Ghana (English)
    //   Grenada (English)
    //   Haiti (French)
    //   Heard Island and McDonald Islands (English)
    //   Kiribati (English)
    //   Korea, Democratic People's Republic of (Korean)
    //   Kyrgyzstan (Russian)
    //   Lao People's Democratic Republic (English)
    //   Lesotho (English)
    //   Liberia (English)
    //   Malawi (English)
    //   Maldives (English)
    //   Mauritania (Arabic)
    //   Micronesia, Federated States of (English)
    //   Mongolia (English)
    //   Montserrat (English)
    //   Nauru (English)
    //   New Caledonia (French)
    //   Nigeria (English)
    //   Palau (English)
    //   Palestinian Territory (Arabic)
    //   Papua New Guinea (English)
    //   Pitcairn (English)
    //   Saint Bartelemey (French)
    //   Saint Helena (English)
    //   Saint Kitts and Nevis (English)
    //   Saint Lucia (English)
    //   Saint Vincent and the Grenadines (English)
    //   Samoa (English)
    //   San Marino (Italian)
    //   Seychelles (English)
    //   Sierra Leone (English)
    //   Solomon Islands (English)
    //   South Georgia and the South Sandwich Islands (English)
    //   South Sudan (English)
    //   Suriname (Dutch)
    //   Swaziland (English)
    //   Tajikistan (Russian)
    //   Timor-Leste (Portuguese)
    //   Turkmenistan (Russian)
    //   Turks and Caicos Islands (English)
    //   Tuvalu (English)
    //   Vanuatu (English)
    //   Holy See (Vatican City State) (Italian)
    //   Virgin Islands, British (English)
    //   Wallis and Futuna (French)
    //   Western Sahara (Spanish)
    //   Zambia (English)
    //
    public static $CountryCodeToInfo = [
        CULocale::AFGHANISTAN => [
            "enName" => "Afghanistan",
            "locale" => CULocale::PASHTO],
        CULocale::ALAND_ISLANDS => [
            "enName" => "Aland Islands",
            "locale" => CULocale::SWEDISH_FINLAND],
        CULocale::ALBANIA => [
            "enName" => "Albania",
            "locale" => CULocale::ALBANIAN_ALBANIA],
        CULocale::ALGERIA => [
            "enName" => "Algeria",
            "locale" => CULocale::ARABIC_ALGERIA],
        CULocale::AMERICAN_SAMOA => [
            "enName" => "American Samoa",
            "locale" => CULocale::ENGLISH_AMERICAN_SAMOA],
        CULocale::ANDORRA => [
            "enName" => "Andorra",
            "locale" => CULocale::CATALAN],
        CULocale::ANGOLA => [
            "enName" => "Angola",
            "locale" => CULocale::PORTUGUESE_ANGOLA],
        CULocale::ANGUILLA => [
            "enName" => "Anguilla",
            "locale" => CULocale::ENGLISH],
        CULocale::ANTARCTICA => [
            "enName" => "Antarctica",
            "locale" => CULocale::ENGLISH],
        CULocale::ANTIGUA_AND_BARBUDA => [
            "enName" => "Antigua and Barbuda",
            "locale" => CULocale::ENGLISH],
        CULocale::ARGENTINA => [
            "enName" => "Argentina",
            "locale" => CULocale::SPANISH_ARGENTINA],
        CULocale::ARMENIA => [
            "enName" => "Armenia",
            "locale" => CULocale::ARMENIAN_ARMENIA],
        CULocale::ARUBA => [
            "enName" => "Aruba",
            "locale" => CULocale::DUTCH_ARUBA],
        CULocale::AUSTRALIA => [
            "enName" => "Australia",
            "locale" => CULocale::ENGLISH_AUSTRALIA],
        CULocale::AUSTRIA => [
            "enName" => "Austria",
            "locale" => CULocale::GERMAN_AUSTRIA],
        CULocale::AZERBAIJAN => [
            "enName" => "Azerbaijan",
            "locale" => CULocale::AZERBAIJANI_LATIN_AZERBAIJAN],
        CULocale::BAHAMAS => [
            "enName" => "Bahamas",
            "locale" => CULocale::ENGLISH],
        CULocale::BAHRAIN => [
            "enName" => "Bahrain",
            "locale" => CULocale::ARABIC_BAHRAIN],
        CULocale::BANGLADESH => [
            "enName" => "Bangladesh",
            "locale" => CULocale::BENGALI_BANGLADESH],
        CULocale::BARBADOS => [
            "enName" => "Barbados",
            "locale" => CULocale::ENGLISH_BARBADOS],
        CULocale::BELARUS => [
            "enName" => "Belarus",
            "locale" => CULocale::BELARUSIAN_BELARUS],
        CULocale::BELGIUM => [
            "enName" => "Belgium",
            "locale" => CULocale::FRENCH_BELGIUM],
        CULocale::BELIZE => [
            "enName" => "Belize",
            "locale" => CULocale::ENGLISH_BELIZE],
        CULocale::BENIN => [
            "enName" => "Benin",
            "locale" => CULocale::FRENCH_BENIN],
        CULocale::BERMUDA => [
            "enName" => "Bermuda",
            "locale" => CULocale::ENGLISH_BERMUDA],
        CULocale::BHUTAN => [
            "enName" => "Bhutan",
            "locale" => CULocale::TIBETAN_CHINA],
        CULocale::BOLIVIA => [
            "enName" => "Bolivia",
            "locale" => CULocale::SPANISH_BOLIVIA],
        CULocale::BONAIRE_SAINT_EUSTATIUS_AND_SABA => [
            "enName" => "Bonaire, Saint Eustatius and Saba",
            "locale" => CULocale::DUTCH],
        CULocale::BOSNIA_AND_HERZEGOVINA => [
            "enName" => "Bosnia and Herzegovina",
            "locale" => CULocale::BOSNIAN_BOSNIA_AND_HERZEGOVINA],
        CULocale::BOTSWANA => [
            "enName" => "Botswana",
            "locale" => CULocale::ENGLISH_BOTSWANA],
        CULocale::BOUVET_ISLAND => [
            "enName" => "Bouvet Island",
            "locale" => CULocale::NORWEGIAN_NYNORSK],
        CULocale::BRAZIL => [
            "enName" => "Brazil",
            "locale" => CULocale::PORTUGUESE_BRAZIL],
        CULocale::BRITISH_INDIAN_OCEAN_TERRITORY => [
            "enName" => "British Indian Ocean Territory",
            "locale" => CULocale::ENGLISH],
        CULocale::BRUNEI => [
            "enName" => "Brunei",
            "locale" => CULocale::MALAY_BRUNEI],
        CULocale::BULGARIA => [
            "enName" => "Bulgaria",
            "locale" => CULocale::BULGARIAN_BULGARIA],
        CULocale::BURKINA_FASO => [
            "enName" => "Burkina Faso",
            "locale" => CULocale::FRENCH_BURKINA_FASO],
        CULocale::BURUNDI => [
            "enName" => "Burundi",
            "locale" => CULocale::RUNDI_BURUNDI],
        CULocale::CAMBODIA => [
            "enName" => "Cambodia",
            "locale" => CULocale::KHMER_CAMBODIA],
        CULocale::CAMEROON => [
            "enName" => "Cameroon",
            "locale" => CULocale::FRENCH_CAMEROON],
        CULocale::CANADA => [
            "enName" => "Canada",
            "locale" => CULocale::ENGLISH_CANADA],
        CULocale::CAPE_VERDE => [
            "enName" => "Cape Verde",
            "locale" => CULocale::PORTUGUESE],
        CULocale::CAYMAN_ISLANDS => [
            "enName" => "Cayman Islands",
            "locale" => CULocale::ENGLISH],
        CULocale::CENTRAL_AFRICAN_REPUBLIC => [
            "enName" => "Central African Republic",
            "locale" => CULocale::FRENCH_CENTRAL_AFRICAN_REPUBLIC],
        CULocale::CHAD => [
            "enName" => "Chad",
            "locale" => CULocale::FRENCH_CHAD],
        CULocale::CHILE => [
            "enName" => "Chile",
            "locale" => CULocale::SPANISH_CHILE],
        CULocale::CHINA => [
            "enName" => "China",
            "locale" => CULocale::CHINESE_SIMPLIFIED_CHINA],
        CULocale::CHRISTMAS_ISLAND => [
            "enName" => "Christmas Island",
            "locale" => CULocale::ENGLISH_AUSTRALIA],
        CULocale::COCOS_ISLANDS => [
            "enName" => "Cocos (Keeling) Islands",
            "locale" => CULocale::ENGLISH],
        CULocale::COLOMBIA => [
            "enName" => "Colombia",
            "locale" => CULocale::SPANISH_COLOMBIA],
        CULocale::COMOROS => [
            "enName" => "Comoros",
            "locale" => CULocale::FRENCH_COMOROS],
        CULocale::CONGO => [
            "enName" => "Congo",
            "locale" => CULocale::FRENCH_CONGO_BRAZZAVILLE],
        CULocale::CONGO_THE_DEMOCRATIC_REPUBLIC_OF_THE => [
            "enName" => "Congo, The Democratic Republic of the",
            "locale" => CULocale::FRENCH_CONGO_KINSHASA],
        CULocale::COOK_ISLANDS => [
            "enName" => "Cook Islands",
            "locale" => CULocale::ENGLISH_NEW_ZEALAND],
        CULocale::COSTA_RICA => [
            "enName" => "Costa Rica",
            "locale" => CULocale::SPANISH_COSTA_RICA],
        CULocale::COTE_DIVOIRE => [
            "enName" => "Cote d'Ivoire",
            "locale" => CULocale::FRENCH_COTE_DIVOIRE],
        CULocale::CROATIA => [
            "enName" => "Croatia",
            "locale" => CULocale::CROATIAN_CROATIA],
        CULocale::CUBA => [
            "enName" => "Cuba",
            "locale" => CULocale::SPANISH_CUBA],
        CULocale::CURACAO => [
            "enName" => "Curacao",
            "locale" => CULocale::DUTCH_CURACAO],
        CULocale::CYPRUS => [
            "enName" => "Cyprus",
            "locale" => CULocale::GREEK_CYPRUS],
        CULocale::CZECH_REPUBLIC => [
            "enName" => "Czech Republic",
            "locale" => CULocale::CZECH_CZECH_REPUBLIC],
        CULocale::DENMARK => [
            "enName" => "Denmark",
            "locale" => CULocale::DANISH_DENMARK],
        CULocale::DJIBOUTI => [
            "enName" => "Djibouti",
            "locale" => CULocale::FRENCH_DJIBOUTI],
        CULocale::DOMINICA => [
            "enName" => "Dominica",
            "locale" => CULocale::ENGLISH],
        CULocale::DOMINICAN_REPUBLIC => [
            "enName" => "Dominican Republic",
            "locale" => CULocale::SPANISH_DOMINICAN_REPUBLIC],
        CULocale::ECUADOR => [
            "enName" => "Ecuador",
            "locale" => CULocale::SPANISH_ECUADOR],
        CULocale::EGYPT => [
            "enName" => "Egypt",
            "locale" => CULocale::ARABIC_EGYPT],
        CULocale::EL_SALVADOR => [
            "enName" => "El Salvador",
            "locale" => CULocale::SPANISH_EL_SALVADOR],
        CULocale::EQUATORIAL_GUINEA => [
            "enName" => "Equatorial Guinea",
            "locale" => CULocale::SPANISH_EQUATORIAL_GUINEA],
        CULocale::ERITREA => [
            "enName" => "Eritrea",
            "locale" => CULocale::TIGRINYA_ERITREA],
        CULocale::ESTONIA => [
            "enName" => "Estonia",
            "locale" => CULocale::ESTONIAN_ESTONIA],
        CULocale::ETHIOPIA => [
            "enName" => "Ethiopia",
            "locale" => CULocale::AMHARIC_ETHIOPIA],
        CULocale::FALKLAND_ISLANDS => [
            "enName" => "Falkland Islands (Malvinas)",
            "locale" => CULocale::ENGLISH],
        CULocale::FAROE_ISLANDS => [
            "enName" => "Faroe Islands",
            "locale" => CULocale::FAROESE_FAROE_ISLANDS],
        CULocale::FIJI => [
            "enName" => "Fiji",
            "locale" => CULocale::ENGLISH],
        CULocale::FINLAND => [
            "enName" => "Finland",
            "locale" => CULocale::FINNISH_FINLAND],
        CULocale::FRANCE => [
            "enName" => "France",
            "locale" => CULocale::FRENCH_FRANCE],
        CULocale::FRENCH_GUIANA => [
            "enName" => "French Guiana",
            "locale" => CULocale::FRENCH_FRENCH_GUIANA],
        CULocale::FRENCH_POLYNESIA => [
            "enName" => "French Polynesia",
            "locale" => CULocale::FRENCH],
        CULocale::FRENCH_SOUTHERN_TERRITORIES => [
            "enName" => "French Southern Territories",
            "locale" => CULocale::FRENCH],
        CULocale::GABON => [
            "enName" => "Gabon",
            "locale" => CULocale::FRENCH_GABON],
        CULocale::GAMBIA => [
            "enName" => "Gambia",
            "locale" => CULocale::ENGLISH],
        CULocale::GEORGIA => [
            "enName" => "Georgia",
            "locale" => CULocale::GEORGIAN_GEORGIA],
        CULocale::GERMANY => [
            "enName" => "Germany",
            "locale" => CULocale::GERMAN_GERMANY],
        CULocale::GHANA => [
            "enName" => "Ghana",
            "locale" => CULocale::ENGLISH],
        CULocale::GIBRALTAR => [
            "enName" => "Gibraltar",
            "locale" => CULocale::ENGLISH_UNITED_KINGDOM],
        CULocale::GREECE => [
            "enName" => "Greece",
            "locale" => CULocale::GREEK_GREECE],
        CULocale::GREENLAND => [
            "enName" => "Greenland",
            "locale" => CULocale::KALAALLISUT_GREENLAND],
        CULocale::GRENADA => [
            "enName" => "Grenada",
            "locale" => CULocale::ENGLISH],
        CULocale::GUADELOUPE => [
            "enName" => "Guadeloupe",
            "locale" => CULocale::FRENCH_GUADELOUPE],
        CULocale::GUAM => [
            "enName" => "Guam",
            "locale" => CULocale::ENGLISH_GUAM],
        CULocale::GUATEMALA => [
            "enName" => "Guatemala",
            "locale" => CULocale::SPANISH_GUATEMALA],
        CULocale::GUERNSEY => [
            "enName" => "Guernsey",
            "locale" => CULocale::ENGLISH_UNITED_KINGDOM],
        CULocale::GUINEA => [
            "enName" => "Guinea",
            "locale" => CULocale::FRENCH_GUINEA],
        CULocale::GUINEA_BISSAU => [
            "enName" => "Guinea-Bissau",
            "locale" => CULocale::PORTUGUESE_GUINEA_BISSAU],
        CULocale::GUYANA => [
            "enName" => "Guyana",
            "locale" => CULocale::ENGLISH_GUYANA],
        CULocale::HAITI => [
            "enName" => "Haiti",
            "locale" => CULocale::FRENCH],
        CULocale::HEARD_ISLAND_AND_MCDONALD_ISLANDS => [
            "enName" => "Heard Island and McDonald Islands",
            "locale" => CULocale::ENGLISH],
        CULocale::HONDURAS => [
            "enName" => "Honduras",
            "locale" => CULocale::SPANISH_HONDURAS],
        CULocale::HONG_KONG => [
            "enName" => "Hong Kong",
            "locale" => CULocale::CHINESE_TRADITIONAL_HONG_KONG],
        CULocale::HUNGARY => [
            "enName" => "Hungary",
            "locale" => CULocale::HUNGARIAN_HUNGARY],
        CULocale::ICELAND => [
            "enName" => "Iceland",
            "locale" => CULocale::ICELANDIC_ICELAND],
        CULocale::INDIA => [
            "enName" => "India",
            "locale" => CULocale::HINDI_INDIA],
        CULocale::INDONESIA => [
            "enName" => "Indonesia",
            "locale" => CULocale::INDONESIAN_INDONESIA],
        CULocale::IRAN_ISLAMIC_REPUBLIC_OF => [
            "enName" => "Iran, Islamic Republic of",
            "locale" => CULocale::PERSIAN_IRAN],
        CULocale::IRAQ => [
            "enName" => "Iraq",
            "locale" => CULocale::ARABIC_IRAQ],
        CULocale::IRELAND => [
            "enName" => "Ireland",
            "locale" => CULocale::ENGLISH_IRELAND],
        CULocale::ISLE_OF_MAN => [
            "enName" => "Isle of Man",
            "locale" => CULocale::ENGLISH_UNITED_KINGDOM],
        CULocale::ISRAEL => [
            "enName" => "Israel",
            "locale" => CULocale::HEBREW_ISRAEL],
        CULocale::ITALY => [
            "enName" => "Italy",
            "locale" => CULocale::ITALIAN_ITALY],
        CULocale::JAMAICA => [
            "enName" => "Jamaica",
            "locale" => CULocale::ENGLISH_JAMAICA],
        CULocale::JAPAN => [
            "enName" => "Japan",
            "locale" => CULocale::JAPANESE_JAPAN],
        CULocale::JERSEY => [
            "enName" => "Jersey",
            "locale" => CULocale::ENGLISH_UNITED_KINGDOM],
        CULocale::JORDAN => [
            "enName" => "Jordan",
            "locale" => CULocale::ARABIC_JORDAN],
        CULocale::KAZAKHSTAN => [
            "enName" => "Kazakhstan",
            "locale" => CULocale::KAZAKH_CYRILLIC_KAZAKHSTAN],
        CULocale::KENYA => [
            "enName" => "Kenya",
            "locale" => CULocale::SWAHILI_KENYA],
        CULocale::KIRIBATI => [
            "enName" => "Kiribati",
            "locale" => CULocale::ENGLISH],
        CULocale::KOREA_DEMOCRATIC_PEOPLES_REPUBLIC_OF => [
            "enName" => "Korea, Democratic People's Republic of",
            "locale" => CULocale::KOREAN],
        CULocale::KOREA_REPUBLIC_OF => [
            "enName" => "Korea, Republic of",
            "locale" => CULocale::KOREAN_SOUTH_KOREA],
        CULocale::KUWAIT => [
            "enName" => "Kuwait",
            "locale" => CULocale::ARABIC_KUWAIT],
        CULocale::KYRGYZSTAN => [
            "enName" => "Kyrgyzstan",
            "locale" => CULocale::RUSSIAN],
        CULocale::LAO_PEOPLES_DEMOCRATIC_REPUBLIC => [
            "enName" => "Lao People's Democratic Republic",
            "locale" => CULocale::ENGLISH],
        CULocale::LATVIA => [
            "enName" => "Latvia",
            "locale" => CULocale::LATVIAN_LATVIA],
        CULocale::LEBANON => [
            "enName" => "Lebanon",
            "locale" => CULocale::ARABIC_LEBANON],
        CULocale::LESOTHO => [
            "enName" => "Lesotho",
            "locale" => CULocale::ENGLISH],
        CULocale::LIBERIA => [
            "enName" => "Liberia",
            "locale" => CULocale::ENGLISH],
        CULocale::LIBYAN_ARAB_JAMAHIRIYA => [
            "enName" => "Libyan Arab Jamahiriya",
            "locale" => CULocale::ARABIC_LIBYA],
        CULocale::LIECHTENSTEIN => [
            "enName" => "Liechtenstein",
            "locale" => CULocale::GERMAN_LIECHTENSTEIN],
        CULocale::LITHUANIA => [
            "enName" => "Lithuania",
            "locale" => CULocale::LITHUANIAN_LITHUANIA],
        CULocale::LUXEMBOURG => [
            "enName" => "Luxembourg",
            "locale" => CULocale::FRENCH_LUXEMBOURG],
        CULocale::MACAU => [
            "enName" => "Macau",
            "locale" => CULocale::CHINESE_TRADITIONAL_MACAU],
        CULocale::MACEDONIA => [
            "enName" => "Macedonia",
            "locale" => CULocale::MACEDONIAN_MACEDONIA],
        CULocale::MADAGASCAR => [
            "enName" => "Madagascar",
            "locale" => CULocale::MALAGASY_MADAGASCAR],
        CULocale::MALAWI => [
            "enName" => "Malawi",
            "locale" => CULocale::ENGLISH],
        CULocale::MALAYSIA => [
            "enName" => "Malaysia",
            "locale" => CULocale::MALAY_MALAYSIA],
        CULocale::MALDIVES => [
            "enName" => "Maldives",
            "locale" => CULocale::ENGLISH],
        CULocale::MALI => [
            "enName" => "Mali",
            "locale" => CULocale::FRENCH_MALI],
        CULocale::MALTA => [
            "enName" => "Malta",
            "locale" => CULocale::MALTESE_MALTA],
        CULocale::MARSHALL_ISLANDS => [
            "enName" => "Marshall Islands",
            "locale" => CULocale::ENGLISH_MARSHALL_ISLANDS],
        CULocale::MARTINIQUE => [
            "enName" => "Martinique",
            "locale" => CULocale::FRENCH_MARTINIQUE],
        CULocale::MAURITANIA => [
            "enName" => "Mauritania",
            "locale" => CULocale::ARABIC],
        CULocale::MAURITIUS => [
            "enName" => "Mauritius",
            "locale" => CULocale::ENGLISH_MAURITIUS],
        CULocale::MAYOTTE => [
            "enName" => "Mayotte",
            "locale" => CULocale::FRENCH_MAYOTTE],
        CULocale::MEXICO => [
            "enName" => "Mexico",
            "locale" => CULocale::SPANISH_MEXICO],
        CULocale::MICRONESIA_FEDERATED_STATES_OF => [
            "enName" => "Micronesia, Federated States of",
            "locale" => CULocale::ENGLISH],
        CULocale::MOLDOVA_REPUBLIC_OF => [
            "enName" => "Moldova, Republic of",
            "locale" => CULocale::ROMANIAN_MOLDOVA],
        CULocale::MONACO => [
            "enName" => "Monaco",
            "locale" => CULocale::FRENCH_MONACO],
        CULocale::MONGOLIA => [
            "enName" => "Mongolia",
            "locale" => CULocale::ENGLISH],
        CULocale::MONTENEGRO => [
            "enName" => "Montenegro",
            "locale" => CULocale::SERBIAN_LATIN_MONTENEGRO],
        CULocale::MONTSERRAT => [
            "enName" => "Montserrat",
            "locale" => CULocale::ENGLISH],
        CULocale::MOROCCO => [
            "enName" => "Morocco",
            "locale" => CULocale::ARABIC_MOROCCO],
        CULocale::MOZAMBIQUE => [
            "enName" => "Mozambique",
            "locale" => CULocale::PORTUGUESE_MOZAMBIQUE],
        CULocale::MYANMAR => [
            "enName" => "Myanmar",
            "locale" => CULocale::BURMESE_MYANMAR],
        CULocale::NAMIBIA => [
            "enName" => "Namibia",
            "locale" => CULocale::ENGLISH_NAMIBIA],
        CULocale::NAURU => [
            "enName" => "Nauru",
            "locale" => CULocale::ENGLISH],
        CULocale::NEPAL => [
            "enName" => "Nepal",
            "locale" => CULocale::NEPALI_NEPAL],
        CULocale::NETHERLANDS => [
            "enName" => "Netherlands",
            "locale" => CULocale::DUTCH_NETHERLANDS],
        CULocale::NEW_CALEDONIA => [
            "enName" => "New Caledonia",
            "locale" => CULocale::FRENCH],
        CULocale::NEW_ZEALAND => [
            "enName" => "New Zealand",
            "locale" => CULocale::ENGLISH_NEW_ZEALAND],
        CULocale::NICARAGUA => [
            "enName" => "Nicaragua",
            "locale" => CULocale::SPANISH_NICARAGUA],
        CULocale::NIGER => [
            "enName" => "Niger",
            "locale" => CULocale::FRENCH_NIGER],
        CULocale::NIGERIA => [
            "enName" => "Nigeria",
            "locale" => CULocale::ENGLISH],
        CULocale::NIUE => [
            "enName" => "Niue",
            "locale" => CULocale::ENGLISH_NEW_ZEALAND],
        CULocale::NORFOLK_ISLAND => [
            "enName" => "Norfolk Island",
            "locale" => CULocale::ENGLISH_AUSTRALIA],
        CULocale::NORTHERN_MARIANA_ISLANDS => [
            "enName" => "Northern Mariana Islands",
            "locale" => CULocale::ENGLISH_NORTHERN_MARIANA_ISLANDS],
        CULocale::NORWAY => [
            "enName" => "Norway",
            "locale" => CULocale::NORWEGIAN_NYNORSK_NORWAY],
        CULocale::OMAN => [
            "enName" => "Oman",
            "locale" => CULocale::ARABIC_OMAN],
        CULocale::PAKISTAN => [
            "enName" => "Pakistan",
            "locale" => CULocale::URDU_PAKISTAN],
        CULocale::PALAU => [
            "enName" => "Palau",
            "locale" => CULocale::ENGLISH],
        CULocale::PALESTINIAN_TERRITORY => [
            "enName" => "Palestinian Territory",
            "locale" => CULocale::ARABIC],
        CULocale::PANAMA => [
            "enName" => "Panama",
            "locale" => CULocale::SPANISH_PANAMA],
        CULocale::PAPUA_NEW_GUINEA => [
            "enName" => "Papua New Guinea",
            "locale" => CULocale::ENGLISH],
        CULocale::PARAGUAY => [
            "enName" => "Paraguay",
            "locale" => CULocale::SPANISH_PARAGUAY],
        CULocale::PERU => [
            "enName" => "Peru",
            "locale" => CULocale::SPANISH_PERU],
        CULocale::PHILIPPINES => [
            "enName" => "Philippines",
            "locale" => CULocale::ENGLISH_PHILIPPINES],
        CULocale::PITCAIRN => [
            "enName" => "Pitcairn",
            "locale" => CULocale::ENGLISH],
        CULocale::POLAND => [
            "enName" => "Poland",
            "locale" => CULocale::POLISH_POLAND],
        CULocale::PORTUGAL => [
            "enName" => "Portugal",
            "locale" => CULocale::PORTUGUESE_PORTUGAL],
        CULocale::PUERTO_RICO => [
            "enName" => "Puerto Rico",
            "locale" => CULocale::SPANISH_PUERTO_RICO],
        CULocale::QATAR => [
            "enName" => "Qatar",
            "locale" => CULocale::ARABIC_QATAR],
        CULocale::REUNION => [
            "enName" => "Reunion",
            "locale" => CULocale::FRENCH_REUNION],
        CULocale::ROMANIA => [
            "enName" => "Romania",
            "locale" => CULocale::ROMANIAN_ROMANIA],
        CULocale::RUSSIAN_FEDERATION => [
            "enName" => "Russian Federation",
            "locale" => CULocale::RUSSIAN_RUSSIA],
        CULocale::RWANDA => [
            "enName" => "Rwanda",
            "locale" => CULocale::KINYARWANDA_RWANDA],
        CULocale::SAINT_BARTELEMEY => [
            "enName" => "Saint Bartelemey",
            "locale" => CULocale::FRENCH],
        CULocale::SAINT_HELENA => [
            "enName" => "Saint Helena",
            "locale" => CULocale::ENGLISH],
        CULocale::SAINT_KITTS_AND_NEVIS => [
            "enName" => "Saint Kitts and Nevis",
            "locale" => CULocale::ENGLISH],
        CULocale::SAINT_LUCIA => [
            "enName" => "Saint Lucia",
            "locale" => CULocale::ENGLISH],
        CULocale::SAINT_MARTIN => [
            "enName" => "Saint Martin",
            "locale" => CULocale::FRENCH_SAINT_MARTIN],
        CULocale::SAINT_PIERRE_AND_MIQUELON => [
            "enName" => "Saint Pierre and Miquelon",
            "locale" => CULocale::FRENCH_CANADA],
        CULocale::SAINT_VINCENT_AND_THE_GRENADINES => [
            "enName" => "Saint Vincent and the Grenadines",
            "locale" => CULocale::ENGLISH],
        CULocale::SAMOA => [
            "enName" => "Samoa",
            "locale" => CULocale::ENGLISH],
        CULocale::SAN_MARINO => [
            "enName" => "San Marino",
            "locale" => CULocale::ITALIAN],
        CULocale::SAO_TOME_AND_PRINCIPE => [
            "enName" => "Sao Tome and Principe",
            "locale" => CULocale::PORTUGUESE_SAO_TOME_AND_PRINCIPE],
        CULocale::SAUDI_ARABIA => [
            "enName" => "Saudi Arabia",
            "locale" => CULocale::ARABIC_SAUDI_ARABIA],
        CULocale::SENEGAL => [
            "enName" => "Senegal",
            "locale" => CULocale::FRENCH_SENEGAL],
        CULocale::SERBIA => [
            "enName" => "Serbia",
            "locale" => CULocale::SERBIAN_LATIN_SERBIA],
        CULocale::SEYCHELLES => [
            "enName" => "Seychelles",
            "locale" => CULocale::ENGLISH],
        CULocale::SIERRA_LEONE => [
            "enName" => "Sierra Leone",
            "locale" => CULocale::ENGLISH],
        CULocale::SINGAPORE => [
            "enName" => "Singapore",
            "locale" => CULocale::ENGLISH_SINGAPORE],
        CULocale::SINT_MAARTEN => [
            "enName" => "Sint Maarten",
            "locale" => CULocale::DUTCH_SINT_MAARTEN],
        CULocale::SLOVAKIA => [
            "enName" => "Slovakia",
            "locale" => CULocale::SLOVAK_SLOVAKIA],
        CULocale::SLOVENIA => [
            "enName" => "Slovenia",
            "locale" => CULocale::SLOVENIAN_SLOVENIA],
        CULocale::SOLOMON_ISLANDS => [
            "enName" => "Solomon Islands",
            "locale" => CULocale::ENGLISH],
        CULocale::SOMALIA => [
            "enName" => "Somalia",
            "locale" => CULocale::SOMALI_SOMALIA],
        CULocale::SOUTH_AFRICA => [
            "enName" => "South Africa",
            "locale" => CULocale::AFRIKAANS_SOUTH_AFRICA],
        CULocale::SOUTH_GEORGIA_AND_THE_SOUTH_SANDWICH_ISLANDS => [
            "enName" => "South Georgia and the South Sandwich Islands",
            "locale" => CULocale::ENGLISH],
        CULocale::SOUTH_SUDAN => [
            "enName" => "South Sudan",
            "locale" => CULocale::ENGLISH],
        CULocale::SPAIN => [
            "enName" => "Spain",
            "locale" => CULocale::SPANISH_SPAIN],
        CULocale::SRI_LANKA => [
            "enName" => "Sri Lanka",
            "locale" => CULocale::SINHALA_SRI_LANKA],
        CULocale::SUDAN => [
            "enName" => "Sudan",
            "locale" => CULocale::ARABIC_SUDAN],
        CULocale::SURINAME => [
            "enName" => "Suriname",
            "locale" => CULocale::DUTCH],
        CULocale::SVALBARD_AND_JAN_MAYEN => [
            "enName" => "Svalbard and Jan Mayen",
            "locale" => CULocale::NORWEGIAN_NYNORSK_NORWAY],
        CULocale::SWAZILAND => [
            "enName" => "Swaziland",
            "locale" => CULocale::ENGLISH],
        CULocale::SWEDEN => [
            "enName" => "Sweden",
            "locale" => CULocale::SWEDISH_SWEDEN],
        CULocale::SWITZERLAND => [
            "enName" => "Switzerland",
            "locale" => CULocale::GERMAN_SWITZERLAND],
        CULocale::SYRIAN_ARAB_REPUBLIC => [
            "enName" => "Syrian Arab Republic",
            "locale" => CULocale::ARABIC_SYRIA],
        CULocale::TAIWAN => [
            "enName" => "Taiwan",
            "locale" => CULocale::CHINESE_TRADITIONAL_TAIWAN],
        CULocale::TAJIKISTAN => [
            "enName" => "Tajikistan",
            "locale" => CULocale::RUSSIAN],
        CULocale::TANZANIA_UNITED_REPUBLIC_OF => [
            "enName" => "Tanzania, United Republic of",
            "locale" => CULocale::SWAHILI_TANZANIA],
        CULocale::THAILAND => [
            "enName" => "Thailand",
            "locale" => CULocale::THAI_THAILAND],
        CULocale::TIMOR_LESTE => [
            "enName" => "Timor-Leste",
            "locale" => CULocale::PORTUGUESE],
        CULocale::TOGO => [
            "enName" => "Togo",
            "locale" => CULocale::FRENCH_TOGO],
        CULocale::TOKELAU => [
            "enName" => "Tokelau",
            "locale" => CULocale::ENGLISH_NEW_ZEALAND],
        CULocale::TONGA => [
            "enName" => "Tonga",
            "locale" => CULocale::TONGAN_TONGA],
        CULocale::TRINIDAD_AND_TOBAGO => [
            "enName" => "Trinidad and Tobago",
            "locale" => CULocale::ENGLISH_TRINIDAD_AND_TOBAGO],
        CULocale::TUNISIA => [
            "enName" => "Tunisia",
            "locale" => CULocale::ARABIC_TUNISIA],
        CULocale::TURKEY => [
            "enName" => "Turkey",
            "locale" => CULocale::TURKISH_TURKEY],
        CULocale::TURKMENISTAN => [
            "enName" => "Turkmenistan",
            "locale" => CULocale::RUSSIAN],
        CULocale::TURKS_AND_CAICOS_ISLANDS => [
            "enName" => "Turks and Caicos Islands",
            "locale" => CULocale::ENGLISH],
        CULocale::TUVALU => [
            "enName" => "Tuvalu",
            "locale" => CULocale::ENGLISH],
        CULocale::UGANDA => [
            "enName" => "Uganda",
            "locale" => CULocale::GANDA_UGANDA],
        CULocale::UKRAINE => [
            "enName" => "Ukraine",
            "locale" => CULocale::UKRAINIAN_UKRAINE],
        CULocale::UNITED_ARAB_EMIRATES => [
            "enName" => "United Arab Emirates",
            "locale" => CULocale::ARABIC_UNITED_ARAB_EMIRATES],
        CULocale::UNITED_KINGDOM => [
            "enName" => "United Kingdom",
            "locale" => CULocale::ENGLISH_UNITED_KINGDOM],
        CULocale::UNITED_STATES => [
            "enName" => "United States",
            "locale" => CULocale::ENGLISH_UNITED_STATES],
        CULocale::UNITED_STATES_MINOR_OUTLYING_ISLANDS => [
            "enName" => "United States Minor Outlying Islands",
            "locale" => CULocale::ENGLISH_US_MINOR_OUTLYING_ISLANDS],
        CULocale::URUGUAY => [
            "enName" => "Uruguay",
            "locale" => CULocale::SPANISH_URUGUAY],
        CULocale::UZBEKISTAN => [
            "enName" => "Uzbekistan",
            "locale" => CULocale::UZBEK_LATIN_UZBEKISTAN],
        CULocale::VANUATU => [
            "enName" => "Vanuatu",
            "locale" => CULocale::ENGLISH],
        CULocale::VATICAN_CITY_STATE => [
            "enName" => "Holy See (Vatican City State)",
            "locale" => CULocale::ITALIAN],
        CULocale::VENEZUELA => [
            "enName" => "Venezuela",
            "locale" => CULocale::SPANISH_VENEZUELA],
        CULocale::VIETNAM => [
            "enName" => "Vietnam",
            "locale" => CULocale::VIETNAMESE_VIETNAM],
        CULocale::VIRGIN_ISLANDS_BRITISH => [
            "enName" => "Virgin Islands, British",
            "locale" => CULocale::ENGLISH],
        CULocale::VIRGIN_ISLANDS_US => [
            "enName" => "Virgin Islands, U.S.",
            "locale" => CULocale::ENGLISH_US_VIRGIN_ISLANDS],
        CULocale::WALLIS_AND_FUTUNA => [
            "enName" => "Wallis and Futuna",
            "locale" => CULocale::FRENCH],
        CULocale::WESTERN_SAHARA => [
            "enName" => "Western Sahara",
            "locale" => CULocale::SPANISH],
        CULocale::YEMEN => [
            "enName" => "Yemen",
            "locale" => CULocale::ARABIC_YEMEN],
        CULocale::ZAMBIA => [
            "enName" => "Zambia",
            "locale" => CULocale::ENGLISH],
        CULocale::ZIMBABWE => [
            "enName" => "Zimbabwe",
            "locale" => CULocale::ENGLISH_ZIMBABWE]];
}
