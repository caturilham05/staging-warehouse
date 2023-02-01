$(document).ready(function(e) {

    $('.validation').formValidation({ framework: 'bootstrap', excluded: ':disabled' });
    $('.tooltips').tooltip();
    $('.po').popover();
    $('.del-po').popover({trigger: 'focus', html: true, container: 'body', placement: 'left'});
    $('.note').redactor({
        formatting: ['p', 'blockquote', 'h3', 'h4', 'pre'],
        minHeight: 100,
        maxHeight: 400,
        linebreaks: true,
        tabAsSpaces: 4,
    });

    $('.datetime').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        timePicker: true,
        timePicker24Hour: true,
        // opens: "center",
        locale: {
            format: 'YYYY-MM-DD HH:mm'
        }
    });

    $('.date').daterangepicker({
        // opens: "center",
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });

    $(":file").filestyle();
    $('select').select2({ minimumResultsForSearch: 6 });

});

toastr.options = { progressBar: true, closeButton: true };

function get(name) {
    if (typeof (Storage) !== "undefined") {
        return localStorage.getItem(name);
    } else {
        alert('Please use a modern browser as this site needs localstroage!');
    }
}

function store(name, val) {
    if (typeof (Storage) !== "undefined") {
        localStorage.setItem(name, val);
    } else {
        alert('Please use a modern browser as this site needs localstroage!');
    }
}

function remove(name) {
    if (typeof (Storage) !== "undefined") {
        localStorage.removeItem(name);
    } else {
        alert('Please use a modern browser as this site needs localstroage!');
    }
}

function hrsd(sdate) {
    if (sdate !== null) {
        return date(dateformat, strtotime(sdate));
    }
    return sdate;
}

function hrld(ldate) {
    if (ldate !== null) {
        return date(dateformat+' '+timeformat, strtotime(ldate));
    }
    return ldate;
}

function is_numeric(mixed_var) {
    var whitespace =
    " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
        1)) && mixed_var !== '' && !isNaN(mixed_var);
}

function align_center(n) {
    return '<div class="text-center">'+(n != null ? n : '')+'</div>';
}

function formatCurrency(n) {
    return '<div class="text-right">'+formatNumber(n)+'</div>';
}

function cf(n) {
    if (n != null) {
        return '<div class="text-right">'+formatNumber(n)+'</div>';
    }
    return '';
}

function formatNumber(number, symbole) {
    if(!symbole) { symbole = ''; }
    var n = accounting.formatNumber(number, 2, ',', '.');
    return symbole+n;
}

function getNumber(x) {
    return accounting.unformat(x);
}

$(document).ajaxStart(function(){
    $('#ajaxCall').show();
}).ajaxStop(function(){
    $('#ajaxCall').hide();
});

$(document).ready(function() {
    $('body').on('click', '.check_out_link td:not(:nth-child(5), :last-child)', function() {
        $.get( site_url + 'check_out/view/' + $(this).parent('.check_out_link').attr('id'), function( data ) {
            $('#myModal').html(data);
            $('#myModal').modal('show');
        });
    });
    $('body').on('click', '.check_in_link td:not(:nth-child(5), :last-child)', function() {
        $.get( site_url + 'check_in/view/' + $(this).parent('.check_in_link').attr('id'), function( data ) {
            $('#myModal').html(data);
            $('#myModal').modal('show');
        });
    });
    $('body').on('click', '.item_link td:not(:first-child, :last-child)', function() {
        $.get( site_url + 'items/report/' + $(this).parent('.item_link').attr('id'), function( data ) {
            $('#myModal').html(data);
            $('#myModal').modal('show');
        });
    });
    $(document).on('click', '.po', function(e) {
        e.preventDefault();
        $('.po').popover({container: 'body', html: true, placement: 'left', trigger: 'manual'}).popover('show').not(this).popover('hide');
        return false;
    });
    $(document).on('click', '.po-close', function() {
        $('.po').popover('hide');
        return false;
    });
});
