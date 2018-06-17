#!/bin/bash      

now=$(date +"%m_%d_%Y")
echo "Exportando el archivo del dia $now..."
php cron.php exportar > file-$now.cvs