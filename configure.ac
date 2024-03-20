#        -*- Autoconf -*-
# Process this file with autoconf to produce a configure script.

# Disable caching
define([AC_CACHE_LOAD],)
define([AC_CACHE_SAVE],)

AC_PREREQ([2.61])

AC_INIT([pnp4nagios],[0.6.27],[pnp4nagios-devel@lists.sourceforge.net],[],[https://github.com/pnp4nagios/pnp4nagios])
PKG_NAME="$PACKAGE_NAME"
PKG_VERSION="$PACKAGE_VERSION"

#
# release and release_date
# ...gets overwritten by github release-building
#        
PACKAGE_RELEASE="4"
PKG_REL_DATE="2023-10-15"
AC_SUBST(PACKAGE_RELEASE)
AC_SUBST(PKG_REL_DATE)

AC_CONFIG_SRCDIR(src/)
AC_CONFIG_HEADERS(include/config.h:include/config.h.in)

#
if echo $PATH | grep -qv '/sbin' ; then
  echo "PATH variable doesn't include sbin, probable failure"
  exit 1
fi
#
AC_SUBST(ac_configure_args)
XML_STRUCTURE_VERSION="4"
AC_SUBST(XML_STRUCTURE_VERSION)

DEBUG_LEVEL=0
AC_ARG_WITH(debug_level, [ --with-debug_level=<debuglevel> ],
DEBUG_LEVEL=$withval, DEBUG_LEVEL=0)
if test $DEBUG_LEVEL -gt 0 ; then
CFLAGS="${CFLAGS} -O0 -ggdb3 -g3"
fi
AC_SUBST(DEBUG_LEVEL)

#dnl What OS are we running?
AC_CANONICAL_HOST
#dnl kinda doubt much cross-compiling, but just in case...
AC_CANONICAL_TARGET

dnl Get Nagios autoconf-macros
AC_PREFIX_DEFAULT(/usr/local/pnp4nagios)
INIT_PROG=npcd
AX_NAGIOS_GET_OS
AX_NAGIOS_GET_DISTRIB_TYPE
AX_NAGIOS_GET_INIT

dnl permissions for 'init' files
if test $init_type = systemd ; then
   INIT_PERM=0644
else
   INIT_PERM=0755
fi
AC_SUBST(INIT_PERM)


AX_NAGIOS_GET_PATHS
AX_NAGIOS_GET_FILES
dnl undo some of the AX_NAGIOS_GET_PATHS problem with netbsd
if test $opsys = bsd -a $dist_type = netbsd ; then
   sysconfdir="${prefix}/etc"
   pkgsysconfdir="${sysconfdir}/pnp4nagios"
   datarootdir="${datarootdir}/pnp4nagios"
   libdir="${prefix}/lib/pnp4nagios"
   libexecdir="${prefix}/libexec/pnp4nagios"
   if test $initdiroverridden = no -a $init_type = newbsd ; then
      initdir=/etc/rc.d
   fi
fi


# for some reason AX_NAGIOS_GET_PATHS doesn't AC_SUBST this...
AC_SUBST(datadir)

dnl Checks for programs.
AC_PROG_INSTALL
dnl netbsd (and others?) uses different switch for suffix
dnl -S for GNU variety install, taken as default
INSTALL_SUFFIX="-S"
dnl -B for netbsd
inbk=`install --help 2>&1|grep -o -e '-B suffix'|head -n 1`
if test "$inbk" = "-B suffix" ; then
  INSTALL_SUFFIX="-B"
fi
AC_SUBST(INSTALL_SUFFIX)

AC_PROG_CC
AC_PROG_MAKE_SET
AC_PATH_PROG([STRIP],[strip],[true])
AC_PROG_GREP
AC_PROG_EGREP
AC_PROG_AWK
AC_PROG_SED
AC_PROG_LN_S
AC_PATH_PROG(CP,cp)
AC_PATH_PROG(PERL,perl)

eval sbindir=$sbindir

# Checks for libraries.
AS_IF([test "q$target_cpu" = "qx86_64"],[x64="64"],[x64=""])

if test x$libdir = x ; then
   if test -d "/usr/lib${x64}" ; then
      libdir="${prefix}/usr/lib${x64}/${PACKAGE_NAME}"
   elif test -d "/lib${x64}"; then
      libdir="${prefix}/lib${x64}/${PACKAGE_NAME}"
   elif test -d "/usr/lib" ; then
      libdir="${prefix}/usr/lib/${PACKAGE_NAME}"
   else
      libdir="${prefix}/lib/${PACKAGE_NAME}"
   fi
fi
AC_ARG_WITH(libdir,[ --with-libdir=<$libdir> pnp4nagios libs are kept ],
        libdir=$withval
        )
AC_SUBST(libdir)

# Check for Perl lib path
PERL_LIB_PATH=no
AC_ARG_WITH(perl_lib_path,[  --with-perl_lib_path=<path_to_perl_libs> sets path to rrdtool RRDs perl modules.],PERL_LIB_PATH=$withval,PERL_LIB_PATH=no)
AC_SUBST(PERL_LIB_PATH)

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

AC_ARG_WITH(kohana,[  --without-kohana does not install the kohana framework],KOHANA=no,KOHANA=yes)
AC_SUBST(KOHANA)
AC_ARG_WITH(kohana_system,[  --with-kohana_system=<existing kohana system dir> Points to an already installed kohana framework],KOHANA_SYSTEM=$withval,KOHANA_SYSTEM="${datarootdir}/kohana/system")
eval KOHANA_SYSTEM="${KOHANA_SYSTEM}"
AC_SUBST(KOHANA_SYSTEM)

# Checks for library functions.
AC_FUNC_FORK
AC_CHECK_FUNCS([bzero socket alarm strerror strspn getloadavg])
AC_FUNC_MALLOC
AC_FUNC_STAT
AC_C_CONST


# Checks for header files.

AC_HEADER_DIRENT
AC_HEADER_SYS_WAIT
AC_CHECK_HEADERS(dirent.h stdio.h errno.h unistd.h syslog.h signal.h stdlib.h dirent.h string.h pthread.h getopt.h grp.h pwd.h sys/mman.h sys/types.h sys/wait.h sys/stat.h sys/socket.h sys/loadavg.h netinet/in.h fcntl.h limits.h)

# Checks for typedefs, structures, and compiler characteristics.
AC_TYPE_PID_T
AC_TYPE_UID_T
AC_STRUCT_DIRENT_D_TYPE


dnl decide on startup files, depending on distro

if test "x$initdiroverridden" != "xyes"; then
       initname="npcd"
fi

#initname=`echo "$initname" | ${SED} "s#${PKG_NAME}#npcd#"`
initconf=`echo "$initconf" | ${SED} "s#${pkgsysconfdir}#npcd.cfg#"`

case $src_init in
    default-service)
        src_init=npcd.service
        initname=npcd.service        
        ;;
    default-init)
        src_init=rc.npcd
        ;;
    newbsd-init)
        src_init=newbsd.npcd
        ;;
    openrc-init)
       src_init=openrc.npcd
       ;;
    upstart-init)
       src_init=upstart.npcd
       ;;
    *)
       AC_MSG_ERROR([missing an init file for npcd on this distro])
      ;;
