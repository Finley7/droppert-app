<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="callout small alert" onclick="this.classList.add('hidden');">
    <?= $message ?>
</div>