<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * This class allows you to parse the path part of a URL into components, compose a URL path from components, while
 * automatically handling percent-encoding in both cases, as well as to walk a URL path component-by-component.
 *
 * Internally, the characters in the components of a URL path that is represented by an object of this class are stored
 * in their literal representations, i.e. without any of them being percent-encoded. It is only at the time of
 * constructing an object from a path string or converting an object into the corresponding path string when
 * percent-encoding comes into play.
 *
 * You can make a copy of a URL path object using `clone` keyword like so:
 *
 * ```
 * $oUrlPathCopy = clone $oUrlPath;
 * ```
 */

// Method signatures:
//   __construct ($sPath = null)
//   CUStringObject nextComponent ()
//   bool isPosPastEnd ()
//   int numComponents ()
//   CUStringObject component ($iPos)
//   void addComponent ($sComponent)
//   CUStringObject pathString ()

class CUrlPath extends CRootClass
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a URL path from a path string or as empty.
     *
     * If a source path string is provided, it should start with "/". Just like in any valid URL, the path string is
     * not expected to contain characters that cannot be represented literally and percent-encoding is expected to be
     * used for any such characters. No trailing "/" are stripped off the path string, so if the path is e.g.
     * "/comp0/comp1/", it produces an empty string as the last component.
     *
     * @param  string $sPath **OPTIONAL. Default is** *create an empty URL path*. A string with the source path.
     */

    public function __construct ($sPath = null)
    {
        assert( '!isset($sPath) || is_cstring($sPath)', vs(isset($this), get_defined_vars()) );
        assert( '!isset($sPath) || CString::startsWith($sPath, "/")', vs(isset($this), get_defined_vars()) );

        if ( isset($sPath) )
        {
            $sPath = CString::stripStart($sPath, "/");
            $this->m_aComponents = CString::split($sPath, "/");
            $iLen = CArray::length($this->m_aComponents);
            for ($i = 0; $i < $iLen; $i++)
            {
                $this->m_aComponents[$i] = CUrl::leaveTdNew($this->m_aComponents[$i]);
            }
        }
        else
        {
            $this->m_aComponents = CArray::make();
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the next component from a URL path.
     *
     * At the initial call of this method, the current component is the first component. You can use `isPosPastEnd`
     * method to get to know whether the component that was previously returned by this method was the last one.
     *
     * No characters in the returned component are percent-encoded.
     *
     * @return CUStringObject The next component in the URL path.
     *
     * @link   #method_isPosPastEnd isPosPastEnd
     */

    public function nextComponent ()
    {
        assert( '!$this->isPosPastEnd()', vs(isset($this), get_defined_vars()) );

        $iPos = $this->m_iCurrPos;
        $this->m_iCurrPos++;
        return $this->m_aComponents[$iPos];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the current component position in a URL path has gone past the last component.
     *
     * @return bool `true` if the current component position is past the last component, `false` otherwise.
     */

    public function isPosPastEnd ()
    {
        return ( $this->m_iCurrPos >= CArray::length($this->m_aComponents) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the number of components in a URL path.
     *
     * @return int The number of components in the URL path.
     */

    public function numComponents ()
    {
        return CArray::length($this->m_aComponents);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From a URL path, returns the component located at a specified position.
     *
     * No characters in the returned component are percent-encoded.
     *
     * @param  int $iPos The component's position. Positions are zero-based, so the position of the first component in
     * a URL path is `0`, the position of the second component is `1`, and so on.
     *
     * @return CUStringObject The component located at the position specified.
     */

    public function component ($iPos)
    {
        assert( 'is_int($iPos)', vs(isset($this), get_defined_vars()) );
        return $this->m_aComponents[$iPos];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds a component to a URL path.
     *
     * @param  string $sComponent The component to be added. All the characters in the component's string should be
     * represented literally and none of the characters should be percent-encoded.
     *
     * @return void
     */

    public function addComponent ($sComponent)
    {
        assert( 'is_cstring($sComponent)', vs(isset($this), get_defined_vars()) );
        CArray::push($this->m_aComponents, $sComponent);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Composes a URL path into a string ready to be used as a part of a URL and returns it.
     *
     * Any characters that cannot be represented literally in a valid path part of a URL come out percent-encoded. The
     * resulting path always starts with "/".
     *
     * Because the characters in path components are stored in their literal representations, the resulting path string
     * is always normalized, with only those characters appearing percent-encoded that really require it for the path
     * string to be valid and with the hexadecimal letters in percent-encoded characters appearing uppercased.
     *
     * @return CUStringObject A string containing the URL path.
     */

    public function pathString ()
    {
        $aComponents = CArray::makeCopy($this->m_aComponents);
        $iLen = CArray::length($aComponents);
        for ($i = 0; $i < $iLen; $i++)
        {
            $aComponents[$i] = CUrl::enterTdNew($aComponents[$i]);
        }
        return "/" . CArray::join($aComponents, "/");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function __clone ()
    {
        $this->m_aComponents = CArray::makeCopy($this->m_aComponents);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_aComponents;
    protected $m_iCurrPos = 0;
}
