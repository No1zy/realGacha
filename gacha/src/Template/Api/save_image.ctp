<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Image[]|\Cake\Collection\CollectionInterface $images
  */
?>

<?= $this->Html->script('script'); ?>
<?= $this->Form->create("/api/save_Image",['enctype' => 'multipart/form-data',]); ?>
<div id='images'>
<?php for ($i = 1; $i <= 4 ; ++$i): ?>
 <div class="content<?= $i;?>">
 カード名<input type="input" name="image_names[]" id="image-name<?= $i;?>">
 レアリティ<select name="rarities[]">
  <option value="1">ノーマル</option>
  <option value="2">レア</option>
  <option value="3">スーパーレア</option>
  <option value="4">ウルトラレア</option>
 </select>
 <input type="file" name="images[]" value="">
 </div>
 <?php endfor;?>
</div>
<?= $this->Form->hidden('event_id', ['value' => h($event_id)]); ?>
<?= $this->Form->submit('登録'); ?>
<?= $this->Form->end(); ?>