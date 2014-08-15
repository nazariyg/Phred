<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
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
//   __construct ($eExpectedType, $xCollectionInputFilters = null)
//   void setDefault ($xValue)
//   void setJsonStrictness ($eStrictness)
//   enum expectedType ()
//   mixed defaultValue ()
//   mixed collectionInputFilters ()
//   void setValidMin ($xMin)
//   void setValidMax ($xMax)
//   void setValidMinMax ($xMin, $xMax)
//   void setAllowLeadingZeros ($bEnable)
//   void setAllowComma ($bEnable)
//   void setIgnoreProtocolAbsence ($bEnable)
//   void setAllowHex ($bEnable)
//   void setIpV6 ($bEnable)
//   void setIpV4OrV6 ($bEnable)
//   void setAllowPrivateRange ($bEnable)
//   void setAllowReservedRange ($bEnable)
//   void setClampingMin ($xMin)
//   void setClampingMax ($xMax)
//   void setClampingMinMax ($xMin, $xMax)
//   void setKeepAbnormalNewlines ($bEnable)
//   void setKeepNonPrintable ($bEnable)
//   void setKeepTabsAndNewlines ($bEnable)
//   void setKeepSideSpacing ($bEnable)
//   void setKeepExtraSpacing ($bEnable)
//   mixed filter ($xInputStringOrDecodedCollection, &$rbSuccess)

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
     * @param  enum $eExpectedType The output data type that is expected (see [Summary](#summary)).
     * @param  mixed $xCollectionInputFilters **OPTIONAL.** *Required only when the expected type is `CARRAY` or
     * `CMAP`*. The array or map containing the filters to be applied to the elements in the input array if the
     * expected type is `CARRAY` or to the values in the input map if the expected type is `CMAP`. If the input value
     * is going to be an array, the number of filters in this parameter should match the length of the input array, and
     * if the input value is going to be a map, the keys in this parameter should match the keys in the input map. Same
     * as input arrays and maps, this parameter can be multidimensional in order to correspond with the input value.
     */

    public function __construct ($eExpectedType, $xCollectionInputFilters = null)
    {
        assert( 'is_enum($eExpectedType) && !($eExpectedType != self::CARRAY && $eExpectedType != self::CMAP && ' .
                'isset($xCollectionInputFilters)) && !($eExpectedType == self::CARRAY && ' .
                '!is_carray($xCollectionInputFilters)) && !($eExpectedType == self::CMAP && ' .
                '!is_cmap($xCollectionInputFilters))', vs(isset($this), get_defined_vars()) );

        $this->m_eExpectedType = $eExpectedType;
        $this->m_xCollectionInputFilters = $xCollectionInputFilters;

        switch ( $eExpectedType )
        {
        case self::BOOL:
            $this->m_xDefaultValue = false;
            break;
        case self::INT:
            $this->m_xDefaultValue = 0;
            break;
        case self::FLOAT:
            $this->m_xDefaultValue = 0.0;
            break;
        case self::CSTRING:
        case self::CUSTRING:
            $this->m_xDefaultValue = "";
            break;
        case self::CARRAY:
            $this->m_xDefaultValue = CArray::make();
            break;
        case self::CMAP:
            $this->m_xDefaultValue = CMap::make();
            break;
        case self::EMAIL:
        case self::URL:
        case self::IP:
            $this->m_xDefaultValue = "";
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
     * @param  mixed $xValue The default value for the filter.
     *
     * @return void
     */

    public function setDefault ($xValue)
    {
        assert( '($this->m_eExpectedType == self::BOOL && is_bool($xValue)) || ' .
                '($this->m_eExpectedType == self::INT && is_int($xValue)) || ' .
                '($this->m_eExpectedType == self::FLOAT && is_float($xValue)) || ' .
                '($this->m_eExpectedType == self::CSTRING && is_cstring($xValue)) || ' .
                '($this->m_eExpectedType == self::CUSTRING && is_cstring($xValue)) || ' .
                '($this->m_eExpectedType == self::CARRAY && is_carray($xValue)) || ' .
                '($this->m_eExpectedType == self::CMAP && is_cmap($xValue)) || ' .
                '($this->m_eExpectedType == self::EMAIL && is_cstring($xValue)) || ' .
                '($this->m_eExpectedType == self::URL && is_cstring($xValue)) || ' .
                '($this->m_eExpectedType == self::IP && is_cstring($xValue))', vs(isset($this), get_defined_vars()) );

        $this->m_xDefaultValue = $xValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the JSON decoding strictness to be used for JSON input.
     *
     * @param  enum $eStrictness The JSON decoding strictness (see [CJson Summary](CJson.html#summary)).
     *
     * @return void
     */

    public function setJsonStrictness ($eStrictness)
    {
        assert( 'is_enum($eStrictness)', vs(isset($this), get_defined_vars()) );
        $this->m_eJsonStrictness = $eStrictness;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the output data type of a filter.
     *
     * @return enum The filter's output data type.
     */

    public function expectedType ()
    {
        return $this->m_eExpectedType;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the default value of a filter.
     *
     * @return mixed The filter's default value.
     */

    public function defaultValue ()
    {
        return oop_x($this->m_xDefaultValue);
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
        assert( 'isset($this->m_xCollectionInputFilters)', vs(isset($this), get_defined_vars()) );
        return $this->m_xCollectionInputFilters;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, sets the minimum acceptable value for an input value to be considered valid.
     *
     * This method is one of the validation methods.
     *
     * @param  mixed $xMin The minimum acceptable value. The parameter's type should match the expected output type.
     *
     * @return void
     */

    public function setValidMin ($xMin)
    {
        assert( '($this->m_eExpectedType == self::INT && is_int($xMin)) || ' .
                '($this->m_eExpectedType == self::FLOAT && is_float($xMin))', vs(isset($this), get_defined_vars()) );

        if ( $this->m_eExpectedType == self::INT )
        {
            $this->m_iValidMin = $xMin;
        }
        else  // $this->m_eExpectedType = self::FLOAT
        {
            $this->m_fValidMin = $xMin;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, sets the maximum acceptable value for an input value to be considered valid.
     *
     * This method is one of the validation methods.
     *
     * @param  mixed $xMax The maximum acceptable value. The parameter's type should match the expected output type.
     *
     * @return void
     */

    public function setValidMax ($xMax)
    {
        assert( '($this->m_eExpectedType == self::INT && is_int($xMax)) || ' .
                '($this->m_eExpectedType == self::FLOAT && is_float($xMax))', vs(isset($this), get_defined_vars()) );

        if ( $this->m_eExpectedType == self::INT )
        {
            $this->m_iValidMax = $xMax;
        }
        else  // $this->m_eExpectedType = self::FLOAT
        {
            $this->m_fValidMax = $xMax;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, sets the minimum and maximum acceptable values for an input value to be
     * considered valid.
     *
     * This method is one of the validation methods.
     *
     * @param  mixed $xMin The minimum acceptable value. The parameter's type should match the expected output type.
     * @param  mixed $xMax The maximum acceptable value. The parameter's type should match the expected output type.
     *
     * @return void
     */

    public function setValidMinMax ($xMin, $xMax)
    {
        assert( '($this->m_eExpectedType == self::INT && is_int($xMin) && is_int($xMax)) || ' .
                '($this->m_eExpectedType == self::FLOAT && is_float($xMin) && is_float($xMax))',
            vs(isset($this), get_defined_vars()) );
        assert( '$xMin <= $xMax', vs(isset($this), get_defined_vars()) );

        if ( $this->m_eExpectedType == self::INT )
        {
            $this->m_iValidMin = $xMin;
            $this->m_iValidMax = $xMax;
        }
        else  // $this->m_eExpectedType = self::FLOAT
        {
            $this->m_fValidMin = $xMin;
            $this->m_fValidMax = $xMax;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, allows or disallows a string with an input value to start with one or more
     * zeros.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $bEnable `true` to allow an input value to contain leading zeros, `false` otherwise.
     *
     * @return void
     */

    public function setAllowLeadingZeros ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eExpectedType == self::INT || ' .
                '$this->m_eExpectedType == self::FLOAT', vs(isset($this), get_defined_vars()) );

        $this->m_bAllowLeadingZeros = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, allows or disallows the thousand groups in a string with an input value to
     * be separated with commas.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $bEnable `true` to allow an input value to contain commas, `false` otherwise.
     *
     * @return void
     */

    public function setAllowComma ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eExpectedType == self::INT || ' .
                '$this->m_eExpectedType == self::FLOAT', vs(isset($this), get_defined_vars()) );

        $this->m_bAllowComma = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `URL` output type, sets whether to ignore the absence of a protocol in an input URL.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $bEnable `true` to ignore the absence of a protocol in an input URL, `false` otherwise.
     *
     * @return void
     */

    public function setIgnoreProtocolAbsence ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eExpectedType == self::URL', vs(isset($this), get_defined_vars()) );

        $this->m_bIgnoreProtocolAbsence = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` output type, allows or disallows a string with an input value to be a hexadecimal integer prefixed
     * with "0x".
     *
     * This method is one of the validation methods.
     *
     * @param  bool $bEnable `true` to allow an input value to be a hexadecimal integer prefixed with "0x", `false`
     * otherwise.
     *
     * @return void
     */

    public function setAllowHex ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eExpectedType == self::INT', vs(isset($this), get_defined_vars()) );

        $this->m_bAllowHex = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `IP` output type, sets whether an input IP is expected to be IPv6 only.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $bEnable `true` if an input IP is expected to be IPv6 only, `false` otherwise.
     *
     * @return void
     */

    public function setIpV6 ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eExpectedType == self::IP', vs(isset($this), get_defined_vars()) );

        $this->m_bIpV6 = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `IP` output type, sets whether an input IP is allowed to be IPv6 in addition to IPv4.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $bEnable `true` if an input IP is allowed to be IPv6 in addition to IPv4, `false` otherwise.
     *
     * @return void
     */

    public function setIpV4OrV6 ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eExpectedType == self::IP', vs(isset($this), get_defined_vars()) );

        $this->m_bIpV4OrV6 = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `IP` output type, allows or disallows an input IP to be in a private IP range.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $bEnable `true` to allow an input IP to be in a private IP range, `false` otherwise.
     *
     * @return void
     */

    public function setAllowPrivateRange ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eExpectedType == self::IP', vs(isset($this), get_defined_vars()) );

        $this->m_bAllowPrivateRange = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `IP` output type, allows or disallows an input IP to be in a reserved IP range.
     *
     * This method is one of the validation methods.
     *
     * @param  bool $bEnable `true` to allow an input IP to be in a reserved IP range, `false` otherwise.
     *
     * @return void
     */

    public function setAllowReservedRange ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eExpectedType == self::IP', vs(isset($this), get_defined_vars()) );

        $this->m_bAllowReservedRange = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, sets the minimum acceptable value to which an input value should be clamped
     * if needed.
     *
     * This method is one of the sanitization methods.
     *
     * @param  mixed $xMin The minimum acceptable value. The parameter's type should match the expected output type.
     *
     * @return void
     */

    public function setClampingMin ($xMin)
    {
        assert( '($this->m_eExpectedType == self::INT && is_int($xMin)) || ' .
                '($this->m_eExpectedType == self::FLOAT && is_float($xMin))', vs(isset($this), get_defined_vars()) );

        if ( $this->m_eExpectedType == self::INT )
        {
            $this->m_iClampingMin = $xMin;
        }
        else  // $this->m_eExpectedType = self::FLOAT
        {
            $this->m_fClampingMin = $xMin;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, sets the maximum acceptable value to which an input value should be clamped
     * if needed.
     *
     * This method is one of the sanitization methods.
     *
     * @param  mixed $xMax The maximum acceptable value. The parameter's type should match the expected output type.
     *
     * @return void
     */

    public function setClampingMax ($xMax)
    {
        assert( '($this->m_eExpectedType == self::INT && is_int($xMax)) || ' .
                '($this->m_eExpectedType == self::FLOAT && is_float($xMax))', vs(isset($this), get_defined_vars()) );

        if ( $this->m_eExpectedType == self::INT )
        {
            $this->m_iClampingMax = $xMax;
        }
        else  // $this->m_eExpectedType = self::FLOAT
        {
            $this->m_fClampingMax = $xMax;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `INT` and `FLOAT` output types, sets the minimum and maximum acceptable values to which an input value
     * should be clamped if needed.
     *
     * This method is one of the sanitization methods.
     *
     * @param  mixed $xMin The minimum acceptable value. The parameter's type should match the expected output type.
     * @param  mixed $xMax The maximum acceptable value. The parameter's type should match the expected output type.
     *
     * @return void
     */

    public function setClampingMinMax ($xMin, $xMax)
    {
        assert( '($this->m_eExpectedType == self::INT && is_int($xMin) && is_int($xMax)) || ' .
                '($this->m_eExpectedType == self::FLOAT && is_float($xMin) && is_float($xMax))',
            vs(isset($this), get_defined_vars()) );
        assert( '$xMin <= $xMax', vs(isset($this), get_defined_vars()) );

        if ( $this->m_eExpectedType == self::INT )
        {
            $this->m_iClampingMin = $xMin;
            $this->m_iClampingMax = $xMax;
        }
        else  // $this->m_eExpectedType = self::FLOAT
        {
            $this->m_fClampingMin = $xMin;
            $this->m_fClampingMax = $xMax;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `CSTRING` and `CUSTRING` output types, sets whether to keep any non-LF newlines in the output string or
     * convert any such newlines to LF instead.
     *
     * This method is one of the sanitization methods.
     *
     * @param  bool $bEnable `true` to keep any non-LF newlines in the output string, `false` to convert any such
     * newlines to LF.
     *
     * @return void
     */

    public function setKeepAbnormalNewlines ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eExpectedType == self::CSTRING || ' .
                '$this->m_eExpectedType == self::CUSTRING', vs(isset($this), get_defined_vars()) );

        $this->m_bKeepAbnormalNewlines = $bEnable;
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
     * @param  bool $bEnable `true` to keep any non-printable characters in the output string, `false` to remove any
     * such characters.
     *
     * @return void
     */

    public function setKeepNonPrintable ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eExpectedType == self::CSTRING || ' .
                '$this->m_eExpectedType == self::CUSTRING', vs(isset($this), get_defined_vars()) );

        $this->m_bKeepNonPrintable = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `CSTRING` and `CUSTRING` output types, sets whether to keep any tabs and newlines in the output string or to
     * remove them.
     *
     * This method is one of the sanitization methods.
     *
     * @param  bool $bEnable `true` to keep any tabs and newlines in the output string, `false` to remove them.
     *
     * @return void
     */

    public function setKeepTabsAndNewlines ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eExpectedType == self::CSTRING || ' .
                '$this->m_eExpectedType == self::CUSTRING', vs(isset($this), get_defined_vars()) );

        $this->m_bKeepTabsAndNewlines = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `CSTRING` and `CUSTRING` output types, sets whether to keep any whitespace at the both sides of the output
     * string or to trim it.
     *
     * This method is one of the sanitization methods.
     *
     * @param  bool $bEnable `true` to keep any whitespace at the both sides of the output string, `false` to trim it.
     *
     * @return void
     */

    public function setKeepSideSpacing ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eExpectedType == self::CSTRING || ' .
                '$this->m_eExpectedType == self::CUSTRING', vs(isset($this), get_defined_vars()) );

        $this->m_bKeepSideSpacing = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For `CSTRING` and `CUSTRING` output types, sets whether to keep any whitespace at the both sides of the output
     * string and to keep any sequence of whitespace characters within the string as-is or to trim the string from
     * whitespace on both sides and to replace any sequence of whitespace characters with a single space character.
     *
     * This method is one of the sanitization methods.
     *
     * @param  bool $bEnable `true` to keep any whitespace at the both sides of the output string and to keep any
     * sequence of whitespace characters within the string as-is, `false` to trim the string from whitespace on both
     * sides and to replace any sequence of whitespace characters with a single space character.
     *
     * @return void
     */

    public function setKeepExtraSpacing ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eExpectedType == self::CSTRING || ' .
                '$this->m_eExpectedType == self::CUSTRING', vs(isset($this), get_defined_vars()) );

        $this->m_bKeepExtraSpacing = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Filters a string or a collection of strings according to the expected output type(s) and returns the output
     * value(s).
     *
     * @param  mixed $xInputStringOrDecodedCollection The string to be filtered or the array or map containing the
     * strings to be filtered. If the parameter's value is a JSON-encoded string, the output value is going to be
     * either an array or map.
     * @param  reference $rbSuccess **OUTPUT.** After the method is called, the value of this parameter tells whether
     * the filtering was successful.
     *
     * @return mixed The output value or a collection of values of the expected type(s) after having been put through
     * the filter.
     */

    public function filter ($xInputStringOrDecodedCollection, &$rbSuccess)
    {
        assert( 'is_cstring($xInputStringOrDecodedCollection) || is_collection($xInputStringOrDecodedCollection)',
            vs(isset($this), get_defined_vars()) );

        $rbSuccess = true;

        if ( $this->m_eExpectedType != self::CARRAY &&
             $this->m_eExpectedType != self::CMAP )
        {
            // The expected output type is not a collection; the input value must be of string type.

            if ( !is_cstring($xInputStringOrDecodedCollection) )
            {
                $rbSuccess = false;
                return oop_x($this->m_xDefaultValue);
            }

            $sInputString = $xInputStringOrDecodedCollection;

            if ( $this->m_eExpectedType == self::BOOL ||
                 $this->m_eExpectedType == self::INT ||
                 $this->m_eExpectedType == self::FLOAT ||
                 $this->m_eExpectedType == self::EMAIL ||
                 $this->m_eExpectedType == self::URL ||
                 $this->m_eExpectedType == self::IP )
            {
                // Trim the input string on both sides from whitespace, including Unicode whitespace and control
                // characters.
                $sTrimmingSubjectRe = CUString::TRIMMING_AND_SPACING_NORM_SUBJECT_RE;
                $sInputString = CRegex::remove($sInputString, "/^($sTrimmingSubjectRe)+|($sTrimmingSubjectRe)+\\z/u");
            }

            // Pre-process the string for integer and floating-point types.
            $bLooksLikeHex;
            if ( $this->m_eExpectedType == self::INT ||
                 $this->m_eExpectedType == self::FLOAT )
            {
                if ( CString::startsWith($sInputString, "+") )
                {
                    // Remove the plus sign.
                    $sInputString = CString::substr($sInputString, 1);
                }

                $bLooksLikeHex = CRegex::find($sInputString, "/^-?0x/i");

                if ( $this->m_bAllowLeadingZeros &&
                     !($this->m_eExpectedType == self::INT && $this->m_bAllowHex && $bLooksLikeHex) )
                {
                    // Remove any leading zeros (except for special cases).
                    $sInputString = CRegex::replace($sInputString, "/^(\\D*)0*(?!\\b)/", "$1");
                }

                if ( $this->m_bAllowComma )
                {
                    $sInputString = CRegex::remove($sInputString, "/,(?=\\d{3}\\b)/");
                }
            }

            // Validate and sanitize the value according to its expected type.

            if ( $this->m_eExpectedType == self::BOOL )
            {
                if ( !CRegex::find($sInputString, "/^(1|true|yes|on|0|false|no|off)\\z/i") )
                {
                    $rbSuccess = false;
                    return $this->m_xDefaultValue;
                }

                return (
                    CString::equals($sInputString, "1") ||
                    CString::equalsCi($sInputString, "true") ||
                    CString::equalsCi($sInputString, "yes") ||
                    CString::equalsCi($sInputString, "on") );
            }

            if ( $this->m_eExpectedType == self::INT )
            {
                $iValue;
                if ( !($this->m_bAllowHex && $bLooksLikeHex) )
                {
                    // Regular.
                    if ( !CRegex::find($sInputString, "/^-?(?!0(?!\\b))\\d+\\z/") )
                    {
                        $rbSuccess = false;
                        return $this->m_xDefaultValue;
                    }
                    $iValue = CString::toInt($sInputString);
                }
                else
                {
                    // Hex.
                    if ( !CRegex::find($sInputString, "/^-?0x[0-9A-F]+\\z/i") )
                    {
                        $rbSuccess = false;
                        return $this->m_xDefaultValue;
                    }
                    $iValue = CString::toIntFromHex($sInputString);
                }

                if ( (isset($this->m_iValidMin) && $iValue < $this->m_iValidMin) ||
                     (isset($this->m_iValidMax) && $iValue > $this->m_iValidMax) )
                {
                    $rbSuccess = false;
                    return $this->m_xDefaultValue;
                }

                if ( isset($this->m_iClampingMin) && $iValue < $this->m_iClampingMin )
                {
                    $iValue = $this->m_iClampingMin;
                }
                if ( isset($this->m_iClampingMax) && $iValue > $this->m_iClampingMax )
                {
                    $iValue = $this->m_iClampingMax;
                }

                return $iValue;
            }

            if ( $this->m_eExpectedType == self::FLOAT )
            {
                if ( !CRegex::find($sInputString, "/^-?(?!0(?!\\b))\\d*\\.?\\d+(e[\\-+]?\\d+)?\\z/i") )
                {
                    $rbSuccess = false;
                    return $this->m_xDefaultValue;
                }

                $fValue = CString::toFloat($sInputString);

                if ( (isset($this->m_fValidMin) && $fValue < $this->m_fValidMin) ||
                     (isset($this->m_fValidMax) && $fValue > $this->m_fValidMax) )
                {
                    $rbSuccess = false;
                    return $this->m_xDefaultValue;
                }

                if ( isset($this->m_fClampingMin) && $fValue < $this->m_fClampingMin )
                {
                    $fValue = $this->m_fClampingMin;
                }
                if ( isset($this->m_fClampingMax) && $fValue > $this->m_fClampingMax )
                {
                    $fValue = $this->m_fClampingMax;
                }

                return $fValue;
            }

            if ( $this->m_eExpectedType == self::CSTRING )
            {
                $sValue = $sInputString;

                if ( !CString::isValid($sValue) )
                {
                    $rbSuccess = false;
                    return $this->m_xDefaultValue;
                }

                if ( !$this->m_bKeepAbnormalNewlines )
                {
                    $sValue = CString::normNewlines($sValue);
                }

                if ( !$this->m_bKeepNonPrintable )
                {
                    if ( !$this->m_bKeepTabsAndNewlines )
                    {
                        $sValue = CRegex::remove($sValue, "/[\\x00-\\x1F\\x7F-\\xFF]/");
                    }
                    else
                    {
                        $sValue = CRegex::remove($sValue, "/[\\x00-\\x1F\\x7F-\\xFF](?<![\\x09\\x0A\\x0D])/");
                    }
                }
                else if ( !$this->m_bKeepTabsAndNewlines )
                {
                    $sValue = CRegex::remove($sValue, "/[\\x09\\x0A\\x0D]/");
                }

                if ( !$this->m_bKeepSideSpacing )
                {
                    $sValue = CString::trim($sValue);
                }

                if ( !$this->m_bKeepExtraSpacing )
                {
                    $sValue = CString::normSpacing($sValue);
                }

                return $sValue;
            }

            if ( $this->m_eExpectedType == self::CUSTRING )
            {
                $sValue = $sInputString;

                if ( !CUString::isValid($sValue) )
                {
                    $rbSuccess = false;
                    return $this->m_xDefaultValue;
                }

                if ( !$this->m_bKeepAbnormalNewlines )
                {
                    $sValue = CUString::normNewlines($sValue);
                }

                if ( !$this->m_bKeepNonPrintable )
                {
                    if ( !$this->m_bKeepTabsAndNewlines )
                    {
                        $sValue = CRegex::remove($sValue, "/\\p{C}|\\p{Zl}|\\p{Zp}/u");
                    }
                    else
                    {
                        $sValue = CRegex::remove($sValue, "/\\p{C}(?<!\\x{0009}|\\x{000A}|\\x{000D})/u");
                    }
                }
                else if ( !$this->m_bKeepTabsAndNewlines )
                {
                    $sValue = CRegex::remove($sValue, "/\\x{0009}|\\x{000A}|\\x{000D}|\\p{Zl}|\\p{Zp}/u");
                }

                if ( !$this->m_bKeepSideSpacing )
                {
                    $sValue = CUString::trim($sValue);
                }

                if ( !$this->m_bKeepExtraSpacing )
                {
                    $sValue = CUString::normSpacing($sValue);
                }

                return $sValue;
            }

            if ( $this->m_eExpectedType == self::EMAIL )
            {
                $sValue = filter_var($sInputString, FILTER_VALIDATE_EMAIL);
                if ( !is_cstring($sValue) )
                {
                    $rbSuccess = false;
                    return $this->m_xDefaultValue;
                }
                return $sValue;
            }

            if ( $this->m_eExpectedType == self::URL )
            {
                $sValue = $sInputString;
                if ( !CUrl::isValid($sValue, $this->m_bIgnoreProtocolAbsence) )
                {
                    $rbSuccess = false;
                    return $this->m_xDefaultValue;
                }
                if ( $this->m_bIgnoreProtocolAbsence )
                {
                    $sValue = CUrl::ensureProtocol($sValue);
                }
                return $sValue;
            }

            if ( $this->m_eExpectedType == self::IP )
            {
                $sValue = $sInputString;
                $bfOptions = CBitField::ALL_UNSET;
                if ( !$this->m_bAllowPrivateRange )
                {
                    $bfOptions |= CIp::DISALLOW_PRIVATE_RANGE;
                }
                if ( !$this->m_bAllowReservedRange )
                {
                    $bfOptions |= CIp::DISALLOW_RESERVED_RANGE;
                }
                $bIsValid;
                if ( !$this->m_bIpV6 && !$this->m_bIpV4OrV6 )
                {
                    $bIsValid = CIp::isValidV4($sValue, $bfOptions);
                }
                else if ( !$this->m_bIpV4OrV6 )
                {
                    $bIsValid = CIp::isValidV6($sValue, $bfOptions);
                }
                else
                {
                    $bIsValid = ( CIp::isValidV4($sValue, $bfOptions) || CIp::isValidV6($sValue, $bfOptions) );
                }
                if ( !$bIsValid )
                {
                    $rbSuccess = false;
                    return $this->m_xDefaultValue;
                }
                return $sValue;
            }
        }
        else if ( $this->m_eExpectedType == self::CARRAY )
        {
            if ( !is_cstring($xInputStringOrDecodedCollection) && !is_carray($xInputStringOrDecodedCollection) )
            {
                $rbSuccess = false;
                return oop_x($this->m_xDefaultValue);
            }

            $aValue;
            if ( is_cstring($xInputStringOrDecodedCollection) )
            {
                // Assume JSON format for the input string.
                $oJson = new CJson($xInputStringOrDecodedCollection, $this->m_eJsonStrictness);
                $aValue = $oJson->decode($rbSuccess);
                if ( !$rbSuccess )
                {
                    return oop_x($this->m_xDefaultValue);
                }
                if ( !is_carray($aValue) )
                {
                    $rbSuccess = false;
                    return oop_x($this->m_xDefaultValue);
                }
            }
            else  // a CArray
            {
                $aValue = $xInputStringOrDecodedCollection;
            }

            $aValue = self::recurseCollectionFiltering($aValue, $this->m_xCollectionInputFilters, $rbSuccess, 0);
            if ( !$rbSuccess )
            {
                return oop_x($this->m_xDefaultValue);
            }

            return $aValue;
        }
        else  // $this->m_eExpectedType = self::CMAP
        {
            if ( !is_cstring($xInputStringOrDecodedCollection) && !is_cmap($xInputStringOrDecodedCollection) )
            {
                $rbSuccess = false;
                return oop_x($this->m_xDefaultValue);
            }

            $mValue;
            if ( is_cstring($xInputStringOrDecodedCollection) )
            {
                // Assume JSON format for the input string.
                $oJson = new CJson($xInputStringOrDecodedCollection, $this->m_eJsonStrictness);
                $mValue = $oJson->decode($rbSuccess);
                if ( !$rbSuccess )
                {
                    return oop_x($this->m_xDefaultValue);
                }
                if ( !is_cmap($mValue) )
                {
                    $rbSuccess = false;
                    return oop_x($this->m_xDefaultValue);
                }
            }
            else  // a CMap
            {
                $mValue = $xInputStringOrDecodedCollection;
            }

            $mValue = self::recurseCollectionFiltering($mValue, $this->m_xCollectionInputFilters, $rbSuccess, 0);
            if ( !$rbSuccess )
            {
                return oop_x($this->m_xDefaultValue);
            }

            return $mValue;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseCollectionFiltering ($xInputCollection, $xFilterOrFilterCollection, &$rbSuccess,
        $iCurrDepth)
    {
        assert( 'is_a($xFilterOrFilterCollection, get_called_class()) || is_collection($xFilterOrFilterCollection)',
            vs(isset($this), get_defined_vars()) );

        if ( $iCurrDepth == self::$ms_iMaxRecursionDepth )
        {
            $rbSuccess = false;
            return;
        }
        $iCurrDepth++;

        if ( is_carray($xInputCollection) )
        {
            if ( !is_carray($xFilterOrFilterCollection) )
            {
                $rbSuccess = false;
                return;
            }
            $iLen = CArray::length($xInputCollection);
            if ( $iLen != CArray::length($xFilterOrFilterCollection) )
            {
                $rbSuccess = false;
                return;
            }
            for ($i = 0; $i < $iLen; $i++)
            {
                $xInputValue = $xInputCollection[$i];
                $xFilterElement = $xFilterOrFilterCollection[$i];
                if ( !is_collection($xInputValue) )
                {
                    $sInputValue = self::collectionElementToString($xInputValue, $rbSuccess);
                    if ( !$rbSuccess )
                    {
                        return;
                    }
                    if ( !is_a($xFilterElement, get_called_class()) )
                    {
                        $rbSuccess = false;
                        return;
                    }
                    $xInputValue = $xFilterElement->filter($sInputValue, $rbSuccess);
                    if ( !$rbSuccess )
                    {
                        return;
                    }
                }
                else
                {
                    $xInputValue = self::recurseCollectionFiltering($xInputValue, $xFilterElement, $rbSuccess,
                        $iCurrDepth);
                    if ( !$rbSuccess )
                    {
                        return;
                    }
                }
                $xInputCollection[$i] = $xInputValue;
            }
        }
        else  // a CMap
        {
            if ( !is_cmap($xFilterOrFilterCollection) )
            {
                $rbSuccess = false;
                return;
            }
            foreach ($xInputCollection as $xInputKey => &$rxInputValue)
            {
                if ( !CMap::hasKey($xFilterOrFilterCollection, $xInputKey) )
                {
                    $rbSuccess = false;
                    return;
                }
                $xFilterElement = $xFilterOrFilterCollection[$xInputKey];
                if ( !is_collection($rxInputValue) )
                {
                    $sInputValue = self::collectionElementToString($rxInputValue, $rbSuccess);
                    if ( !$rbSuccess )
                    {
                        return;
                    }
                    if ( !is_a($xFilterElement, get_called_class()) )
                    {
                        $rbSuccess = false;
                        return;
                    }
                    $rxInputValue = $xFilterElement->filter($sInputValue, $rbSuccess);
                    if ( !$rbSuccess )
                    {
                        return;
                    }
                }
                else
                {
                    $rxInputValue = self::recurseCollectionFiltering($rxInputValue, $xFilterElement, $rbSuccess,
                        $iCurrDepth);
                    if ( !$rbSuccess )
                    {
                        return;
                    }
                }
            } unset($rxInputValue);
        }
        return $xInputCollection;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function collectionElementToString ($xElementValue, &$rbSuccess)
    {
        if ( is_cstring($xElementValue) )
        {
            return $xElementValue;
        }
        if ( is_bool($xElementValue) )
        {
            return CString::fromBool10($xElementValue);
        }
        if ( is_int($xElementValue) )
        {
            return CString::fromInt($xElementValue);
        }
        if ( is_float($xElementValue) )
        {
            return CString::fromFloat($xElementValue);
        }
        $rbSuccess = false;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // Properties and defaults.
    protected $m_eExpectedType;
    protected $m_xCollectionInputFilters;
    protected $m_xDefaultValue;
    protected $m_eJsonStrictness = CJson::STRICT;
    protected $m_iValidMin;
    protected $m_iValidMax;
    protected $m_fValidMin;
    protected $m_fValidMax;
    protected $m_bAllowLeadingZeros = true;
    protected $m_bAllowHex = false;
    protected $m_bAllowComma = true;
    protected $m_bIgnoreProtocolAbsence = true;
    protected $m_bIpV6 = false;
    protected $m_bIpV4OrV6 = false;
    protected $m_bAllowPrivateRange = true;
    protected $m_bAllowReservedRange = true;
    protected $m_iClampingMin;
    protected $m_iClampingMax;
    protected $m_fClampingMin;
    protected $m_fClampingMax;
    protected $m_bKeepAbnormalNewlines = false;
    protected $m_bKeepNonPrintable = false;
    protected $m_bKeepTabsAndNewlines = true;
    protected $m_bKeepSideSpacing = true;
    protected $m_bKeepExtraSpacing = true;

    // Global defaults.
    protected static $ms_iMaxRecursionDepth = CSystem::DEFAULT_MAX_RECURSION_DEPTH;
}
