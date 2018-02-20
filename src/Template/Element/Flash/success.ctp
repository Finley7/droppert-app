<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="callout small success" onclick="this.classList.add('hidden')"><?= $message ?></div>
