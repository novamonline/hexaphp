# Deploying HexaPHP Applications
This document provides guidance on deploying HexaPHP applications to a production environment.

## Building Artifacts
To deploy a HexaPHP application, you first need to build the necessary artifacts. To do this, you can use the composer command to install dependencies and build a package:

```
php run build
```
This will create a package.zip file containing the built artifacts.

## Deployment
To deploy the built artifacts, you can use a tool like scp or rsync to copy the package.zip file to the production environment:

```
php run deploy
```
Once the file is copied to the production environment, you can extract it and start the PHP process:

```
unzip package.zip
cd /path/to/destination php index.php
```
## Monitoring
To monitor a deployed HexaPHP application, you can use a tool like monit or supervisord to ensure that the PHP process is running and restart it if necessary. You can also use logging tools like rsyslog or logrotate to manage logs.