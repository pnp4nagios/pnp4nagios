#!/usr/bin/bash

NAME=pnp4nagios
VERSION=$1

dnf install -y epel-release
dnf install -y mock

tar -xf ${NAME}-${VERSION}.tgz ${NAME}-${VERSION}/ci/${NAME}.spec
mv ${NAME}-${VERSION}/ci/${NAME}.spec ./${NAME}.spec.base


RELEASE=$(grep '^Release: ' ${NAME}.spec.base | cut -d ':' -f2 | awk -F'%' '{print $1}' | tr -d ' ')

echo "RELEASE = ${RELEASE}"
mkdir outputs


BREL="${RELEASE}.alma%{?dist}"
sed "/^Release:/c\
Release:        ${BREL}" <${NAME}.spec.base >${NAME}.spec
config='alma+epel-8-x86_64'
mock -r $config  \
     --additional-package=selinux-policy-targeted \
     --additional-package=selinux-policy-devel \
     --additional-package=perl-Time-HiRes \
     --additional-package=rrdtool \
     --spec=${NAME}.spec \
     --sources=. --resultdir=./outputs -N

cp ${NAME}.spec.base ${NAME}.spec
config='fedora-38-x86_64'
mock -v -r $config \
     --additional-package=selinux-policy-targeted \
     --additional-package=selinux-policy-devel \
     --additional-package=perl-Time-HiRes \
     --additional-package=rrdtool \
     --spec=${NAME}.spec \
     --sources=. --resultdir=./outputs -N

ls -lR .
