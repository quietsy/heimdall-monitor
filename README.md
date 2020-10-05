# heimdall-monitor

## Warning - this is an alpha, it doesn't support all configurations and services.

![Image](https://i.imgur.com/9uHibY0.jpg)

## Install
1. Edit docker-compose according to the example below
1. **OPTIONAL** - for docker stats:
    1. Move stats.sh to the host
    1. Edit stats.sh and fix the path to the /`<path of heimdall config>`/monitor/libs/data/stats folder
    1. Create a cron job to execute stats.sh once a minute

## Docker Compose

| Parameter | Function |
| --- | --- |
| `DOCKER_MODS=quietsy/heimdall-monitor-mod:latest` | enables the mod |
| `QBITTORRENTURL=http://qbittorrent:8080` | **optional** - enable qbittorrent downloads monitoring |
| `QBITTORRENTAUTH=username=<qbittorrent-user>&password=<qbittorrent-password>` | **optional** - login details for qbittorrent downloads monitoring |
| `JELLYFINAPI=http://jellyfin:8096/sessions?api_key=<api-key>` | **optional** - enable active jellyfin streams monitoring |
| `DOCKERSTATS=true` | **optional** - enable docker stats monitoring (requires additional installation steps) |

```YAML
  heimdall:
    image: linuxserver/heimdall
    container_name: heimdall
    environment:
      - PUID=${PUID}
      - PGID=${PGID}
      - TZ=${TZ}
      - DOCKER_MODS=quietsy/heimdall-monitor-mod:latest #required
      - QBITTORRENTURL=http://qbittorrent:8080 #optional - for qbittorrent downloads
      - QBITTORRENTAUTH=username=<qbittorrent-user>&password=<qbittorrent-password> #optional - for qbittorrent downloads
      - JELLYFINAPI=http://jellyfin:8096/sessions?api_key=<api-key> #optional - for jellyfin streams
      - DOCKERSTATS=true #optional - for docker stats
    volumes:
      - /<path of heimdall config>:/config
    networks:
      - internal
    restart: always
```

## Uninstall
1. Remove the added environment variables from the docker compose
1. Remove stats.sh and the cron job from the host
1. In heimdall's config folder:
    1. Remove the monitor folder
    1. Remove nginx/site-confs/default
    1. Move nginx/default.backup to nginx/site-confs/default
    1. Remove php/www2.conf
    1. Move php/www2.conf.backup to php/www2.conf
