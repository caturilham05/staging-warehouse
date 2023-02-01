<div class="">
    <h3><i class="fa fa-barcode"></i> <?= $page_title; ?><a href="<?= site_url('users/add') ?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> <?= lang('Add User'); ?></a></h3>
    <p><?= lang('list_results'); ?></p>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="content-panel">
            <table class="table table-bordered table-striped table-condensed cf">
                <thead class="cf">
                    <tr>
                        <th><?php echo lang('first_name'); ?></th>
                        <th><?php echo lang('last_name'); ?></th>
                        <th><?php echo lang('email'); ?></th>
                        <th><?php echo lang('group'); ?></th>
                        <th style="width:100px;"><?php echo lang('status'); ?></th>
                        <th style="width:80px;"><?php echo lang('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach($users as $user) {
                    echo '<tr>';
                    echo '<td>'.$user->first_name.'</td>';
                    echo '<td>'.$user->last_name.'</td>';
                    echo '<td>'.$user->email.'</td>';
                    echo '<td>'.$user->group.'</td>';
                    echo '<td class="text-center">'.($user->active ? '<span class="label label-success">'.lang('active').'</span>' : '<span class="label label-danger">'.lang('inactive').'</span>').'</td>';
                    echo '<td><div class="btn-group btn-group-justified" role="group"> <div class="btn-group" role="group"> <a class="btn btn-warning btn-xs tip" title="' . lang("profile") . '" href="' . site_url('users/profile/'.$user->id) . '"><i class="fa fa-edit"></i></a></div>
                    <div class="btn-group" role="group"><a href="#" class="btn btn-danger btn-xs tip del-po" title="<b>' . lang("delete_user") . '</b>" data-html="true" data-placement="left" data-trigger="focus" data-content="<p>' . lang('action_x_undone') . '</p><a class=\'btn btn-danger po-delete\' href=\'' . site_url('auth/delete/'.$user->id) . '\'>' . lang('i_m_sure') . '</a> <button class=\'btn po-close\'>' . lang('no') . '</button>" rel="popover"><i class="fa fa-trash-o"></i></a></div></div></td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
