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
$carimg_list = new carimg_list();

// Run the page
$carimg_list->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$carimg_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if (!$carimg->isExport()) { ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "list";
var fcarimglist = currentForm = new ew.Form("fcarimglist", "list");
fcarimglist.formKeyCountName = '<?php echo $carimg_list->FormKeyCountName ?>';

// Validate form
fcarimglist.validate = function() {
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
		<?php if ($carimg_list->id->Required) { ?>
			elm = this.getElements("x" + infix + "_id");
			if (elm && !ew.isHidden(elm) && !ew.hasValue(elm))
				return this.onError(elm, "<?php echo JsEncode(str_replace("%s", $carimg->id->caption(), $carimg->id->RequiredErrorMessage)) ?>");
		<?php } ?>
		<?php if ($carimg_list->num_slide->Required) { ?>
			elm = this.getElements("x" + infix + "_num_slide");
			if (elm && !ew.isHidden(elm) && !ew.hasValue(elm))
				return this.onError(elm, "<?php echo JsEncode(str_replace("%s", $carimg->num_slide->caption(), $carimg->num_slide->RequiredErrorMessage)) ?>");
		<?php } ?>
			elm = this.getElements("x" + infix + "_num_slide");
			if (elm && !ew.checkInteger(elm.value))
				return this.onError(elm, "<?php echo JsEncode($carimg->num_slide->errorMessage()) ?>");
		<?php if ($carimg_list->slide_src->Required) { ?>
			felm = this.getElements("x" + infix + "_slide_src");
			elm = this.getElements("fn_x" + infix + "_slide_src");
			if (felm && elm && !ew.hasValue(elm))
				return this.onError(felm, "<?php echo JsEncode(str_replace("%s", $carimg->slide_src->caption(), $carimg->slide_src->RequiredErrorMessage)) ?>");
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
fcarimglist.emptyRow = function(infix) {
	var fobj = this._form;
	if (ew.valueChanged(fobj, infix, "num_slide", false)) return false;
	if (ew.valueChanged(fobj, infix, "slide_src", false)) return false;
	return true;
}

// Form_CustomValidate event
fcarimglist.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fcarimglist.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var fcarimglistsrch = currentSearchForm = new ew.Form("fcarimglistsrch");

// Filters
fcarimglistsrch.filterList = <?php echo $carimg_list->getFilterList() ?>;
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
<?php if (!$carimg->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($carimg_list->TotalRecs > 0 && $carimg_list->ExportOptions->visible()) { ?>
<?php $carimg_list->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($carimg_list->ImportOptions->visible()) { ?>
<?php $carimg_list->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($carimg_list->SearchOptions->visible()) { ?>
<?php $carimg_list->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($carimg_list->FilterOptions->visible()) { ?>
<?php $carimg_list->FilterOptions->render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
$carimg_list->renderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if (!$carimg->isExport() && !$carimg->CurrentAction) { ?>
<form name="fcarimglistsrch" id="fcarimglistsrch" class="form-inline ew-form ew-ext-search-form" action="<?php echo CurrentPageName() ?>">
<?php $searchPanelClass = ($carimg_list->SearchWhere <> "") ? " show" : " show"; ?>
<div id="fcarimglistsrch-search-panel" class="ew-search-panel collapse<?php echo $searchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="carimg">
	<div class="ew-basic-search">
<div id="xsr_1" class="ew-row d-sm-flex">
	<div class="ew-quick-search input-group">
		<input type="text" name="<?php echo TABLE_BASIC_SEARCH ?>" id="<?php echo TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo HtmlEncode($carimg_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo HtmlEncode($Language->phrase("Search")) ?>">
		<input type="hidden" name="<?php echo TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo HtmlEncode($carimg_list->BasicSearch->getType()) ?>">
		<div class="input-group-append">
			<button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?php echo $Language->phrase("SearchBtn") ?></button>
			<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false"><span id="searchtype"><?php echo $carimg_list->BasicSearch->getTypeNameShort() ?></span></button>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item<?php if ($carimg_list->BasicSearch->getType() == "") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this)"><?php echo $Language->phrase("QuickSearchAuto") ?></a>
				<a class="dropdown-item<?php if ($carimg_list->BasicSearch->getType() == "=") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'=')"><?php echo $Language->phrase("QuickSearchExact") ?></a>
				<a class="dropdown-item<?php if ($carimg_list->BasicSearch->getType() == "AND") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'AND')"><?php echo $Language->phrase("QuickSearchAll") ?></a>
				<a class="dropdown-item<?php if ($carimg_list->BasicSearch->getType() == "OR") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'OR')"><?php echo $Language->phrase("QuickSearchAny") ?></a>
			</div>
		</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $carimg_list->showPageHeader(); ?>
<?php
$carimg_list->showMessage();
?>
<?php if ($carimg_list->TotalRecs > 0 || $carimg->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($carimg_list->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> carimg">
<?php if (!$carimg->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$carimg->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?php echo CurrentPageName() ?>">
<?php if (!isset($carimg_list->Pager)) $carimg_list->Pager = new PrevNextPager($carimg_list->StartRec, $carimg_list->DisplayRecs, $carimg_list->TotalRecs, $carimg_list->AutoHidePager) ?>
<?php if ($carimg_list->Pager->RecordCount > 0 && $carimg_list->Pager->Visible) { ?>
<div class="ew-pager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ew-prev-next"><div class="input-group input-group-sm">
<div class="input-group-prepend">
<!-- first page button -->
	<?php if ($carimg_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerFirst") ?>" href="<?php echo $carimg_list->pageUrl() ?>start=<?php echo $carimg_list->Pager->FirstButton->Start ?>"><i class="icon-first ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerFirst") ?>"><i class="icon-first ew-icon"></i></a>
	<?php } ?>
<!-- previous page button -->
	<?php if ($carimg_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerPrevious") ?>" href="<?php echo $carimg_list->pageUrl() ?>start=<?php echo $carimg_list->Pager->PrevButton->Start ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerPrevious") ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } ?>
</div>
<!-- current page number -->
	<input class="form-control" type="text" name="<?php echo TABLE_PAGE_NO ?>" value="<?php echo $carimg_list->Pager->CurrentPage ?>">
<div class="input-group-append">
<!-- next page button -->
	<?php if ($carimg_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerNext") ?>" href="<?php echo $carimg_list->pageUrl() ?>start=<?php echo $carimg_list->Pager->NextButton->Start ?>"><i class="icon-next ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerNext") ?>"><i class="icon-next ew-icon"></i></a>
	<?php } ?>
<!-- last page button -->
	<?php if ($carimg_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerLast") ?>" href="<?php echo $carimg_list->pageUrl() ?>start=<?php echo $carimg_list->Pager->LastButton->Start ?>"><i class="icon-last ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerLast") ?>"><i class="icon-last ew-icon"></i></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $carimg_list->Pager->PageCount ?></span>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($carimg_list->Pager->RecordCount > 0) { ?>
<div class="ew-pager ew-rec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $carimg_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $carimg_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $carimg_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $carimg_list->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fcarimglist" id="fcarimglist" class="form-inline ew-form ew-list-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($carimg_list->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $carimg_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="carimg">
<div id="gmp_carimg" class="<?php if (IsResponsiveLayout()) { ?>table-responsive <?php } ?>card-body ew-grid-middle-panel">
<?php if ($carimg_list->TotalRecs > 0 || $carimg->isAdd() || $carimg->isCopy() || $carimg->isGridEdit()) { ?>
<table id="tbl_carimglist" class="table ew-table"><!-- .ew-table ##-->
<thead>
	<tr class="ew-table-header">
<?php

// Header row
$carimg_list->RowType = ROWTYPE_HEADER;

// Render list options
$carimg_list->renderListOptions();

// Render list options (header, left)
$carimg_list->ListOptions->render("header", "left");
?>
<?php if ($carimg->id->Visible) { // id ?>
	<?php if ($carimg->sortUrl($carimg->id) == "") { ?>
		<th data-name="id" class="<?php echo $carimg->id->headerCellClass() ?>"><div id="elh_carimg_id" class="carimg_id"><div class="ew-table-header-caption"><?php echo $carimg->id->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $carimg->id->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event,'<?php echo $carimg->SortUrl($carimg->id) ?>',1);"><div id="elh_carimg_id" class="carimg_id">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $carimg->id->caption() ?></span><span class="ew-table-header-sort"><?php if ($carimg->id->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($carimg->id->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($carimg->num_slide->Visible) { // num_slide ?>
	<?php if ($carimg->sortUrl($carimg->num_slide) == "") { ?>
		<th data-name="num_slide" class="<?php echo $carimg->num_slide->headerCellClass() ?>"><div id="elh_carimg_num_slide" class="carimg_num_slide"><div class="ew-table-header-caption"><?php echo $carimg->num_slide->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="num_slide" class="<?php echo $carimg->num_slide->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event,'<?php echo $carimg->SortUrl($carimg->num_slide) ?>',1);"><div id="elh_carimg_num_slide" class="carimg_num_slide">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $carimg->num_slide->caption() ?></span><span class="ew-table-header-sort"><?php if ($carimg->num_slide->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($carimg->num_slide->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($carimg->slide_src->Visible) { // slide_src ?>
	<?php if ($carimg->sortUrl($carimg->slide_src) == "") { ?>
		<th data-name="slide_src" class="<?php echo $carimg->slide_src->headerCellClass() ?>"><div id="elh_carimg_slide_src" class="carimg_slide_src"><div class="ew-table-header-caption"><?php echo $carimg->slide_src->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="slide_src" class="<?php echo $carimg->slide_src->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event,'<?php echo $carimg->SortUrl($carimg->slide_src) ?>',1);"><div id="elh_carimg_slide_src" class="carimg_slide_src">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $carimg->slide_src->caption() ?><?php echo $Language->phrase("SrchLegend") ?></span><span class="ew-table-header-sort"><?php if ($carimg->slide_src->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($carimg->slide_src->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$carimg_list->ListOptions->render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($carimg->isAdd() || $carimg->isCopy()) {
		$carimg_list->RowIndex = 0;
		$carimg_list->KeyCount = $carimg_list->RowIndex;
		if ($carimg->isCopy() && !$carimg_list->loadRow())
			$carimg->CurrentAction = "add";
		if ($carimg->isAdd())
			$carimg_list->loadRowValues();
		if ($carimg->EventCancelled) // Insert failed
			$carimg_list->restoreFormValues(); // Restore form values

		// Set row properties
		$carimg->resetAttributes();
		$carimg->RowAttrs = array_merge($carimg->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_carimg', 'data-rowtype'=>ROWTYPE_ADD));
		$carimg->RowType = ROWTYPE_ADD;

		// Render row
		$carimg_list->renderRow();

		// Render list options
		$carimg_list->renderListOptions();
		$carimg_list->StartRowCnt = 0;
?>
	<tr<?php echo $carimg->rowAttributes() ?>>
<?php

// Render list options (body, left)
$carimg_list->ListOptions->render("body", "left", $carimg_list->RowCnt);
?>
	<?php if ($carimg->id->Visible) { // id ?>
		<td data-name="id">
<input type="hidden" data-table="carimg" data-field="x_id" name="o<?php echo $carimg_list->RowIndex ?>_id" id="o<?php echo $carimg_list->RowIndex ?>_id" value="<?php echo HtmlEncode($carimg->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($carimg->num_slide->Visible) { // num_slide ?>
		<td data-name="num_slide">
<span id="el<?php echo $carimg_list->RowCnt ?>_carimg_num_slide" class="form-group carimg_num_slide">
<input type="text" data-table="carimg" data-field="x_num_slide" name="x<?php echo $carimg_list->RowIndex ?>_num_slide" id="x<?php echo $carimg_list->RowIndex ?>_num_slide" size="30" placeholder="<?php echo HtmlEncode($carimg->num_slide->getPlaceHolder()) ?>" value="<?php echo $carimg->num_slide->EditValue ?>"<?php echo $carimg->num_slide->editAttributes() ?>>
</span>
<input type="hidden" data-table="carimg" data-field="x_num_slide" name="o<?php echo $carimg_list->RowIndex ?>_num_slide" id="o<?php echo $carimg_list->RowIndex ?>_num_slide" value="<?php echo HtmlEncode($carimg->num_slide->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($carimg->slide_src->Visible) { // slide_src ?>
		<td data-name="slide_src">
<span id="el<?php echo $carimg_list->RowCnt ?>_carimg_slide_src" class="form-group carimg_slide_src">
<div id="fd_x<?php echo $carimg_list->RowIndex ?>_slide_src">
<span title="<?php echo $carimg->slide_src->title() ? $carimg->slide_src->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($carimg->slide_src->ReadOnly || $carimg->slide_src->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="carimg" data-field="x_slide_src" name="x<?php echo $carimg_list->RowIndex ?>_slide_src" id="x<?php echo $carimg_list->RowIndex ?>_slide_src"<?php echo $carimg->slide_src->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fn_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo $carimg->slide_src->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fa_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="0">
<input type="hidden" name="fs_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fs_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="200">
<input type="hidden" name="fx_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fx_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo $carimg->slide_src->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fm_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo $carimg->slide_src->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $carimg_list->RowIndex ?>_slide_src" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="carimg" data-field="x_slide_src" name="o<?php echo $carimg_list->RowIndex ?>_slide_src" id="o<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo HtmlEncode($carimg->slide_src->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$carimg_list->ListOptions->render("body", "right", $carimg_list->RowCnt);
?>
<script>
fcarimglist.updateLists(<?php echo $carimg_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($carimg->ExportAll && $carimg->isExport()) {
	$carimg_list->StopRec = $carimg_list->TotalRecs;
} else {

	// Set the last record to display
	if ($carimg_list->TotalRecs > $carimg_list->StartRec + $carimg_list->DisplayRecs - 1)
		$carimg_list->StopRec = $carimg_list->StartRec + $carimg_list->DisplayRecs - 1;
	else
		$carimg_list->StopRec = $carimg_list->TotalRecs;
}

// Restore number of post back records
if ($CurrentForm && $carimg_list->EventCancelled) {
	$CurrentForm->Index = -1;
	if ($CurrentForm->hasValue($carimg_list->FormKeyCountName) && ($carimg->isGridAdd() || $carimg->isGridEdit() || $carimg->isConfirm())) {
		$carimg_list->KeyCount = $CurrentForm->getValue($carimg_list->FormKeyCountName);
		$carimg_list->StopRec = $carimg_list->StartRec + $carimg_list->KeyCount - 1;
	}
}
$carimg_list->RecCnt = $carimg_list->StartRec - 1;
if ($carimg_list->Recordset && !$carimg_list->Recordset->EOF) {
	$carimg_list->Recordset->moveFirst();
	$selectLimit = $carimg_list->UseSelectLimit;
	if (!$selectLimit && $carimg_list->StartRec > 1)
		$carimg_list->Recordset->move($carimg_list->StartRec - 1);
} elseif (!$carimg->AllowAddDeleteRow && $carimg_list->StopRec == 0) {
	$carimg_list->StopRec = $carimg->GridAddRowCount;
}

// Initialize aggregate
$carimg->RowType = ROWTYPE_AGGREGATEINIT;
$carimg->resetAttributes();
$carimg_list->renderRow();
$carimg_list->EditRowCnt = 0;
if ($carimg->isEdit())
	$carimg_list->RowIndex = 1;
if ($carimg->isGridAdd())
	$carimg_list->RowIndex = 0;
if ($carimg->isGridEdit())
	$carimg_list->RowIndex = 0;
while ($carimg_list->RecCnt < $carimg_list->StopRec) {
	$carimg_list->RecCnt++;
	if ($carimg_list->RecCnt >= $carimg_list->StartRec) {
		$carimg_list->RowCnt++;
		if ($carimg->isGridAdd() || $carimg->isGridEdit() || $carimg->isConfirm()) {
			$carimg_list->RowIndex++;
			$CurrentForm->Index = $carimg_list->RowIndex;
			if ($CurrentForm->hasValue($carimg_list->FormActionName) && $carimg_list->EventCancelled)
				$carimg_list->RowAction = strval($CurrentForm->getValue($carimg_list->FormActionName));
			elseif ($carimg->isGridAdd())
				$carimg_list->RowAction = "insert";
			else
				$carimg_list->RowAction = "";
		}

		// Set up key count
		$carimg_list->KeyCount = $carimg_list->RowIndex;

		// Init row class and style
		$carimg->resetAttributes();
		$carimg->CssClass = "";
		if ($carimg->isGridAdd()) {
			$carimg_list->loadRowValues(); // Load default values
		} else {
			$carimg_list->loadRowValues($carimg_list->Recordset); // Load row values
		}
		$carimg->RowType = ROWTYPE_VIEW; // Render view
		if ($carimg->isGridAdd()) // Grid add
			$carimg->RowType = ROWTYPE_ADD; // Render add
		if ($carimg->isGridAdd() && $carimg->EventCancelled && !$CurrentForm->hasValue("k_blankrow")) // Insert failed
			$carimg_list->restoreCurrentRowFormValues($carimg_list->RowIndex); // Restore form values
		if ($carimg->isEdit()) {
			if ($carimg_list->checkInlineEditKey() && $carimg_list->EditRowCnt == 0) { // Inline edit
				$carimg->RowType = ROWTYPE_EDIT; // Render edit
			}
		}
		if ($carimg->isGridEdit()) { // Grid edit
			if ($carimg->EventCancelled)
				$carimg_list->restoreCurrentRowFormValues($carimg_list->RowIndex); // Restore form values
			if ($carimg_list->RowAction == "insert")
				$carimg->RowType = ROWTYPE_ADD; // Render add
			else
				$carimg->RowType = ROWTYPE_EDIT; // Render edit
		}
		if ($carimg->isEdit() && $carimg->RowType == ROWTYPE_EDIT && $carimg->EventCancelled) { // Update failed
			$CurrentForm->Index = 1;
			$carimg_list->restoreFormValues(); // Restore form values
		}
		if ($carimg->isGridEdit() && ($carimg->RowType == ROWTYPE_EDIT || $carimg->RowType == ROWTYPE_ADD) && $carimg->EventCancelled) // Update failed
			$carimg_list->restoreCurrentRowFormValues($carimg_list->RowIndex); // Restore form values
		if ($carimg->RowType == ROWTYPE_EDIT) // Edit row
			$carimg_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$carimg->RowAttrs = array_merge($carimg->RowAttrs, array('data-rowindex'=>$carimg_list->RowCnt, 'id'=>'r' . $carimg_list->RowCnt . '_carimg', 'data-rowtype'=>$carimg->RowType));

		// Render row
		$carimg_list->renderRow();

		// Render list options
		$carimg_list->renderListOptions();

		// Skip delete row / empty row for confirm page
		if ($carimg_list->RowAction <> "delete" && $carimg_list->RowAction <> "insertdelete" && !($carimg_list->RowAction == "insert" && $carimg->isConfirm() && $carimg_list->emptyRow())) {
?>
	<tr<?php echo $carimg->rowAttributes() ?>>
<?php

// Render list options (body, left)
$carimg_list->ListOptions->render("body", "left", $carimg_list->RowCnt);
?>
	<?php if ($carimg->id->Visible) { // id ?>
		<td data-name="id"<?php echo $carimg->id->cellAttributes() ?>>
<?php if ($carimg->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="carimg" data-field="x_id" name="o<?php echo $carimg_list->RowIndex ?>_id" id="o<?php echo $carimg_list->RowIndex ?>_id" value="<?php echo HtmlEncode($carimg->id->OldValue) ?>">
<?php } ?>
<?php if ($carimg->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($carimg->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $carimg_list->RowCnt ?>_carimg_id" class="carimg_id">
<span<?php echo $carimg->id->viewAttributes() ?>>
<?php echo $carimg->id->getViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($carimg->num_slide->Visible) { // num_slide ?>
		<td data-name="num_slide"<?php echo $carimg->num_slide->cellAttributes() ?>>
<?php if ($carimg->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $carimg_list->RowCnt ?>_carimg_num_slide" class="form-group carimg_num_slide">
<input type="text" data-table="carimg" data-field="x_num_slide" name="x<?php echo $carimg_list->RowIndex ?>_num_slide" id="x<?php echo $carimg_list->RowIndex ?>_num_slide" size="30" placeholder="<?php echo HtmlEncode($carimg->num_slide->getPlaceHolder()) ?>" value="<?php echo $carimg->num_slide->EditValue ?>"<?php echo $carimg->num_slide->editAttributes() ?>>
</span>
<input type="hidden" data-table="carimg" data-field="x_num_slide" name="o<?php echo $carimg_list->RowIndex ?>_num_slide" id="o<?php echo $carimg_list->RowIndex ?>_num_slide" value="<?php echo HtmlEncode($carimg->num_slide->OldValue) ?>">
<?php } ?>
<?php if ($carimg->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $carimg_list->RowCnt ?>_carimg_num_slide" class="form-group carimg_num_slide">
<input type="text" data-table="carimg" data-field="x_num_slide" name="x<?php echo $carimg_list->RowIndex ?>_num_slide" id="x<?php echo $carimg_list->RowIndex ?>_num_slide" size="30" placeholder="<?php echo HtmlEncode($carimg->num_slide->getPlaceHolder()) ?>" value="<?php echo $carimg->num_slide->EditValue ?>"<?php echo $carimg->num_slide->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($carimg->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $carimg_list->RowCnt ?>_carimg_num_slide" class="carimg_num_slide">
<span<?php echo $carimg->num_slide->viewAttributes() ?>>
<?php echo $carimg->num_slide->getViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($carimg->slide_src->Visible) { // slide_src ?>
		<td data-name="slide_src"<?php echo $carimg->slide_src->cellAttributes() ?>>
<?php if ($carimg->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $carimg_list->RowCnt ?>_carimg_slide_src" class="form-group carimg_slide_src">
<div id="fd_x<?php echo $carimg_list->RowIndex ?>_slide_src">
<span title="<?php echo $carimg->slide_src->title() ? $carimg->slide_src->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($carimg->slide_src->ReadOnly || $carimg->slide_src->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="carimg" data-field="x_slide_src" name="x<?php echo $carimg_list->RowIndex ?>_slide_src" id="x<?php echo $carimg_list->RowIndex ?>_slide_src"<?php echo $carimg->slide_src->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fn_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo $carimg->slide_src->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fa_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="0">
<input type="hidden" name="fs_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fs_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="200">
<input type="hidden" name="fx_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fx_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo $carimg->slide_src->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fm_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo $carimg->slide_src->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $carimg_list->RowIndex ?>_slide_src" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="carimg" data-field="x_slide_src" name="o<?php echo $carimg_list->RowIndex ?>_slide_src" id="o<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo HtmlEncode($carimg->slide_src->OldValue) ?>">
<?php } ?>
<?php if ($carimg->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $carimg_list->RowCnt ?>_carimg_slide_src" class="form-group carimg_slide_src">
<div id="fd_x<?php echo $carimg_list->RowIndex ?>_slide_src">
<span title="<?php echo $carimg->slide_src->title() ? $carimg->slide_src->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($carimg->slide_src->ReadOnly || $carimg->slide_src->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="carimg" data-field="x_slide_src" name="x<?php echo $carimg_list->RowIndex ?>_slide_src" id="x<?php echo $carimg_list->RowIndex ?>_slide_src"<?php echo $carimg->slide_src->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fn_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo $carimg->slide_src->Upload->FileName ?>">
<?php if (Post("fa_x<?php echo $carimg_list->RowIndex ?>_slide_src") == "0") { ?>
<input type="hidden" name="fa_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fa_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fa_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fs_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="200">
<input type="hidden" name="fx_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fx_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo $carimg->slide_src->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fm_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo $carimg->slide_src->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $carimg_list->RowIndex ?>_slide_src" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php if ($carimg->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $carimg_list->RowCnt ?>_carimg_slide_src" class="carimg_slide_src">
<span>
<?php echo GetFileViewTag($carimg->slide_src, $carimg->slide_src->getViewValue()) ?>
</span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$carimg_list->ListOptions->render("body", "right", $carimg_list->RowCnt);
?>
	</tr>
<?php if ($carimg->RowType == ROWTYPE_ADD || $carimg->RowType == ROWTYPE_EDIT) { ?>
<script>
fcarimglist.updateLists(<?php echo $carimg_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if (!$carimg->isGridAdd())
		if (!$carimg_list->Recordset->EOF)
			$carimg_list->Recordset->moveNext();
}
?>
<?php
	if ($carimg->isGridAdd() || $carimg->isGridEdit()) {
		$carimg_list->RowIndex = '$rowindex$';
		$carimg_list->loadRowValues();

		// Set row properties
		$carimg->resetAttributes();
		$carimg->RowAttrs = array_merge($carimg->RowAttrs, array('data-rowindex'=>$carimg_list->RowIndex, 'id'=>'r0_carimg', 'data-rowtype'=>ROWTYPE_ADD));
		AppendClass($carimg->RowAttrs["class"], "ew-template");
		$carimg->RowType = ROWTYPE_ADD;

		// Render row
		$carimg_list->renderRow();

		// Render list options
		$carimg_list->renderListOptions();
		$carimg_list->StartRowCnt = 0;
?>
	<tr<?php echo $carimg->rowAttributes() ?>>
<?php

// Render list options (body, left)
$carimg_list->ListOptions->render("body", "left", $carimg_list->RowIndex);
?>
	<?php if ($carimg->id->Visible) { // id ?>
		<td data-name="id">
<input type="hidden" data-table="carimg" data-field="x_id" name="o<?php echo $carimg_list->RowIndex ?>_id" id="o<?php echo $carimg_list->RowIndex ?>_id" value="<?php echo HtmlEncode($carimg->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($carimg->num_slide->Visible) { // num_slide ?>
		<td data-name="num_slide">
<span id="el$rowindex$_carimg_num_slide" class="form-group carimg_num_slide">
<input type="text" data-table="carimg" data-field="x_num_slide" name="x<?php echo $carimg_list->RowIndex ?>_num_slide" id="x<?php echo $carimg_list->RowIndex ?>_num_slide" size="30" placeholder="<?php echo HtmlEncode($carimg->num_slide->getPlaceHolder()) ?>" value="<?php echo $carimg->num_slide->EditValue ?>"<?php echo $carimg->num_slide->editAttributes() ?>>
</span>
<input type="hidden" data-table="carimg" data-field="x_num_slide" name="o<?php echo $carimg_list->RowIndex ?>_num_slide" id="o<?php echo $carimg_list->RowIndex ?>_num_slide" value="<?php echo HtmlEncode($carimg->num_slide->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($carimg->slide_src->Visible) { // slide_src ?>
		<td data-name="slide_src">
<span id="el$rowindex$_carimg_slide_src" class="form-group carimg_slide_src">
<div id="fd_x<?php echo $carimg_list->RowIndex ?>_slide_src">
<span title="<?php echo $carimg->slide_src->title() ? $carimg->slide_src->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($carimg->slide_src->ReadOnly || $carimg->slide_src->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="carimg" data-field="x_slide_src" name="x<?php echo $carimg_list->RowIndex ?>_slide_src" id="x<?php echo $carimg_list->RowIndex ?>_slide_src"<?php echo $carimg->slide_src->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fn_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo $carimg->slide_src->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fa_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="0">
<input type="hidden" name="fs_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fs_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="200">
<input type="hidden" name="fx_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fx_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo $carimg->slide_src->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $carimg_list->RowIndex ?>_slide_src" id= "fm_x<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo $carimg->slide_src->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $carimg_list->RowIndex ?>_slide_src" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="carimg" data-field="x_slide_src" name="o<?php echo $carimg_list->RowIndex ?>_slide_src" id="o<?php echo $carimg_list->RowIndex ?>_slide_src" value="<?php echo HtmlEncode($carimg->slide_src->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$carimg_list->ListOptions->render("body", "right", $carimg_list->RowIndex);
?>
<script>
fcarimglist.updateLists(<?php echo $carimg_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
<?php if ($carimg->isAdd() || $carimg->isCopy()) { ?>
<input type="hidden" name="<?php echo $carimg_list->FormKeyCountName ?>" id="<?php echo $carimg_list->FormKeyCountName ?>" value="<?php echo $carimg_list->KeyCount ?>">
<?php } ?>
<?php if ($carimg->isGridAdd()) { ?>
<input type="hidden" name="action" id="action" value="gridinsert">
<input type="hidden" name="<?php echo $carimg_list->FormKeyCountName ?>" id="<?php echo $carimg_list->FormKeyCountName ?>" value="<?php echo $carimg_list->KeyCount ?>">
<?php echo $carimg_list->MultiSelectKey ?>
<?php } ?>
<?php if ($carimg->isEdit()) { ?>
<input type="hidden" name="<?php echo $carimg_list->FormKeyCountName ?>" id="<?php echo $carimg_list->FormKeyCountName ?>" value="<?php echo $carimg_list->KeyCount ?>">
<?php } ?>
<?php if ($carimg->isGridEdit()) { ?>
<input type="hidden" name="action" id="action" value="gridupdate">
<input type="hidden" name="<?php echo $carimg_list->FormKeyCountName ?>" id="<?php echo $carimg_list->FormKeyCountName ?>" value="<?php echo $carimg_list->KeyCount ?>">
<?php echo $carimg_list->MultiSelectKey ?>
<?php } ?>
<?php if (!$carimg->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
</form><!-- /.ew-list-form -->
<?php

// Close recordset
if ($carimg_list->Recordset)
	$carimg_list->Recordset->Close();
?>
<?php if (!$carimg->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$carimg->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?php echo CurrentPageName() ?>">
<?php if (!isset($carimg_list->Pager)) $carimg_list->Pager = new PrevNextPager($carimg_list->StartRec, $carimg_list->DisplayRecs, $carimg_list->TotalRecs, $carimg_list->AutoHidePager) ?>
<?php if ($carimg_list->Pager->RecordCount > 0 && $carimg_list->Pager->Visible) { ?>
<div class="ew-pager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ew-prev-next"><div class="input-group input-group-sm">
<div class="input-group-prepend">
<!-- first page button -->
	<?php if ($carimg_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerFirst") ?>" href="<?php echo $carimg_list->pageUrl() ?>start=<?php echo $carimg_list->Pager->FirstButton->Start ?>"><i class="icon-first ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerFirst") ?>"><i class="icon-first ew-icon"></i></a>
	<?php } ?>
<!-- previous page button -->
	<?php if ($carimg_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerPrevious") ?>" href="<?php echo $carimg_list->pageUrl() ?>start=<?php echo $carimg_list->Pager->PrevButton->Start ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerPrevious") ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } ?>
</div>
<!-- current page number -->
	<input class="form-control" type="text" name="<?php echo TABLE_PAGE_NO ?>" value="<?php echo $carimg_list->Pager->CurrentPage ?>">
<div class="input-group-append">
<!-- next page button -->
	<?php if ($carimg_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerNext") ?>" href="<?php echo $carimg_list->pageUrl() ?>start=<?php echo $carimg_list->Pager->NextButton->Start ?>"><i class="icon-next ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerNext") ?>"><i class="icon-next ew-icon"></i></a>
	<?php } ?>
<!-- last page button -->
	<?php if ($carimg_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerLast") ?>" href="<?php echo $carimg_list->pageUrl() ?>start=<?php echo $carimg_list->Pager->LastButton->Start ?>"><i class="icon-last ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerLast") ?>"><i class="icon-last ew-icon"></i></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $carimg_list->Pager->PageCount ?></span>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($carimg_list->Pager->RecordCount > 0) { ?>
<div class="ew-pager ew-rec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $carimg_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $carimg_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $carimg_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $carimg_list->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($carimg_list->TotalRecs == 0 && !$carimg->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $carimg_list->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php
$carimg_list->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<?php if (!$carimg->isExport()) { ?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$carimg_list->terminate();
?>