<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that lets you send plain-text and HTML emails with attached content, embedded content, and with support
 * for other email features and do it in the simplest way possible.
 *
 * **You can refer to this class by its alias, which is** `Ml`.
 *
 * To create an email message, you can use `makeSmtp` or `makeSystem` static method. And in order for a message to be
 * sent, specifying any email field other than "From" and "To" is optional. Setting the "From" field can also be
 * optional if the "Sender" or "Return Address" field is specified. Setting the "To" field is not required if the "Cc"
 * or "Bcc" field is set. The "From" and "To" fields can be set either when creating an email message or afterwards
 * with the appropriate `set...` or `add...` method.
 *
 * **Defaults:**
 *
 * * Word wrapping of the text in a message's body is `true`.
 */

// Method signatures:
//   static CMail makeSmtp ($sOutgoingServer, $sUsername, $sPassword, $xFrom = null, $xTo = null,
//     $eSecurity = self::SECURITY_STARTTLS)
//   static CMail makeSystem ($xFrom = null, $xTo = null, $sSendmailCommand = null)
//   static CMail makeGmail ($sUsername, $sPassword, $xFrom = null, $xTo = null)
//   void setFrom ($sAddress, $sName = null)
//   void addFrom ($sAddress, $sName = null)
//   void setTo ($sAddress, $sName = null)
//   void addTo ($sAddress, $sName = null)
//   void addCc ($sAddress, $sName = null)
//   void addBcc ($sAddress, $sName = null)
//   void setSender ($sAddress, $sName = null)
//   void setReturnAddress ($sAddress)
//   void setReplyAddress ($sAddress)
//   void setSubject ($sSubject)
//   void setBody ($sBody, $sType = CMimeType::PLAIN_TEXT)
//   void addAltBody ($sBody, $sType = CMimeType::PLAIN_TEXT)
//   void disableWordWrapping ()
//   void setWordWrapping ($iWidth)
//   void attachFile ($sAttachmentFp, $sType = null)
//   void attachFileWithFilename ($sAttachmentFp, $sFilename, $sType = null)
//   void attachData ($byData, $sFilename, $sType)
//   CUStringObject embeddableCidForFile ($sEmbedFp)
//   CUStringObject embeddableCidForData ($byData, $sFilename, $sType)
//   void setTime (CTime $oTime)
//   void setPriority ($ePriority)
//   int send (&$raFailedAddresses = null)

class CMail extends CRootClass
{
    // Connection security.
    /**
     * `enum` Unencrypted connection.
     *
     * @var enum
     */
    const SECURITY_NONE = 0;
    /**
     * `enum` STARTTLS security.
     *
     * @var enum
     */
    const SECURITY_STARTTLS = 1;
    /**
     * `enum` SSL/TLS security.
     *
     * @var enum
     */
    const SECURITY_SSL_TLS = 2;

    // Message priorities.
    /**
     * `enum` Highest message priority.
     *
     * @var enum
     */
    const PRIORITY_HIGHEST = 0;
    /**
     * `enum` High message priority.
     *
     * @var enum
     */
    const PRIORITY_HIGH = 1;
    /**
     * `enum` Normal message priority.
     *
     * @var enum
     */
    const PRIORITY_NORMAL = 2;
    /**
     * `enum` Low message priority.
     *
     * @var enum
     */
    const PRIORITY_LOW = 3;
    /**
     * `enum` Lowest message priority.
     *
     * @var enum
     */
    const PRIORITY_LOWEST = 4;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an email message to be sent via an account on a remote or local SMTP server and returns it.
     *
     * @param  string $sOutgoingServer The address of the SMTP server.
     * @param  string $sUsername The username of the account.
     * @param  string $sPassword The password to the account.
     * @param  mixed $xFrom **OPTIONAL.** The email address of the sender on whose behalf the message is composed or
     * multiple such addresses. This can be a string, an array of strings, or a map where a key is an email address and
     * the associated value is the name of the sender with that address. In case of a map, it's not required that all
     * the keys have values associated with them.
     * @param  mixed $xTo **OPTIONAL.** The email address of the recipient to whom the message is composed or multiple
     * such addresses. This can be a string, an array of strings, or a map where a key is an email address and the
     * associated value is the name of the recipient with that address. In case of a map, it's not required that all
     * the keys have values associated with them.
     * @param  enum $eSecurity **OPTIONAL. Default is** `SECURITY_STARTTLS`. The security to be used for the
     * connection. Can be `SECURITY_NONE`, `SECURITY_STARTTLS`, or `SECURITY_SSL_TLS`.
     *
     * @return CMail A new email message ready for content to be added.
     */

