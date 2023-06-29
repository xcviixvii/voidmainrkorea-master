Common = function() { }

Common.CheckEngNum = function(str) {
    var checkRe = /^[a-zA-Z0-9]+$/;
    return checkRe.test(str);
}

Common.CheckEngNumEtc = function(str) {
	var checkRe = /[^0-9a-zA-Z`~!@#$%^&*(),.;]/ // 숫자,영문,특수문자
    return checkRe.test(str);
}

Common.CheckEng = function(str) {
	var checkRe = /[a-zA-Z]/ 
    return checkRe.test(str);
}

Common.CheckNum = function(str) {
	var checkRe = /[0-9]/ 
    return checkRe.test(str);
}

Common.CheckEtc = function(str) {
	var checkRe = /[`~!@#$%^&*(),.;]/ 
    return checkRe.test(str);
}


Common.PasswordCheck = function(password) {
    var permit = /^[a-zA-Z0-9]{4,20}$/;

    if (!permit.test(password)) {
        return false;
    }
    else {
        return true;
    }
}

Common.PasswordCheckLetter = function(password) {
    var permit = /(.)\1{3,}/;

    if (permit.test(password)) {
        return false;
    }
    else {
        return true;
    }
}

Common.IsEmail = function(Email) {
    return Email.search(/^\s*[\w\~\-\.]+\@[\w\~\-]+(\.[\w\~\-]+)+\s*$/g) >= 0;
}

Common.CheckOnlyNumber = function(value) {
    var checkRe = /^[0-9]+$/;
    return checkRe.test(value);
}

Common.escapeHTML = function(str) {
    str = str.replace(/&/g, "&amp;");
    str = str.replace(/</g, "&lt;");
    str = str.replace(/>/g, "&gt;");

    return str;
}

// Windows Popup
Common.OpenCenterWindow = function(winW, winH, sURL, winName, blnScrollbar) {
    var strScrolbar = "no";

    if (blnScrollbar) {
        strScrolbar = "yes";
    }

    var winL = (screen.width - winW) / 2;
    var winT = (screen.height - winH) / 2;

    var win = window.open(sURL, winName, "width=" + winW + ",height=" + winH + ",scrollbars=" + strScrolbar + ",resizable=no,top=" + winT + ", left=" + winL);

    win.focus();
}

// ImageUpload
Common.CallImgUpload = function(strUrl) {
    Common.OpenCenterWindow(440, 240, strUrl, "upload", false);
}

// flash //
Common.flashObject = function(file_name, flashVar, width, height) {
    document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="' + width + '" height="' + height + '">');
    document.write('<param name="movie" value="' + file_name + '">');
    document.write('<param name="FlashVars" value="' + flashVar + '">');
    document.write('<param name="quality" value="high">');
    document.write('<param name="bgcolor" value="#ffffff">');
    document.write('<param name="wmode" value="transparent">');
    document.write('<param name="allowScriptAccess" value="always">');
    document.write('<param name="base" value=".">');
    document.write('<embed src="' + file_name + '" FlashVars="' + flashVar + '" width="' + width + '" height="' + height + '" quality="high" bgcolor="#ffffff" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent" allowScriptAccess="always" allowNetworking="all" base="."></embed>');
    document.write('</object>');
}

// flash return //
Common.flashObjectReturn = function(file_name, flashVar, width, height) {
    var strTemp = "";

    strTemp += '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="' + width + '" height="' + height + '">';
    strTemp += '<param name="movie" value="' + file_name + '">';
    strTemp += '<param name="FlashVars" value="' + flashVar + '">';
    strTemp += '<param name="quality" value="high">';
    strTemp += '<param name="bgcolor" value="#ffffff">';
    strTemp += '<param name="wmode" value="transparent">';
    strTemp += '<param name="allowScriptAccess" value="always">';
    strTemp += '<param name="base" value=".">';
    strTemp += '<embed src="' + file_name + '" FlashVars="' + flashVar + '" width="' + width + '" height="' + height + '" quality="high" bgcolor="#ffffff" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent" allowScriptAccess="always" allowNetworking="all" base="."></embed>';
    strTemp += '</object>';

    return strTemp;
}

// flash //
Common.GetFlash = function(src, width, height) {
    //var src, width, height;
    document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="' + width + '" height="' + height + '">');
    document.write('<param name="allowScriptAccess" value="always" />');
    document.write('<param name="movie" value="' + src + '">');
    document.write('<param name="quality" value="autohigh">');
    document.write('<param name="menu" value="false">');
    document.write('<param name="wmode" value="transparent">');
    document.write('<embed src="' + src + '"quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash type=application/x-shockwave-flash" width="' + width + '" height="' + height + '">');
    document.write('</embed>');
    document.write('</object>');
}

// flash xml //
Common.GetFlashXML = function(src, width, height, flashvars) {
    //var src, width, height;
    document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="' + width + '" height="' + height + '">');
    document.write('<param name="allowScriptAccess" value="always">');
    document.write('<param name="movie" value="' + src + '">');
    document.write('<param name="quality" value="autohigh">');
    document.write('<param name="menu" value="false">');
    document.write('<param name="wmode" value="transparent">');
    document.write('<param name="flashvars" value="' + flashvars + '">');
    document.write('<embed src="' + src + '" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash type=application/x-shockwave-flash" width="' + width + '" height="' + height + '">');
    document.write('</embed>');
    document.write('</object>');
}

//html 치환
Common.escapeHTML = function(str) {
    str = str.replace(/&/g, "&amp;");
    str = str.replace(/</g, "&lt;");
    str = str.replace(/>/g, "&gt;");

    return str;
}

//html 치환
Common.escapeHTMLComment = function(str) {
    str = str.replace(/&/g, "&amp;");
    str = str.replace(/</g, "&lt;");
    str = str.replace(/>/g, "&gt;");
    str = str.replace(/\?/g, "&#63;");

    return str;
}

//html 태그를 지운다.
Common.DeleteTag = function(str) {
    //return str.replaceAll("<(/)?([a-zA-Z]*)(\\s[a-zA-Z]*=[^>]*)?(\\s)*(/)?>", "");
    var objStrip = new RegExp();
    objStrip = /[<][^>]*[>]/gi;
    return str.replace(objStrip, "");
}

// 문자열 치환
var stringEscape = {
    '\b': '\\b',
    '\t': '\\t',
    '\n': '\\n',
    '\f': '\\f',
    '\r': '\\r',
    '"': '\\"',
    '\\': '\\\\'
};

// Escape 문자들을 대응되는 일반 문자열로 변환한다.
// \r과 같은 캐리지 리턴을 \\r이라는 문자열로 바꿔서 json 사용 시 문제가 없도록 한다.
// 이렇게 보내면, 서버에서는 올바로 \r (캐리지 리턴)으로 인식한다.
Common.replaceJSONSafeEscape = function(string) {
    return string
            .replace(
				/[\x00-\x1f\\"]/g,
				function(a) {
				    var b = stringEscape[a];
				    if (b)
				        return b;
				    else
				        return a;
				}
			);
}

//QueryString 으로 넘어온 값을 추출
Common.queryString = function(name) {
    var q = location.search.replace(/^\?/, '').replace(/\&$/, '').split('&');
    for (var i = q.length - 1; i >= 0; i--) {
        var p = q[i].split('='), key = p[0], val = p[1];
        if (name.toLowerCase() == key.toLowerCase()) return val;
    }

    return "";
}

//폼 빈값 체크
Common.FormValidate = function(obj) {
    if (obj.val().replace(/\s/g, "").length == 0) {
        return false;
    } else {
        return true;
    }
}

//날짜 형식을 바꾼다. 2010-11-10 형식이어야 한다.
Common.GetDateFormat = function(date) {
    var arrTemp = date.split("-");
    var monthName = "";

    switch (arrTemp[1]) {
        case "01":
            monthName = "Jan";
            break;
        case "02":
            monthName = "Feb";
            break;
        case "03":
            monthName = "Mar";
            break;
        case "04":
            monthName = "Apr";
            break;
        case "05":
            monthName = "May";
            break;
        case "06":
            monthName = "Jun";
            break;
        case "07":
            monthName = "Jul";
            break;
        case "08":
            monthName = "Aug";
            break;
        case "09":
            monthName = "Sept";
            break;
        case "10":
            monthName = "Oct";
            break;
        case "11":
            monthName = "Nov";
            break;
        case "12":
            monthName = "Dec";
            break;
    }

    return monthName + " " + arrTemp[2] + "." + arrTemp[0];
}

//2010-12-22 뉴 이미지
Common.IsRecentData = function(d) {   //d는 2008-4-12 20:23:40 과 같은 포맷이어야 함
    d = d.replace("오전 ", "");
    d = d.replace("오후 ", "");

    var boolRecent = false;
    var dTemp = d.split(" ");
    if (dTemp.length < 2) return;

    var date = dTemp[0].split("-");
    var time = dTemp[1].split(":");
    var cDate = new Date(date[0], date[1] - 1, date[2], time[0], time[1], time[2]);

    var hoursApart = Math.abs(Math.round((new Date() - cDate) / 3600000));
    if (hoursApart < 24)
        boolRecent = true;

    return boolRecent;
}

Common.CutText = function(str, len) {
    var strReturn = '';

    strReturn = str;
    if (str.length > len) {
        strReturn = str.substring(0, len) + "...";
    }
    return strReturn;
}

Common.CutTextByte = function(str, len) {
    var _byte = 0;
    var strReturn = '';
    if (str.length != 0) {
        for (var i = 0; i < str.length; i++) {
            var c = str.charAt(i);
            if (escape(c).length > 4) {
                _byte += 2;
            }
            else {
                _byte++;
            }

            if (_byte <= (len * 2)) {
                strReturn += str.substr(i, 1);
            } else {
                strReturn = strReturn;
                break;
            }
        }
    }

    if (_byte > (len * 2)) {
        strReturn += "...";
    }

    return strReturn;
}

Common.ItemShopBuy = function() {    
    var strUrl = "/ItemShop/Payment/PaymentStep1.aspx";

    Common.OpenCenterWindow(500, 412, strUrl, "WinItemBuy", false);
}

Common.ItemShopUse = function() {
    var strUrl = "/shop/popmygameItembuylist.asp";

    Common.OpenCenterWindow(580, 550, strUrl, "WinItemUse", false);
}

function OpenItemShopGuide() {
    Common.OpenCenterWindow(500, 350, '/shop/guide.asp', "WinItemShopGuideView", true);
}

function OpenItemShopBuy()
{
    var strUrl = "/shop/popmybuylist.asp";

    Common.OpenCenterWindow(600, 570, strUrl, "WinItemBuy", false);
}

function OpenItemShopAccount() {
    var strUrl = "/shop/popmybuylist.asp";
    Common.OpenCenterWindow(600, 570, strUrl, "WinItemAccount", false);
}

//브라우저 체크
//var Browser = {
//   agent: navigator.userAgent.toLowerCase()
//}
var agent = navigator.userAgent.toLowerCase();
var Browser = {
    ie : false,
    ie6 : agent.indexOf('msie 6') != -1,
    ie7 : agent.indexOf('msie 7') != -1,
    ie8 : agent.indexOf('msie 8') != -1,
    ie9 : agent.indexOf('msie 9') != -1,
    opera : !!window.opera,
    safari : agent.indexOf('safari') != -1,
    mac : agent.indexOf('mac') != -1,
    chrome : agent.indexOf('chrome') != -1,
    firefox: agent.indexOf('firefox') != -1,
    mobile: agent.indexOf('android') != -1,
    mobile: agent.indexOf('iphone') != -1,
    mobile: agent.indexOf('symbianOS') != -1,
    mobile: agent.indexOf('blackberry') != -1,
    mobile: agent.indexOf('ipad') != -1
}

Common.GetMobileCheck = function() {
    if (agent.indexOf('android') != -1 || agent.indexOf('iphone') != -1 || agent.indexOf('symbianos') != -1 || agent.indexOf('blackberry') != -1 || agent.indexOf('ipad') != -1) {
        return true;
    } else {
        return false;
    }
}

/*
var pattern = /[^(가-힣)]/; //한글만 허용 할때
var pattern = /[^(가-힣a-zA-Z0-9)]/; //한글,영문,숫자만 허용
var pattern = /[^(a-zA-Z)]/; //영문만 허용
*/

Common.IsKrEnInt = function(val) {
    var pattern = /[^(가-힣a-zA-Z0-9)]/; //한글,영문,숫자만 허용

    if (!pattern.test(val)) {
        return true;
    } else {
        return false;
    }
}


var frameControl = new Object({
    // 브라우저 체크(FF3, FF2, FF, IE, Saf, Chr, Op)
    //browser: (function x() { })[-5] == 'x' ? 'FF3' : (function x() { })[-6] == 'x' ? 'FF2' : /a/[-1] == 'a' ? 'FF' : '\v' == 'v' ? 'IE' : /a/.__proto__ == '//' ? 'Saf' : /s/.test(/a/.toString) ? 'Chr' : /^function \(/.test([].sort) ? 'Op' : 'Unknown',
	browser: (function x() { })[-5] == 'x' ? 'FF3' : (function x() { })[-6] == 'x' ? 'FF2' : /a/[-1] == 'a' ? 'FF' : '\v' == 'v' ? 'IE' : /s/.test(/a/.toString) ? 'Chr' : /^function \(/.test([].sort) ? 'Op' : 'Unknown',

    // 팝업 리사이징
    resize: function() {
        var winSize = this.alertSize();
        var sizeWidth = winSize[0];
        var sizeHeight = winSize[1];

        var Dwidth = 0, Dheight = 0;
        if (this.browser == 'IE') {
            Dwidth = document.body.scrollWidth;
            Dheight = document.body.scrollHeight;
        } else {
            Dwidth = document.compatMode == "CSS1Compat" ? document.documentElement.scrollWidth : document.body.scrollWidth;
            Dheight = document.compatMode == "CSS1Compat" ? document.documentElement.scrollHeight : document.body.scrollHeight;
        }

        var width = Dwidth - sizeWidth;
        var height = Dheight - sizeHeight;

        self.resizeBy(width, height);
    },

    // 윈도우 사이즈 체크
    alertSize: function() {
        var myWidth = 0, myHeight = 0;
        if (typeof (window.innerWidth) == 'number') {
            //Non-IE
            myWidth = window.innerWidth;
            myHeight = window.innerHeight;
        } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
            //IE 6+ in 'standards compliant mode'
            myWidth = document.documentElement.clientWidth;
            myHeight = document.documentElement.clientHeight;
        } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
            //IE 4 compatible
            myWidth = document.body.clientWidth;
            myHeight = document.body.clientHeight;
        }
        return [myWidth, myHeight];
    }
});

// 클립보드 복사
Common.setClip = function(text) {
    var result = window.clipboardData.setData('Text', text);
    if (result) {
        alert("클립보드에 저장 되었습니다. ctrl + v 로 붙여 주세요.");
    } else {
        location.href = location.href;
    }
}

// 쿠키를 가져옮
Common.getCookie = function(name) {
    var nameOfCookie = name + "=";
    var x = 0;
    while (x <= document.cookie.length) {
        var y = (x + nameOfCookie.length);
        if (document.cookie.substring(x, y) == nameOfCookie) {
            if ((endOfCookie = document.cookie.indexOf(";", y)) == -1)
                endOfCookie = document.cookie.length;
            return unescape(document.cookie.substring(y, endOfCookie));
        }
        x = document.cookie.indexOf(" ", x) + 1;
        if (x == 0) break;
    }
    return "";
}

// 만 나이
Common.getAge = function(birthday, nowDate) {
    var byear = parseInt(birthday.split('-')[0]);
    var bmonth = parseInt(birthday.split('-')[1]);
    var bday = parseInt(birthday.split('-')[2]);

    var nyear = parseInt(nowDate.split('-')[0]);
    var nmonth = parseInt(nowDate.split('-')[1]);
    var nday = parseInt(nowDate.split('-')[2]);

    var age;

    if ((nmonth > bmonth) || (nmonth == bmonth & nday >= bday)) {
        age = nyear - byear;
    } else {
        age = nyear - (byear + 1);
    }

    return age;
}

/* Number add Comma */
Common.Comma = function(n) {
    var reg = /(^[+-]?\d+)(\d{3})/;   // 정규식
    n += '';                          // 숫자를 문자열로 변환

    while (reg.test(n))
        n = n.replace(reg, '$1' + ',' + '$2');

    return n;
}

/* 아이프레임 리사이즈 */
Common.FrameResize = function(frame) {
    var the_height = document.getElementById(frame).contentWindow.document.body.scrollHeight;

    //change the height of the iframe
    document.getElementById('auto-iframe').height = the_height + 15;
}