 #!/bin/sh
if [ "$(stat -c '%a' /var/www/public/fileadmin)" = "2775" ]; then
	exit 0; 
else 
	exit 1; 
fi