# 'Brooklyn-Nine-Nine' box writeup
## Brooklyn-Nine-Nine is a CTF box written by Fsociety2006 and available on the [TryHackMe](https://tryhackme.com/) platform.
## Read about [Less Command](https://linuxize.com/post/less-command-in-linux/) and [Privilege Escalation using find, vim, less or bash](https://pentestlab.blog/category/privilege-escalation/)
# ![bg](images/background.jpeg?raw=true "Title")

## Foothold
+ **Let's deploy our machine and start with a nmap scan for ports**

``nmap -sV -sC -oN scan1 10.10.244.52``

+ **We can clearly see 3 ports open, a ftp, ssh and a http, all configured on default ports**

# ![1](images/nmap_scan_bnn.jpg?raw=true "nmap_scan")

**From the nmap report, the ftp anonymous login seems to be possible, so let's try it**

``ftp 10.10.244.52``

# ![2](images/ftp.jpg?raw=true "ftp")

**We successfully connected and we can see a** note_to_jake.txt **file inside the ftp server. We can get that file and read it**

``get note_to_jake.txt``
# ![3](images/change_password.jpg?raw=true "cp")

+ **Looks that Jake need to change his password. Because jake is using a very weak password, maybe we can bruteforce his login to some service. Let's use hydra to bruteforce the ssh serice - i'm using the rockyou.txt wordlist**

``hydra -l jake -P /usr/share/wordlists/rockyou.txt 10.10.244.52 -t 4 ssh``

# ![4](images/hydra.jpg?raw=true "hydra")

## User escalation

+ **So here we got some ssh credentials. Let's connect on the ssh service and run a** ``sudo -l`` **command on the jake user**

# ![5](images/less.jpg?raw=true "less")

**It looks like jake can run the less command with su privillege. Less is a command which can display content of a file and we can navigate both forward and backward through the file. Let's try to read the user flag**

``sudo less /home/holt/user.txt``

# ![6](images/user_flag_1.jpg?raw=true "user")

## Root escalation

+ **And here it is our first flag. We can also use less to get a privesc and get root access. Let's read a file with less**

``less /etc/passwd``

**Then generate a shell for the root user**

``!/bin/sh``

# ![7](images/binsh.jpg?raw=true "binsh")

# ![8](images/root_flag_2.jpg?raw=true "root")
