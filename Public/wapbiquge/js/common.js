var checkbg = "#A7A7A7";
//内容页用户设置
function nr_setbg(intype) {
    var huyandiv = document.getElementById("huyandiv");
    var light = document.getElementById("lightdiv");
    if (intype == "huyan") {
        if (huyandiv.style.backgroundColor == "") {
            document.cookie = "light=huyan;path=/";
            set("light", "huyan");

        } else {
            document.cookie = "light=no;path=/";
            set("light", "no");

        }
    }
    if (intype == "light") {
        if (light.innerHTML == "关灯") {
            document.cookie = "light=yes;path=/";
            set("light", "yes");

        } else {
            document.cookie = "light=no;path=/";
            set("light", "no");

        }
    }
    if (intype == "big") {
        document.cookie = "font=big;path=/";
        set("font", "big");

    }
    if (intype == "middle") {
        document.cookie = "font=middle;path=/";
        set("font", "middle");

    }
    if (intype == "small") {
        document.cookie = "font=small;path=/";
        set("font", "small");

    }
}

//内容页读取设置
function getset() {
    //document.getElementsByClassName('footer')[0].style.height="80px"; 
    var strCookie = document.cookie;
    var arrCookie = strCookie.split("; ");
    var light;
    var font;

    for (var i = 0; i < arrCookie.length; i++) {
        var arr = arrCookie[i].split("=");
        if ("light" == arr[0]) {
            light = arr[1];
            break;
        }
    }
    for (var i = 0; i < arrCookie.length; i++) {
        var arr = arrCookie[i].split("=");
        if ("font" == arr[0]) {
            font = arr[1];
            break;
        }
    }

    //light
    if (light == "yes") {
        set("light", "yes");
    } else if (light == "no") {
        set("light", "no");
    } else if (light == "huyan") {
        set("light", "huyan");
    }
    //font
    if (font == "big") {
        set("font", "big");
    } else if (font == "middle") {
        set("font", "middle");
    } else if (font == "small") {
        set("font", "small");
    } else {
        set("", "");
    }
}

//内容页应用设置
function set(intype, p) {
    var nr_body = document.getElementById("nr_body"); //页面body
    var huyandiv = document.getElementById("huyandiv"); //护眼div
    var lightdiv = document.getElementById("lightdiv"); //灯光div
    var fontfont = document.getElementById("fontfont"); //字体div
    var fontbig = document.getElementById("fontbig"); //大字体div
    var fontmiddle = document.getElementById("fontmiddle"); //中字体div
    var fontsmall = document.getElementById("fontsmall"); //小字体div
    var nr1 = document.getElementById("nr1"); //内容div
    var nr_title = document.getElementById("nr_title"); //文章标题
    var nr_title = document.getElementById("nr_title"); //文章标题

    var pt_prev = document.getElementById("pt_prev");
    var pt_mulu = document.getElementById("pt_mulu");
    var pt_next = document.getElementById("pt_next");
    var pb_prev = document.getElementById("pb_prev");
    var pb_mulu = document.getElementById("pb_mulu");
    var pb_next = document.getElementById("pb_next");

    //灯光
    if (intype == "light") {
        if (p == "yes") {
            //关灯
            lightdiv.innerHTML = "开灯";
            nr_body.style.backgroundColor = "#000000";
            huyandiv.style.backgroundColor = "";
            nr_title.style.color = "#ccc";
            nr1.style.color = "#999";
            var pagebutton = "background-color:#3e4245;color:#ccc;border:1px solid #313538";
            pt_prev.style.cssText = pagebutton;
            pt_mulu.style.cssText = pagebutton;
            pt_next.style.cssText = pagebutton
            pb_prev.style.cssText = pagebutton;
            pb_mulu.style.cssText = pagebutton;
            pb_next.style.cssText = pagebutton;
        } else if (p == "no") {
            //开灯
            lightdiv.innerHTML = "关灯";
            nr_body.style.backgroundColor = "#fbf6ec";
            nr1.style.color = "#000";
            nr_title.style.color = "#000";
            huyandiv.style.backgroundColor = "";
            var pagebutton = "background-color:#f4f0e9;color:green;border:1px solid #ece6da";
            pt_prev.style.cssText = pagebutton;
            pt_mulu.style.cssText = pagebutton;
            pt_next.style.cssText = pagebutton
            pb_prev.style.cssText = pagebutton;
            pb_mulu.style.cssText = pagebutton;
            pb_next.style.cssText = pagebutton;
        } else if (p == "huyan") {
            //护眼
            lightdiv.innerHTML = "关灯";
            huyandiv.style.backgroundColor = checkbg;
            nr_body.style.backgroundColor = "#DCECD2";
            nr1.style.color = "#000";
            var pagebutton = "background-color:#CCE2BF;color:green;border:1px solid #bbd6aa";
            pt_prev.style.cssText = pagebutton;
            pt_mulu.style.cssText = pagebutton;
            pt_next.style.cssText = pagebutton
            pb_prev.style.cssText = pagebutton;
            pb_mulu.style.cssText = pagebutton;
            pb_next.style.cssText = pagebutton;
        }
    }
    //字体
    if (intype == "font") {
        //alert(p);
        fontbig.style.backgroundColor = "";
        fontmiddle.style.backgroundColor = "";
        fontsmall.style.backgroundColor = "";
        if (p == "big") {
            fontbig.style.backgroundColor = checkbg;
            nr1.style.fontSize = "26px";
        }
        if (p == "middle") {
            fontmiddle.style.backgroundColor = checkbg;
            nr1.style.fontSize = "22px";
        }
        if (p == "small") {
            fontsmall.style.backgroundColor = checkbg;
            nr1.style.fontSize = "16px";
        }
    }
}