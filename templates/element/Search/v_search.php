<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div id="ext-search"></div>
<?= $this->element('CakeDC/SearchFilter.Search/v_templates'); ?>
<script >
window._search = window._search || {};
window._search.fields = <?= json_encode($viewFields) ?>;
var values = null;
<?php if (!empty($values)): ?>
    window._search.values = <?= json_encode($values) ?>;
<?php else: ?>
    window._search.values = {};
<?php endif; ?>
</script>

<script src="/search_filter/js/vue3.js"></script>
<script src="/search_filter/js/main.js" type="module"></script>
