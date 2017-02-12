# Madisoft backend-developer-test

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