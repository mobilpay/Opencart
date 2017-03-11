<form action="<?php echo $action; ?>" method="post" id="payment">
  <input type="hidden" name="env_key" value="<?php echo $env_key;?>"/>
  <input type="hidden" name="data" value="<?php echo $data;?>"/>
  <div class="buttons">
    <div class="pull-right">
    <a onclick="$('#payment').submit();" class="btn btn-primary"><?php echo $button_confirm; ?></a>
    </div>
  </div>
</form>
