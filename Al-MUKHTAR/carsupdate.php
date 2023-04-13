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
$cars_update = new cars_update();

// Run the page
$cars_update->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cars_update->Page_Render();
?>
<?php include_once "header.php" ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "update";
var fcarsupdate = currentForm = new ew.Form("fcarsupdate", "update");

// Validate form
fcarsupdate.validate = function() {
	if (!this.validateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.getForm(), $fobj = $(fobj);
	if ($fobj.find("#confirm").val() == "F")
		return true;
	if (!ew.updateSelected(fobj)) {
		ew.alert(ew.language.phrase("NoFieldSelected"));
		return false;
	}
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.formKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = ["insert", "gridinsert"].includes($fobj.find("#action").val()) && $k[0];
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		<?php if ($cars_update->car_img->Required) { ?>
			felm = this.getElements("x" + infix + "_car_img");
			elm = this.getElements("fn_x" + infix + "_car_img");
			uelm = this.getElements("u" + infix + "_car_img");
			if (uelm && uelm.checked) {
				if (felm && elm && !ew.hasValue(elm))
					return this.onError(felm, "<?php echo JsEncode(str_replace("%s", $cars->car_img->caption(), $cars->car_img->RequiredErrorMessage)) ?>");
			}
		<?php } ?>

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fcarsupdate.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fcarsupdate.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php $cars_update->showPageHeader(); ?>
<?php
$cars_update->showMessage();
?>
<form name="fcarsupdate" id="fcarsupdate" class="<?php echo $cars_update->FormClassName ?>" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($cars_update->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $cars_update->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cars">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?php echo (int)$cars_update->IsModal ?>">
<?php foreach ($cars_update->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_carsupdate" class="ew-update-div"><!-- page -->
	<div class="form-check">
		<input type="checkbox" class="form-check-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="form-check-label" for="u"><?php echo $Language->Phrase("UpdateSelectAll") ?></label>
	</div>
<?php if ($cars->car_img->Visible) { // car_img ?>
	<div id="r_car_img" class="form-group row">
		<label class="<?php echo $cars_update->LeftColumnClass ?>"><div class="form-check">
<input type="checkbox" name="u_car_img" id="u_car_img" class="form-check-input ew-multi-select" value="1"<?php echo ($cars->car_img->MultiUpdate == "1") ? " checked" : "" ?>>
<label class="form-check-label" for="u_car_img"><?php echo $cars->car_img->caption() ?></label></div></label>
		<div class="<?php echo $cars_update->RightColumnClass ?>"><div<?php echo $cars->car_img->cellAttributes() ?>>
<span id="el_cars_car_img">
<div id="fd_x_car_img">
<span title="<?php echo $cars->car_img->title() ? $cars->car_img->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($cars->car_img->ReadOnly || $cars->car_img->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cars" data-field="x_car_img" name="x_car_img" id="x_car_img"<?php echo $cars->car_img->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x_car_img" id= "fn_x_car_img" value="<?php echo $cars->car_img->Upload->FileName ?>">
<?php if (Post("fa_x_car_img") == "0") { ?>
<input type="hidden" name="fa_x_car_img" id= "fa_x_car_img" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_car_img" id= "fa_x_car_img" value="1">
<?php } ?>
<input type="hidden" name="fs_x_car_img" id= "fs_x_car_img" value="200">
<input type="hidden" name="fx_x_car_img" id= "fx_x_car_img" value="<?php echo $cars->car_img->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_car_img" id= "fm_x_car_img" value="<?php echo $cars->car_img->UploadMaxFileSize ?>">
</div>
<table id="ft_x_car_img" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php echo $cars->car_img->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page -->
<?php if (!$cars_update->IsModal) { ?>
	<div class="form-group row"><!-- buttons .form-group -->
		<div class="<?php echo $cars_update->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?php echo $Language->phrase("UpdateBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?php echo $cars_update->getReturnUrl() ?>"><?php echo $Language->phrase("CancelBtn") ?></button>
		</div><!-- /buttons offset -->
	</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<?php
$cars_update->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cars_update->terminate();
?>