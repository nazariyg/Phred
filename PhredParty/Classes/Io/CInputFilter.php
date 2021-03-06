<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that takes all the surprise out of communicating with users and the rest of the outer world by ensuring
 * that any string that is expected to represent a boolean, integer, or floating-point value, an ASCII or Unicode
 * string, an email, a URL etc. does exactly that, also being able to validate and sanitize values by various
 * parameters in the process of filtering.
 *
 * **You can refer to this class by its alias, which is** `IFi`.
 *
 * An input filter works by taking a string or an array or map containing strings as an input for `filter` method and,
 * after the validation and sanitization of the string(s), outputting the value(s) represented by the string(s) in
 * accordance with the expected output type(s) that you provided to the constructor when creating the filter's object.
 *
 * For all output types but `CSTRING` (ASCII string) and `CUSTRING` (Unicode/ASCII string), an input string is trimmed
 * from whitespace (including any Unicode whitespace) on both sides as an initial step. An input string is recognized
 * as a valid boolean value for "1", "true", "yes", and "on", as well as for "0", "false", "no", and "off",
 * case-insensitively. An ASCII string containing a byte with a value greater than 127 is not considered to be valid.
 * If the filter is set to ignore protocol absence in URLs, any valid URL with a missing protocol is filtered to the
 * same URL but with the default protocol indicated (based on the value of `CUrl::DEFAULT_PROTOCOL`).
 *
 * **Defaults for validation:**
 *
 * * Allow leading zeros in a valid integer or floating-point value is `true`.
 * * Allow a valid integer to be in the hex notation, i.e. "0x" followed by hex digits, is `false`.
 * * Allow thousand groups in a valid integer or floating-point value to be separated with commas is `true`.
 * * Ignore protocol absence when validating a URL is `true`.
 * * Validate an IP as IPv6 instead of IPv4 is `false`.
 * * Validate an IP as either IPv4 or IPv6 is `false`.
 * * Allow a valid IP to be in a private IP range is `true`.
 * * Allow a valid IP to be in a reserved IP range is `true`.
 *
 * **Defaults for sanitization:**
 *
 * * Keep abnormal newlines (that are not LF) in a string value is `false`.
 * * Keep non-printable characters in a string value is `false`.
 * * Keep tabs and newlines in a string value is `true`.
 * * Keep whitespace on both sides of a string value is `true`.
 * * Keep consecutive whitespace characters in a string value and do not shrink them to a single space is `true`.
 *
 * **Other defaults:**
 *
 * * JSON strictness for array/map decoding is `CJson::STRICT`.
 */

// Method signatures:
//   __construct ($expectedType, $collectionInputFilters = null)
//   void setDefault ($value)
//   void setJsonStrictness ($strictness)
//   enum expectedType ()
//   mixed defaultValue ()
//   mixed collectionInputFilters ()
//   void setValidMin ($min)
//   void setValidMax ($max)
//   void setValidMinMax ($min, $max)
//   void setAllowLeadingZeros ($enable)
//   void setAllowComma ($enable)
//   void setIgnoreProtocolAbsence ($enable)
//   void setAllowHex ($enable)
//   void setIpV6 ($enable)
//   void setIpV4OrV6 ($enable)
//   void setAllowPrivateRange ($enable)
//   void setAllowReservedRange ($enable)
//   void setClampingMin ($min)
//   void setClampingMax ($max)
//   void setClampingMinMax ($min, $max)
//   void setKeepAbnormalNewlines ($enable)
//   void setKeepNonPrintable ($enable)
//   void setKeepTabsAndNewlines ($enable)
//   void setKeepSideSpacing ($enable)
//   void setKeepExtraSpacing ($enable)
//   mixed filter ($inputStringOrDecodedCollection, &$success)

