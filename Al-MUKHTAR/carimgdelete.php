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
$carimg_delete = new carimg_delete();

// Run the page
$carimg_delete->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$carimg_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "delete";
var fcarimgdelete = currentForm = new ew.Form("fcarimgdelete", "delete");

// Form_CustomValidate event
fcarimgdelete.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fcarimgdelete.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php $carimg_delete->showPageHeader(); ?>
<?php
$carimg_delete->showMessage();
?>
<form name="fcarimgdelete" id="fcarimgdelete" class="form-inline ew-form ew-delete-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($carimg_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $carimg_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="carimg">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($carimg_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?php if (IsResponsiveLayout()) { ?>table-responsive <?php } ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
	<thead>
	<tr class="ew-table-header">
<?php if ($carimg->id->Visible) { // id ?>
		<th class="<?php echo $carimg->id->headerCellClass() ?>"><span id="elh_carimg_id" class="carimg_id"><?php echo $carimg->id->caption() ?></span></th>
<?php } ?>
<?php if ($carimg->num_slide->Visible) { // num_slide ?>
		<th class="<?php echo $carimg->num_slide->headerCellClass() ?>"><span id="elh_carimg_num_slide" class="carimg_num_slide"><?php echo $carimg->num_slide->caption() ?></span></th>
<?php } ?>
<?php if ($carimg->slide_src->Visible) { // slide_src ?>
		<th class="<?php echo $carimg->slide_src->headerCellClass() ?>"><span id="elh_carimg_slide_src" class="carimg_slide_src"><?php echo $carimg->slide_src->caption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$carimg_delete->RecCnt = 0;
$i = 0;
while (!$carimg_delete->Recordset->EOF) {
	$carimg_delete->RecCnt++;
	$carimg_delete->RowCnt++;

	// Set row properties
	$carimg->resetAttributes();
	$carimg->RowType = ROWTYPE_VIEW; // View

	// Get the field contents
	$carimg_delete->loadRowValues($carimg_delete->Recordset);

	// Render row
	$carimg_delete->renderRow();
?>
	<tr<?php echo $carimg->rowAttributes() ?>>
<?php if ($carimg->id->Visible) { // id ?>
		<td<?php echo $carimg->id->cellAttributes() ?>>
<span id="el<?php echo $carimg_delete->RowCnt ?>_carimg_id" class="carimg_id">
<span<?php echo $carimg->id->viewAttributes() ?>>
<?php echo $carimg->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($carimg->num_slide->Visible) { // num_slide ?>
		<td<?php echo $carimg->num_slide->cellAttributes() ?>>
<span id="el<?php echo $carimg_delete->RowCnt ?>_carimg_num_slide" class="carimg_num_slide">
<span<?php echo $carimg->num_slide->viewAttributes() ?>>
<?php echo $carimg->num_slide->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($carimg->slide_src->Visible) { // slide_src ?>
		<td<?php echo $carimg->slide_src->cellAttributes() ?>>
<span id="el<?php echo $carimg_delete->RowCnt ?>_carimg_slide_src" class="carimg_slide_src">
<span<?php echo $carimg->slide_src->viewAttributes() ?>>
<?php echo GetFileViewTag($carimg->slide_src, $carimg->slide_src->getViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$carimg_delete->Recordset->moveNext();
}
$carimg_delete->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?php echo $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?php echo $carimg_delete->getReturnUrl() ?>"><?php echo $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$carimg_delete->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$carimg_delete->terminate();
?>