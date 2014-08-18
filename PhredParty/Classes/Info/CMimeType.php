<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that enumerates some of the MIME types.
 *
 * The constants of this class cover the majority of the
 * [IANA media types](http://www.iana.org/assignments/media-types), except for the vendor-specific ones. The name of a
 * constant for which an identical type exists in another IANA directory is prefixed with the name of the directory to
 * which the constant belongs.
 */

class CMimeType extends CRootClass
{
    // Text.
    /**
     * `string` "text/1d-interleaved-parityfec"
     *
     * @var string
     */
    const TEXT_ONE_D_INTERLEAVED_PARITYFEC = "text/1d-interleaved-parityfec";
    /**
     * `string` "text/calendar"
     *
     * @var string
     */
    const CALENDAR = "text/calendar";
    /**
     * `string` "text/css"
     *
     * @var string
     */
    const CSS = "text/css";
    /**
     * `string` "text/csv"
     *
     * @var string
     */
    const CSV = "text/csv";
    /**
     * `string` "text/dns"
     *
     * @var string
     */
    const TEXT_DNS = "text/dns";
    /**
     * `string` "text/encaprtp"
     *
     * @var string
     */
    const TEXT_ENCAPRTP = "text/encaprtp";
    /**
     * `string` "text/enriched"
     *
     * @var string
     */
    const ENRICHED = "text/enriched";
    /**
     * `string` "text/example"
     *
     * @var string
     */
    const TEXT_EXAMPLE = "text/example";
    /**
     * `string` "text/fwdred"
     *
     * @var string
     */
    const TEXT_FWDRED = "text/fwdred";
    /**
     * `string` "text/grammar-ref-list"
     *
     * @var string
     */
    const GRAMMAR_REF_LIST = "text/grammar-ref-list";
    /**
     * `string` "text/html"
     *
     * @var string
     */
    const HTML = "text/html";
    /**
     * `string` "text/html; charset=UTF-8"
     *
     * @var string
     */
    const HTML_UTF8 = "text/html; charset=UTF-8";
    /**
     * `string` "text/jcr-cnd"
     *
     * @var string
     */
    const JCR_CND = "text/jcr-cnd";
    /**
     * `string` "text/mizar"
     *
     * @var string
     */
    const MIZAR = "text/mizar";
    /**
     * `string` "text/n3"
     *
     * @var string
     */
    const N3 = "text/n3";
    /**
     * `string` "text/parityfec"
     *
     * @var string
     */
    const TEXT_PARITYFEC = "text/parityfec";
    /**
     * `string` "text/plain"
     *
     * @var string
     */
    const PLAIN_TEXT = "text/plain";
    /**
     * `string` "text/provenance-notation"
     *
     * @var string
     */
    const PROVENANCE_NOTATION = "text/provenance-notation";
    /**
     * `string` "text/raptorfec"
     *
     * @var string
     */
    const TEXT_RAPTORFEC = "text/raptorfec";
    /**
     * `string` "text/RED"
     *
     * @var string
     */
    const TEXT_RED = "text/RED";
    /**
     * `string` "text/rfc822-headers"
     *
     * @var string
     */
    const RFC822_HEADERS = "text/rfc822-headers";
    /**
     * `string` "text/richtext"
     *
     * @var string
     */
    const RICHTEXT = "text/richtext";
    /**
     * `string` "text/rtf"
     *
     * @var string
     */
    const TEXT_RTF = "text/rtf";
    /**
     * `string` "text/rtp-enc-aescm128"
     *
     * @var string
     */
    const TEXT_RTP_ENC_AESCM128 = "text/rtp-enc-aescm128";
    /**
     * `string` "text/rtploopback"
     *
     * @var string
     */
    const TEXT_RTPLOOPBACK = "text/rtploopback";
    /**
     * `string` "text/rtx"
     *
     * @var string
     */
    const TEXT_RTX = "text/rtx";
    /**
     * `string` "text/sgml"
     *
     * @var string
     */
    const TEXT_SGML = "text/sgml";
    /**
     * `string` "text/t140"
     *
     * @var string
     */
    const T140 = "text/t140";
    /**
     * `string` "text/tab-separated-values"
     *
     * @var string
     */
    const TAB_SEPARATED_VALUES = "text/tab-separated-values";
    /**
     * `string` "text/troff"
     *
     * @var string
     */
    const TROFF = "text/troff";
    /**
     * `string` "text/turtle"
     *
     * @var string
     */
    const TURTLE = "text/turtle";
    /**
     * `string` "text/ulpfec"
     *
     * @var string
     */
    const TEXT_ULPFEC = "text/ulpfec";
    /**
     * `string` "text/uri-list"
     *
     * @var string
     */
    const URI_LIST = "text/uri-list";
    /**
     * `string` "text/vcard"
     *
     * @var string
     */
    const VCARD = "text/vcard";

    // Image.
    /**
     * `string` "image/cgm"
     *
     * @var string
     */
    const CGM = "image/cgm";
    /**
     * `string` "image/example"
     *
     * @var string
     */
    const IMAGE_EXAMPLE = "image/example";
    /**
     * `string` "image/fits"
     *
     * @var string
     */
    const IMAGE_FITS = "image/fits";
    /**
     * `string` "image/g3fax"
     *
     * @var string
     */
    const G3FAX = "image/g3fax";
    /**
     * `string` "image/gif"
     *
     * @var string
     */
    const GIF = "image/gif";
    /**
     * `string` "image/ief"
     *
     * @var string
     */
    const IEF = "image/ief";
    /**
     * `string` "image/jp2"
     *
     * @var string
     */
    const JP2 = "image/jp2";
    /**
     * `string` "image/jpeg"
     *
     * @var string
     */
    const JPEG = "image/jpeg";
    /**
     * `string` "image/jpm"
     *
     * @var string
     */
    const JPM = "image/jpm";
    /**
     * `string` "image/jpx"
     *
     * @var string
     */
    const JPX = "image/jpx";
    /**
     * `string` "image/ktx"
     *
     * @var string
     */
    const KTX = "image/ktx";
    /**
     * `string` "image/naplps"
     *
     * @var string
     */
    const NAPLPS = "image/naplps";
    /**
     * `string` "image/png"
     *
     * @var string
     */
    const PNG = "image/png";
    /**
     * `string` "image/pwg-raster"
     *
     * @var string
     */
    const PWG_RASTER = "image/pwg-raster";
    /**
     * `string` "image/svg+xml"
     *
     * @var string
     */
    const SVG_XML = "image/svg+xml";
    /**
     * `string` "image/t38"
     *
     * @var string
     */
    const IMAGE_T38 = "image/t38";
    /**
     * `string` "image/tiff"
     *
     * @var string
     */
    const TIFF = "image/tiff";
    /**
     * `string` "image/tiff-fx"
     *
     * @var string
     */
    const TIFF_FX = "image/tiff-fx";

    // Application.
    /**
     * `string` "application/1d-interleaved-parityfec"
     *
     * @var string
     */
    const APPLICATION_ONE_D_INTERLEAVED_PARITYFEC = "application/1d-interleaved-parityfec";
    /**
     * `string` "application/3gpp-ims+xml"
     *
     * @var string
     */
    const THREE_GPP_IMS_XML = "application/3gpp-ims+xml";
    /**
     * `string` "application/activemessage"
     *
     * @var string
     */
    const ACTIVEMESSAGE = "application/activemessage";
    /**
     * `string` "application/andrew-inset"
     *
     * @var string
     */
    const ANDREW_INSET = "application/andrew-inset";
    /**
     * `string` "application/applefile"
     *
     * @var string
     */
    const APPLEFILE = "application/applefile";
    /**
     * `string` "application/atom+xml"
     *
     * @var string
     */
    const ATOM_XML = "application/atom+xml";
    /**
     * `string` "application/atomdeleted+xml"
     *
     * @var string
     */
    const ATOMDELETED_XML = "application/atomdeleted+xml";
    /**
     * `string` "application/atomicmail"
     *
     * @var string
     */
    const ATOMICMAIL = "application/atomicmail";
    /**
     * `string` "application/atomcat+xml"
     *
     * @var string
     */
    const ATOMCAT_XML = "application/atomcat+xml";
    /**
     * `string` "application/atomsvc+xml"
     *
     * @var string
     */
    const ATOMSVC_XML = "application/atomsvc+xml";
    /**
     * `string` "application/auth-policy+xml"
     *
     * @var string
     */
    const AUTH_POLICY_XML = "application/auth-policy+xml";
    /**
     * `string` "application/batch-SMTP"
     *
     * @var string
     */
    const BATCH_SMTP = "application/batch-SMTP";
    /**
     * `string` "application/beep+xml"
     *
     * @var string
     */
    const BEEP_XML = "application/beep+xml";
    /**
     * `string` "application/calendar+xml"
     *
     * @var string
     */
    const CALENDAR_XML = "application/calendar+xml";
    /**
     * `string` "application/call-completion"
     *
     * @var string
     */
    const CALL_COMPLETION = "application/call-completion";
    /**
     * `string` "application/cals-1840"
     *
     * @var string
     */
    const CALS_1840 = "application/cals-1840";
    /**
     * `string` "application/cbor"
     *
     * @var string
     */
    const CBOR = "application/cbor";
    /**
     * `string` "application/ccmp+xml"
     *
     * @var string
     */
    const CCMP_XML = "application/ccmp+xml";
    /**
     * `string` "application/ccxml+xml"
     *
     * @var string
     */
    const CCXML_XML = "application/ccxml+xml";
    /**
     * `string` "application/cdmi-capability"
     *
     * @var string
     */
    const CDMI_CAPABILITY = "application/cdmi-capability";
    /**
     * `string` "application/cdmi-container"
     *
     * @var string
     */
    const CDMI_CONTAINER = "application/cdmi-container";
    /**
     * `string` "application/cdmi-domain"
     *
     * @var string
     */
    const CDMI_DOMAIN = "application/cdmi-domain";
    /**
     * `string` "application/cdmi-object"
     *
     * @var string
     */
    const CDMI_OBJECT = "application/cdmi-object";
    /**
     * `string` "application/cdmi-queue"
     *
     * @var string
     */
    const CDMI_QUEUE = "application/cdmi-queue";
    /**
     * `string` "application/cea-2018+xml"
     *
     * @var string
     */
    const CEA_2018_XML = "application/cea-2018+xml";
    /**
     * `string` "application/cellml+xml"
     *
     * @var string
     */
    const CELLML_XML = "application/cellml+xml";
    /**
     * `string` "application/cfw"
     *
     * @var string
     */
    const CFW = "application/cfw";
    /**
     * `string` "application/cnrp+xml"
     *
     * @var string
     */
    const CNRP_XML = "application/cnrp+xml";
    /**
     * `string` "application/commonground"
     *
     * @var string
     */
    const COMMONGROUND = "application/commonground";
    /**
     * `string` "application/conference-info+xml"
     *
     * @var string
     */
    const CONFERENCE_INFO_XML = "application/conference-info+xml";
    /**
     * `string` "application/cpl+xml"
     *
     * @var string
     */
    const CPL_XML = "application/cpl+xml";
    /**
     * `string` "application/csrattrs"
     *
     * @var string
     */
    const CSRATTRS = "application/csrattrs";
    /**
     * `string` "application/csta+xml"
     *
     * @var string
     */
    const CSTA_XML = "application/csta+xml";
    /**
     * `string` "application/CSTAdata+xml"
     *
     * @var string
     */
    const CSTADATA_XML = "application/CSTAdata+xml";
    /**
     * `string` "application/cybercash"
     *
     * @var string
     */
    const CYBERCASH = "application/cybercash";
    /**
     * `string` "application/dash+xml"
     *
     * @var string
     */
    const DASH_XML = "application/dash+xml";
    /**
     * `string` "application/dashdelta"
     *
     * @var string
     */
    const DASHDELTA = "application/dashdelta";
    /**
     * `string` "application/davmount+xml"
     *
     * @var string
     */
    const DAVMOUNT_XML = "application/davmount+xml";
    /**
     * `string` "application/dca-rft"
     *
     * @var string
     */
    const DCA_RFT = "application/dca-rft";
    /**
     * `string` "application/dec-dx"
     *
     * @var string
     */
    const DEC_DX = "application/dec-dx";
    /**
     * `string` "application/dialog-info+xml"
     *
     * @var string
     */
    const DIALOG_INFO_XML = "application/dialog-info+xml";
    /**
     * `string` "application/dicom"
     *
     * @var string
     */
    const DICOM = "application/dicom";
    /**
     * `string` "application/dns"
     *
     * @var string
     */
    const APPLICATION_DNS = "application/dns";
    /**
     * `string` "application/dskpp+xml"
     *
     * @var string
     */
    const DSKPP_XML = "application/dskpp+xml";
    /**
     * `string` "application/dssc+der"
     *
     * @var string
     */
    const DSSC_DER = "application/dssc+der";
    /**
     * `string` "application/dssc+xml"
     *
     * @var string
     */
    const DSSC_XML = "application/dssc+xml";
    /**
     * `string` "application/dvcs"
     *
     * @var string
     */
    const DVCS = "application/dvcs";
    /**
     * `string` "application/ecmascript"
     *
     * @var string
     */
    const ECMASCRIPT = "application/ecmascript";
    /**
     * `string` "application/EDI-Consent"
     *
     * @var string
     */
    const EDI_CONSENT = "application/EDI-Consent";
    /**
     * `string` "application/EDIFACT"
     *
     * @var string
     */
    const EDIFACT = "application/EDIFACT";
    /**
     * `string` "application/EDI-X12"
     *
     * @var string
     */
    const EDI_X12 = "application/EDI-X12";
    /**
     * `string` "application/emma+xml"
     *
     * @var string
     */
    const EMMA_XML = "application/emma+xml";
    /**
     * `string` "application/encaprtp"
     *
     * @var string
     */
    const APPLICATION_ENCAPRTP = "application/encaprtp";
    /**
     * `string` "application/epp+xml"
     *
     * @var string
     */
    const EPP_XML = "application/epp+xml";
    /**
     * `string` "application/eshop"
     *
     * @var string
     */
    const ESHOP = "application/eshop";
    /**
     * `string` "application/example"
     *
     * @var string
     */
    const APPLICATION_EXAMPLE = "application/example";
    /**
     * `string` "application/exi"
     *
     * @var string
     */
    const EXI = "application/exi";
    /**
     * `string` "application/fastinfoset"
     *
     * @var string
     */
    const FASTINFOSET = "application/fastinfoset";
    /**
     * `string` "application/fastsoap"
     *
     * @var string
     */
    const FASTSOAP = "application/fastsoap";
    /**
     * `string` "application/fdt+xml"
     *
     * @var string
     */
    const FDT_XML = "application/fdt+xml";
    /**
     * `string` "application/fits"
     *
     * @var string
     */
    const APPLICATION_FITS = "application/fits";
    /**
     * `string` "application/font-sfnt"
     *
     * @var string
     */
    const FONT_SFNT = "application/font-sfnt";
    /**
     * `string` "application/font-tdpfr"
     *
     * @var string
     */
    const FONT_TDPFR = "application/font-tdpfr";
    /**
     * `string` "application/font-woff"
     *
     * @var string
     */
    const FONT_WOFF = "application/font-woff";
    /**
     * `string` "application/framework-attributes+xml"
     *
     * @var string
     */
    const FRAMEWORK_ATTRIBUTES_XML = "application/framework-attributes+xml";
    /**
     * `string` "application/gzip"
     *
     * @var string
     */
    const GZIP = "application/gzip";
    /**
     * `string` "application/H224"
     *
     * @var string
     */
    const H224 = "application/H224";
    /**
     * `string` "application/held+xml"
     *
     * @var string
     */
    const HELD_XML = "application/held+xml";
    /**
     * `string` "application/http"
     *
     * @var string
     */
    const APPLICATION_HTTP = "application/http";
    /**
     * `string` "application/hyperstudio"
     *
     * @var string
     */
    const HYPERSTUDIO = "application/hyperstudio";
    /**
     * `string` "application/ibe-key-request+xml"
     *
     * @var string
     */
    const IBE_KEY_REQUEST_XML = "application/ibe-key-request+xml";
    /**
     * `string` "application/ibe-pkg-reply+xml"
     *
     * @var string
     */
    const IBE_PKG_REPLY_XML = "application/ibe-pkg-reply+xml";
    /**
     * `string` "application/ibe-pp-data"
     *
     * @var string
     */
    const IBE_PP_DATA = "application/ibe-pp-data";
    /**
     * `string` "application/iges"
     *
     * @var string
     */
    const APPLICATION_IGES = "application/iges";
    /**
     * `string` "application/im-iscomposing+xml"
     *
     * @var string
     */
    const IM_ISCOMPOSING_XML = "application/im-iscomposing+xml";
    /**
     * `string` "application/index"
     *
     * @var string
     */
    const INDEX = "application/index";
    /**
     * `string` "application/index.cmd"
     *
     * @var string
     */
    const INDEX_CMD = "application/index.cmd";
    /**
     * `string` "application/index.obj"
     *
     * @var string
     */
    const INDEX_OBJ = "application/index.obj";
    /**
     * `string` "application/index.response"
     *
     * @var string
     */
    const INDEX_RESPONSE = "application/index.response";
    /**
     * `string` "application/index.vnd"
     *
     * @var string
     */
    const INDEX_VND = "application/index.vnd";
    /**
     * `string` "application/inkml+xml"
     *
     * @var string
     */
    const INKML_XML = "application/inkml+xml";
    /**
     * `string` "application/iotp"
     *
     * @var string
     */
    const IOTP = "application/iotp";
    /**
     * `string` "application/ipfix"
     *
     * @var string
     */
    const IPFIX = "application/ipfix";
    /**
     * `string` "application/ipp"
     *
     * @var string
     */
    const IPP = "application/ipp";
    /**
     * `string` "application/isup"
     *
     * @var string
     */
    const ISUP = "application/isup";
    /**
     * `string` "application/its+xml"
     *
     * @var string
     */
    const ITS_XML = "application/its+xml";
    /**
     * `string` "application/javascript"
     *
     * @var string
     */
    const JAVASCRIPT = "application/javascript";
    /**
     * `string` "application/jrd+json"
     *
     * @var string
     */
    const JRD_JSON = "application/jrd+json";
    /**
     * `string` "application/json"
     *
     * @var string
     */
    const JSON = "application/json";
    /**
     * `string` "application/json-patch+json"
     *
     * @var string
     */
    const JSON_PATCH_JSON = "application/json-patch+json";
    /**
     * `string` "application/kpml-request+xml"
     *
     * @var string
     */
    const KPML_REQUEST_XML = "application/kpml-request+xml";
    /**
     * `string` "application/kpml-response+xml"
     *
     * @var string
     */
    const KPML_RESPONSE_XML = "application/kpml-response+xml";
    /**
     * `string` "application/ld+json"
     *
     * @var string
     */
    const LD_JSON = "application/ld+json";
    /**
     * `string` "application/link-format"
     *
     * @var string
     */
    const LINK_FORMAT = "application/link-format";
    /**
     * `string` "application/lost+xml"
     *
     * @var string
     */
    const LOST_XML = "application/lost+xml";
    /**
     * `string` "application/lostsync+xml"
     *
     * @var string
     */
    const LOSTSYNC_XML = "application/lostsync+xml";
    /**
     * `string` "application/mac-binhex40"
     *
     * @var string
     */
    const MAC_BINHEX40 = "application/mac-binhex40";
    /**
     * `string` "application/macwriteii"
     *
     * @var string
     */
    const MACWRITEII = "application/macwriteii";
    /**
     * `string` "application/mads+xml"
     *
     * @var string
     */
    const MADS_XML = "application/mads+xml";
    /**
     * `string` "application/marc"
     *
     * @var string
     */
    const MARC = "application/marc";
    /**
     * `string` "application/marcxml+xml"
     *
     * @var string
     */
    const MARCXML_XML = "application/marcxml+xml";
    /**
     * `string` "application/mathematica"
     *
     * @var string
     */
    const MATHEMATICA = "application/mathematica";
    /**
     * `string` "application/mathml-content+xml"
     *
     * @var string
     */
    const MATHML_CONTENT_XML = "application/mathml-content+xml";
    /**
     * `string` "application/mathml-presentation+xml"
     *
     * @var string
     */
    const MATHML_PRESENTATION_XML = "application/mathml-presentation+xml";
    /**
     * `string` "application/mathml+xml"
     *
     * @var string
     */
    const MATHML_XML = "application/mathml+xml";
    /**
     * `string` "application/mbms-associated-procedure-description+xml"
     *
     * @var string
     */
    const MBMS_ASSOCIATED_PROCEDURE_DESCRIPTION_XML = "application/mbms-associated-procedure-description+xml";
    /**
     * `string` "application/mbms-deregister+xml"
     *
     * @var string
     */
    const MBMS_DEREGISTER_XML = "application/mbms-deregister+xml";
    /**
     * `string` "application/mbms-envelope+xml"
     *
     * @var string
     */
    const MBMS_ENVELOPE_XML = "application/mbms-envelope+xml";
    /**
     * `string` "application/mbms-msk-response+xml"
     *
     * @var string
     */
    const MBMS_MSK_RESPONSE_XML = "application/mbms-msk-response+xml";
    /**
     * `string` "application/mbms-msk+xml"
     *
     * @var string
     */
    const MBMS_MSK_XML = "application/mbms-msk+xml";
    /**
     * `string` "application/mbms-protection-description+xml"
     *
     * @var string
     */
    const MBMS_PROTECTION_DESCRIPTION_XML = "application/mbms-protection-description+xml";
    /**
     * `string` "application/mbms-reception-report+xml"
     *
     * @var string
     */
    const MBMS_RECEPTION_REPORT_XML = "application/mbms-reception-report+xml";
    /**
     * `string` "application/mbms-register-response+xml"
     *
     * @var string
     */
    const MBMS_REGISTER_RESPONSE_XML = "application/mbms-register-response+xml";
    /**
     * `string` "application/mbms-register+xml"
     *
     * @var string
     */
    const MBMS_REGISTER_XML = "application/mbms-register+xml";
    /**
     * `string` "application/mbms-schedule+xml"
     *
     * @var string
     */
    const MBMS_SCHEDULE_XML = "application/mbms-schedule+xml";
    /**
     * `string` "application/mbms-user-service-description+xml"
     *
     * @var string
     */
    const MBMS_USER_SERVICE_DESCRIPTION_XML = "application/mbms-user-service-description+xml";
    /**
     * `string` "application/mbox"
     *
     * @var string
     */
    const MBOX = "application/mbox";
    /**
     * `string` "application/media_control+xml"
     *
     * @var string
     */
    const MEDIA_CONTROL_XML = "application/media_control+xml";
    /**
     * `string` "application/media-policy-dataset+xml"
     *
     * @var string
     */
    const MEDIA_POLICY_DATASET_XML = "application/media-policy-dataset+xml";
    /**
     * `string` "application/mediaservercontrol+xml"
     *
     * @var string
     */
    const MEDIASERVERCONTROL_XML = "application/mediaservercontrol+xml";
    /**
     * `string` "application/metalink4+xml"
     *
     * @var string
     */
    const METALINK4_XML = "application/metalink4+xml";
    /**
     * `string` "application/mets+xml"
     *
     * @var string
     */
    const METS_XML = "application/mets+xml";
    /**
     * `string` "application/mikey"
     *
     * @var string
     */
    const MIKEY = "application/mikey";
    /**
     * `string` "application/mods+xml"
     *
     * @var string
     */
    const MODS_XML = "application/mods+xml";
    /**
     * `string` "application/moss-keys"
     *
     * @var string
     */
    const MOSS_KEYS = "application/moss-keys";
    /**
     * `string` "application/moss-signature"
     *
     * @var string
     */
    const MOSS_SIGNATURE = "application/moss-signature";
    /**
     * `string` "application/mosskey-data"
     *
     * @var string
     */
    const MOSSKEY_DATA = "application/mosskey-data";
    /**
     * `string` "application/mosskey-request"
     *
     * @var string
     */
    const MOSSKEY_REQUEST = "application/mosskey-request";
    /**
     * `string` "application/mp21"
     *
     * @var string
     */
    const MP21 = "application/mp21";
    /**
     * `string` "application/mp4"
     *
     * @var string
     */
    const APPLICATION_MP4 = "application/mp4";
    /**
     * `string` "application/mpeg4-generic"
     *
     * @var string
     */
    const APPLICATION_MPEG4_GENERIC = "application/mpeg4-generic";
    /**
     * `string` "application/mpeg4-iod"
     *
     * @var string
     */
    const MPEG4_IOD = "application/mpeg4-iod";
    /**
     * `string` "application/mpeg4-iod-xmt"
     *
     * @var string
     */
    const MPEG4_IOD_XMT = "application/mpeg4-iod-xmt";
    /**
     * `string` "application/mrb-consumer+xml"
     *
     * @var string
     */
    const MRB_CONSUMER_XML = "application/mrb-consumer+xml";
    /**
     * `string` "application/mrb-publish+xml"
     *
     * @var string
     */
    const MRB_PUBLISH_XML = "application/mrb-publish+xml";
    /**
     * `string` "application/msc-ivr+xml"
     *
     * @var string
     */
    const MSC_IVR_XML = "application/msc-ivr+xml";
    /**
     * `string` "application/msc-mixer+xml"
     *
     * @var string
     */
    const MSC_MIXER_XML = "application/msc-mixer+xml";
    /**
     * `string` "application/msword"
     *
     * @var string
     */
    const MSWORD = "application/msword";
    /**
     * `string` "application/mxf"
     *
     * @var string
     */
    const MXF = "application/mxf";
    /**
     * `string` "application/nasdata"
     *
     * @var string
     */
    const NASDATA = "application/nasdata";
    /**
     * `string` "application/news-checkgroups"
     *
     * @var string
     */
    const NEWS_CHECKGROUPS = "application/news-checkgroups";
    /**
     * `string` "application/news-groupinfo"
     *
     * @var string
     */
    const NEWS_GROUPINFO = "application/news-groupinfo";
    /**
     * `string` "application/news-transmission"
     *
     * @var string
     */
    const NEWS_TRANSMISSION = "application/news-transmission";
    /**
     * `string` "application/nlsml+xml"
     *
     * @var string
     */
    const NLSML_XML = "application/nlsml+xml";
    /**
     * `string` "application/nss"
     *
     * @var string
     */
    const NSS = "application/nss";
    /**
     * `string` "application/ocsp-request"
     *
     * @var string
     */
    const OCSP_REQUEST = "application/ocsp-request";
    /**
     * `string` "application/ocsp-response"
     *
     * @var string
     */
    const OCSP_RESPONSE = "application/ocsp-response";
    /**
     * `string` "application/octet-stream"
     *
     * @var string
     */
    const OCTET_STREAM = "application/octet-stream";
    /**
     * `string` "application/oda"
     *
     * @var string
     */
    const ODA = "application/oda";
    /**
     * `string` "application/oebps-package+xml"
     *
     * @var string
     */
    const OEBPS_PACKAGE_XML = "application/oebps-package+xml";
    /**
     * `string` "application/ogg"
     *
     * @var string
     */
    const APPLICATION_OGG = "application/ogg";
    /**
     * `string` "application/oxps"
     *
     * @var string
     */
    const OXPS = "application/oxps";
    /**
     * `string` "application/p2p-overlay+xml"
     *
     * @var string
     */
    const P2P_OVERLAY_XML = "application/p2p-overlay+xml";
    /**
     * `string` "application/parityfec"
     *
     * @var string
     */
    const APPLICATION_PARITYFEC = "application/parityfec";
    /**
     * `string` "application/patch-ops-error+xml"
     *
     * @var string
     */
    const PATCH_OPS_ERROR_XML = "application/patch-ops-error+xml";
    /**
     * `string` "application/pdf"
     *
     * @var string
     */
    const PDF = "application/pdf";
    /**
     * `string` "application/pgp-encrypted"
     *
     * @var string
     */
    const PGP_ENCRYPTED = "application/pgp-encrypted";
    /**
     * `string` "application/pgp-keys"
     *
     * @var string
     */
    const PGP_KEYS = "application/pgp-keys";
    /**
     * `string` "application/pgp-signature"
     *
     * @var string
     */
    const PGP_SIGNATURE = "application/pgp-signature";
    /**
     * `string` "application/pidf+xml"
     *
     * @var string
     */
    const PIDF_XML = "application/pidf+xml";
    /**
     * `string` "application/pidf-diff+xml"
     *
     * @var string
     */
    const PIDF_DIFF_XML = "application/pidf-diff+xml";
    /**
     * `string` "application/pkcs10"
     *
     * @var string
     */
    const PKCS10 = "application/pkcs10";
    /**
     * `string` "application/pkcs7-mime"
     *
     * @var string
     */
    const PKCS7_MIME = "application/pkcs7-mime";
    /**
     * `string` "application/pkcs7-signature"
     *
     * @var string
     */
    const PKCS7_SIGNATURE = "application/pkcs7-signature";
    /**
     * `string` "application/pkcs8"
     *
     * @var string
     */
    const PKCS8 = "application/pkcs8";
    /**
     * `string` "application/pkix-attr-cert"
     *
     * @var string
     */
    const PKIX_ATTR_CERT = "application/pkix-attr-cert";
    /**
     * `string` "application/pkix-cert"
     *
     * @var string
     */
    const PKIX_CERT = "application/pkix-cert";
    /**
     * `string` "application/pkixcmp"
     *
     * @var string
     */
    const PKIXCMP = "application/pkixcmp";
    /**
     * `string` "application/pkix-crl"
     *
     * @var string
     */
    const PKIX_CRL = "application/pkix-crl";
    /**
     * `string` "application/pkix-pkipath"
     *
     * @var string
     */
    const PKIX_PKIPATH = "application/pkix-pkipath";
    /**
     * `string` "application/pls+xml"
     *
     * @var string
     */
    const PLS_XML = "application/pls+xml";
    /**
     * `string` "application/poc-settings+xml"
     *
     * @var string
     */
    const POC_SETTINGS_XML = "application/poc-settings+xml";
    /**
     * `string` "application/postscript"
     *
     * @var string
     */
    const POSTSCRIPT = "application/postscript";
    /**
     * `string` "application/provenance+xml"
     *
     * @var string
     */
    const PROVENANCE_XML = "application/provenance+xml";
    /**
     * `string` "application/pskc+xml"
     *
     * @var string
     */
    const PSKC_XML = "application/pskc+xml";
    /**
     * `string` "application/rdf+xml"
     *
     * @var string
     */
    const RDF_XML = "application/rdf+xml";
    /**
     * `string` "application/qsig"
     *
     * @var string
     */
    const QSIG = "application/qsig";
    /**
     * `string` "application/raptorfec"
     *
     * @var string
     */
    const APPLICATION_RAPTORFEC = "application/raptorfec";
    /**
     * `string` "application/reginfo+xml"
     *
     * @var string
     */
    const REGINFO_XML = "application/reginfo+xml";
    /**
     * `string` "application/relax-ng-compact-syntax"
     *
     * @var string
     */
    const RELAX_NG_COMPACT_SYNTAX = "application/relax-ng-compact-syntax";
    /**
     * `string` "application/remote-printing"
     *
     * @var string
     */
    const REMOTE_PRINTING = "application/remote-printing";
    /**
     * `string` "application/resource-lists-diff+xml"
     *
     * @var string
     */
    const RESOURCE_LISTS_DIFF_XML = "application/resource-lists-diff+xml";
    /**
     * `string` "application/resource-lists+xml"
     *
     * @var string
     */
    const RESOURCE_LISTS_XML = "application/resource-lists+xml";
    /**
     * `string` "application/riscos"
     *
     * @var string
     */
    const RISCOS = "application/riscos";
    /**
     * `string` "application/rlmi+xml"
     *
     * @var string
     */
    const RLMI_XML = "application/rlmi+xml";
    /**
     * `string` "application/rls-services+xml"
     *
     * @var string
     */
    const RLS_SERVICES_XML = "application/rls-services+xml";
    /**
     * `string` "application/rpki-ghostbusters"
     *
     * @var string
     */
    const RPKI_GHOSTBUSTERS = "application/rpki-ghostbusters";
    /**
     * `string` "application/rpki-manifest"
     *
     * @var string
     */
    const RPKI_MANIFEST = "application/rpki-manifest";
    /**
     * `string` "application/rpki-roa"
     *
     * @var string
     */
    const RPKI_ROA = "application/rpki-roa";
    /**
     * `string` "application/rpki-updown"
     *
     * @var string
     */
    const RPKI_UPDOWN = "application/rpki-updown";
    /**
     * `string` "application/rtf"
     *
     * @var string
     */
    const APPLICATION_RTF = "application/rtf";
    /**
     * `string` "application/rtploopback"
     *
     * @var string
     */
    const APPLICATION_RTPLOOPBACK = "application/rtploopback";
    /**
     * `string` "application/rtx"
     *
     * @var string
     */
    const APPLICATION_RTX = "application/rtx";
    /**
     * `string` "application/samlassertion+xml"
     *
     * @var string
     */
    const SAMLASSERTION_XML = "application/samlassertion+xml";
    /**
     * `string` "application/samlmetadata+xml"
     *
     * @var string
     */
    const SAMLMETADATA_XML = "application/samlmetadata+xml";
    /**
     * `string` "application/sbml+xml"
     *
     * @var string
     */
    const SBML_XML = "application/sbml+xml";
    /**
     * `string` "application/scvp-cv-request"
     *
     * @var string
     */
    const SCVP_CV_REQUEST = "application/scvp-cv-request";
    /**
     * `string` "application/scvp-cv-response"
     *
     * @var string
     */
    const SCVP_CV_RESPONSE = "application/scvp-cv-response";
    /**
     * `string` "application/scvp-vp-request"
     *
     * @var string
     */
    const SCVP_VP_REQUEST = "application/scvp-vp-request";
    /**
     * `string` "application/scvp-vp-response"
     *
     * @var string
     */
    const SCVP_VP_RESPONSE = "application/scvp-vp-response";
    /**
     * `string` "application/sdp"
     *
     * @var string
     */
    const SDP = "application/sdp";
    /**
     * `string` "application/sep-exi"
     *
     * @var string
     */
    const SEP_EXI = "application/sep-exi";
    /**
     * `string` "application/sep+xml"
     *
     * @var string
     */
    const SEP_XML = "application/sep+xml";
    /**
     * `string` "application/session-info"
     *
     * @var string
     */
    const SESSION_INFO = "application/session-info";
    /**
     * `string` "application/set-payment"
     *
     * @var string
     */
    const SET_PAYMENT = "application/set-payment";
    /**
     * `string` "application/set-payment-initiation"
     *
     * @var string
     */
    const SET_PAYMENT_INITIATION = "application/set-payment-initiation";
    /**
     * `string` "application/set-registration"
     *
     * @var string
     */
    const SET_REGISTRATION = "application/set-registration";
    /**
     * `string` "application/set-registration-initiation"
     *
     * @var string
     */
    const SET_REGISTRATION_INITIATION = "application/set-registration-initiation";
    /**
     * `string` "application/sgml"
     *
     * @var string
     */
    const APPLICATION_SGML = "application/sgml";
    /**
     * `string` "application/sgml-open-catalog"
     *
     * @var string
     */
    const SGML_OPEN_CATALOG = "application/sgml-open-catalog";
    /**
     * `string` "application/shf+xml"
     *
     * @var string
     */
    const SHF_XML = "application/shf+xml";
    /**
     * `string` "application/sieve"
     *
     * @var string
     */
    const SIEVE = "application/sieve";
    /**
     * `string` "application/simple-filter+xml"
     *
     * @var string
     */
    const SIMPLE_FILTER_XML = "application/simple-filter+xml";
    /**
     * `string` "application/simple-message-summary"
     *
     * @var string
     */
    const SIMPLE_MESSAGE_SUMMARY = "application/simple-message-summary";
    /**
     * `string` "application/simpleSymbolContainer"
     *
     * @var string
     */
    const SIMPLESYMBOLCONTAINER = "application/simpleSymbolContainer";
    /**
     * `string` "application/slate"
     *
     * @var string
     */
    const SLATE = "application/slate";
    /**
     * `string` "application/smil+xml"
     *
     * @var string
     */
    const SMIL_XML = "application/smil+xml";
    /**
     * `string` "application/smpte336m"
     *
     * @var string
     */
    const SMPTE336M = "application/smpte336m";
    /**
     * `string` "application/soap+fastinfoset"
     *
     * @var string
     */
    const SOAP_FASTINFOSET = "application/soap+fastinfoset";
    /**
     * `string` "application/soap+xml"
     *
     * @var string
     */
    const SOAP_XML = "application/soap+xml";
    /**
     * `string` "application/sparql-query"
     *
     * @var string
     */
    const SPARQL_QUERY = "application/sparql-query";
    /**
     * `string` "application/sparql-results+xml"
     *
     * @var string
     */
    const SPARQL_RESULTS_XML = "application/sparql-results+xml";
    /**
     * `string` "application/spirits-event+xml"
     *
     * @var string
     */
    const SPIRITS_EVENT_XML = "application/spirits-event+xml";
    /**
     * `string` "application/sql"
     *
     * @var string
     */
    const SQL = "application/sql";
    /**
     * `string` "application/srgs"
     *
     * @var string
     */
    const SRGS = "application/srgs";
    /**
     * `string` "application/srgs+xml"
     *
     * @var string
     */
    const SRGS_XML = "application/srgs+xml";
    /**
     * `string` "application/sru+xml"
     *
     * @var string
     */
    const SRU_XML = "application/sru+xml";
    /**
     * `string` "application/ssml+xml"
     *
     * @var string
     */
    const SSML_XML = "application/ssml+xml";
    /**
     * `string` "application/tamp-apex-update"
     *
     * @var string
     */
    const TAMP_APEX_UPDATE = "application/tamp-apex-update";
    /**
     * `string` "application/tamp-apex-update-confirm"
     *
     * @var string
     */
    const TAMP_APEX_UPDATE_CONFIRM = "application/tamp-apex-update-confirm";
    /**
     * `string` "application/tamp-community-update"
     *
     * @var string
     */
    const TAMP_COMMUNITY_UPDATE = "application/tamp-community-update";
    /**
     * `string` "application/tamp-community-update-confirm"
     *
     * @var string
     */
    const TAMP_COMMUNITY_UPDATE_CONFIRM = "application/tamp-community-update-confirm";
    /**
     * `string` "application/tamp-error"
     *
     * @var string
     */
    const TAMP_ERROR = "application/tamp-error";
    /**
     * `string` "application/tamp-sequence-adjust"
     *
     * @var string
     */
    const TAMP_SEQUENCE_ADJUST = "application/tamp-sequence-adjust";
    /**
     * `string` "application/tamp-sequence-adjust-confirm"
     *
     * @var string
     */
    const TAMP_SEQUENCE_ADJUST_CONFIRM = "application/tamp-sequence-adjust-confirm";
    /**
     * `string` "application/tamp-status-query"
     *
     * @var string
     */
    const TAMP_STATUS_QUERY = "application/tamp-status-query";
    /**
     * `string` "application/tamp-status-response"
     *
     * @var string
     */
    const TAMP_STATUS_RESPONSE = "application/tamp-status-response";
    /**
     * `string` "application/tamp-update"
     *
     * @var string
     */
    const TAMP_UPDATE = "application/tamp-update";
    /**
     * `string` "application/tamp-update-confirm"
     *
     * @var string
     */
    const TAMP_UPDATE_CONFIRM = "application/tamp-update-confirm";
    /**
     * `string` "application/tei+xml"
     *
     * @var string
     */
    const TEI_XML = "application/tei+xml";
    /**
     * `string` "application/thraud+xml"
     *
     * @var string
     */
    const THRAUD_XML = "application/thraud+xml";
    /**
     * `string` "application/timestamp-query"
     *
     * @var string
     */
    const TIMESTAMP_QUERY = "application/timestamp-query";
    /**
     * `string` "application/timestamp-reply"
     *
     * @var string
     */
    const TIMESTAMP_REPLY = "application/timestamp-reply";
    /**
     * `string` "application/timestamped-data"
     *
     * @var string
     */
    const TIMESTAMPED_DATA = "application/timestamped-data";
    /**
     * `string` "application/tve-trigger"
     *
     * @var string
     */
    const TVE_TRIGGER = "application/tve-trigger";
    /**
     * `string` "application/ulpfec"
     *
     * @var string
     */
    const APPLICATION_ULPFEC = "application/ulpfec";
    /**
     * `string` "application/urc-grpsheet+xml"
     *
     * @var string
     */
    const URC_GRPSHEET_XML = "application/urc-grpsheet+xml";
    /**
     * `string` "application/urc-ressheet+xml"
     *
     * @var string
     */
    const URC_RESSHEET_XML = "application/urc-ressheet+xml";
    /**
     * `string` "application/urc-targetdesc+xml"
     *
     * @var string
     */
    const URC_TARGETDESC_XML = "application/urc-targetdesc+xml";
    /**
     * `string` "application/urc-uisocketdesc+xml"
     *
     * @var string
     */
    const URC_UISOCKETDESC_XML = "application/urc-uisocketdesc+xml";
    /**
     * `string` "application/vcard+xml"
     *
     * @var string
     */
    const VCARD_XML = "application/vcard+xml";
    /**
     * `string` "application/vemmi"
     *
     * @var string
     */
    const VEMMI = "application/vemmi";
    /**
     * `string` "application/voicexml+xml"
     *
     * @var string
     */
    const VOICEXML_XML = "application/voicexml+xml";
    /**
     * `string` "application/vq-rtcpxr"
     *
     * @var string
     */
    const VQ_RTCPXR = "application/vq-rtcpxr";
    /**
     * `string` "application/watcherinfo+xml"
     *
     * @var string
     */
    const WATCHERINFO_XML = "application/watcherinfo+xml";
    /**
     * `string` "application/whoispp-query"
     *
     * @var string
     */
    const WHOISPP_QUERY = "application/whoispp-query";
    /**
     * `string` "application/whoispp-response"
     *
     * @var string
     */
    const WHOISPP_RESPONSE = "application/whoispp-response";
    /**
     * `string` "application/widget"
     *
     * @var string
     */
    const WIDGET = "application/widget";
    /**
     * `string` "application/wita"
     *
     * @var string
     */
    const WITA = "application/wita";
    /**
     * `string` "application/wordperfect5.1"
     *
     * @var string
     */
    const WORDPERFECT5_1 = "application/wordperfect5.1";
    /**
     * `string` "application/wsdl+xml"
     *
     * @var string
     */
    const WSDL_XML = "application/wsdl+xml";
    /**
     * `string` "application/wspolicy+xml"
     *
     * @var string
     */
    const WSPOLICY_XML = "application/wspolicy+xml";
    /**
     * `string` "application/x-www-form-urlencoded"
     *
     * @var string
     */
    const X_WWW_FORM = "application/x-www-form-urlencoded";
    /**
     * `string` "application/x400-bp"
     *
     * @var string
     */
    const X400_BP = "application/x400-bp";
    /**
     * `string` "application/xcap-att+xml"
     *
     * @var string
     */
    const XCAP_ATT_XML = "application/xcap-att+xml";
    /**
     * `string` "application/xcap-caps+xml"
     *
     * @var string
     */
    const XCAP_CAPS_XML = "application/xcap-caps+xml";
    /**
     * `string` "application/xcap-diff+xml"
     *
     * @var string
     */
    const XCAP_DIFF_XML = "application/xcap-diff+xml";
    /**
     * `string` "application/xcap-el+xml"
     *
     * @var string
     */
    const XCAP_EL_XML = "application/xcap-el+xml";
    /**
     * `string` "application/xcap-error+xml"
     *
     * @var string
     */
    const XCAP_ERROR_XML = "application/xcap-error+xml";
    /**
     * `string` "application/xcap-ns+xml"
     *
     * @var string
     */
    const XCAP_NS_XML = "application/xcap-ns+xml";
    /**
     * `string` "application/xcon-conference-info-diff+xml"
     *
     * @var string
     */
    const XCON_CONFERENCE_INFO_DIFF_XML = "application/xcon-conference-info-diff+xml";
    /**
     * `string` "application/xcon-conference-info+xml"
     *
     * @var string
     */
    const XCON_CONFERENCE_INFO_XML = "application/xcon-conference-info+xml";
    /**
     * `string` "application/xenc+xml"
     *
     * @var string
     */
    const XENC_XML = "application/xenc+xml";
    /**
     * `string` "application/xhtml+xml"
     *
     * @var string
     */
    const XHTML_XML = "application/xhtml+xml";
    /**
     * `string` "application/xml"
     *
     * @var string
     */
    const XML = "application/xml";
    /**
     * `string` "application/xml-dtd"
     *
     * @var string
     */
    const XML_DTD = "application/xml-dtd";
    /**
     * `string` "application/xml-external-parsed-entity"
     *
     * @var string
     */
    const XML_EXTERNAL_PARSED_ENTITY = "application/xml-external-parsed-entity";
    /**
     * `string` "application/xmpp+xml"
     *
     * @var string
     */
    const XMPP_XML = "application/xmpp+xml";
    /**
     * `string` "application/xop+xml"
     *
     * @var string
     */
    const XOP_XML = "application/xop+xml";
    /**
     * `string` "application/xslt+xml"
     *
     * @var string
     */
    const XSLT_XML = "application/xslt+xml";
    /**
     * `string` "application/xv+xml"
     *
     * @var string
     */
    const XV_XML = "application/xv+xml";
    /**
     * `string` "application/yang"
     *
     * @var string
     */
    const YANG = "application/yang";
    /**
     * `string` "application/yin+xml"
     *
     * @var string
     */
    const YIN_XML = "application/yin+xml";
    /**
     * `string` "application/zip"
     *
     * @var string
     */
    const ZIP = "application/zip";
    /**
     * `string` "application/zlib"
     *
     * @var string
     */
    const ZLIB = "application/zlib";

    // Multipart.
    /**
     * `string` "multipart/alternative"
     *
     * @var string
     */
    const ALTERNATIVE = "multipart/alternative";
    /**
     * `string` "multipart/appledouble"
     *
     * @var string
     */
    const APPLEDOUBLE = "multipart/appledouble";
    /**
     * `string` "multipart/byteranges"
     *
     * @var string
     */
    const BYTERANGES = "multipart/byteranges";
    /**
     * `string` "multipart/digest"
     *
     * @var string
     */
    const DIGEST = "multipart/digest";
    /**
     * `string` "multipart/encrypted"
     *
     * @var string
     */
    const ENCRYPTED = "multipart/encrypted";
    /**
     * `string` "multipart/example"
     *
     * @var string
     */
    const MULTIPART_EXAMPLE = "multipart/example";
    /**
     * `string` "multipart/form-data"
     *
     * @var string
     */
    const FORM_DATA = "multipart/form-data";
    /**
     * `string` "multipart/header-set"
     *
     * @var string
     */
    const HEADER_SET = "multipart/header-set";
    /**
     * `string` "multipart/mixed"
     *
     * @var string
     */
    const MIXED = "multipart/mixed";
    /**
     * `string` "multipart/parallel"
     *
     * @var string
     */
    const PARALLEL = "multipart/parallel";
    /**
     * `string` "multipart/related"
     *
     * @var string
     */
    const RELATED = "multipart/related";
    /**
     * `string` "multipart/report"
     *
     * @var string
     */
    const REPORT = "multipart/report";
    /**
     * `string` "multipart/signed"
     *
     * @var string
     */
    const SIGNED = "multipart/signed";
    /**
     * `string` "multipart/voice-message"
     *
     * @var string
     */
    const VOICE_MESSAGE = "multipart/voice-message";

    // Audio.
    /**
     * `string` "audio/1d-interleaved-parityfec"
     *
     * @var string
     */
    const AUDIO_ONE_D_INTERLEAVED_PARITYFEC = "audio/1d-interleaved-parityfec";
    /**
     * `string` "audio/32kadpcm"
     *
     * @var string
     */
    const THIRTY_TWO_KADPCM = "audio/32kadpcm";
    /**
     * `string` "audio/3gpp"
     *
     * @var string
     */
    const AUDIO_THREE_GPP = "audio/3gpp";
    /**
     * `string` "audio/3gpp2"
     *
     * @var string
     */
    const AUDIO_THREE_GPP2 = "audio/3gpp2";
    /**
     * `string` "audio/ac3"
     *
     * @var string
     */
    const AC3 = "audio/ac3";
    /**
     * `string` "audio/AMR"
     *
     * @var string
     */
    const AMR = "audio/AMR";
    /**
     * `string` "audio/AMR-WB"
     *
     * @var string
     */
    const AMR_WB = "audio/AMR-WB";
    /**
     * `string` "audio/amr-wb+"
     *
     * @var string
     */
    const AMR_WB_ = "audio/amr-wb+";
    /**
     * `string` "audio/asc"
     *
     * @var string
     */
    const ASC = "audio/asc";
    /**
     * `string` "audio/ATRAC-ADVANCED-LOSSLESS"
     *
     * @var string
     */
    const ATRAC_ADVANCED_LOSSLESS = "audio/ATRAC-ADVANCED-LOSSLESS";
    /**
     * `string` "audio/ATRAC-X"
     *
     * @var string
     */
    const ATRAC_X = "audio/ATRAC-X";
    /**
     * `string` "audio/ATRAC3"
     *
     * @var string
     */
    const ATRAC3 = "audio/ATRAC3";
    /**
     * `string` "audio/basic"
     *
     * @var string
     */
    const BASIC = "audio/basic";
    /**
     * `string` "audio/BV16"
     *
     * @var string
     */
    const BV16 = "audio/BV16";
    /**
     * `string` "audio/BV32"
     *
     * @var string
     */
    const BV32 = "audio/BV32";
    /**
     * `string` "audio/clearmode"
     *
     * @var string
     */
    const CLEARMODE = "audio/clearmode";
    /**
     * `string` "audio/CN"
     *
     * @var string
     */
    const CN = "audio/CN";
    /**
     * `string` "audio/DAT12"
     *
     * @var string
     */
    const DAT12 = "audio/DAT12";
    /**
     * `string` "audio/dls"
     *
     * @var string
     */
    const DLS = "audio/dls";
    /**
     * `string` "audio/dsr-es201108"
     *
     * @var string
     */
    const DSR_ES201108 = "audio/dsr-es201108";
    /**
     * `string` "audio/dsr-es202050"
     *
     * @var string
     */
    const DSR_ES202050 = "audio/dsr-es202050";
    /**
     * `string` "audio/dsr-es202211"
     *
     * @var string
     */
    const DSR_ES202211 = "audio/dsr-es202211";
    /**
     * `string` "audio/dsr-es202212"
     *
     * @var string
     */
    const DSR_ES202212 = "audio/dsr-es202212";
    /**
     * `string` "audio/DV"
     *
     * @var string
     */
    const AUDIO_DV = "audio/DV";
    /**
     * `string` "audio/DVI4"
     *
     * @var string
     */
    const DVI4 = "audio/DVI4";
    /**
     * `string` "audio/eac3"
     *
     * @var string
     */
    const EAC3 = "audio/eac3";
    /**
     * `string` "audio/encaprtp"
     *
     * @var string
     */
    const AUDIO_ENCAPRTP = "audio/encaprtp";
    /**
     * `string` "audio/EVRC"
     *
     * @var string
     */
    const EVRC = "audio/EVRC";
    /**
     * `string` "audio/EVRC-QCP"
     *
     * @var string
     */
    const EVRC_QCP = "audio/EVRC-QCP";
    /**
     * `string` "audio/EVRC0"
     *
     * @var string
     */
    const EVRC0 = "audio/EVRC0";
    /**
     * `string` "audio/EVRC1"
     *
     * @var string
     */
    const EVRC1 = "audio/EVRC1";
    /**
     * `string` "audio/EVRCB"
     *
     * @var string
     */
    const EVRCB = "audio/EVRCB";
    /**
     * `string` "audio/EVRCB0"
     *
     * @var string
     */
    const EVRCB0 = "audio/EVRCB0";
    /**
     * `string` "audio/EVRCB1"
     *
     * @var string
     */
    const EVRCB1 = "audio/EVRCB1";
    /**
     * `string` "audio/EVRCNW"
     *
     * @var string
     */
    const EVRCNW = "audio/EVRCNW";
    /**
     * `string` "audio/EVRCNW0"
     *
     * @var string
     */
    const EVRCNW0 = "audio/EVRCNW0";
    /**
     * `string` "audio/EVRCNW1"
     *
     * @var string
     */
    const EVRCNW1 = "audio/EVRCNW1";
    /**
     * `string` "audio/EVRCWB"
     *
     * @var string
     */
    const EVRCWB = "audio/EVRCWB";
    /**
     * `string` "audio/EVRCWB0"
     *
     * @var string
     */
    const EVRCWB0 = "audio/EVRCWB0";
    /**
     * `string` "audio/EVRCWB1"
     *
     * @var string
     */
    const EVRCWB1 = "audio/EVRCWB1";
    /**
     * `string` "audio/example"
     *
     * @var string
     */
    const AUDIO_EXAMPLE = "audio/example";
    /**
     * `string` "audio/fwdred"
     *
     * @var string
     */
    const AUDIO_FWDRED = "audio/fwdred";
    /**
     * `string` "audio/G719"
     *
     * @var string
     */
    const G719 = "audio/G719";
    /**
     * `string` "audio/G722"
     *
     * @var string
     */
    const G722 = "audio/G722";
    /**
     * `string` "audio/G7221"
     *
     * @var string
     */
    const G7221 = "audio/G7221";
    /**
     * `string` "audio/G723"
     *
     * @var string
     */
    const G723 = "audio/G723";
    /**
     * `string` "audio/G726-16"
     *
     * @var string
     */
    const G726_16 = "audio/G726-16";
    /**
     * `string` "audio/G726-24"
     *
     * @var string
     */
    const G726_24 = "audio/G726-24";
    /**
     * `string` "audio/G726-32"
     *
     * @var string
     */
    const G726_32 = "audio/G726-32";
    /**
     * `string` "audio/G726-40"
     *
     * @var string
     */
    const G726_40 = "audio/G726-40";
    /**
     * `string` "audio/G728"
     *
     * @var string
     */
    const G728 = "audio/G728";
    /**
     * `string` "audio/G729"
     *
     * @var string
     */
    const G729 = "audio/G729";
    /**
     * `string` "audio/G7291"
     *
     * @var string
     */
    const G7291 = "audio/G7291";
    /**
     * `string` "audio/G729D"
     *
     * @var string
     */
    const G729D = "audio/G729D";
    /**
     * `string` "audio/G729E"
     *
     * @var string
     */
    const G729E = "audio/G729E";
    /**
     * `string` "audio/GSM"
     *
     * @var string
     */
    const GSM = "audio/GSM";
    /**
     * `string` "audio/GSM-EFR"
     *
     * @var string
     */
    const GSM_EFR = "audio/GSM-EFR";
    /**
     * `string` "audio/GSM-HR-08"
     *
     * @var string
     */
    const GSM_HR_08 = "audio/GSM-HR-08";
    /**
     * `string` "audio/iLBC"
     *
     * @var string
     */
    const ILBC = "audio/iLBC";
    /**
     * `string` "audio/ip-mr_v2.5"
     *
     * @var string
     */
    const IP_MR_V2_5 = "audio/ip-mr_v2.5";
    /**
     * `string` "audio/L8"
     *
     * @var string
     */
    const L8 = "audio/L8";
    /**
     * `string` "audio/L16"
     *
     * @var string
     */
    const L16 = "audio/L16";
    /**
     * `string` "audio/L20"
     *
     * @var string
     */
    const L20 = "audio/L20";
    /**
     * `string` "audio/L24"
     *
     * @var string
     */
    const L24 = "audio/L24";
    /**
     * `string` "audio/LPC"
     *
     * @var string
     */
    const LPC = "audio/LPC";
    /**
     * `string` "audio/mobile-xmf"
     *
     * @var string
     */
    const MOBILE_XMF = "audio/mobile-xmf";
    /**
     * `string` "audio/MPA"
     *
     * @var string
     */
    const MPA = "audio/MPA";
    /**
     * `string` "audio/mp4"
     *
     * @var string
     */
    const AUDIO_MP4 = "audio/mp4";
    /**
     * `string` "audio/MP4A-LATM"
     *
     * @var string
     */
    const MP4A_LATM = "audio/MP4A-LATM";
    /**
     * `string` "audio/mpa-robust"
     *
     * @var string
     */
    const MPA_ROBUST = "audio/mpa-robust";
    /**
     * `string` "audio/mpeg"
     *
     * @var string
     */
    const AUDIO_MPEG = "audio/mpeg";
    /**
     * `string` "audio/mpeg4-generic"
     *
     * @var string
     */
    const AUDIO_MPEG4_GENERIC = "audio/mpeg4-generic";
    /**
     * `string` "audio/ogg"
     *
     * @var string
     */
    const AUDIO_OGG = "audio/ogg";
    /**
     * `string` "audio/parityfec"
     *
     * @var string
     */
    const AUDIO_PARITYFEC = "audio/parityfec";
    /**
     * `string` "audio/PCMA"
     *
     * @var string
     */
    const PCMA = "audio/PCMA";
    /**
     * `string` "audio/PCMA-WB"
     *
     * @var string
     */
    const PCMA_WB = "audio/PCMA-WB";
    /**
     * `string` "audio/PCMU"
     *
     * @var string
     */
    const PCMU = "audio/PCMU";
    /**
     * `string` "audio/PCMU-WB"
     *
     * @var string
     */
    const PCMU_WB = "audio/PCMU-WB";
    /**
     * `string` "audio/QCELP"
     *
     * @var string
     */
    const QCELP = "audio/QCELP";
    /**
     * `string` "audio/raptorfec"
     *
     * @var string
     */
    const AUDIO_RAPTORFEC = "audio/raptorfec";
    /**
     * `string` "audio/RED"
     *
     * @var string
     */
    const AUDIO_RED = "audio/RED";
    /**
     * `string` "audio/rtp-enc-aescm128"
     *
     * @var string
     */
    const AUDIO_RTP_ENC_AESCM128 = "audio/rtp-enc-aescm128";
    /**
     * `string` "audio/rtploopback"
     *
     * @var string
     */
    const AUDIO_RTPLOOPBACK = "audio/rtploopback";
    /**
     * `string` "audio/rtp-midi"
     *
     * @var string
     */
    const RTP_MIDI = "audio/rtp-midi";
    /**
     * `string` "audio/rtx"
     *
     * @var string
     */
    const AUDIO_RTX = "audio/rtx";
    /**
     * `string` "audio/SMV"
     *
     * @var string
     */
    const SMV = "audio/SMV";
    /**
     * `string` "audio/SMV0"
     *
     * @var string
     */
    const SMV0 = "audio/SMV0";
    /**
     * `string` "audio/SMV-QCP"
     *
     * @var string
     */
    const SMV_QCP = "audio/SMV-QCP";
    /**
     * `string` "audio/sp-midi"
     *
     * @var string
     */
    const SP_MIDI = "audio/sp-midi";
    /**
     * `string` "audio/speex"
     *
     * @var string
     */
    const SPEEX = "audio/speex";
    /**
     * `string` "audio/t140c"
     *
     * @var string
     */
    const T140C = "audio/t140c";
    /**
     * `string` "audio/t38"
     *
     * @var string
     */
    const AUDIO_T38 = "audio/t38";
    /**
     * `string` "audio/telephone-event"
     *
     * @var string
     */
    const TELEPHONE_EVENT = "audio/telephone-event";
    /**
     * `string` "audio/tone"
     *
     * @var string
     */
    const TONE = "audio/tone";
    /**
     * `string` "audio/UEMCLIP"
     *
     * @var string
     */
    const UEMCLIP = "audio/UEMCLIP";
    /**
     * `string` "audio/ulpfec"
     *
     * @var string
     */
    const AUDIO_ULPFEC = "audio/ulpfec";
    /**
     * `string` "audio/VDVI"
     *
     * @var string
     */
    const VDVI = "audio/VDVI";
    /**
     * `string` "audio/VMR-WB"
     *
     * @var string
     */
    const VMR_WB = "audio/VMR-WB";
    /**
     * `string` "audio/vorbis"
     *
     * @var string
     */
    const VORBIS = "audio/vorbis";
    /**
     * `string` "audio/vorbis-config"
     *
     * @var string
     */
    const VORBIS_CONFIG = "audio/vorbis-config";

    // Video.
    /**
     * `string` "video/1d-interleaved-parityfec"
     *
     * @var string
     */
    const VIDEO_ONE_D_INTERLEAVED_PARITYFEC = "video/1d-interleaved-parityfec";
    /**
     * `string` "video/3gpp"
     *
     * @var string
     */
    const VIDEO_THREE_GPP = "video/3gpp";
    /**
     * `string` "video/3gpp2"
     *
     * @var string
     */
    const VIDEO_THREE_GPP2 = "video/3gpp2";
    /**
     * `string` "video/3gpp-tt"
     *
     * @var string
     */
    const THREE_GPP_TT = "video/3gpp-tt";
    /**
     * `string` "video/BMPEG"
     *
     * @var string
     */
    const BMPEG = "video/BMPEG";
    /**
     * `string` "video/BT656"
     *
     * @var string
     */
    const BT656 = "video/BT656";
    /**
     * `string` "video/CelB"
     *
     * @var string
     */
    const CELB = "video/CelB";
    /**
     * `string` "video/DV"
     *
     * @var string
     */
    const VIDEO_DV = "video/DV";
    /**
     * `string` "video/encaprtp"
     *
     * @var string
     */
    const VIDEO_ENCAPRTP = "video/encaprtp";
    /**
     * `string` "video/example"
     *
     * @var string
     */
    const VIDEO_EXAMPLE = "video/example";
    /**
     * `string` "video/H261"
     *
     * @var string
     */
    const H261 = "video/H261";
    /**
     * `string` "video/H263"
     *
     * @var string
     */
    const H263 = "video/H263";
    /**
     * `string` "video/H263-1998"
     *
     * @var string
     */
    const H263_1998 = "video/H263-1998";
    /**
     * `string` "video/H263-2000"
     *
     * @var string
     */
    const H263_2000 = "video/H263-2000";
    /**
     * `string` "video/H264"
     *
     * @var string
     */
    const H264 = "video/H264";
    /**
     * `string` "video/H264-RCDO"
     *
     * @var string
     */
    const H264_RCDO = "video/H264-RCDO";
    /**
     * `string` "video/H264-SVC"
     *
     * @var string
     */
    const H264_SVC = "video/H264-SVC";
    /**
     * `string` "video/jpeg2000"
     *
     * @var string
     */
    const JPEG2000 = "video/jpeg2000";
    /**
     * `string` "video/MJ2"
     *
     * @var string
     */
    const MJ2 = "video/MJ2";
    /**
     * `string` "video/MP1S"
     *
     * @var string
     */
    const MP1S = "video/MP1S";
    /**
     * `string` "video/MP2P"
     *
     * @var string
     */
    const MP2P = "video/MP2P";
    /**
     * `string` "video/MP2T"
     *
     * @var string
     */
    const MP2T = "video/MP2T";
    /**
     * `string` "video/mp4"
     *
     * @var string
     */
    const VIDEO_MP4 = "video/mp4";
    /**
     * `string` "video/MP4V-ES"
     *
     * @var string
     */
    const MP4V_ES = "video/MP4V-ES";
    /**
     * `string` "video/MPV"
     *
     * @var string
     */
    const MPV = "video/MPV";
    /**
     * `string` "video/mpeg"
     *
     * @var string
     */
    const VIDEO_MPEG = "video/mpeg";
    /**
     * `string` "video/mpeg4-generic"
     *
     * @var string
     */
    const VIDEO_MPEG4_GENERIC = "video/mpeg4-generic";
    /**
     * `string` "video/nv"
     *
     * @var string
     */
    const NV = "video/nv";
    /**
     * `string` "video/ogg"
     *
     * @var string
     */
    const VIDEO_OGG = "video/ogg";
    /**
     * `string` "video/parityfec"
     *
     * @var string
     */
    const VIDEO_PARITYFEC = "video/parityfec";
    /**
     * `string` "video/pointer"
     *
     * @var string
     */
    const POINTER = "video/pointer";
    /**
     * `string` "video/quicktime"
     *
     * @var string
     */
    const QUICKTIME = "video/quicktime";
    /**
     * `string` "video/raptorfec"
     *
     * @var string
     */
    const VIDEO_RAPTORFEC = "video/raptorfec";
    /**
     * `string` "video/raw"
     *
     * @var string
     */
    const RAW = "video/raw";
    /**
     * `string` "video/rtp-enc-aescm128"
     *
     * @var string
     */
    const VIDEO_RTP_ENC_AESCM128 = "video/rtp-enc-aescm128";
    /**
     * `string` "video/rtploopback"
     *
     * @var string
     */
    const VIDEO_RTPLOOPBACK = "video/rtploopback";
    /**
     * `string` "video/rtx"
     *
     * @var string
     */
    const VIDEO_RTX = "video/rtx";
    /**
     * `string` "video/SMPTE292M"
     *
     * @var string
     */
    const SMPTE292M = "video/SMPTE292M";
    /**
     * `string` "video/ulpfec"
     *
     * @var string
     */
    const VIDEO_ULPFEC = "video/ulpfec";
    /**
     * `string` "video/vc1"
     *
     * @var string
     */
    const VC1 = "video/vc1";

    // Model.
    /**
     * `string` "model/example"
     *
     * @var string
     */
    const MODEL_EXAMPLE = "model/example";
    /**
     * `string` "model/iges"
     *
     * @var string
     */
    const MODEL_IGES = "model/iges";
    /**
     * `string` "model/mesh"
     *
     * @var string
     */
    const MESH = "model/mesh";
    /**
     * `string` "model/vrml"
     *
     * @var string
     */
    const VRML = "model/vrml";

    // Message.
    /**
     * `string` "message/CPIM"
     *
     * @var string
     */
    const CPIM = "message/CPIM";
    /**
     * `string` "message/delivery-status"
     *
     * @var string
     */
    const DELIVERY_STATUS = "message/delivery-status";
    /**
     * `string` "message/disposition-notification"
     *
     * @var string
     */
    const DISPOSITION_NOTIFICATION = "message/disposition-notification";
    /**
     * `string` "message/example"
     *
     * @var string
     */
    const MESSAGE_EXAMPLE = "message/example";
    /**
     * `string` "message/external-body"
     *
     * @var string
     */
    const EXTERNAL_BODY = "message/external-body";
    /**
     * `string` "message/feedback-report"
     *
     * @var string
     */
    const FEEDBACK_REPORT = "message/feedback-report";
    /**
     * `string` "message/global"
     *
     * @var string
     */
    const MESSAGE_GLOBAL = "message/global";
    /**
     * `string` "message/global-delivery-status"
     *
     * @var string
     */
    const GLOBAL_DELIVERY_STATUS = "message/global-delivery-status";
    /**
     * `string` "message/global-disposition-notification"
     *
     * @var string
     */
    const GLOBAL_DISPOSITION_NOTIFICATION = "message/global-disposition-notification";
    /**
     * `string` "message/global-headers"
     *
     * @var string
     */
    const GLOBAL_HEADERS = "message/global-headers";
    /**
     * `string` "message/http"
     *
     * @var string
     */
    const MESSAGE_HTTP = "message/http";
    /**
     * `string` "message/imdn+xml"
     *
     * @var string
     */
    const IMDN_XML = "message/imdn+xml";
    /**
     * `string` "message/partial"
     *
     * @var string
     */
    const PARTIAL = "message/partial";
    /**
     * `string` "message/rfc822"
     *
     * @var string
     */
    const RFC822 = "message/rfc822";
    /**
     * `string` "message/s-http"
     *
     * @var string
     */
    const S_HTTP = "message/s-http";
    /**
     * `string` "message/sip"
     *
     * @var string
     */
    const SIP = "message/sip";
    /**
     * `string` "message/sipfrag"
     *
     * @var string
     */
    const SIPFRAG = "message/sipfrag";
    /**
     * `string` "message/tracking-status"
     *
     * @var string
     */
    const TRACKING_STATUS = "message/tracking-status";

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Currently no-op.
     */

    public function __construct ()
    {
        // Empty.
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
