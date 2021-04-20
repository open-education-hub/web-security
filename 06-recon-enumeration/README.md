# Enumeration & Recon & Recap

When it comes to hacking, knowledge is power. The more knowledge you have about a target system or network, the more options you have available. This session will put together all you have learned so far to give you an overview of how to approach a given target you want to exploit. You will also find out about some more security tools that can help you automate the process.

# Phases of Penetration Testing

Penetration testing is an authorized simulated cyber attack on a computer system, performed to evaluate its security.

A standard penetration testing flow implies 5 stages:
* reconnaissance - gathering information about the target system: website technologies, subdomains, open ports, Google hacking
* scanning - manually or automatically (using tools) discovering vulnerabilities in the system, like SQL injection, XSS etc.
* gaining access using enumeration - exploiting the vulnerabilities found before to collect sensitive information: usernames, machine names, network information, service settings
* maintaining access - planting hidden programs (like Trojan horses) that make a future attack easier
* covering tracks - cleaning up all the signs that may lead to thinking that an attack happened 

![Penetration testing phases](https://github.com/hexcellents/sss-web/blob/master/06-recon-enumeration/support/pentest_phases.png)

Next, we introduce some popular tools that may help in the first three phases, to gather information about a target. Exploiting Tools/ Security Testing Tools/ Penetration Testing Tools are used for the discovery of vulnerabilities without attempting to actually exploit them.

# 1. Reconnaissance

Reconnaissance is an important first stage in any ethical hacking attempt. Before it is possible to exploit a vulnerability in the target system, it is necessary to find it. By performing reconnaissance on the target, an ethical hacker can learn about the details of the target network and identify potential attack vectors.

## Nmap
Nmap is probably the most well-known tool for active **network** reconnaissance. It is a network scanner designed to determine details about a system and the programs running on it.

Every computer has a total of 65535 available ports; however, many of these are registered as standard ports. For example, a HTTP Webservice can nearly always be found on port 80 of the server. A HTTPS Webservice can be found on port 443. If we do not know which of these ports a server has open, then we do not have a hope of successfully attacking the target; thus, it is crucial that we begin any attack with a port scan. Nmap can be used to perform many different kinds of port scan; the basic theory is this: it will connect to each port of the target in turn. Depending on how the port responds, it can be determined as being _open_, _closed_, or _filtered_ (usually by a firewall). Once we know which ports are open, we can then look at _enumerating_ which services are running on each port – either manually, or more commonly using nmap.

Typing the simple command `nmap` will display all of its options for scanning, while `nmap <target>` will convert the hostname to an IP address and scan the top 1000 TCP ports, displaying their state and the service running on it:

![Nmap output](https://github.com/hexcellents/sss-web/blob/master/06-recon-enumeration/support/nmap_output.png)

You can see the full example here [[1]](https://nmap.org/book/port-scanning-tutorial.html) and practice more Nmap options here [[2]](https://tryhackme.com/room/rpnmap).

# 2. Scanning

## OWASP Zap
Developed by OWASP (Open Web Application Security Project), ZAP or Zed Attack Proxy [[3]](https://www.zaproxy.org/) is a multi-platform, open source web application security testing tool. ZAP is used for finding a number of security vulnerabilities in a web app during the development as well as testing phase. Other than its use as a scanner, ZAP can also be used to intercept a proxy for manually testing a webpage. [[4]](https://hackr.io/blog/top-10-open-source-security-testing-tools-for-web-applications)
ZAP can identify:
* Application error disclosure
* Cookie not marked with the HttpOnly flag
* Missing anti-CSRF tokens and security headers
* Private IP disclosure
* Session ID in URL rewrite
* SQL injection
* XSS injection

You can read about other active recon tools here [[5]](https://resources.infosecinstitute.com/topic/top-10-network-recon-tools/#gref): Nessus, OpenVAS, Nikto, Metasploit.

# 3. Enumeration

## Extracting common passwords - Burp Intruder

You were introduced to Burp Proxy in earlier session. Now we'll see an example of how to use Intruder in order to enumerate passwords.
With Burp Intruder, customized attacks can be automated against web applications. Customizing attacks requires that we specify one or more payloads and the position where the payloads will be placed in the website.
Use Cases: Enumerating identifiers, harvesting useful data and fuzzing for vulnerabilities.

* I have opened Burp and the built-in Chromium browser, having my intercept off.
* I navigated to https://sss-ctf.security.cs.pub.ro/home and tried to log in using the email **a@a.com** and the password **abc123**.
* The POST request can be found in HTTP history. Right click on it to send it to Intruder.

![Send request to Burp Intruder](https://github.com/hexcellents/sss-web/blob/master/06-recon-enumeration/support/send_to_intruder.png)

* Let's say we want to try all the passwords from **abc1**, **abc3**, **abc5**... to **abc100**. Navigate to the **Positions** tab - the payload position is specified with a pair of these characters: **§** called **payload markers**.

**Note!** By default, Burp surrounds by default some parameter values which might be candidates for enumeration, such as cookie values, or POST data values. Remove the extra **§** characters, leaving it like in the picture below.

![Set payload position](https://github.com/hexcellents/sss-web/blob/master/06-recon-enumeration/support/payload_position.png)

* Our payload type (wordlist) is a sequence of numbers which can be automatically generated in Burp. Go to the **Payloads** tab and select **Numbers** as the **Payload type**.
* Fill in the Payload options to generate all the numbers from 1 to 100, with the step 2 (1, 3, 5...).
* Finally, launch the attack.

![Set payload type](https://github.com/hexcellents/sss-web/blob/master/06-recon-enumeration/support/payload_type.png)

* A new window opens and you can see all the requests Burp is making, with the payloads you specified. For example, you can check the request corresponding to the payload 7, with the resulting password being **abc7**, and you can observe the response, its status code, or even open it in the browser.

![Attack example](https://github.com/hexcellents/sss-web/blob/master/06-recon-enumeration/support/attack_example.png)

There are many ways in which you can customize this process according to your needs. You can have multiple payload positions and select from four attack types, specifying how to insert the payloads (one different wordlist for each position, or combinations of them). Find more details here [[6]](https://portswigger.net/burp/documentation/desktop/tools/intruder/positions).
* **Sniper** - This uses a single set of payloads. It targets each payload position in turn, and places each payload into that position in turn. Positions that are not targeted for a given request are not affected - the position markers are removed and any enclosed text that appears between them in the template remains unchanged. This attack type is useful for fuzzing a number of request parameters individually for common vulnerabilities. The total number of requests generated in the attack is the product of the number of positions and the number of payloads in the payload set.
* **Battering ram** - This uses a single set of payloads. It iterates through the payloads, and places the same payload into all of the defined payload positions at once. This attack type is useful where an attack requires the same input to be inserted in multiple places within the request (e.g. a username within a Cookie and a body parameter). The total number of requests generated in the attack is the number of payloads in the payload set.
* **Pitchfork** - This uses multiple payload sets. There is a different payload set for each defined position (up to a maximum of 20). The attack iterates through all payload sets simultaneously, and places one payload into each defined position. In other words, the first request will place the first payload from payload set 1 into position 1 and the first payload from payload set 2 into position 2; the second request will place the second payload from payload set 1 into position 1 and the second payload from payload set 2 into position 2, etc. This attack type is useful where an attack requires different but related input to be inserted in multiple places within the request (e.g. a username in one parameter, and a known ID number corresponding to that username in another parameter). The total number of requests generated in the attack is the number of payloads in the smallest payload set.
* **Cluster bomb** - This uses multiple payload sets. There is a different payload set for each defined position (up to a maximum of 20). The attack iterates through each payload set in turn, so that all permutations of payload combinations are tested. I.e., if there are two payload positions, the attack will place the first payload from payload set 2 into position 2, and iterate through all the payloads in payload set 1 in position 1; it will then place the second payload from payload set 2 into position 2, and iterate through all the payloads in payload set 1 in position 1. This attack type is useful where an attack requires different and unrelated or unknown input to be inserted in multiple places within the request (e.g. when guessing credentials, a username in one parameter, and a password in another parameter). The total number of requests generated in the attack is the product of the number of payloads in all defined payload sets - this may be extremely large.

There are also many different types of payloads you can use [[7]](https://portswigger.net/burp/documentation/desktop/tools/intruder/payloads/types), from specifying your own list of words to generating random bytes. You can find lists of popular credentials online, for instance, here it is a repo with lists of most used passwords [[8]](https://github.com/danielmiessler/SecLists/tree/master/Passwords).


## Brute Force Active Directory

Let's say we have the target server **http://192.168.1.224/** and we want to discover hidden files, directories or other resources there. Manually, we would make multiple requests like _http://192.168.1.224/docs_, _http://192.168.1.224/config.php_ etc. or whatever we imagine might find and see if we get a 404 Not Found response or not. Luckily, there are command line tools and predefined wordlists in Kali (**/usr/share/wordlists/**) doing exactly this for us.

## DIRB
DIRB [[9]](https://tools.kali.org/web-applications/dirb) is a Web Content Scanner, a Kali built in tool. It looks for existing (and/or hidden) Web Objects. It basically works by launching a dictionary based attack against a web server and analyzing the response.
DIRB comes with a set of preconfigured attack wordlists for easy usage but you can use your custom wordlists. For each filename, it check the  existence on the webserver and returns the results which do not give a 404 Not Found response.

Usage example: `./dirb <url_base> [<wordlist_file(s)>] [options]`

![DIRB example](https://github.com/hexcellents/sss-web/blob/master/06-recon-enumeration/support/dirb_example.png)

The output lines with the results found (not 404) start with a `+` and give details about status code and page size.

You can read the documentation if you want to specify custom options, like custom file extensions to look for.

## Similar tools
* DirBuster [[10]](https://tools.kali.org/web-applications/dirbuster) - Kali built in, written in Java and the only one with a GUI and not a CLI
* GoBuster [[11]](https://tools.kali.org/web-applications/gobuster) - Kali built in, written in Go, also run in command line, but with more configurable options, like setting cookies or the User-Agent
* wfuzz [[12]](https://github.com/xmendez/wfuzz) - available on GitHub, written in Python, has a lot of options, can be easily installed with pip 
* ffuf [[13]](https://github.com/ffuf/ffuf) - also on GitHub, written in Go, has the option to mutate the files found

## Other techniques

* Extracting user names using email ID's
* Extract information using the default password         
* Extract user names using SNMP
* Extract user groups from Windows
* Extract information using DNS Zone transfer

Read more:
https://www.knowledgehut.com/blog/security/enumeration-in-ethical-hacking
https://www.greycampus.com/opencampus/ethical-hacking/enumeration-and-its-types
