<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Image $image
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Image'), ['action' => 'edit', $image->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Image'), ['action' => 'delete', $image->id], ['confirm' => __('Are you sure you want to delete # {0}?', $image->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Images'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Image'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Events'), ['controller' => 'Events', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Event'), ['controller' => 'Events', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="images view large-9 medium-8 columns content">
    <h3><?= h($image->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Event') ?></th>
            <td><?= $image->has('event') ? $this->Html->link($image->event->event_id, ['controller' => 'Events', 'action' => 'view', $image->event->event_id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($image->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rarity') ?></th>
            <td><?= $this->Number->format($image->rarity) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Image') ?></h4>
        <?= $this->Text->autoParagraph(h($image->image)); ?>
    </div>
</div>
