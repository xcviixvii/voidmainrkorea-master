var Main = function() { }

var NewsCategory = 0; // 게임소식 카테고리 기본은 0으로 전체 조회
var ItemShopFlag = 1; // 아이템샵 : 1 - 새로나온 아이템, 2 - GM 추천 아이템
var RankingFlag = 0; // 시즌랭킹 : 0, 랭킹 : 1 - 선도클럽, 2 - 개인랭킹

var date = new Date();
var year = date.getFullYear();
var month = date.getMonth() + 1;

// 친구 목록
var PageNo = 1;
var PageCount = 0;

$(document).ready(function() {
    // 대표 캐릭터 정보
    $('#btnCha').click(function() {
        if (document.getElementById('represent-character').style.display == 'none' || document.getElementById('represent-character').style.display == '') {
            Main.GetUserChaList();
        } else {
            $('#btnChaCancel').click();
        }
    });

    // 대표 캐릭터 설정
    $('#btnChaSave').click(function() {
        if (!Common.FormValidate($('#hdnSGNum'))) {
            alert('대표캐릭터를 선택해주세요.');
            return false;
        }

        $.ajax({
            url: '/WebService/AuthWebService.asmx/SetUserCha',
            data: '{"UserID" : "' + UserID + '", "SGNum" : "' + $('#hdnSGNum').val() + '", "UserChaNum" : "' + $('#hdnUserChaNum').val() + '"}',
            success: function(data) {
                var result = data.d;

                if (result == 0) {
                    alert('대표 캐릭터로 설정되었습니다.');
                    location.href = location.href;
                } else {
                    alert('오류입니다.\n재시도해주세요.');
                    location.href = location.href;
                }
            },
            error: function(data) {
                alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
            }
        });
    });

    // 대표 캐릭터 취소
    $('#btnChaCancel').click(function() {
        $('#hdnSGNum').val('');
        $('#hdnUserChaNum').val('');

        $('.represent-character').hide();
    });

    Main.GetUserFriendList();

    $('#friend').click(function() {
        PageNo = 1;
        $(this).removeClass('off').addClass('on');
        $('#club').removeClass('on').addClass('off');
        $('#page-btn1').show();
        $('#page-btn2').hide();
        Main.GetUserFriendList();
    });

    $('#club').click(function() {
        PageNo = 1;
        $(this).removeClass('off').addClass('on');
        $('#friend').removeClass('on').addClass('off');
        $('#page-btn1').hide();
        $('#page-btn2').show();
        Main.GetUserGuildList();
    });

    $('img#btnFPrev').click(function() {
        if (PageNo - 1 >= 1) {
            PageNo = PageNo - 1;
            Main.GetUserFriendList();
        }
    });

    $('img#btnFNext').click(function() {
        if (PageCount >= PageNo + 1) {
            PageNo = PageNo + 1;
            Main.GetUserFriendList();
        }
    });

    $('img#btnGPrev').click(function() {
        if (PageNo - 1 >= 1) {
            PageNo = PageNo - 1;
            Main.GetUserGuildList();
        }
    });

    $('img#btnGNext').click(function() {
        if (PageCount >= PageNo + 1) {
            PageNo = PageNo + 1;
            Main.GetUserGuildList();
        }
    });


    

    // FAQ 검색
    $('#txtFaqWord').keyup(function(e) {
        if (e.keyCode == '13') {
            Main.SearchFaq();
        }
    });
    $('#btnFaq').click(Main.SearchFaq);

    //페이지 로드시 년월 셋팅
    $('#yearmonth').text(year + "." + month);

    // 이벤트가 있는 날짜에 클릭이벤트 추가
    $('#calendar td[IsEvent="True"]').click(function() {
        var $this = $(this);
        Main.GetMainEventList($(this).attr('val'));
    }).css("cursor", "pointer");

    // 이전달
    $('#prevcalendar').click(function() {
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

    // 다음달
    $('#nextcalendar').click(function() {
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

    // 아이템샵 슬라이드 셋팅
    // Set starting slide to 1
    var startSlide = 1;
    // Get slide number if it exists
    if (window.location.hash) {
        startSlide = window.location.hash.replace('#', '');
    }

    $('#slides1').slides({
        preload: true,
        preloadImage: '..//Images/ajax-loader.gif',
        generatePagination: false,
        play: 3000,
        pause: 2500,
        hoverPause: true,
        // Get the starting slide
        start: startSlide,
        animationComplete: function(current) {
            // Set the slide number as a hash
            //window.location.hash = '#' + current;
        }
    });

    $('#calendar-event-close').click(function() {
        $('#calendar-event').slideUp();
    });

    // 투표하기 버튼에 이벤트 바인드
    $('#btnPollVote').bind("click", function() {
        if (IsAuth == 'False') {
            alert('로그인 후 이용해주세요.');
            return false;
        }

        if ($('input[name="rdoSel"]:checked').length == 0) {
            alert('하나의 답변을 선택해주세요.');
            return false;
        }
        $('#hdnPollSeq').val(PollSeq);
        $('#frmPoll').submit();
    });

    // 결과보기 버튼에 이벤트 바인드
    $('#btnPollResult').bind("click", function() {
        location.href = "/community/pollview.aspx?idx=" + PollSeq;
    });

    // 메인 뉴스 탭
    $('.main-news-header').mouseover(function() {
        var id = $(this).attr('id').replace('main-news-header', '');

        if (NewsCategory == id) {
            return false;
        }
        NewsCategory = id;

        $('.main-news-header').each(function() {
            $(this).attr('src', $(this).attr('src').replace('_on', '_off'));
        });

        $(this).attr('src', $(this).attr('src').replace('_off', '_on'));
        $('.main-news-article').each(function() {
            $(this).hide();
        });
        $('#main-news' + id).show();
    }).click(function() {
        if (NewsCategory == 0) {
            location.href = "/News/NewsList.aspx?Category=10";
        } else if (NewsCategory == 1) {
            location.href = "/News/NewsList.aspx?Category=10";
        } else if (NewsCategory == 2) {
            location.href = "/News/NewsList.aspx?Category=20";
        } else if (NewsCategory == 3) {
            location.href = "/News/NewsList.aspx?Category=30";
        } else if (NewsCategory == 4) {
            location.href = "/News/NewsList.aspx?Category=40";
        }
    });

    // 메인 아이템 샵 탭
    $('.main-item-header').mouseover(function() {
        var id = $(this).attr('id').replace('main-item-header', '');

        if (ItemShopFlag == id) {
            return false;
        }

        ItemShopFlag = id;

        $('.main-item-header').each(function() {
            $(this).attr('src', $(this).attr('src').replace('_on', '_off'));
        });

        $(this).attr('src', $(this).attr('src').replace('_off', '_on'));

        if (id == 1) {
            Main.GeItemShopMainList();
        }
        else {
            Main.GetItemShopMainGMList();
        }
        

        $('.main-item-wrapper').each(function() {
            $(this).hide();
        });
        $('#main-item' + id).show();
    }).click(function() {
        location.href = '/ItemShop/';
    });


    $('#txtItemSearchString').keypress(function(e) {
        
    });

    // ItemShop 검색 자동완성
    //$('#txtItemSearchString').keypress(Main.GetMainItemSearchAutoList());
    $('#txtItemSearchString').keyup(function(e) {
		if (e.keyCode == '13') {
            $('#btnItemSearch').click();
        }
		
        if (e.keyCode != "38" && e.keyCode != "40") {
            if (Common.FormValidate($(this))) {
                $.ajax({
                    url: '/WebService/NoAuthWebService.asmx/GetMainItemSearchAutoList',
                    data: '{"CategoryFirst" : "' + $('#selItemCategory').val() + '", "SearchString" : "' + $('#txtItemSearchString').val() + '"}',
                    success: function(data) {
                        var result = data.d;
                        var arr = result.split("▒");

                        $('#txtItemSearchString').autocomplete(arr);
                    },
                    error: function(data) {
                        alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
                    }
                });
            }
        }
    });

    $('#btnItemSearch').click(function() {
        var categoryFirst = $('#selItemCategory').val();
        var categorySecond = 0;

        if (categoryFirst == 600) {
            categorySecond = 610;
        }
        location.href = '/ItemShop/?CategoryFirst=' + categoryFirst + '&CategorySecond=' + categorySecond + '&SearchString=' + escape($('#txtItemSearchString').val());
    });

    // 메인 랭킹 탭
    $('.main-ranking-header').mouseover(function() {
        var id = $(this).attr('id').replace('main-ranking-header', '');

        $('.main-ranking-header').each(function() {
            $(this).attr('src', $(this).attr('src').replace('_on', '_off'));
        });

        RankingFlag = id;

        $(this).attr('src', $(this).attr('src').replace('_off', '_on'));

        $('.main-ranking-wrapper').each(function() {
            $(this).hide();
        });

        $('#main-ranking' + id).show();
    }).click(function() {
        if (RankingFlag == 0) {
            location.href = "/Ranking/Season.aspx";
        } else if (RankingFlag == 1) {
            location.href = "/Ranking/Total.aspx";
        }
    });

    //Main.GeItemShopMainList();
});

// 달력
Main.GetCalendar = function(nowYear, nowMonth) {
    $.ajax({
        url: '/aziaadmin/WebService/GetCalendar',
        data: '{"nowYear": "' + nowYear + '", "nowMonth" : "' + nowMonth + '"}',
        success: function(data) {
            var result = data.d;

            $('#calendar tbody').html(result);

            // 이벤트가 있는 날짜에 클릭이벤트 추가
            $('#calendar td[IsEvent="True"]').click(function() {
                var $this = $(this);
                //Main.GetMainEventList($(this).attr('val'));
            }).css("cursor", "pointer");
        },
        error: function(data) {
            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
        }
    });
}

// 달력 이벤트 목록
Main.GetMainEventList = function(nowDate) {
    $.ajax({           
        dataType: "xml",
        url: '/aziaadmin/webservice/GetMainEventList',
        data: '{"nowDate": "' + nowDate + '"}',
        success: function(data) {
            alert(data)
            var result, list;

            eval("result = " + data.d);
            list = result.List;
            var $offset = $('#calendar').offset();

            var html = '';
            $.each(list, function(index, obj) {
                html += '<dt><a href="/News/Newsview.aspx?Category=30&Idx=' + obj.Idx + '"><span><img src="' + obj.ListImageUrl + '" width="81" height="41" alt=""></span></a></dt>';
                html += '<dd><span class="title"><a class="calendar" href="/News/Newsview.aspx?Category=30&Idx=' + obj.Idx + '">' + obj.Title + '</a></span><span class="date">(' + obj.EventStartDate + ' ~ ' + obj.EventEndDate + ')</span></dd>';
            });

            $('#calendar-event-list').html(html);

            var tempNowDate = nowDate.split('-');
            var nowDateString = tempNowDate[0] + '년 ' + tempNowDate[1] + '월 ' + tempNowDate[2] + '일';

            $('#event-date').text(nowDateString);

            $('#calendar-event').css({
                'position': 'absolute',
                'left': $offset.left + 205,
                'top': $offset.top - 1,
                'z-index': '100'
            }).slideDown();

        },
        error: function(data) {
            alert('here');

            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
        }
    });
}

Main.SearchFaq = function() {
    if (!Common.FormValidate($('#txtFaqWord'))) {
        alert('검색어를 입력해주세요.');
        $('#txtFaqWord').focus();
        return false;
    }

    location.href = '/Customer/FAQ/FAQList.aspx?Category=0&SearchFlag=0&SearchString=' + escape($('#txtFaqWord').val()) + '&IsSearch=1';
}

// 메인 아이템샵
Main.GeItemShopMainList = function() {
    var $itemshop1 = $('#main-itemshop1');
    var $itemshop2 = $('#main-itemshop2');

    $('#prev1').show();
    $('#next1').show();
    $('#prev2').hide();
    $('#next2').hide();

    $itemshop2.empty();

    $.ajax({
        url: '/WebService/NoAuthWebService.asmx/GetMainItemShopList',
        data: '{}',
        success: function(data) {
            var result, list;
            eval('result = ' + data.d);
            list = result.List;

            var html = '<div class="slide">\n';
            html += '<ul class="main-item-list">\n';
            var i = 0;
            $.each(list, function(index, obj) {
                i = index + 1;

                html += '<li>\n';
                html += '<a href="' + base_url + 'ItemShop/ItemFind/' + escape(obj.ItemName) + '"><img src="' + obj.ItemImg + '" alt="" width="72" height=72"></a>\n';
                html += '<p class="item-title"><a href="' + base_url + 'ItemShop/ItemFind/' + escape(obj.ItemName) + '">' + Common.CutText(obj.ItemName, 12) + '</a></p>\n';
                //html += '<p class="item-momey"><img src="..//Images/Icon/won_icon.gif" alt="">' + obj.ItemPrice + '</p>\n';
                html += '</li>\n';

                if (i % 4 == 0) {
                    html += '</ul>\n';
                    html += '</div>\n';

                    if (list.length != i) {
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
                animationComplete: function(current) {
                    // Set the slide number as a hash
                    //window.location.hash = '#' + current;
                }
            });
        },
        error: function(data) {
            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
        }
    });
}

// 메인 GM 추천 아이템

Main.GetItemShopMainGMList = function() {
    var $itemshop1 = $('#main-itemshop1');
    var $itemshop2 = $('#main-itemshop2');

    $('#prev1').hide();
    $('#next1').hide();
    $('#prev2').show();
    $('#next2').show();

    $itemshop1.empty();

    $.ajax({
        url: '/aziaadmin/Webservice/GetMainItemShopGMList',
        data: '{}',
        success: function(data) {
            var result, list;
            eval('result = ' + data.d);
            list = result.List;

            var html = '<div class="slide">\n';
            html += '<ul class="main-item-list">\n';
            var i = 0;
            $.each(list, function(index, obj) {
                i = index + 1;

                html += '<li>\n';
                html += '<a href="/ItemShop/?SearchString=' + escape(obj.ItemName) + '"><img src="' + obj.ItemImg + '" alt="" width="72" height=72"></a>\n';
                html += '<p class="item-title"><a href="/ItemShop/?SearchString=' + escape(obj.ItemName) + '">' + Common.CutText(obj.ItemName, 12) + '</a></p>\n';
                //html += '<p class="item-momey"><img src="..//Images/Icon/won_icon.gif" alt="">' + obj.ItemPrice + '</p>\n';
                html += '</li>\n';

                if (i % 4 == 0) {
                    html += '</ul>\n';
                    html += '</div>\n';

                    if (list.length != i) {
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

            // Set starting slide to 1
            var startSlide = 1;
            // Get slide number if it exists
            if (window.location.hash) {
                startSlide = window.location.hash.replace('#', '');
            }
            // Initialize Slides
            $('#slides2').slides({
                preload: true,
                preloadImage: '..//Images/ajax-loader.gif',
                generatePagination: false,
                play: 3000,
                pause: 2500,
                hoverPause: true,
                // Get the starting slide
                start: startSlide,
                animationComplete: function(current) {
                    // Set the slide number as a hash
                    //window.location.hash = '#' + current;
                }
            });
        },
        error: function(data) {
            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
        }
    });
}

Main.GetMainItemSearchAutoList = function() {
    $.ajax({
        url: '/WebService/NoAuthWebService.asmx/GetMainItemSearchAutoList',
        data: '{"CategoryFirst" : "' + $('#selItemCategory').val() + '", "SearchString" : "' + $('#txtItemSearchString').val() + '"}',
        success: function(data) {
            var result, list;            
            eval("result = " + data.d);
            list = result.List;
        },
        error: function(data) {
            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
        }
    });
}

// 대표 캐릭터 목록
Main.GetUserChaList = function() {
    $('#cha-list').html('<div style="text-align:center; padding:10px 0;"><img src="/Images/ajax-loader.gif" alt=""></div>');
    $('.represent-character').show();

    $.ajax({
        url: '/WebService/AuthWebService.asmx/GetUserChaList',
        data: '{"UserID" : "' + UserID + '"}',
        success: function(data) {
            var result, list;
            eval('result = ' + data.d);
            list = result.List;
            /*    
            <ul>
            <li><a>[메슬란] 모션아리</a></li>
            <li><a>[메슬란] 아리창고CRM</a></li>
            <li><a>[메슬란] 잉여잉여아리쨩</a></li>
            <li><a>[세인트] 총10자로된닉네임이다</a></li>
            </ul>
            */

            var html = '';
            if (list.length > 0) {
                html = '<ul>';
                $.each(list, function(index, obj) {
                    html += '<li><a class="cha-item" SGNum="' + obj.SGNum + '" ChaNum="' + obj.ChaNum + '">[' + obj.SGName + ']' + obj.ChaName + '</a></li>';
                });
                html += '</ul>'
            }
            else {
                html = '<ul><li class="nothing">선택 가능한 캐릭터가 없습니다.<br>캐릭터를 생성해주세요.</li><ul>';
            }

            $('#cha-list').html(html);

            $('li a.cha-item').click(function() {
                $('li a.cha-item').css({
                    'color': '#888',
                    'font-weight': 'normal'
                });

                $(this).css({
                    'color': '#cc0000',
                    'font-weight': 'bold'
                });

                $('#hdnSGNum').val($(this).attr('SGNum'));
                $('#hdnUserChaNum').val($(this).attr('ChaNum'));
            });
        },
        error: function(data) {
            alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
        }
    });
}

// 대표 캐릭터 친구 목록
Main.GetUserFriendList = function() {
    $('#page-no').text('0');
    $('#total-page').text('0');
    if (IsAuth == 'False') {
        $('#relation-list').html('<li class="nothing">로그인을 하시면 내 친구와 클럽원<br>접속현황을 확인 하실 수 있습니다.</li>');
        $('#page-btn1').hide();
        $('#page-btn2').hide();
    } else {
        $('#page-btn1').show();
        $('#page-btn2').hide();
        $('#relation-list').html('<li class="nothing"><img src="..//Images/ajax-loader.gif" alt=""></li>');
        $.ajax({
            url: '/WebService/AuthWebService.asmx/GetUserFriendList',
            data: '{"PageNo" : "' + PageNo + '", "PageSize" : "5", "UserID" : "' + UserID + '"}',
            success: function(data) {
                var result, TotalItemCount, list;
                eval('result = ' + data.d);
                TotalItemCount = result.TotalItemCount;
                list = result.List;

                PageCount = Math.ceil(TotalItemCount / 5);

                var html = '';
                if (list.length > 0) {
                    $.each(list, function(index, obj) {
                        if (obj.FChaOnline == '0') {
                            html += '<li class="off">' + obj.FChaName + '<span class="state"><img src="..//Images/Icon/off_icon.gif" alt="on"></span></li>';
                        } else {
                            html += '<li class="on">' + obj.FChaName + '<span class="state"><img src="..//Images/Icon/on_icon.gif" alt="on"></span></li>';
                        }
                    });
                }
                else {
                    html = '<li class="nothing">등록된 친구가 없습니다.</li>';
                }

                $('#relation-list').html(html);

                $('#page-no').text(PageNo);
                $('#total-page').text(PageCount);
            },
            error: function(data) {
                alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
            }
        });
    }
}

// 대표 캐릭터 길드원 목록
Main.GetUserGuildList = function() {
    $('#page-no').text('0');
    $('#total-page').text('0');
    if (IsAuth == 'False') {
        $('#relation-list').html('<li class="nothing">로그인을 하시면 내 친구와 클럽원<br>접속현황을 확인 하실 수 있습니다.</li>');
        $('#page-btn1').hide();
        $('#page-btn2').hide();
    } else {
        $('#page-btn1').hide();
        $('#page-btn2').show();
        $('#relation-list').html('<li class="nothing"><img src="..//Images/ajax-loader.gif" alt=""></li>');
        $.ajax({
            url: '/WebService/AuthWebService.asmx/GetUserGuildList',
            data: '{"PageNo" : "' + PageNo + '", "PageSize" : "5", "SGNum" : "' + SGNum + '", "ChaNum" : "' + ChaNum + '"}',
            success: function(data) {
                var result, TotalItemCount, list;
                eval('result = ' + data.d);
                TotalItemCount = result.TotalItemCount;
                list = result.List;

                PageCount = Math.ceil(TotalItemCount / 5);

                var html = '';
                if (list.length > 0) {
                    $('#relation-list').empty();
                    $.each(list, function(index, obj) {
                        if (obj.ChaOnline == '0') {
                            html += '<li class="off">' + obj.ChaName + '<span class="state"><img src="../Images/Icon/off_icon.gif" alt="on"></span></li>';
                        } else {
                            html += '<li class="on">' + obj.ChaName + '<span class="state"><img src="../Images/Icon/on_icon.gif" alt="on"></span></li>';
                        }
                    });
                }
                else {
                    html = '<li class="nothing">등록된 클럽원이 없습니다.</li>';
                }

                $('#relation-list').html(html);

                $('#page-no').text(PageNo);
                $('#total-page').text(PageCount);
            },
            error: function(data) {
                alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
            }
        });
    }
}