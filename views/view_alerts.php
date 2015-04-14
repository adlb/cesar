<div ng-app="emptyApp" ng-init="viewedalerts=localStorage.getItem('alerts') || [];">

<?php foreach($obj['alerts'] as $alert) { ?>
<div ng-show="viewedalerts.contains(<?php echo $alert['id'] ?>)">
	<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseAlert<?php echo $alert['id'] ?>" ng-click="viewedalerts.push(<?php echo $alert['id'] ?>);localStorage.setItem('alerts',viewedalerts);">
		<?php t($alert['title']); ?>
	</button>
	<div class="collapse" id="collapseAlert<?php echo $alert['id'] ?>">
		<div class="well">
			<?php echo $alert['htmlContent'] ?>
		</div>
	</div>
</div>
<?php } ?>

</div>
