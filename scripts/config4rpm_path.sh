#!/bin/bash
# WHAT: Configure pnp4nagios to use rpm pkg's path, instead of /usr/local.
# WHY : To test new code locally without going through whole rpm creation/deployment workflow.
#
# Example output:
#*** Configuration summary for pnp4nagios-0.6.27 08-29-2023 ***
#
#  General Options:
#  -------------------------         -------------------
#  Nagios user/group:                nagios nagios
#  Install directory:                /usr
#  HTML Dir:                         /usr/share/nagios/html/pnp4nagios
#  Config Dir:                       /etc/pnp4nagios
#  Location of rrdtool binary:       /usr/bin/rrdtool Version 1.7.2
#  RRDs Perl Modules:                FOUND (Version 1.7002)
#  RRD Files stored in:              /var/lib/pnp4nagios
#  process_perfdata.pl Logfile:      /var/log/pnp4nagios/perfdata.log
#  Perfdata files (NPCD) stored in:  /var/spool/pnp4nagios
#
#  Web Interface Options:
#  -------------------------         -------------------
#  HTML URL:                         http://localhost/pnp4nagios
#  Apache Config File:               /etc/httpd/conf.d/pnp4nagios.conf
#
#
#  Review the options above for accuracy.  If they look okay,
#  type 'make all' to compile.
#

_name=pnp4nagios
_exec_prfix=/usr
_bindir=/usr/bin
_sbindir=/usr/sbin
_sysconfdir=/etc 
_libexecdir=/usr/libexec
_libdir=/usr/lib64
_localstatedir=/var
_datadir=/usr/share

./configure --build=x86_64-redhat-linux-gnu \
	    --host=x86_64-redhat-linux-gnu \
	    --program-prefix= \
	    --prefix=${_exec_prfix} \
	    --exec-prefix=${_exec_prfix} \
	    --bindir=${_bindir} \
	    --sbindir=${_sbindir} \
	    --sysconfdir=${_sysconfdir} \
	    --datadir=${_datadir} \
	    --includedir=/usr/include \
	    --libdir=${_libexecdir} \
	    --libexecdir=${_libexecdir}  \
	    --localstatedir=${_localstatedir} \
	    --sharedstatedir=/var/lib \
	    --mandir=/usr/share/man \
	    --infodir=/usr/share/info \
	    --libexecdir=/usr/libexec/${_name} \
	    --sysconfdir=/etc/${_name} \
	    --localstatedir=${_localstatedir}/log/${_name} \
	    --datadir=${_datadir}/nagios/html/${_name} \
	    --datarootdir=${_datadir}/nagios/html/${_name} \
	    --with-perfdata-dir=${_localstatedir}/lib/${_name} \
	    --with-perfdata-spool-dir=${_localstatedir}/spool/${_name}
