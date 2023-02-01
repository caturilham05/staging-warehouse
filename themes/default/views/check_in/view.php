<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?= $page_title; ?></h4>
          </div>
        <div class="modal-body">

            <div class="well well-sm">
                <div class="row bold">
                    <div class="col-sm-12">
                    <table class="table mb0">
                        <tbody>
                            <tr>
                                <td class="col-xs-3" style="border-top:0;"><?= lang("ref"); ?></td>
                                <td class="col-xs-9" style="border-top:0;">: <?= $inv->reference; ?></td>
                            </tr>
                            <tr>
                                <td><?= lang("date"); ?></td>
                                <td>: <?= $this->tec->hrld($inv->date); ?></td>
                            </tr>
                            <tr>
                                <td><?= lang("supplier"); ?></td>
                                <td>: <?= $supplier ? $supplier->name : ''; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped print-table order-table">

                    <thead>

                    <tr>
                        <th><?= lang("no"); ?></th>
                        <th><?= lang("description"); ?></th>
                        <th><?= lang("quantity"); ?></th>
                    </tr>

                    </thead>

                    <tbody>

                    <?php $r = 1;
                    $tax_summary = array();
                    foreach ($items as $row):
                    ?>
                        <tr>
                            <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                            <td style="vertical-align:middle;">
                                <?= $row->item_name . " (" . $row->item_code . ")"; ?>
                            </td>
                            <td class="col-xs-2" style="text-align:center; vertical-align:middle;"><?= $row->quantity; ?></td>
                        </tr>
                        <?php
                        $r++;
                    endforeach;
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-xs-7">
                    <?php
                        if ($inv->note || $inv->note != "") { ?>
                            <div class="well well-sm">
                                <p class="bold"><?= lang("note"); ?>:</p>
                                <div><?= $this->tec->decode_html($inv->note); ?></div>
                            </div>
                        <?php
                        }
                        ?>
                </div>

                <div class="col-xs-5 pull-right mb0">
                    <div class="well well-sm">
                        <p>
                            <?= lang("created_by"); ?>: <?= $created_by->first_name . ' ' . $created_by->last_name; ?> <br>
                            <?= lang("date"); ?>: <?= $this->tec->hrld($inv->date); ?>
                        </p>
                        <?php if ($inv->updated_by) { ?>
                        <p>
                            <?= lang("updated_by"); ?>: <?= $updated_by->first_name . ' ' . $updated_by->last_name;; ?><br>
                            <?= lang("update_at"); ?>: <?= $this->tec->hrld($inv->updated_at); ?>
                        </p>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>