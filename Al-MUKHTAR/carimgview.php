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
$carimg_view = new carimg_view();

// Run the page
$carimg_view->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$carimg_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if (!$carimg->isExport()) { ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "view";
var fcarimgview = currentForm = new ew.Form("fcarimgview", "view");

// Form_CustomValidate event
fcarimgview.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fcarimgview.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if (!$carimg->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $carimg_view->ExportOptions->render("body") ?>
<?php $carimg_view->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $carimg_view->showPageHeader(); ?>
<?php
$carimg_view->showMessage();
?>
<form name="fcarimgview" id="fcarimgview" class="form-inline ew-form ew-view-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($carimg_view->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $carimg_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="carimg">
<input type="hidden" name="modal" value="<?php echo (int)$carimg_view->IsModal ?>">
<table class="table ew-view-table">
<?php if ($carimg->id->Visible) { // id ?>
	<tr id="r_id">
		<td class="<?php echo $carimg_view->TableLeftColumnClass ?>"><span id="elh_carimg_id"><?php echo $carimg->id->caption() ?></span></td>
		<td data-name="id"<?php echo $carimg->id->cellAttributes() ?>>
<span id="el_carimg_id">
<span<?php echo $carimg->id->viewAttributes() ?>>
<?php echo $carimg->id->getViewValue() ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($carimg->num_slide->Visible) { // num_slide ?>
	<tr id="r_num_slide">
		<td class="<?php echo $carimg_view->TableLeftColumnClass ?>"><span id="elh_carimg_num_slide"><?php echo $carimg->num_slide->caption() ?></span></td>
		<td data-name="num_slide"<?php echo $carimg->num_slide->cellAttributes() ?>>
<span id="el_carimg_num_slide">
<span<?php echo $carimg->num_slide->viewAttributes() ?>>
<?php echo $carimg->num_slide->getViewValue() ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($carimg->slide_src->Visible) { // slide_src ?>
	<tr id="r_slide_src">
		<td class="<?php echo $carimg_view->TableLeftColumnClass ?>"><span id="elh_carimg_slide_src"><?php echo $carimg->slide_src->caption() ?></span></td>
		<td data-name="slide_src"<?php echo $carimg->slide_src->cellAttributes() ?>>
<span id="el_carimg_slide_src">
<span>
<?php echo GetFileViewTag($carimg->slide_src, $carimg->slide_src->getViewValue()) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php
$carimg_view->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<?php if (!$carimg->isExport()) { ?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$carimg_view->terminate();
?>