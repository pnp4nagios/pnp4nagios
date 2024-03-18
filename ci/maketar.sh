#!/bin/sh
# collect up all the "base" files into a tar file
# exclude the stuff that gets created during a build

# usage: maketar.sh [version] [release] [releasedate]
# defaults from configure.ac if not provided

# also makes a zip file

startdir=$(pwd)
me=$(realpath -e -L $0)
distdir=$(dirname $me)
basedir=$(realpath -e -L $distdir/..)
#echo "distdir $distdir"
#echo "basedir $basedir"

VERSION=$1
CVER=$(awk -F, '/^AC_INIT/ {print $2}' $basedir/configure.ac|tr -d '[]')
if test "x${VERSION}" = "x"  ;
then
    VERSION=$CVER
    echo "VERSION ($VERSION) from configure.ac"
elif test "${VERSION}" != "${CVER}"  ;
then
    echo "$0 version requested $VERSION mismatch configure.ac $CVER"
    exit 1
fi
    
RELEASE=$2
if test "x${RELEASE}" = "x"  ;
then
    RELEASE=$(awk -F'"' '/^PACKAGE_RELEASE=/{print $2}' $basedir/configure.ac)
    echo "RELEASE ($RELEASE) from configure.ac"
fi

RELDATE=$3
if test "x${RELDATE}" = "x"  ;
then
    RELDATE=$(awk -F'"' '/^PKG_REL_DATE=/{print $2}' $basedir/configure.ac)
    echo "RELDATE ($RELDATE) from configure.ac"
fi

echo "Version $VERSION Release $RELEASE Date $RELDATE"

tdir=$(mktemp -p "/tmp" -d "pnp4nagiosDIST_XXXXXXXX")
#echo "tempdir $tdir"
cd $tdir

#directory for dist 
mkdir pnp4nagios-${VERSION}


#populate with symbolic links from main code directory
cd pnp4nagios-${VERSION} 


for f in AUTHORS ChangeLog ci config.guess config.sub contrib \
                 configure aclocal.m4 autoconf-macros \
                 pnp4nagios.te pnp4nagios.fc.in \
                 COPYING helpers include INSTALL install-sh lib \
                 Makefile.in man README.md sample-config scripts \
                 share src subst.in summary.in THANKS ; 
do
#    echo "ln -s $basedir/$f ."
    ln -s $basedir/$f  .  
done

# update version/release/release_date 
cp $basedir/configure.ac .
touch configure.ac
rm ci/pnp4nagios.spec
cp ci/pnp4nagios.spec.in ci/pnp4nagios.spec
sed -i "s/@PACKAGE_VERSION@/${VERSION}/" ci/pnp4nagios.spec
sed -i "s/@PACKAGE_RELEASE@/${RELEASE}/" ci/pnp4nagios.spec
sed -i "s/PACKAGE_RELEASE=\"[^\"]*\"/PACKAGE_RELEASE=\"${RELEASE}\"/" \
    configure.ac
sed -i "s/PKG_REL_DATE=\"[^\"]*\"/PKG_REL_DATE=\"${RELDATE}\"/" \
    configure.ac


cd ..

# any file that is a 'FILE.in' is kept, but the
# resulting 'FILE' is not. 
find -L pnp4nagios-${VERSION} -name '*.in' >dist.exclude
sed -i 's/.in$//' dist.exclude
# .. no object files
find -L pnp4nagios-${VERSION} -name '*.o' >>dist.exclude
# .. no binaries
echo "./pnp4nagios-${VERSION}/src/npcd" >>dist.exclude
echo "./pnp4nagios-${VERSION}/src/utils" >>dist.exclude
echo "./pnp4nagios-${VERSION}/src/pnpsender" >>dist.exclude
# ...and no archives in dist, either
find -L pnp4nagios-${VERSION} -name 'pnp4nagios-*.tgz' >>dist.exclude
find -L pnp4nagios-${VERSION} -name 'pnp4nagios-*.zip' >>dist.exclude
find -L pnp4nagios-${VERSION} -path '*/ci/outputs' >>dist.exclude
find -L pnp4nagios-${VERSION} -path '*/ci/outputs/*' >>dist.exclude

# exception is pnp4nagios.spec
grep -v ci/pnp4nagios.spec dist.exclude >dist.x
mv dist.x dist.exclude

#GNU makefile 'dist' guideline is that files in the archive
#should have world rx permissions
chmod 0755 -R pnp4nagios-${VERSION}

#make the tar archive, rereferencing symbolic links
tar chzf pnp4nagios-${VERSION}.tgz -X dist.exclude --exclude-backups pnp4nagios-${VERSION}
mv pnp4nagios-${VERSION}.tgz $distdir

zip -r -q pnp4nagios-${VERSION}.zip  pnp4nagios-${VERSION}/ -x\*~ -x\*\# -x\@dist.exclude
mv pnp4nagios-${VERSION}.zip $distdir
cd $startdir
# clean up temp directory
rm -rf $tdir


