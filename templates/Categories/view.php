<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Category $category
 */
$this->loadHelper('Authentication.Identity');
$uid = 0;
$role = 0;
if ($this->Identity->isLoggedIn()) {
	$role = $this->Identity->get('role_id');
	$uid = $this->Identity->get('id');
}
?>
<h1><?= h($category->name) ?> Pathways</h1>
<div class="text">
<?= $this->Text->autoParagraph(h($category->description)); ?>
</div>
<div class="row">
<div class="col-md-6">
<?php if (!empty($category->pathways)) : ?>
<?php foreach ($category->pathways as $pathway) : ?>

<div class="card mb-2">
<div class="card-body">

<?php if($pathway->status_id != 2): // is not published? ?>
<?php if($role == 2 || $role == 5): // is curator or admin ?>
<span class="badge badge-warning"><?= $pathway->status->name ?></span>
<h2>
<?= $this->Html->link($pathway->name, ['controller' => 'Pathways', 'action' => 'view', $pathway->id]) ?>
</h2>
<div class="mb-3">
<?= h($pathway->objective) ?>
</div>
<?php endif; // is curator or admin ?>
<?php else: ?>
<h2>
<?= $this->Html->link($pathway->name, ['controller' => 'Pathways', 'action' => 'view', $pathway->id]) ?>
</h2>
<div class="mb-3">
<?= h($pathway->objective) ?>
</div>
<?php endif; // is not published ?>

</div>
</div>

<?php endforeach; ?>
<?php endif; ?>
</div>
</div>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" 
	integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" 
	crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" 
	integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" 
	crossorigin="anonymous"></script>