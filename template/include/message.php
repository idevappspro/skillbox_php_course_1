<?php
$type = session_flash_get('type');
$message = session_flash_get('message');
?>
<?php if (!empty($type) && !empty($message)): ?>
<div class="alert_<?= $type ?>"><?= $message ?></div>
<?php endif; ?>
