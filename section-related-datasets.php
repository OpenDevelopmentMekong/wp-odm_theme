<?php
$datasets = opendev_get_related_datasets();
$groupby = 'groups';
if(!empty($datasets)) {
	$grouped = array();
	foreach($datasets as $dataset) {
		if(!empty($dataset[$groupby])) {
			foreach($dataset[$groupby] as $group) {
				if(!$grouped[$group['id']]) {
					$grouped[$group['id']] = $group;
					$grouped[$group['id']]['datasets'] = array();
				}
				$grouped[$group['id']]['datasets'][] = $dataset;
			}
		} else {
			if(!$grouped['_other'])
				$grouped['_other'] = array(
					'display_name' => __('Other', 'opendev'),
					'datasets' => array()
				);
			$grouped['_other']['datasets'][] = $dataset;
		}
	}
}
?>
<?php if(isset($grouped) && !empty($grouped)) : ?>
	<section id="related-datasets" class="row">
		<div class="container">
			<div class="box-section twelve columns">
				<div class="box-title">
					<h2><?php _e('Related resources', 'opendev'); ?></h2>
				</div>
				<div class="box-items">
					<?php
					foreach($grouped as $group) :
						if(!empty($group['datasets'])) :
							?>
							<div class="group-item box-item">
								<?php if(count($grouped) > 1) : ?>
									<h3><?php echo isset($group['title']) ? $group['title'] : $group['display_name']; ?></h3>
								<?php endif; ?>
								<ul class="dataset-list">
									<?php foreach($group['datasets'] as $dataset) : ?>
										<li class="dataset-item">
											<h4>
												<a href="<?php echo $dataset['']; ?>"><?php echo $dataset['title']; ?></a>
											</h4>
											<?php if(isset($dataset['description'])) : ?>
												<p class="dataset-description"><?php echo $dataset['description']; ?></p>
											<?php elseif(isset($dataset['notes'])) : ?>
												<p class="dataset-description"><?php echo $dataset['notes']; ?></p>
											<?php endif; ?>
											<ul class="dataset-resources clearfix">
												<?php
												$i = 0;
												foreach($dataset['resources'] as $resource) :
													if($i > 3)
														continue;
													$i++;
													?>
													<li class="resource-item">
														<a href="<?php echo $resource['url']; ?>" target="_blank" rel="external">
															<?php echo $resource['description']; ?>
															<?php if($resource['format']) : ?>
																<span class="format"><?php echo $resource['format']; ?></span>
															<?php endif; ?>
														</a>
													</li>
												<?php endforeach; ?>
											</ul>
											<p class="read-more"><a href="<?php echo $dataset['']; ?>"><?php _e('Read more...', 'opendev'); ?></a></p>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php
						endif;
					endforeach;
					?>
				</div>
				<a class="toggle-resources"><?php _e('Show resources details', 'opendev'); ?></a>
				<script type="text/javascript">
					jQuery(document).ready(function($) {

						$('#related-datasets').addClass('collapsed');
						$('.dataset-resources').hide();

						var viewingAll = false;

						$('.toggle-resources').on('click', function() {
							toggle();
							if(viewingAll) {
								$(this).text('<?php _e("Hide resources details", "opendev"); ?>');
							} else {
								$(this).text('<?php _e("Show resources details", "opendev"); ?>');
							}
						});

						function toggle(node) {

							node = node || false;

							if(!node) {
								if(viewingAll) {
									$('.dataset-resources').hide();
									$('#related-datasets').addClass('collapsed');
									viewingAll = false;
								} else {
									$('.dataset-resources').show();
									$('#related-datasets').removeClass('collapsed');
									viewingAll = true;
								}
							} else {

							}

						}
					});
				</script>
			</div>
		</div>
	</section>
<?php endif; ?>