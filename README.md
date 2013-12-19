SofaChamps
==========

Dominate without breaking a sweat ... or even getting off the couch

Gearman Installation
====================
apt-get install gearman-job-server libgearman-dev
apt-get install php-pear
pecl install gearman

Supervisor Installation
=======================
apt-get install supervisor

Sample supervisord configuration
[program:sofachamps]
command=/var/www/sofachamps/app/console gearman:worker:execute
numprocs=1
stdout_logfile=/var/log/sofachamps/gearman.log
autostart=true
autorestart=true
