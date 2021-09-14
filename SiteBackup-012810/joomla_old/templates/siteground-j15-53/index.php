<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
JPlugin::loadLanguage( 'tpl_SG1' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/template.css" type="text/css" />

</head>
<body id="page_bg">
		<?php if($this->countModules('user4') and JRequest::getCmd('layout') != 'form') : ?>
			<div id="top_with_search">
				<div class="pathway">
					<jdoc:include type="module" name="breadcrumbs" />
					<jdoc:include type="modules" name="user4" />	
				</div>
			</div>
		<?php else: ?>
			<div id="top_without_search">
				<div class="pathway">
					<jdoc:include type="module" name="breadcrumbs" />
				</div>
			</div>
		<?php endif; ?>
		<div id="headertop"></div>
		<div id="header">
			<div id="image">
				<div id="logo">
					<a href="index.php"><?php echo $mainframe->getCfg('sitename') ;?></a>
				</div>
			</div>
		</div>
		<div id="headerbottom"></div>
	
	<div class="center">		
		<div id="wrapper">
			<div id="content">
				<?php if($this->countModules('left') and JRequest::getCmd('layout') != 'form') : ?>
				<div id="leftcolumn">	
					<jdoc:include type="modules" name="left" style="rounded" />
					
				</div>
				<?php endif; ?>
				
				<?php if($this->countModules('right') and JRequest::getCmd('layout') != 'form') : ?>
				<div id="maincolumn">
				<?php else: ?>
				<div id="maincolumn_full">
				<?php endif; ?>
					<div class="nopad">				
						<jdoc:include type="message" />
						<?php if($this->params->get('showComponent')) : ?>
							<jdoc:include type="component" />
						<?php endif; ?>
					</div>
				</div>
				<?php if($this->countModules('right') and JRequest::getCmd('layout') != 'form') : ?>
				<div id="rightcolumn" style="float:right;">
					<jdoc:include type="modules" name="right" style="rounded" />								
				</div>
				<?php endif; ?>
				<div class="clr"></div>
			</div>
		</div>
		<div id="content_bottom"></div>				
	<jdoc:include type="modules" name="debug" />
	</div>
	<div id="validation">
		<div id="footer">
			<div id="sgf">
				<?php $sg = ''; include "templates.php"; ?>
			</div>
		</div>	
		<div class="valid_css"><a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="<?php echo $this->baseurl ?>/templates/siteground-j15-53/images/css.jpg" alt="" title="" /></a></div>
		<div class="valid_xhtml"><a href="http://validator.w3.org/check/referer"><img src="<?php echo $this->baseurl ?>/templates/siteground-j15-53/images/xhtml.jpg" alt="" title="" /></a></div>
	</div>
	
</body>
</html>