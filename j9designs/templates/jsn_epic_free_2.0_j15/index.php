<?php
/**
	* @copyright Copyright (C) 2006 - 2008 JoomlaShine.com
	* @author JoomlaShine.com
	* @license Creative Commons Attribution-Share Alike 3.0 Unported License
	* Please visit http://www.joomlashine.com for more information
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );
define( 'YOURBASEPATH', dirname(__FILE__) );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- Joomla! template by JoomlaShine.com - JSN Epic PRO 2.0 for Joomla! 1.5.x -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language ?>" dir="<?php echo $this->direction; ?>">
<head>
<jdoc:include type="head" />
<?php
	require( YOURBASEPATH.DS."php/jsn_utils.php");
	/****************************************************************/
	/* TEMPLATE PARAMETERS */

	/* Overal template width specified in pixels (for fixed width) or percentage (for fluid width). */
	$template_width = $this->params->get("templateWidth", "960px");

	/* Path to logo image starting from the Joomla! root folder (! without preceding slash !). */
	$logo_path = $this->params->get("logoPath", "templates/jsn_epic_pro/images/logo.png");

	/* Logo width specified in pixels. */
	$logo_width = $this->params->get("logoWidth", "250px");

	/* Logo height specified in pixels. */
	$logo_height = $this->params->get("logoHeight", "75px");

	/* URL where logo image should link to (! without preceding slash !). */
	$logo_link = $this->params->get("logoLink", "");

	/* Definition whether to enable PNG fix feature for IE6 or not.
	   This parameter should be turned off only when there are incompatibility issues. */
	$enable_pngfix = ($this->params->get("enablePNGfix", 1) == 1)?"yes":"no"; // yes | no


	/****************************************************************/

	/* Private use only */
	$template_path = $this->baseurl."/templates/".$this->template;
	$has_right = $this->countModules('right');
	$has_left = $this->countModules('left');

	$logo_image = $this->baseurl."/".$logo_path;
	$logo_text = $mainframe->getCfg('sitename');

	// Parameter filter
	$template_width = intval($template_width);
	$template_width .= ($template_width < 100)?"%":"px";
	$logo_width = intval($logo_width)."px";
	$logo_height = intval($logo_height)."px";
	$logo_link = ($logo_link != "")?$this->baseurl."/".$logo_link:"";
?>
<link rel="shortcut icon" href="<?php echo $this->baseurl; ?>/images/favicon.ico" />
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/general.css" type="text/css" />
<link href="<?php echo $template_path; ?>/css/template.css" rel="stylesheet" type="text/css" media="screen" />
<?php

	// Inline CSS styles for template layout
	echo '<style type="text/css">';

	// Setup template width parameter
	echo '
	#jsn-page {
		width: '.$template_width.';
	}
	';

	// Adjustment of header area according to the logo height parameter
	echo '
	#jsn-header {
		height: '.$logo_height.';
	}
	';

	// Setup width of content area
	$tw = 100;
	if ($has_left) {
		$tw -= 23;
		echo '
	#jsn-content_inner1 {
		background: transparent url('.$template_path.'/images/leftside-bg-full.png) repeat-y 23% top;
		padding: 0;
	}
	#jsn-maincontent_inner {
		padding-left: 0;
	}
	';
	}

	if ($has_right) {
		$tw -= 23;
		echo '
	#jsn-content_inner2 {
		background: transparent url('.$template_path.'/images/rightside-bg-full.png) repeat-y 77% top;
		padding: 0;
	}
	#jsn-maincontent_inner {
		padding-right: 0;
	}
	';
	}

	echo '
	#jsn-leftsidecontent {
		float: left;
		width: 23%;
	}
	#jsn-maincontent {
		float: left;
		width: '.($tw-0.15).'%;
	}
	#jsn-rightsidecontent {
		float: right;
		width: 23%;
	}
	';
	
	echo '</style>';

	// Setup core javascript library
	echo '<script type="text/javascript" src="'.$template_path.'/js/jsn_script.js"></script>';
	
