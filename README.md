Two factors authentication for TYPO3 Flow and TYPO3 Neos
========================================================

TYPO3 Neos and Flow package that integrate One Time Password (OTP) from [Yubico](https://www.yubico.com/). 
	
Features
--------

* Provide an authentication provider and token to support Yubikey OTP validation
* Self provisioning of Yubikey, the first time a use login with a Yubikey, the key is attached to the user
* Replace the Neos login screen to a third field for the Yubikey Token

![A Neos login box with OTP support](https://dl.dropboxusercontent.com/s/pi53fniqr0xuqiy/2015-01-21%20at%2001.23%202x.png?dl=0)

Usage
-----

You can add this package to your composer.json:

```
"ttee/yubico": "~1.0"
```

The package require a single database type to store the key identifier and the mapping between the key and your user account.

```
flow doctrine:migrationexecute --version 20150120005252 --direction up
```

Check the [Settings.yaml](Configuration/Settings.yaml) to understand how the authentication provider and token are configured.

To use the OTP validation server provided by Yubico, you need your personal API keys: https://upgrade.yubico.com/getapikey/

Requirements / Limitations
--------------------------

* You need to have a key that support OTP, this package is tested with a [Yubikey Neo](https://www.yubico.com/products/yubikey-hardware/)
* You can mix account with and without Yubikey
* A Yubikey can only be used for one single account

Whishlist
---------

Feel free to open issue if you need a specific feature and better send a pull request. Here are some idea for future 
improvements:

* A backend module to manage Yubikey provisioning
* Open issue if you have some specific requirements
	
Acknowledgments
---------------

Development sponsored by [ttree ltd - neos solution provider](http://ttree.ch).

We try our best to craft this package with a lots of love, we are open to sponsoring, support request, ... just contact us.

License
-------

Licensed under GPLv3+, see [LICENSE](LICENSE)