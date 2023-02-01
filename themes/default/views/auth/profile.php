<div class="row mt">
    <div class="col-lg-12">
        <div class="grey-panel">
            <h3><i class="fa fa-edit"></i> <?= lang('profile'); ?></h3>
            <p><?= lang('update_info'); ?></p>
            <?php
            echo form_open('auth/edit_user/'.$user->id);
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo lang('first_name', 'first_name'); ?> 
                            <div class="controls">
                                <?php echo form_input('first_name', $user->first_name, 'class="form-control" id="first_name" required="required"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo lang('last_name', 'last_name'); ?> 
                            <div class="controls">
                                <?php echo form_input('last_name', $user->last_name, 'class="form-control" id="last_name" required="required"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo lang('phone', 'phone'); ?>
                            <div class="controls">
                                <input type="tel" name="phone" class="form-control" id="phone" required="required" value="<?= $user->phone ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?= lang('gender', 'gender'); ?> 
                            <div class="controls">  <?php
                                $ge[''] = array('male' => lang('male'), 'female' => lang('female'));
                                echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : $user->gender), 'class="tip form-control" id="gender" required="required"');
                                ?> 
                            </div>
                        </div>
                    </div>
                    <?php if ($Admin && $id != $this->session->userdata('user_id')) { ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo lang('username', 'username'); ?>
                            <input type="text" name="username" class="form-control" id="username" value="<?= $user->username ?>" required="required" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo lang('email', 'email'); ?>

                            <input type="email" name="email" class="form-control" id="email" value="<?= $user->email ?>" required="required" />
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <div class="panel panel-warning">
                            <div class="panel-heading"><?= lang('if_you_need_to_rest_password_for_user') ?></div>
                            <div class="panel-body" style="padding: 5px;">

                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?php echo lang('password', 'password'); ?> 
                                            <?php echo form_input($password); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?php echo lang('confirm_password', 'password_confirm'); ?>
                                            <?php echo form_input($password_confirm); ?>   
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <div class="panel panel-warning">
                            <div class="panel-heading"><?= lang('user_options') ?></div>
                            <div class="panel-body" style="padding: 5px;">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?= lang('status', 'status'); ?>
                                            <?php
                                            $opt = array(1 => lang('active'), 0 => lang('inactive'));
                                            echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : $user->active), 'id="status" class="form-control input-tip select" style="width:100%;"');
                                            ?> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?= lang("group", "group"); ?>
                                            <?php
                                            $gp[""] = "";
                                            foreach ($groups as $group) {
                                                if($group['name'] != 'customer' && $group['name'] != 'supplier') {
                                                    $gp[$group['id']] = $group['name'];
                                                }
                                            }
                                            echo form_dropdown('group', $gp, (isset($_POST['group']) ? $_POST['group'] : $user->group_id), 'id="group" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("group") . '" class="form-control input-tip select" style="width:100%;"');
                                            ?> 
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php echo form_hidden('id', $id); ?>
                    <?php echo form_hidden($csrf); ?>
                </div>
            </div>  
            <div class="clearfix"></div>
            <div class="col-md-12">
                <?php echo form_submit('update', lang('update'), 'class="btn btn-theme03"'); ?>
            </div>
            <?php echo form_close(); ?>
            <div class="clearfix"></div>
        </div>	
        <div class="clearfix"></div>
        <?php if($id == $this->session->userdata('user_id')) { ?>
        <div class="white-panel">
            <h3><i class="fa fa-key"></i> <?= lang('change_your_password'); ?></h3>
            <p><?= lang('update_info'); ?></p>
            <?php echo form_open("auth/change_password"); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo lang('old_password', 'curr_password'); ?> <br />
                            <?php echo form_password('old_password', '', 'class="form-control" id="curr_password"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="new_password"><?php echo sprintf(lang('new_password'), $min_password_length); ?></label> <br />
                            <?php echo form_password('new_password', '', 'class="form-control" id="new_password" pattern=".{8,}"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo lang('confirm_password', 'new_password_confirm'); ?> <br />
                            <?php echo form_password('new_password_confirm', '', 'class="form-control" id="new_password_confirm" pattern=".{8,}"'); ?>

                        </div>
                    </div>
                    <?php echo form_input($user_id); ?>
                    <div class="col-md-12">
                        <p><?php echo form_submit('change_password', lang('change_password'), 'class="btn btn-theme02"'); ?></p>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <?php } ?>
    </div>
</div>
