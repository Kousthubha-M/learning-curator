<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ActivityType $activityType
 */
?>
<div class="row justify-content-md-center">
<div class="col-md-6">
<div class="card card-body">
<a href="/activity-types">All Activity Types</a>
<?= $this->Form->create($activityType) ?>
<fieldset>
<legend><?= __('Edit Activity Type') ?></legend>
<?php
echo $this->Form->control('name', ['class' => 'form-control']);
echo $this->Form->control('description', ['class' => 'form-control']);
echo $this->Form->control('color', ['class' => 'form-control', 'label' => 'Color (RGB value)']);
// echo $this->Form->control('delivery_method');
echo $this->Form->control('image_path', ['class' => 'form-control', 'label' => 'FontAwesome Icon']);
// echo $this->Form->control('featured');
// echo $this->Form->control('createdby');
// echo $this->Form->control('modifiedby');
?>
</fieldset>
<?= $this->Form->button(__('Submit'), ['class' => 'btn btn-block btn-dark mt-3']) ?>
<?= $this->Form->end() ?>
</div>
</div>
</div>
</div>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" 
	integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" 
	crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" 
	integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" 
	crossorigin="anonymous"></script>