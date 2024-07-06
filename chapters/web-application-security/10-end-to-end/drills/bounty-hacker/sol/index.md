# 'Bounty Hacker' box writeup
## Bounty Hacker is a CTF box written by Sevuhl and available on the [TryHackMe platform](https://tryhackme.com).
## Read about [Tar in Linux](https://www.freecodecamp.org/news/tar-in-linux-example-tar-gz-tar-file-and-tar-directory-and-tar-compress-commands/) and [Breaking restricted environment with tar](https://gtfobins.github.io/gtfobins/tar/).
## ![bg](images/background.jpg?raw=true "Title")

+ **We deploy the machine and start with an nmap scan for open ports**

``nmap -sV -sC -oN scan1 10.10.229.13``

+ **We can see 3 open ports with some well known services: ftp, ssh and http, all opened on default ports**

![1](images/nmap_scan.jpg?raw=true "Nmap_scan")

+ **Next, we will try to connect to the ftp service using the default user anonymous**

![2](images/ftp_login.jpg?raw=true "Ftp_login")

+ **Listing the directory, we can observe two .txt files uploaded so let's get them**

``mget *.txt``

+ **Reading the task.txt file, we can find out who wrote the task list, giving us the first task answer. We list the second txt file, named locks.txt, and we can see multiple strings which seems to be some passwords kept in the ftp server**

```
rEddrAGON
ReDdr4g0nSynd!cat3
Dr@gOn$yn9icat3
R3DDr46ONSYndIC@Te
ReddRA60N
R3dDrag0nSynd1c4te
dRa6oN5YNDiCATE
ReDDR4g0n5ynDIc4te
R3Dr4gOn2044
RedDr4gonSynd1cat3
R3dDRaG0Nsynd1c@T3
...
```

+ **Let's try to use this password file to connect on the ssh service, using simultaneously the user found in the previous task. The Hydra tool has a brute-force option to crack the login of the ssh service, so we can use it**

``hydra -l lin -P locks.txt 10.10.229.13 -t 4 ssh``

+ **After we execute the brute-force process, Hydra give us the needed user password**

# ![3](images/hydra_brute.jpg?raw=true "Hydra")

+ **With the given credentials, we will connect to the ssh service**

``ssh lin@10.10.229.13``

+ **We land on the wanted system so we can read our first user flag**

# ![4](images/first_flag.jpg?raw=true "first_flag")

+ **Running the** ``sudo -l`` **command on @lin user and listing the allowed commands, we can see that user @lin may run the following commands on bountyhacker:**
      ``(root) /bin/tar``

# ![5](images/whoami.jpg?raw=true "whoami")

+ **Tar is a linux archiving utility, used by a lot of unix system administrators to create compressed archive files or to extract them. Looking into the tar manual, we can see that it has an option that can execute a command during the compress-program**

# ![6](images/tar.jpg?raw=true "tar manual")

+ **That being said, let's try to break our environment and spawn a shell using privilege escalation, getting access to the @root user**

``sudo tar xf /dev/null -I '/bin/sh -c "sh <&2 1>&2"'``

# ![Alt text](images/root_flag.jpg?raw=true "root_flag")
