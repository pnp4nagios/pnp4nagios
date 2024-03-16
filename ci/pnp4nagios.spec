%global debug_package %{nil}
%global with_selinux 1
%global _libdir      %{_prefix}/lib64

Name:           pnp4nagios
Version:        0.6.27
Release:        4%{?dist}
Summary:        Nagios performance data analysis tool

Group:          Applications/System
License:        GPLv2
URL:            https://github.com/pnp4nagios/pnp4nagios
Source0:        %{name}-%{version}.tgz
BuildRoot:      %{_tmppath}/%{name}-%{version}-%{release}-root

BuildRequires:  autoconf, automake, libtool
Requires:       rrdtool-perl
Requires:       perl(Time::HiRes)
Requires:       nagios
Requires:       httpd
Requires:       rrdtool-perl
Requires:       php >= 5.6
Requires:       php-gd
Requires:       php-xml
Requires:       php-mbstring
# This ensures that the *-selinux package and all it’s dependencies are not pulled
# into containers and other systems that do not use SELinux
%if 0%{?fedora} || 0%{?rhel} >= 8
Requires:       (%{name}-selinux if selinux-policy-%{selinuxtype})
%else
Requires:       %{name}-selinux
%endif

%if 0%{?rhel} > 6 || 0%{?fedora} > 20
# For necessary macros
BuildRequires:  systemd
%else
Requires(preun):  initscripts, chkconfig
Requires(post):   initscripts, chkconfig
Requires(postun): initscripts
%endif


%description
PNP is an addon to nagios which analyzes performance data provided by plugins
and stores them automatically into RRD-databases.

%if 0%{?with_selinux}
# SELinux subpackage
%package selinux
Summary:          SELinux context for %{name}
BuildArch:        noarch
%global selinuxtype  targeted
%global modulename   pnp4nagios
Requires:         selinux-policy-%{selinuxtype}
Requires(post):   selinux-policy-%{selinuxtype}
Requires:    checkpolicy, selinux-policy-devel, bzip2
%{?selinux_requires}

%description selinux
SElinux security policy for %{name}.
%endif




%prep
%setup -q 
#autoreconf
cp contrib/fedora/pnp4nagios-README.fedora README.fedora
sed -i -e 's/^INSTALL_OPTS="-o $nagios_user -g $nagios_grp"/INSTALL_OPTS=""/' \
    configure
sed -i -e '/^\t$(MAKE) strip-post-install$/d' src/Makefile.in


%build


autoconf
%configure --prefix='' \
           --bindir=%{_sbindir} \
           --libexecdir=%{_libexecdir}/%{name} \
           --sysconfdir=%{_sysconfdir}/%{name} \
           --localstatedir=%{_localstatedir}/log/%{name} \
           --datadir=%{_datadir}/nagios/html/%{name} \
           --datarootdir=%{_datadir}/nagios/html/%{name} \
           %if 0%{with_selinux} 
           --enable-selinux=yes \
           %else
           --with-selinux=no \
           %endif
           --with-dist-type=rh \
           --with-init-type=systemd \
           --with-initdir=%{_unitdir} \
           --with-http_server=apache \
           --with-http_server_base=%{_sysconfdir}/httpd \
           --with-http_user=apache \
           --with-http_group=apache \
           --with-kohana_system=%{_datadir}/nagios/html/%{name}/kohana/system \
           --with-nagios_user=nagios \
           --with-nagios_group=nagios \
           --with-nagios_unit=nagios \
           --with-cache_dir=%{_localstatedir}/cache/pnp4nagios \
           --with-perfdata-dir=%{_localstatedir}/lib/%{name} \
           --with-perfdata-spool-dir=%{_localstatedir}/spool/%{name} \
	   --with-pnp-logdir=%{_localstatedir}/log/%{name}

%{__make} %{?_smp_mflags} all

%install
if [ "$RPM_BUILD_ROOT" != "/" ]; then
    rm -rf $RPM_BUILD_ROOT
