# Cross-site Scripting (XSS)

## Intro
Cross-site Scripting (XSS) is a web security vulnerability that allows an attacker to inject malicious code, usually JavaScript, in the web browser of the victim. This implies that the attacker can do a lot of damage from stealing data to perform actions on behalf of the user. This type of vulnerability implies that the web application uses the user input in the HTML page that it serves. As oposed to other type of code injections, the malicious code in a XSS will always run on the client side shifting the target from the server hosting the application to the users of the application.

## Impact
The impact of the vulnerability is linked with the functionality that the web application provides. If the app is doesn't have much functionality (e.g. a news website) the attacker might not obtain much, but if it's a banking app, there is certainly something to obtain.

Cross-site Scripting can be used to achieve a lot of things:
* steal data - sensitive data as login credentials or credit card numbers
* hijack the session of the user - get the user's session token and browse the application as it's your account without any credentials
* track user movements - keylogging, taking screenshots of the page
* perform actions on behalf of the user - sending payments to attacker's account
* alter the looks of the HTML page (virtual defacement) - fake the balance of the account while the attacker steals the money
* mining - placing a cryptocurency miner in the page while the user reads a long article
* if that's not enough, you may find more ideas [here](http://www.xss-payloads.com/payloads.html).

## Types
There are mainly three types of XSS:
* Reflected XSS
* Stored XSS
* DOM XSS

### Reflected XSS
Reflected XSS or non-persistent is the simplest form of XSS and occurs when user input is immediately returned by the web application in the HTTP response.

