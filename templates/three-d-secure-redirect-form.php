<?php

if (
	! isset( $accessControlServerUrl )
	|| ! isset( $payerAuthenticationRequest )
	|| ! isset( $termUrl )
) {
	die;
}

$form_id = 'converge-three-d-secure-redirect';
?>

<form id="<?php echo $form_id; ?>" action="<?php echo $accessControlServerUrl; ?>" method="post">
    <input name="PaReq" type="hidden" value="<?php echo $payerAuthenticationRequest; ?>"/>
    <input name="TermUrl" type="hidden" value="<?php echo $termUrl; ?>"/>
</form>

<script type="text/javascript">
    document.getElementById('<?php echo $form_id; ?>').submit();
</script>