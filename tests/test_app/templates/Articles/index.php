<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\ORM\ResultSet $articles
 * @var array $viewFields
 */
?>
<h1>Articles</h1>

<div class="search-forms">
    <h3>PlumSearch Filter</h3>

    <h3>CakeDC/SearchFilter</h3>
    <?= $this->Form->create(null, ['type' => 'get']) ?>
    <?= $this->element('CakeDC/SearchFilter.Search/v_search', ['viewFields' => $viewFields]) ?>
    <?= $this->Form->submit('Search') ?>
    <?= $this->Form->end() ?>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articles as $article): ?>
        <tr>
            <td><?= $this->Number->format($article->id) ?></td>
            <td><?= h($article->title) ?></td>
            <td><?= h($article->author) ?></td>
            <td><?= h($article->created) ?></td>
            <td>
                <?= $this->Html->link('View', ['action' => 'view', $article->id]) ?>
                <?= $this->Html->link('Edit', ['action' => 'edit', $article->id]) ?>
                <?= $this->Form->postLink('Delete', ['action' => 'delete', $article->id], ['confirm' => 'Are you sure?']) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="paginator">
    <ul class="pagination">
        <?= $this->Paginator->first('<< First') ?>
        <?= $this->Paginator->prev('< Previous') ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next('Next >') ?>
        <?= $this->Paginator->last('Last >>') ?>
    </ul>
    <p><?= $this->Paginator->counter('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total') ?></p>
</div>

<script>
    window._search.createMyApp(window._search.rootElemId)
</script>