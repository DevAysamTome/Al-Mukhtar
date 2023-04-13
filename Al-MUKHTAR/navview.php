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
$nav_view = new nav_view();

// Run the page
$nav_view->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$nav_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if (!$nav->isExport()) { ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "view";
var fnavview = currentForm = new ew.Form("fnavview", "view");

// Form_CustomValidate event
fnavview.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fnavview.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if (!$nav->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $nav_view->ExportOptions->render("body") ?>
<?php $nav_view->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $nav_view->showPageHeader(); ?>
<?php
$nav_view->showMessage();
?>
<form name="fnavview" id="fnavview" class="form-inline ew-form ew-view-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($nav_view->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $nav_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="nav">
<input type="hidden" name="modal" value="<?php echo (int)$nav_view->IsModal ?>">
<table class="table ew-view-table">
<?php if ($nav->id->Visible) { // id ?>
	<tr id="r_id">
		<td class="<?php echo $nav_view->TableLeftColumnClass ?>"><span id="elh_nav_id"><?php echo $nav->id->caption() ?></span></td>
		<td data-name="id"<?php echo $nav->id->cellAttributes() ?>>
<span id="el_nav_id">
<span<?php echo $nav->id->viewAttributes() ?>>
<?php echo $nav->id->getViewValue() ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($nav->nav_name->Visible) { // nav_name ?>
	<tr id="r_nav_name">
		<td class="<?php echo $nav_view->TableLeftColumnClass ?>"><span id="elh_nav_nav_name"><?php echo $nav->nav_name->caption() ?></span></td>
		<td data-name="nav_name"<?php echo $nav->nav_name->cellAttributes() ?>>
<span id="el_nav_nav_name">
<span<?php echo $nav->nav_name->viewAttributes() ?>>
<?php echo $nav->nav_name->getViewValue() ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($nav->nav_link->Visible) { // nav_link ?>
	<tr id="r_nav_link">
		<td class="<?php echo $nav_view->TableLeftColumnClass ?>"><span id="elh_nav_nav_link"><?php echo $nav->nav_link->caption() ?></span></td>
		<td data-name="nav_link"<?php echo $nav->nav_link->cellAttributes() ?>>
<span id="el_nav_nav_link">
<span<?php echo $nav->nav_link->viewAttributes() ?>>
<?php echo $nav->nav_link->getViewValue() ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php
$nav_view->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<?php if (!$nav->isExport()) { ?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$nav_view->terminate();
?>