esac


if test "x$spooldir" = "xN/A" ; then
   spooldir="$localstatedir/spool/pnp4nagios"
fi
lockfile=`echo "${lockfile}"|${SED} "s#${localstatedir}#npcd.lock#"`

logdir=`echo "${logdir}"|${SED} 's#/log$##'`
logdir="${logdir}/log"
eval logdir="$logdir"

case $host_os in
*bsd*|darwin*)
        root_grp=wheel
        ;;
*)
        root_grp=root
        ;;
esac

AC_ARG_WITH(root_group,AS_HELP_STRING([--with-root-group=<grp>],[sets group name for installing init]),root_grp=$withval)
AC_SUBST(root_grp)
INIT_OPTS="-o root -g $root_grp"
AC_SUBST(INIT_OPTS)


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

dnl ======== SElinux info ===============

AC_MSG_CHECKING(selinux capability)
if test -e /etc/selinux/config  ; 
then
    source /etc/selinux/config
    CAN_SELINUX="yes"
    SELINUX_POLICY="$SELINUXTYPE"
    AC_MSG_RESULT(yes)
else
    CAN_SELINUX="no"
    AC_MSG_RESULT(no)
fi

SELINUX="no"
AC_ARG_ENABLE([selinux],
    AS_HELP_STRING([--disable-selinux], [Disable selinux build]))

AS_IF([test "x$enable_selinux" != "xno"], [
  SELINUX=$CAN_SELINUX
])

