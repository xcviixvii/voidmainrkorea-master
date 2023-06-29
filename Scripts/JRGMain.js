var Main = function () {}

var NewsCategory = 0;
var ItemShopFlag = 1;
var RankingFlag = 0;

var date = new Date();
var year = date.getFullYear();
var month = date.getMonth() + 1;
var base_url = base_url;

var PageNo = 1
pagelimit = 10,
    totalrecord = 0;
var PageCount = 0;


$(document).ready(function () {

    var startSlide = 1;

    if (window.location.hash) {
        startSlide = window.location.hash.replace('#', '');
    }
    // SLIDER //
    $('#slides1').slides({
        preload: true,
        preloadImage: './Images/ajax-loader.gif',
        generatePagination: false,
        play: 3000,
        pause: 2500,
        hoverPause: true,

        start: startSlide,
        animationComplete: function (current) {}
    });
    // END SLIDER //

    // FRIEND & CLUB //
    Main.GetUserFriendList();
    $('#friend').click(function () {
        PageNo = 1;
        $(this).removeClass('off').addClass('on');
        $('#club').removeClass('on').addClass('off');
        $('#page-btn1').show();
        $('#page-btn2').hide();
        Main.GetUserFriendList();
    });

    $('#club').click(function () {
        PageNo = 1;
        $(this).removeClass('off').addClass('on');
        $('#friend').removeClass('on').addClass('off');
        $('#page-btn1').hide();
        $('#page-btn2').show();
        Main.GetUserGuildList();
    });

    $('img#btnFPrev').click(function () {
        if (PageNo - 1 >= 1) {
            PageNo = PageNo - 1;
            Main.GetUserFriendList();
        }
    });

    $('img#btnFNext').click(function () {
        if (PageCount >= PageNo + 1) {
            PageNo = PageNo + 1;
            Main.GetUserFriendList();
        }
        console.log("Next Page: " + PageNo);
    });

    $('img#btnGPrev').click(function () {
        if (PageNo - 1 >= 1) {
            PageNo = PageNo - 1;
            Main.GetUserGuildList();
        }
    });

    $('img#btnGNext').click(function () {
        if (PageCount >= PageNo + 1) {
            PageNo = PageNo + 1;
            Main.GetUserGuildList();
        }
    });
    // END FRIEND & CLUB //


    // CALENDAR //
    $('#calendar-event-close').click(function () {
        $('#calendar-event').slideUp();
    });

    $('#yearmonth').text(year + "." + month);

    $('#calendar td[IsEvent="True"]').click(function () {
        var $this = $(this);
        Main.GetMainEventList($(this).attr('val'));
    }).css("cursor", "pointer");

    $('#prevcalendar').click(function () {
        $('#calendar-event').slideUp();
        if (month == 1) {
            year = year - 1;
            month = 12;
        } else {
            month = month - 1;
        }
        $('#yearmonth').text(year + "." + month);
        Main.GetCalendar(year, month);
    });

    $('#nextcalendar').click(function () {
        $('#calendar-event').slideUp();
        if (month == 12) {
            year = year + 1;
            month = 1;
        } else {
            month = month + 1;
        }
        $('#yearmonth').text(year + "." + month)
        Main.GetCalendar(year, month);
    });
    // END CALENDAR //


    // ITEMSHOP //
    $('.main-item-header').mouseover(function () {
        var id = $(this).attr('id').replace('main-item-header', '');

        if (ItemShopFlag == id) {
            return false;
        }

        ItemShopFlag = id;

        $('.main-item-header').each(function () {
            $(this).attr('src', $(this).attr('src').replace('_on', '_off'));
        });

        $(this).attr('src', $(this).attr('src').replace('_off', '_on'));

        if (id == 1) {
            Main.GeItemShopMainList();
        } else {
            Main.GetItemShopMainGMList();
        }


        $('.main-item-wrapper').each(function () {
            $(this).hide();
        });
        $('#main-item' + id).show();
    }).click(function () {
        location.href = './itemshop/';
    });
    // END ITEMSHOP //

});






// CALLING FUNCTION

