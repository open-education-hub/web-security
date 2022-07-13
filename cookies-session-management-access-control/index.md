---
linkTitle: 02. Cookies & Session Management & Access Control
type: docs
weight: 10
---

# Introduction

In order to understand how to protect a web application, you need to understand how an attacker thinks.
And in order to do that, you need to understand how a platform is built and what techniques are used to ensure minimum usability.
As a first step, you can analyze web applications using readily available tools, such as the browser’s built-in Developer Tools.
Further on, you can attempt to find more information about the basic mechanisms that enable the server to identify its clients and keep tabs on who they are (authentication) and what they are allowed to do (authorization), through the use of cookies and sessions.

# Stateful HTTP: Cookies

As we mentioned in the previous session, HTTP is a stateless protocol used to communicate over the internet.
This means that a request is not aware of any of the previous ones, and each request is executed independently.
Given its stateless nature, simple mechanisms such as HTTP cookies were created to overcome the issue.

An HTTP cookie (also called web cookie, Internet cookie, browser cookie, or simply cookie) is a small piece of data sent from a website and stored on the user's computer by the user's web browser while the user is browsing.
Cookies were designed to be a reliable mechanism for websites to remember stateful information (such as items added in the shopping cart in an online store) or to record the user's browsing activity (including clicking particular buttons, logging in, or recording which pages were visited in the past).
They can also be used to remember pieces of information that the user previously entered into form fields, such as names, addresses, passwords, and credit card numbers.

![Cookies](./assets/cookies.png)

## What is a cookie?

A cookie is a _key=value_ pair stored in a text file on the user’s computer.
This file can be found, for example, at the following path on a Windows 10 using Chrome:

`C:\Users\Your User Name\AppData\Local\Google\Chrome\User Data\Default\Cookies`

An example of cookies set for a website could be:

- username=admin
- cookie_consent=1
- theme=dark

The first cookie stores the username, so it can be displayed to the user without querying the database.
The second one stores the choice made by the user regarding the cookie consent, so the application would not continue to show the message every time.
Finally, the third one stores which theme was selected (in this case, a dark theme).

Once a cookie has been set, the browser will send the cookie information in all subsequent HTTP requests until the cookie is deleted.
Additionally, the cookie can have zero or more attributes, such as:

- _Domain_ and _Path_ attributes define the scope of the cookies.
  These attributes tell the browser what website they belong to.
- _Same origin policy_ dictates that websites are only allowed to set cookies on their own domain.
  In other words, the `www.example.com` website is not allowed to set cookies on `www.test.com` and vice versa.
  A website is only able to control cookies that are within its own domain.
- The _Expires_ attribute defines when the cookie is deleted.
  Alternatively, the Max-Age attribute can be used to state the number of seconds after the cookie is to be deleted.
- The _Secure_ attribute defines that cookies should only be sent using secure channels such as HTTPS.
  Cookies with the Secure attribute should only be sent through a secure connection.
  This protects the application's cookies against theft attempts.
