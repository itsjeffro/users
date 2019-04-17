# Users

## Redis Session Handler

The Redis session handler is currently being utilised and requires the following to be install on the server:
* redis-server
* php-redis

### Server Installation

```
sudo apt-get install redis-server
```

You may restart the redis-server and then test that it is running with...

```
redis-cli ping
```

Next you will need to install php-redis and then reload the server's php service after php-redis has been successfully installed.
```
sudo apt-get install php-redis
```

### Checking sessions

To check the keys potentially created after logging in, use KEY __pattern__. This will list out available keys.
```
KEYS *
```

To check the session assigned to a known key, use...
```
MGET sf_sj5mmdupenma6lbdvl4qr6236f1
```