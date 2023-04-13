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
$nav_delete = new nav_delete();

// Run the page
$nav_delete->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$nav_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "delete";
var fnavdelete = currentForm = new ew.Form("fnavdelete", "delete");

// Form_CustomValidate event
fnavdelete.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fnavdelete.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php $nav_delete->showPageHeader(); ?>
<?php
$nav_delete->showMessage();
?>
<form name="fnavdelete" id="fnavdelete" class="form-inline ew-form ew-delete-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($nav_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $nav_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="nav">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($nav_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?php if (IsResponsiveLayout()) { ?>table-responsive <?php } ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
	<thead>
	<tr class="ew-table-header">
<?php if ($nav->id->Visible) { // id ?>
		<th class="<?php echo $nav->id->headerCellClass() ?>"><span id="elh_nav_id" class="nav_id"><?php echo $nav->id->caption() ?></span></th>
<?php } ?>
<?php if ($nav->nav_name->Visible) { // nav_name ?>
		<th class="<?php echo $nav->nav_name->headerCellClass() ?>"><span id="elh_nav_nav_name" class="nav_nav_name"><?php echo $nav->nav_name->caption() ?></span></th>
<?php } ?>
<?php if ($nav->nav_link->Visible) { // nav_link ?>
		<th class="<?php echo $nav->nav_link->headerCellClass() ?>"><span id="elh_nav_nav_link" class="nav_nav_link"><?php echo $nav->nav_link->caption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$nav_delete->RecCnt = 0;
$i = 0;
while (!$nav_delete->Recordset->EOF) {
	$nav_delete->RecCnt++;
	$nav_delete->RowCnt++;

	// Set row properties
	$nav->resetAttributes();
	$nav->RowType = ROWTYPE_VIEW; // View

	// Get the field contents
	$nav_delete->loadRowValues($nav_delete->Recordset);

	// Render row
	$nav_delete->renderRow();
?>
	<tr<?php echo $nav->rowAttributes() ?>>
<?php if ($nav->id->Visible) { // id ?>
		<td<?php echo $nav->id->cellAttributes() ?>>
<span id="el<?php echo $nav_delete->RowCnt ?>_nav_id" class="nav_id">
<span<?php echo $nav->id->viewAttributes() ?>>
<?php echo $nav->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($nav->nav_name->Visible) { // nav_name ?>
		<td<?php echo $nav->nav_name->cellAttributes() ?>>
<span id="el<?php echo $nav_delete->RowCnt ?>_nav_nav_name" class="nav_nav_name">
<span<?php echo $nav->nav_name->viewAttributes() ?>>
<?php echo $nav->nav_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($nav->nav_link->Visible) { // nav_link ?>
		<td<?php echo $nav->nav_link->cellAttributes() ?>>
<span id="el<?php echo $nav_delete->RowCnt ?>_nav_nav_link" class="nav_nav_link">
<span<?php echo $nav->nav_link->viewAttributes() ?>>
<?php echo $nav->nav_link->getViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$nav_delete->Recordset->moveNext();
}
$nav_delete->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?php echo $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?php echo $nav_delete->getReturnUrl() ?>"><?php echo $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$nav_delete->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$nav_delete->terminate();
?>