<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
//   static CMail makeSmtp ($outgoingServer, $username, $password, $from = null, $to = null,
//     $security = self::SECURITY_STARTTLS)
//   static CMail makeSystem ($from = null, $to = null, $sendmailCommand = null)
//   static CMail makeGmail ($username, $password, $from = null, $to = null)
//   void setFrom ($address, $name = null)
//   void addFrom ($address, $name = null)
//   void setTo ($address, $name = null)
//   void addTo ($address, $name = null)
//   void addCc ($address, $name = null)
//   void addBcc ($address, $name = null)
//   void setSender ($address, $name = null)
//   void setReturnAddress ($address)
//   void setReplyAddress ($address)
//   void setSubject ($subject)
//   void setBody ($body, $type = CMimeType::PLAIN_TEXT)
//   void addAltBody ($body, $type = CMimeType::PLAIN_TEXT)
//   void disableWordWrapping ()
//   void setWordWrapping ($width)
//   void attachFile ($attachmentFp, $type = null)
//   void attachFileWithFilename ($attachmentFp, $filename, $type = null)
//   void attachData ($data, $filename, $type)
//   CUStringObject embeddableCidForFile ($embedFp)
//   CUStringObject embeddableCidForData ($data, $filename, $type)
//   void setTime (CTime $time)
//   void setPriority ($priority)
//   int send (&$failedAddresses = null)

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
     * @param  string $outgoingServer The address of the SMTP server.
     * @param  string $username The username of the account.
     * @param  string $password The password to the account.
     * @param  mixed $from **OPTIONAL.** The email address of the sender on whose behalf the message is composed or
     * multiple such addresses. This can be a string, an array of strings, or a map where a key is an email address and
     * the associated value is the name of the sender with that address. In case of a map, it's not required that all
     * the keys have values associated with them.
     * @param  mixed $to **OPTIONAL.** The email address of the recipient to whom the message is composed or multiple
     * such addresses. This can be a string, an array of strings, or a map where a key is an email address and the
     * associated value is the name of the recipient with that address. In case of a map, it's not required that all
     * the keys have values associated with them.
     * @param  enum $security **OPTIONAL. Default is** `SECURITY_STARTTLS`. The security to be used for the
     * connection. Can be `SECURITY_NONE`, `SECURITY_STARTTLS`, or `SECURITY_SSL_TLS`.
     *
     * @return CMail A new email message ready for content to be added.
     */

    public static function makeSmtp ($outgoingServer, $username, $password, $from = null, $to = null,
        $security = self::SECURITY_STARTTLS)
    {
        assert( 'is_cstring($outgoingServer) && is_cstring($username) && is_cstring($password) && ' .
                '(!isset($from) || is_cstring($from) || is_collection($from)) && ' .
                '(!isset($to) || is_cstring($to) || is_collection($to)) && is_enum($security)',
            vs(isset($this), get_defined_vars()) );

        $from = _from_oop_tp($from);
        $to = _from_oop_tp($to);

        $mail = new self();

        $secure = false;
        $security;
        $port;
        switch ( $security )
        {
        case self::SECURITY_NONE:
            $port = 25;
            break;
        case self::SECURITY_STARTTLS:
            $secure = true;
            $security = "tls";
            $port = 587;
            break;
        case self::SECURITY_SSL_TLS:
            $secure = true;
            $security = "ssl";
            $port = 465;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }
        $transport;
        if ( $secure )
        {
            $transport = Swift_SmtpTransport::newInstance($outgoingServer, $port, $security);
        }
        else
        {
            $transport = Swift_SmtpTransport::newInstance($outgoingServer, $port);
        }
        $transport->setUsername($username);
        $transport->setPassword($password);
        $mail->m_swiftMailer = Swift_Mailer::newInstance($transport);

        $mail->m_swiftMessage = Swift_Message::newInstance();
        if ( isset($from) )
        {
            $mail->m_from = $from;
        }
        if ( isset($to) )
        {
            $mail->m_to = $to;
        }
        $mail->m_swiftMessage->setCharset("utf-8");
        $mail->m_swiftMessage->setSubject(self::$ms_defaultSubject);

        return $mail;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an email message to be sent via the OS's emailing facility and returns it.
     *
     * @param  mixed $from **OPTIONAL.** The email address of the sender on whose behalf the message is composed or
     * multiple such addresses. This can be a string, an array of strings, or a map where a key is an email address and
     * the associated value is the name of the sender with that address. In case of a map, it's not required that all
     * the keys have values associated with them.
     * @param  mixed $to **OPTIONAL.** The email address of the recipient to whom the message is composed or multiple
     * such addresses. This can be a string, an array of strings, or a map where a key is an email address and the
     * associated value is the name of the recipient with that address. In case of a map, it's not required that all
     * the keys have values associated with them.
     * @param  string $sendmailCommand **OPTIONAL. Default is** *OS's default*. The custom command to be used for
     * sending the message, e.g. `/usr/sbin/sendmail -oi -t`.
     *
     * @return CMail A new email message ready for content to be added.
     */

    public static function makeSystem ($from = null, $to = null, $sendmailCommand = null)
    {
        assert( '(!isset($from) || is_cstring($from) || is_collection($from)) && ' .
                '(!isset($to) || is_cstring($to) || is_collection($to)) && ' .
                '(!isset($sendmailCommand) || is_cstring($sendmailCommand))', vs(isset($this), get_defined_vars()) );

        $from = _from_oop_tp($from);
        $to = _from_oop_tp($to);

        $mail = new self();

        $transport;
        if ( !isset($sendmailCommand) )
        {
            $transport = Swift_SendmailTransport::newInstance();
        }
        else
        {
            $transport = Swift_SendmailTransport::newInstance($sendmailCommand);
        }
        $mail->m_swiftMailer = Swift_Mailer::newInstance($transport);

        $mail->m_swiftMessage = Swift_Message::newInstance();
        if ( isset($from) )
        {
            $mail->m_from = $from;
        }
        if ( isset($to) )
        {
            $mail->m_to = $to;
        }
        $mail->m_swiftMessage->setCharset("utf-8");
        $mail->m_swiftMessage->setSubject(self::$ms_defaultSubject);

        return $mail;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an email message to be sent via a Gmail account.
     *
     * @param  string $username The username of the account.
     * @param  string $password The password to the account.
     * @param  mixed $from **OPTIONAL.** The email address of the sender on whose behalf the message is composed or
     * multiple such addresses. This can be a string, an array of strings, or a map where a key is an email address and
     * the associated value is the name of the sender with that address. In case of a map, it's not required that all
     * the keys have values associated with them.
     * @param  mixed $to **OPTIONAL.** The email address of the recipient to whom the message is composed or multiple
     * such addresses. This can be a string, an array of strings, or a map where a key is an email address and the
     * associated value is the name of the recipient with that address. In case of a map, it's not required that all
     * the keys have values associated with them.
     *
     * @return CMail A new email message ready for content to be added.
     */

    public static function makeGmail ($username, $password, $from = null, $to = null)
    {
        assert( 'is_cstring($username) && is_cstring($password) && ' .
                '(!isset($from) || is_cstring($from) || is_collection($from)) && ' .
                '(!isset($to) || is_cstring($to) || is_collection($to))', vs(isset($this), get_defined_vars()) );

        return self::makeSmtp("smtp.googlemail.com", $username, $password, $from, $to, self::SECURITY_SSL_TLS);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the email address and, optionally, the name of the sender on whose behalf a message is composed.
     *
     * @param  string $address The email address of the sender.
     * @param  string $name **OPTIONAL.** The name of the sender.
     *
     * @return void
     */

    public function setFrom ($address, $name = null)
    {
        assert( 'is_cstring($address) && (!isset($name) || is_cstring($name))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($name) )
        {
            $this->m_from = $address;
        }
        else
        {
            $this->m_from = [$address => $name];
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the email address and, optionally, the name of a sender on whose behalf a message is composed to the list
     * of other senders.
     *
     * @param  string $address The email address of the sender.
     * @param  string $name **OPTIONAL.** The name of the sender.
     *
     * @return void
     */

    public function addFrom ($address, $name = null)
    {
        assert( 'is_cstring($address) && (!isset($name) || is_cstring($name))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_from) )
        {
            $this->m_from = CMap::make();
        }
        else if ( is_cstring($this->m_from) )
        {
            $this->m_from = [$this->m_from];
        }

        if ( !isset($name) )
        {
            CMap::insertValue($this->m_from, $address);
        }
        else
        {
            $this->m_from[$address] = $name;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the email address and, optionally, the name of the recipient to whom a message is composed.
     *
     * @param  string $address The email address of the recipient.
     * @param  string $name **OPTIONAL.** The name of the recipient.
     *
     * @return void
     */

    public function setTo ($address, $name = null)
    {
        assert( 'is_cstring($address) && (!isset($name) || is_cstring($name))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($name) )
        {
            $this->m_to = $address;
        }
        else
        {
            $this->m_to = [$address => $name];
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the email address and, optionally, the name of a recipient to whom a message is composed to the list of
     * other recipients.
     *
     * @param  string $address The email address of the recipient.
     * @param  string $name **OPTIONAL.** The name of the recipient.
     *
     * @return void
     */

    public function addTo ($address, $name = null)
    {
        assert( 'is_cstring($address) && (!isset($name) || is_cstring($name))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_to) )
        {
            $this->m_to = CMap::make();
        }
        else if ( is_cstring($this->m_to) )
        {
            $this->m_to = [$this->m_to];
        }

        if ( !isset($name) )
        {
            CMap::insertValue($this->m_to, $address);
        }
        else
        {
            $this->m_to[$address] = $name;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the email address and, optionally, the name of a "carbon copy" recipient who should receive a copy of a
     * message so that this recipient is visible to all other recipients.
     *
     * @param  string $address The email address of the recipient.
     * @param  string $name **OPTIONAL.** The name of the recipient.
     *
     * @return void
     */

    public function addCc ($address, $name = null)
    {
        assert( 'is_cstring($address) && (!isset($name) || is_cstring($name))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_cc) )
        {
            $this->m_cc = CMap::make();
        }

        if ( !isset($name) )
        {
            CMap::insertValue($this->m_cc, $address);
        }
        else
        {
            $this->m_cc[$address] = $name;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the email address and, optionally, the name of a "blind carbon copy" recipient who should receive a copy of
     * a message so that this recipient is not visible to any other recipients.
     *
     * @param  string $address The email address of the recipient.
     * @param  string $name **OPTIONAL.** The name of the recipient.
     *
     * @return void
     */

    public function addBcc ($address, $name = null)
    {
        assert( 'is_cstring($address) && (!isset($name) || is_cstring($name))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_bcc) )
        {
            $this->m_bcc = CMap::make();
        }

        if ( !isset($name) )
        {
            CMap::insertValue($this->m_bcc, $address);
        }
        else
        {
            $this->m_bcc[$address] = $name;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the email address and, optionally, the name of the sender who should be known as the actual sender of a
     * message.
     *
     * This field has a higher precedence than the regular "From" sender(s).
     *
     * @param  string $address The email address of the sender.
     * @param  string $name **OPTIONAL.** The name of the sender.
     *
     * @return void
     */

    public function setSender ($address, $name = null)
    {
        assert( 'is_cstring($address) && (!isset($name) || is_cstring($name))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($name) )
        {
            $this->m_sender = $address;
        }
        else
        {
            $this->m_sender = [$address => $name];
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the email address to which a message should be "bounced" if it could not be properly delivered.
     *
     * @param  string $address The email address to which the message should be "bounced" if it could not be properly
     * delivered.
     *
     * @return void
     */

    public function setReturnAddress ($address)
    {
        assert( 'is_cstring($address)', vs(isset($this), get_defined_vars()) );
        $this->m_returnAddress = $address;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the email address to which any replies to a message should be sent.
     *
     * @param  string $address The email address to which any replies to the message should be sent.
     *
     * @return void
     */

    public function setReplyAddress ($address)
    {
        assert( 'is_cstring($address)', vs(isset($this), get_defined_vars()) );
        $this->m_replyAddress = $address;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the subject line of a message.
     *
     * @param  string $subject The subject line of the message.
     *
     * @return void
     */

    public function setSubject ($subject)
    {
        assert( 'is_cstring($subject)', vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_swiftMessage)', vs(isset($this), get_defined_vars()) );

        $this->m_swiftMessage->setSubject($subject);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the body of a message.
     *
     * @param  string $body The body of the message.
     * @param  string $type **OPTIONAL. Default is** `CMimeType::PLAIN_TEXT`. The MIME type of the body.
     *
     * @return void
     */

    public function setBody ($body, $type = CMimeType::PLAIN_TEXT)
    {
        assert( 'is_cstring($body) && is_cstring($type)', vs(isset($this), get_defined_vars()) );

        $this->m_body = $body;
        $this->m_bodyType = $type;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the alternative body of a message to be used if the primary body cannot be displayed.
     *
     * @param  string $body The alternative body of the message.
     * @param  string $type **OPTIONAL. Default is** `CMimeType::PLAIN_TEXT`. The MIME type of the alternative body.
     *
     * @return void
     */

    public function addAltBody ($body, $type = CMimeType::PLAIN_TEXT)
    {
        assert( 'is_cstring($body) && is_cstring($type)', vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_altBodiesAndTypes) )
        {
            $this->m_altBodiesAndTypes = CArray::make();
        }

        $bodyAndType = CArray::fromElements($body, $type);
        CArray::push($this->m_altBodiesAndTypes, $bodyAndType);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Disables word wrapping for the body of a message.
     *
     * @return void
     */

    public function disableWordWrapping ()
    {
        $this->m_bodyWordWrappingIsDisabled = true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the width to which the text in the body of a message should be wrapped.
     *
     * @param  int $width The wrapping width, in characters.
     *
     * @return void
     */

    public function setWordWrapping ($width)
    {
        assert( 'is_int($width)', vs(isset($this), get_defined_vars()) );
        $this->m_bodyWordWrappingWidth = $width;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Attaches a file to a message.
     *
     * @param  string $attachmentFp The path to the file to be attached.
     * @param  string $type **OPTIONAL.** The MIME type of the file's contents.
     *
     * @return void
     */

    public function attachFile ($attachmentFp, $type = null)
    {
        assert( 'is_cstring($attachmentFp) && (!isset($type) || is_cstring($type))',
            vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_swiftMessage)', vs(isset($this), get_defined_vars()) );

        $attachmentFp = CFilePath::frameworkPath($attachmentFp);

        $attachment;
        if ( !isset($type) )
        {
            $attachment = Swift_Attachment::fromPath($attachmentFp);
        }
        else
        {
            $attachment = Swift_Attachment::fromPath($attachmentFp, $type);
        }
        if ( self::$ms_allAttachmentsAreInline )
        {
            $attachment->setDisposition("inline");
        }
        $this->m_swiftMessage->attach($attachment);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Attaches a file to a message, also assigning a custom name to the attached file.
     *
     * @param  string $attachmentFp The path to the file to be attached.
     * @param  string $filename The custom name for the file.
     * @param  string $type **OPTIONAL.** The MIME type of the file's contents.
     *
     * @return void
     */

    public function attachFileWithFilename ($attachmentFp, $filename, $type = null)
    {
        assert( 'is_cstring($attachmentFp) && is_cstring($filename) && (!isset($type) || is_cstring($type))',
            vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_swiftMessage)', vs(isset($this), get_defined_vars()) );

        $attachmentFp = CFilePath::frameworkPath($attachmentFp);

        $attachment;
        if ( !isset($type) )
        {
            $attachment = Swift_Attachment::fromPath($attachmentFp);
        }
        else
        {
            $attachment = Swift_Attachment::fromPath($attachmentFp, $type);
        }
        $attachment->setFilename($filename);
        if ( self::$ms_allAttachmentsAreInline )
        {
            $attachment->setDisposition("inline");
        }
        $this->m_swiftMessage->attach($attachment);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Attaches a data to a message.
     *
     * @param  data $data The data to be attached.
     * @param  string $filename The name by which the data is to be seen as a file by the recipient(s).
     * @param  string $type The MIME type of the data's contents.
     *
     * @return void
     */

    public function attachData ($data, $filename, $type)
    {
        assert( 'is_cstring($data) && is_cstring($filename) && is_cstring($type)',
            vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_swiftMessage)', vs(isset($this), get_defined_vars()) );

        $data = _from_oop_tp($data);

        $attachment = Swift_Attachment::newInstance($data, $filename, $type);
        if ( self::$ms_allAttachmentsAreInline )
        {
            $attachment->setDisposition("inline");
        }
        $this->m_swiftMessage->attach($attachment);
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
     * @param  string $embedFp The path to the file to be embedded.
     *
     * @return CUStringObject The embeddable CID of the file.
     */

    public function embeddableCidForFile ($embedFp)
    {
        assert( 'is_cstring($embedFp)', vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_swiftMessage)', vs(isset($this), get_defined_vars()) );

        $embedFp = CFilePath::frameworkPath($embedFp);

        $embeddedFile = Swift_EmbeddedFile::fromPath($embedFp);
        return $this->m_swiftMessage->embed($embeddedFile);
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
     * @param  data $data The data to be embedded.
     * @param  string $filename The filename to be associated with the embedded data.
     * @param  string $type The MIME type of the data's contents.
     *
     * @return CUStringObject The embeddable CID of the data.
     */

    public function embeddableCidForData ($data, $filename, $type)
    {
        assert( 'is_cstring($data) && is_cstring($filename) && is_cstring($type)',
            vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_swiftMessage)', vs(isset($this), get_defined_vars()) );

        $data = _from_oop_tp($data);

        $embeddedFile = Swift_EmbeddedFile::newInstance($data, $filename, $type);
        return $this->m_swiftMessage->embed($embeddedFile);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the declarative time when a message was sent.
     *
     * @param  CTime $time The point in time to be declared as the moment of the message's dispatching.
     *
     * @return void
     */

    public function setTime (CTime $time)
    {
        assert( 'isset($this->m_swiftMessage)', vs(isset($this), get_defined_vars()) );
        $this->m_swiftMessage->setDate($time->UTime());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the priority of a message.
     *
     * @param  enum $priority The priority of the message. Can be `PRIORITY_HIGHEST`, `PRIORITY_HIGH`,
     * `PRIORITY_NORMAL`, `PRIORITY_LOW`, or `PRIORITY_LOWEST`.
     *
     * @return void
     */

    public function setPriority ($priority)
    {
        assert( 'is_enum($priority)', vs(isset($this), get_defined_vars()) );
        assert( 'isset($this->m_swiftMessage)', vs(isset($this), get_defined_vars()) );

        $this->m_swiftMessage->setPriority($priority + 1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sends a message to the recipient(s).
     *
     * @param  reference $failedAddresses **OPTIONAL. OUTPUT.** After the method is called with this parameter
     * provided, the parameter's value, which is of type `CArrayObject`, is an array containing the email addresses of
     * the recipients who failed to receive the message.
     *
     * @return int The number of recipients who have successfully received the message.
     */

    public function send (&$failedAddresses = null)
    {
        assert( 'isset($this->m_swiftMailer) && isset($this->m_swiftMessage)', vs(isset($this), get_defined_vars()) );
        assert( '(isset($this->m_from) || isset($this->m_sender) || isset($this->m_returnAddress)) && ' .
                '(isset($this->m_to) || isset($this->m_cc) || isset($this->m_bcc))',
            vs(isset($this), get_defined_vars()) );

        $message = $this->m_swiftMessage;

        if ( isset($this->m_from) )
        {
            $message->setFrom($this->m_from);
        }
        if ( isset($this->m_to) )
        {
            $message->setTo($this->m_to);
        }
        if ( isset($this->m_cc) )
        {
            $message->setCc($this->m_cc);
        }
        if ( isset($this->m_bcc) )
        {
            $message->setBcc($this->m_bcc);
        }
        if ( isset($this->m_sender) )
        {
            $message->setSender($this->m_sender);
        }
        if ( isset($this->m_returnAddress) )
        {
            $message->setReturnPath($this->m_returnAddress);
        }
        if ( isset($this->m_replyAddress) )
        {
            $message->setReplyTo($this->m_replyAddress);
        }

        if ( isset($this->m_body) )
        {
            if ( CString::equals($this->m_bodyType, CMimeType::PLAIN_TEXT) )
            {
                $this->m_body = $this->maybeWrapText($this->m_body);
            }
            $message->setBody($this->m_body, $this->m_bodyType);
        }

        if ( isset($this->m_altBodiesAndTypes) )
        {
            $len = CArray::length($this->m_altBodiesAndTypes);
            for ($i = 0; $i < $len; $i++)
            {
                $bodyAndType = $this->m_altBodiesAndTypes[$i];
                $body = $bodyAndType[0];
                $type = $bodyAndType[1];
                if ( CString::equals($type, CMimeType::PLAIN_TEXT) )
                {
                    $body = $this->maybeWrapText($body);
                }
                $message->addPart($body, $type);
            }
        }

        $paFailedAddresses;
        $res = $this->m_swiftMailer->send($message, $paFailedAddresses);
        if ( is_cmap($paFailedAddresses) )
        {
            $failedAddresses = oop_a(CArray::fromPArray($paFailedAddresses));
        }
        $res = ( is_int($res) ) ? $res : 0;
        return $res;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function maybeWrapText ($text)
    {
        if ( !isset($this->m_bodyWordWrappingIsDisabled) || !$this->m_bodyWordWrappingIsDisabled )
        {
            // Wrap the text.
            $width;
            if ( !isset($this->m_bodyWordWrappingWidth) )
            {
                $width = self::$ms_defaultWordWrappingWidth;
            }
            else
            {
                $width = $this->m_bodyWordWrappingWidth;
            }
            $text = CUString::wordWrap($text, $width);
        }
        return $text;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_swiftMailer;
    protected $m_swiftMessage;
    protected $m_from;
    protected $m_to;
    protected $m_cc;
    protected $m_bcc;
    protected $m_sender;
    protected $m_returnAddress;
    protected $m_replyAddress;
    protected $m_bodyWordWrappingWidth;
    protected $m_bodyWordWrappingIsDisabled;
    protected $m_body;
    protected $m_bodyType;
    protected $m_altBodiesAndTypes;

    protected static $ms_defaultSubject = "";
    protected static $ms_defaultWordWrappingWidth = 80;

    // "Inline" attachments are expected to be displayed inline and, if possible, at the message's end.
    protected static $ms_allAttachmentsAreInline = true;
}
