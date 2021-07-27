## Introduction

When performing an attack on a target, the last step of a malicious actor, before achieving full system compromise, is to elevate his or her privileges to an administrative account. Once the attacker reached this phase and successully escalated his access rights, he can do anything with the vulnerable application or computer system.

## What is privilege escalation?

Privilege escalation vulnerabilities are security issues that allow users to gain more permissions and a higher level of access to systems or applications than their administrators intended. These types of flaws are valuable for attackers because they're needed for full exploit chains but can be overlooked by defenders or developers because of their lower severity scores.

In general, any violation of an intentional security boundary can be considered a privilege escalation issue, including gaining kernel access from a user application in an operating system, escaping a virtual machine to access the underlying hypervisor, gaining domain administrator access from a workstation, or gaining privileged roles in public web applications by exploiting misconfigurations.

There are two main types of privilege escalation:
1. **Horizontal Privilege Escalation** is when a user gains the access rights of another user who has the same access level as he or she does.
3. **Vertical Privilege Escalation** is when an attacker uses a flaw in the system to gain access above what was intended for him or her.

### Horizontal Privilege Escalation
Gaining access to a user account with the same level of privileges as the malicious actor might sound a little weird, but there are legitimate use-cases for this. Think about the following scenario:

> Bob and Alice both have their own accounts at the same bank. Bob has malicious intents and exploits a misconfiguration to gain access to Alice's account. Even though they have the same level of access to the application's functionality, Bob can now access Alice's personal information, and is able to alter her account, transfer money on her behalf and many other things.

### Vertical Privilege Escalation
Generally, when someone attempts to hack into a system, it’s because they want to perform some action on the system. This could be damaging the system or stealing information. Oftentimes, this requires a privilege level the attacker does not possess. This is where vertical privilege escalation comes in.

The attacker exploits a flaw in the system, abuses a misconfiguration, or uses another vector to elevate his privileges from a normal user to an administrator.

Once the attacker managed to elevate his access rights, he will be able to perform any action the compromised account was able to perform.

An actual scenario to better understand the potential damage:

