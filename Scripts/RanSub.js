var Sub = function() { }

$(document).ready(function() {
    // 대표 캐릭터 정보
    $('#btnCha').click(function() {
        if (document.getElementById('represent-character').style.display == 'none' || document.getElementById('represent-character').style.display == '') {
            Sub.GetUserChaList();
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

    // FAQ 검색
    $('#txtFaqWord').keyup(function(e) {
        if (e.keyCode == '13') {
            Sub.SearchFaq();
        }
    });
    $('#btnFaq').click(Sub.SearchFaq);
});

Sub.SearchFaq = function() {
    if (!Common.FormValidate($('#txtFaqWord'))) {
        alert('검색어를 입력해주세요.');
        $('#txtFaqWord').focus();
        return false;
    }

    location.href = '/Customer/FAQ/FAQList.aspx?Category=0&SearchFlag=0&SearchString=' + escape($('#txtFaqWord').val()) + '&IsSearch=1';
}

// 대표 캐릭터 목록
Sub.GetUserChaList = function() {
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
                html = '<ul><li class="nothing">선택 가능한 캐릭터가 없습니다.<br>캐릭터를 생성해주세요.</li></ul>';
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