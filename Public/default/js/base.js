function killerrors() {
    return true;
}
window.onerror = killerrors;
//fav
function fav() {
    var URL = location.href,
        title = document.title;
    try {
        window.external.addFavorite(URL, title);
    } catch (e) {
        try {
            window.sidebar.addPanel(title, URL, "");
        } catch (e) {
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}
//通用功能
function GetObj(objName) {
    if (document.getElementById) {
        return eval('document.getElementById("' + objName + '")');
    } else if (document.layers) {
        return eval("document.layers['" + objName + "']");
    } else {
        return eval('document.all.' + objName);
    }
}
//tab切换
function showTab(cid, no) {

    for (var i = 1; i < 10; i++) {
        var tt = GetObj(cid + "_" + i); //区块隐藏
        if (tt != null) {
            tt.style.display = 'none';
        } else {
            break;
        }
        var oo = GetObj(cid + i); //按钮恢复
        if (oo != null) {
            oo.className = '';
        }
    }

    var tt = GetObj(cid + "_" + no); //区块显示
    if (tt != null) {
        tt.style.display = 'block';
    }
    var oo = GetObj(cid + no); //按钮变色
    if (oo != null) {
        oo.className = 'on';
    }

}

//分享
function share() {
    document.writeln('<div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_tieba" data-cmd="tieba" title="分享到百度贴吧"><a href="#" class="bds_count" data-cmd="count" title=""></a></div>');
}

//改变阅读背景、字体大小和颜色的javascript
var ReadSet = {
    bgcolor: ["#E9FAFF", "#FFFFED", "#efefef", "#FCEFFF", "#ffffff", "#eefaee"],
    bgcname: ["淡蓝海洋", "明黄淡雅", "灰色世界", "红粉世家", "白雪天地", "绿意春色"],
    bgcvalue: "#E9FAFF",
    fontcolor: ["#000000", "#333333", "#008000", "#ffc0cb", "#0000ff", "#ffffff"],
    fontcname: ["墨色", "黑色", "绿色", "粉色", "蓝色", "白色"],
    fontcvalue: "#333333",
    fontsize: ["14px", "18px", "22px", "26px", "30px"],
    fontsname: ["很小", "较小", "中等", "较大", "很大"],
    fontsvalue: "22px",
    contentid: "BookCon",
    fontsizeid: "BookText",
    SetBgcolor: function(color) {
        //document.bgColor = color;
        document.getElementById(this.contentid).style.backgroundColor = color;
        if (this.bgcvalue != color) this.SetCookies("bgcolor", color);
        this.bgcvalue = color;
    },
    SetFontcolor: function(color) {
        document.getElementById(this.fontsizeid).style.color = color;
        if (this.fontcvalue != color) this.SetCookies("fontcolor", color);
        this.fontcvalue = color;
    },
    SetFontsize: function(size) {
        document.getElementById(this.fontsizeid).style.fontSize = size;
        if (this.fontsvalue != size) this.SetCookies("fontsize", size);
        this.fontsvalue = size;
    },
    LoadCSS: function() {
        var style = "";
        style += ".readSet{padding:3px;clear:both;line-height:20px;width:780px;margin:0 auto;}\n";
        style += ".readSet .rc{color:#333333;float:left;padding-left:20px;}\n";
        style += ".readSet a.ra{border:1px solid #cccccc;display:block;width:16px;height:16px;float:left;margin-left:6px;overflow:hidden;}\n";
        style += ".readSet .rf{float:left;}\n";
        style += ".readSet .rt{padding:0px 5px;}\n";

        if (document.all) {
            var oStyle = document.styleSheets[0];
            var a = style.split("\n");
            for (var i = 0; i < a.length; i++) {
                if (a[i] == "") continue;
                var ad = a[i].replace(/([\s\S]*)\{([\s\S]*)\}/, "$1|$2").split("|");
                oStyle.addRule(ad[0], ad[1]);
            }
        } else {
            var styleobj = document.createElement('style');
            styleobj.type = 'text/css';
            styleobj.innerHTML = style;
            document.getElementsByTagName('HEAD').item(0).appendChild(styleobj);
        }
    },
    Show: function() {
        var output;
        output = '<div class="readSet">';
        output += '<span class="rc">阅读背景:</span>';
        for (i = 0; i < this.bgcolor.length; i++) {
            output += '<a style="background-color: ' + this.bgcolor[i] + '" class="ra" title="' + this.bgcname[i] + '" onclick="ReadSet.SetBgcolor(\'' + this.bgcolor[i] + '\')" href="javascript:;"></a>';
        }
        output += '<span class="rc">字体颜色:</span>';
        for (i = 0; i < this.fontcolor.length; i++) {
            output += '<a style="background-color: ' + this.fontcolor[i] + '" class="ra" title="' + this.fontcname[i] + '" onclick="ReadSet.SetFontcolor(\'' + this.fontcolor[i] + '\')" href="javascript:;"></a>';
        }
        output += '<span class="rc">字体大小:</span><span class="rf">[';
        for (i = 0; i < this.fontsize.length; i++) {
            output += '<a class="rt" onclick="ReadSet.SetFontsize(\'' + this.fontsize[i] + '\')" href="javascript:;">' + this.fontsname[i] + '</a>';
        }
        output += ']</span>';
        output += '<div style="font-size:0px;clear:both;"></div></div>';
        document.write(output);
    },
    SetCookies: function(cookieName, cookieValue, expirehours) {
        var today = new Date();
        var expire = new Date();
        expire.setTime(today.getTime() + 3600000 * 356 * 24);
        document.cookie = cookieName + '=' + escape(cookieValue) + ';expires=' + expire.toGMTString() + '; path=/';
    },
    ReadCookies: function(cookieName) {
        var theCookie = '' + document.cookie;
        var ind = theCookie.indexOf(cookieName);
        if (ind == -1 || cookieName == '') return '';
        var ind1 = theCookie.indexOf(';', ind);
        if (ind1 == -1) ind1 = theCookie.length;
        return unescape(theCookie.substring(ind + cookieName.length + 1, ind1));
    },
    SaveSet: function() {
        this.SetCookies("bgcolor", this.bgcvalue);
        this.SetCookies("fontcolor", this.fontcvalue);
        this.SetCookies("fontsize", this.fontsvalue);
    },
    LoadSet: function() {
        tmpstr = this.ReadCookies("bgcolor");
        if (tmpstr != "") this.bgcvalue = tmpstr;
        this.SetBgcolor(this.bgcvalue);
        tmpstr = this.ReadCookies("fontcolor");
        if (tmpstr != "") this.fontcvalue = tmpstr;
        this.SetFontcolor(this.fontcvalue);
        tmpstr = this.ReadCookies("fontsize");
        if (tmpstr != "") this.fontsvalue = tmpstr;
        this.SetFontsize(this.fontsvalue);
    }
}

function bookset() {
    ReadSet.LoadCSS();
    ReadSet.Show();
}

function LoadReadSet() {
    ReadSet.LoadSet();
}
if (document.all) {
    window.attachEvent('onload', LoadReadSet);
} else {
    window.addEventListener('load', LoadReadSet, false);
}

//百度分享
window._bd_share_config = { "common": { "bdSnsKey": {}, "bdText": "", "bdMini": "2", "bdMiniList": false, "bdPic": "", "bdStyle": "0", "bdSize": "32" }, "share": {} };
with(document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = '/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];
//百度jquery
document.writeln('<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>');
document.writeln("<script src=\'/Public/trans.js\'></script>");

function setCookie(name, value, day) {
    var exp = new Date();
    exp.setTime(exp.getTime() + day * 24 * 60 * 60 * 1000);
    document.cookie = name + "= " + escape(value) + ";expires= " + exp.toGMTString()
}

function getCookie(objName) {
    var arrStr = document.cookie.split("; ");
    for (var i = 0; i < arrStr.length; i++) {
        var temp = arrStr[i].split("=");
        if (temp[0] == objName) return unescape(temp[1])
    }
}