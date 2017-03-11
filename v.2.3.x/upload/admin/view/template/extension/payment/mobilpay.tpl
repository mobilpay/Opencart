<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-mobilpay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><img src="view/image/payment/mobilpay.gif" alt="" /> <?php echo $heading_title; ?></h1>
	  <ul class="breadcrumb">
	    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
	    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	    <?php } ?>
	  </ul>
	</div>
  </div>
<div class="container-fluid">
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php } ?>
  <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-mobilpay" class="form-horizontal">
        <div class="form-group required">
		<label class="col-sm-2 control-label" for="input-signature"><?php echo $entry_signature; ?></label>
		<div class="col-sm-10">
			<input type="text" name="mobilpay_signature" value="<?php echo $mobilpay_signature; ?>" placeholder="<?php echo $entry_signature; ?>" id="input-merchant" class="form-control"/>
		      <?php if ($error_signature) { ?>
		      <div class="text-danger"><?php echo $error_signature; ?></div>
		      <?php } ?>
		</div>
	</div>
	<div class="form-group">
            <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_test; ?></label>
            <div class="col-sm-10">
              <select name="mobilpay_test" id="input-test" class="form-control">
                <?php if ($mobilpay_test == '1') { ?>
                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_yes; ?></option>
                <?php } ?>
                <?php if ($mobilpay_test == '0') { ?>
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_no; ?></option>
                <?php } ?>
              </select>
            </div>
	</div>
	<div class="form-group">
	 <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_order_status; ?></label>
	 <div class="col-sm-10">
	  <select name="mobilpay_order_status_id" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $mobilpay_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
	  </select>
	 </div>
	</div>
	<div class="form-group">
	 <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_order_status_confirmed_pending; ?></label>
	 <div class="col-sm-10">
	  <select name="mobilpay_order_status_confirmed_pending_id" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $mobilpay_order_status_confirmed_pending_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
	  </select>
	 </div>
	</div>
	<div class="form-group">
	 <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_order_status_paid_pending; ?></label>
	 <div class="col-sm-10">
	  <select name="mobilpay_order_status_paid_pending_id" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $mobilpay_order_status_paid_pending_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
	  </select>
	 </div>
	</div>
	<div class="form-group">
	 <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_order_status_paid; ?></label>
	 <div class="col-sm-10">
	  <select name="mobilpay_order_status_paid_id" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $mobilpay_order_status_paid_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
	  </select>
	 </div>
	</div> 
	<div class="form-group">
	 <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_order_status_canceled; ?></label>
	 <div class="col-sm-10">
	  <select name="mobilpay_order_status_canceled_id" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $mobilpay_order_status_canceled_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
	  </select>
	 </div>
	</div> 
	<div class="form-group">
	 <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_order_status_credit; ?></label>
	 <div class="col-sm-10">
	  <select name="mobilpay_order_status_credit_id" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $mobilpay_order_status_credit_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
	  </select>
	 </div>
	</div>
	<div class="form-group">
	 <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
	 <div class="col-sm-10">
	  <input type="text" name="worldpay_total" value="<?php echo $mobilpay_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
	 </div>
	</div>	  
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="mobilpay_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $mobilpay_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <tr>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="mobilpay_status" id="input-status" class="form-control">
                <?php if ($mobilpay_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="mobilpay_sort_order" value="<?php echo $mobilpay_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
