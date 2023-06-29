$(document).ready(function() {
   
    $('#txtID').focus(function() {
        $(this).css('background-image', 'none');
    });

    $('#txtID').blur(function() {
        if ($(this).val().replace(/\s/g, "").length == 0) {
            $(this).css('background', '#000 url(./Images/Login/id_txt.gif) no-repeat 5% 50%');
        }
    });

    $('#txtPW').focus(function() {
        $(this).css('background-image', 'none');
    });

    $('#txtPW').blur(function() {
        if ($(this).val().replace(/\s/g, "").length == 0) {
            $(this).css('background', '#000 url(./Images/Login/pwd_txt.gif) no-repeat 5% 50%');
        }
    });

    $('#txtID').keypress(function(e) {
        if (e.keyCode == '13') {
            return false;
        }
    });

    $('#txtPW').keypress(function(e) {
        if (e.keyCode == '13') {
            return false;
        }
    });

    $('#txtID').keyup(function(e) {
        if (e.keyCode == "13") {
            $("#txtPW").focus();
        }
    });

    $('#txtPW').keyup(function(e) {
        if (e.keyCode == "13") {
            $("#btnLogin").click();
        }
    });

    $('#btnLogin').click(function() {
        if (!Common.FormValidate($('#txtID'))) {
            alert('Please enter your ID.');
            $('#txtID').focus();
            return false;
        }

        if (!Common.FormValidate($('#txtPW'))) {
            alert('Please enter a password.');
            $('#txtPW').focus();
            return false;
        }

        //$('#frmLogin').attr('action', 'https/www.ran-online.co.kr/MasterPage/LoginProcess.aspx').submit();
        $('#frmLogin').attr('action', '' + base_url + 'jrgapi/MasterPage/player').submit();

        /*
        $.ajax({
        url: '/WebService/NoAuthWebService.asmx/LoginUser',
        data: '{"UserID" : "' + $("#txtID").val() + '", "UserPassword" : "' + $("#txtPW").val() + '", "Heartbeat" : "' + $('#hdnHeartbeat').val() + '"}',
        success: function(data) {
        var result = data.d;

                if (result == 0) {
        $('#frmLogin').attr('action', '/Account/LoginProcess.asp').submit();
        } else if (result == 1) {
        alert('등록되지 않은 계정입니다.');
        } else if (result == 2) {
        alert('비밀번호가 일치하지 않습니다.');
        } else if (result == 3) {
        alert('현재 이용에 제한이 있는 계정입니다.');
        } else if (result == 4) {
        alert('현재 탈퇴가 진행중인 계정입니다.\복구신청을 원하실 경우 ran@mincoms.co.kr 이메일을 통해\n계정(ID), 복구신청 사유를 기재하신 후 보내주시기 바랍니다.');
        } else if (result == 6) {
        alert('14세미만 회원입니다.');
        } else if (result == 7) {
        alert('해당 계정(ID)은 현재 하이코쿤(GF게임) 회원님으로 회원서비스 이관 후 서비스 이용이 가능합니다.');
        location.href = '/Account/TranDefault.asp';
        } else if (result == 100) {
        alert('잘못된 접근 경로입니다.');
        }
        },
        error: function(data) {
        alert(data.status + ' : ' + data.statusText + ' : ' + data.responseText);
        }
        });
        */
    });

    $('#btnLogout').click(function() {
        $.ajax({
            url: "/WebService/AuthWebService.asmx/UserLogout",
            data: "{ }",
            success: function(data) {
                location.href = '/Account/Logout.asp';
            },
            error: function(data) {
                alert(data.status + " : " + data.statusText + " : " + data.responseText);
            }
        });
    });
});