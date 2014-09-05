</div>
<div class="yoast-ga-banners">
	<?php foreach ( $banners as $item ): ?>
		<p><a href="<?php echo $item['url']; ?>" target="_blank">
				<img src="<?php echo $item['banner']; ?>" alt="<?php echo $item['title']; ?>" class="yoast-banner" border="0" width="250" />
			</a></p>
	<?php endforeach; ?>
</div>
</div>