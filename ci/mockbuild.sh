#!/usr/bin/bash

NAME=pnp4nagios
VERSION=$1

dnf install -y epel-release
dnf install -y mock

tar -xf ${NAME}-${VERSION}.tgz ${NAME}-${VERSION}/dist/${NAME}.spec
mv ${NAME}-${VERSION}/dist/${NAME}.spec .

mkdir outputs

#for config in almalinux-8-x86_64 fedora-38-x86_64 rhel-9-x86_64 

config='almalinux-8-x86_64'
mock -r $config  \
     --spec=${NAME}.spec \
     --sources=. --resultdir=./outputs -N

exit 0
config='fedora-38-x86_64'
mock -r $config \
     --spec=${NAME}.spec \
     --sources=. --resultdir=./outputs -N

