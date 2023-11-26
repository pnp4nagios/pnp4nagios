#                                               -*- Autoconf -*-
# Process this file with autoconf to produce a configure script.

AC_PREREQ([2.61])
AC_INIT([pnp4nagios],[0.6.27],[pnp4nagios-devel@lists.sourceforge.net],[],[https://github.com/pnp4nagios/pnp4nagios])
AC_CONFIG_SRCDIR(src/)
AC_CONFIG_HEADERS(include/config.h:include/config.h.in)

AC_DEFINE([DEFAULT_NAGIOS_USER], [nagios], [Default Nagios User])
AC_DEFINE([DEFAULT_NAGIOS_GROUP], [nagios], [Default Nagios Group])
AC_DEFINE([DEFAULT_HTTPSERVER_USER], [apache], [Default HTTPserver User])
AC_DEFINE([DEFAULT_HTTPSERVER_GROUP], [apache], [Default HTTPserver Group])

#
# release and release_date
# ...gets overwritten by github release-building
#
PACKAGE_RELEASE="4"
PKG_REL_DATE="2023-10-15"
AC_SUBST(PACKAGE_RELEASE)
AC_SUBST(PKG_REL_DATE)
#
#AC_PREFIX_DEFAULT(/usr/local/pnp4nagios)
#AC_PREFIX_DEFAULT()
#
#
AC_SUBST(ac_configure_args)
XML_STRUCTURE_VERSION="4"
AC_SUBST(XML_STRUCTURE_VERSION)

dnl Figure out how to invoke "install" and what install options to use.

AC_PROG_INSTALL
AC_SUBST(INSTALL)

#dnl What OS are we running?
AC_CANONICAL_HOST
#dnl kinda doubt much cross-compiling, but just in case...
AC_CANONICAL_TARGET

AS_IF([test "q$target_cpu" = "qx86_64"],[x64="64"],[x64=""])

dnl Checks for programs.
AC_PROG_CC
AC_PROG_MAKE_SET
AC_PATH_PROG([STRIP],[strip],[true])
AC_PROG_GREP
AC_PATH_PROG(CP,cp)

# Checks for libraries.

# Checks for header files.

AC_HEADER_DIRENT
AC_HEADER_SYS_WAIT
AC_CHECK_HEADERS(dirent.h stdio.h errno.h unistd.h syslog.h signal.h stdlib.h dirent.h string.h pthread.h getopt.h grp.h pwd.h sys/mman.h sys/types.h sys/wait.h sys/stat.h sys/socket.h sys/loadavg.h netinet/in.h fcntl.h limits.h)

# Checks for typedefs, structures, and compiler characteristics.
AC_TYPE_PID_T
AC_TYPE_UID_T
AC_STRUCT_DIRENT_D_TYPE

# Checks for library functions.
AC_FUNC_FORK
AC_CHECK_FUNCS([bzero socket alarm strerror strspn getloadavg])
AC_FUNC_MALLOC
AC_FUNC_STAT
AC_C_CONST



#dnl get the linux variant, to set "layout" default

osrelfile="/etc/os-release"
if test -e "/etc/os-release" 
then
   osrelfile="/etc/os-release"
elif test -e "/usr/lib/os-release"
then
   osrelfile="usr/lib/os-release"
else
   osrelfile=""
fi      
if test "x${osrelfile}" != "x" 
then
    OSID=$(grep -oP '(?<=^ID=).+' $osrelfile | tr -d '"')
    OSLIKE=$(grep -oP '(?<=^ID_LIKE=).+' $osrelfile | tr -d '"')
    if test "${OSLIKE}" != "" ;
    then
        OSLIKE="${OSID} ${OSLIKE}"
    else
        OSLIKE=${OSID}
    fi
else
    OSLIKE=linux
    rfiles=( /etc/redhat-release \
                 /etc/lsb-release \
                 /etc/SuSE-release \
                 /etc/debian_version \
                 /etc/arch-release \
                 /etc/gentoo-release \
                 /etc/slackware-version \
                 /etc/frugalware-release \
                 /etc/altlinux-release \
                 /etc/mandriva-release \
                 /etc/meego-release \
                 /etc/angstrom-version \
           )
    rnames=( fedora debian suse debian arch gentoo slack frugal \
                    alt mandriva meego angstrom )
    
    jrel=0
    while test "${rfiles[$jrel]}" != ""
    do
        if test -e "${rfiles[$jrel]}"
        then
            OSLIKE=${rnames[$jrel]}
            break
        fi
        let jrel++  
    done
fi

layout=default
for dist in $OSLIKE
do
    case "$dist" in
        "rhel" | "redhat" | "fedora" | "centos" )
            layout=rhel
            break
            ;;
        "debian" )
            layout=debian
            break
            ;;
    esac
