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
AC_PREFIX_DEFAULT(/usr/local/pnp4nagios)

#
#
AC_SUBST(ac_configure_args)
XML_STRUCTURE_VERSION="4"
AC_SUBST(XML_STRUCTURE_VERSION)

DEBUG_LEVEL=0
AC_ARG_WITH(debug_level, [ --with-debug_level=<debuglevel> ],
DEBUG_LEVEL=$withval, DEBUG_LEVEL=0)
if test "$DEBUG_LEVEL" > 0 ; then
CFLAGS="${CFLAGS} -O0 -ggdb3 -g3"
fi
AC_SUBST(DEBUG_LEVEL)

#dnl What OS are we running?
AC_CANONICAL_HOST
#dnl kinda doubt much cross-compiling, but just in case...
AC_CANONICAL_TARGET

dnl Get Nagios autoconf-macros
INIT_PROG=npcd
AX_NAGIOS_GET_OS
AX_NAGIOS_GET_DISTRIB_TYPE
AX_NAGIOS_GET_INIT
AX_NAGIOS_GET_PATHS
AX_NAGIOS_GET_FILES
# for some reason AX_NAGIOS_GET_PATHS doesn't AC_SUBST this...
AC_SUBST(datadir)

dnl Checks for programs.
AC_PROG_INSTALL
AC_PROG_CC
AC_PROG_MAKE_SET
AC_PATH_PROG([STRIP],[strip],[true])
AC_PROG_GREP
AC_PROG_AWK
AC_PROG_SED
AC_PROG_LN_S
AC_PATH_PROG(CP,cp)
AC_PATH_PROG(PERL,perl)

eval sbindir=$sbindir

# Checks for libraries.
AS_IF([test "q$target_cpu" = "qx86_64"],[x64="64"],[x64=""])

if test -d "/usr/lib${x64}" ; then
   libdir="${prefix}/usr/lib${x64}/${PACKAGE_NAME}"
elif test -d "/lib${x64}"; then
   libdir="${prefix}/lib${x64}/${PACKAGE_NAME}"
elif test -d "/usr/lib" ; then
   libdir="${prefix}/usr/lib/${PACKAGE_NAME}"
else
   libdir="${prefix}/lib/${PACKAGE_NAME}"
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
AC_ARG_WITH(kohana_system,[  --with-kohana_system=<existing kohana system dir> Points to an already installed kohana framework],KOHANA_SYSTEM=$withval,KOHANA_SYSTEM="$libdir/kohana/system")
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

case $src_init in
    default-service)
        src_init=npcd.service
        initname="${initname}.service"        
        ;;
    default-init)
        src_init=rc.npcd
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


initname=${initname/${PKG_NAME}/npcd}
initconf=${initconf=${pkgsysconfdir}/npcd.cfg}

if test "x$spooldir" == "xN/A" ; then
   spooldir="$localstatedir/spool"
else
   spooldir=${spooldir/${PKG_NAME}/npcd}
fi   
lockfile=${lockfile=${localstatedir}/npcd.lock}

eval logdir="${logdir}/log"

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



dnl ================ Nagios configuration info ============

AC_MSG_CHECKING(Nagios server)
# if nagios not installed yet, probably will install a new version
# so just pick a recent version unless one is given in arguments
NAGIOS_VER=no
AC_ARG_WITH(nagios_version,[ --with-nagios-version=<nagios_version> nagios version string, like 3.1 etc], NAGIOS_VER=$withval,NAGIOS_VER=no)
if test x$NAGIOS_VER = xno; then
   if which nagios >/dev/null 2>/dev/null ; then
      NAGIOS_VER=`nagios --help | ${AWK} '/^Nagios /'  | ${AWK} -v RS=' ' '/^[[0-9]]/'`
      AC_MSG_RESULT($NAGIOS_VER)
   else
      NAGIOS_VER=4.4.6
      AC_MSG_RESULT([not found, assuming $NAGIOS_VER])
   fi    
