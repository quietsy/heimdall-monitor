# Heimdall Monitor
###### Source - [heimdall-monitor](https://github.com/quietsy/heimdall-monitor)
###### Mod Source - [heimdall-monitor-mod](https://github.com/quietsy/heimdall-monitor-mod)

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
1. Stop the heimdall container
1. Remove the added environment variables from the docker compose
1. If you enabled docker stats, remove stats.sh and the cron job from the host
1. In heimdall's config folder delete monitor, nginx and php
1. Start the heimdall container
1. If you want to restore the nginx and php folders, you can find them in the config folder under backup
