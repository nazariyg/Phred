<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The static methods for Base64 decoding and encoding.
 */

// Method signatures:
//   static CUStringObject decode ($encodedData, &$success = null)
//   static CUStringObject encode ($data)

class CBase64 extends CRootClass
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Decodes a Base64-encoded data and returns the result.
     *
     * @param  data $encodedData The data to be decoded.
     * @param  reference $success **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value tells whether the data was decoded successfully.
     *
     * @return CUStringObject The decoded data.
     */

    public static function decode ($encodedData, &$success = null)
    {
        assert( 'is_cstring($encodedData)', vs(isset($this), get_defined_vars()) );

        $data = @base64_decode($encodedData, true);
        $success = is_cstring($data);
        if ( !$success )
        {
            $data = "";
        }
        return $data;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Encodes a data with Base64 encoding and returns the result.
     *
     * @param  data $data The data to be encoded.
     *
     * @return CUStringObject The encoded data.
     */

    public static function encode ($data)
    {
        assert( 'is_cstring($data)', vs(isset($this), get_defined_vars()) );

        $encodedData = base64_encode($data);
        assert( 'is_cstring($encodedData)', vs(isset($this), get_defined_vars()) );
        return $encodedData;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
