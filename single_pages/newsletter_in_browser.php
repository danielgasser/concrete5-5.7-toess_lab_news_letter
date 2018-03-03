<?php    defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php
if($controller->get('preview') != '1' && !$controller->checkKey($controller->get('uEmail'), $controller->get('template'), $controller->get('newsletter'), $controller->get('key'))){
	$response = \Redirect::to('/');
	$response->send();
	exit;
}

?>
<script>
	$(document).ready(function () {
		$.ajax({
			type: 'GET',
			url: '<?php  echo $controller->action('get_newsletter')?>',
			data: {
				t_id: '<?php  echo $controller->get('template') ?>',
				n_id: '<?php  echo $controller->get('newsletter') ?>',
				uEmail: '<?php  echo $controller->get('uEmail') ?>'
			},
			success: function (data) {
				if (data.hasOwnProperty('<!DOCTYPE html>')){
					window.location.href = '/';
				}
				var dats = $.parseJSON(data);
				console.log(dats);
				$('#container').html(dats.tpl);
			}
		});
	});
</script>
<div id="container"></div>

