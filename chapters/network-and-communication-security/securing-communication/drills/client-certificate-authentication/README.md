# Client Certificate Authentication

Connect via HTTPS to a https://141.85.224.102:31443.
Use client certificate authentication to retrieve the flag.

See the `../../media/client-certificate-authentication/` folder for details.

## Deploy
```
https://jamielinux.com/docs/openssl-certificate-authority/create-the-root-pair.html

./create-ca
./create-cert <subject_name>
```

We deploy a server for which we generate a CA and a client certificate signed by the same CA.

The client subject name must be equal to the host name, e.g., `127.0.0.1`.

We provide the participants the CA certificate and its key, as well as the OpenSSL config file.
They need to create their client certificate and sign it with the same authority as that of the server.

The CA key is protected by a password, that they can obtain by inspecting the `../../media/client-certificate-authentication/` folder in the repository: `sss-web-ca`.

## Solution

Solution script in `sol/solution.sh`, but it needs to be updated, i.e., create the client certificate from the files provided in the archive given to the participants.

If they manage to successfully connect, they will be prompted the flag.
