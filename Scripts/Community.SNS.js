/*
 * 작성자 : 김현우
 * 작성일 : 2011-08-31
 * 내용 : 외부 SNS와 연동하는 스크립트
*/
var Community = function() { }
Community.SNS = function() { }

Community.SNS.Scrap = function(sns, title, url) {
    var scrapUrl = '';
    switch (sns) {
        case 'facebook':
            scrapUrl = "http://www.facebook.com/share.php?u=" + encodeURIComponent(url) + '&t=' + encodeURIComponent(title);
            break;
        case 'twitter':
            scrapUrl = "http://twitter.com/home?status=" + encodeURIComponent('"' + title + '":' + url);
            break;
        case 'me2day':
            scrapUrl = "http://www.me2day.net/posts/new?new_post[body]=" + encodeURIComponent('"' + title + '":' + url);
            break;
        default:
            alert('잘못된 SNS입니다.');
            return null;
    }

    Common.OpenCenterWindow(1020, 700, scrapUrl, sns, true);
}