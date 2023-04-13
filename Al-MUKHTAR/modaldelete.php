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
$modal_delete = new modal_delete();

// Run the page
$modal_delete->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$modal_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "delete";
var fmodaldelete = currentForm = new ew.Form("fmodaldelete", "delete");

// Form_CustomValidate event
fmodaldelete.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fmodaldelete.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php $modal_delete->showPageHeader(); ?>
<?php
$modal_delete->showMessage();
?>
<form name="fmodaldelete" id="fmodaldelete" class="form-inline ew-form ew-delete-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($modal_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $modal_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="modal">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($modal_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?php if (IsResponsiveLayout()) { ?>table-responsive <?php } ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
	<thead>
	<tr class="ew-table-header">
<?php if ($modal->id->Visible) { // id ?>
		<th class="<?php echo $modal->id->headerCellClass() ?>"><span id="elh_modal_id" class="modal_id"><?php echo $modal->id->caption() ?></span></th>
<?php } ?>
<?php if ($modal->num_slide->Visible) { // num_slide ?>
		<th class="<?php echo $modal->num_slide->headerCellClass() ?>"><span id="elh_modal_num_slide" class="modal_num_slide"><?php echo $modal->num_slide->caption() ?></span></th>
<?php } ?>
<?php if ($modal->slide_src->Visible) { // slide_src ?>
		<th class="<?php echo $modal->slide_src->headerCellClass() ?>"><span id="elh_modal_slide_src" class="modal_slide_src"><?php echo $modal->slide_src->caption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$modal_delete->RecCnt = 0;
$i = 0;
while (!$modal_delete->Recordset->EOF) {
	$modal_delete->RecCnt++;
	$modal_delete->RowCnt++;

	// Set row properties
	$modal->resetAttributes();
	$modal->RowType = ROWTYPE_VIEW; // View

	// Get the field contents
	$modal_delete->loadRowValues($modal_delete->Recordset);

	// Render row
	$modal_delete->renderRow();
?>
	<tr<?php echo $modal->rowAttributes() ?>>
<?php if ($modal->id->Visible) { // id ?>
		<td<?php echo $modal->id->cellAttributes() ?>>
<span id="el<?php echo $modal_delete->RowCnt ?>_modal_id" class="modal_id">
<span<?php echo $modal->id->viewAttributes() ?>>
<?php echo $modal->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($modal->num_slide->Visible) { // num_slide ?>
		<td<?php echo $modal->num_slide->cellAttributes() ?>>
<span id="el<?php echo $modal_delete->RowCnt ?>_modal_num_slide" class="modal_num_slide">
<span<?php echo $modal->num_slide->viewAttributes() ?>>
<?php echo $modal->num_slide->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($modal->slide_src->Visible) { // slide_src ?>
		<td<?php echo $modal->slide_src->cellAttributes() ?>>
<span id="el<?php echo $modal_delete->RowCnt ?>_modal_slide_src" class="modal_slide_src">
<span<?php echo $modal->slide_src->viewAttributes() ?>>
<?php echo GetFileViewTag($modal->slide_src, $modal->slide_src->getViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$modal_delete->Recordset->moveNext();
}
$modal_delete->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?php echo $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?php echo $modal_delete->getReturnUrl() ?>"><?php echo $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$modal_delete->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$modal_delete->terminate();
?>