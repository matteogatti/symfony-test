# Symfony3 test

## Overview
This project is a simple Symfony3 application to test the knowledge of the framework


## Requirement

To begin with initialization of environment you need to have installed in your pc:

- Vagrant 1.9.1
- Ansible 2.2.1.0
- Virtualbox 5.1

## Installation

1. First of all initialize vagrant machine `vagrant up`
2. If the provisioning procedure ends without errors edit your `/etc/hosts` and add `192.168.56.111 deploy.madisoft.it`
3. In case of errors in previous step, you can retry with `vagrant up --provision`
4. MySQL default password `pinguino`
5. Project folder `/var/www/deploy`

## Post installation

1. Init vendor `php composer.phar install`
2. Init database `php bin/console doctrine:database:create`
3. Init schema `php bin/console doctrine:schema:update --force`
4. Init cache `php bin/console cache:warmup --env=prod`
