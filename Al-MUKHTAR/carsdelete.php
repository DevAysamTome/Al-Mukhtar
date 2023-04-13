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
$cars_delete = new cars_delete();

// Run the page
$cars_delete->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cars_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "delete";
var fcarsdelete = currentForm = new ew.Form("fcarsdelete", "delete");

// Form_CustomValidate event
fcarsdelete.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fcarsdelete.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php $cars_delete->showPageHeader(); ?>
<?php
$cars_delete->showMessage();
?>
<form name="fcarsdelete" id="fcarsdelete" class="form-inline ew-form ew-delete-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($cars_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $cars_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cars">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($cars_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?php if (IsResponsiveLayout()) { ?>table-responsive <?php } ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
	<thead>
	<tr class="ew-table-header">
<?php if ($cars->id->Visible) { // id ?>
		<th class="<?php echo $cars->id->headerCellClass() ?>"><span id="elh_cars_id" class="cars_id"><?php echo $cars->id->caption() ?></span></th>
<?php } ?>
<?php if ($cars->car_img->Visible) { // car_img ?>
		<th class="<?php echo $cars->car_img->headerCellClass() ?>"><span id="elh_cars_car_img" class="cars_car_img"><?php echo $cars->car_img->caption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$cars_delete->RecCnt = 0;
$i = 0;
while (!$cars_delete->Recordset->EOF) {
	$cars_delete->RecCnt++;
	$cars_delete->RowCnt++;

	// Set row properties
	$cars->resetAttributes();
	$cars->RowType = ROWTYPE_VIEW; // View

	// Get the field contents
	$cars_delete->loadRowValues($cars_delete->Recordset);

	// Render row
	$cars_delete->renderRow();
?>
	<tr<?php echo $cars->rowAttributes() ?>>
<?php if ($cars->id->Visible) { // id ?>
		<td<?php echo $cars->id->cellAttributes() ?>>
<span id="el<?php echo $cars_delete->RowCnt ?>_cars_id" class="cars_id">
<span<?php echo $cars->id->viewAttributes() ?>>
<?php echo $cars->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cars->car_img->Visible) { // car_img ?>
		<td<?php echo $cars->car_img->cellAttributes() ?>>
<span id="el<?php echo $cars_delete->RowCnt ?>_cars_car_img" class="cars_car_img">
<span>
<?php echo GetFileViewTag($cars->car_img, $cars->car_img->getViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$cars_delete->Recordset->moveNext();
}
$cars_delete->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?php echo $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?php echo $cars_delete->getReturnUrl() ?>"><?php echo $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$cars_delete->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cars_delete->terminate();
?>