done

#dnl echo " decided on: layout=$layout"

dnl Layout Methods

AC_ARG_WITH(layout,[  --with-layout=\[default,rhel,debian,old\] sets directory layout],layout=$withval)

case $layout in
	debian)
                prefix="/usr/local"
                exec_prefix=$prefix
                bindir="$prefix/bin"
                sbindir="$prefix/sbin"
                datadir="$prefix/usr/share"
                libdir="${prefix}/lib${x64}/${PACKAGE_NAME}"
		sysconfdir="${prefix}/${PACKAGE_NAME}/etc"
		localstatedir="${prefix}/var/log/${PACKAGE_NAME}"
		libexecdir="${prefix}/usr/lib/${PACKAGE_NAME}/libexec"
		libdir="${prefix}/usr/lib${x64}/${PACKAGE_NAME}"
		datarootdir="${prefix}/${PACKAGE_NAME}/share"
		PERFDATA_LOG="${prefix}/${PACKAGE_NAME}/var/perfdata.log"
		PERFDATA_DIR="${prefix}/${PACKAGE_NAME}/var/perfdata"
		PERFDATA_SPOOL_DIR="${prefix}/${PACKAGE_NAME}/var/spool"
                mandir="${prefix}/man"
	;;
	old)
		prefix="/usr/local/nagios"
                exec_prefix=$prefix
                bindir="$prefix/bin"
                sbindir="$prefix/sbin"
                datadir="$prefix/share"
                libdir="${prefix}/lib${x64}/${PACKAGE_NAME}"
		sysconfdir="${prefix}/etc/pnp"
		localstatedir="${prefix}/var"
		libexecdir="${prefix}/libexec"
		datarootdir="${prefix}/share/pnp"
		libdir="${prefix}/lib/pnp"
		PERFDATA_LOG="${prefix}/var/perfdata.log"
		PERFDATA_DIR="${prefix}/share/perfdata"
		PERFDATA_SPOOL_DIR="${prefix}/var/spool/perfdata"
	;;
	"rhel" | "default" )
               prefix=""
               exec_prefix=$prefix
               bindir="$prefix/usr/bin"
               sbindir="$prefix/usr/sbin"
               datadir="$prefix/usr/share"
               libdir="${prefix}/usr/lib${x64}/${PACKAGE_NAME}"
               sysconfdir="${prefix}/etc/${PACKAGE_NAME}"
	       localstatedir="${prefix}/var/log/${PACKAGE_NAME}"
               libexecdir="${prefix}/usr/libexec/${PACKAGE_NAME}"
               libdir="${prefix}/usr/lib${x64}/${PACKAGE_NAME}"
	       datarootdir="${prefix}/usr/share/nagios/html/${PACKAGE_NAME}"
	       PERFDATA_LOG="${localstatedir}/perfdata.log"
	       PERFDATA_DIR="${localstatedir}/perfdata"
	       PERFDATA_SPOOL_DIR="${localstatedir}/spool"
	       mandir="${prefix}/man"
	;;

#dnl other layouts? netbsd? suse? 
esac
AC_SUBST(layout)


AC_ARG_WITH(sysconfdir,[  --with-sysconfdir=<$sysconfdir> Tell me where I should keep the 'pnp4nagios.cfg' config file],
        sysconfdir=$withval
	   )
SYSCONFDIR="$sysconfdir"
AC_SUBST(SYSCONFDIR)

AC_ARG_WITH(bindir,[ --with-bindir=<$bindir> where most executables are kept],
        bindir=$withval
        )
BINDIR="$bindir"
AC_SUBST(BINDIR)

AC_ARG_WITH(sbindir,[ --with-sbindir=<$sbindir> where system executables are kept],
        sbindir=$withval
        )
SBINDIR="$sbindir"
AC_SUBST(SBINDIR)

AC_ARG_WITH(libdir,[ --with-libdir=<$libdir> pnp4nagios libs are kept ],
        libdir=$withval
        )
AC_SUBST(libdir)


if test -e /etc/selinux/config  ; 
then
    source /etc/selinux/config
    CAN_SELINUX="yes"
    SELINUX_POLICY="$SELINUXTYPE"
else
    CAN_SELINUX="no"
