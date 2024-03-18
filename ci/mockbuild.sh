#!/bin/sh

NAME=pnp4nagios
VERSION=$(grep '^Version: ' ${NAME}.spec | cut -d ':' -f2 | awk -F'%' '{print $1}' | tr -d ' ')
RELEASE=$(grep '^Release: ' ${NAME}.spec | cut -d ':' -f2 | awk -F'%' '{print $1}' | tr -d ' ')

dnf install -y epel-release
dnf install -y mock

cp ${NAME}.spec ${NAME}.spec.base

echo "VERSION = ${VERSION}"
echo "RELEASE = ${RELEASE}"
mkdir outputs

# want to use the plain-vanilla pnp4nagios.spec so that
# it's the one that is included in the srpm

BREL="${RELEASE}.alma%{?dist}"
sed "/^Release:/c\
Release:        ${BREL}" <${NAME}.spec.base >${NAME}.spec

config='alma+epel-8-x86_64'
mock -v -r $config  \
     --additional-package=selinux-policy-targeted \
     --additional-package=selinux-policy-devel \
     --additional-package=perl-Time-HiRes \
     --additional-package=rrdtool \
     --spec=${NAME}.spec \
     --sources=${NAME}-${VERSION}.tgz \
     --resultdir=./outputs -N

cp ${NAME}.spec.base ${NAME}.spec
config='fedora-38-x86_64'
mock -v -r $config \
     --additional-package=selinux-policy-targeted \
     --additional-package=selinux-policy-devel \
     --additional-package=perl-Time-HiRes \
     --additional-package=rrdtool \
     --spec=${NAME}.spec \
     --sources=${NAME}-${VERSION}.tgz --resultdir=./outputs -N


ls -lR .