?>
<!--[if lte IE 6]>
<link href="<?php echo $template_path; ?>/css/jsn_fixie6.css" rel="stylesheet" type="text/css" />
<?php if($enable_pngfix == "yes") {?>
<style type="text/css">
	img {  behavior: url(<?php echo $template_path;?>/js/iepngfix.htc); }
</style>
<?php } ?>
<![endif]-->
<!--[if lte IE 7]>
<script type="text/javascript" src="<?php echo $template_path; ?>/js/suckerfish.js"></script>
<![endif]-->
<!--[if IE 7]>
<link href="<?php echo $template_path; ?>/css/jsn_fixie7.css" rel="stylesheet" type="text/css" />
<![endif]-->
</head>
<body id="jsn-master">
	<div id="jsn-page">
		<div id="jsn-header">
			<div id="jsn-logo"><?php if ($logo_link != "") echo '<a href="'.$logo_link.'" title="'.$logo_text.'">'; ?><img src="<?php echo $logo_image;?>" width="<?php echo intval($logo_width); ?>" height="<?php echo intval($logo_height); ?>" alt="<?php echo $logo_text; ?>" /><?php if ($logo_link != "") echo '</a>'; ?></div>
			<?php if ($this->countModules( 'top' ) > 0) { ?>
			<div id="jsn-ptop"><jdoc:include type="modules" name="top" style="xhtml" /></div>
			<?php } ?>
		</div>
		<div id="jsn-body">
			<?php if (jsnCountPositions($this, array('toolbar', 'inset'))) { ?>
			<div id="jsn-mainmenu">
				<?php if ($this->countModules( 'toolbar' ) > 0) { ?>
				<div id="jsn-ptoolbar"><jdoc:include type="modules" name="toolbar" style="xhtml" /></div>
				<?php } ?>
				<?php if ($this->countModules( 'inset' ) > 0) { ?>
				<div id="jsn-pinset"><jdoc:include type="modules" name="inset" style="xhtml" /></div>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if ($this->countModules( 'header' ) > 0) { ?>
			<div id="jsn-promo">
				<div id="jsn-pheader" class="jsn-column"><jdoc:include type="modules" name="header" style="xhtml" /></div>
			</div>
			<?php } ?>
			<div id="jsn-content"><div id="jsn-content_inner1"><div id="jsn-content_inner2">
				<?php if ($this->countModules( 'left' ) > 0) { ?>
				<div id="jsn-leftsidecontent" class="jsn-column">
					<div id="jsn-pleft"><jdoc:include type="modules" name="left" style="rounded" /></div>
				</div>
				<?php } ?>
				<div id="jsn-maincontent" class="jsn-column"><div id="jsn-maincontent_inner">
					<div id="jsn-pathway"><jdoc:include type="module" name="breadcrumbs" style="raw" /></div>
					<?php
						$positionCount = jsnCountPositions($this, array('user1', 'user2'));
						if($positionCount){
							$grid_suffix = "_grid".$positionCount;
					?>
					<div id="jsn-usermodules1"><div id="jsn-usermodules1_inner<?php echo $grid_suffix; ?>">
						<?php if ($this->countModules( 'user1' ) > 0) { ?>
						<div id="jsn-puser1<?php echo $grid_suffix; ?>" class="jsn-column"><div id="jsn-puser1"><jdoc:include type="modules" name="user1" style="xhtml" /></div></div>
						<?php } ?>
						<?php if ($this->countModules( 'user2' ) > 0) { ?>
						<div id="jsn-puser2<?php echo $grid_suffix; ?>" class="jsn-column"><div id="jsn-puser2"><jdoc:include type="modules" name="user2" style="xhtml" /></div></div>
						<?php } ?>
						<div class="clearbreak"></div>
					</div></div>
					<?php } ?>
					<div id="jsn-mainbody">
						<jdoc:include type="message" />
						<jdoc:include type="component" />
					</div>
					<?php
					$positionCount = jsnCountPositions($this, array('user3', 'user4'));
					if($positionCount){
						$grid_suffix = "_grid".$positionCount;
					?>
					<div id="jsn-usermodules2"><div id="jsn-usermodules2_inner<?php echo $grid_suffix; ?>">
						<?php if ($this->countModules( 'user3' ) > 0) { ?>
						<div id="jsn-puser3<?php echo $grid_suffix; ?>" class="jsn-column"><div id="jsn-puser3"><jdoc:include type="modules" name="user3" style="xhtml" /></div></div>
						<?php } ?>
						<?php if ($this->countModules( 'user4' ) > 0) { ?>
						<div id="jsn-puser4<?php echo $grid_suffix; ?>" class="jsn-column"><div id="jsn-puser4"><jdoc:include type="modules" name="user4" style="xhtml" /></div></div>
						<?php } ?>
						<div class="clearbreak"></div>
					</div></div>
					<?php } ?>
					<?php if ($this->countModules( 'banner' ) > 0) { ?>
					<div id="jsn-banner"><jdoc:include type="modules" name="banner" style="xhtml" /></div>
					<?php } ?>
				</div></div>
				<?php if ($this->countModules( 'right' ) > 0) { ?>
				<div id="jsn-rightsidecontent" class="jsn-column">
					<div id="jsn-pright"><jdoc:include type="modules" name="right" style="rounded" /></div>
				</div>
				<?php } ?>
				<div class="clearbreak"></div>
			</div></div></div>
			<?php
			$positionCount = jsnCountPositions($this, array('user5', 'user6', 'user7'));
			if($positionCount){
				$grid_suffix = "_grid".$positionCount;
			?>
			<div id="jsn-usermodules3"><div id="jsn-usermodules3_inner<?php echo $grid_suffix; ?>">
				<?php if ($this->countModules( 'user5' ) > 0) { ?>
				<div id="jsn-puser5<?php echo $grid_suffix; ?>" class="jsn-column"><div id="jsn-puser5"><jdoc:include type="modules" name="user5" style="xhtml" /></div></div>
				<?php } ?>
				<?php if ($this->countModules( 'user6' ) > 0) { ?>
				<div id="jsn-puser6<?php echo $grid_suffix; ?>" class="jsn-column"><div id="jsn-puser6"><jdoc:include type="modules" name="user6" style="xhtml" /></div></div>
				<?php } ?>
				<?php if ($this->countModules( 'user7' ) > 0) { ?>
				<div id="jsn-puser7<?php echo $grid_suffix; ?>" class="jsn-column"><div id="jsn-puser7"><jdoc:include type="modules" name="user7" style="xhtml" /></div></div>
				<?php } ?>
				<div class="clearbreak"></div>
			</div></div>
			<?php } ?>
		</div>
		<?php
			$positionCount = jsnCountPositions($this, array('footer', 'bottom'));
			if($positionCount){
				$grid_suffix = "_grid".$positionCount;
		?>
		<div id="jsn-footer">
			<?php if ($this->countModules( 'footer' ) > 0) { ?>
			<div id="jsn-pfooter<?php echo $grid_suffix; ?>" class="jsn-column"><div id="jsn-pfooter"><jdoc:include type="modules" name="footer" style="xhtml" /></div></div>
			<?php } ?>
			<?php if ($this->countModules( 'bottom' ) > 0) { ?>
			<div id="jsn-pbottom<?php echo $grid_suffix; ?>" class="jsn-column"><div id="jsn-pbottom"><jdoc:include type="modules" name="bottom" style="xhtml" /></div></div>
			<?php } ?>
			<div class="clearbreak"></div>
		</div>
		<?php } ?>
	</div>
	<?php
		/*** REMOVAL OR MODIFICATION COPYRIGHT TEXT BELLOW IS VIOLATION OF JOOMLASHINE.COM TERMS & CONDITIONS AND DEPRIVES OF ANY KIND OF SUPPORTS ***/
		$copyright_text = '<div id="jsn-copyright"><a href="http://www.joomlashine.com" title="Joomla 1.5 Templates">Joomla 1.5 Templates</a> by JoomlaShine.com</div>';
		echo $copyright_text;
	?>
	<jdoc:include type="modules" name="debug" style="xhtml" />
	<?php require( YOURBASEPATH.DS."php/jsn_debug.php"); ?>
</body>
</html>