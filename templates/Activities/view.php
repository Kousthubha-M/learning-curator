<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Activity $activity
 */
$this->layout = 'nowrap';
$this->loadHelper('Authentication.Identity');
$uid = 0;
$role = 0;
if ($this->Identity->isLoggedIn()) {
	$role = $this->Identity->get('role_id');
	$uid = $this->Identity->get('id');
}
?>
<div class="container-fluid">
<div class="row justify-content-md-center"
		style="background-color: rgba(<?= $activity->activity_type->color ?>,.2); border:0">

<div class="col-md-12 col-lg-6">
<div class="pad-lg">

	<?php if($role == 2 || $role == 5): ?>
	<div class="btn-group float-right">
	<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $activity->id], ['confirm' => __('Really delete?'), 'class' => 'btn btn-sm btn-light']) ?>
	<?= $this->Html->link(__('Edit'), ['controller' => 'Activities', 'action' => 'edit', $activity->id], ['class' => 'btn btn-light btn-sm']) ?>
	</div>

	<?php if($activity->status_id == 3): ?>
	<span class="badge badge-danger">DEFUNCT</span>
	<?php endif ?>
	<?php if($activity->moderation_flag == 1): ?>
	<span class="badge badge-warning">INVESTIGATE</span>
	<?php endif ?>
	<?php endif; // role check ?>
	
		<div class="row align-items-center">
		<div class="col-3">
		<div class="activity-icon activity-icon-lg" style="background-color: rgba(<?= $activity->activity_type->color ?>,1)">
			<i class="activity-icon activity-icon-lg fas <?= $activity->activity_type->image_path ?>"></i>
		</div>
		</div>
		<div class="col">
		<?php if(in_array($activity->id,$useractivitylist)): // if the user hasn't claimed this, then show them claim form ?>
		<div class="btn btn-dark" data-toggle="tooltip" data-placement="bottom" title="You have completed this activity. Great work!">CLAIMED <i class="fas fa-check-circle"></i></div>
		<?php endif ?>
	
		</div>
	</div>
	<h1 class="my-1">
		<?= $activity->name ?>
	</h1>
	<div class="p-3 rounded-lg" style="background: rgba(255,255,255,.3);">
		<div class="mb-2">
		<span class="badge badge-light" data-toggle="tooltip" data-placement="bottom" title="This activity should take <?= $activity->estimated_time ?> to complete">
			<i class="fas fa-clock"></i>
			<?php echo $this->Html->link($activity->estimated_time, ['controller' => 'Activities', 'action' => 'estimatedtime', $activity->estimated_time], ['class' => 'text-dark']) ?>
		</span> 
			<?php foreach($activity->tags as $tag): ?>
			<a href="/learning-curator/tags/view/<?= h($tag->id) ?>" class="badge badge-light"><?= $tag->name ?></a> 
			<?php endforeach ?>
		</div>
		<?= $activity->description ?>
	</div>
	<?php if(!empty($activity->tags)): ?>
	<?php foreach($activity->tags as $tag): ?>
	<?php if($tag->name == 'Learning System Course'): ?>

	<a target="_blank" 
		rel="noopener" 
		data-toggle="tooltip" 
		data-placement="bottom" 
		title="Enrol in this course in the Learning System"
		href="https://learning.gov.bc.ca/psc/CHIPSPLM_6/EMPLOYEE/ELM/c/LM_OD_EMPLOYEE_FL.LM_FND_LRN_FL.GBL?Page=LM_FND_LRN_RSLT_FL&Action=U&KWRD=<?php echo urlencode($activity->name) ?>" 
		style="background-color: rgba(<?= $activity->activity_type->color ?>,1); color: #000; font-weight: bold;" 
		class="btn btn-block my-3 text-uppercase btn-lg">

			<i class="fas <?= $activity->activity_type->image_path ?>"></i>

			<?= $activity->activity_type->name ?>

	</a>

	<?php elseif($tag->name == 'YouTube'): ?>
	<?php 
		preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $activity->hyperlink, $match);
		$youtube_id = $match[1];
		?>
	<div class="my-3 p-3" style="background-color: rgba(<?= $activity->activity_type->color ?>,1); border-radius: 3px;">
		<iframe width="100%" 
			height="315" 
			src="https://www.youtube-nocookie.com/embed/<?= $youtube_id ?>/" 
			frameborder="0" 
			allow="" 
			allowfullscreen>
		</iframe>
	</div>

	<?php elseif($tag->name == 'Vimeo'): ?>
		<?php 
		$vimeoid = substr(parse_url($activity->hyperlink, PHP_URL_PATH), 1);
		?>
		<iframe src="https://player.vimeo.com/video/<?= $vimeoid ?>" 
			width="100%" 
			height="315" 
			frameborder="0" 
			allow="fullscreen" 
			allowfullscreen>
		</iframe>
	
	<?php endif; // logic check for formatting differently based on tag ?>	

	<?php endforeach; // tags loop ?>

	<?php else: // there are no tags ?>
		<div class="my-3 p-3 text-truncate" style="font-size: 130%">
			<?= $activity->activity_type->name ?>: 
			<a href="<?= h($activity->hyperlink) ?>" target="_blank" rel="noopener">
				<?= h($activity->hyperlink) ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="my-3">
		<a href="#newreport" 
			style="color:#333;" 
			class="btn btn-light float-right" 
			data-toggle="collapse" 
			title="Report this activity for some reason" 
			data-target="#newreport" 
			aria-expanded="false" 
			aria-controls="newreport">
				<i class="fas fa-exclamation-triangle"></i> Report
		</a>	
		<div class="collapse" id="newreport">
		<div class="my-3 p-3 bg-white rounded-lg">
		<?= $this->Form->create(null,['url' => ['controller' => 'reports','action' => 'add'],'class'=>'reportform']) ?>
            <fieldset>
                <legend><?= __('Report this activity') ?></legend>
				<p>Is there something wrong with this activity? Tell us about it!</p>
                <?php
                    echo $this->Form->hidden('activity_id', ['value' => $activity->id]);
                    echo $this->Form->hidden('user_id', ['value' => $uid]);
                    echo $this->Form->textarea('issue',['class' => 'form-control', 'placeholder' => 'Type here ...']);
                ?>
            </fieldset>
            <input type="submit" class="btn btn-dark" value="Submit Report">
            <?= $this->Form->end() ?>
		</div>
		</div>
		<a href="/learning-curator/activities/like/<?= $activity->id ?>" style="color:#333;" class="likingit btn btn-light float-left mr-1" data-toggle="tooltip" data-placement="bottom" title="Like this activity">
			<i class="fas fa-thumbs-up"></i> <span class="lcount"><?= h($activity->recommended) ?> likes</span>
		</a>

		<?php if(!in_array($activity->id,$userbooklist)): // if the user hasn't bookmarked this, then show them claim form ?>
	<?= $this->Form->create(null,['url' => ['controller' => 'activities-bookmarks', 'action' => 'add'], 'class' => 'bookmark form-inline']) ?>
		<?= $this->Form->hidden('activity_id',['value' => $activity->id]) ?>
		<button class="btn btn-light"><i class="fas fa-bookmark"></i> Bookmark</button>
		<?php //$this->Form->button(__('Bookmark'),['class' => 'btn btn-light']) ?>
		<?= $this->Form->end() ?>
	<?php else: ?>
		<span class="btn btn-dark"><i class="fas fa-bookmark"></i> Bookmarked</span>
	<?php endif ?>
		</div>

		
