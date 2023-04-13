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
$slideshow_edit = new slideshow_edit();

// Run the page
$slideshow_edit->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$slideshow_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "edit";
var fslideshowedit = currentForm = new ew.Form("fslideshowedit", "edit");

// Validate form
fslideshowedit.validate = function() {
	if (!this.validateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.getForm(), $fobj = $(fobj);
	if ($fobj.find("#confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.formKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = ["insert", "gridinsert"].includes($fobj.find("#action").val()) && $k[0];
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		<?php if ($slideshow_edit->id->Required) { ?>
			elm = this.getElements("x" + infix + "_id");
			if (elm && !ew.isHidden(elm) && !ew.hasValue(elm))
				return this.onError(elm, "<?php echo JsEncode(str_replace("%s", $slideshow->id->caption(), $slideshow->id->RequiredErrorMessage)) ?>");
		<?php } ?>
		<?php if ($slideshow_edit->img_link->Required) { ?>
			felm = this.getElements("x" + infix + "_img_link");
			elm = this.getElements("fn_x" + infix + "_img_link");
			if (felm && elm && !ew.hasValue(elm))
				return this.onError(felm, "<?php echo JsEncode(str_replace("%s", $slideshow->img_link->caption(), $slideshow->img_link->RequiredErrorMessage)) ?>");
		<?php } ?>

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ew.forms[val])
			if (!ew.forms[val].validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fslideshowedit.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fslideshowedit.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php $slideshow_edit->showPageHeader(); ?>
<?php
$slideshow_edit->showMessage();
?>
<form name="fslideshowedit" id="fslideshowedit" class="<?php echo $slideshow_edit->FormClassName ?>" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($slideshow_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $slideshow_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="slideshow">
<?php if ($slideshow->isConfirm()) { // Confirm page ?>
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="confirm" id="confirm" value="confirm">
<?php } else { ?>
<input type="hidden" name="action" id="action" value="confirm">
<?php } ?>
<input type="hidden" name="modal" value="<?php echo (int)$slideshow_edit->IsModal ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($slideshow->id->Visible) { // id ?>
	<div id="r_id" class="form-group row">
		<label id="elh_slideshow_id" class="<?php echo $slideshow_edit->LeftColumnClass ?>"><?php echo $slideshow->id->caption() ?><?php echo ($slideshow->id->Required) ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
		<div class="<?php echo $slideshow_edit->RightColumnClass ?>"><div<?php echo $slideshow->id->cellAttributes() ?>>
<?php if (!$slideshow->isConfirm()) { ?>
<span id="el_slideshow_id">
<span<?php echo $slideshow->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?php echo RemoveHtml($slideshow->id->EditValue) ?>"></span>
</span>
<input type="hidden" data-table="slideshow" data-field="x_id" name="x_id" id="x_id" value="<?php echo HtmlEncode($slideshow->id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_slideshow_id">
<span<?php echo $slideshow->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?php echo RemoveHtml($slideshow->id->ViewValue) ?>"></span>
</span>
<input type="hidden" data-table="slideshow" data-field="x_id" name="x_id" id="x_id" value="<?php echo HtmlEncode($slideshow->id->FormValue) ?>">
<?php } ?>
<?php echo $slideshow->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($slideshow->img_link->Visible) { // img_link ?>
	<div id="r_img_link" class="form-group row">
		<label id="elh_slideshow_img_link" class="<?php echo $slideshow_edit->LeftColumnClass ?>"><?php echo $slideshow->img_link->caption() ?><?php echo ($slideshow->img_link->Required) ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
		<div class="<?php echo $slideshow_edit->RightColumnClass ?>"><div<?php echo $slideshow->img_link->cellAttributes() ?>>
<span id="el_slideshow_img_link">
<div id="fd_x_img_link">
<span title="<?php echo $slideshow->img_link->title() ? $slideshow->img_link->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($slideshow->img_link->ReadOnly || $slideshow->img_link->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="slideshow" data-field="x_img_link" name="x_img_link" id="x_img_link"<?php echo $slideshow->img_link->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x_img_link" id= "fn_x_img_link" value="<?php echo $slideshow->img_link->Upload->FileName ?>">
<?php if (Post("fa_x_img_link") == "0") { ?>
<input type="hidden" name="fa_x_img_link" id= "fa_x_img_link" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_img_link" id= "fa_x_img_link" value="1">
<?php } ?>
<input type="hidden" name="fs_x_img_link" id= "fs_x_img_link" value="200">
<input type="hidden" name="fx_x_img_link" id= "fx_x_img_link" value="<?php echo $slideshow->img_link->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_img_link" id= "fm_x_img_link" value="<?php echo $slideshow->img_link->UploadMaxFileSize ?>">
</div>
<table id="ft_x_img_link" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php echo $slideshow->img_link->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$slideshow_edit->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
	<div class="<?php echo $slideshow_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if (!$slideshow->isConfirm()) { // Confirm page ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" onclick="this.form.action.value='confirm';"><?php echo $Language->phrase("SaveBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?php echo $slideshow_edit->getReturnUrl() ?>"><?php echo $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?php echo $Language->phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="submit" onclick="this.form.action.value='cancel';"><?php echo $Language->phrase("CancelBtn") ?></button>
<?php } ?>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<?php
$slideshow_edit->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$slideshow_edit->terminate();
?>