import sys
import subprocess as sp

if len(sys.argv) != 3:
    print "Usage: python "+sys.argv[0]+" [registration number] [pac number]"
    sys.exit()
    
output = sp.check_output(["/usr/local/bin/phantomjs", "scrape.js", sys.argv[1], sys.argv[2]])

print output