<?php if($role == 2 || $role == 5): ?>
	
	<button class="btn btn-light btn-sm" type="button" data-toggle="collapse" data-target="#assignment<?= $activity->id ?>" aria-expanded="false" aria-controls="assignment<?= $activity->id ?>">
	<i class="fas fa-sitemap"></i> Path Assigment
	  </button>
	  
	<div class="collapse" id="assignment<?= $activity->id ?>">
	<?php foreach($allpathways as $pathway): ?>
	<div class="my-1 p-3" style="background-color: rgba(255,255,255,.3)">
	<button class="btn btn-light btn-sm" type="button" data-toggle="collapse" data-target="#steps<?= $pathway->id ?>" aria-expanded="false" aria-controls="steps<?= $pathway->id ?>">
		Steps
	  </button>
	
	<a href="/learning-curator/pathways/<?= $pathway->slug ?>" target="_blank" rel="noopener"><?= $pathway->name ?></a>
	
	<div class="collapse p-3" id="steps<?= $pathway->id ?>">
	<?= $this->Form->create(null, ['url' => ['controller' => 'activities-steps','action' => 'add', 'class' => '']]) ?>
	<?= $this->Form->control('pathway_id',['type' => 'hidden', 'value' => $pathway->id ]) ?>
	<?= $this->Form->control('activity_id',['type' => 'hidden', 'value' => $activity->id]) ?>
	<?php foreach($pathway->steps as $step): ?>
	<label style="display: inline-block; margin: 0 10px 0 5px;">
	<input id="step_id_<?= $step->id ?>" type="radio" name="step_id" value="<?= $step->id ?>">
	<?= $step->name ?>
	</label>
	<?php endforeach ?>
	<?= $this->Form->button(__('Assign'),['class'=>'btn btn-sm btn-light']) ?>
	<?= $this->Form->end() ?>
	</div>
	</div>
	
	<?php endforeach ?>
	</div> <!-- .collapse -->
	<?php endif ?>