    public static function makeSmtp ($sOutgoingServer, $sUsername, $sPassword, $xFrom = null, $xTo = null,
        $eSecurity = self::SECURITY_STARTTLS)
    {
        assert( 'is_cstring($sOutgoingServer) && is_cstring($sUsername) && is_cstring($sPassword) && ' .
                '(!isset($xFrom) || is_cstring($xFrom) || is_collection($xFrom)) && ' .
                '(!isset($xTo) || is_cstring($xTo) || is_collection($xTo)) && is_enum($eSecurity)',
            vs(isset($this), get_defined_vars()) );

        $xFrom = _from_oop_tp($xFrom);
        $xTo = _from_oop_tp($xTo);

        $oMail = new self();

        $bSecure = false;
        $sSecurity;
        $iPort;
        switch ( $eSecurity )
        {
        case self::SECURITY_NONE:
            $iPort = 25;
            break;
        case self::SECURITY_STARTTLS:
            $bSecure = true;
            $sSecurity = "tls";
            $iPort = 587;
            break;
        case self::SECURITY_SSL_TLS:
            $bSecure = true;
            $sSecurity = "ssl";
            $iPort = 465;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }
        $oTransport;
        if ( $bSecure )
        {
            $oTransport = Swift_SmtpTransport::newInstance($sOutgoingServer, $iPort, $sSecurity);
        }
        else
        {
            $oTransport = Swift_SmtpTransport::newInstance($sOutgoingServer, $iPort);
        }
        $oTransport->setUsername($sUsername);
        $oTransport->setPassword($sPassword);
        $oMail->m_oSwiftMailer = Swift_Mailer::newInstance($oTransport);

        $oMail->m_oSwiftMessage = Swift_Message::newInstance();
        if ( isset($xFrom) )
        {
            $oMail->m_xFrom = $xFrom;
        }
        if ( isset($xTo) )
        {
            $oMail->m_xTo = $xTo;
        }
        $oMail->m_oSwiftMessage->setCharset("utf-8");
        $oMail->m_oSwiftMessage->setSubject(self::$ms_sDefaultSubject);

        return $oMail;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an email message to be sent via the OS's emailing facility and returns it.
     *
     * @param  mixed $xFrom **OPTIONAL.** The email address of the sender on whose behalf the message is composed or
     * multiple such addresses. This can be a string, an array of strings, or a map where a key is an email address and
     * the associated value is the name of the sender with that address. In case of a map, it's not required that all
     * the keys have values associated with them.
     * @param  mixed $xTo **OPTIONAL.** The email address of the recipient to whom the message is composed or multiple
     * such addresses. This can be a string, an array of strings, or a map where a key is an email address and the
     * associated value is the name of the recipient with that address. In case of a map, it's not required that all
     * the keys have values associated with them.
     * @param  string $sSendmailCommand **OPTIONAL. Default is** *OS's default*. The custom command to be used for
     * sending the message, e.g. `/usr/sbin/sendmail -oi -t`.
     *
     * @return CMail A new email message ready for content to be added.
     */

    public static function makeSystem ($xFrom = null, $xTo = null, $sSendmailCommand = null)
    {
        assert( '(!isset($xFrom) || is_cstring($xFrom) || is_collection($xFrom)) && ' .
                '(!isset($xTo) || is_cstring($xTo) || is_collection($xTo)) && ' .
                '(!isset($sSendmailCommand) || is_cstring($sSendmailCommand))', vs(isset($this), get_defined_vars()) );

        $xFrom = _from_oop_tp($xFrom);
        $xTo = _from_oop_tp($xTo);

        $oMail = new self();

        $oTransport;
        if ( !isset($sSendmailCommand) )
        {
            $oTransport = Swift_SendmailTransport::newInstance();
        }
        else
        {
            $oTransport = Swift_SendmailTransport::newInstance($sSendmailCommand);
        }
        $oMail->m_oSwiftMailer = Swift_Mailer::newInstance($oTransport);

        $oMail->m_oSwiftMessage = Swift_Message::newInstance();
        if ( isset($xFrom) )
        {
            $oMail->m_xFrom = $xFrom;
        }
        if ( isset($xTo) )
        {
            $oMail->m_xTo = $xTo;
        }
        $oMail->m_oSwiftMessage->setCharset("utf-8");
        $oMail->m_oSwiftMessage->setSubject(self::$ms_sDefaultSubject);

        return $oMail;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an email message to be sent via a Gmail account.
     *
     * @param  string $sUsername The username of the account.
     * @param  string $sPassword The password to the account.
     * @param  mixed $xFrom **OPTIONAL.** The email address of the sender on whose behalf the message is composed or
     * multiple such addresses. This can be a string, an array of strings, or a map where a key is an email address and
     * the associated value is the name of the sender with that address. In case of a map, it's not required that all
     * the keys have values associated with them.
     * @param  mixed $xTo **OPTIONAL.** The email address of the recipient to whom the message is composed or multiple
     * such addresses. This can be a string, an array of strings, or a map where a key is an email address and the
     * associated value is the name of the recipient with that address. In case of a map, it's not required that all
     * the keys have values associated with them.
     *
     * @return CMail A new email message ready for content to be added.
     */

    public static function makeGmail ($sUsername, $sPassword, $xFrom = null, $xTo = null)
    {
        assert( 'is_cstring($sUsername) && is_cstring($sPassword) && ' .
                '(!isset($xFrom) || is_cstring($xFrom) || is_collection($xFrom)) && ' .
                '(!isset($xTo) || is_cstring($xTo) || is_collection($xTo))', vs(isset($this), get_defined_vars()) );

        return self::makeSmtp("smtp.googlemail.com", $sUsername, $sPassword, $xFrom, $xTo, self::SECURITY_SSL_TLS);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the email address and, optionally, the name of the sender on whose behalf a message is composed.
     *
     * @param  string $sAddress The email address of the sender.
     * @param  string $sName **OPTIONAL.** The name of the sender.
     *
     * @return void
     */

    public function setFrom ($sAddress, $sName = null)
    {
        assert( 'is_cstring($sAddress) && (!isset($sName) || is_cstring($sName))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($sName) )
        {
            $this->m_xFrom = $sAddress;
        }
        else
        {
            $this->m_xFrom = [$sAddress => $sName];
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the email address and, optionally, the name of a sender on whose behalf a message is composed to the list
     * of other senders.
     *
     * @param  string $sAddress The email address of the sender.
     * @param  string $sName **OPTIONAL.** The name of the sender.
     *
     * @return void
     */

    public function addFrom ($sAddress, $sName = null)
    {
        assert( 'is_cstring($sAddress) && (!isset($sName) || is_cstring($sName))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_xFrom) )
        {
            $this->m_xFrom = CMap::make();
        }
        else if ( is_cstring($this->m_xFrom) )
        {
            $this->m_xFrom = [$this->m_xFrom];
        }

        if ( !isset($sName) )
        {
            CMap::insertValue($this->m_xFrom, $sAddress);
        }
        else
        {
            $this->m_xFrom[$sAddress] = $sName;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the email address and, optionally, the name of the recipient to whom a message is composed.
     *
     * @param  string $sAddress The email address of the recipient.
     * @param  string $sName **OPTIONAL.** The name of the recipient.
     *
     * @return void
     */

    public function setTo ($sAddress, $sName = null)
    {
        assert( 'is_cstring($sAddress) && (!isset($sName) || is_cstring($sName))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($sName) )
        {
            $this->m_xTo = $sAddress;
        }
        else
        {
            $this->m_xTo = [$sAddress => $sName];
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the email address and, optionally, the name of a recipient to whom a message is composed to the list of
     * other recipients.
     *
     * @param  string $sAddress The email address of the recipient.
     * @param  string $sName **OPTIONAL.** The name of the recipient.
     *
     * @return void
     */

    public function addTo ($sAddress, $sName = null)
    {
        assert( 'is_cstring($sAddress) && (!isset($sName) || is_cstring($sName))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_xTo) )
        {
            $this->m_xTo = CMap::make();
        }
        else if ( is_cstring($this->m_xTo) )
        {
            $this->m_xTo = [$this->m_xTo];
        }

        if ( !isset($sName) )
        {
            CMap::insertValue($this->m_xTo, $sAddress);
        }
        else
        {
            $this->m_xTo[$sAddress] = $sName;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the email address and, optionally, the name of a "carbon copy" recipient who should receive a copy of a
     * message so that this recipient is visible to all other recipients.
     *
     * @param  string $sAddress The email address of the recipient.
     * @param  string $sName **OPTIONAL.** The name of the recipient.
     *
     * @return void
     */

    public function addCc ($sAddress, $sName = null)
    {
        assert( 'is_cstring($sAddress) && (!isset($sName) || is_cstring($sName))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_xCc) )
        {
            $this->m_xCc = CMap::make();
        }

        if ( !isset($sName) )
        {
            CMap::insertValue($this->m_xCc, $sAddress);
        }
        else
        {
            $this->m_xCc[$sAddress] = $sName;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the email address and, optionally, the name of a "blind carbon copy" recipient who should receive a copy of
     * a message so that this recipient is not visible to any other recipients.
     *
     * @param  string $sAddress The email address of the recipient.
     * @param  string $sName **OPTIONAL.** The name of the recipient.
     *
     * @return void
     */

    public function addBcc ($sAddress, $sName = null)
    {
        assert( 'is_cstring($sAddress) && (!isset($sName) || is_cstring($sName))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_xBcc) )
        {
            $this->m_xBcc = CMap::make();
        }

        if ( !isset($sName) )
        {
            CMap::insertValue($this->m_xBcc, $sAddress);
        }
        else
        {
            $this->m_xBcc[$sAddress] = $sName;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the email address and, optionally, the name of the sender who should be known as the actual sender of a
     * message.
     *
     * This field has a higher precedence than the regular "From" sender(s).
     *
     * @param  string $sAddress The email address of the sender.
     * @param  string $sName **OPTIONAL.** The name of the sender.
     *
     * @return void
     */

    public function setSender ($sAddress, $sName = null)
    {
        assert( 'is_cstring($sAddress) && (!isset($sName) || is_cstring($sName))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($sName) )
        {
            $this->m_xSender = $sAddress;
        }
        else
        {
            $this->m_xSender = [$sAddress => $sName];
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the email address to which a message should be "bounced" if it could not be properly delivered.
     *
     * @param  string $sAddress The email address to which the message should be "bounced" if it could not be properly
     * delivered.
     *
     * @return void
     */

    public function setReturnAddress ($sAddress)
    {
        assert( 'is_cstring($sAddress)', vs(isset($this), get_defined_vars()) );
        $this->m_sReturnAddress = $sAddress;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the email address to which any replies to a message should be sent.
     *
     * @param  string $sAddress The email address to which any replies to the message should be sent.
     *
     * @return void
     */

    public function setReplyAddress ($sAddress)
    {
        assert( 'is_cstring($sAddress)', vs(isset($this), get_defined_vars()) );
        $this->m_sReplyAddress = $sAddress;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the subject line of a message.
     *
     * @param  string $sSubject The subject line of the message.
     *
     * @return void
     */

    public function setSubject ($sSubject)
    {
        assert( 'is_cstring($sSubject)', vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_oSwiftMessage)', vs(isset($this), get_defined_vars()) );

        $this->m_oSwiftMessage->setSubject($sSubject);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the body of a message.
     *
     * @param  string $sBody The body of the message.
     * @param  string $sType **OPTIONAL. Default is** `CMimeType::PLAIN_TEXT`. The MIME type of the body.
     *
     * @return void
     */

    public function setBody ($sBody, $sType = CMimeType::PLAIN_TEXT)
    {
        assert( 'is_cstring($sBody) && is_cstring($sType)', vs(isset($this), get_defined_vars()) );

        $this->m_sBody = $sBody;
        $this->m_sBodyType = $sType;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the alternative body of a message to be used if the primary body cannot be displayed.
     *
     * @param  string $sBody The alternative body of the message.
     * @param  string $sType **OPTIONAL. Default is** `CMimeType::PLAIN_TEXT`. The MIME type of the alternative body.
     *
     * @return void
     */

    public function addAltBody ($sBody, $sType = CMimeType::PLAIN_TEXT)
    {
        assert( 'is_cstring($sBody) && is_cstring($sType)', vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_aAltBodiesAndTypes) )
        {
            $this->m_aAltBodiesAndTypes = CArray::make();
        }

        $aBodyAndType = CArray::fromElements($sBody, $sType);
        CArray::push($this->m_aAltBodiesAndTypes, $aBodyAndType);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Disables word wrapping for the body of a message.
     *
     * @return void
     */

    public function disableWordWrapping ()
    {
        $this->m_bBodyWordWrappingIsDisabled = true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the width to which the text in the body of a message should be wrapped.
     *
     * @param  int $iWidth The wrapping width, in characters.
     *
     * @return void
     */

    public function setWordWrapping ($iWidth)
    {
        assert( 'is_int($iWidth)', vs(isset($this), get_defined_vars()) );
        $this->m_iBodyWordWrappingWidth = $iWidth;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Attaches a file to a message.
     *
     * @param  string $sAttachmentFp The path to the file to be attached.
     * @param  string $sType **OPTIONAL.** The MIME type of the file's contents.
     *
     * @return void
     */

    public function attachFile ($sAttachmentFp, $sType = null)
    {
        assert( 'is_cstring($sAttachmentFp) && (!isset($sType) || is_cstring($sType))',
            vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_oSwiftMessage)', vs(isset($this), get_defined_vars()) );

        $sAttachmentFp = CFilePath::frameworkPath($sAttachmentFp);

        $oAttachment;
        if ( !isset($sType) )
        {
            $oAttachment = Swift_Attachment::fromPath($sAttachmentFp);
        }
        else
        {
            $oAttachment = Swift_Attachment::fromPath($sAttachmentFp, $sType);
        }
        if ( self::$ms_bAllAttachmentsAreInline )
        {
            $oAttachment->setDisposition("inline");
        }
        $this->m_oSwiftMessage->attach($oAttachment);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Attaches a file to a message, also assigning a custom name to the attached file.
     *
     * @param  string $sAttachmentFp The path to the file to be attached.
     * @param  string $sFilename The custom name for the file.
     * @param  string $sType **OPTIONAL.** The MIME type of the file's contents.
     *
     * @return void
     */

    public function attachFileWithFilename ($sAttachmentFp, $sFilename, $sType = null)
    {
        assert( 'is_cstring($sAttachmentFp) && is_cstring($sFilename) && (!isset($sType) || is_cstring($sType))',
            vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_oSwiftMessage)', vs(isset($this), get_defined_vars()) );

        $sAttachmentFp = CFilePath::frameworkPath($sAttachmentFp);

        $oAttachment;
        if ( !isset($sType) )
        {
            $oAttachment = Swift_Attachment::fromPath($sAttachmentFp);
        }
        else
        {
            $oAttachment = Swift_Attachment::fromPath($sAttachmentFp, $sType);
        }
        $oAttachment->setFilename($sFilename);
        if ( self::$ms_bAllAttachmentsAreInline )
        {
            $oAttachment->setDisposition("inline");
        }
        $this->m_oSwiftMessage->attach($oAttachment);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Attaches a data to a message.
     *
     * @param  data $byData The data to be attached.
     * @param  string $sFilename The name by which the data is to be seen as a file by the recipient(s).
     * @param  string $sType The MIME type of the data's contents.
     *
     * @return void
     */

    public function attachData ($byData, $sFilename, $sType)
    {
        assert( 'is_cstring($byData) && is_cstring($sFilename) && is_cstring($sType)',
            vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_oSwiftMessage)', vs(isset($this), get_defined_vars()) );

        $byData = _from_oop_tp($byData);

        $oAttachment = Swift_Attachment::newInstance($byData, $sFilename, $sType);
        if ( self::$ms_bAllAttachmentsAreInline )
        {
            $oAttachment->setDisposition("inline");
        }
        $this->m_oSwiftMessage->attach($oAttachment);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Generates a CID for a file by which the file can be embedded into the body of a message.
     *
     * The same CID can be used to embed a file in more than one place.
     *
     * For example, an image for which a CID was generated and put into `$cid` variable can be embedded into a message
     * with HTML type of body by `<img src="' . $cid . '" alt="Title" />`.
     *
     * @param  string $sEmbedFp The path to the file to be embedded.
     *
     * @return CUStringObject The embeddable CID of the file.
     */

    public function embeddableCidForFile ($sEmbedFp)
    {
        assert( 'is_cstring($sEmbedFp)', vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_oSwiftMessage)', vs(isset($this), get_defined_vars()) );

        $sEmbedFp = CFilePath::frameworkPath($sEmbedFp);

        $oEmbeddedFile = Swift_EmbeddedFile::fromPath($sEmbedFp);
        return $this->m_oSwiftMessage->embed($oEmbeddedFile);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Generates a CID for a data by which the data can be embedded into the body of a message.
     *
     * The same CID can be used to embed a data in more than one place.
     *
     * For example, an image for which a CID was generated and put into `$cid` variable can be embedded into a message
     * with HTML type of body by `<img src="' . $cid . '" alt="Title" />`.
     *
     * @param  data $byData The data to be embedded.
     * @param  string $sFilename The filename to be associated with the embedded data.
     * @param  string $sType The MIME type of the data's contents.
     *
     * @return CUStringObject The embeddable CID of the data.
     */

    public function embeddableCidForData ($byData, $sFilename, $sType)
    {
        assert( 'is_cstring($byData) && is_cstring($sFilename) && is_cstring($sType)',
            vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_oSwiftMessage)', vs(isset($this), get_defined_vars()) );

        $byData = _from_oop_tp($byData);

        $oEmbeddedFile = Swift_EmbeddedFile::newInstance($byData, $sFilename, $sType);
        return $this->m_oSwiftMessage->embed($oEmbeddedFile);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the declarative time when a message was sent.
     *
     * @param  CTime $oTime The point in time to be declared as the moment of the message's dispatching.
     *
     * @return void
     */

    public function setTime (CTime $oTime)
    {
        assert( 'isset($this->m_oSwiftMessage)', vs(isset($this), get_defined_vars()) );
        $this->m_oSwiftMessage->setDate($oTime->UTime());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the priority of a message.
     *
     * @param  enum $ePriority The priority of the message. Can be `PRIORITY_HIGHEST`, `PRIORITY_HIGH`,
     * `PRIORITY_NORMAL`, `PRIORITY_LOW`, or `PRIORITY_LOWEST`.
     *
     * @return void
     */

    public function setPriority ($ePriority)
    {
        assert( 'is_enum($ePriority)', vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_oSwiftMessage)', vs(isset($this), get_defined_vars()) );

        $this->m_oSwiftMessage->setPriority($ePriority + 1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sends a message to the recipient(s).
     *
     * @param  reference $raFailedAddresses **OPTIONAL. OUTPUT.** After the method is called with this parameter
     * provided, the parameter's value, which is of type `CArrayObject`, is an array containing the email addresses of
     * the recipients who failed to receive the message.
     *
     * @return int The number of recipients who have successfully received the message.
     */

    public function send (&$raFailedAddresses = null)
    {
        assert( 'isset($this->m_oSwiftMailer) && isset($this->m_oSwiftMessage)', vs(isset($this), get_defined_vars()) );
        assert( '(isset($this->m_xFrom) || isset($this->m_xSender) || isset($this->m_sReturnAddress)) && ' .
                '(isset($this->m_xTo) || isset($this->m_xCc) || isset($this->m_xBcc))',
            vs(isset($this), get_defined_vars()) );

        $oMessage = $this->m_oSwiftMessage;

        if ( isset($this->m_xFrom) )
        {
            $oMessage->setFrom($this->m_xFrom);
        }
        if ( isset($this->m_xTo) )
        {
            $oMessage->setTo($this->m_xTo);
        }
        if ( isset($this->m_xCc) )
        {
            $oMessage->setCc($this->m_xCc);
        }
        if ( isset($this->m_xBcc) )
        {
            $oMessage->setBcc($this->m_xBcc);
        }
        if ( isset($this->m_xSender) )
        {
            $oMessage->setSender($this->m_xSender);
        }
        if ( isset($this->m_sReturnAddress) )
        {
            $oMessage->setReturnPath($this->m_sReturnAddress);
        }
        if ( isset($this->m_sReplyAddress) )
        {
            $oMessage->setReplyTo($this->m_sReplyAddress);
        }

        if ( isset($this->m_sBody) )
        {
            if ( CString::equals($this->m_sBodyType, CMimeType::PLAIN_TEXT) )
            {
                $this->m_sBody = $this->maybeWrapText($this->m_sBody);
            }
            $oMessage->setBody($this->m_sBody, $this->m_sBodyType);
        }

        if ( isset($this->m_aAltBodiesAndTypes) )
        {
            $iLen = CArray::length($this->m_aAltBodiesAndTypes);
            for ($i = 0; $i < $iLen; $i++)
            {
                $aBodyAndType = $this->m_aAltBodiesAndTypes[$i];
                $sBody = $aBodyAndType[0];
                $sType = $aBodyAndType[1];
                if ( CString::equals($sType, CMimeType::PLAIN_TEXT) )
                {
                    $sBody = $this->maybeWrapText($sBody);
                }
                $oMessage->addPart($sBody, $sType);
            }
        }

        $mFailedAddresses;
        $iRes = $this->m_oSwiftMailer->send($oMessage, $mFailedAddresses);
        if ( is_cmap($mFailedAddresses) )
        {
            $raFailedAddresses = oop_a(CArray::fromPArray($mFailedAddresses));
        }
        $iRes = ( is_int($iRes) ) ? $iRes : 0;
        return $iRes;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function maybeWrapText ($sText)
    {
        if ( !isset($this->m_bBodyWordWrappingIsDisabled) || !$this->m_bBodyWordWrappingIsDisabled )
        {
            // Wrap the text.
            $iWidth;
            if ( !isset($this->m_iBodyWordWrappingWidth) )
            {
                $iWidth = self::$ms_iDefaultWordWrappingWidth;
            }
            else
            {
                $iWidth = $this->m_iBodyWordWrappingWidth;
            }
            $sText = CUString::wordWrap($sText, $iWidth);
        }
        return $sText;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_oSwiftMailer;
    protected $m_oSwiftMessage;
    protected $m_xFrom;
    protected $m_xTo;
    protected $m_xCc;
    protected $m_xBcc;
    protected $m_xSender;
    protected $m_sReturnAddress;
    protected $m_sReplyAddress;
    protected $m_iBodyWordWrappingWidth;
    protected $m_bBodyWordWrappingIsDisabled;
    protected $m_sBody;
    protected $m_sBodyType;
    protected $m_aAltBodiesAndTypes;

    protected static $ms_sDefaultSubject = "";
    protected static $ms_iDefaultWordWrappingWidth = 80;

    // "Inline" attachments are expected to be displayed inline and, if possible, at the message's end.
    protected static $ms_bAllAttachmentsAreInline = true;
}
