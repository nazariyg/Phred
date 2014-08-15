<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The static methods for Base64 decoding and encoding.
 */

// Method signatures:
//   static CUStringObject decode ($byEncodedData, &$rbSuccess = null)
//   static CUStringObject encode ($byData)

class CBase64 extends CRootClass
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Decodes a Base64-encoded data and returns the result.
     *
     * @param  data $byEncodedData The data to be decoded.
     * @param  reference $rbSuccess **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value tells whether the data was decoded successfully.
     *
     * @return CUStringObject The decoded data.
     */

    public static function decode ($byEncodedData, &$rbSuccess = null)
    {
        assert( 'is_cstring($byEncodedData)', vs(isset($this), get_defined_vars()) );

        $byData = @base64_decode($byEncodedData, true);
        $rbSuccess = is_cstring($byData);
        if ( !$rbSuccess )
        {
            $byData = "";
        }
        return $byData;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Encodes a data with Base64 encoding and returns the result.
     *
     * @param  data $byData The data to be encoded.
     *
     * @return CUStringObject The encoded data.
     */

    public static function encode ($byData)
    {
        assert( 'is_cstring($byData)', vs(isset($this), get_defined_vars()) );

        $byEncodedData = base64_encode($byData);
        assert( 'is_cstring($byEncodedData)', vs(isset($this), get_defined_vars()) );
        return $byEncodedData;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