SELINUX_DEVELDIR="no"
if test "x${SELINUX}" = "xyes" ; then
    AC_ARG_WITH(selinux_develdir,
    [ --with-selinux_develdir=<location of selinux policy Makefile],
    SELINUX_DEVELDIR=$withval,SELINUX_DEVELDIR=no)
    if test "x${SELINUX_DEVELDIR}" = "xno" ; then
       if test -e "/usr/share/selinux/devel/Makefile" ; then
          SELINUX_DEVELDIR="/usr/share/selinux/devel"
       elif test -e "/usr/share/selinux/mcs/include/Makefile" ; then
          SELINUX_DEVELDIR="/usr/share/selinux/mcs/include"
       elif test -e "/usr/share/doc/selinux-policy-dev/examples/Makefile" ; then
          SELINUX_DEVELDIR="/usr/share/doc/selinux-policy-dev/examples"
       else
          AC_MSG_ERROR([Directory with selinux policy Makefile not fund, use --with-selinux_develdir to specify])
       fi
    fi
fi    
   
AC_SUBST(SELINUX)
AC_SUBST(SELINUX_DEVELDIR)

dnl conditional in Makefile
SE=""
if test $SELINUX = no ; then
  SE=['#']
fi
AC_SUBST(SE)

dnl ================ Nagios configuration info ============
dnl check for location where Nagios keeps its config

dnl user inputs override info from system
AC_ARG_WITH(nagios_cfg,[ --with-nagios-cfg=<nagios_cfg_dir> sets path to nagios.cfg file], NAGIOS_CFG=$withval, NAGIOS_CFG=no)

AC_ARG_WITH(nagios_version,[ --with-nagios-version=<nagios_version> nagios version string, like 3.1 etc], NAGIOS_VER=$withval, NAGIOS_VER=no)

NAGIOS_EXE=no

if test x$NAGIOS_CFG = xno -a x$NAGIOS_VER = xno ; then
    AC_MSG_CHECKING(Look for running nagios server)
    rnag=`ps -A -o command | ${EGREP} '/nagios4?( |$)' | head -n 1 | ${AWK} '{print $1;}'`
    
    if test x$rnag = x ; then
       AC_MSG_RESULT([not seen])
    else
       AC_MSG_RESULT($rnag)
       ncfg=`ps -A -o command | ${EGREP} '/nagios4? +.*\.cfg' | head -n 1 | ${AWK} -v RS=' ' '/\.cfg/{print $1;}'`
       if test x$ncfg != x ; then
          NAGIOS_CFG=$ncfg
       fi 
    fi
    
    AC_MSG_CHECKING(Nagios executable)
    if test x$rnag = x ; then
       rnag=`which nagios 2>/dev/null`
       if test x$rnag = x ; then
           rnag=`which nagios4 2>/dev/null`
       fi
    fi
    if test x$rnag = x ; then
       rnag=no
    fi
    NAGIOS_EXE=$rnag
    AC_MSG_RESULT($NAGIOS_EXE)
    
    AC_MSG_CHECKING(Nagios config file location)
    
    if test x$NAGIOS_CFG = xno; then
       if test -e /etc/nagios/nagios.cfg ; then
          NAGIOS_CFG=/etc/nagios/nagios.cfg
       elif test -e /etc/nagios4/nagios.cfg ; then
          NAGIOS_CFG=/etc/nagios4/nagios.cfg
       elif test -e /usr/local/etc/nagios/nagios.cfg ; then
          NAGIOS_CFG=/usr/local/etc/nagios/nagios.cfg
       fi
    fi
    if test x${NAGIOS_CFG} = xno ; then
        NAGIOS_CFG=/etc/nagios/nagios.cfg
        AC_MSG_RESULT([Not found, forced to $NAGIOS_CFG])
    else               
        AC_MSG_RESULT($NAGIOS_CFG)
    fi

    AC_MSG_CHECKING(Nagios version)
    # if nagios not installed yet, probably will install a new version
    # so just pick a recent version unless one is given in arguments
    
    if test x$NAGIOS_VER = xno; then   
       if test x$NAGIOS_EXE != xno ; then
          NAGIOS_VER=`${NAGIOS_EXE} --help | ${AWK} '/^Nagios /'  | ${AWK} -v RS=' ' '/^[[0-9]]/'`
          AC_MSG_RESULT($NAGIOS_VER)
       else
          NAGIOS_VER=4.4.6
          AC_MSG_RESULT([not found, assuming $NAGIOS_VER])
       fi    
    else
       AC_MSG_RESULT([forced use of version $NAGIOS_VER])
    fi
fi
AC_SUBST(NAGIOS_CFG)
AC_SUBST(NAGIOS_VER)

dnl check for service/init for nagios
AC_ARG_WITH(nagios_unit,[ --with-nagios_unit=<nagios_unit_name> sets name of unit for nagios service], NAGIOS_UNIT=$withval,NAGIOS_UNIT=no)
if test x$NAGIOS_UNIT = xno; then
    nagunit=''
    case $init_type in
         systemd)
            nagunit=`systemctl list-units -q 'nagios*.service'|${AWK} '{print $1;}'`
            ;;
         sysv)
            nagunit=`chkconfig --list 2>/dev/null|${GREPE} 'nagios.?\s+0'|${AWK} '{print $1;}'`
            ;;