Main.GetUserFriendList = function () {

    $('#page-no').text('0');
    $('#total-page').text('0');
    if (IsAuth == 'False') {
        $('#relation-list').html('<li class="nothing">When you log in, you can check the status of your friends and club members.</li>');
        $('#page-btn1').hide();
        $('#page-btn2').hide();
    } else {
        $('#page-btn1').show();
        $('#page-btn2').hide();
        $('#relation-list').html('<li class="nothing"><img src="' + base_url + 'Images/ajax-loader.gif" alt=""></li>');
        $.ajax({
            type: 'get',
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            url: "" + base_url + "WebService/GetUserFriendList",
            data: {
                PageNo: PageNo,
                pagelimit: pagelimit,
                UserID: ChaNum

            },
            success: function (data) {
                if (data.success) {
                    var result, TotalItemCount, list;
                    var dataArr = data.success.data;

                    result = dataArr;
                    TotalItemCount = result.length;
                    totalrecord = data.success.totalrecord;

                    PageCount = Math.ceil(totalrecord / pagelimit);

                    var html = "";
                    for (var i = 0; i < dataArr.length; i++) {
                        if (dataArr[i].ChaOnline == '0') {
                            html += '<li class="off">' + dataArr[i].ChaName + '<span class="state"><img src="' + base_url + 'Images/Icon/off_icon.gif" alt="on"></span></li>';
                        } else {
                            html += '<li class="on">' + dataArr[i].ChaName + '<span class="state"><img src="' + base_url + 'Images/Icon/on_icon.gif" alt="on"></span></li>';
                        }
                    }
                    $('#relation-list').html(html);

                    $('#page-no').text(PageNo);
                    $('#total-page').text(PageCount);

                } else {
                    html = '<li class="nothing">There are no friends registered.</li>';
                    $('#relation-list').html(html);

                    $('#page-no').text(0);
                    $('#total-page').text(PageCount);
                }

            },
            error: function (data) {
                alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
            }
        });
    }
}

Main.GetUserGuildList = function () {
    $('#page-no').text('0');
    $('#total-page').text('0');
    if (IsAuth == 'False') {
        $('#relation-list').html('<li class="nothing">When you log in, you can check the status of your friends and club members.</li>');
        $('#page-btn1').hide();
        $('#page-btn2').hide();
    } else {
        $('#page-btn1').hide();
        $('#page-btn2').show();
        $('#relation-list').html('<li class="nothing"><img src="./Images/ajax-loader.gif" alt=""></li>');

        $.ajax({
            type: 'get',
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            url: "" + base_url + "WebService/GetUserGuildList",
            data: {
                PageNo: PageNo,
                pagelimit: pagelimit,
                UserID: UserID,
                GuNum: GuNum

            },
            success: function (data) {
                if (data.success) {
                    var result, TotalItemCount, list;
                    var dataArr = data.success.data;

                    result = dataArr;
                    TotalItemCount = result.length;
                    totalrecord = data.success.totalrecord;

                    PageCount = Math.ceil(totalrecord / pagelimit);

                    var html = "";
                    for (var i = 0; i < dataArr.length; i++) {
                        if (dataArr[i].ChaOnline == '0') {
                            html += '<li class="off">' + dataArr[i].ChaName + '<span class="state"><img src="' + base_url + 'Images/Icon/off_icon.gif" alt="on"></span></li>';
                        } else {
                            html += '<li class="on">' + dataArr[i].ChaName + '<span class="state"><img src="' + base_url + 'Images/Icon/on_icon.gif" alt="on"></span></li>';
                        }
                    }
                    $('#relation-list').html(html);

                    $('#page-no').text(PageNo);
                    $('#total-page').text(PageCount);

                } else {
                    html = '<li class="nothing">There are no registered club members.</li>';
                    $('#relation-list').html(html);

                    $('#page-no').text(0);
                    $('#total-page').text(PageCount);
                }

            },
            error: function (data) {
                alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
            }
        });
    }
}

// There are no registered club members
Main.GeItemShopMainList = function () {
    var $itemshop1 = $('#main-itemshop1');
    var $itemshop2 = $('#main-itemshop2');

    $('#prev1').show();
    $('#next1').show();
    $('#prev2').hide();
    $('#next2').hide();

    $itemshop2.empty();

    $.ajax({
        type: 'post',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        url: './WebService/GetMainItemShopList',
        data: '{}',
        success: function (data) {

            var html = '<div class="slide">\n';
            html += '<ul class="main-item-list">\n';
            var i = 0;

            $.each(data, function (index, obj) {
                i = index + 1;
                html += '<li>\n';
                html += '<a href="' + base_url + 'ItemShop/ItemFind/' + escape(obj.ItemName) + '"><img src="' + obj.ItemImg + '" alt="" width="72" height=72" title="' + obj.ItemName + '"></a>\n';
                html += '<p class="item-title" title="' + obj.ItemName + '"><a href="' + base_url + 'ItemShop/ItemFind/' + escape(obj.ItemName) + '">' + Common.CutText(obj.ItemName, 12) + '</a></p>\n';
                //html += '<p class="item-momey"><img src="..//Images/Icon/won_icon.gif" alt="">' + obj.ItemPrice + '</p>\n';
                html += '</li>\n';

                if (i % 4 == 0) {
                    html += '</ul>\n';
                    html += '</div>\n';

                    if (data.length != i) {
                        html += '<div class="slide">\n';
                        html += '<ul class="main-item-list">\n';
                    }
                }
            });

            if (i % 4 != 0) {
                var temp = i % 4;
                for (var j = temp; j < 4; j++) {
                    html += '<li></li>\n';
                }
                html += '</ul>\n';
                html += '</div>\n';
            }

            $itemshop1.empty();
            $itemshop1.html(html);

            // Set starting slide to 1
            var startSlide = 1;
            // Get slide number if it exists
            if (window.location.hash) {
                startSlide = window.location.hash.replace('#', '');
            }
            // Initialize Slides
            $('#slides1').slides({
                preload: true,
                preloadImage: '..//Images/ajax-loader.gif',
                generatePagination: false,
                play: 3000,
                pause: 2500,
                hoverPause: true,
                // Get the starting slide
                start: startSlide,
                animationComplete: function (current) {
                    // Set the slide number as a hash
                    //window.location.hash = '#' + current;
                }
            });

        },
        error: function (data) {
            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
        }
    });
}

