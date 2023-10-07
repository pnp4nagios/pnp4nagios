#!/bin/bash
# collect up all the "base" files into a tar file
# exclude the stuff that gets created during a build

# 
me=`realpath -e -L $0`
VERSION=$1
if [ "x${VERSION}" == "x" ] ;
then
    echo "usage: $0 version"
    exit 1
fi

distdir=`dirname $me`
echo "distdir $distdir"
tdir=`mktemp -p "/tmp" -d "pnp4nagiosDIST_XXXXXXXX"`
echo "tempdir $tdir"
pushd $tdir >/dev/null

#directory for dist 
mkdir pnp4nagios-${VERSION}


#populate with symbolic links from main code directory
pushd pnp4nagios-${VERSION} >/dev/null

#echo "in tar base dir " `pwd`

for f in AUTHORS ChangeLog ci configure.ac config.sub contrib COPYING \
                dist helpers include INSTALL install-sh lib \
                Makefile.in man README.md sample-config scripts \
                share src subst.in summary.in THANKS ; 
do
#    echo "ln -s $distdir/../$f ."
    ln -s $distdir/../$f  .  
done

ls

popd >/dev/null

# any file that is a 'FILE.in' is kept, but the
# resulting 'FILE' is not. 
find -L pnp4nagios-${VERSION} -name '*.in' >dist.exclude
sed -i 's/.in$//' dist.exclude
# ...and no archives in dist, either
find -L pnp4nagios-${VERSION} -name 'pnp4nagios-*.tgz' >>dist.exclude


#GNU makefile 'dist' guideline is that files in the archive
#should have world rx permissions
chmod 0755 -R pnp4nagios-${VERSION}

#make the tar archive, rereferencing symbolic links
tar chzvf pnp4nagios-${VERSION}.tgz -X dist.exclude pnp4nagios-${VERSION}
mv pnp4nagios-${VERSION}.tgz $distdir
popd >/dev/null
# clean up temp directory
rm -rf $tdir