# not sure how to do other init_types
    esac
    if test "x$nagunit" != x; then
       NAGIOS_UNIT=$nagunit
    fi
fi
NAGIOS_UNIT=${NAGIOS_UNIT%.service}
AC_SUBST(NAGIOS_UNIT)


nagios_dir=`dirname $NAGIOS_CFG`

dnl check for location of Nagios password file

AC_MSG_CHECKING(Nagios password file)
NAGIOS_PWD=no
AC_ARG_WITH(nagios_pwd,[ --with-nagios-pwd=<nagios_pwd_file> sets path to Nagios password file], NAGIOS_PWD=$withval,NAGIOS_PWD=no)
if test x$NAGIOS_PWD = xno; then
   if test -e "${nagios_dir}/passwd" ; then
      NAGIOS_PWD="${nagios_dir}/passwd"
      AC_MSG_RESULT($NAGIOS_PWD)
   elif test -e "${nagios_dir}/htpasswd.users" ; then
      NAGIOS_PWD="${nagios_dir}/htpasswd.users"
      AC_MSG_RESULT($NAGIOS_PWD)
   elif test -e "${nagios_dir}/htdigest.users" ; then
      NAGIOS_PWD="${nagios_dir}/htdigest.users"
      AC_MSG_RESULT($NAGIOS_PWD)
   else
      NAGIOS_PWD="${nagios_dir}/passwd"
      AC_MSG_RESULT([not found, assume default $NAGIOS_PWD])
   fi
else
   AC_MSG_RESULT([forced to $NAGIOS_PWD])
fi
AC_SUBST(NAGIOS_PWD)

dnl directory for nagios "object" files
AC_MSG_CHECKING(Nagios objects directory)
NAGIOS_OBJ=no
AC_ARG_WITH(nagios_objects,[ --with-nagios-objects=<nagios_objects_directory> location of nagios object config files], NAGIOS_OBJ=$withval,NAGIOS_OBJ=no)
if test x$NAGIOS_OBJ = xno; then
   if test -e "${nagios_dir}/objects" ; then
       NAGIOS_OBJ="${nagios_dir}/objects"
       AC_MSG_RESULT($NAGIOS_OBJ)
   else
       NAGIOS_OBJ="${nagios_dir}/objects"
       AC_MSG_RESULT([not found, assume default $NAGIOS_OBJ])
   fi
else
   AC_MSG_RESULT([forced to $NAGIOS_OBJ])
fi
AC_SUBST(NAGIOS_OBJ)


AC_MSG_CHECKING(Nagios user/group)
nagios_user=no
nagios_grp=no
if test -e "$NAGIOS_CFG" ; then
   nagios_user=`${AWK} -F= '/nagios_user/ {print $2;}' $NAGIOS_CFG`
   nagios_grp=`${AWK} -F= '/nagios_group/ {print $2;}' $NAGIOS_CFG`
fi

AC_ARG_WITH(nagios_user,[  --with-nagios-user=<user> sets user name to run nagios],nagios_user=$withval)
AC_ARG_WITH(nagios_group,[  --with-nagios-group=<grp> sets group name to run nagios],nagios_grp=$withval)

ugmsg=""
if test x$nagios_user = xno ; then
    ugmsg="using default"
    nagios_user=nagios
fi

if test x$nagios_grp = xno ; then
    ugmsg="using_default"
    nagios_grp=nagios
fi

if test x$ugmsg = x ; then
    AC_MSG_RESULT([${nagios_user} / ${nagios_grp}])
else
    AC_MSG_RESULT([$ugmsg ${nagios_user} / ${nagios_grp}])
fi

AC_SUBST(nagios_user)
AC_SUBST(nagios_grp)
INSTALL_OPTS="-o $nagios_user -g $nagios_grp"
AC_SUBST(INSTALL_OPTS)

#
# pnp4nagios locations and files
#
PNP_LOGDIR="${logdir}/pnp4nagios"
AC_ARG_WITH(pnp-logdir,[ --with-pnp-logdir=<location of pnp4nagios log files and directories],
      PNP_LOGDIR=$withval
      )
AC_SUBST(PNP_LOGDIR)

PERFDATA_LOG="${PNP_LOGDIR}/perfdata.log"
AC_SUBST(PERFDATA_LOG)

PERFDATA_STATS="${PNP_LOGDIR}/stats"
AC_SUBST(PERFDATA_STATS)