</div>
</div>
</div>
</div>
<div class="container-fluid linear">
<div class="row justify-content-md-center">
<div class="col-md-4">

<h3 class="mt-3"><i class="fas fa-sitemap"></i> Pathways</h3>
<?php foreach($activity->steps as $step): ?>
<?php foreach($step->pathways as $path): ?>
<?php if($path->status_id == 2): ?>
<div class="my-3 p-3 bg-white" style="background-color: rgba(255,255,255,.3)">

	<h4><a href="/learning-curator/pathways/<?= $path->slug ?>/s/<?= $step->id ?>/<?= $step->slug ?>"><?= $path->name ?> - <?= $step->name ?></a></h4>
	<div><?= $step->description ?></div>
	<?php if($role == 2 || $role == 5): ?>
		<?= $this->Form->create(null,['action' => '/learning-curator/activities-steps/delete/' . $step->_joinData->id, 'class' => 'my-3']) ?>
		<?= $this->Form->hidden('id', ['value' => $step->_joinData->id]) ?>
		<?= $this->Form->button(__('Remove from step'),['class' => 'btn btn-sm btn-light']) ?>
		<?= $this->Form->end() ?>
	<?php endif ?>
</div>
<?php else: ?>
<?php if($role == 2 || $role == 5): ?>
<div class="my-3 p-3 bg-white" style="background-color: rgba(255,255,255,.3)">
<span class="badge badge-warning">DRAFT</span>
	<h4><a href="/learning-curator/pathways/<?= $pathway->slug ?>/s/<?= $step->id ?>"><?= $path->name ?> - <?= $step->name ?></a></h4>
	<div><?= $step->description ?></div>
