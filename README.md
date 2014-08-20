<p align="center">
  <img src="readme-files/phred-logo.png"/>
</p>

***

Phred is an open-source initiative aimed at providing PHP with a consistent, completely object-oriented coding standard that enjoys a comfortable API for creating modern-day web applications with native support for Unicode, with components for internationalization and localization, clear-cut fundamental data types focused on performance and reliability, enhanced testing and debugging, and other features.

Phred is also outlining a web application framework to let developers take the most advantage of the improved PHP in their projects as well as to ensure backward compatibility with the whole multitude of the existing PHP libraries and APIs.

One of the Phred's prime efforts is to maintain a clear and thorough [documentation](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/index.html).

Phred is striving to transform PHP into a cutting-edge, fully-featured, and easy-to-use tool for contemporary web development, while remaining free for all and well-documented. And Phred is many months of work that has already been done and that still needs to be accomplished. *You can help Phred carry on and improve in its service to PHP developers by making a [donation](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/readme-files/donate.html).*

## At a Glance

```php
// An OOP Unicode string.
$str = "Юникод Ооп";

echo $str->length();        // 10
echo $str->toUpperCase();   // ЮНИКОД ООП

$array = $str->split(" ");
echo $array->join(", ");    // Юникод, Ооп
$array->sortOn("length");
echo $array->join(", ");    // Ооп, Юникод

echo strlen($str);          // 19
echo strtoupper($str);      // Юникод Ооп
```

***

