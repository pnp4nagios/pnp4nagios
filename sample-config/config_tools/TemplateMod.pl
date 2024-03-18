#!/usr/bin/perl
use strict;
use Getopt::Long;

my ($infile, $outfile, $help);

GetOptions("input|i=s" => \$infile,
           "output|o=s" => \$outfile,
           "help|h" => \$help,
    );
if (defined($help)) {
    help();
    exit 0;
}
die "input file missing" unless defined $infile && -e $infile;
die "output file missing" unless defined $outfile;

    




if (system("grep -q '_url  */pnp4nagios/index' $infile") == 0) {
    print "$infile already has pnp4nagios template info\n";
    exit 0;
}


open(T,"<$infile") || die "unable to open $infile";
my (@templ) = (<T>);
close(T);
open(OUT, ">$outfile") || die "unable to open $outfile";



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

$j = -1;
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
    print OUT $line;
}

print OUT "######################################################\n";
print OUT "#\n";
print OUT "# pnp4nagios\n";
print OUT "#\n";
print OUT "define host {\n";
print OUT "       name host-pnp\n";
print OUT "       action_url /pnp4nagios/index.php/graph?host=\$HOSTNAME\$&srv=_HOST_\n";
print OUT "       register 0\n";
print OUT "}\n";
print OUT "\n";
print OUT "define service {\n";
print OUT "       name service-pnp\n";
print OUT "       action_url /pnp4nagios/index.php/graph?host=\$HOSTNAME\$&srv=\$SERVICEDESC\$\n";
print OUT "       register 0\n";
print OUT "}\n";
close(OUT);
exit 0;
