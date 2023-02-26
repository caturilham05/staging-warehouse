<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="">
    <h3><?= $page_title; ?></h3>
    <p><?= lang('list_results'); ?></p>
</div>
<div class="row">  
    <div class="col-lg-12">
        <div class="content-panel">
            <?= form_open(empty($stock_opname_id) ? 'stockopname/stock_opname_add_view' : 'stockopname/stock_opname_process_view/'.$stock_opname_id);?>
            	<div class="row">
            		<div class="col-md-12">
            			<div class="row">
            				<div class="col-md-6">
            					<div class="form-group">
                        <h3><b><?= $so_data['stock_opname']?></b></h3>
                        <?= form_hidden('stock_opname_id', empty($stock_opname_id) ? $so_data['id'] : $stock_opname_id, 'class="form-control tip" id="stock_opname_id"'); ?>
            					</div>
            				</div>

                    <?php
                        if (!empty($this->session->userdata('warehouse_id')))
                        {
                            ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h3>Warehouse: <b><?= $warehouse->name?></b></h3>
                                        <?= form_hidden('warehouse_id', $warehouse->id, 'class="form-control tip" id="warehouse_id"'); ?>
                                    </div>
                                </div>                                            
                            <?php
                        }
                        else
                        {
                            ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang('Warehouse', 'Warehouse'); ?>
                                        <?php 
                                        $warehouse_data[''] = lang('Select Warehouse');
                                        foreach ($warehouse as $value) $warehouse_data[$value->id] = $value->name;
                                        ?>
                                        <?= form_dropdown('warehouse_id', $warehouse_data, set_value('warehouse_id'), 'class="form-control tip" id="warehouse_id"'); ?>
                                    </div>
                                </div>
                            <?php
                        }
                    ?>
            			</div>
            			<div class="row" style="margin-top: 3rem">
                    <div class="col-md-12">
                        <div class="form-group">
                          <?= lang('Product Code', 'Product Code'); ?>
                          <?= form_input('product_code', '', 'class="form-control tip" id="product_code" placeholder="Input Product Code" style="height: 50px;"'); ?>
                          <center>
	                          <small>Enter the product code to add in stock opname</small>
                          </center>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 2rem">
                      <div class="table table-responsive">
                        <table id="inTable" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>Description</th>
                              <th>Qty</th>
                              <!-- <th>Qty Real</th> -->
                              <th style="width:25px;"><i class="fa fa-trash-o"></i></th>
                            </tr>
                          </thead>
                          <tbody id="body">
                            <tr>
                              <td colspan="4"><?= lang('add_product_by_searching_above_field'); ?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                          <?= lang('notes', 'notes'); ?>
                          <?= form_textarea('notes', set_value('notes'), 'class="form-control redactor tip" id="notes"'); ?>
                        </div>
                    </div>
                    <div class="col-md-2">
          						<?php echo form_submit('create_stock_opname', lang('Create Stock Opname'), 'class="btn btn-primary"'); ?>
                    </div>
            			</div>
            		</div>
            	</div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script src="<?= $assets ?>js/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
  var stoutitems = {};
  $(document).ready(function(){
    if (get('stoutitems')) { remove('stoutitems'); }
    loadInItems();

    $("#product_code").autocomplete({
      source: site_url+'check_out/suggestions',
      minLength: 1,
      autoFocus: false,
      delay: 200,
      response: function (event, ui) {
          if ($(this).val().length >= 16 && ui.content[0].id == 0) {
              alert(lang.no_match_found, function () {
                  $('#product_code').focus();
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
                  $('#product_code').focus();
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

    $('#product_code').bind('keypress', function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            $(this).autocomplete("search");
        }
    });

    $(document).on('click', '.stoutdel', function (e) {
        e.preventDefault();
        var row = $(this).closest('tr');
        var item_id = row.attr('data-item-id');
        delete stoutitems[item_id];
        row.remove();
        if(stoutitems.hasOwnProperty(item_id)) { } else {
            store('stoutitems', JSON.stringify(stoutitems));
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
        stoutitems[item_id].row.qty = new_qty;
        store('stoutitems', JSON.stringify(stoutitems));
        loadInItems();
    });
  })

function loadInItems() {
  if (get('stoutitems')) {
    $("#inTable tbody").empty();
    stoutitems = JSON.parse(get('stoutitems'));
    console.log(stoutitems)
    $.each(stoutitems, function () {
        var item = this;
        var item_id = item.id;
        stoutitems[item_id] = item;
        console.log(item)
        var product_id = item.row.id, item_qty = item.row.qty, item_aqty = item.row.quantity, item_code = item.row.code,
        item_name = item.row.name, item_qty_total = item.row.qty_real;

        item_qty_total = item_qty_total == null ? 0 : item_qty_total;

        var row_no = (new Date).getTime();
        var newTr = $('<tr id="' + row_no + '" class="' + item_id + '" data-item-id="' + item_id + '"></tr>');
        tr_html = '<td style="min-width:100px;"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')</span></td>';
        tr_html += '<td style="padding:2px;"><input class="form-control input-sm kb-pad text-center rquantity" name="quantity[]" type="text" value="' + item_qty + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();" min="1"></td>';
        // tr_html += '<td style="padding-left:10px;">'+item_qty_total+'</td>';

        tr_html += '<td class="text-center"><i class="fa fa-trash-o tip pointer stoutdel" id="' + row_no + '" title="Remove"></i></td>';
        newTr.html(tr_html);
        newTr.prependTo("#inTable");
        if(item_aqty < item_qty) {
            $('#'+row_no).addClass('danger');
        }
    });

    $('#product_code').focus();
  }
}


function add_order_item(item) {
  var item_id = item.id;
  if (stoutitems[item_id]) {
    stoutitems[item_id].row.qty = parseFloat(stoutitems[item_id].row.qty) + 1;
  } else {
    stoutitems[item_id] = item;
  }

  store('stoutitems', JSON.stringify(stoutitems));
  loadInItems();
  return true;
}
</script>
