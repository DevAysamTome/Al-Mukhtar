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
$modal_view = new modal_view();

// Run the page
$modal_view->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$modal_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if (!$modal->isExport()) { ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "view";
var fmodalview = currentForm = new ew.Form("fmodalview", "view");

// Form_CustomValidate event
fmodalview.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fmodalview.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if (!$modal->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $modal_view->ExportOptions->render("body") ?>
<?php $modal_view->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $modal_view->showPageHeader(); ?>
<?php
$modal_view->showMessage();
?>
<form name="fmodalview" id="fmodalview" class="form-inline ew-form ew-view-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($modal_view->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $modal_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="modal">
<input type="hidden" name="modal" value="<?php echo (int)$modal_view->IsModal ?>">
<table class="table ew-view-table">
<?php if ($modal->id->Visible) { // id ?>
	<tr id="r_id">
		<td class="<?php echo $modal_view->TableLeftColumnClass ?>"><span id="elh_modal_id"><?php echo $modal->id->caption() ?></span></td>
		<td data-name="id"<?php echo $modal->id->cellAttributes() ?>>
<span id="el_modal_id">
<span<?php echo $modal->id->viewAttributes() ?>>
<?php echo $modal->id->getViewValue() ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($modal->num_slide->Visible) { // num_slide ?>
	<tr id="r_num_slide">
		<td class="<?php echo $modal_view->TableLeftColumnClass ?>"><span id="elh_modal_num_slide"><?php echo $modal->num_slide->caption() ?></span></td>
		<td data-name="num_slide"<?php echo $modal->num_slide->cellAttributes() ?>>
<span id="el_modal_num_slide">
<span<?php echo $modal->num_slide->viewAttributes() ?>>
<?php echo $modal->num_slide->getViewValue() ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($modal->slide_src->Visible) { // slide_src ?>
	<tr id="r_slide_src">
		<td class="<?php echo $modal_view->TableLeftColumnClass ?>"><span id="elh_modal_slide_src"><?php echo $modal->slide_src->caption() ?></span></td>
		<td data-name="slide_src"<?php echo $modal->slide_src->cellAttributes() ?>>
<span id="el_modal_slide_src">
<span<?php echo $modal->slide_src->viewAttributes() ?>>
<?php echo GetFileViewTag($modal->slide_src, $modal->slide_src->getViewValue()) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php
$modal_view->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<?php if (!$modal->isExport()) { ?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$modal_view->terminate();
?>