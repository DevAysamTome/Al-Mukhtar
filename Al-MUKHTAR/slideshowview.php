<?php
namespace PHPMaker2019\project2;

// Session
if (session_status() !== PHP_SESSION_ACTIVE)
	session_start(); // Init session data

// Output buffering
ob_start(); 

// Autoload
include_once "autoload.php";
?>
<?php

// Write header
WriteHeader(FALSE);

// Create page object
$slideshow_view = new slideshow_view();

// Run the page
$slideshow_view->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$slideshow_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if (!$slideshow->isExport()) { ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "view";
var fslideshowview = currentForm = new ew.Form("fslideshowview", "view");

// Form_CustomValidate event
fslideshowview.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fslideshowview.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if (!$slideshow->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $slideshow_view->ExportOptions->render("body") ?>
<?php $slideshow_view->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $slideshow_view->showPageHeader(); ?>
<?php
$slideshow_view->showMessage();
?>
<form name="fslideshowview" id="fslideshowview" class="form-inline ew-form ew-view-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($slideshow_view->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $slideshow_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="slideshow">
<input type="hidden" name="modal" value="<?php echo (int)$slideshow_view->IsModal ?>">
<table class="table ew-view-table">
<?php if ($slideshow->id->Visible) { // id ?>
	<tr id="r_id">
		<td class="<?php echo $slideshow_view->TableLeftColumnClass ?>"><span id="elh_slideshow_id"><?php echo $slideshow->id->caption() ?></span></td>
		<td data-name="id"<?php echo $slideshow->id->cellAttributes() ?>>
<span id="el_slideshow_id">
<span<?php echo $slideshow->id->viewAttributes() ?>>
<?php echo $slideshow->id->getViewValue() ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($slideshow->img_link->Visible) { // img_link ?>
	<tr id="r_img_link">
		<td class="<?php echo $slideshow_view->TableLeftColumnClass ?>"><span id="elh_slideshow_img_link"><?php echo $slideshow->img_link->caption() ?></span></td>
		<td data-name="img_link"<?php echo $slideshow->img_link->cellAttributes() ?>>
<span id="el_slideshow_img_link">
<span>
<?php echo GetFileViewTag($slideshow->img_link, $slideshow->img_link->getViewValue()) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php
$slideshow_view->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<?php if (!$slideshow->isExport()) { ?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$slideshow_view->terminate();
?>