PERFDATA_DIR="${PNP_LOGDIR}/rrd"
AC_ARG_WITH(perfdata-dir,[  --with-perfdata-dir=<path_to_perfdata> Tell me where I should store the RRD Database Files],
	    PERFDATA_DIR=$withval
	   )
AC_SUBST(PERFDATA_DIR)

PERFDATA_SPOOL_DIR="$spooldir"
AC_ARG_WITH(perfdata-spool-dir,[  --with-perfdata-spool-dir=<path_to_perfdata_spool_dir> Tell me where I should store perfdata files for bulk mode with npcd],
	    PERFDATA_SPOOL_DIR=$withval
	   )
AC_SUBST(PERFDATA_SPOOL_DIR)


dnl ---not sure if these three are used ---
pnpsender_name=pnpsender
AC_SUBST(pnpsender_name)

npcd_name=npcd
AC_SUBST(npcd_name)

pp_pl_name=process_perfdata.pl
AC_SUBST(pp_pl_name)


dnl =================== http server settings ================


AC_MSG_CHECKING(http server)
HTTP_SERVER=no
AC_ARG_WITH(http_server,
 [ --with-http_server=(http,apache,apache2,lighttpd,nginx) http server software],
 HTTP_SERVER=$withval)

if test "x${HTTP_SERVER}" = xno ; then
    http_server_types="httpd
    apache
    apache2
    lighttpd
    nginx"
    http_unit=x
    for HTTP_SERVER in $http_server_types
    do
        case "$init_type" in
             systemd)
                http_unit=`systemctl list-units -q "${HTTP_SERVER}.service"|${AWK} '{print $1;}'`
                ;;
              sysv)
                http_unit=`chkconfig --list 2>/dev/null|${GREPE} "${HTTP_SERVER}\s+0"|${AWK} '{print $1;}'`
                ;;
         esac
         if test "x${http_unit}" != x ; then
            break;
         fi
     done
fi

AC_ARG_WITH(http_server_base,
 [ --with-http_server_base=(http server base dir)],
 http_base=$withval, http_base=no)

if test x$http_base = xno ; then
    case "${HTTP_SERVER}" in
       apache*)
        if apachectl -t -D DUMP_RUN_CFG >/dev/null 2>/dev/null ;
        then
             http_base=`apachectl -t -D DUMP_RUN_CFG | ${AWK} '/^ServerRoot:/ {print $2;}'`
             http_base=`echo "$srvroot"|${SED} 's/"//'`
             HTTP_USER=`apachectl -t -D DUMP_RUN_CFG | ${AWK} '/^User:/ {print $2;}'`
             HTTP_USER=`echo "${HTTP_USER}"|${SED} 's/name=//'`
             HTTP_USER=`echo "${HTTP_USER}"|${SED} 's/"//g'`
             HTTP_GROUP=`apachectl -t -D DUMP_RUN_CFG | ${AWK} '/^Group:/ {print $2;}'`
             HTTP_GROUP=`echo "${HTTP_GROUP}"|${SED} 's/name=//'`
             HTTP_GROUP=`echo "${HTTP_GROUP}"|${SED} 's/"//g'`
             HTTP_CONFDIR=`apachectl -t -D DUMP_INCLUDES| ${GREP} "$http_base/.*\.conf" | ${AWK} '/\/conf/{print $2;}' | ${SED} -E 's/[^\/]+$//' | head -n 1`

             HTTP_CONFDIR=`apachectl -t -D DUMP_INCLUDES |${GREP} "$http_base/.*\.conf" | awk '/\/conf/{print $2;}'|${SED} -E 's/\/[[^\/]]+$//' | head -n 1`
             fi
         ;;
   esac
fi



if test "x${http_base}" = xno ; then           
    if test -e "/etc/${HTTP_SERVER}" ; then
       http_base="/etc/${HTTP_SERVER}"
    elif test -e "/usr/local/${HTTP_SERVER}" ; then
       http_base="/usr/local/${HTTP_SERVER}"
    elif test -e "/usr/local/etc/${HTTP_SERVER}" ; then
       http_base="/usr/local/etc/${HTTP_SERVER}"
    elif test -e "/usr/pkg/etc/${HTTP_SERVER}" ; then
       http_base="/usr/pkg/etc/${HTTP_SERVER}"
    fi
fi

if test "x$http_base" = "xno" ; then
   AC_MSG_ERROR(http server base directory not found)
fi

