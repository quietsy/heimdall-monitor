#!/bin/sh
stats=`docker stats --no-stream --format "table {{.Name}},{{.CPUPerc}},{{.MemUsage}},{{.NetIO}}"`
echo "$stats" > /mnt/data/apps/heimdall/monitor/libs/data/stats
sleep 20
stats=`docker stats --no-stream --format "table {{.Name}},{{.CPUPerc}},{{.MemUsage}},{{.NetIO}}"`
echo "$stats" > /mnt/data/apps/heimdall/monitor/libs/data/stats
sleep 20
stats=`docker stats --no-stream --format "table {{.Name}},{{.CPUPerc}},{{.MemUsage}},{{.NetIO}}"`
echo "$stats" > /mnt/data/apps/heimdall/monitor/libs/data/stats