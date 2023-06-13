# Local

```bash
#copy and edit .env
cp .env.example .env
#install vendor
composer install
#generate key
php artisan key:generate
#create symbol link (Optional)
php artisan storage:link
#generate docs
php artisan scribe:generate
#start local server (Optional)
php artisan serve
#Run scheduler locally (Optional)
php artisan schedule:work
```

# Dev Server

```bash
#copy and edit .env
cp .env.example .env
#install vendor
composer install --no-dev
#generate key
php artisan key:generate
#create symbol link (Optional)
php artisan storage:link
#generate docs
php artisan scribe:generate
#check supervisor status
sudo systemctl start supervisord
#setup supervisor
sudo vim /etc/supervisord.d/cheers.ini
#read new config
sudo supervisorctl reread
#update config
sudo supervisorctl update
#restart
sudo supervisorctl restart cheers-octane:
#setup apache
sudo vim /etc/httpd/conf.d/cheers-le-ssl.conf
#setup cronjob
crontab -e
* * * * * cd /var/www/html/cheers/admin/ && php artisan schedule:run >>/dev/null 2>&1
#install octane(Swoole)
php artisan octane:install

```

## Supervisor Config

```bash
[program:cheers-octane]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/cheers/admin/artisan octane:start --server=swoole --max-requests=250 --workers=1 --task-workers=1 --port=9000
autostart=true
autorestart=true
user=ec2-user
redirect_stderr=true
stdout_logfile=/var/log/supervisor/cheers-octane.log
stopwaitsecs=3600
```

## Apache Config

```bash
<IfModule mod_ssl.c>
<VirtualHost *:443> 
    DocumentRoot "/var/www/html/cheers/"
    ServerName "cheers.emohk.dev"
    <Directory "/var/www/html/cheers/">
        AllowOverride All
        Require all granted
    </Directory>
RewriteEngine on
# Some rewrite rules in this file were disabled on your HTTPS site,
# because they have the potential to create redirection loops.

# RewriteCond %{SERVER_NAME} =cheers.emohk.dev
# RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]
SSLCertificateFile /etc/letsencrypt/live/cheers.emohk.dev/fullchain.pem
SSLCertificateKeyFile /etc/letsencrypt/live/cheers.emohk.dev/privkey.pem
Include /etc/letsencrypt/options-ssl-apache.conf

    ProxyRequests Off
    ProxyPreserveHost On
    ProxyVia Full
    <Proxy *>
        Require all granted
    </Proxy>
	ProxyPass /admin/ http://127.0.0.1:9000/
	ProxyPassReverse /admin/ http://127.0.0.1:9000/
</VirtualHost>
</IfModule>
```

## Setup Stripe webhook

- Endpoint: https://cheers.emohk.dev/admin/api/v1/services/webhook
- Listening for: checkout.session.completed

---

# Deploy

```bash
#disable the debug tools in .env
CLOCKWORK_ENABLE=false
LOG_VIEWER_ENABLED=false
```

# Example link

Host: https://cheers.emohk.dev/admin

Docs: https://cheers.emohk.dev/admin/docs

Log Viewer: https://cheers.emohk.dev/admin/log-viewer

ClockWork(For debug API call): https://cheers.emohk.dev/admin/clockwork/app

---

# Notes

1. 'Use "Accept-Language" as lang Header, support `tc` or `en`'
2. Call `sanctum/csrf-cookie` before call api
3. restart the supervisor process when any change is made
