#!/usr/bin/perl
use strict;

my $tfile = shift;
die "usage: $0 <templatefile>" unless defined $tfile;

if (system("grep -q '_url  */pnp4nagios/index' $tfile") == 0) {
    print "$tfile already has pnp4nagios template info\n";
    exit 0;
}


open(T,"<$tfile") || die "unable to open $tfile";
my (@templ) = (<T>);
close(T);


my ($genhost0,$genhost1);
my ($gensvc0, $gensvc1);
my $host = '';
my $service  = '';
my $inhost = 0;
my $inservice = 0;
my $j = -1;
foreach my $line (@templ) {
    $j++;

    if ($inhost) {
        if ($line =~ /^\s*\}/) {
            $inhost = 0;
            $genhost1 = $j if $host =~ /generic-host/;
        } elsif ($line =~ /^\s*name\s+(\S+)/) {
            $host = $1;
            $genhost0 = $j if $host =~ /generic-host/;
        }
        next;
    } elsif ($line =~ /^\s*define\s+host\s/) {
        $inhost = 1;
        $host = '';
        next;
    }

    if ($inservice) {
        if ($line =~ /^\s*\}/) {
            $inservice = 0;
            $gensvc1 = $j if $service =~ /generic-service/;
        } elsif ($line =~ /^\s*name\s+(\S+)/) {
            $service = $1;
            $gensvc0 = $j if $service =~ /generic-service/;
        }
    } elsif ($line =~ /^\s*define\s+service\s/) {
        $inservice = 1;
        $service = '';
    }
    
}

my $diduse;

$diduse = 0;
for ($j = $genhost0; $j <= $genhost1; $j++) {
    if ($templ[$j] =~ /^\s*use\s/) {
        $templ[$j] = ";".$templ[$j];
        splice(@templ,$j,0,"    use\t\t\t\t    host-pnp                ; graphing\n");
        $diduse=1;
        last;
    }
}
splice(@templ,$genhost0+1,0,"    use\t\t\t\t    host-pnp                ; graphing\n") unless $diduse;

my $j = -1;
foreach my $line (@templ) {
    $j++;

    if ($inservice) {
        if ($line =~ /^\s*\}/) {
            $inservice = 0;
            $gensvc1 = $j if $service =~ /generic-service/;
        } elsif ($line =~ /^\s*name\s+(\S+)/) {
            $service = $1;
            $gensvc0 = $j if $service =~ /generic-service/;
        }
    } elsif ($line =~ /^\s*define\s+service\s/) {
        $inservice = 1;
        $service = '';
    }
    
}

$diduse = 0;
for ($j = $gensvc0; $j <= $gensvc1; $j++) {
    if ($templ[$j] =~ /^\s*use\s/) {
        $templ[$j] = ";".$templ[$j];
        splice(@templ,$j,0,"    use\t\t\t\t    service-pnp                ; graphing\n");
        $diduse=1;
        last;
    }
}
splice(@templ,$gensvc0+1,0,"    use\t\t\t\t    service-pnp                ; graphing\n") unless $diduse;


foreach my $line (@templ) {
    print $line;
}

print "######################################################\n";
print "#\n";
print "# pnp4nagios\n";
print "#\n";
print "define host {\n";
print "       name host-pnp\n";
print "       action_url /pnp4nagios/index.php/graph?host=\$HOSTNAME\$&srv=_HOST_\n";
print "       register 0\n";
print "}\n";
print "\n";
print "define service {\n";
print "       name service-pnp\n";
print "       action_url /pnp4nagios/index.php/graph?host=\$HOSTNAME\$&srv=\$SERVICEDESC\$\n";
print "       register 0\n";
print "}\n";