if test "x$HTTP_CONFDIR" = x ; then
   case  "$HTTP_SERVER" in
      apache* | httpd )
          if test -e "${http_base}/conf/httpd.conf" ; then
              hconf="${http_base}/conf/httpd.conf"
          elif test -e "${http_base}/httpd.conf" ; then
              hconf="${http_base}/httpd.conf"
          elif test -e "${http_base}/apache.conf" ; then
              hconf="${http_base}/apache.conf"
          elif test -e "${http_base}/apache2.conf" ; then
              hconf="${http_base}/apache2.conf"
          else
              echo "HTTPD main configure file not found"
              hconf="${http_base}/httpd.conf"
          fi
          if test -e $hconf ; then
              HTTP_USER=`${AWK} '/^User/{print $2;}' $hconf`
              HTTP_GROUP=`${AWK} '/^Group/{print $2;}' $hconf`
              HTTP_CONFDIR=`${AWK} '/^IncludeOptional .*\*/{print $2;}' $hconf|head -n 1`
              if test "x${HTTP_CONFDIR}" != x ; then
                   HTTP_CONFDIR=`dirname "${http_base}/${HTTP_CONFDIR}"`
              fi
           fi
           HTTP_USER=${HTTP_USER:-daemon}
           HTTP_GROUP=${HTTP_GROUP:-daemon}
           HTTP_CONFDIR=${HTTP_CONFDIR:-$http_base}
           ;;
    lighttpd )
           HTTP_USER=lighttpd
           HTTP_GROUP=lighttpd
           HTTP_CONFDIR="${http_base}/conf.d"
           ;;
    nginx )
           HTTP_USER=nobody
           HTTP_GROUP=root
           HTTP_CONFDIR="${http_base}/conf.d" 
           ;;
   esac
fi

if test ! -e ${HTTP_CONFDIR} ; then
   HTTP_CONFDIR=${http_base}
fi


if test "${HTTP_SERVER}" = "httpd" ; then
     HTTP_SERVER=apache
elif test "${HTTP_SERVER}" = "apache2" ; then
     HTTP_SERVER=apache
fi
AC_MSG_RESULT($HTTP_SERVER)
AC_SUBST(HTTP_SERVER)
HTTP_UNIT=${HTTP_UNIT%.service}
AC_SUBST(HTTP_UNIT)

dnl user/grp are based on "tar" install defaults
dnl becuase package managers do their own thing.
dnl so if these user/group settings look unfamiliar, that
dnl might be why

AC_ARG_WITH(http_user,[  --with-http_user=<user> sets user name for http server],http_user=$withval,http_user=$HTTP_USER)
AC_ARG_WITH(http_group,[  --with-http_group=<grp> sets group name for http server],http_group=$withval,http_group=$HTTP_GROUP)

HTTP_USER=${http_user}
HTTP_GROUP=${http_group}

if test x${HTTP_USER} = xno -o x${HTTP_USER} = x ; then
    AC_MSG_ERROR(http_user not defined)
elif test x${HTTP_GROUP} = xno -o x${HTTP_GROUP} = x ; then
    AC_MSG_ERROR(http_group not defined)
fi

AC_SUBST(HTTP_USER)
AC_SUBST(HTTP_GROUP)
HTTP_INSTALL_OPTS="-o $HTTP_USER -g $HTTP_GROUP"
AC_SUBST(HTTP_INSTALL_OPTS)

dnl override the http server config directory, if requested

AC_ARG_WITH(http_confdir,[  --with-http_confdir=<path_to_conf_dir> sets path to http server conf.d directory],HTTP_CONFDIR=$withval)
AC_SUBST(HTTP_CONFDIR)

dnl cache directory, needed for XDG_CACHE_DIR

AC_ARG_WITH(cache_dir,[ --with-cache_dir=<path to cache dir>],
CACHE_DIR=$withval,CACHE_DIR=no)
if test "x$CACHE_DIR" = "xno" ; then
   if test -e "/var/cache" ; then
      CACHE_DIR=/var/cache/pnp4nagios
   else
      AC_MSG_ERROR([/var/cache missing, need to define cache_dir])
   fi
fi
AC_SUBST(CACHE_DIR)

dnl set the url that points to pnp4nagios graphs
AC_ARG_WITH(pnp_url,
    [--with-pnp_url=<pnp4nagios-url>],
    PNP_URL=$withval,
    PNP_URL=/pnp4nagios)

AC_SUBST(PNP_URL)

AC_MSG_CHECKING(Default paper size for pdf generation)
dnl autoconf resets locale, so can't use LC_PAPER
dnl look for system-wide locale setting entry
dnl countries/regions that primarily use "letter" size paper:
dnl Belize Canada Chile Colombia Costa Rica El Salvador Guatemala Mexico
dnl Nicaragua Panama Philippines Puerto Rico United States Venezuela
dnl
dnl codes:  BZ,CA,CL,CO,CR,SV,GT,MX,NI,PA,PH,PR,US,VE
dnl ---- the rest of the world mostly uses A4. 

