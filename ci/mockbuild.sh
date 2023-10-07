#!/usr/bin/bash

NAME=pnp4nagios
VERSION=$1

dnf install -y epel-release
dnf install -y mock

tar -xf dist/${NAME}-${VERSION}.tgz ${NAME}-${VERSION}/dist/${NAME}.spec

RELEASE=$(grep 'Release: ' ${NAME}-${VERSION}/${NAME}.spec | cut -d ':' -f2 | awk -F'%' '{print $1}' | tr -d ' ')
echo RELEASE=${RELEASE} >> $GITHUB_ENV

mkdir outputs

config='almalinux-8-x86_64'
dist='el8'
mock -r $config  \
     --define="version_ $VERSION" \
     --spec=${NAME}-${VERSION}/dist/${NAME}.spec \
     --sources=dist/ --resultdir=./outputs -N

exit 0

config='fedora-38-x86_64'
dist='fc38'
mock -r $config --buildsrpm \
     --define="version_ $VERSION" \
     --spec=${NAME}-${VERSION}/${NAME}.spec \
     --sources=. --resultdir=./outputs -N

ls -R

mock -r $config \
     --rebuild outputs/${NAME}-${VERSION}-${RELEASE}.${dist}.src.rpm \
     --define="version_ $VERSION" \
     --resultdir=./outputs -N

ls -R
