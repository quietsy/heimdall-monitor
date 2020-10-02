# heimdall-monitor

  heimdall:
    image: linuxserver/heimdall
    container_name: heimdall
    environment:
      - PUID=${PUID}
      - PGID=${PGID}
      - TZ=${TZ}
      - DOWNLOADERURL=${DOWNLOADERURL}
      - DOWNLOADERAUTH=${DOWNLOADERAUTH}
      - STREAMSAPI=${STREAMSAPI}
      - DOCKERSTATS=true
    volumes:
      - ${DATADIR}/apps/heimdall:/config
      - ${DATADIR}/apps/heimdall/monitor:/var/www/localhost/heimdall/storage/app/public/monitor
      - ${DATADIR}/apps/heimdall/monitor/www.conf:/etc/php7/php-fpm.d/www.conf
    networks:
      - internal
    restart: always


    server {
    listen 80;
    server_name homepage.x;
    client_max_body_size 0;
    if ($lan-ip = no) { return 404; }

    location / {
        include /config/nginx/proxy.conf;
        resolver 127.0.0.11 valid=30s;
        set $upstream_app heimdall;
        set $upstream_port 80;
        set $upstream_proto http;
        proxy_pass $upstream_proto://$upstream_app:$upstream_port;

        proxy_set_header Accept-Encoding "";
        sub_filter
        '<main>'
        '<main>
        <style type="text/css"> 
            @media screen and (max-width: 1600px) { #myFrame { height: 350px; } }
            @media screen and (max-width: 900px) { #myFrame { height: 500px; } } 
        </style>
        <iframe src="http://homepage.x/storage/monitor/" width="100%" height="200px" id="myFrame" frameborder="0" allowtransparency="true">
        </iframe>';
        sub_filter_once on;
    }
}