PAPERSIZE=no
AC_ARG_WITH(papersize,[  --with-papersize=<paper_size>  paper size to use A4 or letter],PAPERSIZE=$withval,PAPERSIZE=no)
if test x${PAPERSIZE} = xno ; then
   localefile=no
   if test -e "/etc/locale.conf" ; then
       localefile=/etc/locale.conf
   elif test -e "/etc/default/locale"  ; then
       localefile=/etc/default/locale
   fi
   if test x${localefile} = xno ; then
      PAPERSIZE=A4
   else
        country=`${GREP} "_..\." -o ${localefile} | ${SED} 's/[[\._]]//g'`
        if echo 'BZ,CA,CL,CO,CR,SV,GT,MX,NI,PA,PH,PR,US,VE' | ${GREP} -q ${country} ; then
            PAPERSIZE=letter
        else
            PAPERSIZE=A4
        fi
   fi
fi
AC_SUBST(PAPERSIZE)
AC_MSG_RESULT($PAPERSIZE)

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


AC_CONFIG_FILES( subst Makefile share/Makefile lib/Makefile scripts/Makefile src/Makefile sample-config/Makefile man/Makefile )
AC_CONFIG_FILES([include/stamp-h], [date '+%Y-%m-%dT%H:%M:%S%z' > include/stamp-h])
AC_CONFIG_FILES(scripts/rrd_modify.pl scripts/rc.npcd scripts/rc.pnp_gearman_worker)
AC_CONFIG_FILES(scripts/npcd.service scripts/pnp_gearman_worker.service)
AC_CONFIG_FILES(scripts/newbsd.npcd)
AC_CONFIG_FILES(scripts/check_pnp_rrds.pl share/pnp/index.php share/pnp/install.php)
AC_CONFIG_FILES(sample-config/README_config.md)
AC_CONFIG_FILES(sample-config/SetLogLevels)
AC_CONFIG_FILES(sample-config/config_tools/NagiosCfgMod.pl)
AC_CONFIG_FILES(sample-config/config_tools/TemplateMod.pl)
AC_CONFIG_FILES(sample-config/config_tools/verify_pnp_config)
AC_CONFIG_FILES(sample-config/config_samples/apache.pnp4nagios.conf)
AC_CONFIG_FILES(sample-config/config_samples/config.php)
AC_CONFIG_FILES(sample-config/config_samples/lighttpd.pnp4nagios.conf)
AC_CONFIG_FILES(sample-config/config_samples/nagios.cfg)
AC_CONFIG_FILES(sample-config/config_samples/nginx.pnp4nagios.conf)
AC_CONFIG_FILES(sample-config/config_samples/npcd.cfg)
AC_CONFIG_FILES(sample-config/config_samples/pnp-bulk.cfg)
AC_CONFIG_FILES(sample-config/config_samples/pnp-npcd.cfg)
AC_CONFIG_FILES(sample-config/config_samples/pnp-sync.cfg)
AC_CONFIG_FILES(sample-config/config_samples/process_perfdata.cfg)
AC_CONFIG_FILES(sample-config/pnp4nagios_release)
AC_CONFIG_FILES(man/npcd.8 ci/pnp4nagios.spec)
AC_CONFIG_FILES(pnp4nagios.fc src/config.c src/npcdmod.c)
AC_CONFIG_FILES(contrib/fedora/pnp4nagios.logrotate.conf)
AC_CONFIG_FILES(contrib/fedora/logwatch/conf/logfiles/pnp4nagios.conf)
AC_CONFIG_FILES(contrib/fedora/logwatch/scripts/services/pnp4nagios)
AC_CONFIG_FILES(contrib/ssi/status-header.ssi)

AC_OUTPUT
 
#### need to use 'subst' instead of configure for this
#### to deal with 'use lib "no"' case for perl scripts
$PERL subst summary
$PERL subst scripts/process_perfdata.pl 
$PERL subst scripts/rrd_convert.pl 
#
if test "x$RRDS" = "x1";  then
   $PERL -MRRDs -e '1;' 2>/dev/null
   if test "$?" -ne 0; then
        RRDS=0
   else
       rrds_version=`$PERL -MRRDs -e 'print \$RRDs::VERSION;'`
   fi
fi
rrdtest=`${RRDTOOL}|head -n1|cut -d' ' -f1| ${AWK} '{print tolower($0)}'`
rrdtest=`basename $rrdtest`
if test "x$rrdtest" != "xrrdtool" ; then
   rrdtool_message="WARNING: '$RRDTOOL' does not look like rrdtool"