class CInputFilter extends CRootClass
{
    // Expected output types.
    /**
     * `enum` Boolean type.
     *
     * @var enum
     */
    const BOOL = 0;
    /**
     * `enum` Integer type.
     *
     * @var enum
     */
    const INT = 1;
    /**
     * `enum` Floating-point type.
     *
     * @var enum
     */
    const FLOAT = 2;
    /**
     * `enum` ASCII string.
     *
     * @var enum
     */
    const CSTRING = 3;
    /**
     * `enum` Unicode string or ASCII string.
     *
     * @var enum
     */
    const CUSTRING = 4;
    /**
     * `enum` Array containing values of the supported types, other arrays, or maps.
     *
     * @var enum
     */
    const CARRAY = 5;
    /**
     * `enum` Map containing values of the supported types, arrays, or other maps.
     *
     * @var enum
     */
    const CMAP = 6;
    /**
     * `enum` Email address.
     *
     * @var enum
     */
    const EMAIL = 7;
    /**
     * `enum` URL address.
     *
     * @var enum
     */
    const URL = 8;
    /**
     * `enum` IP address.
     *
     * @var enum
     */
    const IP = 9;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a filter suited for a specified data type to be outputted.
     *
     * @param  enum $expectedType The output data type that is expected (see [Summary](#summary)).
     * @param  mixed $collectionInputFilters **OPTIONAL.** *Required only when the expected type is `CARRAY` or
     * `CMAP`*. The array or map containing the filters to be applied to the elements in the input array if the
     * expected type is `CARRAY` or to the values in the input map if the expected type is `CMAP`. If the input value
     * is going to be an array, the number of filters in this parameter should match the length of the input array, and
     * if the input value is going to be a map, the keys in this parameter should match the keys in the input map. Same
     * as input arrays and maps, this parameter can be multidimensional in order to correspond with the input value.
     */

    public function __construct ($expectedType, $collectionInputFilters = null)
    {
        assert( 'is_enum($expectedType) && !($expectedType != self::CARRAY && $expectedType != self::CMAP && ' .
                'isset($collectionInputFilters)) && !($expectedType == self::CARRAY && ' .
                '!is_carray($collectionInputFilters)) && !($expectedType == self::CMAP && ' .
                '!is_cmap($collectionInputFilters))', vs(isset($this), get_defined_vars()) );

        $this->m_expectedType = $expectedType;
        $this->m_collectionInputFilters = $collectionInputFilters;

        switch ( $expectedType )
        {
        case self::BOOL:
            $this->m_defaultValue = false;
            break;
        case self::INT:
            $this->m_defaultValue = 0;
            break;
        case self::FLOAT:
            $this->m_defaultValue = 0.0;
            break;
        case self::CSTRING:
        case self::CUSTRING:
            $this->m_defaultValue = "";
            break;
        case self::CARRAY:
            $this->m_defaultValue = CArray::make();
            break;
        case self::CMAP:
            $this->m_defaultValue = CMap::make();
            break;
        case self::EMAIL:
        case self::URL:
        case self::IP:
            $this->m_defaultValue = "";
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the default value to be returned by `filter` method in case if the input value does not pass the filtering
     * process.
     *
     * @param  mixed $value The default value for the filter.
     *
     * @return void
     */

    public function setDefault ($value)
    {
        assert( '($this->m_expectedType == self::BOOL && is_bool($value)) || ' .
                '($this->m_expectedType == self::INT && is_int($value)) || ' .
                '($this->m_expectedType == self::FLOAT && is_float($value)) || ' .
                '($this->m_expectedType == self::CSTRING && is_cstring($value)) || ' .
                '($this->m_expectedType == self::CUSTRING && is_cstring($value)) || ' .
                '($this->m_expectedType == self::CARRAY && is_carray($value)) || ' .
                '($this->m_expectedType == self::CMAP && is_cmap($value)) || ' .
                '($this->m_expectedType == self::EMAIL && is_cstring($value)) || ' .
                '($this->m_expectedType == self::URL && is_cstring($value)) || ' .
                '($this->m_expectedType == self::IP && is_cstring($value))', vs(isset($this), get_defined_vars()) );

        $this->m_defaultValue = $value;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the JSON decoding strictness to be used for JSON input.
     *
     * @param  enum $strictness The JSON decoding strictness (see [CJson Summary](CJson.html#summary)).
     *
     * @return void
     */

    public function setJsonStrictness ($strictness)
    {
        assert( 'is_enum($strictness)', vs(isset($this), get_defined_vars()) );
        $this->m_jsonStrictness = $strictness;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the output data type of a filter.
     *
     * @return enum The filter's output data type.
     */

    public function expectedType ()
    {
        return $this->m_expectedType;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the default value of a filter.
     *
     * @return mixed The filter's default value.
     */

    public function defaultValue ()
    {
        return oop_x($this->m_defaultValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the array or map containing the filters associated with the values in the input array or map for a
     * filter.
     *
     * @return mixed The array or map containing the filters associated with the values in the input array or map.
     */

    public function collectionInputFilters ()
    {
        assert( 'isset($this->m_collectionInputFilters)', vs(isset($this), get_defined_vars()) );
        return $this->m_collectionInputFilters;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, sets the minimum acceptable value for an input value to be considered valid.
     *
     * This method is one of the validation methods.
     *
     * @param  mixed $min The minimum acceptable value. The parameter's type should match the expected output type.
     *
     * @return void
     */

    public function setValidMin ($min)
    {
        assert( '($this->m_expectedType == self::INT && is_int($min)) || ' .
                '($this->m_expectedType == self::FLOAT && is_float($min))', vs(isset($this), get_defined_vars()) );

        if ( $this->m_expectedType == self::INT )
        {
            $this->m_intValidMin = $min;
        }
        else  // $this->m_expectedType = self::FLOAT
        {
            $this->m_floatValidMin = $min;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, sets the maximum acceptable value for an input value to be considered valid.
     *
     * This method is one of the validation methods.
     *
     * @param  mixed $max The maximum acceptable value. The parameter's type should match the expected output type.
     *
     * @return void
     */

    public function setValidMax ($max)
    {
        assert( '($this->m_expectedType == self::INT && is_int($max)) || ' .
                '($this->m_expectedType == self::FLOAT && is_float($max))', vs(isset($this), get_defined_vars()) );

        if ( $this->m_expectedType == self::INT )
        {
            $this->m_intValidMax = $max;
        }
        else  // $this->m_expectedType = self::FLOAT
        {
            $this->m_floatValidMax = $max;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, sets the minimum and maximum acceptable values for an input value to be
     * considered valid.
     *
     * This method is one of the validation methods.
     *
     * @param  mixed $min The minimum acceptable value. The parameter's type should match the expected output type.
     * @param  mixed $max The maximum acceptable value. The parameter's type should match the expected output type.
     *
     * @return void
     */

    public function setValidMinMax ($min, $max)
    {
        assert( '($this->m_expectedType == self::INT && is_int($min) && is_int($max)) || ' .
                '($this->m_expectedType == self::FLOAT && is_float($min) && is_float($max))',
            vs(isset($this), get_defined_vars()) );
        assert( '$min <= $max', vs(isset($this), get_defined_vars()) );

        if ( $this->m_expectedType == self::INT )
        {
            $this->m_intValidMin = $min;
            $this->m_intValidMax = $max;
        }
        else  // $this->m_expectedType = self::FLOAT
        {
            $this->m_floatValidMin = $min;
            $this->m_floatValidMax = $max;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, allows or disallows a string with an input value to start with one or more
     * zeros.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $enable `true` to allow an input value to contain leading zeros, `false` otherwise.
     *
     * @return void
     */

    public function setAllowLeadingZeros ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_expectedType == self::INT || ' .
                '$this->m_expectedType == self::FLOAT', vs(isset($this), get_defined_vars()) );

        $this->m_allowLeadingZeros = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, allows or disallows the thousand groups in a string with an input value to
     * be separated with commas.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $enable `true` to allow an input value to contain commas, `false` otherwise.
     *
     * @return void
     */

    public function setAllowComma ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_expectedType == self::INT || ' .
                '$this->m_expectedType == self::FLOAT', vs(isset($this), get_defined_vars()) );

        $this->m_allowComma = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `URL` output type, sets whether to ignore the absence of a protocol in an input URL.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $enable `true` to ignore the absence of a protocol in an input URL, `false` otherwise.
     *
     * @return void
     */

    public function setIgnoreProtocolAbsence ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_expectedType == self::URL', vs(isset($this), get_defined_vars()) );

        $this->m_ignoreProtocolAbsence = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` output type, allows or disallows a string with an input value to be a hexadecimal integer prefixed
     * with "0x".
     *
     * This method is one of the validation methods.
     *
     * @param  bool $enable `true` to allow an input value to be a hexadecimal integer prefixed with "0x", `false`
     * otherwise.
     *
     * @return void
     */

    public function setAllowHex ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_expectedType == self::INT', vs(isset($this), get_defined_vars()) );

        $this->m_allowHex = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `IP` output type, sets whether an input IP is expected to be IPv6 only.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $enable `true` if an input IP is expected to be IPv6 only, `false` otherwise.
     *
     * @return void
     */

    public function setIpV6 ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_expectedType == self::IP', vs(isset($this), get_defined_vars()) );

        $this->m_ipV6 = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `IP` output type, sets whether an input IP is allowed to be IPv6 in addition to IPv4.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $enable `true` if an input IP is allowed to be IPv6 in addition to IPv4, `false` otherwise.
     *
     * @return void
     */

    public function setIpV4OrV6 ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_expectedType == self::IP', vs(isset($this), get_defined_vars()) );

        $this->m_ipV4OrV6 = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `IP` output type, allows or disallows an input IP to be in a private IP range.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $enable `true` to allow an input IP to be in a private IP range, `false` otherwise.
     *
     * @return void
     */

    public function setAllowPrivateRange ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_expectedType == self::IP', vs(isset($this), get_defined_vars()) );

        $this->m_allowPrivateRange = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `IP` output type, allows or disallows an input IP to be in a reserved IP range.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $enable `true` to allow an input IP to be in a reserved IP range, `false` otherwise.
     *
     * @return void
     */

    public function setAllowReservedRange ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_expectedType == self::IP', vs(isset($this), get_defined_vars()) );

        $this->m_allowReservedRange = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, sets the minimum acceptable value to which an input value should be clamped
     * if needed.
     *
     * This method is one of the sanitization methods.
     *
     * @param  mixed $min The minimum acceptable value. The parameter's type should match the expected output type.
     *
     * @return void
     */

    public function setClampingMin ($min)
    {
        assert( '($this->m_expectedType == self::INT && is_int($min)) || ' .
                '($this->m_expectedType == self::FLOAT && is_float($min))', vs(isset($this), get_defined_vars()) );

        if ( $this->m_expectedType == self::INT )
        {
            $this->m_intClampingMin = $min;
        }
        else  // $this->m_expectedType = self::FLOAT
        {
            $this->m_floatClampingMin = $min;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, sets the maximum acceptable value to which an input value should be clamped
     * if needed.
     *
     * This method is one of the sanitization methods.
     *
     * @param  mixed $max The maximum acceptable value. The parameter's type should match the expected output type.
     *
     * @return void
     */

    public function setClampingMax ($max)
    {
        assert( '($this->m_expectedType == self::INT && is_int($max)) || ' .
                '($this->m_expectedType == self::FLOAT && is_float($max))', vs(isset($this), get_defined_vars()) );

        if ( $this->m_expectedType == self::INT )
        {
            $this->m_intClampingMax = $max;
        }
        else  // $this->m_expectedType = self::FLOAT
        {
            $this->m_floatClampingMax = $max;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, sets the minimum and maximum acceptable values to which an input value
     * should be clamped if needed.
     *
     * This method is one of the sanitization methods.
     *
     * @param  mixed $min The minimum acceptable value. The parameter's type should match the expected output type.
     * @param  mixed $max The maximum acceptable value. The parameter's type should match the expected output type.
     *
     * @return void
     */

    public function setClampingMinMax ($min, $max)
    {
        assert( '($this->m_expectedType == self::INT && is_int($min) && is_int($max)) || ' .
                '($this->m_expectedType == self::FLOAT && is_float($min) && is_float($max))',
            vs(isset($this), get_defined_vars()) );
        assert( '$min <= $max', vs(isset($this), get_defined_vars()) );

        if ( $this->m_expectedType == self::INT )
        {
            $this->m_intClampingMin = $min;
            $this->m_intClampingMax = $max;
        }
        else  // $this->m_expectedType = self::FLOAT
        {
            $this->m_floatClampingMin = $min;
            $this->m_floatClampingMax = $max;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `CSTRING` and `CUSTRING` output types, sets whether to keep any non-LF newlines in the output string or
     * convert any such newlines to LF instead.
     *
     * This method is one of the sanitization methods.
     *
     * @param  bool $enable `true` to keep any non-LF newlines in the output string, `false` to convert any such
     * newlines to LF.
     *
     * @return void
     */

    public function setKeepAbnormalNewlines ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_expectedType == self::CSTRING || ' .
                '$this->m_expectedType == self::CUSTRING', vs(isset($this), get_defined_vars()) );

        $this->m_keepAbnormalNewlines = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `CSTRING` and `CUSTRING` output types, sets whether to keep any non-printable characters in the output
     * string or remove any such characters.
     *
     * An ASCII or Unicode space is treated as a printable character in the context of this method.
     *
     * This method is one of the sanitization methods.
     *
     * @param  bool $enable `true` to keep any non-printable characters in the output string, `false` to remove any
     * such characters.
     *
     * @return void
     */

    public function setKeepNonPrintable ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_expectedType == self::CSTRING || ' .
                '$this->m_expectedType == self::CUSTRING', vs(isset($this), get_defined_vars()) );

        $this->m_keepNonPrintable = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `CSTRING` and `CUSTRING` output types, sets whether to keep any tabs and newlines in the output string or to
     * remove them.
     *
     * This method is one of the sanitization methods.
     *
     * @param  bool $enable `true` to keep any tabs and newlines in the output string, `false` to remove them.
     *
     * @return void
     */

    public function setKeepTabsAndNewlines ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_expectedType == self::CSTRING || ' .
                '$this->m_expectedType == self::CUSTRING', vs(isset($this), get_defined_vars()) );

        $this->m_keepTabsAndNewlines = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `CSTRING` and `CUSTRING` output types, sets whether to keep any whitespace at the both sides of the output
     * string or to trim it.
     *
     * This method is one of the sanitization methods.
     *
     * @param  bool $enable `true` to keep any whitespace at the both sides of the output string, `false` to trim it.
     *
     * @return void
     */

    public function setKeepSideSpacing ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_expectedType == self::CSTRING || ' .
                '$this->m_expectedType == self::CUSTRING', vs(isset($this), get_defined_vars()) );

        $this->m_keepSideSpacing = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `CSTRING` and `CUSTRING` output types, sets whether to keep any whitespace at the both sides of the output
     * string and to keep any sequence of whitespace characters within the string as-is or to trim the string from
     * whitespace on both sides and to replace any sequence of whitespace characters with a single space character.
     *
     * This method is one of the sanitization methods.
     *
     * @param  bool $enable `true` to keep any whitespace at the both sides of the output string and to keep any
     * sequence of whitespace characters within the string as-is, `false` to trim the string from whitespace on both
     * sides and to replace any sequence of whitespace characters with a single space character.
     *
     * @return void
     */

    public function setKeepExtraSpacing ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_expectedType == self::CSTRING || ' .
                '$this->m_expectedType == self::CUSTRING', vs(isset($this), get_defined_vars()) );

        $this->m_keepExtraSpacing = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Filters a string or a collection of strings according to the expected output type(s) and returns the output
     * value(s).
     *
     * @param  mixed $inputStringOrDecodedCollection The string to be filtered or the array or map containing the
     * strings to be filtered. If the parameter's value is a JSON-encoded string, the output value is going to be
     * either an array or map.
     * @param  reference $success **OUTPUT.** After the method is called, the value of this parameter tells whether
     * the filtering was successful.
     *
     * @return mixed The output value or a collection of values of the expected type(s) after having been put through
     * the filter.
     */

    public function filter ($inputStringOrDecodedCollection, &$success)
    {
        assert( 'is_cstring($inputStringOrDecodedCollection) || is_collection($inputStringOrDecodedCollection)',
            vs(isset($this), get_defined_vars()) );

        $success = true;

        if ( $this->m_expectedType != self::CARRAY &&
             $this->m_expectedType != self::CMAP )
        {
            // The expected output type is not a collection; the input value must be of string type.

            if ( !is_cstring($inputStringOrDecodedCollection) )
            {
                $success = false;
                return oop_x($this->m_defaultValue);
            }

            $inputString = $inputStringOrDecodedCollection;

            if ( $this->m_expectedType == self::BOOL ||
                 $this->m_expectedType == self::INT ||
                 $this->m_expectedType == self::FLOAT ||
                 $this->m_expectedType == self::EMAIL ||
                 $this->m_expectedType == self::URL ||
                 $this->m_expectedType == self::IP )
            {
                // Trim the input string on both sides from whitespace, including Unicode whitespace and control
                // characters.
                $trimmingSubjectRe = CUString::TRIMMING_AND_SPACING_NORM_SUBJECT_RE;
                $inputString = CRegex::remove($inputString, "/^($trimmingSubjectRe)+|($trimmingSubjectRe)+\\z/u");
            }

            // Pre-process the string for integer and floating-point types.
            $looksLikeHex;
            if ( $this->m_expectedType == self::INT ||
                 $this->m_expectedType == self::FLOAT )
            {
                if ( CString::startsWith($inputString, "+") )
                {
                    // Remove the plus sign.
                    $inputString = CString::substr($inputString, 1);
                }

                $looksLikeHex = CRegex::find($inputString, "/^-?0x/i");

                if ( $this->m_allowLeadingZeros &&
                     !($this->m_expectedType == self::INT && $this->m_allowHex && $looksLikeHex) )
                {
                    // Remove any leading zeros (except for special cases).
                    $inputString = CRegex::replace($inputString, "/^(\\D*)0*(?!\\b)/", "$1");
                }

                if ( $this->m_allowComma )
                {
                    $inputString = CRegex::remove($inputString, "/,(?=\\d{3}\\b)/");
                }
            }

            // Validate and sanitize the value according to its expected type.

            if ( $this->m_expectedType == self::BOOL )
            {
                if ( !CRegex::find($inputString, "/^(1|true|yes|on|0|false|no|off)\\z/i") )
                {
                    $success = false;
                    return $this->m_defaultValue;
                }

                return (
                    CString::equals($inputString, "1") ||
                    CString::equalsCi($inputString, "true") ||
                    CString::equalsCi($inputString, "yes") ||
                    CString::equalsCi($inputString, "on") );
            }

            if ( $this->m_expectedType == self::INT )
            {
                $value;
                if ( !($this->m_allowHex && $looksLikeHex) )
                {
                    // Regular.
                    if ( !CRegex::find($inputString, "/^-?(?!0(?!\\b))\\d+\\z/") )
                    {
                        $success = false;
                        return $this->m_defaultValue;
                    }
                    $value = CString::toInt($inputString);
                }
                else
                {
                    // Hex.
                    if ( !CRegex::find($inputString, "/^-?0x[0-9A-F]+\\z/i") )
                    {
                        $success = false;
                        return $this->m_defaultValue;
                    }
                    $value = CString::toIntFromHex($inputString);
                }

                if ( (isset($this->m_intValidMin) && $value < $this->m_intValidMin) ||
                     (isset($this->m_intValidMax) && $value > $this->m_intValidMax) )
                {
                    $success = false;
                    return $this->m_defaultValue;
                }

                if ( isset($this->m_intClampingMin) && $value < $this->m_intClampingMin )
                {
                    $value = $this->m_intClampingMin;
                }
                if ( isset($this->m_intClampingMax) && $value > $this->m_intClampingMax )
                {
                    $value = $this->m_intClampingMax;
                }

                return $value;
            }

            if ( $this->m_expectedType == self::FLOAT )
            {
                if ( !CRegex::find($inputString, "/^-?(?!0(?!\\b))\\d*\\.?\\d+(e[\\-+]?\\d+)?\\z/i") )
                {
                    $success = false;
                    return $this->m_defaultValue;
                }

                $value = CString::toFloat($inputString);

                if ( (isset($this->m_floatValidMin) && $value < $this->m_floatValidMin) ||
                     (isset($this->m_floatValidMax) && $value > $this->m_floatValidMax) )
                {
                    $success = false;
                    return $this->m_defaultValue;
                }

                if ( isset($this->m_floatClampingMin) && $value < $this->m_floatClampingMin )
                {
                    $value = $this->m_floatClampingMin;
                }
                if ( isset($this->m_floatClampingMax) && $value > $this->m_floatClampingMax )
                {
                    $value = $this->m_floatClampingMax;
                }

                return $value;
            }

            if ( $this->m_expectedType == self::CSTRING )
            {
                $value = $inputString;

                if ( !CString::isValid($value) )
                {
                    $success = false;
                    return $this->m_defaultValue;
                }

                if ( !$this->m_keepAbnormalNewlines )
                {
                    $value = CString::normNewlines($value);
                }

                if ( !$this->m_keepNonPrintable )
                {
                    if ( !$this->m_keepTabsAndNewlines )
                    {
                        $value = CRegex::remove($value, "/[\\x00-\\x1F\\x7F-\\xFF]/");
                    }
                    else
                    {
                        $value = CRegex::remove($value, "/[\\x00-\\x1F\\x7F-\\xFF](?<![\\x09\\x0A\\x0D])/");
                    }
                }
                else if ( !$this->m_keepTabsAndNewlines )
                {
                    $value = CRegex::remove($value, "/[\\x09\\x0A\\x0D]/");
                }

                if ( !$this->m_keepSideSpacing )
                {
                    $value = CString::trim($value);
                }

                if ( !$this->m_keepExtraSpacing )
                {
                    $value = CString::normSpacing($value);
                }

                return $value;
            }

            if ( $this->m_expectedType == self::CUSTRING )
            {
                $value = $inputString;

                if ( !CUString::isValid($value) )
                {
                    $success = false;
                    return $this->m_defaultValue;
                }

                if ( !$this->m_keepAbnormalNewlines )
                {
                    $value = CUString::normNewlines($value);
                }

                if ( !$this->m_keepNonPrintable )
                {
                    if ( !$this->m_keepTabsAndNewlines )
                    {
                        $value = CRegex::remove($value, "/\\p{C}|\\p{Zl}|\\p{Zp}/u");
                    }
                    else
                    {
                        $value = CRegex::remove($value, "/\\p{C}(?<!\\x{0009}|\\x{000A}|\\x{000D})/u");
                    }
                }
                else if ( !$this->m_keepTabsAndNewlines )
                {
                    $value = CRegex::remove($value, "/\\x{0009}|\\x{000A}|\\x{000D}|\\p{Zl}|\\p{Zp}/u");
                }

                if ( !$this->m_keepSideSpacing )
                {
                    $value = CUString::trim($value);
                }

                if ( !$this->m_keepExtraSpacing )
                {
                    $value = CUString::normSpacing($value);
                }

                return $value;
            }

            if ( $this->m_expectedType == self::EMAIL )
            {
                $value = filter_var($inputString, FILTER_VALIDATE_EMAIL);
                if ( !is_cstring($value) )
                {
                    $success = false;
                    return $this->m_defaultValue;
                }
                return $value;
            }

            if ( $this->m_expectedType == self::URL )
            {
                $value = $inputString;
                if ( !CUrl::isValid($value, $this->m_ignoreProtocolAbsence) )
                {
                    $success = false;
                    return $this->m_defaultValue;
                }
                if ( $this->m_ignoreProtocolAbsence )
                {
                    $value = CUrl::ensureProtocol($value);
                }
                return $value;
            }

            if ( $this->m_expectedType == self::IP )
            {
                $value = $inputString;
                $options = CBitField::ALL_UNSET;
                if ( !$this->m_allowPrivateRange )
                {
                    $options |= CIp::DISALLOW_PRIVATE_RANGE;
                }
                if ( !$this->m_allowReservedRange )
                {
                    $options |= CIp::DISALLOW_RESERVED_RANGE;
                }
                $isValid;
                if ( !$this->m_ipV6 && !$this->m_ipV4OrV6 )
                {
                    $isValid = CIp::isValidV4($value, $options);
                }
                else if ( !$this->m_ipV4OrV6 )
                {
                    $isValid = CIp::isValidV6($value, $options);
                }
                else
                {
                    $isValid = ( CIp::isValidV4($value, $options) || CIp::isValidV6($value, $options) );
                }
                if ( !$isValid )
                {
                    $success = false;
                    return $this->m_defaultValue;
                }
                return $value;
            }
        }
        else if ( $this->m_expectedType == self::CARRAY )
        {
            if ( !is_cstring($inputStringOrDecodedCollection) && !is_carray($inputStringOrDecodedCollection) )
            {
                $success = false;
                return oop_x($this->m_defaultValue);
            }

            $value;
            if ( is_cstring($inputStringOrDecodedCollection) )
            {
                // Assume JSON format for the input string.
                $json = new CJson($inputStringOrDecodedCollection, $this->m_jsonStrictness);
                $value = $json->decode($success);
                if ( !$success )
                {
                    return oop_x($this->m_defaultValue);
                }
                if ( !is_carray($value) )
                {
                    $success = false;
                    return oop_x($this->m_defaultValue);
                }
            }
            else  // a CArray
            {
                $value = $inputStringOrDecodedCollection;
            }

            $value = self::recurseCollectionFiltering($value, $this->m_collectionInputFilters, $success, 0);
            if ( !$success )
            {
                return oop_x($this->m_defaultValue);
            }

            return $value;
        }
        else  // $this->m_expectedType = self::CMAP
        {
            if ( !is_cstring($inputStringOrDecodedCollection) && !is_cmap($inputStringOrDecodedCollection) )
            {
                $success = false;
                return oop_x($this->m_defaultValue);
            }

            $value;
            if ( is_cstring($inputStringOrDecodedCollection) )
            {
                // Assume JSON format for the input string.
                $json = new CJson($inputStringOrDecodedCollection, $this->m_jsonStrictness);
                $value = $json->decode($success);
                if ( !$success )
                {
                    return oop_x($this->m_defaultValue);
                }
                if ( !is_cmap($value) )
                {
                    $success = false;
                    return oop_x($this->m_defaultValue);
                }
            }
            else  // a CMap
            {
                $value = $inputStringOrDecodedCollection;
            }

            $value = self::recurseCollectionFiltering($value, $this->m_collectionInputFilters, $success, 0);
            if ( !$success )
            {
                return oop_x($this->m_defaultValue);
            }

            return $value;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseCollectionFiltering ($inputCollection, $filterOrFilterCollection, &$success,
        $currDepth)
    {
        assert( 'is_a($filterOrFilterCollection, get_called_class()) || is_collection($filterOrFilterCollection)',
            vs(isset($this), get_defined_vars()) );

        if ( $currDepth == self::$ms_maxRecursionDepth )
        {
            $success = false;
            return;
        }
        $currDepth++;

        if ( is_carray($inputCollection) )
        {
            if ( !is_carray($filterOrFilterCollection) )
            {
                $success = false;
                return;
            }
            $len = CArray::length($inputCollection);
            if ( $len != CArray::length($filterOrFilterCollection) )
            {
                $success = false;
                return;
            }
            for ($i = 0; $i < $len; $i++)
            {
                $inputValue = $inputCollection[$i];
                $filterElement = $filterOrFilterCollection[$i];
                if ( !is_collection($inputValue) )
                {
                    $strInputValue = self::collectionElementToString($inputValue, $success);
                    if ( !$success )
                    {
                        return;
                    }
                    if ( !is_a($filterElement, get_called_class()) )
                    {
                        $success = false;
                        return;
                    }
                    $inputValue = $filterElement->filter($strInputValue, $success);
                    if ( !$success )
                    {
                        return;
                    }
                }
                else
                {
                    $inputValue = self::recurseCollectionFiltering($inputValue, $filterElement, $success,
                        $currDepth);
                    if ( !$success )
                    {
                        return;
                    }
                }
                $inputCollection[$i] = $inputValue;
            }
        }
        else  // a CMap
        {
            if ( !is_cmap($filterOrFilterCollection) )
            {
                $success = false;
                return;
            }
            foreach ($inputCollection as $inputKey => &$inputValue)
            {
                if ( !CMap::hasKey($filterOrFilterCollection, $inputKey) )
                {
                    $success = false;
                    return;
                }
                $filterElement = $filterOrFilterCollection[$inputKey];
                if ( !is_collection($inputValue) )
                {
                    $strInputValue = self::collectionElementToString($inputValue, $success);
                    if ( !$success )
                    {
                        return;
                    }
                    if ( !is_a($filterElement, get_called_class()) )
                    {
                        $success = false;
                        return;
                    }
                    $inputValue = $filterElement->filter($strInputValue, $success);
                    if ( !$success )
                    {
                        return;
                    }
                }
                else
                {
                    $inputValue = self::recurseCollectionFiltering($inputValue, $filterElement, $success,
                        $currDepth);
                    if ( !$success )
                    {
                        return;
                    }
                }
            } unset($inputValue);
        }
        return $inputCollection;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function collectionElementToString ($elementValue, &$success)
    {
        if ( is_cstring($elementValue) )
        {
            return $elementValue;
        }
        if ( is_bool($elementValue) )
        {
            return CString::fromBool10($elementValue);
        }
        if ( is_int($elementValue) )
        {
            return CString::fromInt($elementValue);
        }
        if ( is_float($elementValue) )
        {
            return CString::fromFloat($elementValue);
        }
        $success = false;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // Properties and defaults.
    protected $m_expectedType;
    protected $m_collectionInputFilters;
    protected $m_defaultValue;
    protected $m_jsonStrictness = CJson::STRICT;
    protected $m_intValidMin;
    protected $m_intValidMax;
    protected $m_floatValidMin;
    protected $m_floatValidMax;
    protected $m_allowLeadingZeros = true;
    protected $m_allowHex = false;
    protected $m_allowComma = true;
    protected $m_ignoreProtocolAbsence = true;
    protected $m_ipV6 = false;
    protected $m_ipV4OrV6 = false;
    protected $m_allowPrivateRange = true;
    protected $m_allowReservedRange = true;
    protected $m_intClampingMin;
    protected $m_intClampingMax;
    protected $m_floatClampingMin;
    protected $m_floatClampingMax;
    protected $m_keepAbnormalNewlines = false;
    protected $m_keepNonPrintable = false;
    protected $m_keepTabsAndNewlines = true;
    protected $m_keepSideSpacing = true;
    protected $m_keepExtraSpacing = true;

    // Global defaults.
    protected static $ms_maxRecursionDepth = CSystem::DEFAULT_MAX_RECURSION_DEPTH;
}