fi

SELINUX="no"
AC_ARG_ENABLE([selinux],
    AS_HELP_STRING([--disable-selinux], [Disable selinux build]))

AS_IF([test "x$enable_selinux" != "xno"], [
  SELINUX=$CAN_SELINUX
])


AC_ARG_WITH(selinux_policy, [ --with-selinux_policy=<selinux policy> selinux policy], SELINUX_POLICY=$withval,SELINUX_POLICY=$SELINUX_POLICY)

AC_SUBST(SELINUX)
AC_SUBST(SELINUX_POLICY)


AC_SUBST(datadir)

AC_ARG_WITH(kohana,[  --without-kohana does not install the kohana framework],KOHANA=no,KOHANA=yes)
AC_SUBST(KOHANA)
AC_ARG_WITH(kohana_system,[  --with-kohana_system=<existing kohana system dir> Points to an already installed kohana framework],KOHANA_SYSTEM=$withval,KOHANA_SYSTEM=$libdir/kohana/system)
AC_SUBST(KOHANA_SYSTEM)

AC_ARG_WITH(nagios_user,[  --with-nagios-user=<user> sets user name to run nagios],nagios_user=$withval,nagios_user=nagios)
AC_ARG_WITH(nagios_group,[  --with-nagios-group=<grp> sets group name to run nagios],nagios_grp=$withval,nagios_grp=nagios)
AC_SUBST(nagios_user)
AC_SUBST(nagios_grp)
AC_DEFINE_UNQUOTED(DEFAULT_NAGIOS_USER,"$nagios_user")
AC_DEFINE_UNQUOTED(DEFAULT_NAGIOS_GROUP,"$nagios_grp")
INSTALL_OPTS="-o $nagios_user -g $nagios_grp"
AC_SUBST(INSTALL_OPTS)


AC_ARG_WITH(http_user,[  --with-http-user=<user> sets user name for http server],http_user=$withval,http_user=apache)
AC_ARG_WITH(http_group,[  --with-http-group=<grp> sets group name for http server],http_grp=$withval,http_grp=apache)
AC_SUBST(http_user)
AC_SUBST(http_grp)
COMMAND_OPTS="-o $http_user -g $http_grp"
AC_SUBST(COMMAND_OPTS)


# Checks for programs.
AC_PATH_PROG(PERL,perl)
# Check for Perl lib path
PERL_LIB_PATH=no
AC_ARG_WITH(perl_lib_path,[  --with-perl_lib_path=<path_to_perl_libs> sets path to rrdtool RRDs perl modules.],PERL_LIB_PATH=$withval,PERL_LIB_PATH=no)
AC_SUBST(PERL_LIB_PATH)
# Check for rrdtool
RRDTOOL=no
AC_ARG_WITH(rrdtool,[  --with-rrdtool=<path_to_rrdtool> sets path to rrdtool],RRDTOOL=$withval,RRDTOOL=no)
if test RRDTOOL=no; then
        AC_PATH_PROG(RRDTOOL,rrdtool)
fi

AC_MSG_CHECKING(rrdtool path $RRDTOOL)
if [ test -d $RRDTOOL ];then
    AC_MSG_RESULT(no)
    AC_MSG_ERROR([$RRDTOOL is a directory! PNP needs the Path to the rrdtool binary!])
fi
AC_MSG_RESULT(yes)
AC_MSG_CHECKING(for executable Bit on $RRDTOOL)
if [ ! test -x $RRDTOOL] ;then
    AC_MSG_RESULT(no)
    AC_MSG_ERROR([$RRDTOOL is not executable!])
fi
AC_MSG_RESULT(yes)

AC_SUBST(RRDTOOL)


AC_ARG_WITH(perfdata-logfile,[  --with-perfdata-logfile=<perfdata_logfile> Tell me where I should store the 'process_perfdata.pl' Logfile],
        PERFDATA_LOG=$withval
	   )
AC_SUBST(PERFDATA_LOG)


AC_ARG_WITH(perfdata-dir,[  --with-perfdata-dir=<path_to_perfdata> Tell me where I should store the RRD Database Files],
	    PERFDATA_DIR=$withval
	   )
AC_SUBST(PERFDATA_DIR)

AC_ARG_WITH(perfdata-spool-dir,[  --with-perfdata-spool-dir=<path_to_perfdata_spool_dir> Tell me where I should store perfdata files for bulk mode with npcd],
	    PERFDATA_SPOOL_DIR=$withval
	   )
