# heimdall-monitor

## Warning - this is an unpolished example of a dashboard, it doesn't support all configurations and services.

![Image](https://i.imgur.com/9uHibY0.jpg)

## Install
1. Copy to /`<path of heimdall config>`/monitor
1. Edit docker-compose according to the example below
1. Edit the reverse proxy according to the example below (replace `<heimdall-domain>`)
1. For docker stats:
    1. Copy stats.sh to your host
    1. Edit stats.sh and fix the path to your /`<path of heimdall config>`/monitor/libs/data/stats folder
    1. Create a cron job to execute stats.sh once a minute

## Docker Compose
```YAML
  heimdall:
    image: linuxserver/heimdall
    container_name: heimdall
    environment:
      - PUID=${PUID}
      - PGID=${PGID}
      - TZ=${TZ}
      - DOWNLOADERURL=http://qbittorrent:8080 #optional - for qbittorrent downloads
      - DOWNLOADERAUTH=username=<qbittorrent-user>&password=<qbittorrent-password> #optional - for qbittorrent downloads
      - STREAMSAPI=http://jellyfin:8096/sessions?api_key=<api-key> #optional - for jellyfin streams
      - DOCKERSTATS=true #optional - for docker top
    volumes:
      - /<path of heimdall config>:/config
      - /<path of heimdall config>/monitor:/var/www/localhost/heimdall/storage/app/public/monitor #required - change to your heimdall config folder
      - /<path of heimdall config>/monitor/www.conf:/etc/php7/php-fpm.d/www.conf #required - change to your heimdall config folder
    networks:
      - internal
    restart: always
```

## Reverse Proxy
```Nginx
  server {
      listen 443 ssl;
      listen [::]:443 ssl;
      server_name heimdall.*;
      client_max_body_size 0;

      location / {
          include /config/nginx/proxy.conf;
          resolver 127.0.0.11 valid=30s;
          set $upstream_app heimdall;
          set $upstream_port 443;
          set $upstream_proto https;
          proxy_pass $upstream_proto://$upstream_app:$upstream_port;

          proxy_set_header Accept-Encoding "";
          sub_filter
          '<main>'
          '<main>
          <style type="text/css"> 
              @media screen and (max-width: 1600px) { #myFrame { height: 350px; } }
              @media screen and (max-width: 900px) { #myFrame { height: 500px; } } 
          </style>
          <iframe src="http://<heimdall-domain>/storage/monitor/" width="100%" height="200px" id="myFrame" frameborder="0" allowtransparency="true">
          </iframe>';
          sub_filter_once on;
      }
  }
```