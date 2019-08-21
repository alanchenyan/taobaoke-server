//window.url="http://localhost:8080/AppFanlishop/";

//window.url = "http://test.taoquanbaapp.com/AppFanlishop/";
window.url = "http://web.taoquanbaapp.com/AppFanlishop/";

var http = function (url, param, success, error) {
    /*    showLoading(); */
    $.post(window.url + url, param, function (res) {
        /* hideLoading(); */
        res = JSON.parse(res);
        if (+res['status']) {
            //成功跳转
            success(res['msg']);
        } else {
            //TODO失败调用android ios的错误提示
            if (error) {
                error(res['msg']['errorMsg']);
            } else {
                alert(res['msg']['errorMsg'])
                // alertMsg("错误信息", res['msg']);
            }
        }
    })
}
 
 

function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var reg_rewrite = new RegExp("(^|/)" + name + "/([^/]*)(/|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    var q = window.location.pathname.substr(1).match(reg_rewrite);
    if (r != null) {
        return unescape(r[2]);
    } else if (q != null) {
        return unescape(q[2]);
    } else {
        return null;
    }
}






function alertMsg(title, content) {
    var share = '{"title":"' + title + '","content":"' + content + '"}';
    jsonMethod = '{"method":"alert","content":' + share + '}';
    console.log(jsonMethod);
    if (isAndroid()) {
        //itemid
        androidObj.call(jsonMethod);
    } else if (isIos()) {
        jsonMethod = encodeURIComponent(jsonMethod);
        loadURL("iosobj://" + jsonMethod);
    }
}

 
function jump(methods,share) {

    /* var share = '{}'; */
    jsonMethod = '{"method":"'+methods+'","content":' + JSON.stringify(share) + '}';
    console.log(jsonMethod);
    if (isAndroid()) {
        //itemid  
        androidObj.call(jsonMethod);
    } else if (isIos()) {
        jsonMethod = encodeURIComponent(jsonMethod);
        loadURL("iosobj://" + jsonMethod);
    }
}


function jumpQQ() {

    var share = '{}';
    jsonMethod = '{"method":"qq","content":' + share + '}';
    console.log(jsonMethod);
    if (isAndroid()) {
        //itemid  
        androidObj.call(jsonMethod);
    } else if (isIos()) {
        jsonMethod = encodeURIComponent(jsonMethod);
        loadURL("iosobj://" + jsonMethod);
    }
}

function jumpNiuDan(){
    
    var share = '{}';
    jsonMethod = '{"method":"niudan","content":' + share + '}';
    console.log(jsonMethod);
    if (isAndroid()) {
        //itemid  
        androidObj.call(jsonMethod);
    } else if (isIos()) {
        jsonMethod = encodeURIComponent(jsonMethod);
        loadURL("iosobj://" + jsonMethod);
    }
}

function shareImgSelectTo(imgUrl) {
    var type = "WEIXIN,WEIXIN_CIRCLE";


    var share = '{"imgUrl":"' + imgUrl + '","type":"' + type + '","title":"","content":"","img":"http://cdn.taoquanbaapp.com/luck_share_icon.png","url":""}';

    jsonMethod = '{"method":"share_select","content":' + share + '}';
    console.log(jsonMethod);
    if (isAndroid()) {
        //itemid
        androidObj.call(jsonMethod);
    } else if (isIos()) {
        jsonMethod = encodeURIComponent(jsonMethod);
        loadURL("iosobj://" + jsonMethod);
    }
}

function shareType(type) {
    //var type="WEIXIN_CIRCLE";

    var shareUrl = "http://tqb.appfanli.net/tqb/auth.html?userId=" + getParameterValue("userId");
    var share = '{"type":"' + type + '","title":"每天收钱省钱的购物APP-淘券吧","content":"我使用淘券吧上淘宝天猫购物，淘券吧每天给我发钱，你也来试试吧","img":"https://web.taofanli.me/tqb/share/asset/images/logo_120.png","url":"' + shareUrl + '"}';

    jsonMethod = '{"method":"share","content":' + share + '}';
    console.log(jsonMethod);
    if (isAndroid()) {
        //itemid  
        androidObj.call(jsonMethod);
    } else if (isIos()) {
        jsonMethod = encodeURIComponent(jsonMethod);
        loadURL("iosobj://" + jsonMethod);
    }
}

