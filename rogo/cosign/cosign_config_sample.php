<?php
// Copyright (C) 2010 FIT Brno University of Technology
// All Rights Reserved. See LICENSE.
// Petr Lampa <lampa@fit.vutbr.cz>
// $Id: cosign_config.php,v 1.1 2010/03/01 10:32:16 lampa Exp $


// Enable Cosign Authentication
$cosign_cfg['CosignProtected'] = true;

// Hostname of server running cosignd 
$cosign_cfg['CosignHostname'] = 'weblogin.umich.edu';

// The port on which cosignd listens
$cosign_cfg['CosignPort'] = '6663';

// The name of cosign service cookie
$cosign_cfg['CosignService'] = '<e-mail to cosign@umich.edu and we will assign>';

// The URL to redirect for login
$cosign_cfg['CosignRedirect'] = 'https://weblogin.umich.edu';

// Filter DB directory. Must end with trailing slash
$cosign_cfg['CosignFilterDB'] = '/var/cosign/filter/';

// Expiration time of service cookie in seconds
$cosign_cfg['CosignCookieExpireTime'] = 3600*24;

// Debug log file path
$cosign_cfg['CosignFilterLog'] = '/tmp/cosign-filter.log';

// Enable debug log (boolean)
$cosign_cfg['CosignFilterDebug'] = true;

// Version of Cosign protocol
$cosign_cfg['CosignProtocolVersion'] = 3;

// The URL to which a user is redirected to if an error is 
// encountered during a POST
$cosign_cfg['CosignPostErrorRedirect'] = $cosign_cfg['CosignRedirect'].'/cosign/post_error.html';

// A list space separated factors that must be satisfied by the user
$cosign_cfg['CosignRequireFactor'] = '';

// Suffix, that is ignored in cosign factors
$cosign_cfg['CosignFactorSuffix'] = '-junk';

// Toggles, whether the value of CosignFactorSuffix is ignored
$cosign_cfg['CosignFactorSuffixIgnore'] = false;

// URL to which the user is redirected after login
$cosign_cfg['CosignSiteEntry'] = '';

// Use only http protocol to redirect back after login
$cosign_cfg['CosignHTTPOnly'] = false;

// Verify browser's IP against cosignd's IP information (no/initial/always)
$cosign_cfg['CosignCheckIP'] = 'initial';

// Subdirectory hash length (0,1,2) for Cosign filter cookie file storage
$cosign_cfg['CosignFilterHashLength'] = 0;

// Toggles whether proxy cookies will be requested from cosignd
$cosign_cfg['CosignGetProxyCookies'] = false;

// Cosign filter proxy DB directory. Must end with trailing slash
// NOT IMPLEMENTED
$cosign_cfg['CosignProxyDB'] = '/var/cosign/proxy/';

/*
** SSL context directives
*/

// PEM encoded certificate and private key
$cosign_cfg['CosignCryptoLocalCert'] = '/path/to/cert&keyfile.pem';

// Passphrase for private key (if private key is protected)
$cosign_cfg['CosignCryptoPassphrase'] = '';

// Require verification of server certificate
$cosign_cfg['CosignCryptoVerifyPeer'] = 1;

// Allow self-signed certificates
$cosign_cfg['CosignCryptoAllowSelfSigned'] = false;

// CA certificate which should be used to verify server certificate
$cosign_cfg['CosignCryptoCAFile'] = '/path/to/CAcertificate.pem';

// CA certificates directory (must be a correctly hashed certificate directory)
$cosign_cfg['CosignCryptoCAPath'] = '/path/to/CAdir';

/*
** Kerberos directives section
*/

//  Toggles whether the value of TGT will be requested from cosignd 
$cosign_cfg['CosignGetKerberosTickets'] = false;

// Kerberos ticket filter DB directory. Must end with trailing slash
$cosign_cfg['CosignTicketPrefix'] = '/var/cosign/tickets/';

?>
