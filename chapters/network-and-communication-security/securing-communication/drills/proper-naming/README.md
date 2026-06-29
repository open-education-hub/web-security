# Proper Naming

The server hosts a secret webpage under the domain `smokey.burger.com`, however, the domain does not resolve via public DNS.
The flag is in that `index.html` file.

We need to formulate a request that tricks Nginx into serving the `smokey.burger.com` virtual host.

Nginx relies strictly on the HTTP Host header (or SNI in TLS/HTTPS environments) to determine which server block handles the traffic.
If you browse directly to http://<host>:<port>, the Host header defaults to that address.
Since this doesn't match smokey.burger.com, Nginx falls back to its default configuration, hiding the flag.

Solution script in `./solution/solution.sh`.

Alternative:

We can locally modify the remote hosts file (i.e., `/etc/hosts`):
```
<IP>    smokey.burger.com
```