function shareSelect() {
    var type = "WEIXIN,WEIXIN_CIRCLE";

    var shareUrl = "http://tqb.appfanli.net/tqb/auth.html?userId=" + getParameterValue("userId");
    var share = '{"type":"' + type + '","title":"每天收钱省钱的购物APP-卷米","content":"卷米每天给我发钱，你也来试试吧","img":"h ","url":"' + shareUrl + '"}';

    jsonMethod = '{"method":"share_select","content":' + share + '}';
    console.log(jsonMethod);
    if (isAndroid()) {
        //itemid  
        androidObj.call(jsonMethod);
    } else if (isIos()) {
        jsonMethod = encodeURIComponent(jsonMethod);
        loadURL("iosobj://" + jsonMethod);
    }
}


function addFanliAvailable(point) {

    var json = '{"fanliAvailable":"' + point + '" }';

    jsonMethod = '{"method":"addPoint","content":' + json + '}';
    console.log(jsonMethod);
    if (isAndroid()) {
        //itemid  
        androidObj.call(jsonMethod);
    } else if (isIos()) {
        jsonMethod = encodeURIComponent(jsonMethod);
        loadURL("iosobj://" + jsonMethod);
    }
}


function addAvailable(point) {

    var json = '{"available":"' + point + '" }';

    jsonMethod = '{"method":"addPoint","content":' + json + '}';
    console.log(jsonMethod);
    if (isAndroid()) {
        //itemid  
        androidObj.call(jsonMethod);
    } else if (isIos()) {
        jsonMethod = encodeURIComponent(jsonMethod);
        loadURL("iosobj://" + jsonMethod);
    }
}

function h5Pay(url) {

    var json = '{"url":"' + url + '" }';

    jsonMethod = '{"method":"h5Pay","content":' + json + '}';
    console.log(jsonMethod);
    if (isAndroid()) {
        //itemid  
        androidObj.call(jsonMethod);
    } else if (isIos()) {
        jsonMethod = encodeURIComponent(jsonMethod);
        loadURL("iosobj://" + jsonMethod);
    }
}

function iosCall(data) {

    document.getElementById("call").value = data;
    console.log(data);
    var obj = JSON.parse(data);
    var method = obj.method;
    var content = obj.content;
    proCall(method, content);
}

function androidCall(data) {
    // data =

    document.getElementById("call").value = data;
    console.log(data);
    var obj = JSON.parse(data);
    var method = obj.method;
    var content = obj.content;
    proCall(method, content);

    // JSON.parse(jsonstr);

}

function proCall(method, content) {
    document.getElementById("call").innerText = method + " " +
        JSON.stringify(content);
}

function isAndroid() {
    var ua = navigator.userAgent.toLowerCase();
    if (ua.indexOf("Android") != -1 || ua.indexOf("android") != -1) {
        return true;
    } else {
        return false;
    }
}

function isIos() {

    var ua = navigator.userAgent.toLowerCase();
    if (ua.indexOf("iphone") != -1 || ua.indexOf("Iphone") != -1) {
        return true;
    } else {
        return false;
    }
}

function loadURL(url) {
    var iFrame;
    iFrame = document.createElement("iframe");
    iFrame.setAttribute("src", url);
    iFrame.setAttribute("style", "display:none;");
    iFrame.setAttribute("height", "0px");
    iFrame.setAttribute("width", "0px");
    iFrame.setAttribute("frameborder", "0");
    document.body.appendChild(iFrame);
    // 发起请求后这个 iFrame 就没用了，所以把它从 dom 上移除掉
    iFrame.parentNode.removeChild(iFrame);
    iFrame = null;
}

function getParameterValue(name) {
    var value = "";
    var url = location.href;
    url = decodeURIComponent(url);
    var position = url.indexOf("?");
    var parameterStr = url.substr(position + 1); // Get the string after ?
    var arr = parameterStr.split("&");
    for (i = 0; i < arr.length; i++) {
        var parameter = arr[i].split("=");
        if (parameter[0] == name) {
            value = parameter[1];
        }
    }
    return value;
}

function getParameterValue(name) {
    var value = "";
    var url = location.href;
    url = decodeURIComponent(url);
    var position = url.indexOf("?");
    var parameterStr = url.substr(position + 1); // Get the string after ?
    var arr = parameterStr.split("&");
    for (i = 0; i < arr.length; i++) {
        var parameter = arr[i].split("=");
        if (parameter[0] == name) {
            value = parameter[1];
        }
    }
    return value;
}

function isWeiXin() {
    var ua = window.navigator.userAgent.toLowerCase();

    if (ua.match(/MicroMessenger/i) == 'micromessenger') {
        return true;
    } else {
        return false;
    }

}