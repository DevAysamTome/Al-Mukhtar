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
$cars_view = new cars_view();

// Run the page
$cars_view->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cars_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if (!$cars->isExport()) { ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "view";
var fcarsview = currentForm = new ew.Form("fcarsview", "view");

// Form_CustomValidate event
fcarsview.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fcarsview.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if (!$cars->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $cars_view->ExportOptions->render("body") ?>
<?php $cars_view->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $cars_view->showPageHeader(); ?>
<?php
$cars_view->showMessage();
?>
<form name="fcarsview" id="fcarsview" class="form-inline ew-form ew-view-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($cars_view->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $cars_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cars">
<input type="hidden" name="modal" value="<?php echo (int)$cars_view->IsModal ?>">
<table class="table ew-view-table">
<?php if ($cars->id->Visible) { // id ?>
	<tr id="r_id">
		<td class="<?php echo $cars_view->TableLeftColumnClass ?>"><span id="elh_cars_id"><?php echo $cars->id->caption() ?></span></td>
		<td data-name="id"<?php echo $cars->id->cellAttributes() ?>>
<span id="el_cars_id" data-page="1">
<span<?php echo $cars->id->viewAttributes() ?>>
<?php echo $cars->id->getViewValue() ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cars->car_img->Visible) { // car_img ?>
	<tr id="r_car_img">
		<td class="<?php echo $cars_view->TableLeftColumnClass ?>"><span id="elh_cars_car_img"><?php echo $cars->car_img->caption() ?></span></td>
		<td data-name="car_img"<?php echo $cars->car_img->cellAttributes() ?>>
<span id="el_cars_car_img" data-page="1">
<span>
<?php echo GetFileViewTag($cars->car_img, $cars->car_img->getViewValue()) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php
$cars_view->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<?php if (!$cars->isExport()) { ?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$cars_view->terminate();
?>