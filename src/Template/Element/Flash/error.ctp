<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="callout small alert" onclick="this.classList.add('hidden');">
    <p><i class="fa fa-exclamation-triangle"></i> <?= $message ?></p>
</div>