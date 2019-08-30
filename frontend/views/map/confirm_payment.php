<?php

/* @var $payment_button string */

?>

<div class="confirm-payment hidden">
    <?= $payment_button ?>
</div>

<!-- script activates payment button -->
<script>
    document.querySelector(".confirm-payment form input[type=image]").click();
</script>