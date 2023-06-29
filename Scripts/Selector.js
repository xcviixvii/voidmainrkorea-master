/// <reference path="jquery-1.5.1-vsdoc.js" />
function init_select(select, list, hdnVal, fn) {
    var cls = $('#' + select).attr('class');

    if (cls.indexOf('select-on') == -1) {
        $('#' + select).addClass('select-on');
        $('#' + list).show();
    } else {
        $('#' + select).removeClass('select-on');
        $('#' + list).hide();
    }

    $('#' + list).hover(
        function() {
            $(this).show();
        },
        function() {
            $(this).hide();
            $('#' + select).removeClass('select-on');
        }
    );

    $('#' + list + ' .selected').click(function() {
        $('#' + select + ' span').text($(this).text());
        $('#' + hdnVal).val($(this).attr('title'));
        $('#' + select).removeClass('select-on');
        $('#' + list).hide();

        if (fn != null) {
            fn();
        }
    });

    $('#' + list + ' .selected-email').click(function() {
        $('#' + select + ' span').text($(this).text());
        $('#' + hdnVal).val($(this).attr('title'));
        $('#' + select).removeClass('select-on');
        $('#' + list).hide(); ;

        if ($(this).attr('title') == '0') {
            $('#txtUserEmailServer').show();
        } else {
            $('#txtUserEmailServer').hide();
        }
    });
}