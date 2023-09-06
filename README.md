# PNP4Nagios

The new/current place for the pnp4nagios organization is
   https://github.com/pnp4nagios
With this *particular* version residing in
   https://github.com/pnp4nagios/celane      


This is a fork of [@lingej's original pnp4nagios package](https://github.com/lingej/pnp4nagios).

As a long-time user of `pnpnagios`, I am trying to give the project a
good home as we work to further develop and enhance this project. Please
contact me if you would like to help.

The original source was retired on commit 5e09f538373ac4310a13355746bb3d3a10eb7bef
(Version pnp-0.6.?? ??/??/2017 on 2022/Jan/16). Many thanks to Joerg Linge and his
awesome contributors for what we have, now!


## Getting Started

For now, I'm advocating installing from source (**my** source), as we move
forward into further developing this project.

Instructions can be found in
[this gist](https://gist.github.com/russellvt/051fa43592778a41e53cb423b791bab6).


## Documentation

The old site for documentation at https://docs.pnp4nagios.org is no longer
up; the replacement is https://github.com/pnp4nagios/docs
It's possible that there are still some references to the old site scattered
here and there. 


## About Me

I am a long time Nagios/Icinga/Monitoring/Devops Professional. I've been
"the toolsguy" long from "devops" was even a "new" term.



## build RPM from git

The spec file is in the "dist" subdirectory.  Install git-build-rpm (it's on
github, surprise!) and, as an example:

HERE=(main directory of this git package; where this README.md file is located)
cd $HERE
git build-rpm --rpm-dir /home/local/lane/rpmbuild --spec dist/pnp4nagios.spec --dist .fc35

The --rpm-dir option is to keep the .srpm file from the build (it will be
in your usual SRPMS subdirectory in your rpmbuild tree), the
--dist option is to avoid the longwinded "-%{version}-%{release}%{timestamp}.%{dist}..." package name. The rpms will get copied from the rpmbuild tree to
$HERE. 

You can also just get the source code, do a
./configure
then
make
etc....but some of us (me) prefers to have a software package, to organize
software installation/update/removal. 