- The _http-only_ attribute defines that cookie should be exposed only using HTTP or HTTPS channels.
  This means that the cookies with this attribute cannot be accessed via client-side scripting or other methods.
  This is a defense mechanism against [some attacks](https://owasp.org/www-community/HttpOnly).

# Stateful HTTP: Sessions

As previously stated, HTTP is stateless.
Therefore, it needs a mechanism to remember information from previous requests and associate it with a user for authentication purposes.
The cookies are one way to achieve this.
However, they are considered highly insecure because the user can easily manipulate their content.
We cannot directly use them for authentication and other sensitive data.
The solution to this problem is the session, which stores the data on the server, rather than the client.
The session ID can be used as a means of communication.

## How does a session work?

When accessing a website that uses sessions, each user is assigned a session ID.
They are more secure than the previously mentioned method mainly because the data never leaves the server, so an attacker cannot alter it.
Instead, the ID is used to uniquely identify each user and associate the respective information with them.

Sessions are usually short-lived, which makes them ideal for storing temporary states between pages.
Sessions also expire once the user closes his browser or after a predefined amount of time (for example, 30 minutes).

The basic workflow is:

1. The server starts a new session (sets a cookie via the HTTP Cookie header).
2. The server sets a new session variable (stored on the server-side).
3. When the client changes the page, it sends all the cookies in the request, along with the session ID from step 1.
4. The server reads the session ID from the cookie.
5. The server matches the session ID with the entries of a local list (in-memory, text file etc.).
6. If the server finds a match, it reads the stored variables. For PHP, these variables will become available in the superglobal variable `$_SESSION`.
7. If the server doesn’t find a match, it will create a new session and repeat steps 1-6.

![Session lifecycle](./assets/session.jpg)

Example of a session in PHP:  
```php
<?php
session_start(); // Start the session
$_SESSION['username'] = "John Doe"; 
$_SESSION['is_admin'] = true;
echo "Hello " . $_SESSION['username'];
?>
```

Example of a session in Python:  
```python
s = requests.Session()
s.get('https://httpbin.org/cookies/set/sessioncookie/123456789')
r = s.get('https://httpbin.org/cookies')

print(r.text)
# '{"cookies": {"sessioncookie": "123456789"}}'
```

One might consider that sessions are pretty secure.
However, they won’t stop an attacker to intercept the cookie with the session ID, for example using a [Man-in-the-Middle attack](https://www.imperva.com/learn/application-security/man-in-the-middle-attack-mitm/) over an insecure Wi-Fi connection, and steal the session ID to use it.
This won’t give them access to the values that are stored on the server, but they will be able to impersonate the user or perform actions on their behalf.
This is known as session hijacking.
You can read more on this subject [here](https://owasp.org/www-community/attacks/Session_hijacking_attack) and [here](https://www.netsparker.com/blog/web-security/session-hijacking/).

# Authentication vs Authorization

Two concepts that usually make people confused are authentication and authorization.
Both terms are often used in conjunction with each other when it comes to security and gaining access to the system.
They are essential in almost every modern web application, as most of these apps need a way to uniquely identify their users using an account.
These accounts can contain both personal information, available only to the logged in user, and public information, available to anybody.
Based on the privilege level, users can have access to various functionalities, such as deleting other users, creating blog posts etc.

Fundamentally, authentication refers to **who you are** while authorization refers to **what you can do**.

**Authentication** is the process of verifying the identity of a person or device.
A common example is entering a username and password when you log in to a website.
Entering the correct login information lets the website know who you are and that it is actually you accessing the website.

There could be other methods of authentication, such as passcodes, biometrics (fingerprints), Two-Factor Authentication, etc.
We won’t insist too much on these other methods, but it’s good to know they exist.

**Authorization** is a security mechanism to determine access levels or user/client privileges related to system resources including files, services, computer programs, data and application features.
This is the process of granting or denying access to a network resource that allows the user access to various resources based on the user's identity.

## Real-life scenarios

Now imagine what would happen if someone obtains access to your Facebook account.
Besides the previously public information, such as your name and your birthday, they can now view your friend lists, private conversations, or even impersonate you through a post.
Although this situation won’t affect Facebook directly, it will certainly affect you.

What if someone were to gain access to an administrator account of a university?
They could remove all the students, erase their grades and all the study materials.
This would be a really nefarious incident that would destroy the institution’s reputation and will also affect you as a student.

This is why authentication and authorization are very important and their security is crucial.

# Path Traversal

In many web applications, resources are accessed using a filename as a parameter.
This file is processed and displayed to the client by the application.
If the application does not verify the parameter, the attacker might be able to exploit the application and display an arbitrary file from the target system.
Normally an attacker would try to access password or configuration files to gain access to the system.
Obviously, server-side script files could be accessed to perform manual inspection for vulnerabilities.
Consider the following URL:

`http://example.com/view.php?file=image.jpg`

If the attacker wants to investigate the view.php file for possible exploitable coding mistakes, he would try to use the script in order to open the file:

`http://example.com/view.php?file=view.php `

It is likely that images are stored in a subdirectory, so the attacker might have to access the parent directory:

`http://example.com/view.php?file=../view.php` or `http://example.com/view.php?file=../../view.php`

Depending on the system, a backslash could also be used:

`http://example.com/view.php?file=..\..\view.php `

An example of accessing system files:

`http://example.com/view.php?file=../../../../etc/passwd`

![Path Traversal](./assets/path_traversal.png)

## Path Traversal Prevention

The application should not allow directory traversal or the accessing of arbitrary files.
If the files to be accessed are known, the application should implement a mapping between the file and application-specific identifier.
This identifier can be hardcoded in the application to prevent any malicious attempts to modify it.

If it is considered unavoidable to pass user-supplied input to filesystem APIs, then two layers of defense should be used together to prevent attacks:

- The application should validate the user input before processing it.
  Ideally, the validation should compare against a whitelist of permitted values.
  If that isn't possible for the required functionality, then the validation should verify that the input contains only permitted content, such as purely alphanumeric characters.
- After validating the supplied input, the application should append the input to the base directory and use a platform filesystem API to canonicalize the path.
  It should verify that the canonicalized path starts with the expected base directory.

Below is an example of some simple Java code to validate the canonical path of a file based on user input:

```java
File file = new File(BASE_DIRECTORY, userInput);` 
if (file.getCanonicalPath().startsWith(BASE_DIRECTORY)) {` 
// process file
}
```

# Insecure Direct Object References

Insecure direct object reference vulnerability is similar to path traversal vulnerability.
The application allows access to resources using an identifier that is controllable by the user.
In this case, however, the identifier is not a file / path as is the case with path traversal.

Consider the following case where a user is able to view his own invoice:

`http://www.example.com/view.php?invoice=24411`

Now, by changing the invoice number the user might be able to access other invoices, including ones that are not his own, thereby gaining access to the sensitive information of other users.
Obviously the application should enforce access control over the items to be accessed.
If the application fails to do so, this would be a case of insecure direct object reference vulnerability.

When performing penetration tests, the application parameters should certainly be investigated by iterating through possible values and observing the responses.

![Insecure Direct Object References](./assets/insecure_direct_object_references.png)

## Insecure Direct Object References Prevention

First, you should control all normal, ajax and API requests when creating an application.
For example, can a read-only user write anything in the app?
Or can a non-admin user access and create API tokens that should only be created by an admin user?
So, in order to test all the possible IDOR vulnerabilities, you should think like a hacker.

You can provide permissions on your application for all endpoints.
If your `privatesection` endpoint includes the API requests such as `/api/privatesection/admins`, `/api/privatesection/console`, `/api/privatesection/tokens`, you can block the endpoint for non-admin users.

Moreover, to make the attacker’s job harder or prevent it altogether, you can use hash functions and hashed values instead of regular numbers and strings.

## robots.txt: Preventing Caching

The **robots.txt** file provides a list of instructions for automated Web crawlers, also called robots or bots.
Standardized at [robotstxt](http://www.robotstxt.org/robotstxt.html), this file allows you to define, with a great deal of precision, which files and directories are off-limits to Web robots.
The robots.txt file must be placed in the root of the Web server with permissions that allow the Web server to read the file.
Lines in the file beginning with a # sign are considered comments and are ignored.
Each line not beginning with a `#` should begin with either a User-agent or a disallow statement, followed by a colon and an optional space.
These lines are written to disallow certain crawlers from accessing certain directories or files.

Each Web crawler should send a user-agent field, which lists the name or type of the crawler.
The value of Google’s user-agent field is `Googlebot`.
To address a disallow to Google, the user-agent line should read:

`User-agent: Googlebot`

According to the original specification, the wildcard character `*` can be used in the user-agent field to indicate all crawlers.
The disallow line describes what, exactly; the crawler should not look at.

**NOTE:** Hackers don’t have to obey your robots.txt file.
In fact, Web crawlers really don’t have to either, although most of the big-name Web crawlers will, if only for the “CYA” factor.
One fairly common hacker trick is to view a site’s robots.txt file first to get an idea of how files and directories are mapped on the server.
In fact a quick Google query can reveal lots of sites that have had their robots.txt files crawled.
This, of course, is a misconfiguration, because the robots.txt file is meant to stay behind the scenes.

## Sitemap.xml

The sitemap.xml is a simple XML page which could be available on some websites and provide a “roadmap” for Google to the important pages that need to be crawled.
It’s a SEO (Search Engine Optimization) tool to help with the visibility of your website on the internet, but it could also be useful for a hacker, serving basically the same purpose: to give him a roadmap to every page.

## Examples of Google Dorking

- Google Hacking tool from [Pentest-Tools](https://pentest-tools.com/information-gathering/google-hacking)

- Passive Google Dork [Pagodo](https://github.com/opsdisk/pagodo)

### Explore LOG Files For Login Credentials

`allintext:password filetype:log after:2019` - Finds exposed log files that might contain passwords.

`allintext:username filetype:log` - Finds logs that contain usernames.

**Prevention:** Do not allow Google to access important data of your website, by properly configuring robots.txt.

### Explore Configurations Using **ENV** files

.env is used by various popular web development frameworks to declare general variables and configurations.

`DB_USERNAME filetype:env`  
`DB_PASSWORD filetype:enc=v`

By using the command you can find a list of sites that expose their _.env_ file on the internet.
Developers may accidentally include the _.env_ file in the public directory of the website, which can cause great harm if cyber criminals find it.

If you click into any of the exposed .env files, you will notice unencrypted usernames, passwords and IPs are directly exposed in the search results.
**Prevention:** _.env_ files should **not** be in a publicly accessible folder.

## Wayback Machine

The [Wayback Machine](https://archive.org/web/) is a digital archive of the entire internet.
It allows the user to go “back in time” and see what websites looked like in the past.
For a hacker, it can be useful to see what information was displayed on a website a few months ago or even a few years ago.  

# Wrap-up

In this rapidly evolving world, the technologies we use change at a very fast pace.
We need to constantly implement new systems to help solve the issues that arise.
Since HTTP is stateless, dynamic web applications needed a way to preserve the state between requests, so they used cookies and sessions.

It’s very important to understand the difference between authentication and authorization.
Almost every web application on the internet today has one form or another of authentication and authorization.
Many [frameworks](https://techterms.com/definition/framework#:~:text=A%20framework%2C%20or%20software%20framework,programs%20for%20a%20specific%20platform) and [Content Management Systems](https://techterms.com/definition/cms) provide built-in implementations of authorization and authentication to make the job of web developers easier.

# Browser Extensions

Some browser extensions can make your life easier when interacting with the websites. Some examples would be:

- **EditThisCookie**: Lets you quickly add, edit or delete a cookie.
  It could be useful to open a context-menu with one click and edit a cookie on the fly while navigating, without the need to open the developer tools console.

  - Google Chrome link: https://chrome.google.com/webstore/detail/editthiscookie/fngmhnnpilhplaeedifhccceomclgfbg
  - Mozilla Firefox link: https://addons.mozilla.org/en-US/firefox/addon/etc2/

- **ModHeader**: Lets you add, modify and remove request headers and response headers. You can use this extension to set **X-Forwarded-For**, **Authorization**, **Access-Control-Allow-Origin** and other headers and remember your settings across your account.

  - Google Chrome link: https://chrome.google.com/webstore/detail/modheader/idgpnmonknjnojddfkpgkljpfnnfcklj
  - Mozilla Firefox link: https://addons.mozilla.org/en-US/firefox/addon/modheader-firefox/

- **Hasher**: An extension to quickly generate most common **hashes** (_MD5_, _SHA-1_, _SHA-224_, _SHA-256_, _SHA-384_, _SHA-512_, etc), **ciphers** (_AES-256_, _DES_, _Triple-DES_, _RC4_, etc), **ROT13**, **HMAC**, **CRC** (_CRC-8_, _CRC 16_), for a given input. You can also convert timestamps to human readable formats, convert numbers from different bases (hex, binary, dec), encode or decode strings and convert between _ASCII_, _HEX_, _UTF-8_, etc.
  - Google Chrome link: https://chrome.google.com/webstore/detail/hasher/kignjplbjlocolcfldfhbonmbblpfbjb

# Further Reading

- [1] Metropolia University of Applied Sciences - Applied Web Application Security Course
- [2] https://en.wikipedia.org/wiki/HTTP_cookie
- [3] https://stackoverflow.com/questions/11142882/what-are-cookies-and-sessions-and-how-do-they-relate-to-each-other
- [4] https://docs.python-requests.org/en/master/user/advanced/
- [5] https://stackoverflow.com/questions/11142882/what-are-cookies-and-sessions-and-how-do-they-relate-to-each-other
- [6] https://chrome.google.com/webstore/detail/editthiscookie/fngmhnnpilhplaeedifhccceomclgfbg
- [7] https://danielmiessler.com/study/encoding-encryption-hashing-obfuscation/
- [8] https://searchsecurity.techtarget.com/tip/How-to-encrypt-and-secure-a-website-using-HTTPS
- [9] https://www.linkedin.com/learning/asp-dot-net-core-identity-authentication-management
- [10] https://techterms.com/definition/authentication
- [11] https://www.bugcrowd.com/blog/how-to-find-idor-insecure-direct-object-reference-vulnerabilities-for-large-bounty-rewards/
- [12] https://portswigger.net/web-security/file-path-traversal
- [13] https://www.amazon.com/Google-Hacking-Penetration-Testers-1/dp/1931836361
- [14] https://securitytrails.com/blog/google-hacking-techniques
- [15] https://www.acunetix.com/websitesecurity/google-hacking/
- [16] https://www.sciencedirect.com/science/article/pii/B9781931836364500087

# Activities

**1.** [Nobody loves me](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**2.** [Do you need glasses?](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**3.** [Chef hacky mchack](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**4.** [Santa](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**5.** [Great Names](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**6.** [Mind your own business](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**7.** [Beep Beep Boop](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**8.** [Let's traverse the universe](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)  
**9.** [Color](https://sss-ctf.security.cs.pub.ro/challenges?category=web-sessions)