</div>
<?php endif ?>
<?php endif ?>
<?php endforeach ?>
<?php endforeach ?>
</div>
<?php if($role == 2 || $role == 5): ?>
<?php if (!empty($activity->users)) : ?>
<div class="col-md-4">
<h3 class="mt-3"><?= __('Related Users') ?></h3>
<?php foreach ($activity->users as $users) : ?>
<div class="my-3 p-3 bg-white rounded-lg">
<?= $this->Html->link($users->name, ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<?php endif; ?>


<?php if($role == 2 || $role == 5): ?>
<?php if(!empty($activity->reports)): ?>
<div class="col-md-4">
<h3 class="mt-3"><i class="fas fa-exclamation-triangle"></i> Reports</h3>
<?php foreach($activity->reports as $report): ?>
<div class="my-3 p-3 bg-white rounded-lg">
<div><a href="/learning-curator/users/view/<?= $report->user->id ?>"><?= $report->user->name ?></a> says:</div>
<div><?= $report->issue ?></div>
<div class="mt-2" style="font-size: 12px">Added on <?= $report->created ?></div>
<?php if(empty($report->response)): ?>
<div class="my-2 alert alert-warning">No reply yet</div>
<?php else: ?>
<div class="mt-3">
	<a href="/learning-curator/users/view/<?= $report->curator_id ?>">Curator</a> repsonse:
	<div class="my-2 alert alert-success"><?= $report->response ?></div>
</div>
<?php endif ?>
<a href="#curatorresponse<?= $report->id ?>" 
	style="color:#333;" 
	class="btn btn-light" 
	data-toggle="collapse" 
	title="Respond to this report" 
	data-target="#curatorresponse<?= $report->id ?>" 
	aria-expanded="false" 
	aria-controls="curatorresponse<?= $report->id ?>">
		Respond
</a>	
<div class="collapse" id="curatorresponse<?= $report->id ?>">
<?= $this->Form->postLink(__('Delete'), ['controller' => 'Reports', 'action' => 'delete', $report->id], ['confirm' => __('Are you sure you want to delete this report?', $report->id), 'class' => 'float-right btn btn-dark']) ?>
<?= $this->Form->create(null,['url' => ['controller' => 'reports','action' => 'edit', $report->id]]) ?>
<fieldset>
<legend><?= __('Respond') ?></legend>
<?php
echo $this->Form->hidden('id', ['value' => $report->id]);
echo $this->Form->hidden('curator_id', ['value' => $uid]);
echo $this->Form->textarea('response',['class' => 'form-control', 'placeholder' => 'Type here ...']);
?>
</fieldset>
<input type="submit" class="btn btn-dark" value="Submit Response">
<?= $this->Form->end() ?>
</div> <!-- curatorresponse -->

</div>
<?php endforeach ?>
<?php endif ?>
<?php endif ?>
<?php if($role == 2 || $role == 5): ?>
<?php if (!empty($activity->moderator_notes)) : ?>
<h4><?= __('Moderator Notes') ?></h4>
<blockquote>
<?= $this->Text->autoParagraph(h($activity->moderator_notes)); ?>
</blockquote>
<?php endif ?>
</div>
<?php endif; ?>



</div>
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

<script>


$(document).ready(function(){

	$('.bookmark').on('submit', function(e){
		
		e.preventDefault();
		var form = $(this);
		form.children('button').removeClass('btn-light').addClass('btn-dark').html('<span class="fas fa-bookmark"></span> Bookmarked!').tooltip('dispose').attr('title','Good job!');
		
		//$(this).parent('.activity').css('box-shadow','0 0 10px rgba(0,0,0,.4)'); // css('border','2px solid #000')

		var url = form.attr('action');
		$.ajax({
			type: "POST",
			url: '/learning-curator/activities-bookmarks/add',
			data: form.serialize(),
			success: function(data)
			{
				//loadStatus();
			},
			statusCode: 
			{
				403: function() {
					form.after('<div class="alert alert-warning">You must be logged in.</div>');
				}
			}
		});
	});
	$('.claim').on('submit', function(e){
		
		e.preventDefault();
		var form = $(this);
		form.children('button').removeClass('btn-light').addClass('btn-dark').html('CLAIMED! <span class="fas fa-check-circle"></span>').tooltip('dispose').attr('title','Good job!');
		
		$(this).parent('.activity').css('box-shadow','0 0 10px rgba(0,0,0,.4)'); // css('border','2px solid #000')

		var url = form.attr('action');
		$.ajax({
			type: "POST",
			url: '/learning-curator/activities-users/claim',
			data: form.serialize(),
			success: function(data)
			{
				loadStatus();
			},
			statusCode: 
			{
				403: function() {
					form.after('<div class="alert alert-warning">You must be logged in.</div>');
				}
			}
		});
	});

	$('.reportform').on('submit', function(e){
		
		e.preventDefault();
		var form = $(this);
		form.after('<div class="alert alert-success">Thank you for your report. A curator will respond. <a href="/learning-curator/users/reports">View all your reports</a>.').remove();
		
		var url = form.attr('action');
		$.ajax({
			type: "POST",
			url: '/learning-curator/reports/add',
			data: form.serialize(),
			success: function(data)
			{
				
			},
			statusCode: 
			{
				403: function() {
					form.after('<div class="alert alert-warning">You must be logged in.</div>');
				}
			}
		});
	});

	$('[data-toggle="tooltip"]').tooltip();

	$('.likingit').on('click',function(e){
		var url = $(this).attr('href');
		$(this).tooltip('dispose').children('.lcount').html('Liked!');
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: url,
			data: '',
			success: function(data)
			{
			},
			statusCode: 
			{
				403: function() {
					form.after('<div class="alert alert-warning">You must be logged in.</div>');
				}
			}
		});
	});
});

</script>