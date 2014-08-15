<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The static methods for decompressing data from and compressing data to gzip format.
 */

// Method signatures:
//   static CUStringObject decompress ($byCompressedData, &$rbSuccess = null)
//   static CUStringObject compress ($byData, $eCompressionLevel = self::NORMAL)

class CGZip extends CRootClass
{
    // Compression levels.
    /**
     * `enum` "Fastest" compression level (optimized for speed).
     *
     * @var enum
     */
    const FASTEST = 0;
    /**
     * `enum` "Fast" compression level.
     *
     * @var enum
     */
    const FAST = 1;
    /**
     * `enum` "Normal" compression level.
     *
     * @var enum
     */
    const NORMAL = 2;
    /**
     * `enum` "Maximum" compression level (optimized for compression ratio).
     *
     * @var enum
     */
    const MAXIMUM = 3;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Decompresses a gzip-compressed data and returns the result.
     *
     * @param  data $byCompressedData The data to be decompressed.
     * @param  reference $rbSuccess **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value tells whether the data was decompressed successfully.
     *
     * @return CUStringObject The decompressed data.
     */

    public static function decompress ($byCompressedData, &$rbSuccess = null)
    {
        assert( 'is_cstring($byCompressedData)', vs(isset($this), get_defined_vars()) );

        $byData = @gzdecode($byCompressedData);
        $rbSuccess = is_cstring($byData);
        if ( !$rbSuccess )
        {
            $byData = "";
        }
        return $byData;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Compresses a data into gzip format and returns the result.
     *
     * @param  data $byData The data to be compressed.
     * @param  enum $eCompressionLevel **OPTIONAL. Default is** `NORMAL`. The compression level. Can be `FASTEST`,
     * `FAST`, `NORMAL`, or `MAXIMUM`.
     *
     * @return CUStringObject The compressed data.
     */

    public static function compress ($byData, $eCompressionLevel = self::NORMAL)
    {
        assert( 'is_cstring($byData) && is_enum($eCompressionLevel)', vs(isset($this), get_defined_vars()) );

        $iCompressionLevelGZip = self::compressionLevelEnumToGZip($eCompressionLevel);
        $byCompressedData = gzencode($byData, $iCompressionLevelGZip, FORCE_GZIP);
        assert( 'is_cstring($byCompressedData)', vs(isset($this), get_defined_vars()) );
        return $byCompressedData;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function compressionLevelEnumToGZip ($eCompressionLevel)
    {
        switch ( $eCompressionLevel )
        {
        case self::FASTEST:
            return 1;
        case self::FAST:
            return 3;
        case self::NORMAL:
            return 6;
        case self::MAXIMUM:
            return 9;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
