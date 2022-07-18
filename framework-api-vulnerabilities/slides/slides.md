---
title: "SSS: Session: Framework and API Vulnerabilities"
revealOptions:
  background-color: 'aquamarine'
  transition: 'none'
---

# Framework and API Vulnerabilities

Security Summer School

---

* API

* Framework (Django, Laravel, ASP.NET)

* CMS (WordPress, Drupal, Joomla, Shopify)

---

## API Vulnerabilities (1)

* Broken Object Level Authorization

* Can request information from an endpoint that you are not supposed to have access to

---

## API Vulnerabilities (2)

* Broken Function Level Authorization

* Can send requests to endpoints that you should not have access to

---

## API Vulnerabilities (3)

* Broken Authentication

* Can authenticate as another user

---

## API Vulnerabilities (4)

* Lack of Resources & Rate Limiting

* Should implement a maximum rate of requests per minute or something similar

---

## API Vulnerabilities (5)

* Excessive Data Exposure

* Example: requesting an endpoint that returns more data than it's supposed to, without filtering it

---

## API Vulnerabilities (6)

* Improper Assets Management

* Example: new API version, the old one has a security flaw and can still be accessed

---

## API Vulnerabilities (7)

* Insufficient Logging & Monitoring

* Makes it hard to debug problems or security incidents

---

## Framework Vulnerabilities

* Deciding to use a certain framework implies relying on its security

* A new vulnerability may be discovered at some point, therefore regular updates are a good practice

---

## Log4Shell (CVE-2021-44228)

* a software vulnerability in Apache Log4j 2, a popular Java library for logging error messages in applications

* 10/10 CVSS score

* https://apkash8.medium.com/exploiting-the-log4j-vulnerability-cve-2021-44228-4b8d9d5133f6
