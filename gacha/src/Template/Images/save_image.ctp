<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Image[]|\Cake\Collection\CollectionInterface $images
  */
?>
<?= $this->Form->create("/api/saveImage"); ?>
<?= $this->Form->file('images'); ?>
<?= $this->Form->submit(); ?>