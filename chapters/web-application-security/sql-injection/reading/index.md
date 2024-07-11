---
linkTitle: 04. SQL Injection
type: docs
weight: 10
---

# SQL Injection

In this session, we'll explain what SQL injection is, describe some common examples, explain how to find and exploit various kinds of SQL injection vulnerabilities, and summarize how to prevent SQL injection.

**NOTE:**

For this session you'll need to use [Burp Suite](https://portswigger.net/burp) so please follow the links in the [Further Reading](#further-reading) section to set it up if you haven't done so yet.[[2](https://portswigger.net/support/configuring-your-browser-to-work-with-burp),[3](https://portswigger.net/support/checking-your-browser-proxy-configuration),[4](https://portswigger.net/support/installing-burp-suites-ca-certificate-in-your-browser)]

## What is SQL injection

SQL injection is a web security vulnerability that allows an attacker to interfere with the queries that an application makes to its database.
It generally allows attackers to view data that they are not normally able to retrieve.
This might include data belonging to other users, or any other data that the application itself is able to access.
In many cases, an attacker can modify or delete this data, causing persistent changes to the application's content or behavior.

In some situations, an attacker can escalate an SQL injection attack to compromise the underlying server or other back-end infrastructure or perform a denial-of-service attack.

### What is the impact of a successful SQL injection attack?

A successful SQL injection attack can result in unauthorized access to sensitive data, such as passwords, credit card details, or personal user information.
Many high-profile data breaches in recent years have been the result of SQL injection attacks, leading to reputational damage and regulatory fines.
In some cases, an attacker can obtain a persistent backdoor into an organization's systems, leading to a long-term compromise that can go unnoticed for an extended period.

### SQL injection examples

There are a wide variety of SQL injection vulnerabilities, attacks, and techniques, which arise in different situations.
Some common SQL injection examples include:

- **[Retrieving hidden data](##retrieving-hidden-data)** - you can modify an SQL query to return additional results.
- **[Subverting application logic](##subverting-application-logic)** - you can change a query to interfere with the application's logic.
- **[UNION attacks](##SQL-injection-UNION-attacks)** - you can retrieve data from different database tables.
- **[Examining the database](##Examining-the-database)** - you can extract information about the version and structure of the database.
- **[Blind SQL injection](##Blind-SQL-injection)** - the results of a query you control are not returned in the application's responses.

---

## Retrieving hidden data

Consider a shopping application that displays products in different categories.
When the user clicks on the Gifts category, their browser requests the URL:

`https://insecure-website.com/products?category=Gifts`

This causes the application to make an SQL query to retrieve details of the relevant products from the database:

`SELECT * FROM products WHERE category = 'Gifts' AND released = 1`

This SQL query asks the database to return:

- all details (\*)
- from the products table
- where the category is Gifts
- and released is 1.

The restriction `released = 1` is being used to hide products that are not released.
For unreleased products, presumably `released = 0`.

The application doesn't implement any defenses against SQL injection attacks, so an attacker can construct an attack like:

`https://insecure-website.com/products?category=Gifts'--`

This results in the SQL query:

`SELECT * FROM products WHERE category = 'Gifts'--' AND released = 1`

The key thing here is that the double-dash sequence `--` is a comment indicator in SQL, and means that the rest of the query is interpreted as a comment.
This effectively removes the remainder of the query, so it no longer includes `AND released = 1`.
This means that all products are displayed, including unreleased products.

Going further, an attacker can cause the application to display all the products in any category, including categories that they don't know about:

`https://insecure-website.com/products?category=Gifts'+OR+1=1--`

This results in the SQL query:

`SELECT * FROM products WHERE category = 'Gifts' OR 1=1--' AND released = 1`

The modified query will return all items where either the category is Gifts, or 1 is equal to 1.
Since 1=1 is always true, the query will return all items.

## Subverting application logic

Consider an application that lets users log in with a username and password.
If a user submits the username `wiener` and the password `bluecheese`, the application checks the credentials by performing the following SQL query:

`SELECT * FROM users WHERE username = 'wiener' AND password = 'bluecheese'`

If the query returns the details of a user, then the login is successful.
Otherwise, it is rejected.

Here, an attacker can log in as any user without a password simply by using the SQL comment sequence `--` to remove the password check from the `WHERE` clause of the query.
For example, submitting the username `administrator'--` and a blank password results in the following query:

`SELECT * FROM users WHERE username = 'administrator'--' AND password = ''`

This query returns the user whose username is `administrator` and successfully logs the attacker in as that user.

## Retrieving data from other database tables

In cases where the results of an SQL query are returned within the application's responses, an attacker can leverage an SQL injection vulnerability to retrieve data from other tables within the database.
This is done using the `UNION` keyword, which lets you execute an additional `SELECT` query and append the results to the original query.

For example, if an application executes the following query containing the user input "Gifts":

`SELECT name, description FROM products WHERE category = 'Gifts'`

then an attacker can submit the input:

`' UNION SELECT username, password FROM users--`

This will cause the application to return all usernames and passwords along with the names and descriptions of products.

## SQL injection UNION attacks

When an application is vulnerable to SQL injection and the results of the query are returned within the application's responses, the `UNION` keyword can be used to retrieve data from other tables within the database.
This results in an SQL injection UNION attack.

The `UNION` keyword lets you execute one or more additional `SELECT` queries and append the results to the original query.
For example:

`SELECT a, b FROM table1 UNION SELECT c, d FROM table2`

This SQL query will return a single result set with two columns, containing values from columns `a` and `b` in `table1` and columns `c` and `d` in `table2`.

For a `UNION` query to work, two key requirements must be met:

- The individual queries must return the same number of columns.
- The data types in each column must be compatible between the individual queries.

To carry out an SQL injection UNION attack, you need to ensure that your attack meets these two requirements.
This generally involves figuring out:

- How many columns are being returned from the original query?
- Which columns returned from the original query are of a suitable data type to hold the results from the injected query?

### Determining the number of columns required in an SQL injection UNION attack

When performing an SQL injection UNION attack, there are two effective methods to determine how many columns are being returned from the original query.

The first method involves injecting a series of `ORDER BY` clauses and incrementing the specified column index until an error occurs.
For example, assuming the injection point is a quoted string within the `WHERE` clause of the original query, you would submit:

- `' ORDER BY 1--`
- `' ORDER BY 2--`
- `' ORDER BY 3--`

etc.

This series of payloads modifies the original query to order the results by different columns in the result set.
The column in an `ORDER BY` clause can be specified by its index, so you don't need to know the names of any columns.
When the specified column index exceeds the number of actual columns in the result set, the database returns an error, such as:

`The ORDER BY position number 3 is out of range of the number of items in the select list.`

The application might actually return the database error in its HTTP response, or it might return a generic error, or simply return no results.
Provided you can detect some difference in the application's response, you can infer how many columns are being returned from the query.

The second method involves submitting a series of `UNION SELECT` payloads specifying a different number of null values:

- `' UNION SELECT NULL--`
- `' UNION SELECT NULL,NULL--`
- `' UNION SELECT NULL,NULL,NULL--`

etc.

If the number of nulls does not match the number of columns, the database returns an error, such as:

`All queries combined using a UNION, INTERSECT or EXCEPT operator must have an equal number of expressions in their target lists.`

Again, the application might actually return this error message, or might just return a generic error or no results.
When the number of nulls matches the number of columns, the database returns an additional row in the result set, containing null values in each column.
The effect on the resulting HTTP response depends on the application's code.
If you are lucky, you will see some additional content within the response, such as an extra row on an HTML table.
Otherwise, the null values might trigger a different error, such as a `NullPointerException`.
Worst case, the response might be indistinguishable from that which is caused by an incorrect number of nulls, making this method of determining the column count ineffective.

**NOTE:**

- The reason for using `NULL` as the values returned from the injected `SELECT` query is that the data types in each column must be compatible between the original and the injected queries.
  Since `NULL` is convertible to every commonly used data type, using `NULL` maximizes the chance that the payload will succeed when the column count is correct.
- In Oracle, every `SELECT` query must use the FROM keyword and specify a valid table.
  There is a built-in table in Oracle called `DUAL` which can be used for this purpose.
  So the injected queries in Oracle would need to look like: `' UNION SELECT NULL FROM DUAL--`.
- The payloads described use the double-dash comment sequence `--` to comment out the remainder of the original query following the injection point.
  In MySQL, the double-dash sequence must be followed by a space. Alternatively, the hash character `#` can be used to identify a comment.

For more details of database-specific syntax, see the [SQL injection cheat sheet](https://portswigger.net/web-security/sql-injection/cheat-sheet).

### Finding columns with a useful data type in an SQL injection UNION attack

The reason for performing an SQL injection UNION attack is to be able to retrieve the results from an injected query.
Generally, the interesting data that you want to retrieve will be in string form, so you need to find one or more columns in the original query results whose data type is, or is compatible with, string data.

Having already determined the number of required columns, you can probe each column to test whether it can hold string data by submitting a series of `UNION SELECT` payloads that place a string value into each column in turn.
For example, if the query returns four columns, you would submit:

- `' UNION SELECT 'a',NULL,NULL,NULL--`
- `' UNION SELECT NULL,'a',NULL,NULL--`
- `' UNION SELECT NULL,NULL,'a',NULL--`
- `' UNION SELECT NULL,NULL,NULL,'a'--`

If the data type of a column is not compatible with string data, the injected query will cause a database error, such as:

`Conversion failed when converting the varchar value 'a' to data type int.`

If an error does not occur, and the application's response contains some additional content including the injected string value, then the relevant column is suitable for retrieving string data.

### Using an SQL injection UNION attack to retrieve interesting data

When you have determined the number of columns returned by the original query and found which columns can hold string data, you are in a position to retrieve interesting data.

Suppose that:

- The original query returns two columns, both of which can hold string data.
- The injection point is a quoted string within the `WHERE` clause.
- The database contains a table called `users` with the columns `username` and `password`.

In this situation, you can retrieve the contents of the `users` table by submitting the input:

`' UNION SELECT username, password FROM users--`

Of course, the crucial information needed to perform this attack is that there is a table called `users` with two columns called `username` and `password`.
Without this information, you would be left trying to guess the names of tables and columns.
In fact, all modern databases provide ways of examining the database structure, to determine what tables and columns it contains.

### Retrieving multiple values within a single column

In the preceding example, suppose instead that the query only returns a single column.

You can easily retrieve multiple values together within this single column by concatenating the values together, ideally including a suitable separator to let you distinguish the combined values.
For example, in Oracle you could submit the input:

`' UNION SELECT username || '~' || password FROM users--`

This uses the double-pipe sequence `||` which is a string concatenation operator in Oracle.
The injected query concatenates together the values of the `username` and `password` fields, separated by the `~` character.

The results from the query will let you read all of the usernames and passwords, for example:

```
...
administrator~s3cure
wiener~peter
carlos~montoya
...
```

Note that different databases use different syntax to perform string concatenation.
For more details, see the [SQL injection cheat sheet](https://portswigger.net/web-security/sql-injection/cheat-sheet).

## Examining the database

Following the initial identification of an SQL injection vulnerability, it is generally useful to obtain some information about the database itself.
This information can often pave the way for further exploitation.

You can query the version details for the database.
The way that this is done depends on the database type, so you can infer the database type from whichever technique works.
For example, in Oracle you can execute:

`SELECT * FROM v$version`

You can also determine what database tables exist, and which columns they contain.
For example, on most databases you can execute the following query to list the tables:

`SELECT * FROM information_schema.tables`

### Querying the database type and version

Different databases provide different ways of querying their version.
You often need to try out different queries to find one that works, allowing you to determine both the type and version of the database software.

The queries to determine the database version for some popular database types are as follows:

| Database type    | Query                     |
| ---------------- | ------------------------- |
| Microsoft, MySQL | `SELECT @@version`        |
| Oracle           | `SELECT * FROM v$version` |
| PostgreSQL       | `SELECT version()`        |

For example, you could use a `UNION` attack with the following input:

`' UNION SELECT @@version--`

This might return output like the following, confirming that the database is Microsoft SQL Server, and the version that is being used:

```
Microsoft SQL Server 2016 (SP2) (KB4052908) - 13.0.5026.0 (X64)
Mar 18 2018 09:11:49
Copyright (c) Microsoft Corporation
Standard Edition (64-bit) on Windows Server 2016 Standard 10.0 <X64> (Build 14393: ) (Hypervisor)
```

## Blind SQL injection

In this section, we'll describe what blind SQL injection is, explain various techniques for finding and exploiting blind SQL injection vulnerabilities.

### What is blind SQL injection?

Blind SQL injection arises when an application is vulnerable to SQL injection, but its HTTP responses do not contain the results of the relevant SQL query or the details of any database errors.

With blind SQL injection vulnerabilities, many techniques such as UNION attacks are not effective, because they rely on being able to see the results of the injected query within the application's responses.
It is still possible to exploit blind SQL injection to access unauthorized data, but different techniques must be used.

### Exploiting blind SQL injection by triggering conditional responses

Consider an application that uses tracking cookies to gather analytics about usage.
Requests to the application include a cookie header like this:

`Cookie: TrackingId=u5YD3PapBcR4lN3e7Tj4`

When a request containing a `TrackingId` cookie is processed, the application determines whether this is a known user using an SQL query like this:

`SELECT TrackingId FROM TrackedUsers WHERE TrackingId = 'u5YD3PapBcR4lN3e7Tj4'`

This query is vulnerable to SQL injection, but the results from the query are not returned to the user.
However, the application does behave differently depending on whether the query returns any data.
If it returns data (because a recognized `TrackingId` was submitted), then a "Welcome back" message is displayed within the page.

This behavior is enough to be able to exploit the blind SQL injection vulnerability and retrieve information by triggering different responses conditionally, depending on an injected condition.
To see how this works, suppose that two requests are sent containing the following `TrackingId` cookie values in turn:

`xyz' AND '1'='1`<br/>
`xyz' AND '1'='2`

The first of these values will cause the query to return results, because the injected `AND '1'='1` condition is true, and so the "Welcome back" message will be displayed.
Whereas the second value will cause the query to not return any results, because the injected condition is false, and so the "Welcome back" message will not be displayed.
This allows us to determine the answer to any single injected condition, and so extract data one bit at a time.

For example, suppose there is a table called `Users` with the columns `Username` and `Password`, and a user called `Administrator`.
We can systematically determine the password for this user by sending a series of inputs to test the password one character at a time.

To do this, we start with the following input:

`xyz' AND SUBSTRING((SELECT Password FROM Users WHERE Username = 'Administrator'), 1, 1) > 'm`

This returns the "Welcome back" message, indicating that the injected condition is true, and so the first character of the password is greater than `m`.

Next, we send the following input:

`xyz' AND SUBSTRING((SELECT Password FROM Users WHERE Username = 'Administrator'), 1, 1) > 't`

This does not return the "Welcome back" message, indicating that the injected condition is false, and so the first character of the password is not greater than `t`.

Eventually, we send the following input, which returns the "Welcome back" message, thereby confirming that the first character of the password is `s`:

`xyz' AND SUBSTRING((SELECT Password FROM Users WHERE Username = 'Administrator'), 1, 1) = 's`

We can continue this process to systematically determine the full password for the `Administrator` user.

**NOTE:**

The `SUBSTRING` function is called `SUBSTR` on some types of databases.
For more details, see the [SQL injection cheat sheet](https://portswigger.net/web-security/sql-injection/cheat-sheet).

### Inducing conditional responses by triggering SQL errors

In the preceding example, suppose instead that the application carries out the same SQL query, but does not behave any differently depending on whether the query returns any data.
The preceding technique will not work, because injecting different Boolean conditions makes no difference to the application's responses.

In this situation, it is often possible to induce the application to return conditional responses by triggering SQL errors conditionally, depending on an injected condition.
This involves modifying the query so that it will cause a database error if the condition is true, but not if the condition is false.
Very often, an unhandled error thrown by the database will cause some difference in the application's response (such as an error message), allowing us to infer the truth of the injected condition.

To see how this works, suppose that two requests are sent containing the following `TrackingId` cookie values in turn:

`xyz' AND (SELECT CASE WHEN (1=2) THEN 1/0 ELSE 'a' END)='a`
`xyz' AND (SELECT CASE WHEN (1=1) THEN 1/0 ELSE 'a' END)='a`

These inputs use the `CASE` keyword to test a condition and return a different expression depending on whether the expression is true.
With the first input, the `CASE` expression evaluates to `'a'`, which does not cause any error.
With the second input, it evaluates to `1/0`, which causes a divide-by-zero error.
Assuming the error causes some difference in the application's HTTP response, we can use this difference to infer whether the injected condition is true.

Using this technique, we can retrieve data in the way already described, by systematically testing one character at a time:

`xyz' AND (SELECT CASE WHEN (Username = 'Administrator' AND SUBSTRING(Password, 1, 1) > 'm') THEN 1/0 ELSE 'a' END FROM Users)='a`

### Exploiting blind SQL injection by triggering time delays

In the preceding example, suppose that the application now catches database errors and handles them gracefully.
Triggering a database error when the injected SQL query is executed no longer causes any difference in the application's response, so the preceding technique of inducing conditional errors will not work.

In this situation, it is often possible to exploit the blind SQL injection vulnerability by triggering time delays conditionally, depending on an injected condition.
Because SQL queries are generally processed synchronously by the application, delaying the execution of an SQL query will also delay the HTTP response.
This allows us to infer the truth of the injected condition based on the time taken before the HTTP response is received.

The techniques for triggering a time delay are highly specific to the type of database being used.
On Microsoft SQL Server, input like the following can be used to test a condition and trigger a delay depending on whether the expression is true:

`'; IF (1=2) WAITFOR DELAY '0:0:10'--`
`'; IF (1=1) WAITFOR DELAY '0:0:10'--`

The first of these inputs will not trigger a delay, because the condition `1=2` is false.
The second input will trigger a delay of 10 seconds, because the condition `1=1` is true.

Using this technique, we can retrieve data in the way already described, by systematically testing one character at a time:

`'; IF (SELECT COUNT(username) FROM Users WHERE username = 'Administrator' AND SUBSTRING(password, 1, 1) > 'm') = 1 WAITFOR DELAY '0:0:{delay}'--`

### Second Order SQL Injection

A Second Order Injection is the same as a traditional injection attack but the payload is already stored in the database intentionally placed so that it can be triggered in another area of code.

Let’s look at an example and see how easy it is to exploit this vulnerability.
This is a form of registration that uses parameterization, meaning that a vicious input will not affect the database.

```php
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

Suppose, however, that someone introduces the following structure as a name:

`'; DROP TABLE user; --`

This will not be a problem for this form, however, this MySQL code is in the database, and if another part of the code uses this name it can be executed.
Let's say that we select the user by name use the following code:

```php
$sql = "SELECT * FROM user WHERE username = '{$userName}'";
$stmt = $conn->query($sql);
$user = $stmt->fetch();
```

Because for this piece of code we do not use the parameterization, the code that will be executed will become:

`SELECT * FROM user WHERE username = ''; DROP TABLE user; --';`

### How to prevent **blind** SQL injection attacks?

Although the techniques needed to find and exploit blind SQL injection vulnerabilities are different and more sophisticated than for regular SQL injection, the measures needed to prevent SQL injection are the same regardless of whether the vulnerability is blind or not.

## How to prevent SQL injection

Most instances of SQL injection can be prevented by using parameterized queries (also known as prepared statements) instead of string concatenation within the query.

The following code is vulnerable to SQL injection because the user input is concatenated directly into the query:

```php
String query = "SELECT * FROM products WHERE category = '" + input + "'";
Statement statement = connection.createStatement();
ResultSet resultSet = statement.executeQuery(query);
```

This code can be easily rewritten in a way that prevents the user input from interfering with the query structure:

```php
PreparedStatement statement = connection.prepareStatement("SELECT * FROM products WHERE category = ?");
statement.setString(1, input);
ResultSet resultSet = statement.executeQuery();
```

Parameterized queries can be used for any situation where untrusted input appears as data within the query, including the `WHERE` clause and values in an `INSERT` or `UPDATE` statement.
They can't be used to handle untrusted input in other parts of the query, such as table or column names, or the `ORDER BY` clause.
Application functionality that places untrusted data into those parts of the query will need to take a different approach, such as white-listing permitted input values, or using different logic to deliver the required behavior.

For a parameterized query to be effective in preventing SQL injection, the string that is used in the query must always be a hard-coded constant, and must never contain any variable data from any origin.
Do not be tempted to decide case-by-case whether an item of data is trusted, and continue using string concatenation within the query for cases that are considered safe.
It is all too easy to make mistakes about the possible origin of data, or for changes in other code to violate assumptions about what data is tainted.

# Tools

## Burp Suite

[Burp Suite](https://portswigger.net/burp) is one of the most popular penetration testing and vulnerability finder tools, and is often used for checking web application security.
With this tool you can see all the requests that are executed when interacting with a web application.
You can change the requests to see different behaviors and you can set automatic requests with different parameters.

## SQLmap

[SQLmap](https://sqlmap.org/) is an open source penetration testing tool that automates the process of detecting and exploiting SQL injection flaws and taking over database servers.
It comes with a powerful detection engine, many niche features for the ultimate penetration tester and a broad range of switches lasting from database fingerprinting, over data fetching from the database, to accessing the underlying file system and executing commands on the operating system via out-of-band connections.
You can see some of the features in this recording.
[![asciicast](https://asciinema.org/a/434673.svg)](https://asciinema.org/a/434673)

# Further Reading

**1.** [Burp](https://portswigger.net/burp)  
**2.** [Burp Browser Configuration](https://portswigger.net/support/configuring-your-browser-to-work-with-burp)  
**3.** [Burp Proxy Configuration](https://portswigger.net/support/checking-your-browser-proxy-configuration)  
**4.** [Burp Certificate](https://portswigger.net/support/installing-burp-suites-ca-certificate-in-your-browser)  
**5.** [SQL Cheat Sheet](https://portswigger.net/web-security/sql-injection/cheat-sheet)  
**6.** [SQL Payloads](https://github.com/swisskyrepo/PayloadsAllTheThings/tree/master/SQL%20Injection)  
**7.** [SQLmap](https://sqlmap.org/)

# Activities

**1.** [Demo SQL Injection](https://sss-ctf.security.cs.pub.ro/challenges)
**2.** [Blacklist](https://sss-ctf.security.cs.pub.ro/challenges)
**3.** [Nightmare Store](https://sss-ctf.security.cs.pub.ro/challenges)
**4.** [One by one](https://sss-ctf.security.cs.pub.ro/challenges)
**5.** [T0p S3cr3t](https://sss-ctf.security.cs.pub.ro/challenges)
**6.** [Retrieve hidden data](https://portswigger.net/web-security/sql-injection/lab-retrieve-hidden-data)  
**7.** [Login bypass](https://portswigger.net/web-security/sql-injection/lab-login-bypass)  
**8.** [Determine number of columns](https://portswigger.net/web-security/sql-injection/union-attacks/lab-determine-number-of-columns)  
**9.** [Find column](https://portswigger.net/web-security/sql-injection/union-attacks/lab-find-column-containing-text)  
**10.** [Retrieve data from other tables](https://portswigger.net/web-security/sql-injection/union-attacks/lab-retrieve-data-from-other-tables)  
**11.** [Single column](https://portswigger.net/web-security/sql-injection/union-attacks/lab-retrieve-multiple-values-in-single-column)  
**12.** [What version?](https://portswigger.net/web-security/sql-injection/examining-the-database/lab-querying-database-version-oracle)  
**13.** [More versions](https://portswigger.net/web-security/sql-injection/examining-the-database/lab-querying-database-version-mysql-microsoft)  
**14.** [Conditional responses](https://portswigger.net/web-security/sql-injection/blind/lab-conditional-responses)  
**15.** [Conditional errors](https://portswigger.net/web-security/sql-injection/blind/lab-conditional-errors)  
**16.** [Time delays](https://portswigger.net/web-security/sql-injection/blind/lab-time-delays)  
**17.** [Out-of-band Application Security Testing](https://portswigger.net/web-security/sql-injection/blind/lab-out-of-band)