fi
%{__make} install DESTDIR=$RPM_BUILD_ROOT INSTALL_OPTS="" HTTP_INSTALL_OPTS="" MIX_OPTS="" INIT_OPTS="" NOSAMPLES=1

# NO...do NOT remove -sample from filename suffix
#for i in $RPM_BUILD_ROOT/%{_sysconfdir}/pnp4nagios/*-sample \
#         $RPM_BUILD_ROOT/%{_sysconfdir}/pnp4nagios/*/*-sample
#do
#  mv ${i} ${i%%-sample}
#done
rm -f $RPM_BUILD_ROOT%{_sysconfdir}/%{name}/config.php.*
rm -f $RPM_BUILD_ROOT%{_sysconfdir}/%{name}/config_local.php
chmod a-x $RPM_BUILD_ROOT%{_sysconfdir}/%{name}/config.php
chmod a-x $RPM_BUILD_ROOT%{_sysconfdir}/%{name}/*.cfg
chmod a-x $RPM_BUILD_ROOT%{_sysconfdir}/%{name}/*.md
chmod a-x $RPM_BUILD_ROOT%{_sysconfdir}/%{name}/check_commands/*.cfg-sample

mkdir -p $RPM_BUILD_ROOT%{_localstatedir}/lib/%{name}
mkdir -p $RPM_BUILD_ROOT%{_localstatedir}/spool/%{name}
mkdir -p $RPM_BUILD_ROOT%{_localstatedir}/log/%{name}
mkdir -p $RPM_BUILD_ROOT%{_localstatedir}/log/%{name}/stats
mkdir -p $RPM_BUILD_ROOT%{_localstatedir}/log/%{name}/kohana
#
install -Dp -m 0644 contrib/fedora/pnp4nagios.logrotate.conf \
        $RPM_BUILD_ROOT%{_sysconfdir}/logrotate.d/pnp4nagios
#
mkdir -p $RPM_BUILD_ROOT%{_sysconfdir}/logwatch/scripts/services
mkdir -p $RPM_BUILD_ROOT%{_sysconfdir}/logwatch/conf/services
mkdir -p $RPM_BUILD_ROOT%{_sysconfdir}/logwatch/conf/logfiles
install -m 0666 contrib/fedora/logwatch/scripts/services/pnp4nagios \
        $RPM_BUILD_ROOT%{_sysconfdir}/logwatch/scripts/services/
install -m 0644 contrib/fedora/logwatch/conf/services/pnp4nagios.conf \
        $RPM_BUILD_ROOT%{_sysconfdir}/logwatch/conf/services/
install -m 0644 contrib/fedora/logwatch/conf/logfiles/pnp4nagios.conf \
        $RPM_BUILD_ROOT%{_sysconfdir}/logwatch/conf/logfiles/
#
#mkdir -p $RPM_BUILD_ROOT%{_sysconfdir}/httpd/conf.d
#sed 's|/usr/local/nagios/etc/htpasswd.users|@NAGIOS_PWD@|' \
#   sample-config/httpd.conf \
#   > $RPM_BUILD_ROOT%{_sysconfdir}/httpd/conf.d/%{name}.conf
install -Dp -m 0644 scripts/npcd.service \
        $RPM_BUILD_ROOT%{_unitdir}/npcd.service

#### broker api changed
#mkdir -p $RPM_BUILD_ROOT%{_libdir}/nagios/brokers
#mv $RPM_BUILD_ROOT%{_libdir}/npcdmod.o \
#   $RPM_BUILD_ROOT%{_libdir}/nagios/brokers/npcdmod.o
## rm -f $RPM_BUILD_ROOT%{_libdir}/%{name}/npcmod.o

#mv $RPM_BUILD_ROOT/man $RPM_BUILD_ROOT%{_datadir}/

# Move kohana to pnp4nagios, there is another kohana in fedore/EPEL,
# which can be installed.
#mv $RPM_BUILD_ROOT%{_libdir}/kohana \
#  $RPM_BUILD_ROOT%{_datadir}/nagios/html/%{name}/kohana
#sed -i 's|%{_libdir}/pnp4nagios/kohana|%{_datadir}/nagios/html/%{name}/kohana|' \
#  $RPM_BUILD_ROOT%{_datadir}/nagios/html/%{name}/index.php
mv $RPM_BUILD_ROOT%{_datadir}/nagios/html/%{name}/install.ignore.not \
   $RPM_BUILD_ROOT%{_datadir}/nagios/html/%{name}/install.ignore
rm $RPM_BUILD_ROOT%{_sysconfdir}/pnp4nagios/background.pdf

%if 0%{with_selinux}
install -d $RPM_BUILD_ROOT%{_localstatedir}/lib/selinux/tmp/%{name}
install -m 0644 %{name}.fc $RPM_BUILD_ROOT%{_localstatedir}/lib/selinux/tmp/%{name}/%{modulename}.fc
install -m 0644 %{name}.te $RPM_BUILD_ROOT%{_localstatedir}/lib/selinux/tmp/%{name}/%{modulename}.te
%endif

%package logrotate
Summary:        config for rotating pnp4nagios logs
Requires:       logrotate
Group:          Applications/System

%description logrotate
config file used by logrotate, set up for pnp4nagios logs


%package logwatch
Summary:        config and scripts for checking pnp4nagios log files
Requires:       logwatch
Group:          Applications/System

%description logwatch
config files and log scanning script for checking pnp4nagios log
files for errors, and flagging them for attention. 


%clean
#if [ "$RPM_BUILD_ROOT" != "/" ]; then
#   rm -rf $RPM_BUILD_ROOT
#fi



%files
%defattr(644,root,root,755)
%doc AUTHORS ChangeLog COPYING
%doc INSTALL README.md README.fedora
%doc THANKS contrib/
%config(noreplace) %attr(644,root,root) %{_sysconfdir}/%{name}/pages/background-*.pdf
%config(noreplace) %attr(644,root,root) %{_sysconfdir}/%{name}/config.php
%config(noreplace) %attr(644,root,root) %{_sysconfdir}/%{name}/*.cfg
%attr(755,root,root) %{_sysconfdir}/%{name}/SetLogLevels
%attr(755,root,root) %{_sysconfdir}/%{name}/verify_pnp_config
%attr(644,root,root) %{_sysconfdir}/%{name}/README_config.md
%{_sysconfdir}/%{name}/check_commands/*
%{_sysconfdir}/%{name}/contrib/*
%dir %{_sysconfdir}/%{name}/config.d
%dir %attr(744,apache,apache) /var/cache/pnp4nagios
%attr(755,root,root) %{_sysconfdir}/%{name}/config_tools/NagiosCfgMod.pl
%attr(755,root,root) %{_sysconfdir}/%{name}/config_tools/TemplateMod.pl
%{_sysconfdir}/%{name}/config_samples/*
%{_sysconfdir}/%{name}/pnp4nagios_release

%attr(755,root,root) %{_sbindir}/npcd
%config(noreplace) %attr(644,root,root) %{_unitdir}/npcd.service
%dir %{_libexecdir}/%{name}
%attr(755,root,root) %{_libexecdir}/%{name}/*
%attr(755,nagios,nagios) %{_localstatedir}/lib/%{name}
%dir %attr(755,nagios,nagios) %{_localstatedir}/log/%{name}
%dir %attr(755,nagios,nagios) %{_localstatedir}/log/%{name}/stats
%dir %attr(755,nagios,nagios) %{_localstatedir}/log/%{name}/lock
%dir %attr(755,apache,apache) %{_localstatedir}/log/%{name}/kohana
%dir %attr(755,nagios,nagios) %{_localstatedir}/spool/%{name}
%{_datadir}/nagios/html/%{name}/
%{_mandir}/man8/*

%files logrotate
%config(noreplace) %{_sysconfdir}/logrotate.d/%{name}

%files logwatch
%defattr(644,root,root)
%config(noreplace) %{_sysconfdir}/logwatch/scripts/services/%{name}
%config(noreplace) %{_sysconfdir}/logwatch/conf/services/%{name}.conf
%config(noreplace) %{_sysconfdir}/logwatch/conf/logfiles/%{name}.conf

%if 0%{?with_selinux}
%files selinux
%{_localstatedir}/lib/selinux/tmp/%{name}/%{modulename}.te
%{_localstatedir}/lib/selinux/tmp/%{name}/%{modulename}.fc

%pre selinux
%selinux_relabel_pre -s %{selinuxtype}

%post selinux
pushd %{_localstatedir}/lib/selinux/tmp/%{name} >/dev/null
if semodule -E nagios 2>/dev/null >/dev/null  ;
then
  semodule_unpackage nagios.pp nagios.mod nagios.fc
  for pnpdir in /etc/pnp4nagios /var/log/pnp4nagios /var/lib/pnp4nagios /usr/lib/pnp4nagios
  do
      if grep -q $pnpdir nagios.fc ;
      then
          sed -i "\\|$pnpdir|s/^/#/" %{modulename}.fc
      fi
  done
fi
rm nagios.*
%{__make} %{?_smp_mflags} -f /usr/share/selinux/devel/Makefile all
bzip2 %{name}.pp
install -m 0600 %{modulename}.pp.bz2 %{_datadir}/selinux/packages/%{selinuxtype}/
popd >/dev/null
rm -rf /var/lib/selinux/tmp/%{name}
%selinux_modules_install -s %{selinuxtype} %{_datadir}/selinux/packages/%{selinuxtype}/%{modulename}.pp.bz2

%postun selinux
if [ $1 -eq 0 ]; then
    %selinux_modules_uninstall -s %{selinuxtype} %{modulename}
fi

%posttrans selinux
%selinux_relabel_post -s %{selinuxtype}

%endif


%post
# config sample update
pushd %{_sysconfdir}/pnp4nagios >/dev/null
if [ -e %{_sysconfdir}/nagios/objects/templates.cfg ] ;
then
        perl %{_sysconfdir}/pnp4nagios/config_tools/TemplateMod.pl -i %{_sysconfdir}/nagios/objects/templates.cfg -o config_samples/nagios/objects/templates.cfg
fi
if [ -e %{_sysconfdir}/nagios/nagios.cfg ] ;
then
    nagver=`nagios --version | gawk '/^Nagios /'  | gawk -v RS=' ' '/^[0-9]/'`
    perl %{_sysconfdir}/pnp4nagios/config_tools/NagiosCfgMod.pl -i %{_sysconfdir}/nagios/nagios.cfg -m sync -o config_samples/nagios/nagios-sync.cfg -n $nagver
    perl %{_sysconfdir}/pnp4nagios/config_tools/NagiosCfgMod.pl -i %{_sysconfdir}/nagios/nagios.cfg -m bulk -o config_samples/nagios/nagios-bulk.cfg -n $nagver
    perl %{_sysconfdir}/pnp4nagios/config_tools/NagiosCfgMod.pl -i %{_sysconfdir}/nagios/nagios.cfg -m npcd -o config_samples/nagios/nagios-npcd.cfg -n $nagver
fi        
# determine default paper size based on locale
if test -e "/etc/locale.conf" ; then
    localefile=/etc/locale.conf
elif test -e "/etc/default/coale" ; then
    localefile=/etc/default/locale
else
    localefile=""
fi
if test "x${localefile}" = "x" ; then
    PAPERSIZE=A4
else
    country=`grep "_..\." -o ${localefile} | sed 's/[\._]//g'`
    if echo 'BZ,CA,CL,CO,CR,SV,GT,MX,NI,PA,PH,PR,US,VE' | grep -q ${country} ; then
       PAPERSIZE=letter
    else
       PAPERSIZE=A4
    fi
fi
if [ ! -e %{_sysconfdir}/pnp4nagios/background.pdf ] ; then
   ln -rs %{_sysconfdir}/pnp4nagios/pages/background-${PAPERSIZE}.pdf %{_sysconfdir}/pnp4nagios/background.pdf
fi

popd >/dev/null
%systemd_post npcd.service

%preun
%systemd_preun npcd.service

%postun
%systemd_postun_with_restart npcd.service




%changelog
* Mon Feb 19 2024 Chuck Lane <lane@dchooz.org> - 0.6.27-6
- get rpm in sync with 'build-from-source' autoconf setup

* Thu Nov 23 2023 Chuck Lane <lane@dchooz.org> - 0.6.27-5
- add selinux info, improved packaging

* Mon Sep 11 2023 Chuck Lane <lane@dchooz.org> - 0.6.27-4
- change to defining XDG_CACHE_HOME in php, many links in docs fixed

* Mon Aug 28 2023 Chuck Lane <lane@dchooz.org> - 0.6.27-3
- one more pnp8.2 fix, update release number

* Fri Aug 18 2023 Chuck Lane <lane@dhooz.org> - 0.6.27-1
- many pnp8.2 deprecation fixes, get XDG_CACHE_HOME in systemd setup
  
* Tue Dec 20 2022 Chuck Lane <lane@dchooz.org> - 0.6.26-14
- minor config cleanups, add logwatch and logrotate subpackages

* Sun Sep 11 2022 Chuck Lane <lane@dchooz.org> - 0.6.26-3
- upgrade to php8

* Mon Jun 08 2015 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.25-1
- Update to upstream.

* Sun Aug 17 2014 Fedora Release Engineering <rel-eng@lists.fedoraproject.org> - 0.6.22-3
- Rebuilt for https://fedoraproject.org/wiki/Fedora_21_22_Mass_Rebuild

* Fri Jul 04 2014 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.22-2
- Fix two URL Cross-Site Scripting Vulnerabilities (bz#1115983)

* Thu Jul 03 2014 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.22-1
- Update to upstream (fixes XSS flaw in an error page - bz#1115770)

* Sat Jun 07 2014 Fedora Release Engineering <rel-eng@lists.fedoraproject.org> - 0.6.21-5
- Rebuilt for https://fedoraproject.org/wiki/Fedora_21_Mass_Rebuild

* Sun Aug 04 2013 Fedora Release Engineering <rel-eng@lists.fedoraproject.org> - 0.6.21-4
- Rebuilt for https://fedoraproject.org/wiki/Fedora_20_Mass_Rebuild

* Wed Jul 17 2013 Petr Pisar <ppisar@redhat.com> - 0.6.21-3
- Perl 5.18 rebuild

* Wed Jul 03 2013 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.21-2
- Broken configuration for httpd 2.4 fixed (bz#871465)
- fixed dates in changelog items

* Tue Jun 04 2013 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.21-1
- update to upstream

* Sat Mar 23 2013 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.20-2
- added autoreconf to prep section (bz#926359)

* Sun Mar 03 2013 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.20-1
- update to upstream

* Sun Feb 17 2013 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.19-2
- updated hostextinfo URL for pnp4nagios 0.6
- spec file cleanup

* Sat Feb 16 2013 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.19-1
- update to upstream

* Mon Sep 03 2012 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.16-4
- CVE-2012-3457 - process_perfdata.cfg world readable

* Thu Apr 05 2012 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.16-2
- Removed double slashes fro directories (BZ#810212).

* Thu Nov 24 2011 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.16-1
- update to upstream

* Mon Nov 21 2011 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.15-4
- add back kohana, it's a different version
- added BR: perl(Time::HiRes)

* Mon Nov 21 2011 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.15-2
- exclude kohana sources and require php-Kohana package

* Wed Nov 16 2011 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.15-1
- update to upstream
- remove /usr/share/nagios/html/pnp4nagios/install.php
- added /etc/httpd/conf.d/pnp4nagios.conf
- removed -sample suffix from rest of sample files

* Tue Oct 11 2011 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.1-3
- Updated renaming of "-sample" config files.

* Wed Sep 14 2011 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.6.1-1
- Update to 0.6.1.

* Tue Sep 13 2011 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.4.14-7
- added perl-Time-HiRes to build requires

* Tue Sep 13 2011 Ján ONDREJ (SAL) <ondrejj(at)salstar.sk> - 0.4.14-6
- rebuilt for EPEL-6

* Wed Feb 09 2011 Fedora Release Engineering <rel-eng@lists.fedoraproject.org> - 0.4.14-5
- Rebuilt for https://fedoraproject.org/wiki/Fedora_15_Mass_Rebuild

* Mon Sep 27 2010 Xavier Bachelot <xavier@bachelot.org> 0.4.14-4
- Bump release for rebuild.

* Sun Jul 18 2010 Xavier Bachelot <xavier@bachelot.org> 0.4.14-3
- Add patch to fix PHP deprecated warnings with PHP 5.3.
  (Patch from Jan Ondrej - RHBZ#572851)

* Thu Aug 27 2009 Xavier Bachelot <xavier@bachelot.org> 0.4.14-2
- Ship contrib directory as doc.

* Thu Aug 27 2009 Xavier Bachelot <xavier@bachelot.org> 0.4.14-1
- Update to 0.4.14 (RHBZ#518069).
- Fix typo in README.fedora (RHBZ#490664).
- Move npcdmod.o to a better place.
- BR: rrdtool-perl

* Sun Jul 26 2009 Fedora Release Engineering <rel-eng@lists.fedoraproject.org> - 0.4.12-4
- Rebuilt for https://fedoraproject.org/wiki/Fedora_12_Mass_Rebuild

* Thu Feb 26 2009 Fedora Release Engineering <rel-eng@lists.fedoraproject.org> - 0.4.12-3
- Rebuilt for https://fedoraproject.org/wiki/Fedora_11_Mass_Rebuild

* Thu Dec  4 2008 Michael Schwendt <mschwendt@fedoraproject.org> 0.4.12-2
- Include /usr/libexec/pnp4nagios directory.

* Tue Oct 21 2008 Robert M. Albrecht <romal@gmx.de> 0.4.12-1
- Upstream released 0.4.12

* Tue Sep 02 2008 Xavier Bachelot <xavier@bachelot.org> 0.4.10-3
- Fix logrotate conf (RHBZ#460861).

* Fri Jul 18 2008 Xavier Bachelot <xavier@bachelot.org> 0.4.10-2
- Fix typo in logrotate conf.

* Wed Jul 09 2008 Xavier Bachelot <xavier@bachelot.org> 0.4.10-1
- Update to 0.4.10.

* Tue May 27 2008 Xavier Bachelot <xavier@bachelot.org> 0.4.9-3
- Fix npcd init script to use /etc/pnp4nagios.

* Tue May 27 2008 Xavier Bachelot <xavier@bachelot.org> 0.4.9-2
- Install npcd unstripped to let rpm do it.

* Sat May 24 2008 Xavier Bachelot <xavier@bachelot.org> 0.4.9-1
- Update to 0.4.9.
- Rename to pnp4nagios to match other distros packages.

* Mon Apr 14 2008 Xavier Bachelot <xavier@bachelot.org> 0.4.7-5
- Log to file by default.
- Kill pnpsender man page.

* Mon Apr 07 2008 Xavier Bachelot <xavier@bachelot.org> 0.4.7-4
- Install inside of nagios html dir.

* Mon Apr 07 2008 Xavier Bachelot <xavier@bachelot.org> 0.4.7-3
- Provide properly named config files.
- Add missing Requires:.
- Add a logrotate conf file.

* Fri Apr 04 2008 Xavier Bachelot <xavier@bachelot.org> 0.4.7-2
- Add an initscript for npcd.

* Wed Mar 19 2008 Xavier Bachelot <xavier@bachelot.org> 0.4.7-1
- Initial build.