else
   AC_MSG_RESULT([forced use of version $NAGIOS_VER])
fi
AC_SUBST(NAGIOS_VER)

AC_DEFINE([DEFAULT_NAGIOS_USER], [nagios], [Default Nagios User])
AC_DEFINE([DEFAULT_NAGIOS_GROUP], [nagios], [Default Nagios Group])
AC_ARG_WITH(nagios_user,[  --with-nagios-user=<user> sets user name to run nagios],nagios_user=$withval,nagios_user=nagios)
AC_ARG_WITH(nagios_group,[  --with-nagios-group=<grp> sets group name to run nagios],nagios_grp=$withval,nagios_grp=nagios)
AC_SUBST(nagios_user)
AC_SUBST(nagios_grp)
AC_DEFINE_UNQUOTED(DEFAULT_NAGIOS_USER,"$nagios_user")
AC_DEFINE_UNQUOTED(DEFAULT_NAGIOS_GROUP,"$nagios_grp")
INSTALL_OPTS="-o $nagios_user -g $nagios_grp"
AC_SUBST(INSTALL_OPTS)

PERFDATA_LOG="${logdir}/perfdata.log"
AC_ARG_WITH(perfdata-logfile,[  --with-perfdata-logfile=<perfdata_logfile> Tell me where I should store the 'process_perfdata.pl' Logfile],
        PERFDATA_LOG=$withval
	   )
AC_SUBST(PERFDATA_LOG)

PERFDATA_DIR="${localstatedir}/rrd"
AC_ARG_WITH(perfdata-dir,[  --with-perfdata-dir=<path_to_perfdata> Tell me where I should store the RRD Database Files],
	    PERFDATA_DIR=$withval
	   )
AC_SUBST(PERFDATA_DIR)

PERFDATA_SPOOL_DIR="$spooldir"
AC_ARG_WITH(perfdata-spool-dir,[  --with-perfdata-spool-dir=<path_to_perfdata_spool_dir> Tell me where I should store perfdata files for bulk mode with npcd],
	    PERFDATA_SPOOL_DIR=$withval
	   )
AC_SUBST(PERFDATA_SPOOL_DIR)


dnl check for location where Nagios keeps its config
AC_MSG_CHECKING(Nagios config)

NAGIOS_CFG=no
AC_ARG_WITH(nagios_cfg,[ --with-nagios-cfg=<nagios_cfg_dir> sets path to nagios.cfg file], NAGIOS_CFG=$withval,NAGIOS_CFG=no)
if test x$NAGIOS_CFG = xno; then
   if test -e /etc/nagios/nagios.cfg ; then
      NAGIOS_CFG=/etc/nagios/nagios.cfg
      AC_MSG_RESULT($NAGIOS_CFG)
   elif test -e /usr/local/etc/nagios/nagios.cfg ; then
      NAGIOS_CFG=/usr/local/etc/nagios/nagios.cfg
      AC_MSG_RESULT($NAGIOS_CFG)
   else
      NAGIOS_CFG=/etc/nagios/nagios.cfg
      AC_MSG_RESULT([not found, assume default $NAGIOS_CFG])
   fi
else
   AC_MSG_RESULT([forced to $NAGIOS_CFG])
fi
AC_SUBST(NAGIOS_CFG)

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
 [ --with-http_server=apache,lighttpd,nginx http server software],
 HTTP_SERVER=$withval, HTTP_SERVER=no )


if test "x${HTTP_SERVER}" = "xno" ; then
   http_base=no
   for HTTP_SERVER in "httpd" "lighttpd" "nginx" 
   do
        if test -e "/etc/${HTTP_SERVER}" ; then
           http_base="/etc/${HTTP_SERVER}"
           break
        elif test -e "/usr/local/${HTTP_SERVER}" ; then
           http_base="/usr/local/${HTTP_SERVER}"
           break
        elif test -e "/usr/local/etc/${HTTP_SERVER}" ; then
           http_base="/usr/local/etc/${HTTP_SERVER}"
           break
        fi
   done
  
   if test "x$http_base" = "xno" ; then
      AC_MSG_ERROR(not found)
   fi 

   if test "${HTTP_SERVER}" = "httpd" ; then
        HTTP_SERVER=apache
   fi
   AC_MSG_RESULT($HTTP_SERVER)

