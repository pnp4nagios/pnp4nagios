#! /bin/sh
# Rerun autotools to get new ./configure
aclocal 
autoconf 
automake --add-missing 
autoreconf -vif

