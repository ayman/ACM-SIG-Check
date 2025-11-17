# ACM-SIG-Check
A simple form to check active ACM SIG membership and provide a personalized link to proceed to another site or form.

## How To Use

This package will work with any PHP ready webserver.  Just throw the files in a directory and go from there. 

### Setup 

If you set a define in your PHP as `ACM_CHECK_KEY` to some secure random string, this will be used for encryption and decrytion. So in the parent of the DOCUMENT_ROOT, make a config directory and add to (or create a) `config.php`.

```
<?php
define('ACM_CHECK_KEY', 'EXAMPLE_RANDOM_STRING');
?>
```

If you do not set this key, the member ID will be used as the encryption key.  This is fine but you won't be able to verify the hash as valid if needed. 

### Usage

Simply visit the index.php page. You can construct a URL (see the `about.php`) based on some parameters.  For example, to redirect to https://acm.org if the member ID is a SIGMM member:

```
index.php??sigid=44&sp=https://acm.org/
```

if an active SIGMM member is entered, you will get a custom link to proceed. This is useful for sending to a survey or form.  By default, the member number is not included for anonymous privacy, but there's a parameter to add it if needed.

### Checking

There is a `check.php` which will take a list of hashes and will verify them IFF `ACM_CHECK_KEY` is set.  Duplicates will also be listed.  Valid keys have a messsage in the format of `timestamp:sig_code:sig_name`.  The timestamp is useful to sanity check the freshness of the hash.  If the hash is older than your need, it's an old one so throw out that entry/submission.