> The attacker managed to capture the admin's session cookies and takes over his session. Once logged in using the admin's cookies, he has access to the administration panel of the web application. From here, he can steal sensitive information (such as users data), perform a Denial of Service (DoS) attack (by deleting website's data), and create persistence (by locking out the actual administrators of the website).

## Application vs System PrivEsc

In the context of Web Security, we can also speak of another way of categorizing privilege escalation:
1. **Application Privilege Escalation** is when the attacker uses the application accounts to gain further access to application functionality.
2. **System Privilege Escalation** is when the attacker has already gained access to the underlying system where the web application runs and wishes to elevate his privileges to the administrator's account of the server.

We have already given a few examples of application privilege escalations in thre previous section, so now we will focus on system privilege escalation.

## System Privilege Escalation

Security best practices suggest a very useful principle, called the **Principle of Least Privilege**, in which a user is given the minimum levels of access – or permissions – needed to perform his/her job functions.

Following this principle, web servers should always be run by an unprivileged user – say `www-data` on a Linux system. The reciprocate of this is to **never** run a web server as `root`. This is _very important_, as it adds an extra security layer in case the web application is compromised. If that happens, the attacker will have the same privileges on the system as the user running the application.

Let's say that an attacker managed to find an **RCE vulnerability** (Remote Code Execution) on the web application. If the application is run by `root`, the attacker will be able to perform any command on the system with the same privileges as `root`. If, however, the application is run as `www-data`, the attacker will only have access to a small part of the system and will have to find another vulnerability to elevate his privileges.

# Privilege Escalation Vectors

## Application Vectors
First of all, let's talk about how an attacker could perform a privilege escalation attack on the web application.

1. **Broken Access Control** - when a user can in fact access some resource or perform some action that they are not supposed to be able to access.
> **Example:** The web application has an admin panel protected against unauthorized access, but uses API calls to retrieve users and perform actions. The developer forgot to secure these API endpoints with the same protection as the admin panel interface and the attacker discovered them, having unrestricted access to admin commands.

2. **Session Hijacking** - when a user steals another user / administrator session cookies and impersonates him.
> **Example:** The attacker found an XSS vulnerability / performed a Man in the Middle Attack and stole the session cookie of another user. Now he is able to impersonate that user in any request by using their cookies. If the compromised account is a normal user, it's called horizontal privilege escalation. If it's an administrator account, it's called vertical privilege escalation.
3. ****

### System Vectors

Finally, let's analyze a few methods where an attacker could gain elevated privileges once he has a foothold of the system (is able to execute commands on the underlying system).

There are countless methods to elevate privileges on a Linux system. The key in finding them is to **enumerate** the host for potential vectors.

1. **Kernel Exploit**
	- CVE-2016-5195 ([DirtyCow](https://dirtycow.ninja/)) - Linux Kernel <= `3.19.0-73.8`.
		A race condition was found in the way the Linux kernel's memory subsystem handled the copy-on-write (COW) breakage of private read-only memory mappings. An unprivileged local user could use this flaw to gain write access to otherwise read-only memory mappings and thus increase their privileges on the system.
	- sudo <= `v1.28`
		```bash
		> sudo -u#-1 /bin/bash
		```
	- More kernel exploits in this Git repos: [@lucyoa](https://github.com/lucyoa/kernel-exploits), [@offensive-security](https://github.com/offensive-security/exploitdb-bin-sploits/tree/master/bin-sploits).
2. **Exploiting SUDO Rights / SUID Binaries**
	- Sudo configuration might allow a user to execute some command with another user privileges without knowing the password:
		 ```bash
		 > sudo -l
		 User demo may run the following commands on demo-host:
			(root) NOPASSWD: /usr/bin/vim
		 ```
		 This would allow the attacker to create a privileged shell:
		 ```bash
		 > sudo vim -c '!sh'
		 ```
	- SUID Binaries. SUID/Setuid stands for "set user ID upon execution", and it is enabled by default in every Linux distributions. If a file with this bit is ran, the `uid` will be changed by the owner one. If the file owner is `root`, the `uid` will be changed to `root` even if it was executed from user `bob`. SUID bit is represented by an `s`.
		Commands to list SUID binaries:
		```bash
		> find / -perm -4000 -type f -exec ls -la {} 2>/dev/null \;
		> find / -uid 0 -perm -4000 -type f 2>/dev/null
		```
	- [GTFOBins](https://gtfobins.github.io/) are a curated list of Unix binaries that can be exploited by an attacker to bypass local security restrictions.
3. **Path Hijacking**
	- Path Hijacking occurs when a program uses the relative path to another program instead of the absolute path. Consider the following Python code:
		```python
		import os
		os.system('create_backup')
		```
		The `$PATH` variable is a Linux environment variable that specifies where to look for a specific binary when a full path is not provided. An attacker can exploit this mechanism by either being allowed to modify the `$PATH` variable or being able to write files inside directories specified there.
		So, in order to exploit the above Python code, the attacker places a program called `create_backup` inside a location from the `$PATH` variable and Linux will execute the malicious program instead of the intended one.
4. **Docker Privilege Escalation / Container Escape**
	- This requires the user to be privileged enough to run docker, i.e. being in the `docker` group or being `root`.
		```bash
		> docker run -v /:/mnt --rm -it alpine chroot /mnt sh
		```
		The command above creates a new container based on the `Linux Alpine` image, mounts the `/` directory from the host on `/mnt` inside the container and runs it with `/bin/sh`. Now the attacker can read any file on the system.
	- Escaping Docker privileged containers. Docker privileged containers are those run with the `--privileged` flag. Unlike regular containers, these have root privilege to the host machine. A detailed article can be read [here](https://betterprogramming.pub/escaping-docker-privileged-containers-a7ae7d17f5a1)
5. **Others**
	- `id` / `whoami` - identify if the user is part of special groups, such as `docker`, `admin`, etc.
	- `cat /etc/passwd` - list system users for potential privilege escalation
	- `crontab -l` / `ls -al /etc/cron* /etc/at*` - enumerate cron jobs (scheduled jobs) on the system.
	- `ps aux` / `ps -ef` - inspect running processes
	- `find / -name authorized_keys 2> /dev/null` - find SSH authorized keys
	- `find / -name id_rsa 2> /dev/null` - find SSH private keys
	- `find / -type f -iname ".*" -ls 2>/dev/null` - find hidden files
	- `grep --color=auto -rnw '/' -ie "PASSWORD" --color=always 2> /dev/null` - find files containing passwords.
	- Manually looking through web server logs, such as access or error logs for any sensitive information. Default locations for these logs:
		- `/var/log/apache2/error.log`
		- `/var/log/apache/access.log`
		- `/var/log/apache2/access.log`
		- `/etc/httpd/logs/access_log`

### Tools
There are many tools that automated the process of enumeration and could help you save a lot of time when looking for privilege escalation vectors. The best tool for Linux is [LinPEAS](https://github.com/carlospolop/privilege-escalation-awesome-scripts-suite/tree/master/linPEAS).

## Preventing Privilege Escalation
When it comes to OS-level privilege escalation vulnerabilities, it's vital to install security patches as soon as possible, not only for the OS, but for all third-party applications used on the system.

Application whitelisting technologies can be used to restrict which programs may run on a system, enabling organizations to reduce a machine's attack surface. Making sure that unneeded services are turned off and that unused hardware components and drivers are disabled is also very important.

## Further reading
- https://www.csoonline.com/article/3564726/privilege-escalation-explained-why-these-flaws-are-so-valuable-to-hackers.html
- https://portswigger.net/web-security/access-control
- https://github.com/swisskyrepo/PayloadsAllTheThings/blob/master/Methodology%20and%20Resources/Linux%20-%20Privilege%20Escalation.md
- https://book.hacktricks.xyz/linux-unix/privilege-escalation
- https://dirtycow.ninja/
- https://github.com/lucyoa/kernel-exploits
- https://github.com/offensive-security/exploitdb-bin-sploits/tree/master/bin-sploits
- https://gtfobins.github.io/
- https://betterprogramming.pub/escaping-docker-privileged-containers-a7ae7d17f5a1
- https://github.com/carlospolop/privilege-escalation-awesome-scripts-suite/tree/master/linPEAS
- https://app.hackthebox.eu/machines

## Challenges

- Escalation
