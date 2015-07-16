// {{{ class Message
/**
 * 提示信息类
 *
 * @class
 */
function Message() {
    var __this = this;

    this.__thisName = "this";

    this.init = function() {

    }

    // {{{ function show()

    /**
     * 提示信息显示
     *
     * @param {string} msg 提示信息
     * @param {string} type success|fail 提示信息类型
     * @param {object} obj 提示信息要放到的对象位置
     */ 
    this.show = function(msg, type, obj, time) {
        __this.hide();
        
        var _dom = document.createElement("div");
        _dom.id = "message";
        _dom.className = "msgbox";

        var _domChild = document.createElement("div");
        _domChild.innerHTML = msg;

        switch (type) {
            case "success":
                _domChild.className = "msg_suc";
                break;

            case "fail":
            case "failure":
                _domChild.className = "msg_err";
                break;
        }

        _dom.appendChild(_domChild);

        if ("undefined" === typeof obj || null == obj) {
            obj = document.body;
        }
        
        if ("object" === typeof obj) {
            obj.appendChild(_dom);
        }

        if ("undefined" === typeof time) {
            time = 3000;
        }
        
        setTimeout(function() {__this.hide();}, time);
    }
    
    // }}}
    // {{{ function remove()
    
    /**
     * 提示信息隐藏 
     */
    this.remove = function() {
        if (null !== document.getElementById("message")) {
            document.getElementById("message").parentNode.removeChild(document.getElementById("message"));
        }   
    }   
        
    // }}}
    // {{{ function hide()
    
    /**
     * 提示信息隐藏 remove()函数的别名 
     */
    this.hide = function() {
        if (null !== document.getElementById("message")) {
            document.getElementById("message").parentNode.removeChild(document.getElementById("message"));
        }
        //document.getElementById("message").style.display = "none";
    }

    // }}}

    __this.init();
}

// }}}



var fMessage = new Message();
