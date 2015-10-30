# phpbb_2fa
phpBB extension to implement 2FA
================================

This extension adds support for the U2F two factor authentication standard in your phpBB forum.
You can set several options within this extension:

 *  Disable 2FA completly (Basicly disabling the extension!)
 *  Do not require 2FA, but give it as option to users
 *  Require 2FA for users with a_ permissions only, and only to login for the ACP
 *  Require 2FA for users with a_ permissions only
 *  Require 2FA for users with a_ or m_ permissions only
 *  Require 2FA for all users
Depending on the choosen setting 2FA is required at registration (If a new user is registered), or a user is directly asked
after login to update his profile with his key. 

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

##Important:##
This extenion requires that your board runs under SSL. Without SSL this extension won't work, and you will receive a error 2 when trying to use it!
This is a limitation from U2F.

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