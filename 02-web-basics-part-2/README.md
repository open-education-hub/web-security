# Introduction

In order to understand how to protect a web application, first you need to understand how an attacker thinks. And in order to do that you need to understand how the application is built and what mechanisms are used to ensure minimum usability. For this purpose, you need to understand how you can analyze any web application on the internet using the available tools, such as the browser’s built-in Developer Tools, and then further grasp which are the basic mechanisms that enable the server to identify its clients and keep tabs on each of them as to who they are (authentication) and what are they allowed to do (authorization), using cookies and sessions. Furthermore, you need to know how a website protects its data using encryption, hashing, encoding and obfuscation.

# Developer Tools

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

# Stateful HTTP: Cookies

As we stated in the previous session, HTTP is a stateless protocol used to communicate over the internet. This means that there is no way the current requests knows anything about the previous ones, if there were any. To overcome this issue, there is a simple mechanism called HTTP cookie.  
  
An HTTP cookie (also called web cookie, Internet cookie, browser cookie, or simply cookie) is a small piece of data sent from a website and stored on the user's computer by the user's web browser while the user is browsing. Cookies were designed to be a reliable mechanism for websites to remember stateful information (such as items added in the shopping cart in an online store) or to record the user's browsing activity (including clicking particular buttons, logging in, or recording which pages were visited in the past). They can also be used to remember pieces of information that the user previously entered into form fields, such as names, addresses, passwords, and credit-card numbers.  
  