Main.GetItemShopMainGMList = function () {
    var $itemshop1 = $('#main-itemshop1');
    var $itemshop2 = $('#main-itemshop2');

    $('#prev1').hide();
    $('#next1').hide();
    $('#prev2').show();
    $('#next2').show();

    $itemshop1.empty();

    $.ajax({
        type: 'post',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        url: './WebService/GetMainItemShopGMList',
        data: '{}',
        success: function (data) {

            var html = '<div class="slide">\n';
            html += '<ul class="main-item-list">\n';
            var i = 0;
            $.each(data, function (index, obj) {
                i = index + 1;

                html += '<li>\n';
                html += '<a href="' + base_url + 'ItemShop/ItemFind/' + escape(obj.ItemName) + '"><img src="' + obj.ItemImg + '" alt="" width="72" height=72" title="' + obj.ItemName + '"></a>\n';
                html += '<p class="item-title" title="' + obj.ItemName + '"><a href="' + base_url + 'ItemShop/ItemFind/' + escape(obj.ItemName) + '">' + Common.CutText(obj.ItemName, 12) + '</a></p>\n';
                html += '</li>\n';

                if (i % 4 == 0) {
                    html += '</ul>\n';
                    html += '</div>\n';

                    if (data.length != i) {
                        html += '<div class="slide">\n';
                        html += '<ul class="main-item-list">\n';
                    }
                }
            });

            if (i % 4 != 0) {
                var temp = i % 4;
                for (var j = temp; j < 4; j++) {
                    html += '<li></li>\n';
                }
                html += '</ul>\n';
                html += '</div>\n';
            }

            $itemshop2.empty();
            $itemshop2.html(html);

            var startSlide = 1;
            if (window.location.hash) {
                startSlide = window.location.hash.replace('#', '');
            }
            $('#slides2').slides({
                preload: true,
                preloadImage: '../Images/ajax-loader.gif',
                generatePagination: false,
                play: 3000,
                pause: 2500,
                hoverPause: true,
                start: startSlide,
                animationComplete: function (current) {}
            });
        },
        error: function (data) {
            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
        }
    });
}


Main.GetCalendar = function (nowYear, nowMonth) {
    $.post('./WebService/GetCalendar', {
        "nowYear": "" + nowYear + "",
        "nowMonth": "" + nowMonth + ""
    }, function (data) {
        if (data != '') {
            var result = data;
            $('#calendar tbody').html(result);

            $('#calendar td[IsEvent="True"]').click(function () {
                var $this = $(this);
                Main.GetMainEventList($(this).attr('val'));
            }).css("cursor", "pointer");
        }
    });
}


Main.GetMainEventList = function (nowDate) {
    $.post('./WebService/GetMainEventList', {
        "nowDate": "" + nowDate + ""
    }, function (data) {
        if (data != '') {
            var result = eval('(' + data + ')');
            var $offset = $('#calendar').offset();

            var html = '';
            $.each(result, function (index, obj) {
                html += '<dt><a href="' + base_url + 'News/View/' + obj.Idx + '"><span><img src="' + obj.ListImageUrl + '" width="81" height="41" alt=""></span></a></dt>';
                html += '<dd><span class="title"><a class="calendar" href="' + base_url + 'News/View/' + obj.Idx + '">' + obj.Title + '</a></span><span class="date">(' + obj.EventStartDate + ' ~ ' + obj.EventEndDate + ')</span></dd>';
            });

            $('#calendar-event-list').html(html);

            var tempNowDate = nowDate.split('-');

            var nowDateString = tempNowDate[1] + '-' + tempNowDate[2] + '-' + tempNowDate[0]

            $('#event-date').text(nowDateString);

            $('#calendar-event').css({
                'position': 'absolute',
                'left': $offset.left + 205,
                'top': $offset.top - 1,
                'z-index': '100'
            }).slideDown();
        }
    });
}