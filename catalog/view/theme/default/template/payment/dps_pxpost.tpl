
<h2><?php echo $text_credit_card; ?></h2>
<div class="content" id="payment">
  <table class="form">
    <tr>
      <td><?php echo $entry_cc_owner; ?></td>
      <td><input type="text" name="cc_owner" value="" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_number; ?></td>
      <td><input type="text" name="cc_number" value="" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_expire_date; ?></td>
      <td><select name="cc_expire_date_month">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        /
        <select name="cc_expire_date_year">
          <?php foreach ($year_expire as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select></td>
    </tr>

    <tr>
      <td><?php echo $entry_cc_cvv2; ?></td>
      <td><input type="text" name="cc_cvv2" value="" size="4" /></td>
    </tr>
	<tr id="cvcMiss">
      <td><?php echo $entry_cc_cvv2Miss; ?></td>
      <td><input type="radio" name="cc_cvv2_miss" value="2" /> CVC is illegible.<br />
		<input type="radio" name="cc_cvv2_miss" value="9" /> CVC is not on the card</td>
    </tr>
	<tr>
      <td><img src="image/data/paymentexpress.png" alt="DPS - Payment Express" /></td>
     
    </tr>
  </table>
</div>
<div class="buttons">
  <div class="right"><input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" /></div>
</div>


<script type="text/javascript">

$("input[type=text][name=cc_cvv2]").focus(function () {
         $('#cvcMiss').hide();
    });
	
$("input[type=text][name=cc_cvv2]").focusout(function () {
if ($("input[type=text][name=cc_cvv2]").val()==""){
	$('#cvcMiss').show();
	}
});
$('#button-confirm').bind('click', function() {
	
	if ( $("input[type=text][name=cc_cvv2]").val()=="" && (!$("input[type=radio][name=cc_cvv2_miss]").is(':checked')) ){
		alert("Please enter CVV2 value or Choose one of radio box");
	} else {
		$.ajax({
			url: 'index.php?route=payment/dps_pxpost/send',
			type: 'post',
			data: $('#payment :input'),
			dataType: 'json',		
			beforeSend: function() {
				$('#button-confirm').attr('disabled', true);
				$('#payment').before('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
			complete: function() {
				$('#button-confirm').attr('disabled', false);
				$('.attention').remove();
			
			},				
			success: function(json) {
				
				if (json['error']) {
					alert(json['error']);
				}
				
				if (json['success']) {
					location = json['success'];
				}
			},
			 error: function(json,  ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + json.statusText + "\r\n" + json.responseText);
			}
		});
	}
});
</script>