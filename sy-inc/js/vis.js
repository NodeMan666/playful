var ref=""+escape(top.document.referrer); 
var colord = window.screen.colorDepth; 
var res = window.screen.width + "x" + window.screen.height;
var ptitle=document.title.replace(/&/g,'and'); 	
var ptitle = addslashes(ptitle);
var ptitle = escape(ptitle);
var cururl=location.href;
var reff=document.referrer;
var reff=reff.replace("http://",''); 	
var reff=reff.replace("https://",''); 	
// $("#log").show().append("ref: "+ref+"<br>res: "+res+"<br>ptitle: "+ptitle+"<br>cururl: "+cururl+"<br>reff: "+reff+"<br>date id: "+date_id+"<br>page_viewed: "+page_viewed+"<br>cookie: "+readCookie('vid')+"<br>LV: "+readCookie('lv')+"<br>");

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}