AC_SUBST(PERFDATA_SPOOL_DIR)

AC_ARG_WITH(debug,[  --with-debug Enable debuging for process_perfdata.pl],
	    DEBUG="2",
	    DEBUG="0"
	   )
AC_SUBST(DEBUG)

dnl Check for location of Apache conf.d directory
HTTP_CONF=no
AC_ARG_WITH(httpd_conf,[  --with-httpd-conf=<path_to_conf> sets path to Apache conf.d directory],HTTPD_CONF=$withval,HTTPD_CONF=no)
if test x$HTTPD_CONF = xno; then
        if test -d /etc/httpd/conf.d; then
                HTTPD_CONF="/etc/httpd/conf.d"
        elif test -d /etc/apache2/conf.d; then
                HTTPD_CONF="/etc/apache2/conf.d"
        elif test -d /etc/apache/conf.d; then
                HTTPD_CONF="/etc/apache/conf.d"
        else
                HTTPD_CONF="/etc/httpd/conf.d"
        fi
fi
AC_SUBST(HTTPD_CONF)

dnl check for location where Nagios keeps its config

NAGIOS_CFG=no
AC_ARG_WITH(nagios_cfg,[ --with-nagios-cfg=<nagios_cfg_dir> sets path to nagios.cfg file], NAGIOS_CFG=$withval,NAGIOS_CFG=no)
if test x$NAGIOS_CFG = xno; then
   if test -e /etc/nagios/nagios.cfg ; then
      NAGIOS_CFG=/etc/nagios/nagios.cfg
   elif test -e /usr/local/etc/nagios/nagios.cfg ; then
      NAGIOS_CFG=/usr/local/etc/nagios/nagios.cfg
   else
      NAGIOS_CFG=/etc/nagios/nagios.cfg
   fi
fi
AC_SUBST(NAGIOS_CFG)

nagios_dir=`dirname $NAGIOS_CFG`

dnl check for location of Nagios 

NAGIOS_PWD=no
AC_ARG_WITH(nagios_pwd,[ --with-nagios-pwd=<nagios_pwd_file> sets path to Nagios password file], NAGIOS_PWD=$withval,NAGIOS_PWD=no)
if test x$NAGIOS_PWD = xno; then
   if test -e "${nagios_dir}/passwd" ; then
      NAGIOS_PWD="${nagios_dir}/passwd"
   elif test -e "${nagios_dir}/htpasswd.users" ; then
      NAGIOS_PWD="${nagios_dir}/htpasswd.users"
   else
      NAGIOS_PWD="${nagios_dir}/passwd"
   fi
fi
AC_SUBST(NAGIOS_PWD)

NAGIOS_OBJ=no
AC_ARG_WITH(nagios_objects,[ --with-nagios-objects=<nagios_objects_directory> location of nagios object config files], NAGIOS_OBJ=$withval,NAGIOS_OBJ=no)
if test x$NAGIOS_OBJ = xno; then
   NAGIOS_OBJ="${nagios_dir}/objects"
fi
AC_SUBST(NAGIOS_OBJ)

NAGIOS_VER=no
AC_ARG_WITH(nagios_version,[ --with-nagios-version=<nagios_version> nagios version string, like 3.1 etc], NAGIOS_VER=$withval,NAGIOS_VER=no)
if test x$NAGIOS_VER = xno; then
   if which rpm 2>/dev/null ; then
      if rpm -q nagios 2>/dev/null ; then
         NAGIOS_VER=`rpm -q --queryformat='%{VERSION}' nagios`
      fi
   fi
fi
# if nagios not installed yet, probably will install a new version
# so just pick a recent version 
if test x$NAGIOS_VER = xno; then
   if which nagios 2>/dev/null ; then
      NAGIOS_VER=`nagios --version | gawk '/^Nagios /'  | gawk -v RS=' ' '/^[0-9]/'`
   else
      NAGIOS_VER=4.4.6
   fi    
fi
AC_SUBST(NAGIOS_VER)



# Find the right directory to put the root-mode PID file in
AC_MSG_CHECKING([pid file location])
if test -d "/run"
then
	piddir="/run"
elif test -d "/var/run"; then
        piddir="/var/run"
elif test -d "/etc"; then
        piddir="/etc"
fi
PIDDIR="$piddir"
#AC_DEFINE_UNQUOTED([PIDDIR], "$piddir",
#	  [Define to the pid storage directory.])
AC_SUBST(PIDDIR)