![Cookies](https://github.com/hexcellents/sss-web/blob/master/02-web-basics/support/cookies.png)

## What is a cookie?
A cookie is a _key=value_ pair stored in a text file on the user’s computer. This file can be found, for example, at the following path on a Windows 10 using Chrome:  
  
`C:\Users\Your User Name\AppData\Local\Google\Chrome\User Data\Default\Cookies`  
  
An example of cookies set for a website could be:
* username=admin
* cookie_consent=1
* theme=dark
  
The first one remembers the username, so it can be displayed to the user without querying the database. The second one remembers the choice made by the user regarding the cookie consent, so the application would not continue to show the message every time. Finally, the third one remembers which theme has the user set for this browser, which is dark here.  
  
Once a cookie has been set, the browser will send the cookie information in all subsequent HTTP requests until the cookie is deleted. Additionally, the cookie can have zero or more attributes, such as:

* _Domain_ and _Path_ attributes define the scope of the cookies. These attributes show the browser which website the cookie belongs to.
* _Same origin policy_ dictates that websites are only allowed to set cookies on their own domain. In other words, www.example.com website is not allowed to set cookies on test.com and vice versa. A website is only able to control cookies which are within its own domain.
* The _Expires_ attribute defines when the cookie is deleted. Alternatively, the Max-Age attribute can be used to define intervals in seconds defining when the cookie is to be deleted.
* The _Secure_ attribute defines that cookies should only be sent using secure channels such as HTTPS. Cookies with Secure attributes should only be sent using a secure connection. This protects the application's cookies against theft attempts.
* The _http-only_ attribute defines that cookie should be exposed only using HTTP or HTTPS channels. This means that the cookies with this attribute cannot be accessed via client-side scripting or other methods. This is a defense mechanism against certain types of attack.

# Stateful HTTP: Sessions

As stated before, HTTP is stateless, which means that it needs a mechanism to remember things between subsequent requests and associate it with a user for authentication purposes. The cookies, as we presented them before, are one way to achieve this. However, they are considered highly insecure because the user can easily manipulate their content. So we cannot use them as they are for authentication and other sensitive data. The solution to this problem is the session, which keeps the data stored on the server, rather than the client, and uses only a session ID in communications.

## How does a session work?

When accessing a website that uses sessions, each user is assigned a session ID. They are more secure than the previously mentioned method mainly because the data never leaves the server, so an attacker cannot alter it. Instead, the ID is used to uniquely identify each user and associate the respective information with them.  
  
Sessions are usually short-lived, which makes them ideal for storing temporary state between pages. Sessions also expire once the user closes his browser or after a predefined amount of time (for example, 30 minutes).  
  
The basic workflow is:
1. The server opens a new session (sets a cookie via the HTTP Cookie header).
2. The server sets a new session variable (stored on the server-side).
3. When the client changes the page, it sends all the cookies in the request, along with the session ID from step 1.
4. The server reads the session ID from the cookie.
5. The server matches the session ID from a local list (in-memory, text file, etc).
6. If the server finds a match, it reads the variables which are now available on the `$_SESSION` PHP superglobal variable.
7. If the server doesn’t find a match, it will start a new session and repeat the steps 1-6.
  
![Session lifecycle](https://github.com/hexcellents/sss-web/blob/master/02-web-basics/support/session.jpg)
  
Example of a session in PHP:  
`<?php`  
`session_start(); // Start the session`  
`$_SESSION['username'] = "John Doe";`  
`$_SESSION['is_admin'] = true;`  
`echo "Hello " . $_SESSION['username'];`  
`?>`  
  
Example of session in Python:  
`s = requests.Session()`  
`s.get('https://httpbin.org/cookies/set/sessioncookie/123456789')`  
`r = s.get('https://httpbin.org/cookies')`  
  
`print(r.text)`  
`# '{"cookies": {"sessioncookie": "123456789"}}'`  
  
This proves that the sessions are pretty secure. However, this won’t stop an attacker to intercept the cookie with the session ID, using for example a Man in the Middle technique over an unsecured WiFi connection, and steal that in order to use it himself. This won’t give him access to the values stored on the server, but he will be able to impersonate the user using his session or perform actions on his behalf. This is known as session hijacking. You can read more on this subject here [1][2].  

# Same-origin Policy

The same-origin policy is a critical security mechanism that restricts how a document or script loaded from one origin can interact with a resource from another origin. It helps isolate potentially malicious documents, reducing possible attack vectors.  
  
In order to understand how the policy works, you also need to understand what is an origin. Two URLs have the same origin if the protocol, port (if specified), and host are the same for both. To better understand this, follow the table below:

| URL                                              | Outcome     | Reason                                         |
| ------------------------------------------------ | ----------- | ---------------------------------------------- |
| http://store.company.com/dir2/other.html         | Same origin | Only the path differs                          |
| http://store.company.com/dir/inner/another.html  | Same origin | Only the path differs                          |
| https://store.company.com/page.html	           | Failure     | Different protocol                             |
| http://store.company.com:81/dir/page.html        | Failure     | Different port (http:// is port 80 by default) |
| http://news.company.com/dir/page.html            | Failure     | Different host                                 |
  
## Why is this important?

Assume you are logged into Facebook and visit a malicious website in another browser tab. Without the same origin policy JavaScript on that website could do anything to your Facebook account that you are allowed to do. For example read private messages, post status updates, analyse the HTML DOM-tree after you entered your password before submitting the form.  
  
But of course Facebook wants to use JavaScript to enhance the user experience. So it is important that the browser can detect that this JavaScript is trusted to access Facebook resources. That's where the same origin policy comes into play: If the JavaScript is included from a HTML page on facebook.com, it may access facebook.com resources.  
  
Now replace Facebook with your online banking website, and it will be obvious that this is an issue.  

## Is this always the case, to access only resources on the same origin?

The most prevalent myth about Same-origin Policy is that it plainly forbids a browser to load a resource from a different origin. Though we know that the thing makes today's web technologies so rich and colorful is the content loaded from different origins. The presence of a huge content delivery network (CDN) ecosystem proves this is not true.  
  
Another prevalent myth is that an origin cannot send information to another one. That is also not true. Again we know that an origin can make a request to another one. The information of the forms in one origin can be reached from another origin. If we think of cloud payment systems integrated into a business workflow, these often operate by sending requests to another origin.  Even one of the most common web vulnerabilities, Cross-Site Request Forgery (CSRF), arises from that point. CSRF is possible because of the ability of sites to make requests to each other. This topic will be covered in a separate session more in-depth.  

# CORS

Cross-Origin Resource Sharing (CORS) is a mechanism that uses additional HTTP headers to tell browsers to give a web application running at one origin, access to selected resources from a different origin. A web application executes a cross-origin HTTP request when it requests a resource that has a different origin (domain, protocol, or port) from its own.  
  
An example of a cross-origin request: the front-end JavaScript code served from https://domain-a.com uses XMLHttpRequest (AJAX) to make a request for https://domain-b.com/data.json.  
  
For security reasons, browsers restrict cross-origin HTTP requests initiated from scripts. For example, XMLHttpRequest follows the same-origin policy. This means that a web application using those APIs can only request resources from the same origin the application was loaded from unless the response from other origins includes the right CORS headers.  
  
The CORS mechanism supports secure cross-origin requests and data transfers between browsers and servers. Modern browsers use CORS in APIs such as XMLHttpRequest to mitigate the risks of cross-origin HTTP requests. The CORS header is added by the server to the response.  
  
**CORS Header Syntax:**  
`Access-Control-Allow-Origin: *`  
`Access-Control-Allow-Origin: <origin>`  
`Access-Control-Allow-Origin: null`  
`Access-Control-Allow-Origin: https://developer.mozilla.org`  
  
  
![CORS](https://github.com/hexcellents/sss-web/blob/master/02-web-basics/support/CORS.jpg)  

***

The next two sections are very important from an application standpoint as well as from a security expert point of view. We will briefly introduce them here and we will continue to stress them in the following sessions, as they are the bedrock for multiple types of attacks, such as SQL Injection.  

# Authentication vs Authorization

Two concepts that usually get confused by people are authentication and authorization. Both terms are often used in conjunction with each other when it comes to security and gaining access to the system. They are essential in almost every modern web application, as most of these apps need a way to uniquely identify their users using an account. These accounts can contain both personal information, available only to the logged in user, and public information, available to anybody. Or they can have access to some functionality, such as the possibility to delete other users, to add blog posts, etc.  
  
Fundamentally, authentication refers to **who you are** while authorization refers to **what you can do**.  
  
**Authentication** is the process of verifying the identity of a person or device. A common example is entering a username and password when you log in to a website. Entering the correct login information lets the website know 1) who you are and 2) that it is actually you accessing the website.  
  
There could be other methods of authentication, such as passcodes, biometrics (fingerprints), Two-Factor Authentication, etc. We won’t insist too much on these other methods, but it’s good to know they exist.  
  
**Authorization** is a security mechanism to determine access levels or user/client privileges related to system resources including files, services, computer programs, data and application features. This is the process of granting or denying access to a network resource which allows the user access to various resources based on the user's identity.  
  
## Real scenarios

Now imagine if someone gets access to your Facebook account. Besides the previously public information, such as your name and your birthday, they could now view your friends lists, private conversations, or even post something in your name. However, this situation won’t affect Facebook directly, but it will certainly affect you.  
  
What if someone were to gain access to an administrator account of a university? They could remove all the students, delete their grades and all the study materials. This would be a really nefarious incident that would destroy the institution’s reputation and will also affect you as a student.  
  
This is why authentication and authorization are very important and their security is crucial.

# Encryption vs Hashing vs Encoding vs Obfuscation

Sensitive data is usually encrypted, hashed or encoded, so even if you can get access to it, you may not know what to do with it.  
  
1. **Encryption** is a two-way function. It is used for maintaining data confidentiality and requires the use of a key (kept secret) in order to return to plaintext. Examples of algorithms: _RSA_, _AES_, _Blowfish_.
2. **Hashing** is a one-way function. It is used for validating the integrity of content by detecting all modification thereof via obvious changes to the hash output. Examples of algorithms: _MD5_ (considered insecure now), _SHA-2_, _SHA-3_.
3. **Encoding** is for maintaining data usability and can be reversed by employing the same algorithm that encoded the content, i.e. no key is used. Example of algorithms: _ASCII_, _UTF-8_, _Unicode_, _URL Encoding_, _Base64_.
4. **Obfuscation** is used to prevent people from understanding the meaning of something, and is often used with computer code to help prevent successful reverse engineering and/or theft of a product’s functionality. Examples of tools: Javascript Obfuscator (https://javascriptobfuscator.com/).
  
One might ask when obfuscation would be used instead of encryption, and the answer is that obfuscation is used to make it harder for one entity to understand (like a human) while still being easy to consume for something else (like a computer). With encryption, neither a human or a computer could read the content without a key.  
  
## How is hashing used in web applications?

Also, you may ask what exactly is used for securing passwords. Most web applications hash passwords before storing them in a database, so even if an attacker gets access to them, they would have to perform additional steps to be able to use them. In order to verify a user’s identity, the server would hash the provided password and then try to match the user/password combination of an already stored entry.

## Salting passwords

However, the simple hashing of a password may not always be sufficient. There is something called rainbow tables which consists of a huge database of precomputed hashes for known passwords, and searching for a hash of a word like ‘123456’ or ‘password’ would return the result very quickly. To combat this issue, developers usually add something called a “salt” to passwords before hashing them, which is actually just a random piece of text concatenated to the initial password.

## How is encoding used in web applications?

Base64 encoding schemes are commonly used when there is a need to encode binary data that needs to be stored and transferred over media that are designed to deal with ASCII. This is to ensure that the data remains intact without modification during transport. Base64 is commonly used in a number of applications including email via MIME, and storing complex data in XML. It can also be used to hide things in plain sight, but it’s not a secure method at all, because of  how easy it is to reverse the process and find the initial content.

## How is encryption used in web applications?

The HTTP protocol sends data in clear text. If you want to send confidential information to the server and avoid an eavesdropping attack (someone listening to the communication), you should use HTTPS. Here comes into play the encryption algorithms. The server and the client exchange a certificate before communicating your information, which contains the server’s public key. The client will then use the public key to encrypt every message and the server will use its private key to decrypt that information. As long as the server’s private key is secret, the communication is confidential.

# Wrap-up

In an ever-evolving world, our needs grow rapidly, so we implement new systems to help solve the problems. Because HTTP is stateless, dynamic web applications needed a way to preserve a state between requests, so they used cookies and sessions. Furthermore, that raises the problem of security, so specific headers, such as _http-only_, were needed in order to prevent malicious tampering of cookies from scripts.  
  
Also, modern web applications can be more complex than ever, so it’s a good practice to structure the applications over multiple files, separating the logic. This means that a website will use lots of files, some of which may not be on the same server, and need to be loaded from a different source. Here comes into play the Same-origin policy, which handles the security, and CORS, which handles the exceptions.  
  
Lastly, it’s very important to understand the difference between authentication and authorization and how this process is secured using encryption, hashing and other methods. Almost every web application on the internet today has one form or another of authentication and authorization, which gives the opportunity even to inexperienced people to manage their websites without the need to write code, but with a few clicks.  

# Resources

* [1] https://owasp.org/www-community/attacks/Session_hijacking_attack  
* [2] https://www.netsparker.com/blog/web-security/session-hijacking/
* [3] https://www.netsparker.com/whitepaper-same-origin-policy/  
* [4] https://developer.mozilla.org/en-US/docs/Web/Security/Same-origin_policy  
* [5] https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS  
* [6] https://stackoverflow.com/questions/11142882/what-are-cookies-and-sessions-and-how-do-they-relate-to-each-other  
* [7] https://chrome.google.com/webstore/detail/editthiscookie/fngmhnnpilhplaeedifhccceomclgfbg  
* [8] https://danielmiessler.com/study/encoding-encryption-hashing-obfuscation/  
* [9] https://searchsecurity.techtarget.com/tip/How-to-encrypt-and-secure-a-website-using-HTTPS  

# Exercises

1. Nobody loves me
2. Chef hacky mchack
3. Chocolate
4. Do you need glasses?
5. Santa
6. How's the weather












