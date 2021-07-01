# Introduction

In order to understand how to protect a web application, you need to understand how an attacker thinks. And in order to do that, you need to understand how a platform is built and what techniques are used to ensure minimum usability. As a first step, you can analyze web applications using the readily available tools, such as the browser’s built-in Developer Tools. Further on, you can attempt to find more information about the basic mechanisms that enable the server to identify its clients and keep tabs on who they are (authentication) and what they are allowed to do (authorization), through the use of cookies and sessions.

# Stateful HTTP: Cookies

As we mentioned in the previous session, HTTP is a stateless protocol used to communicate over the internet. This means that a request is not aware about any of the previous ones, and each request is executed independently. Given its stateless nature, simple mechanisms such as HTTP cookies were created to overcome the issue.

An HTTP cookie (also called web cookie, Internet cookie, browser cookie, or simply cookie) is a small piece of data sent from a website and stored on the user's computer by the user's web browser while the user is browsing. Cookies were designed to be a reliable mechanism for websites to remember stateful information (such as items added in the shopping cart in an online store) or to record the user's browsing activity (including clicking particular buttons, logging in, or recording which pages were visited in the past). They can also be used to remember pieces of information that the user previously entered into form fields, such as names, addresses, passwords, and credit-card numbers.  
  
![Cookies](https://github.com/hexcellents/sss-web/blob/master/02-web-basics/support/cookies.png)

## What is a cookie?
A cookie is a _key=value_ pair stored in a text file on the user’s computer. This file can be found, for example, at the following path on a Windows 10 using Chrome:  
  
`C:\Users\Your User Name\AppData\Local\Google\Chrome\User Data\Default\Cookies`  
  
An example of cookies set for a website could be:
* username=admin
* cookie_consent=1
* theme=dark

The first cookie stores the username, so it can be displayed to the user without querying the database. The second one stores the choice made by the user regarding the cookie consent, so the application would not continue to show the message every time. Finally, the third one stores which theme was selected (in this case, a dark theme).

Once a cookie has been set, the browser will send the cookie information in all subsequent HTTP requests until the cookie is deleted. Additionally, the cookie can have zero or more attributes, such as:

* _Domain_ and _Path_ attributes define the scope of the cookies. These attributes tell the browser what website they belong to.
* _Same origin policy_ dictates that websites are only allowed to set cookies on their own domain. In other words, the `www.example.com` website is not allowed to set cookies on `www.test.com` and vice versa. A website is only able to control cookies which are within its own domain.
* The _Expires_ attribute defines when the cookie is deleted. Alternatively, the Max-Age attribute can be used to state the amount of seconds after the cookie is to be deleted.
* The _Secure_ attribute defines that cookies should only be sent using secure channels such as HTTPS. Cookies with the Secure attribute should only be sent through a secure connection. This protects the application's cookies against theft attempts.
* The _http-only_ attribute defines that cookie should be exposed only using HTTP or HTTPS channels. This means that the cookies with this attribute cannot be accessed via client-side scripting or other methods. This is a defense mechanism against [some attacks](https://owasp.org/www-community/HttpOnly).

# Stateful HTTP: Sessions

As previously stated, HTTP is stateless. Therefore, it needs a mechanism to remember information from previous requests and associate it with a user for authentication purposes. The cookies are one way to achieve this. However, they are considered highly insecure because the user can easily manipulate their content. We cannot directly use them for authentication and other sensitive data. The solution to this problem is the session, which stores the data on the server, rather than the client. The session ID can be used as a means of communication.

## How does a session work?

When accessing a website that uses sessions, each user is assigned a session ID. They are more secure than the previously mentioned method mainly because the data never leaves the server, so an attacker cannot alter it. Instead, the ID is used to uniquely identify each user and associate the respective information with them.  
  
Sessions are usually short-lived, which makes them ideal for storing temporary state between pages. Sessions also expire once the user closes his browser or after a predefined amount of time (for example, 30 minutes).  
  
The basic workflow is:
1. The server starts a new session (sets a cookie via the HTTP Cookie header).
2. The server sets a new session variable (stored on the server-side).
3. When the client changes the page, it sends all the cookies in the request, along with the session ID from step 1.
4. The server reads the session ID from the cookie.
5. The server matches the session ID with the entries of a local list (in-memory, text file etc.).
6. If the server finds a match, it reads the stored variables. For PHP, these variables will become available in the superglobal variable `$_SESSION`.
7. If the server doesn’t find a match, it will create a new session and repeat the steps 1-6.
  
![Session lifecycle](https://github.com/hexcellents/sss-web/blob/master/02-web-basics/support/session.jpg)
  
Example of a session in PHP:  
`<?php`  
`session_start();  // Start the session`  
`$_SESSION['username'] = "John Doe";`  
`$_SESSION['is_admin'] = true;`  
`echo "Hello " . $_SESSION['username'];`  
`?>`  
  
Example of a session in Python:  
`s = requests.Session()`  
`s.get('https://httpbin.org/cookies/set/sessioncookie/123456789')`  
`r = s.get('https://httpbin.org/cookies')`  
  
`print(r.text)`  
`# '{"cookies": {"sessioncookie": "123456789"}}'`  
  
One might consider that sessions are pretty secure. However, they won’t stop an attacker to intercept the cookie with the session ID, for example using a [Man-in-the-Middle attack](https://www.imperva.com/learn/application-security/man-in-the-middle-attack-mitm/) over an insecure Wi-Fi connection, and steal the session ID to use it. This won’t give them access to the values that are stored on the server, but they will be able to impersonate the user or perform actions on their behalf. This is known as session hijacking. You can read more on this subject [here](https://owasp.org/www-community/attacks/Session_hijacking_attack) and [here](https://www.netsparker.com/blog/web-security/session-hijacking/).

# Authentication vs Authorization

Two concepts that usually make people confused are authentication and authorization. Both terms are often used in conjunction with each other when it comes to security and gaining access to the system. They are essential in almost every modern web application, as most of these apps need a way to uniquely identify their users using an account. These accounts can contain both personal information, available only to the logged in user, and public information, available to anybody. Based on the privilege level, users can have access to various functionalities, such as deleting other users, creating blog posts etc.

Fundamentally, authentication refers to **who you are** while authorization refers to **what you can do**.

**Authentication** is the process of verifying the identity of a person or device. A common example is entering a username and password when you log in to a website. Entering the correct login information lets the website know 1) who you are and 2) that it is actually you accessing the website.  
  
There could be other methods of authentication, such as passcodes, biometrics (fingerprints), Two-Factor Authentication, etc. We won’t insist too much on these other methods, but it’s good to know they exist.  
  
**Authorization** is a security mechanism to determine access levels or user/client privileges related to system resources including files, services, computer programs, data and application features. This is the process of granting or denying access to a network resource which allows the user access to various resources based on the user's identity.

## Real-life scenarios

Now imagine what would happen if someone obtains access to your Facebook account. Besides the previously public information, such as your name and your birthday, they can now view your friend lists, private conversations, or even impersonate you through a post. Although this situation won’t affect Facebook directly, it will certainly affect you.  
  
What if someone were to gain access to an administrator account of a university? They could remove all the students, erase their grades and all the study materials. This would be a really nefarious incident that would destroy the institution’s reputation and will also affect you as a student.  
  
This is why authentication and authorization are very important and their security is crucial.

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

# Google Hacking - Google Dorking

A Google Dork, also known as Google Dorking or Google hacking, is a valuable resource for security researchers. For the average person, Google is just a search engine used to find text, images, videos, and news. However, in the infosec world, Google is a useful hacking tool.  

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

# Wrap-up

In this rapidly evolving world, the technologies we use change at a very fast pace. We need to constantly implement new systems to help solve the issues that arise. Since HTTP is stateless, dynamic web applications needed a way to preserve the state between requests, so they used cookies and sessions.
  
It’s very important to understand the difference between authentication and authorization. Almost every web application on the internet today has one form or another of authentication and authorization. Many [frameworks](https://techterms.com/definition/framework#:~:text=A%20framework%2C%20or%20software%20framework,programs%20for%20a%20specific%20platform) and [Content Management Systems](https://techterms.com/definition/cms) provide built-in implementations of authorization and authentication to make the job of web developers easier.

# (Optional) Developer Tools

Modern browsers, such as Google Chrome, Mozilla Firefox and Microsoft Edge, include some functionality aimed at developers for easier debugging, testing and previews. Anyone can use these tools to look at the internals of a web page. As a security professional, or even a hobbyist, these instruments provide you with insightful information about the inner workings of any web application out there. Even if it can only show the front-end code, it can create an overview of the structure and maybe reveal valuable details, such as the traffic sent from and received by the client.  
  
In order to open these tools, you can press _F12_ while navigating a web page in any browser mentioned, or by using _Mouse Right Click_ and selecting the Inspect Element option. The latter lets you select which part of the page should be in focus when inspected.  
  
Alternatively, you can see the entire HTML code of a web page by selecting View Page Source in the Mouse Right Click context menu.  
  
Next, some of the core functionalities of these tools will be detailed (some names may vary slightly across browsers, but the functionality is mainly the same, so we will focus in Google Chrome here):

* **Elements**: In this tab you can see the HTML structure of the page. On the right panel, you can see the styles applied to each element when selected and add, remove or edit the properties directly from there. This kind of inspection could lead to the discovery of hidden elements which can be toggled into view by altering the CSS code or could lead to the discovery of commented pieces of code which could contain sensitive data. Also, the [DOM](https://github.com/hexcellents/sss-web/wiki/Session-01:-Web-Basics-&-Browser-Security-Model#dom-document-object-model) (Document Object Model) structure of the page can be altered, and elements can be added or removed, such as scripts, input fields, etc. (any element in fact), which means that any JavaScript code used to sanitize user input or perform other functions can be bypassed.

![Elements - Developer Tools](https://github.com/hexcellents/sss-web/blob/master/02-web-basics/support/devtools-1.png)  

* **Console**: The console prints errors which occurred during page rendering or during any action performed on the page, such as, but not limited to, error loading an image not found, error while performing an asynchronous request to fetch data, missing included file (such as CSS or Javascript files), errors in Javascript code from the included scripts, debug messages left by the developer, etc. The console also has the ability to run any Javascript code by typing it directly there and interacting with the page.  

![Console - Developer Tools](https://github.com/hexcellents/sss-web/blob/master/02-web-basics/support/devtools-2.png)  

* **Sources**: This tab lets you see any file from the loaded in the front-end, such as images, JS, CSS, etc. in an arborescent way. This could be a good tool to inspect the JS scripts included in the current page. They could reveal possibly valuable information, such as hidden paths or resources, or even critical pieces of functionality, which, if understood, could lead to successful exploits.  

![Sources - Developer Tools](https://github.com/hexcellents/sss-web/blob/master/02-web-basics/support/devtools-3.png)  

* **Network**: The network tab shows detailed information about every file loaded and every request and response made by the page. You can find in-depth info about the [HTTP requests](https://github.com/hexcellents/sss-web/wiki/Session-01:-Web-Basics-&-Browser-Security-Model#http-hypertext-transfer-protocol), such as HTTP parameters, HTTP methods (GET, POST), HTTP status codes (200, 404, 500, etc.), loading time and size of each loaded element (image, script, etc). Furthermore, clicking on one of the requests there, you can see the headers, the preview, the response (as raw content) and others. This is useful for listing all the resources needed by a page, such as if there are any requests to APIs, additional scripts loaded, etc.  

![Network - Developer Tools](https://github.com/hexcellents/sss-web/blob/master/02-web-basics/support/devtools-4.png)  

* **Application**: This tab lets you see some specific data about the page, such as cookies (which will be covered in depth in the next section), local storage, session storage, cache, etc. This can be useful to see which data is stored on the client-side and it may contain useful values.  

![Application - Developer Tools](https://github.com/hexcellents/sss-web/blob/master/02-web-basics/support/devtools-5.png)  

* **Security**: Detailed information about the protocol used (HTTP or HTTPS) and the website certificates. Insecure websites can be vulnerable because HTTP sends data in plain text across the connection, which may be intercepted (e.g. Man in the Middle).  

![Security - Developer Tools](https://github.com/hexcellents/sss-web/blob/master/02-web-basics/support/devtools-6.png)  

# Browser Extensions

Some browser extensions can make your life easier when interacting with the websites. Some examples would be:
* **EditThisCookie**: Lets you quickly add, edit or delete a cookie. It could be useful to open a context-menu with one click and edit a cookie on the fly while navigating, without the need to open the developer tools console.
    * Google Chrome link: https://chrome.google.com/webstore/detail/editthiscookie/fngmhnnpilhplaeedifhccceomclgfbg
    * Mozilla Firefox link: https://addons.mozilla.org/en-US/firefox/addon/etc2/  

* **ModHeader**: Lets you to add, modify and remove request headers and response headers. You can use this extension to set **X-Forwarded-For**, **Authorization**, **Access-Control-Allow-Origin** and other headers and remember your settings across your account.
    * Google Chrome link: https://chrome.google.com/webstore/detail/modheader/idgpnmonknjnojddfkpgkljpfnnfcklj
    * Mozilla Firefox link: https://addons.mozilla.org/en-US/firefox/addon/modheader-firefox/  

* **Hasher**: An extension to quickly generate most common **hashes** (_MD5_, _SHA-1_, _SHA-224_, _SHA-256_, _SHA-384_, _SHA-512_, etc), **ciphers** (_AES-256_, _DES_, _Triple-DES_, _RC4_, etc), **ROT13**, **HMAC**, **CRC** (_CRC-8_, _CRC 16_), for a given input. You can also convert timestamps to human readable formats, convert numbers from different bases (hex, binary, dec), encode or decode strings and convert between _ASCII_, _HEX_, _UTF-8_, etc.
    * Google Chrome link: https://chrome.google.com/webstore/detail/hasher/kignjplbjlocolcfldfhbonmbblpfbjb

# Further Reading

* [1] Metropolia University of Applied Sciences - Applied Web Application Security Course
* [2] https://en.wikipedia.org/wiki/HTTP_cookie
* [3] https://stackoverflow.com/questions/11142882/what-are-cookies-and-sessions-and-how-do-they-relate-to-each-other
* [4] https://docs.python-requests.org/en/master/user/advanced/
* [5] https://stackoverflow.com/questions/11142882/what-are-cookies-and-sessions-and-how-do-they-relate-to-each-other  
* [6] https://chrome.google.com/webstore/detail/editthiscookie/fngmhnnpilhplaeedifhccceomclgfbg  
* [7] https://danielmiessler.com/study/encoding-encryption-hashing-obfuscation/  
* [8] https://searchsecurity.techtarget.com/tip/How-to-encrypt-and-secure-a-website-using-HTTPS  
* [9] https://www.linkedin.com/learning/asp-dot-net-core-identity-authentication-management
* [10] https://techterms.com/definition/authentication
* [11] https://www.bugcrowd.com/blog/how-to-find-idor-insecure-direct-object-reference-vulnerabilities-for-large-bounty-rewards/
* [12] https://portswigger.net/web-security/file-path-traversal
* [13] https://www.amazon.com/Google-Hacking-Penetration-Testers-1/dp/1931836361
* [14] https://securitytrails.com/blog/google-hacking-techniques
* [15] https://www.acunetix.com/websitesecurity/google-hacking/
* [16] https://www.sciencedirect.com/science/article/pii/B9781931836364500087

# Exercises

**1.** [Nobody loves me](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**2.** [Do you need glasses?](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**3.** [Chef hacky mchack](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**4.** [Santa](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**5.** [Great Names](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**6.** [Mind your own business](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**7.**  [Beep Beep Boop](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**8.**  [Let's traverse the universe](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**9.**  [Color](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  