dnl Check for location of init scripts
init_dir=/etc/rc.d/init.d
if test -d /etc/rc.d/init.d; then
    init_dir="/etc/rc.d/init.d"
elif test -d /usr/local/etc/rc.d; then
    init_dir="/usr/local/etc/rc.d"
elif test -d /etc/rc.d; then
    init_dir="/etc/rc.d"
elif test -d /etc/init.d; then
    init_dir="/etc/init.d"
elif test -d /sbin/init.d; then
    init_dir="/sbin/init.d"
fi

BASE_URL=${PACKAGE_NAME}
AC_ARG_WITH(base-url,[  --with-base-url=/${PACKAGE_NAME} ],BASE_URL=$withval,BASE_URL=/${PACKAGE_NAME})
AC_SUBST(BASE_URL)



dnl from man 7 daemon to check for systemd stuff
PKG_PROG_PKG_CONFIG()
AC_ARG_WITH([systemdsystemunitdir],
     [AS_HELP_STRING([--with-systemdsystemunitdir=DIR], [Directory for systemd service files])],,
     [with_systemdsystemunitdir=auto])
AS_IF([test "x$with_systemdsystemunitdir" = "xyes" -o "x$with_systemdsystemunitdir" = "xauto"], [
     def_systemdsystemunitdir=$($PKG_CONFIG --variable=systemdsystemunitdir systemd)

     AS_IF([test "x$def_systemdsystemunitdir" = "x"],
          [AS_IF([test "x$with_systemdsystemunitdir" = "xyes"],
          [AC_MSG_ERROR([systemd support requested but pkg-config unable to query systemd package])])
              with_systemdsystemunitdir=no],
              [with_systemdsystemunitdir="$def_systemdsystemunitdir"])])
AS_IF([test "x$with_systemdsystemunitdir" != "xno"],
    [AC_SUBST([systemdsystemunitdir], [$with_systemdsystemunitdir])])

AM_CONDITIONAL([HAVE_SYSTEMD], [test "x$with_systemdsystemunitdir" != "xno"])

dnl autoconf resets locale, so can't use LC_PAPER
PAPERSIZE=no
AC_ARG_WITH(papersize,[  --with-papersize=<paper_size>  paper size to use A4 or letter],PAPERSIZE=$withval,PAPERSIZE=no)
if test x${PAPERSIZE} = xno ; then
   if locale|grep LANG|grep -q en_US ; then
      PAPERSIZE=letter
   else
      PAPERSIZE=A4
   fi
fi
AC_SUBST(PAPERSIZE)


NAGIOS_OBJ=no
AC_ARG_WITH(nagios_objects,[ --with-nagios-objects=<nagios_objects_directory> location of nagios object config files], NAGIOS_OBJ=$withval,NAGIOS_OBJ=no)
if test x$NAGIOS_OBJ = xno; then
   NAGIOS_OBJ="${nagios_dir}/objects"
fi





dnl User can override init script location
AC_ARG_WITH(init_dir,[  --with-init-dir=<path> sets directory to place init script into],init_dir=$withval) 
AC_SUBST(init_dir)

pnpsender_name=pnpsender
AC_SUBST(pnpsender_name)

npcd_name=npcd
AC_SUBST(npcd_name)

pp_pl_name=process_perfdata.pl
AC_SUBST(pp_pl_name)

dnl - Modified version from www.erlang.org
dnl - Some 12/15/05 mods made after reading http://xaxxon.slackworks.com/phuku/dl.html
AC_MSG_CHECKING(for linker flags for loadable modules)
case $host_os in
	solaris2*|sysv4*)
		MOD_LDFLAGS="-G"
	;;
	aix4*|aix5*|aix6*|aix7*)
		#MOD_LDFLAGS="-G -bnoentry -bexpall"
		MOD_LDFLAGS="-shared -Wl,-G -Wl,-bM:SRE -Wl,-bnoentry -Wl,-bexpall"
	;;
	freebsd2*)
		# Non-ELF GNU linker
		MOD_LDFLAGS="-Bshareable"
	;;
	darwin*)
		# Mach-O linker, a shared lib and a loadable
		# object file is not the same thing.
		MOD_LDFLAGS="-bundle -flat_namespace -undefined suppress"
		MOD_CFLAGS="$MOD_CFLAGS -fno-common"
	;;
	linux* | k*bsd*-gnu*)
		# assume GNU linker and ELF
		MOD_LDFLAGS="-shared"
		MOD_CFLAGS="-fPIC"
	;;
	*)
		# assume GNU linker and ELF
		MOD_LDFLAGS="-shared"
	;;
