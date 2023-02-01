    $(document).ready(function() {
        $("#add_item").autocomplete({
            source: site_url+'check_in/suggestions',
            minLength: 1,
            autoFocus: false,
            delay: 500,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    alert(lang.no_match_found, function () {
                        $('#add_item').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                  
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
                    var row = add_order_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    bootbox.alert(lang.no_match_found);
                }
            }
        });

        // $("#supplier").autocomplete({
        //     source: site_url+'check_in/suppliers',
        //     minLength: 1,
        //     autoFocus: false,
        //     delay: 200
        // });

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

        var old_row_qty;
        $(document).on("focus", '.rquantity', function () {
            old_row_qty = $(this).val();
        }).on("change", '.rquantity', function () {
            var row = $(this).closest('tr');
            if (!is_numeric($(this).val())) {
                $(this).val(old_row_qty);
                bootbox.alert(lang.unexpected_value);
                return;
            }
            var new_qty = parseFloat($(this).val()),
            item_id = row.attr('data-item-id');
            stinitems[item_id].row.qty = new_qty;
            store('stinitems', JSON.stringify(stinitems));
            loadInItems();
        });

    });

function loadInItems() {

    if (get('stinitems')) {

        $("#inTable tbody").empty();

        stinitems = JSON.parse(get('stinitems'));
      
        
        $.each(stinitems, function () {

            var item = this;
            var item_id = item.id;
            
            //stinitems[item_id] = item;

            var product_id = item.row.id, item_qty = item.row.qty, item_code = item.row.code, warehouse_id = item.row.warehouse_id
            item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
          

            var row_no = (new Date).getTime();
            var newTr = $('<tr id="' + row_no + '" class="' + item_id + '" data-item-id="' + item_id + '"></tr>');
            tr_html = '<td style="min-width:100px;"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')</span></td>';
            tr_html += '<td style="padding:2px;"><input class="form-control input-sm kb-pad text-center rquantity" name="quantity[]" type="text" value="' + item_qty + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
            // tr_html = '<td><input name="warehouse_id[]" type="hidden" class="rid" value="' + warehouse_id + '">"'+warehouse_id+'"</td>';
            tr_html += '<td class="text-center"><i class="fa fa-trash-o tip pointer stindel" id="' + row_no + '" title="Remove"></i></td>';
            
        
            newTr.html(tr_html);
            newTr.prependTo("#inTable");
            
        });

        $('#add_item').focus();
    }
}

function add_order_item(item) {

    var item_id = item.id;
    if (stinitems[item_id]) {
        stinitems[item_id].row.qty = parseFloat(stinitems[item_id].row.qty) + 1;
    } else {
        stinitems[item_id] = item;
    }
    console.log(stinitems)
    store('stinitems', JSON.stringify(stinitems));
    loadInItems();
    return true;
}