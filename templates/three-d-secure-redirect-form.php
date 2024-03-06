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

<form id="<?php echo esc_attr( $form_id ); ?>" action="<?php echo esc_url( $accessControlServerUrl ); ?>" method="post">
	<input name="PaReq" type="hidden" value="<?php echo esc_attr( $payerAuthenticationRequest ); ?>"/>
	<input name="TermUrl" type="hidden" value="<?php echo esc_url( $termUrl ); ?>"/>
</form>

<script type="text/javascript">
	document.getElementById('<?php echo esc_html( $form_id ); ?>').submit();
</script>