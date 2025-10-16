<!DOCTYPE HTML>
<html lang="en">
<title><?=$site_title?></title>
<meta name="descrition" content="<?=$site_desc?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?=$config->baseURL?>public/images/favicon.png" />
<?php
if (@$styles) {
	foreach($styles as $file) {
		echo '<link rel="stylesheet" type="text/css" href="'.$file.'?r='.time().'"/>';
	}
}

if (@$scripts) {
	foreach($scripts as $file) {
		echo '<script type="text/javascript" src="'.$file.'?r='.time().'"/></script>';
	}
}
?>
</html>
<body>
	<div class="background"></div>
	<div class="backdrop"></div>
	<div class="card-container">
		<div class="card-header">
			<div class="logo">
				<img src="<?php echo $config->baseURL . 'public/images/' . $settingWeb->logo_register ?>?r=<?=time()?>">
			</div>
			<h3><?=$title?></h3>
			<?php if (@$desc) {
				echo '<p>' . $desc . '</p>';
			}?>
		</div>
		