![Reflected XSS Steps](https://github.com/hexcellents/sss-web/blob/master/05-injection-2/support/reflected-xss.png)

1. The attacker crafts a URL containing a malicious string and sends it to the victim.
2. The victim is tricked by the attacker into requesting the URL from the website.
3. The website includes the malicious string from the URL in the response.
4. The victim's browser executes the malicious script inside the response, sending the victim's cookies to the attacker's server.

#### Example
Let's consider a simple application that greets the user with the name passed through the parameter `name` in the URL.
`https://example.com/greet?name=John`
```html
<p>Hello John!</p>
```

Without changing the user input in any way, an attacker might give the following input:

`https://example.com/greet?name=<script>alert(1)</script>`
```html
<p>Hello <script>alert(1)</script>!</p>
```

The attacker now has control to the JavaScript code using a parameter stored in the URL.

### Stored XSS
Stored XSS or persistent XSS happens when user input is stored in the database and it is used non-sanitized in the response HTML pages.

![Persistent XSS Steps](https://github.com/hexcellents/sss-web/blob/master/05-injection-2/support/persistent-xss.png)

1. The attacker uses one of the website's forms to insert a malicious string into the website's database.
2. The victim requests a page from the website.
3. The website includes the malicious string from the database in the response and sends it to the victim.
4. The victim's browser executes the malicious script inside the response, sending the victim's cookies to the attacker's server.

#### Example
A simple example would be an online shop with a review system that would let customers to write text/comments about the products.
One might use it to say `"This product is great! I recommend it!"`
```html
<p>This product is great! I recommend it!</p>
```
But an attacker might use it to send `"<script>alert(1)</script>"`
```html
<p><script>alert(1)</script></p>
```

The difference between this example and the one at Reflected XSS is that the attacker uses a request to send the review that will be stored in the database of the server. Later on, when another user accesses the product page, the code behind the website will get from the database all reviews associated with the product and insert them in the HTML response page.

### DOM XSS
DOM vulnerabilities usually arise when JavaScript code uses data from the user to construct the DOM / HTML page.

![DOM XSS Steps](https://github.com/hexcellents/sss-web/blob/master/05-injection-2/support/dom-based-xss.png)

1. The attacker crafts a URL containing a malicious string and sends it to the victim.
2. The victim is tricked by the attacker into requesting the URL from the website.
3. The website receives the request, but does not include the malicious string in the response.
4. The victim's browser executes the legitimate script inside the response, causing the malicious script to be inserted into the page.
5. The victim's browser executes the malicious script inserted into the page, sending the victim's cookies to the attacker's server.

#### Example
Suppose the following code is used to create a form to let the user choose his/her preferred language. A default language is also provided in the query string, as the parameter “default”.

```html
Select your language:

<select><script>

document.write("<OPTION value=1>"+document.location.href.substring(document.location.href.indexOf("default=")+8)+"</OPTION>");

document.write("<OPTION value=2>English</OPTION>");

</script></select>
```

The page is invoked with a URL such as:

`https://example.com/page.html?default=French`

A DOM Based XSS attack against this page can be accomplished by sending the following URL to a victim:

`https://example.com/page.html?default=<script>alert(1)</script>`


## Note
The three types of XSS presented above are the ones that historically speaking were categorised, but in order to do a categorization a criteria is needed. The types above have no criteria on which they are sepparated, so let's split XSS vulnerabilities by the place where data is used:
* Server XSS
* Client XSS

By doing so, the three types above mentioned will look like this:
![Server vs Client XSS](https://github.com/hexcellents/sss-web/blob/master/05-injection-2/support/server-client-xss.png)


## Defense Mechanisms
1. **Filter input on arrival.** At the point where user input is received, filter as strictly as possible based on what is expected or valid input.
2. **Encode data on output.** At the point where user-controllable data is output in HTTP responses, encode the output to prevent it from being interpreted as active content. Depending on the output context, this might require applying combinations of HTML, URL, JavaScript, and CSS encoding.
3. **Use appropriate response headers.** To prevent XSS in HTTP responses that aren't intended to contain any HTML or JavaScript, you can use the `Content-Type` and `X-Content-Type-Options` headers to ensure that browsers interpret the responses in the way you intend.
4. **Content Security Policy.** Use Content Security Policy (CSP) to reduce the severity of any XSS vulnerabilities that still occur.\
5. **Continuous database scanning."" For stored XSS is common practice to scan the database at regulated intervals, although XSS payloads can be written in multiple formats (base64, binary etc.).

## Other XSS
### Self XSS
Self XSS is a social engineering attack used to gain access to victim's web accounts. The victim is tricked into running malicious JavaScript code into their own browser, in web developer console, which can either exfiltrate data or perform actions on behalf of the user.
This is not a code injection vulnerability as each website is vulnerable to this type of attack. In order to prevent this, websites add warning messages in console. In the image below, there are two examples, from Google Meet and Facebook.

![Self XSS](https://github.com/hexcellents/sss-web/blob/master/05-injection-2/support/self-xss.png)

### Mutated XSS
Mutated XSS happens when the browser tries to fix and rewrite invaild HTML but fails doing so thus executing attacker's code. Because it depends from browser to browser, is extremely hard to detect or sanitize within the websites application logic. At the end of this page you will find a video explaining a mXSS on Google Search.

## Tasks
### 1. DVWA
We will use Damn Vulnerable Web Application (DVWA) for this one. You may install it however you want, just to work, but a simple way to do it is by using Docker. Once you [installed](https://docs.docker.com/engine/install/) docker you must run
```bash
$ docker run --rm -it -p 8080:80 vulnerables/web-dvwa
```
To access the app, go to http://localhost:8080/setup.php and click on `Create/Reset Database`. Login with username `admin` and password `password`.

DVWA has multipe vulnerable components, but today we will use the XSS related ones. The app has 4 levels of security that can be changed from 'DVWA Security' section.
All the DVWA tasks allow you to view the PHP source of the page to get additional info about the back-end script. However, in a real-world scenario you won't have that kind of access, so please use this functionality only after you have finished the task.

The goal of this tasks is to rise an `alert(1)` from JavaScript like this:
![DVWA Alert 1](https://github.com/hexcellents/sss-web/blob/master/05-injection-2/support/dvwa-alert-1.png)

With the security level set on **low** you will have to go through each XSS type. Raise the alert, then change the security level to **medium** and then **high**. After you are done with this, change on **impossible** and click on `View Source`. Why an exploit is not possible in this case? Check the PHP documentation to understand why.

Go thgough each on in the following order: Reflected, Stored, DOM

### 2. XSS Challenges
You have 6 levels to finish here: https://xss-game.appspot.com/

### 3. Even more XSS Challenges
https://alf.nu/alert1

### 4. If you get here
http://prompt.ml/0

https://xss-quiz.int21h.jp/

http://sudo.co.il/xss/

Those should be enough to suck the soul out of you.

## Further reading
* https://owasp.org/www-community/attacks/xss/
* https://portswigger.net/web-security/cross-site-scripting
* [5 Practical Scenarios for XSS Attacks](https://pentest-tools.com/blog/xss-attacks-practical-scenarios/)
* [XSS Exploit Payloads](http://www.xss-payloads.com/payloads.html)
* [XSS Cheat Sheet 1](https://portswigger.net/web-security/cross-site-scripting/cheat-sheet)
* [XSS Cheat Sheet 2](https://owasp.org/www-community/xss-filter-evasion-cheatsheet)
* [Mutated XSS on Google Search](https://www.youtube.com/watch?v=lG7U3fuNw3A)

---

# Cross-Site Request Forgery (CSRF)

## Intro
Cross-site request forgery is a vulnerability in web applications that allows an attacker to trick users into performing actions that they did not intend.

## Impact
Once again, the impact of the vulnerability is linked with the functionality that the web application provides, but it can range from posting a comment on behalf of the user with an outdated 2008 meme to making payments to attacker's wallet to full account takeover.

## How does it work?
For a CSRF attack to be possible, three key conditions must be in place:
* **A relevant action.** There is an action within the application that the attacker has a reason to induce. This might be a privileged action (such as modifying permissions for other users) or any action on user-specific data (such as changing the user's own password).
* **Cookie-based session handling.** Performing the action involves issuing one or more HTTP requests, and the application relies solely on session cookies to identify the user who has made the requests. There is no other mechanism in place for tracking sessions or validating user requests.
* **No unpredictable request parameters.** The requests that perform the action do not contain any parameters whose values the attacker cannot determine or guess. For example, when causing a user to change their password, the function is not vulnerable if an attacker needs to know the value of the existing password.

For example, suppose an application contains a function that lets the user change the email address on their account. When a user performs this action, they make an HTTP request like the following:
```
POST /email/change HTTP/1.1
Host: vulnerable-website.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 30
Cookie: session=yvthwsztyeQkAPzeQ5gHgTvlyxHfsAfE

email=wiener@normal-user.com
```

This meets the conditions required for CSRF:

* The action of changing the email address on a user's account is of interest to an attacker. Following this action, the attacker will typically be able to trigger a password reset and take full control of the user's account.
* The application uses a session cookie to identify which user issued the request. There are no other tokens or mechanisms in place to track user sessions.
* The attacker can easily determine the values of the request parameters that are needed to perform the action.

With these conditions in place, the attacker can construct a web page containing the following HTML:
```html
<html>
  <body>
    <form action="https://vulnerable-website.com/email/change" method="POST">
      <input type="hidden" name="email" value="pwned@evil-user.net" />
    </form>
    <script>
      document.forms[0].submit();
    </script>
  </body>
</html>
```

## Prevention
* Use SameSite cookie attribute (similar to HTTPOnly and Secure)
* Add a CSRF token for each request that changes the state of the application (e.g. changing password, sending messages) and validate it on backend
* Use user interaction based CSRF defense for sensitive actions such as changing credentials: re-authentication, one-time token, captcha
* Use Cross-origin resource sharing (CORS) headers to prevent CSRF from your website
* in-depth list [here](https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html)

If you find an action/request to have a CSRF token don't give up to that so easily, there are a number of ways that improper validation of the token will still result in a CSRF vulnerability:
* Validation of CSRF token depends on request method
* Validation of CSRF token depends on token being present
* CSRF token is not tied to the user session
* CSRF token is tied to a non-session cookie
* CSRF token is simply duplicated in a cookie

## Note
You can combine XSS with CSRF :). If you have two websites that share a part of their user base, you can use a XSS vulnerability in one to abuse a CSRF vulnerability in the other one. For example if a company provides two related services (e.g. email and calendar) you can assume that there is a high probability that a user might have accounts on both of the services, making a Stored XSS in one app a great way to exploit a CSRF in the other one.

## Tasks
### 1. DVWA
Using the same setup as for XSS tasks, go to the CSRF page of DVWA and craft URLs that will change the password for the user accessing it for each security level: **low**, **medium**, **hard**. Why a CSRF is not possible for **impossible**?

### 2. PortSwigger Academy
You have 8 tasks here: https://portswigger.net/web-security/all-labs#cross-site-request-forgery-csrf

## Further reading
* https://portswigger.net/web-security/csrf
* https://owasp.org/www-community/attacks/csrf
* [CSRF Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html)