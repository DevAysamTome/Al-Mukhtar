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
$modal_list = new modal_list();

// Run the page
$modal_list->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$modal_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if (!$modal->isExport()) { ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "list";
var fmodallist = currentForm = new ew.Form("fmodallist", "list");
fmodallist.formKeyCountName = '<?php echo $modal_list->FormKeyCountName ?>';

// Validate form
fmodallist.validate = function() {
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
		var checkrow = (gridinsert) ? !this.emptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
		<?php if ($modal_list->id->Required) { ?>
			elm = this.getElements("x" + infix + "_id");
			if (elm && !ew.isHidden(elm) && !ew.hasValue(elm))
				return this.onError(elm, "<?php echo JsEncode(str_replace("%s", $modal->id->caption(), $modal->id->RequiredErrorMessage)) ?>");
		<?php } ?>
		<?php if ($modal_list->num_slide->Required) { ?>
			elm = this.getElements("x" + infix + "_num_slide");
			if (elm && !ew.isHidden(elm) && !ew.hasValue(elm))
				return this.onError(elm, "<?php echo JsEncode(str_replace("%s", $modal->num_slide->caption(), $modal->num_slide->RequiredErrorMessage)) ?>");
		<?php } ?>
			elm = this.getElements("x" + infix + "_num_slide");
			if (elm && !ew.checkInteger(elm.value))
				return this.onError(elm, "<?php echo JsEncode($modal->num_slide->errorMessage()) ?>");
		<?php if ($modal_list->slide_src->Required) { ?>
			felm = this.getElements("x" + infix + "_slide_src");
			elm = this.getElements("fn_x" + infix + "_slide_src");
			if (felm && elm && !ew.hasValue(elm))
				return this.onError(felm, "<?php echo JsEncode(str_replace("%s", $modal->slide_src->caption(), $modal->slide_src->RequiredErrorMessage)) ?>");
		<?php } ?>

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		ew.alert(ew.language.phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
fmodallist.emptyRow = function(infix) {
	var fobj = this._form;
	if (ew.valueChanged(fobj, infix, "num_slide", false)) return false;
	if (ew.valueChanged(fobj, infix, "slide_src", false)) return false;
	return true;
}

// Form_CustomValidate event
fmodallist.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fmodallist.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var fmodallistsrch = currentSearchForm = new ew.Form("fmodallistsrch");

// Filters
fmodallistsrch.filterList = <?php echo $modal_list->getFilterList() ?>;
</script>
<style type="text/css">
.ew-table-preview-row { /* main table preview row color */
	background-color: #FFFFFF; /* preview row color */
}
.ew-table-preview-row .ew-grid {
	display: table;
}
</style>
<div id="ew-preview" class="d-none"><!-- preview -->
	<div class="ew-nav-tabs"><!-- .ew-nav-tabs -->
		<ul class="nav nav-tabs"></ul>
		<div class="tab-content"><!-- .tab-content -->
			<div class="tab-pane fade active show"></div>
		</div><!-- /.tab-content -->
	</div><!-- /.ew-nav-tabs -->
</div><!-- /preview -->
<script src="phpjs/ewpreview.js"></script>
<script>
ew.PREVIEW_PLACEMENT = ew.CSS_FLIP ? "right" : "left";
ew.PREVIEW_SINGLE_ROW = false;
ew.PREVIEW_OVERLAY = false;
</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if (!$modal->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($modal_list->TotalRecs > 0 && $modal_list->ExportOptions->visible()) { ?>
<?php $modal_list->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($modal_list->ImportOptions->visible()) { ?>
<?php $modal_list->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($modal_list->SearchOptions->visible()) { ?>
<?php $modal_list->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($modal_list->FilterOptions->visible()) { ?>
<?php $modal_list->FilterOptions->render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
$modal_list->renderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if (!$modal->isExport() && !$modal->CurrentAction) { ?>
<form name="fmodallistsrch" id="fmodallistsrch" class="form-inline ew-form ew-ext-search-form" action="<?php echo CurrentPageName() ?>">
<?php $searchPanelClass = ($modal_list->SearchWhere <> "") ? " show" : " show"; ?>
<div id="fmodallistsrch-search-panel" class="ew-search-panel collapse<?php echo $searchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="modal">
	<div class="ew-basic-search">
<div id="xsr_1" class="ew-row d-sm-flex">
	<div class="ew-quick-search input-group">
		<input type="text" name="<?php echo TABLE_BASIC_SEARCH ?>" id="<?php echo TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo HtmlEncode($modal_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo HtmlEncode($Language->phrase("Search")) ?>">
		<input type="hidden" name="<?php echo TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo HtmlEncode($modal_list->BasicSearch->getType()) ?>">
		<div class="input-group-append">
			<button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?php echo $Language->phrase("SearchBtn") ?></button>
			<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false"><span id="searchtype"><?php echo $modal_list->BasicSearch->getTypeNameShort() ?></span></button>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item<?php if ($modal_list->BasicSearch->getType() == "") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this)"><?php echo $Language->phrase("QuickSearchAuto") ?></a>
				<a class="dropdown-item<?php if ($modal_list->BasicSearch->getType() == "=") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'=')"><?php echo $Language->phrase("QuickSearchExact") ?></a>
				<a class="dropdown-item<?php if ($modal_list->BasicSearch->getType() == "AND") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'AND')"><?php echo $Language->phrase("QuickSearchAll") ?></a>
				<a class="dropdown-item<?php if ($modal_list->BasicSearch->getType() == "OR") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'OR')"><?php echo $Language->phrase("QuickSearchAny") ?></a>
			</div>
		</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $modal_list->showPageHeader(); ?>
<?php
$modal_list->showMessage();
?>
<?php if ($modal_list->TotalRecs > 0 || $modal->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($modal_list->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> modal">
<?php if (!$modal->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$modal->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?php echo CurrentPageName() ?>">
<?php if (!isset($modal_list->Pager)) $modal_list->Pager = new PrevNextPager($modal_list->StartRec, $modal_list->DisplayRecs, $modal_list->TotalRecs, $modal_list->AutoHidePager) ?>
<?php if ($modal_list->Pager->RecordCount > 0 && $modal_list->Pager->Visible) { ?>
<div class="ew-pager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ew-prev-next"><div class="input-group input-group-sm">
<div class="input-group-prepend">
<!-- first page button -->
	<?php if ($modal_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerFirst") ?>" href="<?php echo $modal_list->pageUrl() ?>start=<?php echo $modal_list->Pager->FirstButton->Start ?>"><i class="icon-first ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerFirst") ?>"><i class="icon-first ew-icon"></i></a>
	<?php } ?>
<!-- previous page button -->
	<?php if ($modal_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerPrevious") ?>" href="<?php echo $modal_list->pageUrl() ?>start=<?php echo $modal_list->Pager->PrevButton->Start ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerPrevious") ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } ?>
</div>
<!-- current page number -->
	<input class="form-control" type="text" name="<?php echo TABLE_PAGE_NO ?>" value="<?php echo $modal_list->Pager->CurrentPage ?>">
<div class="input-group-append">
<!-- next page button -->
	<?php if ($modal_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerNext") ?>" href="<?php echo $modal_list->pageUrl() ?>start=<?php echo $modal_list->Pager->NextButton->Start ?>"><i class="icon-next ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerNext") ?>"><i class="icon-next ew-icon"></i></a>
	<?php } ?>
<!-- last page button -->
	<?php if ($modal_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerLast") ?>" href="<?php echo $modal_list->pageUrl() ?>start=<?php echo $modal_list->Pager->LastButton->Start ?>"><i class="icon-last ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerLast") ?>"><i class="icon-last ew-icon"></i></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $modal_list->Pager->PageCount ?></span>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($modal_list->Pager->RecordCount > 0) { ?>
<div class="ew-pager ew-rec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $modal_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $modal_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $modal_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $modal_list->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fmodallist" id="fmodallist" class="form-inline ew-form ew-list-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($modal_list->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $modal_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="modal">
<div id="gmp_modal" class="<?php if (IsResponsiveLayout()) { ?>table-responsive <?php } ?>card-body ew-grid-middle-panel">
<?php if ($modal_list->TotalRecs > 0 || $modal->isAdd() || $modal->isCopy() || $modal->isGridEdit()) { ?>
<table id="tbl_modallist" class="table ew-table"><!-- .ew-table ##-->
<thead>
	<tr class="ew-table-header">
<?php

// Header row
$modal_list->RowType = ROWTYPE_HEADER;

// Render list options
$modal_list->renderListOptions();

// Render list options (header, left)
$modal_list->ListOptions->render("header", "left");
?>
<?php if ($modal->id->Visible) { // id ?>
	<?php if ($modal->sortUrl($modal->id) == "") { ?>
		<th data-name="id" class="<?php echo $modal->id->headerCellClass() ?>"><div id="elh_modal_id" class="modal_id"><div class="ew-table-header-caption"><?php echo $modal->id->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $modal->id->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event,'<?php echo $modal->SortUrl($modal->id) ?>',1);"><div id="elh_modal_id" class="modal_id">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $modal->id->caption() ?></span><span class="ew-table-header-sort"><?php if ($modal->id->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($modal->id->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($modal->num_slide->Visible) { // num_slide ?>
	<?php if ($modal->sortUrl($modal->num_slide) == "") { ?>
		<th data-name="num_slide" class="<?php echo $modal->num_slide->headerCellClass() ?>"><div id="elh_modal_num_slide" class="modal_num_slide"><div class="ew-table-header-caption"><?php echo $modal->num_slide->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="num_slide" class="<?php echo $modal->num_slide->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event,'<?php echo $modal->SortUrl($modal->num_slide) ?>',1);"><div id="elh_modal_num_slide" class="modal_num_slide">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $modal->num_slide->caption() ?></span><span class="ew-table-header-sort"><?php if ($modal->num_slide->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($modal->num_slide->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($modal->slide_src->Visible) { // slide_src ?>
	<?php if ($modal->sortUrl($modal->slide_src) == "") { ?>
		<th data-name="slide_src" class="<?php echo $modal->slide_src->headerCellClass() ?>"><div id="elh_modal_slide_src" class="modal_slide_src"><div class="ew-table-header-caption"><?php echo $modal->slide_src->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="slide_src" class="<?php echo $modal->slide_src->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event,'<?php echo $modal->SortUrl($modal->slide_src) ?>',1);"><div id="elh_modal_slide_src" class="modal_slide_src">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $modal->slide_src->caption() ?><?php echo $Language->phrase("SrchLegend") ?></span><span class="ew-table-header-sort"><?php if ($modal->slide_src->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($modal->slide_src->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$modal_list->ListOptions->render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($modal->isAdd() || $modal->isCopy()) {
		$modal_list->RowIndex = 0;
		$modal_list->KeyCount = $modal_list->RowIndex;
		if ($modal->isCopy() && !$modal_list->loadRow())
			$modal->CurrentAction = "add";
		if ($modal->isAdd())
			$modal_list->loadRowValues();
		if ($modal->EventCancelled) // Insert failed
			$modal_list->restoreFormValues(); // Restore form values

		// Set row properties
		$modal->resetAttributes();
		$modal->RowAttrs = array_merge($modal->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_modal', 'data-rowtype'=>ROWTYPE_ADD));
		$modal->RowType = ROWTYPE_ADD;

		// Render row
		$modal_list->renderRow();

		// Render list options
		$modal_list->renderListOptions();
		$modal_list->StartRowCnt = 0;
?>
	<tr<?php echo $modal->rowAttributes() ?>>
<?php

// Render list options (body, left)
$modal_list->ListOptions->render("body", "left", $modal_list->RowCnt);
?>
	<?php if ($modal->id->Visible) { // id ?>
		<td data-name="id">
<input type="hidden" data-table="modal" data-field="x_id" name="o<?php echo $modal_list->RowIndex ?>_id" id="o<?php echo $modal_list->RowIndex ?>_id" value="<?php echo HtmlEncode($modal->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($modal->num_slide->Visible) { // num_slide ?>
		<td data-name="num_slide">
<span id="el<?php echo $modal_list->RowCnt ?>_modal_num_slide" class="form-group modal_num_slide">
<input type="text" data-table="modal" data-field="x_num_slide" name="x<?php echo $modal_list->RowIndex ?>_num_slide" id="x<?php echo $modal_list->RowIndex ?>_num_slide" size="30" placeholder="<?php echo HtmlEncode($modal->num_slide->getPlaceHolder()) ?>" value="<?php echo $modal->num_slide->EditValue ?>"<?php echo $modal->num_slide->editAttributes() ?>>
</span>
<input type="hidden" data-table="modal" data-field="x_num_slide" name="o<?php echo $modal_list->RowIndex ?>_num_slide" id="o<?php echo $modal_list->RowIndex ?>_num_slide" value="<?php echo HtmlEncode($modal->num_slide->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($modal->slide_src->Visible) { // slide_src ?>
		<td data-name="slide_src">
<span id="el<?php echo $modal_list->RowCnt ?>_modal_slide_src" class="form-group modal_slide_src">
<div id="fd_x<?php echo $modal_list->RowIndex ?>_slide_src">
<span title="<?php echo $modal->slide_src->title() ? $modal->slide_src->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($modal->slide_src->ReadOnly || $modal->slide_src->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="modal" data-field="x_slide_src" name="x<?php echo $modal_list->RowIndex ?>_slide_src" id="x<?php echo $modal_list->RowIndex ?>_slide_src"<?php echo $modal->slide_src->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fn_x<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo $modal->slide_src->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fa_x<?php echo $modal_list->RowIndex ?>_slide_src" value="0">
<input type="hidden" name="fs_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fs_x<?php echo $modal_list->RowIndex ?>_slide_src" value="200">
<input type="hidden" name="fx_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fx_x<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo $modal->slide_src->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fm_x<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo $modal->slide_src->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $modal_list->RowIndex ?>_slide_src" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="modal" data-field="x_slide_src" name="o<?php echo $modal_list->RowIndex ?>_slide_src" id="o<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo HtmlEncode($modal->slide_src->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$modal_list->ListOptions->render("body", "right", $modal_list->RowCnt);
?>
<script>
fmodallist.updateLists(<?php echo $modal_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($modal->ExportAll && $modal->isExport()) {
	$modal_list->StopRec = $modal_list->TotalRecs;
} else {

	// Set the last record to display
	if ($modal_list->TotalRecs > $modal_list->StartRec + $modal_list->DisplayRecs - 1)
		$modal_list->StopRec = $modal_list->StartRec + $modal_list->DisplayRecs - 1;
	else
		$modal_list->StopRec = $modal_list->TotalRecs;
}

// Restore number of post back records
if ($CurrentForm && $modal_list->EventCancelled) {
	$CurrentForm->Index = -1;
	if ($CurrentForm->hasValue($modal_list->FormKeyCountName) && ($modal->isGridAdd() || $modal->isGridEdit() || $modal->isConfirm())) {
		$modal_list->KeyCount = $CurrentForm->getValue($modal_list->FormKeyCountName);
		$modal_list->StopRec = $modal_list->StartRec + $modal_list->KeyCount - 1;
	}
}
$modal_list->RecCnt = $modal_list->StartRec - 1;
if ($modal_list->Recordset && !$modal_list->Recordset->EOF) {
	$modal_list->Recordset->moveFirst();
	$selectLimit = $modal_list->UseSelectLimit;
	if (!$selectLimit && $modal_list->StartRec > 1)
		$modal_list->Recordset->move($modal_list->StartRec - 1);
} elseif (!$modal->AllowAddDeleteRow && $modal_list->StopRec == 0) {
	$modal_list->StopRec = $modal->GridAddRowCount;
}

// Initialize aggregate
$modal->RowType = ROWTYPE_AGGREGATEINIT;
$modal->resetAttributes();
$modal_list->renderRow();
$modal_list->EditRowCnt = 0;
if ($modal->isEdit())
	$modal_list->RowIndex = 1;
if ($modal->isGridAdd())
	$modal_list->RowIndex = 0;
if ($modal->isGridEdit())
	$modal_list->RowIndex = 0;
while ($modal_list->RecCnt < $modal_list->StopRec) {
	$modal_list->RecCnt++;
	if ($modal_list->RecCnt >= $modal_list->StartRec) {
		$modal_list->RowCnt++;
		if ($modal->isGridAdd() || $modal->isGridEdit() || $modal->isConfirm()) {
			$modal_list->RowIndex++;
			$CurrentForm->Index = $modal_list->RowIndex;
			if ($CurrentForm->hasValue($modal_list->FormActionName) && $modal_list->EventCancelled)
				$modal_list->RowAction = strval($CurrentForm->getValue($modal_list->FormActionName));
			elseif ($modal->isGridAdd())
				$modal_list->RowAction = "insert";
			else
				$modal_list->RowAction = "";
		}

		// Set up key count
		$modal_list->KeyCount = $modal_list->RowIndex;

		// Init row class and style
		$modal->resetAttributes();
		$modal->CssClass = "";
		if ($modal->isGridAdd()) {
			$modal_list->loadRowValues(); // Load default values
		} else {
			$modal_list->loadRowValues($modal_list->Recordset); // Load row values
		}
		$modal->RowType = ROWTYPE_VIEW; // Render view
		if ($modal->isGridAdd()) // Grid add
			$modal->RowType = ROWTYPE_ADD; // Render add
		if ($modal->isGridAdd() && $modal->EventCancelled && !$CurrentForm->hasValue("k_blankrow")) // Insert failed
			$modal_list->restoreCurrentRowFormValues($modal_list->RowIndex); // Restore form values
		if ($modal->isEdit()) {
			if ($modal_list->checkInlineEditKey() && $modal_list->EditRowCnt == 0) { // Inline edit
				$modal->RowType = ROWTYPE_EDIT; // Render edit
			}
		}
		if ($modal->isGridEdit()) { // Grid edit
			if ($modal->EventCancelled)
				$modal_list->restoreCurrentRowFormValues($modal_list->RowIndex); // Restore form values
			if ($modal_list->RowAction == "insert")
				$modal->RowType = ROWTYPE_ADD; // Render add
			else
				$modal->RowType = ROWTYPE_EDIT; // Render edit
		}
		if ($modal->isEdit() && $modal->RowType == ROWTYPE_EDIT && $modal->EventCancelled) { // Update failed
			$CurrentForm->Index = 1;
			$modal_list->restoreFormValues(); // Restore form values
		}
		if ($modal->isGridEdit() && ($modal->RowType == ROWTYPE_EDIT || $modal->RowType == ROWTYPE_ADD) && $modal->EventCancelled) // Update failed
			$modal_list->restoreCurrentRowFormValues($modal_list->RowIndex); // Restore form values
		if ($modal->RowType == ROWTYPE_EDIT) // Edit row
			$modal_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$modal->RowAttrs = array_merge($modal->RowAttrs, array('data-rowindex'=>$modal_list->RowCnt, 'id'=>'r' . $modal_list->RowCnt . '_modal', 'data-rowtype'=>$modal->RowType));

		// Render row
		$modal_list->renderRow();

		// Render list options
		$modal_list->renderListOptions();

		// Skip delete row / empty row for confirm page
		if ($modal_list->RowAction <> "delete" && $modal_list->RowAction <> "insertdelete" && !($modal_list->RowAction == "insert" && $modal->isConfirm() && $modal_list->emptyRow())) {
?>
	<tr<?php echo $modal->rowAttributes() ?>>
<?php

// Render list options (body, left)
$modal_list->ListOptions->render("body", "left", $modal_list->RowCnt);
?>
	<?php if ($modal->id->Visible) { // id ?>
		<td data-name="id"<?php echo $modal->id->cellAttributes() ?>>
<?php if ($modal->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="modal" data-field="x_id" name="o<?php echo $modal_list->RowIndex ?>_id" id="o<?php echo $modal_list->RowIndex ?>_id" value="<?php echo HtmlEncode($modal->id->OldValue) ?>">
<?php } ?>
<?php if ($modal->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($modal->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $modal_list->RowCnt ?>_modal_id" class="modal_id">
<span<?php echo $modal->id->viewAttributes() ?>>
<?php echo $modal->id->getViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($modal->num_slide->Visible) { // num_slide ?>
		<td data-name="num_slide"<?php echo $modal->num_slide->cellAttributes() ?>>
<?php if ($modal->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $modal_list->RowCnt ?>_modal_num_slide" class="form-group modal_num_slide">
<input type="text" data-table="modal" data-field="x_num_slide" name="x<?php echo $modal_list->RowIndex ?>_num_slide" id="x<?php echo $modal_list->RowIndex ?>_num_slide" size="30" placeholder="<?php echo HtmlEncode($modal->num_slide->getPlaceHolder()) ?>" value="<?php echo $modal->num_slide->EditValue ?>"<?php echo $modal->num_slide->editAttributes() ?>>
</span>
<input type="hidden" data-table="modal" data-field="x_num_slide" name="o<?php echo $modal_list->RowIndex ?>_num_slide" id="o<?php echo $modal_list->RowIndex ?>_num_slide" value="<?php echo HtmlEncode($modal->num_slide->OldValue) ?>">
<?php } ?>
<?php if ($modal->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $modal_list->RowCnt ?>_modal_num_slide" class="form-group modal_num_slide">
<input type="text" data-table="modal" data-field="x_num_slide" name="x<?php echo $modal_list->RowIndex ?>_num_slide" id="x<?php echo $modal_list->RowIndex ?>_num_slide" size="30" placeholder="<?php echo HtmlEncode($modal->num_slide->getPlaceHolder()) ?>" value="<?php echo $modal->num_slide->EditValue ?>"<?php echo $modal->num_slide->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($modal->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $modal_list->RowCnt ?>_modal_num_slide" class="modal_num_slide">
<span<?php echo $modal->num_slide->viewAttributes() ?>>
<?php echo $modal->num_slide->getViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($modal->slide_src->Visible) { // slide_src ?>
		<td data-name="slide_src"<?php echo $modal->slide_src->cellAttributes() ?>>
<?php if ($modal->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $modal_list->RowCnt ?>_modal_slide_src" class="form-group modal_slide_src">
<div id="fd_x<?php echo $modal_list->RowIndex ?>_slide_src">
<span title="<?php echo $modal->slide_src->title() ? $modal->slide_src->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($modal->slide_src->ReadOnly || $modal->slide_src->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="modal" data-field="x_slide_src" name="x<?php echo $modal_list->RowIndex ?>_slide_src" id="x<?php echo $modal_list->RowIndex ?>_slide_src"<?php echo $modal->slide_src->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fn_x<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo $modal->slide_src->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fa_x<?php echo $modal_list->RowIndex ?>_slide_src" value="0">
<input type="hidden" name="fs_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fs_x<?php echo $modal_list->RowIndex ?>_slide_src" value="200">
<input type="hidden" name="fx_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fx_x<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo $modal->slide_src->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fm_x<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo $modal->slide_src->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $modal_list->RowIndex ?>_slide_src" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="modal" data-field="x_slide_src" name="o<?php echo $modal_list->RowIndex ?>_slide_src" id="o<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo HtmlEncode($modal->slide_src->OldValue) ?>">
<?php } ?>
<?php if ($modal->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $modal_list->RowCnt ?>_modal_slide_src" class="form-group modal_slide_src">
<div id="fd_x<?php echo $modal_list->RowIndex ?>_slide_src">
<span title="<?php echo $modal->slide_src->title() ? $modal->slide_src->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($modal->slide_src->ReadOnly || $modal->slide_src->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="modal" data-field="x_slide_src" name="x<?php echo $modal_list->RowIndex ?>_slide_src" id="x<?php echo $modal_list->RowIndex ?>_slide_src"<?php echo $modal->slide_src->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fn_x<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo $modal->slide_src->Upload->FileName ?>">
<?php if (Post("fa_x<?php echo $modal_list->RowIndex ?>_slide_src") == "0") { ?>
<input type="hidden" name="fa_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fa_x<?php echo $modal_list->RowIndex ?>_slide_src" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fa_x<?php echo $modal_list->RowIndex ?>_slide_src" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fs_x<?php echo $modal_list->RowIndex ?>_slide_src" value="200">
<input type="hidden" name="fx_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fx_x<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo $modal->slide_src->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fm_x<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo $modal->slide_src->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $modal_list->RowIndex ?>_slide_src" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php if ($modal->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $modal_list->RowCnt ?>_modal_slide_src" class="modal_slide_src">
<span<?php echo $modal->slide_src->viewAttributes() ?>>
<?php echo GetFileViewTag($modal->slide_src, $modal->slide_src->getViewValue()) ?>
</span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$modal_list->ListOptions->render("body", "right", $modal_list->RowCnt);
?>
	</tr>
<?php if ($modal->RowType == ROWTYPE_ADD || $modal->RowType == ROWTYPE_EDIT) { ?>
<script>
fmodallist.updateLists(<?php echo $modal_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if (!$modal->isGridAdd())
		if (!$modal_list->Recordset->EOF)
			$modal_list->Recordset->moveNext();
}
?>
<?php
	if ($modal->isGridAdd() || $modal->isGridEdit()) {
		$modal_list->RowIndex = '$rowindex$';
		$modal_list->loadRowValues();

		// Set row properties
		$modal->resetAttributes();
		$modal->RowAttrs = array_merge($modal->RowAttrs, array('data-rowindex'=>$modal_list->RowIndex, 'id'=>'r0_modal', 'data-rowtype'=>ROWTYPE_ADD));
		AppendClass($modal->RowAttrs["class"], "ew-template");
		$modal->RowType = ROWTYPE_ADD;

		// Render row
		$modal_list->renderRow();

		// Render list options
		$modal_list->renderListOptions();
		$modal_list->StartRowCnt = 0;
?>
	<tr<?php echo $modal->rowAttributes() ?>>
<?php

// Render list options (body, left)
$modal_list->ListOptions->render("body", "left", $modal_list->RowIndex);
?>
	<?php if ($modal->id->Visible) { // id ?>
		<td data-name="id">
<input type="hidden" data-table="modal" data-field="x_id" name="o<?php echo $modal_list->RowIndex ?>_id" id="o<?php echo $modal_list->RowIndex ?>_id" value="<?php echo HtmlEncode($modal->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($modal->num_slide->Visible) { // num_slide ?>
		<td data-name="num_slide">
<span id="el$rowindex$_modal_num_slide" class="form-group modal_num_slide">
<input type="text" data-table="modal" data-field="x_num_slide" name="x<?php echo $modal_list->RowIndex ?>_num_slide" id="x<?php echo $modal_list->RowIndex ?>_num_slide" size="30" placeholder="<?php echo HtmlEncode($modal->num_slide->getPlaceHolder()) ?>" value="<?php echo $modal->num_slide->EditValue ?>"<?php echo $modal->num_slide->editAttributes() ?>>
</span>
<input type="hidden" data-table="modal" data-field="x_num_slide" name="o<?php echo $modal_list->RowIndex ?>_num_slide" id="o<?php echo $modal_list->RowIndex ?>_num_slide" value="<?php echo HtmlEncode($modal->num_slide->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($modal->slide_src->Visible) { // slide_src ?>
		<td data-name="slide_src">
<span id="el$rowindex$_modal_slide_src" class="form-group modal_slide_src">
<div id="fd_x<?php echo $modal_list->RowIndex ?>_slide_src">
<span title="<?php echo $modal->slide_src->title() ? $modal->slide_src->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($modal->slide_src->ReadOnly || $modal->slide_src->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="modal" data-field="x_slide_src" name="x<?php echo $modal_list->RowIndex ?>_slide_src" id="x<?php echo $modal_list->RowIndex ?>_slide_src"<?php echo $modal->slide_src->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fn_x<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo $modal->slide_src->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fa_x<?php echo $modal_list->RowIndex ?>_slide_src" value="0">
<input type="hidden" name="fs_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fs_x<?php echo $modal_list->RowIndex ?>_slide_src" value="200">
<input type="hidden" name="fx_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fx_x<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo $modal->slide_src->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $modal_list->RowIndex ?>_slide_src" id= "fm_x<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo $modal->slide_src->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $modal_list->RowIndex ?>_slide_src" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="modal" data-field="x_slide_src" name="o<?php echo $modal_list->RowIndex ?>_slide_src" id="o<?php echo $modal_list->RowIndex ?>_slide_src" value="<?php echo HtmlEncode($modal->slide_src->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$modal_list->ListOptions->render("body", "right", $modal_list->RowIndex);
?>
<script>
fmodallist.updateLists(<?php echo $modal_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
<?php if ($modal->isAdd() || $modal->isCopy()) { ?>
<input type="hidden" name="<?php echo $modal_list->FormKeyCountName ?>" id="<?php echo $modal_list->FormKeyCountName ?>" value="<?php echo $modal_list->KeyCount ?>">
<?php } ?>
<?php if ($modal->isGridAdd()) { ?>
<input type="hidden" name="action" id="action" value="gridinsert">
<input type="hidden" name="<?php echo $modal_list->FormKeyCountName ?>" id="<?php echo $modal_list->FormKeyCountName ?>" value="<?php echo $modal_list->KeyCount ?>">
<?php echo $modal_list->MultiSelectKey ?>
<?php } ?>
<?php if ($modal->isEdit()) { ?>
<input type="hidden" name="<?php echo $modal_list->FormKeyCountName ?>" id="<?php echo $modal_list->FormKeyCountName ?>" value="<?php echo $modal_list->KeyCount ?>">
<?php } ?>
<?php if ($modal->isGridEdit()) { ?>
<input type="hidden" name="action" id="action" value="gridupdate">
<input type="hidden" name="<?php echo $modal_list->FormKeyCountName ?>" id="<?php echo $modal_list->FormKeyCountName ?>" value="<?php echo $modal_list->KeyCount ?>">
<?php echo $modal_list->MultiSelectKey ?>
<?php } ?>
<?php if (!$modal->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
</form><!-- /.ew-list-form -->
<?php

// Close recordset
if ($modal_list->Recordset)
	$modal_list->Recordset->Close();
?>
<?php if (!$modal->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$modal->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?php echo CurrentPageName() ?>">
<?php if (!isset($modal_list->Pager)) $modal_list->Pager = new PrevNextPager($modal_list->StartRec, $modal_list->DisplayRecs, $modal_list->TotalRecs, $modal_list->AutoHidePager) ?>
<?php if ($modal_list->Pager->RecordCount > 0 && $modal_list->Pager->Visible) { ?>
<div class="ew-pager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ew-prev-next"><div class="input-group input-group-sm">
<div class="input-group-prepend">
<!-- first page button -->
	<?php if ($modal_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerFirst") ?>" href="<?php echo $modal_list->pageUrl() ?>start=<?php echo $modal_list->Pager->FirstButton->Start ?>"><i class="icon-first ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerFirst") ?>"><i class="icon-first ew-icon"></i></a>
	<?php } ?>
<!-- previous page button -->
	<?php if ($modal_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerPrevious") ?>" href="<?php echo $modal_list->pageUrl() ?>start=<?php echo $modal_list->Pager->PrevButton->Start ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerPrevious") ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } ?>
</div>
<!-- current page number -->
	<input class="form-control" type="text" name="<?php echo TABLE_PAGE_NO ?>" value="<?php echo $modal_list->Pager->CurrentPage ?>">
<div class="input-group-append">
<!-- next page button -->
	<?php if ($modal_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerNext") ?>" href="<?php echo $modal_list->pageUrl() ?>start=<?php echo $modal_list->Pager->NextButton->Start ?>"><i class="icon-next ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerNext") ?>"><i class="icon-next ew-icon"></i></a>
	<?php } ?>
<!-- last page button -->
	<?php if ($modal_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerLast") ?>" href="<?php echo $modal_list->pageUrl() ?>start=<?php echo $modal_list->Pager->LastButton->Start ?>"><i class="icon-last ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerLast") ?>"><i class="icon-last ew-icon"></i></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $modal_list->Pager->PageCount ?></span>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($modal_list->Pager->RecordCount > 0) { ?>
<div class="ew-pager ew-rec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $modal_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $modal_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $modal_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $modal_list->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($modal_list->TotalRecs == 0 && !$modal->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $modal_list->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php
$modal_list->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<?php if (!$modal->isExport()) { ?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$modal_list->terminate();
?>