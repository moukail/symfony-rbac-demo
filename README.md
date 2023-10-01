### Vagrant
```
vagrant up --provision
vagrant up
vagrant reload
vagrant ssh
```

#### Virtualbox Guest Additions

```
vagrant plugin install vagrant-vbguest
vagrant vbguest --do install --no-cleanup
vagrant vbguest --status
```

```
Vagrant was unable to mount VirtualBox shared folders. This is usually
because the filesystem "vboxsf" is not available. This filesystem is
made available via the VirtualBox Guest Additions and kernel module.
Please verify that these guest additions are properly installed in the
guest. This is not a bug in Vagrant and is usually caused by a faulty
Vagrant box. For context, the command attempted was:

mount -t vboxsf -o uid=1000,gid=1000,_netdev var_www_ /var/www

The error output from the command was:

/sbin/mount.vboxsf: mounting failed with the error: No such device
```

### traefik dashboard
https://traefik.app.localhost/dashboard/#/

### mkcert
https://knplabs.com/en/blog/how-to-handle-https-with-docker-compose-and-mkcert-for-local-development/
#### Ubuntu

```bash
sudo apt install mkcert libnss3-tools
mkcert -install
mkcert -cert-file certs/symfony_cert.pem -key-file certs/symfony_key.pem "app.localhost" "*.app.localhost"
openssl pkcs12 -export -out certs/certificate.p12 -inkey certs/symfony_key.pem -in certs/symfony_cert.pem
```

#### Mac