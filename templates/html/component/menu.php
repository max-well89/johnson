<div class="container-fluid">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
	</div>
	<div class="collapse navbar-collapse">
		<ul class="nav navbar-nav">
			<?php foreach ($menu as $name => $url) : ?>
				<?php if (is_array($url)) : ?>
				<li class="dropdovn">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $name ?> <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
					<?php foreach ($url as $subname => $suburl) : ?>
						<li<?php echo $url == $current ? ' class="active"' : ''; ?>><a href="<?php echo $suburl; ?>"><?php echo $subname ?></a></li>
					<?php endforeach; ?>
					</ul>
				</li>
				<?php else: ?>
				<li<?php echo $url == $current ? ' class="active"' : ''; ?>><a href="<?php echo $url; ?>"><?php echo $name ?></a></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li><a class="navbar-right" href="/logout"><span class="glyphicon glyphicon-log-out"></span> <?php echo Context::getInstance()->translate('log_out'); ?></a></li>
		</ul>
	</div>
</div>