else
   rrdtool_version=`${RRDTOOL}|head -n1|cut -d' ' -f2`
   rrdtool_message="${RRDTOOL} Version ${rrdtool_version}"
fi

dnl Review options
echo ""
echo ""
AC_MSG_RESULT([*** Configuration summary for $PKG_NAME $PKG_VERSION $PKG_REL_DATE ***:])

echo ""
echo " General Options:"
echo " -------------------------"

AC_MSG_RESULT([                   Host OS:  $host_os])
AC_MSG_RESULT([              Distribution:  $dist_type, $dist_ver])
AC_MSG_RESULT([           SELINUX enabled:  $SELINUX])
AC_MSG_RESULT([         Install \${prefix}:  $prefix])
AC_MSG_RESULT([            Nagios version:  $NAGIOS_VER])
AC_MSG_RESULT([         Nagios user/group:  $nagios_user,$nagios_grp])
AC_MSG_RESULT([           Root user/group:  root,$root_grp])
AC_MSG_RESULT([              PNP HTML dir:  $datarootdir])
AC_MSG_RESULT([                Config dir:  $pkgsysconfdir])
AC_MSG_RESULT([            rrdtool binary:  $rrdtool_message])
if test "x$RRDS" = "x0" ; then
AC_MSG_RESULT([         RRDs Perl Modules:  *** NOT FOUND ***])
else
AC_MSG_RESULT([         RRDs Perl Modules:  (Version $rrds_version) FOUND])
fi
if test "x$PERL_LIB_PATH" != "x" ; then
AC_MSG_RESULT([     RRDs Perl search path:  $PERL_LIB_PATH])
fi
dnl 
AC_MSG_RESULT([       RRD Files stored in:  $PERFDATA_DIR])
AC_MSG_RESULT([  process_perfdata Logfile:  $PERFDATA_LOG])
AC_MSG_RESULT([   NPCD perfdata stored in:  $PERFDATA_SPOOL_DIR])
AC_MSG_RESULT([      fontconfig cache dir:  $CACHE_DIR])



echo ""
echo " Web Interface Options:"
echo " ------------------------"

AC_MSG_RESULT([               http server:  $HTTP_SERVER])
AC_MSG_RESULT([          http configs dir:  $HTTP_CONFDIR])
AC_MSG_RESULT([  put pnp http config File:  ${HTTP_CONFDIR}/${PACKAGE_NAME}.conf])
AC_MSG_RESULT([           http user/group:  $HTTP_USER,$HTTP_GROUP])
AC_MSG_RESULT([                  HTML URL:  https://localhost${PNP_URL}/])

if test "x$DESTDIR" != "x" ; then
AC_MSG_RESULT([   WARNING \${DESTDIR} set:  $DESTDIR])
fi

if test "x${DEBUG_LEVEL}" != "x0" ; then
echo " Debug output:"
echo " -------------"
AC_MSG_RESULT([             DEBUG_LEVEL:  ${DEBUG_LEVEL}])
AC_MSG_RESULT([               perl exec:  $PERL])
AC_MSG_RESULT([              shell exec:  $SHELL])
AC_MSG_RESULT([              libexecdir:  $libexecdir])
AC_MSG_RESULT([                  libdir:  $libdir])
AC_MSG_RESULT([              sysconfdir:  $sysconfdir])
AC_MSG_RESULT([             datarootdir:  $datarootdir])
AC_MSG_RESULT([                 datadir:  $datadir])
AC_MSG_RESULT([             exec prefix:  $exec_prefix])
AC_MSG_RESULT([                 sbindir:  $sbindir])
AC_MSG_RESULT([                  bindir:  $bindir])
fi

if test $opsys = unix ; then
   if test "x$prefix" = "x/usr/local/nagios" ; then
      echo ""
      echo ""
      echo "prefix=$prefix"
      echo "You probably wanted to do:"
      echo "   ./configure --prefix=/usr/local/pnp4nagios"
      echo "(or some similar prefix) to get the correct prefix."
   fi
fi
if test "$install_method"  = default ; then
    if test $opsys = bsd ; then
      echo ""
      echo ""
      echo "prefix=$prefix"
      echo "You probably wanted to do:"
      echo "   ./configure --enable-install-method=os "
      echo "to get the correct installation paths"
    fi
fi

echo ""
echo ""
echo "Review the options above for accuracy.  If they look okay,"
echo "type 'make all' to compile the main program"
echo ""
echo "For more info run ./configure --with-debug_level=1"
dnl 
dnl 
