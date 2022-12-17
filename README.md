# PNP4Nagios

As a long-time user of `pnpnagios`, I am trying to give the project a
good home as we try to further develop this project..


## Getting Started

Well, for now, I'm advocating installing from source... my source, as we
move forward in to further developing this project.

Instructions can be found in [this gist](https://gist.github.com/russellvt/051fa43592778a41e53cb423b791bab6).


## Final README from lingej/pnp4nagios

```
This project is no longer active.

Thank you for the many years we have worked together on this project.

Feel free to take over the project.

This repository is no longer maintained!
```


## About Me

I am a long time Nagios/Icinga/Monitoring/Devops Professional. I've been
"the toolsguy" long from "devops" was even a "new" term.



## build RPM from git

The spec file is in the "dist" subdirectory.  Install git-build-rpm (it's on
github) and, as an example:

cd (main directory of this git package; where this README.md file is located)
git build-rpm --rpm-dir /home/local/lane/rpmbuild --dist .fc35

The --rpm-dir option is to keep the .srpm file from the build, the
--dist option is to avoid the longwinded "-%{version}-%{release}%{timestamp}.%{dist}..." package name.


