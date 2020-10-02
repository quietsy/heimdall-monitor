var esm = {};

esm.getSystem = function() {

    var module = 'system';
    esm.reloadBlock_spin(module);
    $.get('libs/'+module+'.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content tbody');
        esm.insertDatas($box, module, data);
        esm.reloadBlock_spin(module);
    }, 'json');

}

esm.getCpu = function() {
    var module = 'cpu';
    esm.reloadBlock_spin(module);
    $.get('libs/'+module+'.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content tbody');
        esm.insertDatas($box, module, data);
        esm.reloadBlock_spin(module);
    }, 'json');
}

esm.getDisk = function() {
    var module = 'disk';
    esm.reloadBlock_spin(module);
    $.get('libs/'+module+'.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content tbody');
        $box.empty();
        for (var line in data)
        {
            var html = '';
            html += '<tr>';

            if (typeof data[line].filesystem != 'undefined')
                html += '<td class="filesystem">'+data[line].filesystem+'</td>';

            html += '<td class="t-center" title="Used: '+data[line].used+'">'+data[line].free+'</td>';
            html += '<td class="t-center">'+data[line].total+'</td>';
            html += '</tr>';
            $box.append(html);
        }
        esm.reloadBlock_spin(module);
    }, 'json');
}

esm.getDocker = function() {
    var module = 'docker';
    esm.reloadBlock_spin(module);
    $.get('libs/'+module+'.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content tbody');
        $box.empty();
        for (var line in data)
        {
            var html = '';
            html += '<tr>';
            html += '<td class="filesystem">'+data[line].dname+'</td>';
            html += '<td class="t-center">'+data[line].dcpu+'</td>';
            html += '<td class="t-center">'+data[line].dmem+'</td>';
            html += '<td class="t-center">'+data[line].dnet+'</td>';
            html += '</tr>';
            $box.append(html);
        }
        esm.reloadBlock_spin(module);
    }, 'json');
}

esm.getDownloads = function() {
    var module = 'downloads';
    esm.reloadBlock_spin(module);
    $.get('libs/'+module+'.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content tbody');
        $box.empty();

        for (var line in data)
        {
            var html = '';
            html += '<tr>';
            html += '<td class="filesystem" title="Status: '+data[line].dstatus+'">'+data[line].dname+'</td>';
            html += '<td class="t-center">'+data[line].dsize+'</td>';
            html += '<td class="t-center" title="Seeders: '+data[line].dseeders+'">'+data[line].ddown+'</td>';
            html += '<td class="t-center">'+data[line].ddownloaded+'</td>';
            html += '</tr>';
            $box.append(html);
        }
        esm.reloadBlock_spin(module);
    }, 'json');

}

esm.getStreams = function() {
    var module = 'streams';
    esm.reloadBlock_spin(module);
    $.get('libs/'+module+'.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content tbody');
        $box.empty();

        for (var line in data)
        {
            var html = '';
            html += '<tr>';
            html += '<td class="filesystem">'+data[line].duser+'</td>';
            html += '<td class="t-center" title="'+data[line].dpaused+'">'+data[line].dmedia+'</td>';
            html += '<td class="t-center" title="'+data[line].ddetails+'">'+data[line].dtranscode+'</td>';
            html += '</tr>';
            $box.append(html);
        }
    
        esm.reloadBlock_spin(module);
    }, 'json');
}

esm.getAll = function() {
    esm.getSystem();
    esm.getCpu();
    esm.getDisk();
    esm.getDocker();
    esm.getDownloads();
    esm.getStreams();
}

esm.reloadBlock = function(block) {
    esm.mapping[block]();
}

esm.reloadBlock_spin = function(block) {
    var $module = $('.box#esm-'+block);
    $('.reload', $module).toggleClass('spin disabled');
}

esm.insertDatas = function($box, block, datas) {
    for (var item in datas)
    {
        $('#'+block+'-'+item, $box).html(datas[item]);
    }
}

esm.mapping = {
    all: esm.getAll,
    system: esm.getSystem,
    cpu: esm.getCpu,
    disk: esm.getDisk,
    docker: esm.getDocker,
    downloads: esm.getDownloads,
    streams: esm.getStreams,
};