esac
AC_MSG_RESULT([$MOD_LDFLAGS])
AC_SUBST(MOD_CFLAGS)
AC_SUBST(MOD_LDFLAGS)

#
# Checking for Perl Modules
#
AC_MSG_CHECKING(for Perl Module Time::HiRes)
$PERL -MTime::HiRes -e exit >/dev/null 2>&1
if test $? -ne 0; then
    AC_MSG_RESULT(no)
    AC_MSG_ERROR(Perl Module Time::HiRes not available)
else
    AC_MSG_RESULT(yes)
fi

AC_MSG_CHECKING(for Perl Module Getopt::Long)
$PERL -MGetopt::Long -e exit >/dev/null 2>&1
if test $? -ne 0; then
    AC_MSG_RESULT(no)
    AC_MSG_ERROR(Perl Module Getopt::Long not available)
else
    AC_MSG_RESULT(yes)
fi

RRDS=0
AC_MSG_CHECKING(for optional Perl Module RRDs)
$PERL -I${PERL_LIB_PATH} -MRRDs -e exit >/dev/null 2>&1
if test $? -ne 0; then
    AC_MSG_RESULT(no)
    AC_MSG_WARN(Perl Module RRDs not available)
    RRDS=0
else
    AC_MSG_RESULT(yes)
    RRDS=1
fi
AC_SUBST(RRDS)

AC_CONFIG_FILES( subst Makefile share/Makefile lib/Makefile scripts/Makefile src/Makefile sample-config/Makefile man/Makefile )
AC_CONFIG_FILES([include/stamp-h], [date -Is > include/stamp-h])
AC_CONFIG_FILES(scripts/rrd_modify.pl scripts/rc.npcd scripts/rc.pnp_gearman_worker)
AC_CONFIG_FILES(scripts/npcd.service scripts/pnp_gearman_worker.service)
AC_CONFIG_FILES(scripts/check_pnp_rrds.pl share/pnp/index.php share/pnp/install.php)
AC_CONFIG_FILES(sample-config/httpd.conf sample-config/lighttpd.pnp4nagios.conf)
AC_CONFIG_FILES(sample-config/nagios.cfg-sample)
AC_CONFIG_FILES(sample-config/nginx.pnp4nagios.conf)
AC_CONFIG_FILES(sample-config/README_config.md)
AC_CONFIG_FILES(sample-config/pnp/config.php)
AC_CONFIG_FILES(sample-config/pnp-synchronous.cfg-sample)
AC_CONFIG_FILES(sample-config/pnp-bulk.cfg-sample)
AC_CONFIG_FILES(sample-config/pnp-npcd.cfg-sample)
AC_CONFIG_FILES(sample-config/pnp/npcd.cfg-sample)
AC_CONFIG_FILES(sample-config/pnp/process_perfdata.cfg-sample)
AC_CONFIG_FILES(sample-config/pnp/pnp4nagios_release contrib/ssi/status-header.ssi)
AC_CONFIG_FILES(sample-config/NagiosCfgMod.pl)
AC_CONFIG_FILES(sample-config/SuggestInstall)
AC_CONFIG_FILES(man/npcd.8 ci/pnp4nagios.spec)

AC_OUTPUT



# $PERL subst scripts/rrd_modify.pl 
# $PERL subst scripts/rc.npcd 
# $PERL subst scripts/rc.pnp_gearman_worker 
# $PERL subst scripts/check_pnp_rrds.pl 
# $PERL subst share/pnp/index.php 
# $PERL subst share/pnp/install.php 
# $PERL subst sample-config/httpd.conf
# $PERL subst sample-config/lighttpd.pnp4nagios.conf
# $PERL subst sample-config/nginx.pnp4nagios.conf
# $PERL subst sample-config/nagios.cfg-sample
# $PERL subst sample-config/misccommands.cfg-sample 
# $PERL subst sample-config/pnp/config.php 
# $PERL subst sample-config/pnp/npcd.cfg-sample 
# $PERL subst sample-config/pnp/process_perfdata.cfg-sample
# $PERL subst sample-config/pnp/pnp4nagios_release
# $PERL subst contrib/ssi/status-header.ssi 
# $PERL subst man/npcd.8
# 
#### need to use 'subst' instead of configure for this
#### to deal with 'use lib "no"' case 
$PERL subst summary
$PERL subst scripts/process_perfdata.pl 
$PERL subst scripts/rrd_convert.pl 
$PERL summary
#

