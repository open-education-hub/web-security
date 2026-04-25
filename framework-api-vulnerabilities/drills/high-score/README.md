# Name: Web: Framework & API Vulnerabilities: High Score

## Vulnerability

Broken authorization on endpoints accessible for both admins and standard users.

## Exploit

After creating an account and logging in, we notice that there is an endpoint for editing our account data (username and email). We wonder what if we can also update the other fields, such as the score, to be the first in the leaderboard. So we send a request to update our score to the current max + 1, also paying attention that the data is hex encoded.

Moreover, we see a cookie named `isAdmin` with value `false`, and we decide to set it to `true`.

In the end, we visit the leaderboard page and see the flag.

Exploit in `../sol/solution.py`.
