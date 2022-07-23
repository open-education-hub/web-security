---
title: "SSS: Session: Recon & Enumeration"
revealOptions:
  background-color: 'aquamarine'
  transition: 'none'
---

# Recon & Enumeration & Recap

Security Summer School

---

## Phases of Penetration Testing

penetration testing / pentester

1. reconnaissance - gathering information about the target system: technologies, subdomains, open ports, Google hacking
2. scanning - manually or automatically to discover vulnerabilities (XSS, SQLi)
3. gaining access - exploiting the found vulnerabilities using enumeration
4. maintaining access - backdoors
5. covering tracks - remove any evidence of the attack

----

## Reconnaissance

Nmap

----

## Ports - like holes in a system

* 0 - 65535, 0-1023 well-known ports
* http://example.com ⇔ example.com:80
* https://example.com ⇔ example.com:443
* 22 - SSH
* 20/21 - FTP
* 25 - SMTP

----

## Shodan Search Engine

https://www.shodan.io

Searches for devices connected to the Internet

Various search filters: port, city, IP etc.

----

## Scanning

OWASP

OWASP Top 10

OWASP ZAP

----

## Gaining access through enumeration

Brute forcing the login form

Wordlists are your best friend

https://github.com/danielmiessler/SecLists/blob/master/Passwords/Common-Credentials/10k-most-common.txt

Burp Intruder

----

## Web Content Enumeration/Discovery/Scanning/ Dirbusting

Common configuration files publicly available

Database dumps, Backups

E.g.:
* /viewdoc.bak => code source of viewdoc.jsp
* /server.log => full paths on the server, system information
* /package.json => file created for Node.js projects
* /php.ini (inside cgi-bin/) => sensitive information about the server, database credentials

----

## Tools

* DIRB - not maintained anymore, but easy to use
* DirBuster - GUI, not maintained anymore
* DirSearch - there are better alternatives
* FFUF
* Wfuzz
* GoBuster
* Burp Intruder

----

## Bug Bounty

https://www.bugcrowd.com/bug-bounty-list/

https://hackerone.com/bug-bounty-programs

https://github.com/projectdiscovery/public-bugbounty-programs/blob/master/chaos-bugbounty-list.json

