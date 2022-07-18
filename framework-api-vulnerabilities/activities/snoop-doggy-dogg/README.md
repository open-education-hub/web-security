# Name: Web: Framework & API Vulnerabilities: Snoop Doggy Dog

## Vulnerability

Another API version with broken authorization.

## Exploit

By clicking the third `Buy now` button on the homepage, it leads to `/dronewalkv125.html`.

Here, there is a comment in the source code leading to the script `form/js/drone_version_check.js`.

Here, there is a hardcoded hex string which, echoed in the browser console, says "Previous drone walk version: 109". This leads to accessing the file `/dronewalkv109.html`.

Here, there is a link to `/ya6sb1bfhfyacuyt.html`, where there is a link to an image, `images/running-dogs-flag.jpg`, which displays the flag.

Exploit in `../sol/solution.sh`.