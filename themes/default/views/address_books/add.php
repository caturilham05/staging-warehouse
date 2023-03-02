<?php (defined('BASEPATH')) or exit('No direct script access allowed');
$this->load->helper('function_helper');
?>

<div class="">
    <h3><i class="fa fa-barcode"></i> <?= $page_title; ?></h3>
    <p><?= lang('list_results'); ?></p>
</div>
<div class="row">  
    <div class="col-lg-12">
        <div class="content-panel">
            <?php
            echo form_open("address_books/add");
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo lang('Nama Pengirim', 'Nama Pengirim'); ?> 
                            <div class="controls">
                                <?php echo form_input('name', '', 'class="form-control" id="name" '); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo lang('Nomor HP', 'Nomor HP'); ?>
                            <div class="controls">
                                <?php echo form_input('phone', '', 'class="form-control" id="phone"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo lang('District', 'District'); ?>
                            <div class="controls">
                                <?php echo form_input('district', '', 'class="form-control" id="district"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo lang('Subdistrict', 'Subdistrict'); ?>
                            <div class="controls">
                                <?php echo form_input('subdistrict', '', 'class="form-control" id="subdistrict"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo lang('Zipcode', 'Zipcode'); ?>
                            <div class="controls">
                                <?php echo form_input('zipcode', '', 'class="form-control" id="zipcode"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?= lang('Pilih Kota', 'Pilih Kota'); ?>
                            <?php 
                            $origin_jne_data[''] = lang('Pilih Kota');
                            foreach ($origin_jne as $value) $origin_jne_data[$value['id']] = sprintf('%s (%s)', $value['origin_name'], $value['origin_code']);
                            ?>
                            <?= form_dropdown('location_id', $origin_jne_data, set_value('location_id'), 'class="form-control tip" id="location_id" required="required"'); ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php echo lang('Alamat Lengkap', 'Alamat Lengkap'); ?>
                            <div class="controls">
                                <?php echo form_textarea('address', '', 'class="form-control" id="address"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <div class="clearfix"></div>
                        <p><?php echo form_submit('add', lang('add_address_books'), 'class="btn btn-theme03"'); ?></p>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.warehouse').hide()
        
        $('#group').change(function () {
            var group = $(this).val();
            // alert(group)
            if (group == 2) {
                $('.warehouse').show();
                // $("#group").setAttr('required');
            } else {
                $('.warehouse').hide();
                // $("#group").removeAttr('required');
            }
        });
    });
</script>