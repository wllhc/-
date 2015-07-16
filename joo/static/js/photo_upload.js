/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

/**
 * 基本库
 *
 * @fileOverview
 * @author $_EYOUMBR_COPYRIGHT_$
 * @version $_EYOUMBR_VERSION_$
 */

// {{{ class EmUpload()

function EmUpload(op) 
{
    var options = {
        url:window.location.href,//请求提交到哪儿
        url2:window.location.href,//请求提交到哪儿
        place:null,//按钮放在哪儿
        postName:'Filedata',
        duration:100,
        container:null,//按钮放在哪儿
        multi:false,
        dataType:'json',
        prefix:'',
        params:{}, 
        onProgress:function(key, file, totalBytes, currentBytes, speed) {}, //进度条
        onError:function(key, file, error) {}, //出错
        onCancel:function(key, file) {}, //取消的时候触发
        onDone:function(key, file, file_info) {}, //完成的时候触发
        onStart:function(key, file) {} //post开始的时候触发
    }, div, _div, container, f, ipt, ipt2, __this = this, uploadKey=null,fileQueue = {};
        
    // {{{ function bind()
        
    var bind = function(node, ev, func, canUnbind)
    {
        canUnbind = !!canUnbind;
        if (canUnbind) {
            var f = func;
        } else {
            var f = function(ev) {
                var res = func.apply(node, [ev]);     
                if (res === false) {
                    if (document.all) {
                        ev.returnValue = false;
                        ev.cancelBubble = true;
                    } else {
                        ev.preventDefault();
                        ev.stopPropagation();
                    }
                }
            };
        }

        if (document.all) {
            node.attachEvent('on' + ev, f);
        } else {
            node.addEventListener(ev, f, false);
        }
    }

    // }}} functions end 
    // {{{ function fire()

    var fire = function(node, ev)
    {
        if (document.all) {
            ev = 'on' + ev;
            var evo = document.createEventObject();
            node.fireEvent(ev, evo);
        } else {
            var evt = document.createEvent('HTMLEvents');
            evt.initEvent(ev, true, true);
            node.dispatchEvent(evt);
        }
    }

    // }}}
    for (var p in op) {
        if (typeof(options[p]) !== 'undefined') {
            options[p] = op[p];
        }
    }

    div = '<div style="position:absolute; left:-1000px; width:20px;"><div style="position:relative; height:20px; width:40px; overflow:hidden;filter:alpha(opacity=0);opacity:0;"><form action="' + options.url + '" method="post" enctype="multipart/form-data"><input type="hidden" name="APC_UPLOAD_PROGRESS" value=""><input hidefocus=true type="file" name="' + options.postName + '" value="" style="cursor:pointer;position:absolute; right:0;filter:alpha(opacity=0);opacity:0;">';
    for (var p in options.params) {
        div += '<input type="hidden" name="' + p + '" value="' + options.params[p] + '">';
    }

    div += '</form></div></div>';

    _div = document.createElement('div');
    _div.innerHTML = div;
    if (options.container) {
        if (typeof(options.container) === 'string') {
            container = document.getElementById(options.container);
        } else {
            container = options.container;
        }
    } else {
        container = document.body;
    }
    if (container.tagName === 'TABLE') {
        //container.parentNode.appendChild(_div);
        container.rows[0].cells[0].appendChild(_div);
    } else {
        container.appendChild(_div);
    }
    div = _div.childNodes[0];
    div.style.zIndex = 9999999999;

    f = div.getElementsByTagName('form')[0];
    ipt = f[options.postName], ipt2 = f.APC_UPLOAD_PROGRESS;
        
    if (options.place) {
        if (typeof(options.place) === 'string') {
            var node = document.getElementById(options.place);
        } else {
            var node = options.place;
        }

        bind(node, 'mousemove', function(e) {
            var isFF = $.browser.mozilla;
            var pos = getPosition(node);
            var l=0,t=0;
            var position = $(container).css('position');
            if (position === 'absolute' || position === 'relative' || (!isFF && position === 'fixed')) {
                var pos1 = getPosition(container);
                l = pos1[0]; 
                t = pos1[1]; 
            }

            if (isFF && position === 'fixed') {
                l = document.documentElement.scrollLeft ?
                document.documentElement.scrollLeft :
                document.body.scrollLeft;
                t = document.documentElement.scrollTop ?
                document.documentElement.scrollTop :
                document.body.scrollTop;
                l = 0 - l;
                t = 0 - t;
            }
                
            if (e.clientX - 20 < pos[0] -1) {
                div.style.left = pos[0] -1 -l + 'px';
            } else if (e.clientX + 20 > pos[0] + 1 + node.offsetWidth){
                div.style.left = pos[0] + 1 -l + node.offsetWidth - 40 + 'px';
            } else {
                div.style.left = e.clientX - 20 -l + 'px';
            }
                
            if (e.clientY - 10 < pos[1] -1) {
                div.style.top = pos[1] -1 - t + 'px';
            } else if (e.clientY + 10 > pos[1] + 1 + node.offsetHeight){
                div.style.top = pos[1] + 1 -t + node.offsetHeight - 20 + 'px';
            } else {
                div.style.top = e.clientY - 10 -t + 'px';
            }
                
        });
            
        node.style.cursor = "default";
        bind(div, 'mouseover', function(e) {
            node.style.textDecoration = 'underline';
        });

        bind(div, 'mouseout', function(e) {
            node.style.textDecoration = 'none';
            div.style.left = '-1000px'; 
        });
    }


    // {{{ function addPostParam()
        
    this.addPostParam = function(k, v)
    {
        var ipts = f.getElementsByTagName('input'); 
        var find = false;
        for (var i=0; i<ipts.length; i++) {
            if (ipts[i].type !== 'hidden') {
                continue;
            }
            if (ipts[i].name === k) {
                ipts[i].value = v; 
                find = true;
                break;
            } 
        }

        if (find === false) {
            if (document.all) {
                try {
                    var ipt = document.createElement('<input type="hidden" name="' + k + '">');
                } catch (e) {
                    var ipt = document.createElement('input');
                    ipt.type = 'hidden';
                    ipt.name = k;
                }
            } else {
                var ipt = document.createElement('input');
                ipt.type = 'hidden';
                ipt.name = k;
            }

            ipt.value = v;
            f.appendChild(ipt);
        }
    }
    // }}}
    // {{{ function setPostParams()
        
    this.setPostParams = function(params)
    {
        var ipts = f.getElementsByTagName('input'); 
        for (var i=0; i<ipts.length; i++) {
            if (ipts[i].type !== 'hidden') {
                continue;
            }
            if (ipts[i].name === 'APC_UPLOAD_PROGRESS') {
                continue;
            }

            f.removeChild(ipts[i]);
        }
            
        options.params = {};
        for (var p in params) {
            if (document.all) {
                try {
                    var ipt = document.createElement('<input type="hidden" name="' + p + '">');
                } catch (e) {
                    var ipt = document.createElement('input');
                    ipt.type = 'hidden';
                    ipt.name = p;
                }
            } else {
                var ipt = document.createElement('input');
                ipt.type = 'hidden';
                ipt.name = p;
            }

            ipt.value = params[p];
            f.appendChild(ipt);
            options.params[p] = params[p];
        }
    }
    // }}}
    // {{{ function reset() 

    this.reset = function()
    {
        f.reset();
        var fileName = '';

        ipt.onchange = function() {
            var _uploadKey = options.prefix + '' + (new Date()).getTime() + '_' + Math.random(); 
            _uploadKey = _uploadKey.replace(/\./g, '_');
            fileName = ipt.value.lastIndexOf('\\') > -1 ? ipt.value.substr(ipt.value.lastIndexOf('\\')+1) : 
            (ipt.value.lastIndexOf('/') > -1 ? ipt.value.substr(ipt.value.lastIndexOf('/')+1) : ipt.value);
            fileQueue[_uploadKey] = true;
            if (typeof(options.onStart) === 'function') {
                if (false === options.onStart.apply(__this, [_uploadKey, fileName])) {
                    delete fileQueue[_uploadKey];
                    __this._stop(_uploadKey); //停止正在上传的
                    __this.reset();
                    return;
                }
            }

            if (!options.multi && uploadKey) {
                __this._stop(uploadKey); //停止正在上传的
            } 
            var uploadKey2 = _uploadKey;
            uploadKey = uploadKey2;

            ipt2.value = uploadKey2;
            //<iframe id="frm" name="frm" frameborder=0 src="about:blank" style="display:none"-->
            if (document.all) {
                try {
                    var frm = document.createElement('<iframe name="' + 'frame_' + uploadKey2 + '" src="javascript:void(0);">');
                } catch (e) {
                    var frm = document.createElement('iframe');
                    frm.src = "javascript:void(0)";
                    frm.name = 'frame_' + uploadKey2;
                }
            } else {
                var frm = document.createElement('iframe');
                frm.src = "javascript:void(0)";
                frm.name = 'frame_' + uploadKey2;
            }
            frm.id = 'frame_' + uploadKey2;
            frm.setAttribute('frameborder', 0);
            frm.style.display = 'none';
            container.appendChild(frm);
            f.target = 'frame_' + uploadKey2;

            var result = false;

            //var tryTime = 0;//尝试次数

            var getStatus = function(isComplete) {
                isComplete = !!isComplete;
                $.ajax({
                    url:(options.url2.indexOf('?') > -1 ? (options.url2 + '&key=' + uploadKey2) : (options.url2 + '?key=' + uploadKey2)) + (isComplete ? '&is_done=1' : ''),
                    dataType:'json',
                    cache:false,
                    success:function(o) {
                        if (o.clean) {
                            return;
                        }
                        setTimeout(function() {
                            if (o === false) {
                                //上传失败
                                //获取不到APC信息，可能文件太大。
                                /*
                                    tryTime++;
                                    if (tryTime >= 3) {
                                        delete fileQueue[uploadKey2];
                                        if (typeof(options.onError) === 'function') {
                                            options.onError.apply(__this, [uploadKey2, fileName, '文件太大。']);
                                        }

                                        __this._stop(uploadKey2);
                                        //getStatus(true);
                                        return;
                                    }
                                    */
                                setTimeout(function() {
                                    getStatus()
                                    }, options.duration);
                                return;
                            }

                            //{"total":8115843,"current":8115843,"rate":89896982.565182,"filename":"","name":"","cancel_upload":0,"done":1,"start_time":1299134776.7796}
                            if (typeof(options.onProgress) === 'function') {
                                options.onProgress.apply(__this, [uploadKey2, fileName, o.total, o.current, o.current/(o.now-o.start_time)]); 
                            }

                            if (result === false) {
                                setTimeout(function() {
                                    getStatus()
                                    }, options.duration);
                            }
                            
                        
                                if (o.done == 0) {
                                    setTimeout(function() {getStatus()}, options.duration);
                                } else {
                                    if (o.cancel_upload != 0) {
                                        delete fileQueue[uploadKey2];
                                        if (o.cancel_upload == 1) {
                                            if (typeof(options.onError) === 'function') {
                                                options.onError.apply(__this, [uploadKey2, fileName, 'file size limited.']);
                                            }
                                        }
                                        if (typeof(options.onCancel) === 'function') {
                                            options.onCancel.apply(__this, [uploadKey2, fileName]);
                                        }
                                        getStatus(true);
                                        __this._stop(uploadKey2);
                                    }
                                    /*
                                    if (o.done == 1 && o.cancel_upload == 0) {
                                        if (result === false) {
                                            setTimeout(function() {getStatus()}, options.duration);
                                        } else {
                                            delete fileQueue[uploadKey2];
                                            if (typeof(options.onDone) === 'function') {
                                                var oo = $(frm.contentWindow.document.body).text();
                                                if (options.dataType === 'json') {
                                                    try {
                                                        eval('(oo=' + oo + ')');
                                                    } catch(e) {
                                                        if (typeof(options.onError) === 'function') {
                                                            options.onError.apply(__this, [uploadKey2, fileName, 'parse error.']);
                                                        }
                                                        return; 
                                                    }
                                                }
                                                options.onDone.apply(__this, [uploadKey2, fileName, oo]);
                                            }
                                            getStatus(true);
                                            __this._stop(uploadKey2);
                                        }
                                    }
									*/
                                }
                                
                        },0);

                    },
                    error:function(xmlHttp, error, e) {
                    /*
                            delete fileQueue[uploadKey2];
                            if (typeof(options.onError) === 'function') {
                                options.onError.apply(__this, [uploadKey2, fileName, error]);
                            }
                            __this._stop(uploadKey2);
                            */
                    }
                });    
            };
            $(frm).bind('uploadCancel', function() {
                if (result === false) {
                    delete fileQueue[uploadKey2];
                    if (typeof(options.onCancel) === 'function') {
                        options.onCancel.apply(__this, [uploadKey2, fileName]);
                    }
                    result = true;
                    getStatus(true);
                }       
            });

            bind(frm, 'load', function() {
                if (result === true) {
                    return;
                }
                result = true;

                delete fileQueue[uploadKey2];
                if (typeof(options.onDone) === 'function') {
                    var oo = $(frm.contentWindow.document.body).text();
                    if (options.dataType === 'json') {
                        try {
                            eval('(oo=' + oo + ')');
                        } catch(e) {
                            if (typeof(options.onError) === 'function') {
                                options.onError.apply(__this, [uploadKey2, fileName, 'parse error.']);
                            }
                            return; 
                        }
                    }
                    options.onDone.apply(__this, [uploadKey2, fileName, oo]);
                }
                getStatus(true);
                __this._stop(uploadKey2);
            });
            f.submit();
            setTimeout(function() {
                getStatus()
                }, options.duration);

            __this.reset();

        };
    }

    // }}}
    // {{{ function stop()

    this.stop = function(k) 
    {
        if (typeof(k) === 'undefined') {
            k = uploadKey;
        }
        __this._stop(k);
    //__this.reset();
    }

    // }}}
    // {{{ function _stop()

    this._stop = function(k) 
    {
        var frm = document.getElementById('frame_' + k); 
        if (frm) {
            $(frm).trigger('uploadCancel');
            frm.contentWindow.location.href = 'about:blank';
            frm.parentNode.removeChild(frm);

        }

    }

    // }}}
    // {{{ function getElement()


    this.getElement = function() 
    {
        return div;
    }

    // }}}
    // {{{ function getCount()

    this.getCount = function()
    {
        return getCount(fileQueue);
    }
    // }}}
    this.reset();
}

// }}} end functions