dnl user/grp are based on "tar" install defaults
dnl becuase package managers do their own thing.
dnl so if these user/group settings look unfamiliar, that
dnl might be why

   case  "$HTTP_SERVER" in
        apache )
               if test -e "${http_base}/conf/httpd.conf" ; then
                 def_httpserver_user=`${AWK} '/^User/{print $2;}' ${http_base}/conf/httpd.conf`
                 def_httpserver_grp=`${AWK} '/^Group/{print $2;}' ${http_base}/conf/httpd.conf`
               fi
               def_httpserver_user=${def_httpserver_user:-daemon}
               def_httpserver_grp=${def_httpserver_grp:-daemon}
               HTTP_CONFDIR="${http_base}/conf.d"
               ;;
        lighttpd )
               def_httpserver_user=lighttpd
               def_httpserver_grp=lighttpd
               HTTP_CONFDIR="${http_base}/conf.d"
               ;;
        nginx )
               def_httpserver_user=nobody
               def_httpserver_grp=root
               HTTP_CONFDIR="${http_base}/conf.d" 
               ;;
    esac
fi
AC_SUBST(HTTP_SERVER)

AC_ARG_WITH(http_user,[  --with-http_user=<user> sets user name for http server],http_user=$withval,http_user=$def_httpserver_user)
AC_ARG_WITH(http_group,[  --with-http_group=<grp> sets group name for http server],http_group=$withval,http_group=$def_httpserver_grp)
HTTP_USER=$http_user
HTTP_GROUP=$http_group
AC_SUBST(HTTP_USER)
AC_SUBST(HTTP_GROUP)
HTTP_INSTALL_OPTS="-o $http_user -g $http_group"
AC_SUBST(HTTP_INSTALL_OPTS)

dnl override the http server config directory, if requested

AC_ARG_WITH(http_confdir,[  --with-http_confdir=<path_to_conf_dir> sets path to http server conf.d directory],HTTP_CONFDIR=$withval)
AC_SUBST(HTTP_CONFDIR)


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
AC_CONFIG_FILES([include/stamp-h], [date -Is > include/stamp-h])
AC_CONFIG_FILES(scripts/rrd_modify.pl scripts/rc.npcd scripts/rc.pnp_gearman_worker)
AC_CONFIG_FILES(scripts/npcd.service scripts/pnp_gearman_worker.service)
AC_CONFIG_FILES(scripts/check_pnp_rrds.pl share/pnp/index.php share/pnp/install.php)
AC_CONFIG_FILES(sample-config/README_config.md)
AC_CONFIG_FILES(sample-config/SetLogLevels)
AC_CONFIG_FILES(sample-config/config_tools/NagiosCfgMod.pl)
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
AC_CONFIG_FILES(contrib/ssi/status-header.ssi)
AC_CONFIG_FILES(man/npcd.8 ci/pnp4nagios.spec)
AC_CONFIG_FILES(pnp4nagios.fc src/config.c src/npcdmod.c)
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
AC_MSG_RESULT([                Config dir:  $sysconfdir])
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



echo ""
echo " Web Interface Options:"
echo " ------------------------"

AC_MSG_RESULT([               http server:  $HTTP_SERVER])
AC_MSG_RESULT([          http configs dir:  $HTTP_CONFDIR])
AC_MSG_RESULT([      pnp http config File:  ${HTTP_CONFDIR}/${PACKAGE_NAME}.conf])
AC_MSG_RESULT([           http user/group:  $http_user,$http_group])
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


echo ""
echo ""
echo "Review the options above for accuracy.  If they look okay,"
echo "type 'make all' to compile the main program"
echo ""
echo "For more info run ./configure --with-debug_level=1"
dnl 
dnl 
