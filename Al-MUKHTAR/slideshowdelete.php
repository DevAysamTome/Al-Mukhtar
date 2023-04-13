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
$slideshow_delete = new slideshow_delete();

// Run the page
$slideshow_delete->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$slideshow_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "delete";
var fslideshowdelete = currentForm = new ew.Form("fslideshowdelete", "delete");

// Form_CustomValidate event
fslideshowdelete.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fslideshowdelete.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php $slideshow_delete->showPageHeader(); ?>
<?php
$slideshow_delete->showMessage();
?>
<form name="fslideshowdelete" id="fslideshowdelete" class="form-inline ew-form ew-delete-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($slideshow_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $slideshow_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="slideshow">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($slideshow_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?php if (IsResponsiveLayout()) { ?>table-responsive <?php } ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
	<thead>
	<tr class="ew-table-header">
<?php if ($slideshow->id->Visible) { // id ?>
		<th class="<?php echo $slideshow->id->headerCellClass() ?>"><span id="elh_slideshow_id" class="slideshow_id"><?php echo $slideshow->id->caption() ?></span></th>
<?php } ?>
<?php if ($slideshow->img_link->Visible) { // img_link ?>
		<th class="<?php echo $slideshow->img_link->headerCellClass() ?>"><span id="elh_slideshow_img_link" class="slideshow_img_link"><?php echo $slideshow->img_link->caption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$slideshow_delete->RecCnt = 0;
$i = 0;
while (!$slideshow_delete->Recordset->EOF) {
	$slideshow_delete->RecCnt++;
	$slideshow_delete->RowCnt++;

	// Set row properties
	$slideshow->resetAttributes();
	$slideshow->RowType = ROWTYPE_VIEW; // View

	// Get the field contents
	$slideshow_delete->loadRowValues($slideshow_delete->Recordset);

	// Render row
	$slideshow_delete->renderRow();
?>
	<tr<?php echo $slideshow->rowAttributes() ?>>
<?php if ($slideshow->id->Visible) { // id ?>
		<td<?php echo $slideshow->id->cellAttributes() ?>>
<span id="el<?php echo $slideshow_delete->RowCnt ?>_slideshow_id" class="slideshow_id">
<span<?php echo $slideshow->id->viewAttributes() ?>>
<?php echo $slideshow->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($slideshow->img_link->Visible) { // img_link ?>
		<td<?php echo $slideshow->img_link->cellAttributes() ?>>
<span id="el<?php echo $slideshow_delete->RowCnt ?>_slideshow_img_link" class="slideshow_img_link">
<span>
<?php echo GetFileViewTag($slideshow->img_link, $slideshow->img_link->getViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$slideshow_delete->Recordset->moveNext();
}
$slideshow_delete->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?php echo $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?php echo $slideshow_delete->getReturnUrl() ?>"><?php echo $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$slideshow_delete->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$slideshow_delete->terminate();
?>