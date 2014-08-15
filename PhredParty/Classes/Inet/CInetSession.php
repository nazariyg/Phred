<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that lets you send multiple requests over the Internet in a time-efficient way and with HTTP cookies
 * supported.
 *
 * **You can refer to this class by its alias, which is** `InetSess`.
 *
 * When you send two or more requests bundled in a session, whether those requests are interrelated or not, you save
 * time by letting the requests be processed by remote server(s) in parallel since, as much as it is allowed by the
 * maximum number of concurrent requests, a request does not have to wait for the previously sent request to complete
 * in order to be sent out.
 *
 * With the CInetSession class, a session is not limited just to the initially added requests and any number of
 * subsequent requests can be queued into the session from a callback function that, if specified, is invoked by the
 * session after a request is complete.
 *
 * **Defaults:**
 *
 * * Cookies are enabled.
 * * The maximum number of concurrent requests is 8.
 */

// Method signatures:
//   __construct ()
//   CUStringObject errorMessage ()
//   void addRequest (CInetRequest $oRequest, $fnOnCompleteCallback = null, $bNewCookieSession = false)
//   void setEnableCookies ($bEnable)
//   void setMaxNumConcurrentRequests ($iMaxNumConcurrentRequests)
//   void start (&$rbSuccess = null)

class CInetSession extends CRootClass
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an Internet session ready to be populated with requests.
     */

    public function __construct ()
    {
        $this->m_aRequestRecordsQueue = CArray::make();

        $this->m_rcMultiCurl = curl_multi_init();
        if ( !is_resource($this->m_rcMultiCurl) )
        {
            $this->m_bHasError = true;
            $this->m_sErrorMessage = "Multi cURL initialization failed.";
            $this->finalize();
            return;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * If a session did not succeed, returns the human-readable error message that is describing the problem.
     *
     * @return CUStringObject The error message.
     */

    public function errorMessage ()
    {
        assert( '$this->m_bHasError', vs(isset($this), get_defined_vars()) );
        return $this->m_sErrorMessage;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds a request to a session.
     *
     * @param  CInetRequest $oRequest The request to be added.
     * @param  callable $fnOnCompleteCallback **OPTIONAL.** The callback function or method to be called by the session
     * when the request completes. The function is expected to take four parameters: a flag of type `bool` that tells
     * whether the request was successful, the response of type `CUStringObject` that was received for the request, an
     * object of type `CInetRequest` that represents the request, and an object of type `CInetSession` that represents
     * the session to which the request belongs, in this order. You can use the session's object to add more requests
     * to the request queue of the session depending on the response or any other factors.
     * @param  bool $bNewCookieSession **OPTIONAL. Default is** `false`. Tells whether to delete all previously stored
     * cookies that were set by the remote server(s) to expire when the "browsing session" during which those cookies
     * were received comes to an end (you can have as much of such "browsing sessions" as you like). If this parameter
     * is `true`, the "browsing session" cookies are deleted before the request is sent.
     *
     * @return void
     */

    public function addRequest (CInetRequest $oRequest, $fnOnCompleteCallback = null, $bNewCookieSession = false)
    {
        assert( '(!isset($fnOnCompleteCallback) || is_callable($fnOnCompleteCallback)) && is_bool($bNewCookieSession)',
            vs(isset($this), get_defined_vars()) );

        $aRequestRecord = CArray::fromElements($oRequest, $fnOnCompleteCallback, $bNewCookieSession);
        CArray::push($this->m_aRequestRecordsQueue, $aRequestRecord);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Enables or disables cookies for a session.
     *
     * @param  bool $bEnable `true` to enable cookies for the session, `false` otherwise.
     *
     * @return void
     */

    public function setEnableCookies ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        $this->m_bCookiesAreEnabled = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the maximum number of requests that are allowed to be processed in parallel by a session.
     *
     * @param  int $iMaxNumConcurrentRequests The maximum number of concurrent requests.
     *
     * @return void
     */

    public function setMaxNumConcurrentRequests ($iMaxNumConcurrentRequests)
    {
        assert( 'is_int($iMaxNumConcurrentRequests)', vs(isset($this), get_defined_vars()) );
        assert( '$iMaxNumConcurrentRequests > 0', vs(isset($this), get_defined_vars()) );

        $this->m_iMaxNumConcurrentRequests = $iMaxNumConcurrentRequests;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Starts a session by sending out the added requests.
     *
     * @param  reference $rbSuccess **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value tells whether the session was successful.
     *
     * @return void
     */

    public function start (&$rbSuccess = null)
    {
        $rbSuccess = true;

        if ( $this->m_bHasError )
        {
            $rbSuccess = false;
            return;
        }

        if ( CArray::isEmpty($this->m_aRequestRecordsQueue) )
        {
            // Nothing to do.
            return;
        }

        // Current policy is to disable HTTP pipelining.
        $bRes = curl_multi_setopt($this->m_rcMultiCurl, CURLMOPT_PIPELINING, 0);
        if ( !$bRes )
        {
            // Should never get in here as long as cURL options are being set correctly, hence the assertion.
            assert( 'false', vs(isset($this), get_defined_vars()) );
            $this->m_bHasError = true;
            $this->m_sErrorMessage = "The 'curl_multi_setopt' function failed.";
            $rbSuccess = false;
            $this->finalize();
            return;
        }

        $bAnySuccessfulRequests = false;

        // Disable the script's execution timeout before getting into the session.
        $oTimeoutPause = new CTimeoutPause();

        $iNumRunningRequests = 0;  // also the index of the next request to send
        while ( true )
        {
            // From the request queue, add as many normal cURL handles to the multi cURL handle as it is allowed by the
            // maximum number of concurrent requests, priorly setting internal options for every request.
            while ( $iNumRunningRequests < CArray::length($this->m_aRequestRecordsQueue) &&
                    $iNumRunningRequests < $this->m_iMaxNumConcurrentRequests )
            {
                $aRequestRecord = $this->m_aRequestRecordsQueue[$iNumRunningRequests];
                $oRequest = $aRequestRecord[0];
                $fnOnCompleteCallback = $aRequestRecord[1];
                $bNewCookieSession = $aRequestRecord[2];
                $rcRequestCurl = $oRequest->curl();

                // Set cURL options for the normal cURL handle, having created a temporary file for cookie storage if
                // needed.
                $bRequestSetOptSuccess;
                if ( $this->m_bCookiesAreEnabled && $oRequest->isHttp() )
                {
                    if ( !isset($this->m_sCookiesFp) )
                    {
                        $this->m_sCookiesFp = CFile::createTemporary();
                    }
                    $oRequest->setInternalOptions($bRequestSetOptSuccess, $this->m_sCookiesFp, $bNewCookieSession);
                }
                else
                {
                    $oRequest->setInternalOptions($bRequestSetOptSuccess);
                }
                if ( !$bRequestSetOptSuccess )
                {
                    if ( isset($fnOnCompleteCallback) )
                    {
                        call_user_func($fnOnCompleteCallback, false, "", $oRequest, $this);
                    }
                    CArray::remove($this->m_aRequestRecordsQueue, $iNumRunningRequests);
                    continue;
                }

                // Add the normal cURL handle to the multi cURL handle.
                $iRes = curl_multi_add_handle($this->m_rcMultiCurl, $rcRequestCurl);
                if ( $iRes != 0 )
                {
                    $this->m_bHasError = true;
                    $sCurlError = curl_multi_strerror($iRes);
                    $this->m_sErrorMessage = ( is_cstring($sCurlError) && !CString::isEmpty($sCurlError) ) ?
                        $sCurlError : "The 'curl_multi_add_handle' function failed.";
                    $rbSuccess = false;
                    $oTimeoutPause->end();
                    $this->finalize();
                    return;
                }

                $iNumRunningRequests++;
            }

            if ( $iNumRunningRequests == 0 )
            {
                break;
            }

            // Process the currently added requests until complete or no more data is available. Although
            // `CURLM_CALL_MULTI_PERFORM` is deprecated since libcurl 7.20, keep it for compatibility reasons.
            $iNumRunningTransfers;
            do
            {
                $eMultiExecRes = curl_multi_exec($this->m_rcMultiCurl, $iNumRunningTransfers);
            } while ( $eMultiExecRes == CURLM_CALL_MULTI_PERFORM );
            if ( $eMultiExecRes != CURLM_OK )
            {
                $this->m_bHasError = true;
                $sCurlError = curl_multi_strerror($eMultiExecRes);
                $this->m_sErrorMessage = ( is_cstring($sCurlError) && !CString::isEmpty($sCurlError) ) ? $sCurlError :
                    "The 'curl_multi_exec' function failed.";
                $rbSuccess = false;
                $oTimeoutPause->end();
                $this->finalize();
                return;
            }

            // Check for completed requests, call the callback function for any completed one (if such a function is
            // defined), finalize completed requests, and remove completed requests from the queue.
            while ( true )
            {
                $xCompletedRequestInfo = curl_multi_info_read($this->m_rcMultiCurl);
                if ( !is_cmap($xCompletedRequestInfo) )
                {
                    break;
                }

                // A request has completed.
                assert( '$xCompletedRequestInfo["msg"] == CURLMSG_DONE', vs(isset($this), get_defined_vars()) );
                $rcRequestCurl = $xCompletedRequestInfo["handle"];
                $eRequestRes = $xCompletedRequestInfo["result"];
                $iRequestRecordPos;
                $bFound = CArray::find($this->m_aRequestRecordsQueue, $rcRequestCurl,
                    function ($aRequestRecord, $rcRequestCurl)
                    {
                        $oRequest = $aRequestRecord[0];
                        return ( $oRequest->curl() == $rcRequestCurl );
                    },
                    $iRequestRecordPos);
                assert( '$bFound', vs(isset($this), get_defined_vars()) );
                $aRequestRecord = $this->m_aRequestRecordsQueue[$iRequestRecordPos];
                $oRequest = $aRequestRecord[0];
                $fnOnCompleteCallback = $aRequestRecord[1];

                // Remove the normal cURL handle from the multi cURL handle.
                $iRes = curl_multi_remove_handle($this->m_rcMultiCurl, $rcRequestCurl);
                if ( $iRes != 0 )
                {
                    $this->m_bHasError = true;
                    $sCurlError = curl_multi_strerror($iRes);
                    $this->m_sErrorMessage = ( is_cstring($sCurlError) && !CString::isEmpty($sCurlError) ) ?
                        $sCurlError : "The 'curl_multi_remove_handle' function failed.";
                    $rbSuccess = false;
                    $oTimeoutPause->end();
                    $this->finalize();
                    return;
                }

                if ( $eRequestRes == CURLE_OK )
                {
                    // The request has succeeded.

                    if ( isset($fnOnCompleteCallback) )
                    {
                        $sResponse;
                        if ( $oRequest->isReturnTransferSet() )
                        {
                            $sResponse = curl_multi_getcontent($rcRequestCurl);
                            assert( 'is_cstring($sResponse)', vs(isset($this), get_defined_vars()) );
                        }
                        else
                        {
                            $sResponse = "";
                        }
                        $oRequest->onRequestCompleteOk();  // also close the normal cURL handle
                        call_user_func($fnOnCompleteCallback, true, $sResponse, $oRequest, $this);
                    }
                    else
                    {
                        $oRequest->onRequestCompleteOk();  // also close the normal cURL handle
                    }

                    $bAnySuccessfulRequests = true;
                }
                else
                {
                    // The request has failed.

                    $sCurlError = curl_strerror($eRequestRes);
                    if ( !is_cstring($sCurlError) )
                    {
                        $sCurlError = "";
                    }
                    $oRequest->onRequestCompleteWithError($sCurlError);  // also close the normal cURL handle

                    if ( isset($fnOnCompleteCallback) )
                    {
                        call_user_func($fnOnCompleteCallback, false, "", $oRequest, $this);
                    }
                }

                CArray::remove($this->m_aRequestRecordsQueue, $iRequestRecordPos);
                $iNumRunningRequests--;
            }

            assert( '$iNumRunningRequests == $iNumRunningTransfers', vs(isset($this), get_defined_vars()) );
            if ( $iNumRunningTransfers > 0 )
            {
                // Some requests are still being processed (by remote machines). Wait for more data to appear on
                // sockets, without getting hard on the CPU.
                do
                {
                    $iMultiSelectRes = curl_multi_select($this->m_rcMultiCurl);
                } while ( $iMultiSelectRes == -1 );
            }
            else
            {
                // No requests are being processed. Check if any requests are pending.
                if ( CArray::isEmpty($this->m_aRequestRecordsQueue) )
                {
                    // No requests are pending.
                    break;
                }
            }
        }

        // Set the script's execution time limit like the session has never happened.
        $oTimeoutPause->end();

        if ( !$bAnySuccessfulRequests )
        {
            $this->m_bHasError = true;
            $this->m_sErrorMessage = "None of the session's requests succeeded.";
            $rbSuccess = false;
        }

        $this->finalize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function __destruct ()
    {
        if ( !$this->m_bDone )
        {
            $this->finalize();
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function finalize ()
    {
        if ( is_resource($this->m_rcMultiCurl) )
        {
            curl_multi_close($this->m_rcMultiCurl);
        }

        if ( isset($this->m_sCookiesFp) && CFile::exists($this->m_sCookiesFp) )
        {
            CFile::delete($this->m_sCookiesFp);
        }

        $this->m_bDone = true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // Properties and defaults.
    protected $m_aRequestRecordsQueue;
    protected $m_rcMultiCurl;
    protected $m_bDone = false;
    protected $m_bHasError = false;
    protected $m_sErrorMessage;
    protected $m_bCookiesAreEnabled = true;
    protected $m_iMaxNumConcurrentRequests = 8;
    protected $m_sCookiesFp;
}
