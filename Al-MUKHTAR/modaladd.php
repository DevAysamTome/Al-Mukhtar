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
$modal_add = new modal_add();

// Run the page
$modal_add->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$modal_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "add";
var fmodaladd = currentForm = new ew.Form("fmodaladd", "add");

// Validate form
fmodaladd.validate = function() {
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
		<?php if ($modal_add->num_slide->Required) { ?>
			elm = this.getElements("x" + infix + "_num_slide");
			if (elm && !ew.isHidden(elm) && !ew.hasValue(elm))
				return this.onError(elm, "<?php echo JsEncode(str_replace("%s", $modal->num_slide->caption(), $modal->num_slide->RequiredErrorMessage)) ?>");
		<?php } ?>
			elm = this.getElements("x" + infix + "_num_slide");
			if (elm && !ew.checkInteger(elm.value))
				return this.onError(elm, "<?php echo JsEncode($modal->num_slide->errorMessage()) ?>");
		<?php if ($modal_add->slide_src->Required) { ?>
			felm = this.getElements("x" + infix + "_slide_src");
			elm = this.getElements("fn_x" + infix + "_slide_src");
			if (felm && elm && !ew.hasValue(elm))
				return this.onError(felm, "<?php echo JsEncode(str_replace("%s", $modal->slide_src->caption(), $modal->slide_src->RequiredErrorMessage)) ?>");
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
fmodaladd.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fmodaladd.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php $modal_add->showPageHeader(); ?>
<?php
$modal_add->showMessage();
?>
<form name="fmodaladd" id="fmodaladd" class="<?php echo $modal_add->FormClassName ?>" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($modal_add->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $modal_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="modal">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?php echo (int)$modal_add->IsModal ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($modal->num_slide->Visible) { // num_slide ?>
	<div id="r_num_slide" class="form-group row">
		<label id="elh_modal_num_slide" for="x_num_slide" class="<?php echo $modal_add->LeftColumnClass ?>"><?php echo $modal->num_slide->caption() ?><?php echo ($modal->num_slide->Required) ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
		<div class="<?php echo $modal_add->RightColumnClass ?>"><div<?php echo $modal->num_slide->cellAttributes() ?>>
<span id="el_modal_num_slide">
<input type="text" data-table="modal" data-field="x_num_slide" name="x_num_slide" id="x_num_slide" size="30" placeholder="<?php echo HtmlEncode($modal->num_slide->getPlaceHolder()) ?>" value="<?php echo $modal->num_slide->EditValue ?>"<?php echo $modal->num_slide->editAttributes() ?>>
</span>
<?php echo $modal->num_slide->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($modal->slide_src->Visible) { // slide_src ?>
	<div id="r_slide_src" class="form-group row">
		<label id="elh_modal_slide_src" class="<?php echo $modal_add->LeftColumnClass ?>"><?php echo $modal->slide_src->caption() ?><?php echo ($modal->slide_src->Required) ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
		<div class="<?php echo $modal_add->RightColumnClass ?>"><div<?php echo $modal->slide_src->cellAttributes() ?>>
<span id="el_modal_slide_src">
<div id="fd_x_slide_src">
<span title="<?php echo $modal->slide_src->title() ? $modal->slide_src->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($modal->slide_src->ReadOnly || $modal->slide_src->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="modal" data-field="x_slide_src" name="x_slide_src" id="x_slide_src"<?php echo $modal->slide_src->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x_slide_src" id= "fn_x_slide_src" value="<?php echo $modal->slide_src->Upload->FileName ?>">
<input type="hidden" name="fa_x_slide_src" id= "fa_x_slide_src" value="0">
<input type="hidden" name="fs_x_slide_src" id= "fs_x_slide_src" value="200">
<input type="hidden" name="fx_x_slide_src" id= "fx_x_slide_src" value="<?php echo $modal->slide_src->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_slide_src" id= "fm_x_slide_src" value="<?php echo $modal->slide_src->UploadMaxFileSize ?>">
</div>
<table id="ft_x_slide_src" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php echo $modal->slide_src->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$modal_add->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
	<div class="<?php echo $modal_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?php echo $Language->phrase("AddBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?php echo $modal_add->getReturnUrl() ?>"><?php echo $Language->phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<?php
$modal_add->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$modal_add->terminate();
?>