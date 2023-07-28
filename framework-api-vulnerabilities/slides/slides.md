---
title: "SSS: Session: Framework and API Vulnerabilities"
revealOptions:
  background-color: 'aquamarine'
  transition: 'none'
---

# Framework and API Vulnerabilities

Security Summer School

---

* Library vs Framework (Django, Laravel, ASP.NET)

* CMS (WordPress, Drupal, Joomla, Shopify)

* API

---

## API Vulnerabilities (1)

* Broken Object Level Authorization

* Can request information about an object that you are not supposed to have access to

---

## API Vulnerabilities (2)

* Broken Function Level Authorization

* Can request information from an endpoint that you are not supposed to have access to

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

## CVEs

---

## WPScan
