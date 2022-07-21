# Name: Web: Exotic Attacks: Meme Uploader

## Vulnerability

Unrestricted file upload

## Exploit

You can upload basically any file, the server only checks its size and if there already exists a file with the same name.

It saves it in the `uploads/` folder (you have to guess this), hashing its name with md5, but leaving its extension intact.
In the success message, it tells you its resulting name.

You can for instance create a file with PHP code that reads the `flag.txt` file containing the flag (you also have to guess that the file with the flag exists, and its name. `ls` won't give you anything useful):

```php
<?php echo system("cat ../flag.txt"); ?>
```

The succes message: `Your file 5c7dce216dceb5c1a61108e9db9fa835.php has been uploaded successfully!`

Now navigate to: `/uploads/5c7dce216dceb5c1a61108e9db9fa835.php`.

The flag should be in the page source (inspect it).

Exploit in `../sol/solution.sh`.
