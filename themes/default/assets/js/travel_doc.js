$(document).ready(function() {
    $("#add_item").autocomplete({
        source: site_url+'letter/suggestions',
        minLength: 1,
        autoFocus: false,
        delay: 200,
        response: function (event, ui) {

            if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                
                alert(lang.no_match_found, function () {
                    $('#add_item').focus();
                });
                $(this).val('');
            }
            else if ((ui.content.length == 1 || ui.content.length > 1) && ui.content[0].id != 0) {
                
                ui.item = ui.content[0];
                $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                $(this).autocomplete('close');
            }
            else if (ui.content.length == 1 && ui.content[0].id == 0) {
                alert(lang.no_match_found, function () {
                    $('#add_item').focus();
                });
                $(this).val('');
            }
        },
        select: function (event, ui) {
            event.preventDefault();

            if (ui.item.id !== 0) {
                var row = add_awb_sales(ui.item);
                if (row)
                    $(this).val('');
            } else {
                bootbox.alert(lang.no_match_found);
            }
        }
    });

    $('#add_item').bind('keypress', function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            $(this).autocomplete("search");
        }
    });

    $(document).on('click', '.stindel', function (e) {
        e.preventDefault();
        var row = $(this).closest('tr');
        var item_id = row.attr('data-item-id');
        delete stinitems[item_id];
        row.remove();
        if(stinitems.hasOwnProperty(item_id)) { } else {
            store('stinitems', JSON.stringify(stinitems));
            loadInItems();
            return;
        }
    });

});

function loadInItems() {

if (get('stinitems')) {

    $("#inTable tbody").empty();

    stinitems = JSON.parse(get('stinitems'));
    $.each(stinitems, function () {

        var item = this;
        var item_id = item.id;
        stinitems[item_id] = item;

        var item_awb = item.row.awb_no;

            var row_no = (new Date).getTime();
            var newTr = $('<tr id="' + row_no + '" class="' + item_id + '" data-item-id="' + item_id + '"></tr>');
            tr_html = '<td style="min-width:100px;"><input type="hidden" name="product_id[]" type="text" class="rid" value="' + item_awb + '"><span class="sname" id="name_' + row_no + '">' + item_awb + '</span></td>';
            tr_html += '<td class="text-center"><i class="fa fa-trash-o tip pointer stindel" id="' + row_no + '" title="Remove"></i></td>';
        newTr.html(tr_html);
      
        newTr.prependTo("#inTable");
        
    });

    $('#add_item').focus();
}
}

function add_awb_sales(item) {

var item_id = item.id;
stinitems[item_id] = item;


store('stinitems', JSON.stringify(stinitems));
loadInItems();
return true;
}