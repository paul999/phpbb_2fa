# phpbb_2fa
[![Build Status](https://travis-ci.org/paul999/phpbb_2fa.svg)](https://travis-ci.org/paul999/phpbb_2fa)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/paul999/phpbb_2fa/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/paul999/phpbb_2fa/?branch=master)

phpBB extension to implement 2FA
================================

This extension adds support for two factor authentication using several different security keys.
Currently, the following security keys are available by default:

 * U2F (See below)
 * Google authenticator (Or compitable)
 * Backup keys
 
New type of keys can be added by following the instructions in the WIKI.

You can set several options within this extension:

 * Disable 2FA completly (Basicly disabling the extension!)
 * Do not require 2FA, but give it as option to users
 * Require 2FA for users with a_ permissions only, and only to login for the ACP
 * Require 2FA for users with a_ permissions only
 * Require 2FA for users with a_ or m_ permissions only
 * Require 2FA for all users
 
Depending on the choosen setting 2FA, the board will be limit available for the user if a 2FA key is required.
Only when the option ```Do not require 2FA, but give it as option to users``` is selected, the board won't be limited.

U2F
===
Currently, the browser support for U2F is limited:

Supported:

 * Google Chrome (Version 41 and later) 
 
Not supported:

 * Safari
 * Firefox (See [this](https://bugzilla.mozilla.org/show_bug.cgi?id=1065729) feature request)
 * Internet Explorer
 * Edge
 
[Test if your browser supports U2F](https://demo.yubico.com/u2f)

You will need a U2F capable security key for this to work. [Yubico](https://www.yubico.com/) (and others) does provide them.

Besides the browser requirements, U2F requires that your board runs under SSL. Without SSL the U2F key won't work, 
and you will not be able to select this type of key to add. This is a limitation from U2F.

Installation
============
Download the latest release and and extract the download to ext/paul999/tfa/ and enable it

Updating
========
Disable the extension in your ACP, extra the zip to ext/paul999/tfa and enable it

Translations
============
Please create a PR on the master branch. Only translations submitted by PR are accepted. 
If a translation is incomplete at the moment of release it will be removed from the repository and the release.

Bugs/Feature requests
=====================
Bugs and feature requests can be made at the [github issue tracker](https://github.com/paul999/phpbb_2fa/issues).