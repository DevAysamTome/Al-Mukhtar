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
$nav_edit = new nav_edit();

// Run the page
$nav_edit->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$nav_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "edit";
var fnavedit = currentForm = new ew.Form("fnavedit", "edit");

// Validate form
fnavedit.validate = function() {
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
		<?php if ($nav_edit->id->Required) { ?>
			elm = this.getElements("x" + infix + "_id");
			if (elm && !ew.isHidden(elm) && !ew.hasValue(elm))
				return this.onError(elm, "<?php echo JsEncode(str_replace("%s", $nav->id->caption(), $nav->id->RequiredErrorMessage)) ?>");
		<?php } ?>
		<?php if ($nav_edit->nav_name->Required) { ?>
			elm = this.getElements("x" + infix + "_nav_name");
			if (elm && !ew.isHidden(elm) && !ew.hasValue(elm))
				return this.onError(elm, "<?php echo JsEncode(str_replace("%s", $nav->nav_name->caption(), $nav->nav_name->RequiredErrorMessage)) ?>");
		<?php } ?>
		<?php if ($nav_edit->nav_link->Required) { ?>
			elm = this.getElements("x" + infix + "_nav_link");
			if (elm && !ew.isHidden(elm) && !ew.hasValue(elm))
				return this.onError(elm, "<?php echo JsEncode(str_replace("%s", $nav->nav_link->caption(), $nav->nav_link->RequiredErrorMessage)) ?>");
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
fnavedit.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fnavedit.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php $nav_edit->showPageHeader(); ?>
<?php
$nav_edit->showMessage();
?>
<form name="fnavedit" id="fnavedit" class="<?php echo $nav_edit->FormClassName ?>" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($nav_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $nav_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="nav">
<?php if ($nav->isConfirm()) { // Confirm page ?>
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="confirm" id="confirm" value="confirm">
<?php } else { ?>
<input type="hidden" name="action" id="action" value="confirm">
<?php } ?>
<input type="hidden" name="modal" value="<?php echo (int)$nav_edit->IsModal ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($nav->id->Visible) { // id ?>
	<div id="r_id" class="form-group row">
		<label id="elh_nav_id" class="<?php echo $nav_edit->LeftColumnClass ?>"><?php echo $nav->id->caption() ?><?php echo ($nav->id->Required) ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
		<div class="<?php echo $nav_edit->RightColumnClass ?>"><div<?php echo $nav->id->cellAttributes() ?>>
<?php if (!$nav->isConfirm()) { ?>
<span id="el_nav_id">
<span<?php echo $nav->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?php echo RemoveHtml($nav->id->EditValue) ?>"></span>
</span>
<input type="hidden" data-table="nav" data-field="x_id" name="x_id" id="x_id" value="<?php echo HtmlEncode($nav->id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_nav_id">
<span<?php echo $nav->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?php echo RemoveHtml($nav->id->ViewValue) ?>"></span>
</span>
<input type="hidden" data-table="nav" data-field="x_id" name="x_id" id="x_id" value="<?php echo HtmlEncode($nav->id->FormValue) ?>">
<?php } ?>
<?php echo $nav->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($nav->nav_name->Visible) { // nav_name ?>
	<div id="r_nav_name" class="form-group row">
		<label id="elh_nav_nav_name" for="x_nav_name" class="<?php echo $nav_edit->LeftColumnClass ?>"><?php echo $nav->nav_name->caption() ?><?php echo ($nav->nav_name->Required) ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
		<div class="<?php echo $nav_edit->RightColumnClass ?>"><div<?php echo $nav->nav_name->cellAttributes() ?>>
<?php if (!$nav->isConfirm()) { ?>
<span id="el_nav_nav_name">
<input type="text" data-table="nav" data-field="x_nav_name" name="x_nav_name" id="x_nav_name" size="30" maxlength="50" placeholder="<?php echo HtmlEncode($nav->nav_name->getPlaceHolder()) ?>" value="<?php echo $nav->nav_name->EditValue ?>"<?php echo $nav->nav_name->editAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_nav_nav_name">
<span<?php echo $nav->nav_name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?php echo RemoveHtml($nav->nav_name->ViewValue) ?>"></span>
</span>
<input type="hidden" data-table="nav" data-field="x_nav_name" name="x_nav_name" id="x_nav_name" value="<?php echo HtmlEncode($nav->nav_name->FormValue) ?>">
<?php } ?>
<?php echo $nav->nav_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($nav->nav_link->Visible) { // nav_link ?>
	<div id="r_nav_link" class="form-group row">
		<label id="elh_nav_nav_link" for="x_nav_link" class="<?php echo $nav_edit->LeftColumnClass ?>"><?php echo $nav->nav_link->caption() ?><?php echo ($nav->nav_link->Required) ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
		<div class="<?php echo $nav_edit->RightColumnClass ?>"><div<?php echo $nav->nav_link->cellAttributes() ?>>
<?php if (!$nav->isConfirm()) { ?>
<span id="el_nav_nav_link">
<input type="text" data-table="nav" data-field="x_nav_link" name="x_nav_link" id="x_nav_link" size="30" maxlength="200" placeholder="<?php echo HtmlEncode($nav->nav_link->getPlaceHolder()) ?>" value="<?php echo $nav->nav_link->EditValue ?>"<?php echo $nav->nav_link->editAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_nav_nav_link">
<span<?php echo $nav->nav_link->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?php echo RemoveHtml($nav->nav_link->ViewValue) ?>"></span>
</span>
<input type="hidden" data-table="nav" data-field="x_nav_link" name="x_nav_link" id="x_nav_link" value="<?php echo HtmlEncode($nav->nav_link->FormValue) ?>">
<?php } ?>
<?php echo $nav->nav_link->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$nav_edit->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
	<div class="<?php echo $nav_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if (!$nav->isConfirm()) { // Confirm page ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" onclick="this.form.action.value='confirm';"><?php echo $Language->phrase("SaveBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?php echo $nav_edit->getReturnUrl() ?>"><?php echo $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?php echo $Language->phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="submit" onclick="this.form.action.value='cancel';"><?php echo $Language->phrase("CancelBtn") ?></button>
<?php } ?>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<?php
$nav_edit->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$nav_edit->terminate();
?>