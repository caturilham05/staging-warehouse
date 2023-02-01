<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
</div>
</section>
<div class="clearfix"></div>
</section>
<footer class="site-footer">
  <div class="text-center">
    &copy; <?= date('Y').' '.$Settings->site_name; ?> v0.1
</div>
</footer>
</section>
<div id="jp"></div>
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<script type="text/javascript" src="<?= $assets ?>helpers/datatables/datatables.min.js"></script>
<!-- <script type="text/javascript" type="text/javascript" src="<?= $assets ?>helpers/datatables_new/datatables.min.js"></script>
<script type="text/javascript" src="<?= base_url('themes/default/assets/helpers/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script type="text/javascript" src="<?= base_url('themes/default/assets/helpers/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script type="text/javascript" src="<?= base_url('themes/default/assets/helpers/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script> -->
<script type="text/javascript" src="<?= $assets ?>js/app.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/custom-scripts.js"></script>
<script type="text/javascript">
    var base_url = '<?=base_url();?>', site_url = '<?=site_url('/');?>', rows_per_page = <?= $Settings->rows_per_page ?>;
    var dateformat = '<?= $Settings->dateformat ?>',  timeformat = '<?= $Settings->timeformat ?>';
    var lang = new Array();
    lang['code_error'] = '<?= lang('code_error'); ?>';
    lang['r_u_sure'] = '<?= lang('r_u_sure'); ?>';
    lang['no_match_found'] = '<?= lang('no_match_found'); ?>';
    lang['unexpected_value'] = '<?= lang('unexpected_value'); ?>';
    <?php if(isset($message) && !empty($message)) { ?>
        toastr.success('<?= addslashes(str_replace(array("\r", "\n"), "", $message)); ?>', 'Success!', {timeOut: 10000});
    <?php } ?>
    <?php if(isset($error) && !empty($error)) { ?>
        toastr.error('<?= addslashes(str_replace(array("\r", "\n"), "", $error)); ?>', 'Error!', {timeOut: 30000});
    <?php } ?>
    <?php if(isset($warning) && !empty($warning)) { ?>
        toastr.warning('<?= addslashes(str_replace(array("\r", "\n"), "", $warning)); ?>', 'Warning!', {timeOut: 10000});
    <?php } ?>
</script>

<script type="text/javascript" charset="UTF-8">
<?php
$s2_lang_file = read_file('./assets/config_dumps/s2_lang.js');
foreach (lang('select2_lang') as $s2_key => $s2_line) {
    $s2_data[$s2_key] = str_replace(array('{', '}'), array('"+', '+"'), $s2_line);
}
$s2_file_date = $this->parser->parse_string($s2_lang_file, $s2_data, true);
echo $s2_file_date;
?>
    $.extend(true, $.fn.dataTable.defaults, {"oLanguage":<?=$dt_lang?>});
</script>
</body>
</html>
