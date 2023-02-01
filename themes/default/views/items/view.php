<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?= $item->name.' ('.$item->code.')'; ?></h4>
        </div>
        <div class="modal-body">

            <?php if (!empty($checkins)) { ?>
            <h4><?= lang('last_5_check_ins'); ?></h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped print-table order-table">
                    <thead>
                        <tr>
                            <th><?= lang("date"); ?></th>
                            <th><?= lang("reference"); ?></th>
                            <th><?= lang("quantity"); ?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($checkins as $check_in): ?>
                            <tr>
                                <td style="text-align:center; vertical-align:middle;"><?= $this->tec->hrld($check_in->date); ?></td>
                                <td style="text-align:center; vertical-align:middle;"><?= $check_in->reference; ?></td>
                                <td class="col-xs-2" style="text-align:center; vertical-align:middle;"><?= $check_in->quantity; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>

            <?php if (!empty($checkouts)) { ?>
            <h4><?= lang('last_5_check_outs'); ?></h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped print-table order-table">
                    <thead>
                        <tr>
                            <th><?= lang("date"); ?></th>
                            <th><?= lang("reference"); ?></th>
                            <th><?= lang("quantity"); ?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($checkouts as $check_out): ?>
                            <tr>
                                <td style="text-align:center; vertical-align:middle;"><?= $this->tec->hrld($check_out->date); ?></td>
                                <td style="text-align:center; vertical-align:middle;"><?= $check_out->reference; ?></td>
                                <td class="col-xs-2" style="text-align:center; vertical-align:middle;"><?= $check_out->quantity; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>
            <?= (empty($checkins) && empty($checkouts)) ? '<h4>'.lang('no_record_found').'</h4>' : ''; ?>

        </div>
    </div>
</div>