* [Preface](#preface)
    * [PHP 6 or Uniphant vs. Elecorn](#php-6-or-uniphant-vs-elecorn)
    * [PHP 7 or Much Like PHP 5.6+ but Faster](#php-7-or-much-like-php-56-but-faster)
    * [こんにちは, Phred](#こんにちは-phred)
    * [The Zen of Phred](#the-zen-of-phred)
* [Installation](#installation)
    * [Getting the Latest PHP Version](#getting-the-latest-php-version)
    * [Installing Phred](#installing-phred)
    * [Running Unit Tests](#running-unit-tests)
* [Fundamental Types](#fundamental-types)
    * [OOP String](#oop-string)
        * [Regular Expressions](#regular-expressions)
        * [Character Encodings](#character-encodings)
    * [OOP Array](#oop-array)
    * [OOP Map](#oop-map)
    * [Time and Time Zones](#time-and-time-zones)
    * [Comparison and Sorting](#comparison-and-sorting)
* [Enhanced Testing and Debugging](#enhanced-testing-and-debugging)
    * [Semantic Checks](#semantic-checks)
    * [Bug Tracking and Reporting](#bug-tracking-and-reporting)
* [U14n](#u14n)
    * [Locales](#locales)
    * [Locale-Specific Formatting](#locale-specific-formatting)
* [Batteries Included](#batteries-included)
    * [Input Filtering](#input-filtering)
    * [Files and Directories](#files-and-directories)
    * [Omnivorous JSON](#omnivorous-json)
    * [Mailing](#mailing)
    * [Requesting](#requesting)
* [Backward Compatibility](#backward-compatibility)
* [Donate](#donate)

***

# Preface

Any web developer is of course familiar with JavaScript. Highly object-oriented and with Unicode done right, JavaScript just feels comfortable to work in when developing for the client side and JavaScript has even made it to the server side as Node.js. Same kind of sentiments might go to other languages that were also designed with a vision in mind, such as Python, Ruby, and Scala.

But while the quality standards of web development have evolved over the past decade to match the increasing demands for clean, readable, and comprehensible code that can be efficiently shared with other people on the team or made public, PHP has remained mostly immature. This may be especially noticeable if you are a PHP developer trying to create an innovative web application that is required to offer a substantial value to many users all over the globe. It even seems bit unfair that PHP, which is the most popular language used by web servers with a share of about [80%](http://w3techs.com/technologies/history_overview/programming_language/ms/y), is not actually superior to JavaScript, even though the control flow of a web application may imply the opposite.

Still being largely procedural and hardly following any conventions in the naming of the daunting number of its functions, some of which start with "str" and some with "str_", being infamous for the order of "haystack", "needle", and "subject" parameters changing irregularly from one function to another, and without the long overdue support for Unicode, PHP has found itself in a weird relationship with the developers, where the amount of people's love for PHP is mixed with a nearly proportional amount of hate. It is true however that the PHP language is easy to get into thanks to the C-like syntax resembling JavaScript along with a good number of other popular languages and that PHP has become the lingua franca of web programming, with a huge community of developers and open-source contributors. But it's also true that PHP is steadily losing in quality to a bunch of rival languages.

Because PHP is going to stay around for many years to come, the demands for an up-to-date PHP standard can no longer be ignored.

## PHP 6 or Uniphant vs. Elecorn

Most of the hopes for a better PHP were being put in PHP 6 announced around 2006. However, PHP 6 became more like a mythical creature over the time, a creature that no one has ever seen. The new features that were planned for PHP, including the highly anticipated Unicode support, had never got released under PHP 6 label. Instead, the PHP 6 development branch was discontinued and only some of the features were backported into the 5.x versions afterwards, still without built-in Unicode. There was no beta or even alpha version of PHP 6.

One of the main reasons behind PHP 6 getting abandoned was a disagreement inside the development team as to which character encoding would serve best for storing and processing Unicode strings. Despite of the fact that the team then picked UTF-16 as the internal encoding to be used for Unicode, the development ran out of steam and eventually came to a halt. This happened not only because the choice of UTF-16 turned out to be suboptimal, but also because of the immense body of work that was required to be done for the PHP's core and all the extensions by the developers becoming less and less enthusiastic with the direction that was chosen and due to other issues. Andrei Zmievski, who was the head of the PHP 6 project, later admitted that he would probably choose UTF-8 over UTF-16 if it was possible to start over.

## PHP 7 or Much Like PHP 5.6+ but Faster

In July of 2014, a vote was held by the PHP's steering group to decide under what name the next major version of PHP should be presented to the public when its development branch, called PHPNG for "PHP New Generation", would become a release candidate. The name that outvoted "PHP 6" and other names was "PHP 7". Skipping over "6" in the PHP's version was probably for the reason that PHP 6 had bit too much of disappointment associated with it and a number of books already existed at the time with "PHP 6" in their titles. 

With the advent of PHPNG, which is going to become the basis for PHP 7, the mainstream implementation of PHP made a substantial progress in speed and memory consumption thanks to a great deal of optimization and code refactoring that eliminated numerous bottlenecks in the PHP's performance, while not breaking any backward compatibility. The modified engine also brought PHP closer to being able to benefit from just-in-time compilation in some of its future versions. The benchmarks that were run with PHPNG showed surprisingly good results, indicating almost double increase in speed and significantly smaller memory footprint. 

## こんにちは, Phred

The idea of Phred is resonating with the principle of separation of concerns. Let the PHP's core contributors remain focused on the language's underpinnings and further improve the PHP's engine to make it even faster, effectively providing a high-performance foundation upon which a consistent and completely object-oriented coding standard can be implemented by PHP developers themselves.

By extending PHP 7, which is now twice as fast, Phred converts PHP into an up-to-date and clean standard advantageous for creating sophisticated web sites and applications to be used by people all over the world, while keeping performance at a high level. Fortunately, the OOP-related features that are already present in PHP 5.6 and PHP 7 have made this possible. And not to forget the vital OOP infusions into the language that were faithfully made by some of its core contributors, most notably Nikita Popov.

The following is an example of how Phred may look like at work:

```php
// Sign up a new user.

$inputUserName = "たかし やまもと ";
$inputBirthday = 491702400;  // converted to Unix time by JS

// Sanitize the username if it's not a valid Unicode string and remove any
// leading or trailing whitespace including any Unicode whitespace.
$userName = $inputUserName->isValid() ? $inputUserName : $inputUserName->sanitize();
$userName = $userName->trim();

if (!$userName->isEmpty()) {
    // The username is not empty.
    if ($userName->length() <= MAX_USERNAME_CHARS) {
        // Search for the first and last names within the username using
        // a regular expression pattern.
        if ($userName->reFindGroups("/^(\pL+)\s+(\pL+)$/u", $foundGroups)) {
            $firstName = $foundGroups[0];
            $lastName =  $foundGroups[1];
            echo $firstName;  // たかし
            echo $lastName;   // やまもと

            // Transliterate the username into the Latin script for searching.
            $userNameT = $userName->transliterateFromAny("latin")->toLowerCase();
            // "たかし やまもと" becomes "takashi yamamoto".

            // Store the names and the rest of the profile info to the database.
            // ...

            // Let's see how many days we are currently away from the user's birthday.
            $currTime = Tm::now();
            $birthday = new Tm($inputBirthday);
            $currYear = $currTime->yearUtc();
            $bdMonth =  $birthday->monthUtc();
            $bdDay =    $birthday->dayUtc();
            if (Tm::areComponentsValid($currYear, $bdMonth, $bdDay)) {
                $bdThisYear = Tm::fromComponentsUtc($currYear, $bdMonth, $bdDay);
                if ($currTime->diffInDays($bdThisYear) <= BD_SPEC_OFFER_DAYS) {
                    // Make a gift to the user on the service's behalf.
                    // ...
                }
            }
        }
    }
}
```

The classes used in the above example are [OOP string](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CUStringObject.html), [OOP array](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CArrayObject.html), and the [time class](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CTime.html).

## The Zen of Phred

* In the world of open-source development where most of the code is shared, code clarity and readability is important.
* Object-oriented code architecture allows for much higher clarity and readability than procedural one, especially in complex code.
* Clean and eloquent code is easier to review and independently reviewed code is more reliable and secure.
* Code clarity largely influences the code's reusability for other purposes and by other people.
* Consistency in a coding standard allows for cleaner and more comprehensible documentation.
* Although it requires some tedious work, the usage of semantic checks on a massive scale pays off with more reliable code.
* Adapting some of the familiar conventions that are used for method naming, parameter ordering, and method behavior in JavaScript is better than making up new ones.
* Unvarying return types with a fixed type for every method is better than varying return types when a value of one type can be mistaken for a value of another type, as in `false == 0`.
* "100" is *not* equal to "1e2", as in `"100" == "1e2"`.
* Underscores in naming are simply depressing -- let's have less of those.

# Installation

Phred is currently available for the Linux/Unix platform running at least 5.6 version of PHP.

If you are still using an older version of PHP and your system is **Ubuntu** or **Debian**, you can follow the simple steps given below to install PHP 5.6 and then Phred.

## Getting the Latest PHP Version

Add the repository where PHP 5.6 packages are maintained and update the package list:

```sh
sudo add-apt-repository ppa:ondrej/php5-5.6
sudo apt-get update
```

Install PHP:

```sh
sudo apt-get install php5 php5-cli php5-dev php5-intl php5-curl php5-cgi php5-fpm
```

After the installation is complete, you can check the PHP version you've got:

```sh
php -v
```

You would also need to install a small PHP extension that Phred depends on when used with PHP 5.6. This extension is authored by a core PHP contributor and, as a part of a larger contribution, it was [accepted](https://wiki.php.net/rfc/uniform_variable_syntax) for inclusion into PHP 7. So, while PHP 7 is still being prepared for release, let's download the extension via `git` and install it.

Still don't have `git` on your system? On Ubuntu/Debian, you can install `git` as follows:

```sh
sudo apt-get install git
```

Download and install the extension:

```sh
git clone https://github.com/nikic/scalar_objects.git
cd scalar_objects
phpize
./configure
make
sudo make install
```

And let's not forget to enable the installed extension. Simply go into `/etc/php5/cli/php.ini` and add the following line at the end of the file:

```
extension = scalar_objects.so
```

To have the extension enabled when PHP is used by your web server software, also add the above line at the end of `/etc/php5/fpm/php.ini` and restart PHP-FPM with:

```sh
sudo service php5-fpm restart
```

## Installing Phred

Now you can `cd` into a directory where you'd like to see the Phred's directory located and install Phred via Composer, which is the most commonly used package manager for PHP:

```sh
curl -sS http://getcomposer.org/installer | php
php composer.phar create-project phred/phred phred 0.4.*@dev --prefer-dist
```

At this point, Phred inhabits `phred` directory inside your current working directory.

## Running Unit Tests

Phred is an extensively tested coding environment and goes accompanied with thousands of individual checks written in the great PHPUnit testing framework.

You can run the Phred's unit tests in mass with a ready-made PHP script:

```sh
cd phred
php run-unit-tests.php
```

If everything went fine, you should see PHPUnit reporting "OK" against a green bar.

But it took quite some time to complete, didn't it? Was it because PHPUnit is not very fast or because it was given too many tests? Well, it's neither. What is actually causing the unit tests being slow by default is that PHPUnit is indirectly invoking a large quantity of a different kind of tests on top. These additional tests are semantic checks embedded in practically every method of every Phred class in the form of assertions. It can be easily made certain by going into `Application/Configuration/` directory inside `phred`, opening `Debug.json` configuration file, and changing the value of `enableAssertions` option from `true` to `false`, and then running the unit tests once again.

What this means is that you can have the semantic checks enabled for your development environment but disabled for your production environment, which is something that Phred has already taken care of if you look into `Application/Configuration/Environments/pro/Debug.json` file that overrides the default configuration options when Phred is put into the production environment. With the Phred's flexible configuration options, you can have the semantic checks activated for the production environment too, based on a time condition.

# Fundamental Types

Phred makes the PHP's fundamental types not just follow the best practices of object-oriented programming with consistent naming and expectable parameter ordering, but also makes them reliable, efficient, and comfortable in use. Phred views the date/time class essential enough for it to be considered a fundamental type, together with the time zone class.

## OOP String

With Phred, any PHP string is equivalent to an object when treated as such. At the same time, any string is seen as a scalar from the engine's perspective, therefore no impact is made on memory consumption and the related performance. The methods that are associated with a PHP string assume UTF-8 encoding for its characters, which nowadays is the default character encoding on the web. The string methods are implemented by the [CUStringObject](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CUStringObject.html) class (alias `UStr`).

To determine if one string is equal to another string, you can use `equals` method or, if the letter case in the strings can be ignored, `equalsCi` method instead, where `Ci` stands for "case-insensitive". For the reason that Unicode allows the same character to be represented by different combinations of bytes, `==` operator is better be avoided in string comparison. And since `==` operator may behave fuzzy in some situations, for instance when it reports `true` for `"1000" == "1e3"`, you would be trying to avoid using it anyways. The `equals` and `equalsCi` methods always report correct results, no matter what the normal forms of the two Unicode strings are. Another advantage of the comparison methods over operators is that, with a comparison method, you can specify Unicode collation options as a parameter. If the encoding of the both strings that need to be compared for equality is ASCII, which is a subset of Unicode, you can make the comparison slightly faster by using the binary-safe `equalsBi` method. And `equalsBi` is also the method for comparing any binary data.

The string class also implements `compare`, `compareCi`, `compareNat`, `compareNatCi`, and a few more methods related to comparison. The four methods return a negative integer if the first string is "less" than the second one, a positive integer if it is "greater", and `0` if the two strings are equal. While not being too useful on their own, these methods enable string sorting for arrays. `Nat` in the two method names stands for "natural order", according to which sorting methods arrange strings in the order that a human would perceive natural, for example, placing "A20" before "A100" and not vice versa like with the other two comparison methods.

The Unicode collation options let you customize in multiple ways how the string comparison should be performed with some of the comparison methods of the string class and with the string sorting methods of the array class. Just to prevent the collation options from clogging the parameter list, the options are represented by bitfields, also known as flags. These collation flags are available as constants of the string class (`UStr::FLAG_NAME`) and in separate or in combination with other flags they can tell a method to ignore accents and other marks when comparing Unicode strings, to ignore whitespace, vertical space, punctuation, and symbols, to put uppercase ahead of lowercase, or to use the "French" collation.

Notable Unicode-related methods are `isValid` method that tests a string for containing byte sequences that are invalid in Unicode, `sanitize` method that replaces any invalid bytes with "�" character, `normalize` method that can ensure the default normal form for a string by normalizing it to the NFC normal form or to any of the other three normal forms, and `isNormalized` method that can answer if a string is already normalized to a certain normal form. There is nothing complicated about Unicode normal forms and you can look into the documentation on the string class to learn why they exist.

Many of the string methods were reasonably inspired by JavaScript or are self-explanatory: `length`, `isEmpty`, `toLowerCase`, `toUpperCase`, `toTitleCase`, `indexOf`, `indexOfCi`, `lastIndexOf`, `lastIndexOfCi`, `find`, `findCi`, `substr`, `substring`, `startsWith`, `startsWithCi`, `endsWith`, `endsWithCi`, `split`, `trim`, `replace`, `replaceCi`, `remove`, `removeCi`, `shuffle`. And among numerous other methods, there is `normSpacing` method that, in addition to trimming a string from whitespace on both sides, also shrinks the inner sequences of whitespace characters to a single space, and `normNewlines` method that normalizes the newlines in a string.

And it is not like the PHP's string functions were simply wrapped into methods with the corresponding JavaScript naming and parameter lists. An important goal was to recreate the exact same behavior that JavaScript implements for strings and to cover all of the many special cases that the native PHP functions do not care enough to address. Just one of the examples:

```php
$str = "Hello";

echo substr($str, 0, 0);  // "" (correct)
echo substr($str, 5, 0);  // false (incorrect or just inconsistent)

echo $str->substr(0, 0);  // "" (correct, same as in JS)
echo $str->substr(5, 0);  // "" (correct, same as in JS)
```

Other methods that do handle special cases with care are `indexOf` and `indexOfCi`, `lastIndexOf` and `lastIndexOfCi`, `split`, `insert`, and several more.

### Regular Expressions

Phred greatly simplifies the use of regular expressions in PHP by integrating the regular expression functionality right into OOP strings.

This integration is intended to save you lots of typing by getting rid of the overly complicated `preg_` functions, `=== 1` and `=== 0` when searching for regular expression patterns since the appropriate string methods naturally return either `true` or `false`, by outputting the found string directly instead of unnecessarily enfolding it into an array, and by avoiding the intricacies with obscure multidimensional arrays when searching for regular expression groups.

Any string method that has to do with regular expressions indicates it with `re` in the beginning of its name. The `re` methods that behave along the lines of some of the plain-text methods are trying to follow maximally the naming and the parameter order used by their counterparts. The only big difference however is `Ci` in the method naming because, as imposed by PCRE, the case-insensitive mode can only be turned on with the "i" modifier in the regular expression pattern itself.

Most of the complexity that exists with regular expression searches was divided and conquered by combining the possible kinds of searches based on whether the search starts from the beginning of the string or from a specific position, whether the search targets a substring or regular expression groups, and whether the search stops after the first occurrence of the regular expression pattern is found or continues to find all the occurrences.

The following example searches for regular expression groups in a string and stores found strings to arrays:

```php
$str = "[gr0-0] [gr0-1] [gr1-0] [gr1-1]";
$numOccurFound = $str->reFindAllGroups("/(\[.*?\])\s+(\[.*?\])/", $foundGroups,
    $foundStrings);
echo $numOccurFound;           // 2
echo $foundGroups[0][0];       // "[gr0-0]"
echo $foundGroups[0][1];       // "[gr0-1]"
echo $foundGroups[1][0];       // "[gr1-0]"
echo $foundGroups[1][1];       // "[gr1-1]"
echo $foundStrings->length();  // 2
echo $foundStrings[0];         // "[gr0-0] [gr0-1]"
echo $foundStrings[1];         // "[gr1-0] [gr1-1]"
```

For a challenge, you could try doing the same but with `preg_` functions just to see the amount of needless code it would produce.

### Character Encodings

Converting from one character encoding to another and other typical tasks related to character encodings are taken care of by the [CEString](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CEString.html) class (alias `EStr`).

Powered by the ICU library, the class is able to convert between practically all character encodings there are in existence. For historical and other reasons, almost every of the character encodings supported by ICU is known by more than one name. Because of this, ICU picked a single name for each character encoding by which the encoding is to be identified internally. Such character encoding names are called primary by the class and the rest of the names are aliases. For example, "UTF-8" is a primary character encoding name, whereas "ibm-1208" and "cp1208" are some of its many aliases, and it's safe to say that "UTF-8" is an alias of "cp1208". When you need to tell a character encoding name to a method of the class, an alias is just as good as the primary name.

In addition to converting between character encodings, detecting encodings, and fixing UTF-8 strings, the class can translate any Unicode string written in any language into ASCII, while preserving as much information as possible. This "flattening" to ASCII might be useful for the indexing of Unicode text, for searching inside or with Unicode text, and for collating Unicode text. If required, the string is transliterated into the Latin script beforehand. Latin characters such as "æ" and German sharp "ß" are all handled properly ("æ" becomes "ae" and "ß" becomes "ss"). The process also converts to ASCII some of the beyond-Latin Unicode characters that have similar appearance or meaning. The Unicode's Line Separator and Paragraph Separator characters are converted into ASCII's LF characters.

## OOP Array

Phred draws a clear distinction between apples and oranges by bringing forward a highly efficient array type for storing and accessing elements with minimum overhead. The type wraps over the SplFixedArray class offered by the Standard PHP Library, which is an integral part of PHP and is written in C. The OOP array is implemented in the [CArrayObject](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CArrayObject.html) class (alias `Ar`).

It often comes as a surprise to new PHP developers that PHP only has one data type to be used for all kinds of arrays. And any PHP array is an associative array, otherwise known as dictionary, hash map, or map. The PHP array is only able to store a value if the value brings along a key by which the value can then be accessed. Therefore, a PHP array is always storing key-value pairs instead of just values, even for simple arrays. In contrast, JavaScript provides the developer with the choice between `Array` type for simple arrays and `Object` type for associative arrays, while Python offers an assortment of at least four array types, namely `tuple`, `list`, `dict`, and `set`.

It would be really nice if PHP developers could benefit from this all-in-one design decision, but unfortunately they don't. On average, a programmer has to work with simple arrays more than with associative ones and simple arrays may contain large quantities of values that need to be stored with least memory consumption. So not only PHP arrays are biased towards one array use at the expense of the other use, but they are also biased in the wrong direction.

Furthermore, the way PHP is using term "array" is outright misleading since what any PHP array is really behaving as is an ordered (hash) map. This misconception has been the source for generating a great deal of confusion in the PHP docs on the array functions and made some of the PHP array's behavior appear so bizarre that, in the eyes of many PHP developers, it may look hardly distinguishable from bug reports.

The following examples illustrate how much unpredictable PHP arrays can be:

```php
$assocArray = array();
$assocArray[0] = "a";
$assocArray[1] = "b";
$assocArray[2] = "c";

// At some point, remove a no longer needed element and replace it with
// a new element at some point later.
unset($assocArray[1]);
// ...
$assocArray[1] = "d";

echo implode(", ", $assocArray);  // "a, c, d"
```

Another example:

```php
$assocArray = array();

// We don't know the value of one of the elements yet, will assign it later.
$assocArray[0] = "a";
$assocArray[2] = "c";
// ...
// OK, we know the value now.
$assocArray[1] = "b";

echo implode(", ", $assocArray);  // "a, c, b"
```

And most notoriously:

```php
$assocArray = array();
for ($i = 4; $i >= 0; $i--) {
    $assocArray[$i] = $i;
}

echo implode(", ", $assocArray);  // "4, 3, 2, 1, 0" instead of "0, 1, 2, 3, 4"
```

But the good thing is that the array implementation being forced by PHP on developers is not the only one available. Another is the [SplFixedArray](http://www.php.net/SplFixedArray) class included with the PHP's SPL library, the goal of which is to provide efficient implementations for interfaces and classes that have to do with data structures in PHP. The language behind the SplFixedArray class' implementation is C. Despite of the class' name, the length of an SplFixedArray is not really fixed and an SplFixedArray can be easily resized using a dedicated method of the class. In addition to arrays, SPL also supplements PHP with classes for lists, stacks, and queues (Phred might acquire those with time).

In Phred, the SplFixedArray class serves as the underlying array implementation for any OOP array. This allows arrays in your applications to be very lightweight and efficient. And it also takes all the bad surprises out of interacting with arrays in your code since an OOP array *always* behaves just like you would expect it to.

As a test, let's allocate some number of integer values, first with a PHP array and then with an OOP array:

```php
$assocArray = array();
for ($i = 0; $i < 500000; $i++) {
    $assocArray[$i] = 0;
}

echo memory_get_peak_usage();  // 74209240
```

And when using an OOP array:

```php
$array = new Ar(500000);
for ($i = 0; $i < 500000; $i++) {
    $array[$i] = 0;
}

echo memory_get_peak_usage();  // 30017960
```

The difference in the peak numbers of allocated bytes shows about 60% of memory saving when an OOP array is used. This is an enjoyable improvement in performance, even for already boosted PHP 7.

So what we've got is:

* OOP arrays have much smaller memory footprint because their elements are stored sequentially and in a much more dense fashion.
* Because the elements in an OOP array are indexed with integer numbers, it allows for significantly faster element access in both reading and writing.
* OOP arrays enable you with the freedom of choice and let you make better optimization decisions in your applications.
* OOP array behaves much like its counterparts from the languages that you are familiar with, such as JavaScript.
* Exactly like in JavaScript, any OOP array is an object that is stored, assigned, and passed by reference.

You can create a new OOP array as an empty array to be grown later, as an array with a number of pre-allocated elements ready to be assigned, or from existing values to become the array's elements. The first two scenarios are covered by the constructor of the OOP array's class:

```php
// Create an empty array.
$array = new Ar();
echo $array->length();  // 0

// Create an array with pre-allocated elements.
$array = new Ar(15);
echo $array->length();  // 15
```

For creating an OOP array from a list of values, you would use a syntax that is shorter than `array()` by 4 characters:

```php
$array = a("one", "two", "three", "four", "five");
echo $array->length();  // 5
```

Something that is true for any array implementation is that, if the length of an array you are creating is known beforehand, it is faster to pre-allocate the array's elements first and then assign them with values as compared to adding the elements one by one using `push` method.

As usual, the elements in an OOP array are accessed with `[ ]` operator:

```php
$array = new Ar(5);
for ($i = 0; $i < $array->length(); $i++) {
    $array[$i] = $i*$i;
}

echo $array->join(", ");  // "0, 1, 4, 9, 16"
```

And OOP arrays can be iterated with `foreach` too:

```php
$array = a("12", "34", "56");
foreach ($array as $value) {
    echo $value;
}
// "123456"
```

While in JavaScript you would use `slice` method to make an independent copy of an array, with OOP arrays you would rather use `clone` keyword like so:

```php
$arrayCopy = clone $array;
```

Some of the methods of the OOP array that need little introduction are `length`, `isEmpty`, `first`, `last`, `push`, `pop`, `slice`, `insert`, `remove`, `splice`, `find`, `filter`, and `sort`.

Inspired by JavaScript, the OOP array also implements `sortOn` method that lets you sort the elements in an OOP array based on return values from a specified method of the elements' class:

```php
class ElementClass
{
    protected $myGoodness;

    public function __construct ($goodness) {
        $this->myGoodness = $goodness;
    }
    public function goodness () {
        return $this->myGoodness;
    }
}

$array = a(
    new ElementClass(5),
    new ElementClass(2),
    new ElementClass(4),
    new ElementClass(1),
    new ElementClass(3));
$array->sortOn("goodness");

for ($i = 0; $i < $array->length(); $i++) {
    echo $array[$i]->goodness();
}
// "12345"
```

It's also easier than ever to perform logical operations with arrays using `union`, `intersection`, `difference`, and `symmetricDifference` methods.

## OOP Map

As you would expect, an OOP map contains values associated with unique keys. The OOP map's functionality is implemented by the [CMapObject](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CMapObject.html) class (alias `Ma`).

Just like with PHP's associative arrays, a key in an OOP map can be either string or integer. When a string key looks like an integer, it's implicitly converted into the corresponding integer and is used as such to access the key's value in the map.

You can create an OOP map as initially empty or from a list of key-value pairs using a syntax similar to that of OOP arrays only with `m` in place of `a` and with square brackets to let `=>` operator glue the keys and values together:

```php
// Create an empty map.
$map = new Ma();
echo $map->length();  // 0

// Create a map from key-value pairs.
$map = m([
    "a" => "one",
    "b" => "two",
    "c" => "three",
    "d" => "four",
    "e" => "five"]);
echo $map->length();  // 5
```

Naturally, the values in an OOP map are accessible with `[ ]` operator and you can iterate through an OOP map with `foreach`:

```php
$map = new Ma();
$map["a"] = "one";
$map["b"] = "two";
$map["c"] = "three";
$map["d"] = "four";
$map["e"] = "five";
foreach ($map as $key => $value) {
    $value = $value->toTitleCase();
    echo "$key:$value ";
}
// "a:One b:Two c:Three d:Four e:Five "
```

Or you can iterate over the values in an OOP map by reference:

```php
$map = new Ma();
$map["a"] = "one";
$map["b"] = "two";
$map["c"] = "three";
$map["d"] = "four";
$map["e"] = "five";
foreach ($map as $key => &$value) {
    $value = $value->toTitleCase();
}
foreach ($map as $key => $value) {
    echo "$key:$value ";
}
// "a:One b:Two c:Three d:Four e:Five "
```

Some of the self-describing methods the OOP map are `length`, `isEmpty`, `hasKey`, `remove`, `filter`, `keys`, and `values`. With `valueByPath` and `setValueByPath` methods, you can also access a value in a multi-dimensional map by its key path, which is just a dot-separated sequence of keys that hierarchically indicate the path to the value, as in "level1key.level2key". Unlike the PHP's `array_merge` function, `merge` method is proud of being able to merge multi-dimensional maps correctly so that a value from a subsequent map overrides a value in a preceding map if they are associated with equal keys, making no special cases for numeric keys like `array_merge` function does.

Following the philosophy of JavaScript where the role of the associative array is played by `Object` type, any OOP map is an object in every aspect and is stored, assigned, and passed by reference. But when required, you can make an independent copy of an OOP map using `clone` keyword:

```php
$mapCopy = clone $map;
```

## Time and Time Zones

Phred tries its best to let you perform all kinds of time-related operations in the simplest way possible. The functionality that is natively present in PHP for dates, time, and time zones and that is mostly scattered over [date/time functions](http://php.net/manual/en/ref.datetime.php), [DateTime class](http://php.net/manual/en/class.datetime.php), [DateTimeZone class](http://php.net/manual/en/class.datetimezone.php), and [IntlTimeZone class](http://php.net/manual/en/class.intltimezone.php) was sorted out, refined, and put into the self-contained and fully-featured [CTime](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CTime.html) class (alias `Tm`). Time zones are represented by objects of the [CTimeZone](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CTimeZone.html) class (alias `Tz`).

An object of the CTime class represents a point in time. While there exists a multitude of time zones around the world, any represented point in time is referring to the very same moment in all of the time zones. Clearly, a point in time cannot be defined by the usual year, month, day, hour, minute, and second, because these date/time components vary from one time zone to another. Instead, any point in time is absolutely defined by a single time coordinate called Unix time.

The Unix time is the number of seconds that have elapsed since the midnight of January 1, 1970 as if this moment occurred in the UTC time zone, which is the time zone of the universal time and geographically matches the time zone of Greenwich, UK. UTC used to be called GMT in the past and is still called GMT in some conventions. Internally, the Unix time coordinate of any point in time is stored as a floating-point number, which is positive or zero, unless the point belongs to a year before 1970 and the coordinate is negative. The signed Unix time is also known as "UTime" in the context of the class and the signed number of milliseconds as "MTime".

For example, the Unix time of 1234567890 corresponds to 23:31:30 on February 13, 2009 UTC:

```php
$time = new Tm(1234567890);
echo $time->toStringUtc();  // "2009-02-13 23:31:30 UTC"
```

In addition to Unix time, you can create a time object from a formatted string, without even specifying the date/time format that is used by the string:

```php
$time = Tm::fromString("2009-02-13 23:31:30");
echo $time->UTime();  // 1234567890
```

And sure enough you can create a time object from date/time components:

```php
$year = 2009;
$month = 2;
$day = 13;
$hour = 23;
$minute = 31;
$second = 30;
$time = Tm::fromComponentsUtc($year, $month, $day, $hour, $minute, $second);

echo $time->toStringUtc();  // "2009-02-13 23:31:30 UTC"
```

But if you'd want the values of the date/time components to be tied to the Los Angeles time instead of UTC time:

```php
// Create a time zone for Los Angeles.
$timeZone = new Tz("America/Los_Angeles");

$year = 2009;
$month = 2;
$day = 13;
$hour = 23;
$minute = 31;
$second = 30;
$time = Tm::fromComponentsInTimeZone($timeZone,
    $year, $month, $day, $hour, $minute, $second);

echo $time->toStringInTimeZone(
    $timeZone);       // "2009-02-13 23:31:30 America/Los_Angeles"
echo $time->UTime();  // 1234596690 (8 hours difference)
```

Any time zone is primarily identified by its name and the same time zone can have multiple names. Unlike locale names, time zone names are case-sensitive. The names are standardized and, besides other places, can be found in the PHP's [List of Supported Timezones](http://php.net/manual/en/timezones.php).

When you need the current time as a time object, you can create the object with `now` static method:

```php
$currTime = Tm::now();
```

The MySQL-like date/time format that you've seen in the previous examples is the default format that, unlike the MySQL format, does not omit the time zone. Other formats are available as well:

```php
$time = new Tm(1234567890);

echo $time->toStringUtc(
    Tm::PATTERN_DEFAULT_DATE);     // "2009-02-13"
echo $time->toStringUtc(
    Tm::PATTERN_DEFAULT_TIME);     // "23:31:30"
echo $time->toStringUtc(
    Tm::PATTERN_ISO8601);          // "2009-02-13T23:31:30+0000"
echo $time->toStringUtc(
    Tm::PATTERN_MYSQL);            // "2009-02-13 23:31:30"
echo $time->toStringUtc(
    Tm::PATTERN_RFC822);           // "Fri, 13 Feb 09 23:31:30 +0000"
echo $time->toStringUtc(
    Tm::PATTERN_W3C);              // "2009-02-13T23:31:30+00:00"

$timeZone = new Tz("America/Los_Angeles");
echo $time->toStringInTimeZone($timeZone,
    Tm::PATTERN_MYSQL);            // "2009-02-13 15:31:30"
echo $time->toStringInTimeZone($timeZone,
    Tm::PATTERN_RFC822);           // "Fri, 13 Feb 09 15:31:30 -0800"
echo $time->toStringInTimeZone($timeZone,
    Tm::PATTERN_W3C);              // "2009-02-13T15:31:30-08:00"
```

When dealing with dates and time, the months are identified by their numbers, starting with 1 for January and ending with 12 for December. Hours, minutes, and seconds are numbered in the usual way, starting with 0 and ending with 23 for hours and with 59 for minutes and seconds. Milliseconds are supported for any point in time and their numbers range from 0 to 999. The days of the week are identified by `SUNDAY`, `MONDAY`, `TUESDAY`, `WEDNESDAY`, `THURSDAY`, `FRIDAY`, and `SATURDAY` enumerands of the class.

You can ask a time object for individual date/time components like this:

```php
$time = Tm::fromString("2009-02-13 23:31:30");

echo $time->yearUtc();       // 2009
echo $time->monthUtc();      // 2
echo $time->dayUtc();        // 13
echo $time->hourUtc();       // 23
echo $time->minuteUtc();     // 31
echo $time->secondUtc();     // 30
echo $time->dayOfWeekUtc();  // Tm::FRIDAY

echo $time->hourInTimeZone(
    new Tz("America/Los_Angeles"));  // 15
```

Or, to make things bit faster, you can obtain the date/time components of a time object in bulk:

```php
$time = Tm::fromString("2009-02-13 23:31:30");
$time->componentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond,
    $dayOfWeek);
echo $year;         // 2009
echo $month;        // 2
echo $day;          // 13
echo $hour;         // 23
echo $minute;       // 31
echo $second;       // 30
echo $millisecond;  // 0
echo $dayOfWeek;    // Tm::FRIDAY
```

To find out whether one point in time goes before or after another point in time, you can use `isBefore` and `isAfter` methods:

```php
$time0 = Tm::fromString("2009-02-13 23:31:30");
$time1 = Tm::fromString("2009-02-14 23:31:30");
echo $time0->isBefore($time1);  // true
echo $time0->isAfter($time1);   // false
```

Among other methods, the CTime class features the `diff...` group of methods that let you compute the absolute difference between any two points in time measured in one of the seven time units, the `signedDiff...` group of methods that allow for negative differences to be reported, the `shifted...` group of methods that let you shift points in time by a certain amount of time units in either direction, and `with...` group of methods that let you modify a point in time by changing the value of one of its date/time components.

## Comparison and Sorting

Some of the methods of the collection types, which currently are OOP array and OOP map, take as an optional parameter a callback function or method to be used by the method for the inter-comparison of the values contained in the collection so that the method could carry out its mission. You can see the default values for this parameter being either `CComparator::EQUALITY` or `CComparator::ORDER_ASC`, which are strings referring to the methods of the [CComparator](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CComparator.html) class (alias `Cmpr`). Another such comparator that is available to you is `CComparator::ORDER_DESC`.

With these comparators being the default callbacks, you don't need to worry about how the values in a collection are going to be compared with one another as long as the type of the values is known to the CComparator class. And any scalar or a class that implements one of the Phred's equality/order interfaces is a type that the CComparator class is familiar with.

Naturally, the CComparator class knows how to perform comparisons with each of the fundamental types in addition to scalars, so you can freely put OOP strings, time objects, OOP arrays, and OOP maps into an OOP array and then sort it with `sort` or `sortOn` method or make use of any other method that depends on a comparator, such as `find`, `countElement`, `removeByValue`, `unique`, `isSubsetOf`, `intersection`, and `difference`. With OOP maps, the default comparators make your life easier when you need to use `find` or `countValue` method.

From the CComparator class' point of view, there only exist three kinds of values: those that are scalars (excluding strings), those that conform to the IEqualityAndOrder interface, and those that conform to the IEquality interface. The difference between the interfaces is that objects of the IEquality interface, which only requires `equals` method to be implemented, can only be compared for equality, while objects of the IEqualityAndOrder interface, which requires both `equals` and `compare` methods to be implemented, can also be compared for order. What this means on practice is that you can let objects of your own class be compared with some of the default comparators by implementing either IEqualityAndOrder or IEquality interface for your class.

The types that currently implement the IEqualityAndOrder interface are:

* OOP string
* OOP array
* OOP map
* CTime

And the types that currently implement the IEquality interface are:

* CTimeZone
* CULocale
* CUrl
* CFile

When sorting Unicode strings inside OOP arrays, you've got multiple methods at your disposal. There is `sort` method that would sort your strings using the default or a custom comparator and, in case of `Cmpr::ORDER_ASC` or `Cmpr::ORDER_DESC` being the comparator, the strings would be sorted with all of the available Unicode collation options set to their defaults. The next is `sortOn` method that lets you sort strings based on return values from one of the string methods. And there are methods specifically optimized for string sorting.

The `sortUStrings`, `sortUStringsCi`, `sortUStringsNat`, and `sortUStringsNatCi` methods of the OOP array perform faster when sorting Unicode strings as compared to the more general-purpose `sort` and `sortOn` methods. What else makes these methods special is that, when calling any of the four methods, you can specify the Unicode collation options to be used for string comparison:

```php
$array = a("c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
$array->sortUStrings();
// "a", "A", "b", "B", "c", "C", "d", "D", "e", "E"

// Sort the same array with default collation options first and then
// ignoring accents and other marks.
$array = a("č", "B", "d", "E", "D", "C", "á", "ê", "b", "A");
$array->sortUStrings();
// "A", "á", "b", "B", "C", "č", "d", "D", "E", "ê"
$array = a("č", "B", "d", "E", "D", "C", "á", "ê", "b", "A");
$array->sortUStrings(UStr::COLLATION_IGNORE_ACCENTS);
// "á", "A", "b", "B", "č", "C", "d", "D", "ê", "E"

// Sort the same array with default collation options first and then
// ignoring whitespace, vertical space, punctuation, and some symbols.
$array = a(" c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A");
$array->sortUStrings();
// " c", ",B", ";D", ":E", "!C", "?a", ".d", ""e", "(b", "[A"
$array = a(" c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A");
$array->sortUStrings(UStr::COLLATION_IGNORE_NONWORD);
// "?a", "[A", "(b", ",B", " c", "!C", ".d", ";D", "\"e", ":E"

// Sort the same array with default collation options first and then
// favoring uppercase ahead of lowercase.
$array = a("c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
$array->sortUStrings();
// "a", "A", "b", "B", "c", "C", "d", "D", "e", "E"
$array = a("c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
$array->sortUStrings(UStr::COLLATION_UPPERCASE_FIRST);
// "A", "a", "B", "b", "C", "c", "D", "d", "E", "e"
```

As well as the locale in which the strings should be compared:

```php
// Default locale vs. French Canadian locale.
$array = a("cote", "côte", "Côte", "coté");
$array->sortUStrings();
// "cote", "coté", "côte", "Côte"
$array = a("cote", "côte", "Côte", "coté");
$array->sortUStrings(UStr::COLLATION_DEFAULT, new ULoc("fr_CA"));
// "cote", "côte", "Côte", "coté"

// Swedish locale vs. German locale.
$array = a("z", "ö");
$array->sortUStrings(UStr::COLLATION_DEFAULT, new ULoc("sv_SE"));
// "z", "ö"
$array = a("z", "ö");
$array->sortUStrings(UStr::COLLATION_DEFAULT, new ULoc("de_DE"));
// "ö", "z"
```

# Enhanced Testing and Debugging

Phred takes the reliability of your applications exceptionally serious. Consideration was given to how bugs can be best detected, tracked, and reported.

The unit tests with which Phred is accompanied are just one part of the Phred's effort in making your projects maximally bug-free, dependable, and therefore secure. Aside from a few classes that are still in development or require intricate testing techniques that are yet to be implemented, practically every method of every class was thoroughly tested to make sure that it will work as intended and to achieve a high method and line coverage. The Phred's unit tests are powered by [PHPUnit](http://phpunit.de/) testing framework and can be run with `run-unit-tests.php` script located in the root directory. A byproduct of the unit tests is that they can serve as an abundant source of examples showing the majority of the Phred's classes and methods is use.

However, because unit tests are only run occasionally after a significant change has been introduced into the code and because the diversity of input values and their combinations are limited by how the tests were initially shaped, Phred deems unit tests not sufficient enough. For the purpose of keeping the chances of bugs getting into your applications as low as physically possible and in order to improve bug detection and bug tracking, Phred made a move to expand testing further on the runtime by means of semantic checks.

## Semantic Checks

If you look into one or more of the unit testing classes, you would see calls to `assertTrue` and `assertFalse` methods being made by every test. These assertions belong to PHPUnit framework and are the most common ways to verify if the results produced by a tested method appear to be correct. In a more general meaning, an assertion is a semantic check that determines whether or not a value or a mixture of values makes sense in a given context.

In PHP as well as in C/C++, Java, and a good number other languages, assertions is a built-in feature. They have proved to be very helpful in saving lots of time that otherwise would be spent on bug researching and allowed developers to come up with fixes quickly and with minimum negative effect on users. A downside of assertions is that they may considerably influence performance in dynamic languages like PHP. But what's great about assertions in PHP is that they can be easily enabled or disabled. If disabled, the PHP runtime skips over all assertions when executing a PHP script as if the assertions did not exist.

Semantic checks in Phred are ubiquitous and are embedded in almost every method as `assert` functions. Diligently playing their roles of safeguards against any misuse of methods and their parameters, the Phred's semantic checks is another obstacle for bugs to make their way into your application and remain undetected ever since. What assertions do in Phred when enabled is similar to what assertions do in unit tests with the key difference being that, in unit tests, assertions are focused on incorrect output while the primary focus of assertions in Phred's methods is incorrect input.

For the simple reason that incorrect input always leads to incorrect output, the Phred's semantic checks realize the previously unrealized side of the PHP's potential for bug detection, effectively making a Phred application with enabled assertions run as a unit test suite fed with highly diverse input that is generated by manifold user requests coming in great quantities.

## Bug Tracking and Reporting

The control panel for debugging and for configuring Phred's semantic checks can be found in `Application/Configuration/Debug.json`. And in `Application/Configuration/Admin.json` you can tell Phred the admin's email address to which error reports should be sent if mailing is enabled.

With the options that are offered to you in `Debug.json`, you can enable/disable the Phred's semantic checks with `enableAssertions` option, enable logging to a file about any encountered errors with the `logging` group of options, and enable mailing to the admin about errors with the `mailing` group of options. `Debug.json` also lets you configure the semantic checks so that they can be used on a production server with little to none impact on performance. You can tell Phred to keep the semantic checks enabled during an hour range, during one or more days of week, or during those days of year that are multiple of a certain number.

Of course, the PHP runtime can encounter errors on its own when, for example, you make a typo in the name of a method that is only called under particular circumstances or when you forget a parameter in one of such methods. If logging or mailing is enabled, Phred will be reporting about all kinds of errors and not just about errors raised by semantic checks. But for an error that was encountered by a semantic check, Phred is able to provide you with more of the valuable information that you would need for successful debugging. In addition to the usual information on the line number of an error that was snatched by a semantic check, Phred will also report:

1. The available backtrace of the error, appearing the chronological order.
2. The values of the method's parameters at the time of the error.
3. The values of the variables that were defined at the time.
4. The values of the object's properties at the time, unless the method is static.

If you are the admin and you have Phred configured to send a mail message to your email address about any detected error, a received message would look like this:

```
Something curious was encountered on hostname (12.34.56.78) in 'doc-root-dir'

[Time:] 2014-08-11 02:32:41 UTC

[Location:] PhredParty/Classes/String/CUString.php, line 1476

Assertion "!isset($length) || ($length >= 0 &&
$startPos + $length <= self::length($string))" failed ()
  'string' => 'Hello',
  'startPos' => 0,
  'length' => 10

[Backtrace:]
#1  assert() called at [PhredParty/Classes/String/CUString.php:1476]
#2  CUString::substr() called at [PhredParty/Classes/String/CUStringObject.php:1162]
#3  CUStringObject->substr() called at [Main.php:18]
#4  MyClass->myMethod() called at [Main.php:26]




[Latest error log records, chronologically:]

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

[Time:] 2014-08-11 02:31:34 UTC

[Location:] Main.php, line 16

Call to undefined method MyClass::typoMethod() (E_ERROR)

[Backtrace:]
#1  CDebug::fatalErrorHandler()

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

[Time:] 2014-08-11 02:32:41 UTC

[Location:] PhredParty/Classes/String/CUString.php, line 1476

Assertion "!isset($length) || ($length >= 0 &&
$startPos + $length <= self::length($string))" failed ()
  'string' => 'Hello',
  'startPos' => 0,
  'length' => 10

[Backtrace:]
#1  assert() called at [PhredParty/Classes/String/CUString.php:1476]
#2  CUString::substr() called at [PhredParty/Classes/String/CUStringObject.php:1162]
#3  CUStringObject->substr() called at [Main.php:18]
#4  MyClass->myMethod() called at [Main.php:26]

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
```

From the report above, it's easy to figure out that the error occurred in `substr` method when the method was called with an input that didn't make much of sense: while the string's value was "Hello", which is only 5 characters, the method was told to extract a substring starting from the first character and up to the non-existent 10th character.

# U14n

Phred prefers thinking about internationalization and localization as two interrelated concepts that in many contexts can be unified under a single term, universalization or just u14n. This is referred to as "globalization" by IBM and some other corporations, but Phred seems to dislike both the connotations and the affiliation of "globalization".

Just like with Unicode, character encodings, and time zones, Phred relies exclusively on the [ICU library](http://site.icu-project.org) (International Components for Unicode) for locale functionality and in the implementation of other components of internationalization and localization. Phred strives to avoid any dependencies on the POSIX data, which may often be incomplete and outdated, and therefore does not use any PHP functions that may still live in the POSIX world. In contrast, the ICU library roots into the CLDR data (Common Locale Data Repository), which is being collected and maintained by the Unicode Consortium and is the most extensive, all-inclusive, and consistent locale data available. The CLDR data is used by OS X, iOS, Android, Google Chrome, Java, Wikimedia Foundation, and many others.

Locales and locale-specific formatting is only a part of the u14n functionality that Phred is planning to have implemented in the long run. More of the functionality, such as message formatting and choice selection, is to be added with time.

## Locales

"Locale" is a term that designates a group of users with similar cultural and linguistic expectations for human-computer interaction. Locales are represented by objects of the [CULocale](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CULocale.html) class (alias `ULoc`).

A locale is identified by its name, which is a string consisting of one or more components. The primary components of a locale name are the language code (two letters, usually in lowercase) and the country/region code (two letters, usually in uppercase). The components in a locale name are separated with "_". For example, "de_BE" locale name refers to the German language with all of the peculiarities of its usage in the country of Belgium.

Additional components in a locale name can be the code of the writing script placed in between of the language code and the country/region code (four letters e.g. "sr_Latn_RS"), one or more locale variants placed after the country/region code (usually in uppercase), and locale metadata in the form of keyword-value pairs that, if present, conclude the locale name separated from everything else with "@" and use ";" to delimit one "keyword=value" from another.

For more detailed information on locales, you can look into [ICU User Guide](http://userguide.icu-project.org/locale).

## Locale-Specific Formatting

The [CUFormat](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CUFormat.html) class (alias `UFmt`) lets you format numbers, percentages, amounts of currency, as well as dates and time using the local formatting rules associated with a locale and, for some methods, the language of a locale.

For example, the formatting that is habitually used for integer and fractional numbers by people in one country may look strange to people in other countries:

```php
echo UFmt::number(1234, new ULoc(ULoc::ENGLISH_UNITED_STATES));
// "1,234"
echo UFmt::number(1234, new ULoc(ULoc::GERMAN_GERMANY));
// "1.234"
echo UFmt::number(1234, new ULoc(ULoc::FRENCH_FRANCE));
// "1 234"

echo UFmt::number(1234.5, new ULoc(ULoc::ENGLISH_UNITED_STATES));
// "1,234.5"
echo UFmt::number(1234.5, new ULoc(ULoc::GERMAN_GERMANY));
// "1.234,5"
echo UFmt::number(1234.5, new ULoc(ULoc::FRENCH_FRANCE));
// "1 234,5"
```

And when you need to put an ordinal number into a text that is going to be seen by a user, you may consider formatting it in the user's locale:

```php
echo UFmt::numberOrdinal(5, new ULoc(ULoc::ENGLISH_UNITED_STATES));
// "5th"
echo UFmt::numberOrdinal(5, new ULoc(ULoc::GERMAN_GERMANY));
// "5."
echo UFmt::numberOrdinal(5, new ULoc(ULoc::FRENCH_FRANCE));
// "5e"
```

In some situations you may need to format an amount of money in the currency used by a certain country or in a custom currency:

```php
echo UFmt::currency(12.34, new ULoc(ULoc::ENGLISH_UNITED_STATES));
// "$12.34"
echo UFmt::currency(12.34, new ULoc(ULoc::GERMAN_GERMANY));
// "12,34 €"
echo UFmt::currency(12.34, new ULoc(ULoc::FRENCH_FRANCE));
// "12,34 €"

echo UFmt::currency(12.34, new ULoc(ULoc::ENGLISH_UNITED_STATES), "EUR");
// "€12.34"
echo UFmt::currency(12.34, new ULoc(ULoc::GERMAN_GERMANY), "USD");
// "12,34 $"
echo UFmt::currency(12.34, new ULoc(ULoc::FRENCH_FRANCE), "USD");
// "12,34 $US"
```

And sometimes you may also need to spell out a number to a user:

```php
echo UFmt::numberSpellOut(1234, new ULoc(ULoc::ENGLISH_UNITED_STATES));
// "one thousand two hundred thirty-four"
echo UFmt::numberSpellOut(1234, new ULoc(ULoc::GERMAN_GERMANY));
// "ein­tausend­zwei­hundert­vier­und­dreißig"
echo UFmt::numberSpellOut(1234, new ULoc(ULoc::FRENCH_FRANCE));
// "mille deux cent trente-quatre"
```

When you need to display a date/time in a way that a user would be familiar with, you can go with one of the predefined display styles for the date and time parts and do the formatting using `timeWithStyles` method:

```php
echo UFmt::timeWithStyles(new Tm(1234567890), new Tz("America/Los_Angeles"),
    UFmt::STYLE_SHORT, UFmt::STYLE_SHORT, new ULoc(ULoc::ENGLISH_UNITED_STATES));
// 2/13/09, 3:31 PM
echo UFmt::timeWithStyles(new Tm(1234567890), new Tz("America/Los_Angeles"),
    UFmt::STYLE_MEDIUM, UFmt::STYLE_MEDIUM, new ULoc(ULoc::ENGLISH_UNITED_STATES));
// Feb 13, 2009, 3:31:30 PM
echo UFmt::timeWithStyles(new Tm(1234567890), new Tz("America/Los_Angeles"),
    UFmt::STYLE_LONG, UFmt::STYLE_LONG, new ULoc(ULoc::ENGLISH_UNITED_STATES));
// February 13, 2009 at 3:31:30 PM PST
echo UFmt::timeWithStyles(new Tm(1234567890), new Tz("America/Los_Angeles"),
    UFmt::STYLE_FULL, UFmt::STYLE_FULL, new ULoc(ULoc::ENGLISH_UNITED_STATES));
// Friday, February 13, 2009 at 3:31:30 PM Pacific Standard Time

echo UFmt::timeWithStyles(new Tm(1234567890), new Tz("Europe/Berlin"),
    UFmt::STYLE_SHORT, UFmt::STYLE_SHORT, new ULoc(ULoc::GERMAN_GERMANY));
// 14.02.09 00:31
echo UFmt::timeWithStyles(new Tm(1234567890), new Tz("Europe/Berlin"),
    UFmt::STYLE_MEDIUM, UFmt::STYLE_MEDIUM, new ULoc(ULoc::GERMAN_GERMANY));
// 14.02.2009 00:31:30
echo UFmt::timeWithStyles(new Tm(1234567890), new Tz("Europe/Berlin"),
    UFmt::STYLE_LONG, UFmt::STYLE_LONG, new ULoc(ULoc::GERMAN_GERMANY));
// 14. Februar 2009 00:31:30 MEZ
echo UFmt::timeWithStyles(new Tm(1234567890), new Tz("Europe/Berlin"),
    UFmt::STYLE_FULL, UFmt::STYLE_FULL, new ULoc(ULoc::GERMAN_GERMANY));
// Samstag, 14. Februar 2009 00:31:30 Mitteleuropäische Normalzeit
```

Or you can use `timeWithPattern` method to format the date/time according to a custom formatting pattern. Such patterns are described in [Date Format Patterns](http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Format_Patterns) of the Unicode Technical Standard #35.

# Batteries Included

## Input Filtering

The security of a web site or web application starts with the security of its policy on input processing. Even if you made sure that any internal link on your site will contain fields in its query string that will exactly match the expectations for those fields on the server side, nothing prevents anyone from changing a field in a request to make it have a wrong type or a value that is out of the sane limits. Additionally, if a string value that is sent with a request is expected to be of some particular kind, for example an email address, it would need to be validated as such. A failure to handle the input properly may result in an exploitable vulnerability, and not just with query strings sent in GET requests but also with POST requests or with any other HTTP method.

With that kept in mind, Phred implements the [CInputFilter](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CInputFilter.html) class (alias `IFi`). An input filter works by taking in a string that, according to the data type specified at the time of the object's construction, represents a boolean, integer, or floating-point value, an ASCII or Unicode string, an email, or a URL, and after the validation and, optionally, the sanitization of the value, the filter outputs a value of the respective type. Since query strings in GET and POST requests are allowed to look like "name[]=value0&name[]=value1" or "name[key0]=value0&name[key1]=value1", the class also lets you filter multidimensional arrays and maps.

For all output types but `CSTRING` (ASCII string) and `CUSTRING` (Unicode/ASCII string), an input string is initially trimmed from whitespace (including any Unicode whitespace) on both sides. An input string is recognized as a valid boolean value for "1", "true", "yes", and "on", and for "0", "false", "no", and "off", case-insensitively. An ASCII string containing a byte with a value greater than 127 is not considered valid. If the filter is set to ignore protocol absence in URLs, any valid URL with a missing protocol is filtered to the same URL but with the default protocol prepended.

When you are retrieving any kind the input that was received with a request, which is represented by the [CRequest](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CRequest.html) class (alias `Req`), filtering input values is not recommended, it is *required*. While usually undemanding and easygoing, Phred has slightly stepped out of its character on these important matters of security so that no user input is left unfiltered, ever.

A quick example:

```php
$maxPageNum = 10;

$filter = new IFi(IFi::INT);
$filter->setClampingMinMax(1, $maxPageNum);

// http://example.com/?page=11
$pageNum = Req::fieldGet("page", $filter, $success);  // filter is required
if ($success) {
    // The "page" field is both present and looks like an integer.
    echo $pageNum;  // 10
}

// http://example.com/?page=hi
$pageNum = Req::fieldGet("page", $filter, $success);  // filter is required
echo $success;  // false
```

## Files and Directories

Phred gathers practically all of the PHP's functionality related to files and puts it into a single class, which is [CFile](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CFile.html) (alias `Fl`). The class lets you check on files and directories as to whether they exist and on their attributes, read from and write to files, create files and directories, copy, move, rename, and delete files and directories, list items in directories, and search for items in directories with wildcards or regular expressions.

An object of the CFile class represents a file accessor that lets you perform sequential read/write operations on a file. By creating a file accessor, you open a file for a session of read/write operations on its contents. Any such accessor keeps track of the current reading/writing position, which is measured in bytes and points to the byte starting at which the next portion of data is going to be read from or written to.

Another class that falls into this category is [CFilePath](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CFilePath.html) (alias `Fp`). This class lets you extract file and directory names out of paths, get to know the name of the directory that is parent to a file or directory, extract file extensions, combine path components together, as well as normalize paths.

With Phred, any method that takes as a parameter a path to a file or directory can contain an alias to a Phred-specific directory and any such alias will be automatically resolved to the actual directory path by the method. For example, if a path contains "{{PHRED_PATH_TO_APP}}" in it, this alias will be replaced with the absolute path to the `Application` directory that is located inside the Phred's root directory. The names of the Phred's directory aliases can be found in `Bootstrap/Paths.php`.

## Omnivorous JSON

JSON is an essential element of many web sites and web applications. Servers use JSON to communicate data with user agents and mobile apps in the form of JavaScript-like objects and arrays. JSON is also the communication language used by many web-based APIs, not all of which however follow the standard strictly. The JSON decoding and encoding is implemented by the [CJson](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CJson.html) class (alias `Jn`).

Previously, PHP would force you into making an ambiguous choice about how a JSON object should be represented in the PHP world, as either a PHP object or an associative array. Although the choice is far from being clear, it may look like the PHP's associative array would be a bit more optimal pick since a JSON object is an associative array for all intents and purposes. But then again, if the JSON data happens to contain simple arrays, both JSON objects and JSON arrays would end up being represented by the very same PHP type, which defies the separation of associative and simple arrays in JSON.

In Phred, JSON objects are most naturally represented by OOP maps and JSON arrays by OOP arrays. So no more confusion when working with JSON data.

The CJson class lets you decode even malformed JSON strings. There are three "difficulty levels" that you can choose from. The strictest level is `STRICT`, according to which an input JSON string is expected to conform to the JSON standard exactly, then goes `STRICT_WITH_COMMENTS`, which allows for `//` and `/*` comments in a JSON string (the original JSON specification does not mention comments), and `LENIENT`, with which you can still successfully decode a JSON string that contains `//` and `/*` comments, uses single quotes on values (the JSON format requires double quotes), uses single quotes or no quotes at all on property names, or contains commas where they are redundant.

Let's see each strictness level at work:

```php
// With the default strictness.
$jsonStr = <<<'NDOC'
{
    "prop": "value"
}
NDOC;
$json = new Jn($jsonStr);
$decodedMap = $json->decode($success);
echo $success;  // true
$json = new Jn($decodedMap);
$json->setPrettyPrint(true);
echo $json->encode();
// {
//     "prop": "value"
// }


// With `STRICT_WITH_COMMENTS` strictness.
$jsonStr = <<<'NDOC'
{
    // comment
    "prop0": "value",  // comment
    "prop1": /*comment*/ ["value0", /*comment*/ "value1"]
}
NDOC;
$json = new Jn($jsonStr, Jn::STRICT_WITH_COMMENTS);
$decodedMap = $json->decode($success);
echo $success;  // true
$json = new Jn($decodedMap);
$json->setPrettyPrint(true);
echo $json->encode();
// {
//     "prop0": "value",
//     "prop1": [
//         "value0",
//         "value1"
//     ]
// }


// With `LENIENT` strictness.
$jsonStr = <<<'NDOC'
{
    // comment
    "prop0": "value",  // comment
    'prop1': /*comment*/ ["value0", /*comment*/ 'value1', ],  // a trailing comma
    prop2: {'subProp0': "value0", subProp1: 'value1', },  // a trailing comma
    prop3: [true, 1234, 56.78],  // a trailing comma
}
NDOC;
$json = new Jn($jsonStr, Jn::LENIENT);
$decodedMap = $json->decode($success);
echo $success;  // true
$json = new Jn($decodedMap);
$json->setPrettyPrint(true);
echo $json->encode();
// {
//     "prop0": "value",
//     "prop1": [
//         "value0",
//         "value1"
//     ],
//     "prop2": {
//         "subProp0": "value0",
//         "subProp1": "value1"
//     },
//     "prop3": [
//         true,
//         1234,
//         56.78
//     ]
// }
```

## Mailing

With the self-contained [CMail](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CMail.html) class (alias `Ml`), sending even a compound email message becomes something trivial. The class lets you send plain-text and HTML emails with attached content, embedded content, and with support for other email features.

To create an email message, you would use `makeSmtp`, `makeSystem`, or `makeGmail` static method. And in order for a message to be sent, specifying any email field other than "From" and "To" is optional. Setting the "From" field can also be optional if the "Sender" or "Return Address" field is specified. Setting the "To" field is not required if the "Cc" or "Bcc" field is set. The "From" and "To" fields can be set either when creating an email message or afterwards with the appropriate `set...` or `add...` method.

To illustrate, let's send an email over an SMTP mail server running at example.com:

```php
$mail = Ml::makeSmtp("smtp.example.com", "john.doe", "password",
    "john.doe@example.com", "jane.roe@elpmaxe.com");
$mail->setSubject("Subject");
$mail->setBody("Body");
$mail->attachFile("/path/to/Attachment.zip");
$numSuccessfulRecipients = $mail->send();
if ($numSuccessfulRecipients == 1) {
    // The message was successfully delivered into the Jane Roe's mailbox.
}
```

Or let's send a fancy HTML email with images embedded right into the HTML code, also sending a CC copy to another recipient:

```php
$mail = Ml::makeSmtp("smtp.example.com", "john.doe", "password",
    "john.doe@example.com", "jane.roe@elpmaxe.com");
$cid = $mail->embeddableCidForFile("/path/to/image.png");
$mail->setSubject("Subject");
$mail->setBody(
    "<!doctype html> ... <img src='" . $cid . "' alt='Image'> ...",
    "text/html");
$mail->addCc("john.q.public@elpmaxe.com");
$mail->send();
```

## Requesting

The [CInetRequest](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CInetRequest.html) class (alias `InetReq`) lets you make HTTP, HTTPS, FTP, and FTPS requests over the Internet, as a part of a cookies-enabled session if used together with the [CInetSession](http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/doc/classes/CInetSession.html) class (alias `InetSess`).

For the HTTP and HTTPS protocols, the fully supported methods are GET, POST, PUT, DELETE, HEAD, and LIST. With HTTP/HTTPS, you can upload a file to a remote location via POST method (this flavor of POST method is referred to as `HTTP_UPLOAD`) or via PUT method. Aside from other purposes, the PUT and DELETE methods can be used for communication with RESTful APIs.

As an example, let's send an HTTP GET request to some web-based API, asking for the description of an item:

```php
$req = new InetReq("http://example.com/item/1234/desc");  // HTTP_GET is the default
$req->setRequestTimeout(15);
$res = $req->send($success);
echo $success;  // true if successful, false otherwise
echo $res;      // the response
```

Or, if the API looks into the HTTP headers of a received request to see if the requested description should appear in a specific language, we could use it to ask for the description of an item in German by specifying the language in an HTTP header:

```php
$req = new InetReq("http://example.com/item/1234/desc");
$req->setRequestTimeout(15);
$req->addHeader("lang", "de");
$res = $req->send($success);
echo $success;  // true if successful, false otherwise
echo $res;      // the response
```

And if the API allows for it, we could update the description of an item by means of an HTTP POST request:

```php
$req = new InetReq("http://example.com/item/1234", InetReq::HTTP_POST);
$req->setRequestTimeout(15);
$req->addPostField("desc", "Description ...");
$req->send($success);
echo $success;  // true if successful, false otherwise
```

For downloading a response to a file, `downloadFile` static method of the CInetRequest class provides the convenience of downloading responses over any protocol without the need for creating an object or specifying the request type:

```php
$success = InetReq::downloadFile("http://example.com/?get=1",
    "/path/to/downloaded/file", 30);
echo $success;  // true if successful, false otherwise
```

When multiple requests need to be sent or when the destination server is expected to set cookies that would need to be sent back later with subsequent requests, you can use the CInetSession class to improve performance by letting the requests be processed in parallel, to enable full cookie support, or both. As much as it is allowed by the maximum number of concurrent requests, which is configurable, a request in a session does not have to wait for the previously sent request to complete in order to be sent out.

With the CInetSession class, a session is not limited just to the initially added requests and any number of subsequent requests can be queued into the session from a callback function that, if specified, is invoked by the session after a request is complete:

```php
$sess = new InetSess();
$req = new InetReq("http://example.com/item/1234/desc");
$sess->addRequest($req);
$req = new InetReq("http://example.com/item/5678/desc");
$sess->addRequest($req, function ($success, $res, $req, $sess) {
    echo $success;  // true if successful, false otherwise
    echo $res;      // the response
    // ...
    // After processing the response, it appears that we would also need
    // the description of one more item.
    $anotherReq = new InetReq("http://example.com/item/9012/desc");
    $sess->addRequest($anotherReq, function ($success, $res, $req, $sess) {
        echo $success;  // true if successful, false otherwise
        echo $res;      // the response
        // Add more requests to the session if necessary.
        // ...
    });
});
$sess->start();
```

# Backward Compatibility

Any library, API, or any other third-party component is backward-compatible with Phred as long as it is installed via Composer, which is de facto the standard package manager for PHP. Even Facebook is recommending Composer for installing its [Facebook SDK](https://developers.facebook.com/docs/php/gettingstarted/).

From the perspective of any third-party component, every OOP string is just a regular PHP string without any memory overhead or use restrictions. A PHP's native array becomes an OOP array when a third-party component in any way outputs it and the PHP array's keys are sequential (0, 1, 2, ...) or, if the array's keys are non-sequential, it arrives as an OOP map, just like you would expect. And when you pass an OOP array or an OOP map to a third-party component, that library or API receives it as a plain PHP array in all cases, just like the third-party component would expect.

The Phred's backward compatibility does not only cover parameters in methods and functions, but also return values and values being output by means of parameters that are declared by reference in methods and functions of third-party components. Furthermore, the backward compatibility comes into play even when you get or set a public property of an object of a class that was brought in by a third-party component, whether or not the class is using `__get` or `__set` "magic" methods for property access.

# Donate

**Developing Phred and maintaining decent documentation takes effort and time. And the project has only started. Donations are highly appreciated.**

<p align="center">
  <a href="http://htmlpreview.github.com/?https://github.com/nazariyg/Phred/blob/master/readme-files/donate.html"><img src="readme-files/ppd-button.png"/></a>
</p>

<p align="right">
  <br>
  <em>You can contact me at <a href="mailto:nazariyg@gmail.com">nazariyg@gmail.com</a></em>
</p>

