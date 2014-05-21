KISSY.add(function(S, julog){

    function speedtest(v){
        console.log(v);
        julog.track('speed', 23, 'speed.home:1', '[v' + v + ']');
    }

    var __load_pre_img__ = function(uri, callback){
        var node = document.createElement('img'),
            NULL = null,
            delay = setTimeout;
        function complete(name){
            node.onload = node.onabort = node.onerror = complete = NULL;

            setTimeout(function(){
                callback.call(node, {type: name});
                node = NULL;
            }, 0);
        };
        callback && S.each(['load', 'abort', 'error'], function(name){
            node['on' + name] = function(){
                complete(name);
            };
        });
        node.src = uri;
        callback && node.complete && complete && complete('load');
        return node;
    };

    function showspeed(st){
        var fs = 76946 / 1024;  //img.jpg文件大小(K)
        var l = 2;    //小数点的位数
        var et = new Date();
        var speedtime = fs * 1000 / (et - st);
        var lnum = Math.pow(10, l);
        return Math.round(speedtime * lnum) / lnum; // kb/s
    }
    function init(){
        setTimeout(function(){
            var st = new Date();
            __load_pre_img__('http://gtms04.alicdn.com/tps/i4/T1fCqXFwxdXXbkkrTW-620-220.jpg_Q90.jpg', function(){
                speedtest(showspeed(st));
            });
        }, 1000);
    }

    return {
        init: init
    }

},{
    requires: ['jbc/julog']
});
