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
$cars_list = new cars_list();

// Run the page
$cars_list->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cars_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if (!$cars->isExport()) { ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "list";
var fcarslist = currentForm = new ew.Form("fcarslist", "list");
fcarslist.formKeyCountName = '<?php echo $cars_list->FormKeyCountName ?>';

// Validate form
fcarslist.validate = function() {
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
		<?php if ($cars_list->id->Required) { ?>
			elm = this.getElements("x" + infix + "_id");
			if (elm && !ew.isHidden(elm) && !ew.hasValue(elm))
				return this.onError(elm, "<?php echo JsEncode(str_replace("%s", $cars->id->caption(), $cars->id->RequiredErrorMessage)) ?>");
		<?php } ?>
		<?php if ($cars_list->car_img->Required) { ?>
			felm = this.getElements("x" + infix + "_car_img");
			elm = this.getElements("fn_x" + infix + "_car_img");
			if (felm && elm && !ew.hasValue(elm))
				return this.onError(felm, "<?php echo JsEncode(str_replace("%s", $cars->car_img->caption(), $cars->car_img->RequiredErrorMessage)) ?>");
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
fcarslist.emptyRow = function(infix) {
	var fobj = this._form;
	if (ew.valueChanged(fobj, infix, "car_img", false)) return false;
	return true;
}

// Form_CustomValidate event
fcarslist.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fcarslist.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var fcarslistsrch = currentSearchForm = new ew.Form("fcarslistsrch");

// Filters
fcarslistsrch.filterList = <?php echo $cars_list->getFilterList() ?>;
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
<?php if (!$cars->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($cars_list->TotalRecs > 0 && $cars_list->ExportOptions->visible()) { ?>
<?php $cars_list->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($cars_list->ImportOptions->visible()) { ?>
<?php $cars_list->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($cars_list->SearchOptions->visible()) { ?>
<?php $cars_list->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($cars_list->FilterOptions->visible()) { ?>
<?php $cars_list->FilterOptions->render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
$cars_list->renderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if (!$cars->isExport() && !$cars->CurrentAction) { ?>
<form name="fcarslistsrch" id="fcarslistsrch" class="form-inline ew-form ew-ext-search-form" action="<?php echo CurrentPageName() ?>">
<?php $searchPanelClass = ($cars_list->SearchWhere <> "") ? " show" : " show"; ?>
<div id="fcarslistsrch-search-panel" class="ew-search-panel collapse<?php echo $searchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="cars">
	<div class="ew-basic-search">
<div id="xsr_1" class="ew-row d-sm-flex">
	<div class="ew-quick-search input-group">
		<input type="text" name="<?php echo TABLE_BASIC_SEARCH ?>" id="<?php echo TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo HtmlEncode($cars_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo HtmlEncode($Language->phrase("Search")) ?>">
		<input type="hidden" name="<?php echo TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo HtmlEncode($cars_list->BasicSearch->getType()) ?>">
		<div class="input-group-append">
			<button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?php echo $Language->phrase("SearchBtn") ?></button>
			<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false"><span id="searchtype"><?php echo $cars_list->BasicSearch->getTypeNameShort() ?></span></button>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item<?php if ($cars_list->BasicSearch->getType() == "") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this)"><?php echo $Language->phrase("QuickSearchAuto") ?></a>
				<a class="dropdown-item<?php if ($cars_list->BasicSearch->getType() == "=") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'=')"><?php echo $Language->phrase("QuickSearchExact") ?></a>
				<a class="dropdown-item<?php if ($cars_list->BasicSearch->getType() == "AND") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'AND')"><?php echo $Language->phrase("QuickSearchAll") ?></a>
				<a class="dropdown-item<?php if ($cars_list->BasicSearch->getType() == "OR") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'OR')"><?php echo $Language->phrase("QuickSearchAny") ?></a>
			</div>
		</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $cars_list->showPageHeader(); ?>
<?php
$cars_list->showMessage();
?>
<?php if ($cars_list->TotalRecs > 0 || $cars->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($cars_list->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> cars">
<?php if (!$cars->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$cars->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?php echo CurrentPageName() ?>">
<?php if (!isset($cars_list->Pager)) $cars_list->Pager = new PrevNextPager($cars_list->StartRec, $cars_list->DisplayRecs, $cars_list->TotalRecs, $cars_list->AutoHidePager) ?>
<?php if ($cars_list->Pager->RecordCount > 0 && $cars_list->Pager->Visible) { ?>
<div class="ew-pager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ew-prev-next"><div class="input-group input-group-sm">
<div class="input-group-prepend">
<!-- first page button -->
	<?php if ($cars_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerFirst") ?>" href="<?php echo $cars_list->pageUrl() ?>start=<?php echo $cars_list->Pager->FirstButton->Start ?>"><i class="icon-first ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerFirst") ?>"><i class="icon-first ew-icon"></i></a>
	<?php } ?>
<!-- previous page button -->
	<?php if ($cars_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerPrevious") ?>" href="<?php echo $cars_list->pageUrl() ?>start=<?php echo $cars_list->Pager->PrevButton->Start ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerPrevious") ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } ?>
</div>
<!-- current page number -->
	<input class="form-control" type="text" name="<?php echo TABLE_PAGE_NO ?>" value="<?php echo $cars_list->Pager->CurrentPage ?>">
<div class="input-group-append">
<!-- next page button -->
	<?php if ($cars_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerNext") ?>" href="<?php echo $cars_list->pageUrl() ?>start=<?php echo $cars_list->Pager->NextButton->Start ?>"><i class="icon-next ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerNext") ?>"><i class="icon-next ew-icon"></i></a>
	<?php } ?>
<!-- last page button -->
	<?php if ($cars_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerLast") ?>" href="<?php echo $cars_list->pageUrl() ?>start=<?php echo $cars_list->Pager->LastButton->Start ?>"><i class="icon-last ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerLast") ?>"><i class="icon-last ew-icon"></i></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $cars_list->Pager->PageCount ?></span>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($cars_list->Pager->RecordCount > 0) { ?>
<div class="ew-pager ew-rec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $cars_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $cars_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $cars_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $cars_list->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fcarslist" id="fcarslist" class="form-inline ew-form ew-list-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($cars_list->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $cars_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cars">
<div id="gmp_cars" class="<?php if (IsResponsiveLayout()) { ?>table-responsive <?php } ?>card-body ew-grid-middle-panel">
<?php if ($cars_list->TotalRecs > 0 || $cars->isAdd() || $cars->isCopy() || $cars->isGridEdit()) { ?>
<table id="tbl_carslist" class="table ew-table"><!-- .ew-table ##-->
<thead>
	<tr class="ew-table-header">
<?php

// Header row
$cars_list->RowType = ROWTYPE_HEADER;

// Render list options
$cars_list->renderListOptions();

// Render list options (header, left)
$cars_list->ListOptions->render("header", "left");
?>
<?php if ($cars->id->Visible) { // id ?>
	<?php if ($cars->sortUrl($cars->id) == "") { ?>
		<th data-name="id" class="<?php echo $cars->id->headerCellClass() ?>"><div id="elh_cars_id" class="cars_id"><div class="ew-table-header-caption"><?php echo $cars->id->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $cars->id->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event,'<?php echo $cars->SortUrl($cars->id) ?>',1);"><div id="elh_cars_id" class="cars_id">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $cars->id->caption() ?></span><span class="ew-table-header-sort"><?php if ($cars->id->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($cars->id->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($cars->car_img->Visible) { // car_img ?>
	<?php if ($cars->sortUrl($cars->car_img) == "") { ?>
		<th data-name="car_img" class="<?php echo $cars->car_img->headerCellClass() ?>"><div id="elh_cars_car_img" class="cars_car_img"><div class="ew-table-header-caption"><?php echo $cars->car_img->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="car_img" class="<?php echo $cars->car_img->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event,'<?php echo $cars->SortUrl($cars->car_img) ?>',1);"><div id="elh_cars_car_img" class="cars_car_img">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $cars->car_img->caption() ?><?php echo $Language->phrase("SrchLegend") ?></span><span class="ew-table-header-sort"><?php if ($cars->car_img->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($cars->car_img->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$cars_list->ListOptions->render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($cars->isAdd() || $cars->isCopy()) {
		$cars_list->RowIndex = 0;
		$cars_list->KeyCount = $cars_list->RowIndex;
		if ($cars->isCopy() && !$cars_list->loadRow())
			$cars->CurrentAction = "add";
		if ($cars->isAdd())
			$cars_list->loadRowValues();
		if ($cars->EventCancelled) // Insert failed
			$cars_list->restoreFormValues(); // Restore form values

		// Set row properties
		$cars->resetAttributes();
		$cars->RowAttrs = array_merge($cars->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_cars', 'data-rowtype'=>ROWTYPE_ADD));
		$cars->RowType = ROWTYPE_ADD;

		// Render row
		$cars_list->renderRow();

		// Render list options
		$cars_list->renderListOptions();
		$cars_list->StartRowCnt = 0;
?>
	<tr<?php echo $cars->rowAttributes() ?>>
<?php

// Render list options (body, left)
$cars_list->ListOptions->render("body", "left", $cars_list->RowCnt);
?>
	<?php if ($cars->id->Visible) { // id ?>
		<td data-name="id">
<input type="hidden" data-table="cars" data-field="x_id" name="o<?php echo $cars_list->RowIndex ?>_id" id="o<?php echo $cars_list->RowIndex ?>_id" value="<?php echo HtmlEncode($cars->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cars->car_img->Visible) { // car_img ?>
		<td data-name="car_img">
<span id="el<?php echo $cars_list->RowCnt ?>_cars_car_img" class="form-group cars_car_img">
<div id="fd_x<?php echo $cars_list->RowIndex ?>_car_img">
<span title="<?php echo $cars->car_img->title() ? $cars->car_img->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($cars->car_img->ReadOnly || $cars->car_img->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cars" data-field="x_car_img" name="x<?php echo $cars_list->RowIndex ?>_car_img" id="x<?php echo $cars_list->RowIndex ?>_car_img"<?php echo $cars->car_img->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fn_x<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo $cars->car_img->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fa_x<?php echo $cars_list->RowIndex ?>_car_img" value="0">
<input type="hidden" name="fs_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fs_x<?php echo $cars_list->RowIndex ?>_car_img" value="200">
<input type="hidden" name="fx_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fx_x<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo $cars->car_img->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fm_x<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo $cars->car_img->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $cars_list->RowIndex ?>_car_img" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="cars" data-field="x_car_img" name="o<?php echo $cars_list->RowIndex ?>_car_img" id="o<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo HtmlEncode($cars->car_img->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$cars_list->ListOptions->render("body", "right", $cars_list->RowCnt);
?>
<script>
fcarslist.updateLists(<?php echo $cars_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($cars->ExportAll && $cars->isExport()) {
	$cars_list->StopRec = $cars_list->TotalRecs;
} else {

	// Set the last record to display
	if ($cars_list->TotalRecs > $cars_list->StartRec + $cars_list->DisplayRecs - 1)
		$cars_list->StopRec = $cars_list->StartRec + $cars_list->DisplayRecs - 1;
	else
		$cars_list->StopRec = $cars_list->TotalRecs;
}

// Restore number of post back records
if ($CurrentForm && $cars_list->EventCancelled) {
	$CurrentForm->Index = -1;
	if ($CurrentForm->hasValue($cars_list->FormKeyCountName) && ($cars->isGridAdd() || $cars->isGridEdit() || $cars->isConfirm())) {
		$cars_list->KeyCount = $CurrentForm->getValue($cars_list->FormKeyCountName);
		$cars_list->StopRec = $cars_list->StartRec + $cars_list->KeyCount - 1;
	}
}
$cars_list->RecCnt = $cars_list->StartRec - 1;
if ($cars_list->Recordset && !$cars_list->Recordset->EOF) {
	$cars_list->Recordset->moveFirst();
	$selectLimit = $cars_list->UseSelectLimit;
	if (!$selectLimit && $cars_list->StartRec > 1)
		$cars_list->Recordset->move($cars_list->StartRec - 1);
} elseif (!$cars->AllowAddDeleteRow && $cars_list->StopRec == 0) {
	$cars_list->StopRec = $cars->GridAddRowCount;
}

// Initialize aggregate
$cars->RowType = ROWTYPE_AGGREGATEINIT;
$cars->resetAttributes();
$cars_list->renderRow();
$cars_list->EditRowCnt = 0;
if ($cars->isEdit())
	$cars_list->RowIndex = 1;
if ($cars->isGridAdd())
	$cars_list->RowIndex = 0;
if ($cars->isGridEdit())
	$cars_list->RowIndex = 0;
while ($cars_list->RecCnt < $cars_list->StopRec) {
	$cars_list->RecCnt++;
	if ($cars_list->RecCnt >= $cars_list->StartRec) {
		$cars_list->RowCnt++;
		if ($cars->isGridAdd() || $cars->isGridEdit() || $cars->isConfirm()) {
			$cars_list->RowIndex++;
			$CurrentForm->Index = $cars_list->RowIndex;
			if ($CurrentForm->hasValue($cars_list->FormActionName) && $cars_list->EventCancelled)
				$cars_list->RowAction = strval($CurrentForm->getValue($cars_list->FormActionName));
			elseif ($cars->isGridAdd())
				$cars_list->RowAction = "insert";
			else
				$cars_list->RowAction = "";
		}

		// Set up key count
		$cars_list->KeyCount = $cars_list->RowIndex;

		// Init row class and style
		$cars->resetAttributes();
		$cars->CssClass = "";
		if ($cars->isGridAdd()) {
			$cars_list->loadRowValues(); // Load default values
		} else {
			$cars_list->loadRowValues($cars_list->Recordset); // Load row values
		}
		$cars->RowType = ROWTYPE_VIEW; // Render view
		if ($cars->isGridAdd()) // Grid add
			$cars->RowType = ROWTYPE_ADD; // Render add
		if ($cars->isGridAdd() && $cars->EventCancelled && !$CurrentForm->hasValue("k_blankrow")) // Insert failed
			$cars_list->restoreCurrentRowFormValues($cars_list->RowIndex); // Restore form values
		if ($cars->isEdit()) {
			if ($cars_list->checkInlineEditKey() && $cars_list->EditRowCnt == 0) { // Inline edit
				$cars->RowType = ROWTYPE_EDIT; // Render edit
			}
		}
		if ($cars->isGridEdit()) { // Grid edit
			if ($cars->EventCancelled)
				$cars_list->restoreCurrentRowFormValues($cars_list->RowIndex); // Restore form values
			if ($cars_list->RowAction == "insert")
				$cars->RowType = ROWTYPE_ADD; // Render add
			else
				$cars->RowType = ROWTYPE_EDIT; // Render edit
		}
		if ($cars->isEdit() && $cars->RowType == ROWTYPE_EDIT && $cars->EventCancelled) { // Update failed
			$CurrentForm->Index = 1;
			$cars_list->restoreFormValues(); // Restore form values
		}
		if ($cars->isGridEdit() && ($cars->RowType == ROWTYPE_EDIT || $cars->RowType == ROWTYPE_ADD) && $cars->EventCancelled) // Update failed
			$cars_list->restoreCurrentRowFormValues($cars_list->RowIndex); // Restore form values
		if ($cars->RowType == ROWTYPE_EDIT) // Edit row
			$cars_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$cars->RowAttrs = array_merge($cars->RowAttrs, array('data-rowindex'=>$cars_list->RowCnt, 'id'=>'r' . $cars_list->RowCnt . '_cars', 'data-rowtype'=>$cars->RowType));

		// Render row
		$cars_list->renderRow();

		// Render list options
		$cars_list->renderListOptions();

		// Skip delete row / empty row for confirm page
		if ($cars_list->RowAction <> "delete" && $cars_list->RowAction <> "insertdelete" && !($cars_list->RowAction == "insert" && $cars->isConfirm() && $cars_list->emptyRow())) {
?>
	<tr<?php echo $cars->rowAttributes() ?>>
<?php

// Render list options (body, left)
$cars_list->ListOptions->render("body", "left", $cars_list->RowCnt);
?>
	<?php if ($cars->id->Visible) { // id ?>
		<td data-name="id"<?php echo $cars->id->cellAttributes() ?>>
<?php if ($cars->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="cars" data-field="x_id" name="o<?php echo $cars_list->RowIndex ?>_id" id="o<?php echo $cars_list->RowIndex ?>_id" value="<?php echo HtmlEncode($cars->id->OldValue) ?>">
<?php } ?>
<?php if ($cars->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cars_list->RowCnt ?>_cars_id" class="form-group cars_id">
<span<?php echo $cars->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?php echo RemoveHtml($cars->id->EditValue) ?>"></span>
</span>
<input type="hidden" data-table="cars" data-field="x_id" name="x<?php echo $cars_list->RowIndex ?>_id" id="x<?php echo $cars_list->RowIndex ?>_id" value="<?php echo HtmlEncode($cars->id->CurrentValue) ?>">
<?php } ?>
<?php if ($cars->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $cars_list->RowCnt ?>_cars_id" class="cars_id">
<span<?php echo $cars->id->viewAttributes() ?>>
<?php echo $cars->id->getViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cars->car_img->Visible) { // car_img ?>
		<td data-name="car_img"<?php echo $cars->car_img->cellAttributes() ?>>
<?php if ($cars->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cars_list->RowCnt ?>_cars_car_img" class="form-group cars_car_img">
<div id="fd_x<?php echo $cars_list->RowIndex ?>_car_img">
<span title="<?php echo $cars->car_img->title() ? $cars->car_img->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($cars->car_img->ReadOnly || $cars->car_img->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cars" data-field="x_car_img" name="x<?php echo $cars_list->RowIndex ?>_car_img" id="x<?php echo $cars_list->RowIndex ?>_car_img"<?php echo $cars->car_img->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fn_x<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo $cars->car_img->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fa_x<?php echo $cars_list->RowIndex ?>_car_img" value="0">
<input type="hidden" name="fs_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fs_x<?php echo $cars_list->RowIndex ?>_car_img" value="200">
<input type="hidden" name="fx_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fx_x<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo $cars->car_img->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fm_x<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo $cars->car_img->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $cars_list->RowIndex ?>_car_img" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="cars" data-field="x_car_img" name="o<?php echo $cars_list->RowIndex ?>_car_img" id="o<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo HtmlEncode($cars->car_img->OldValue) ?>">
<?php } ?>
<?php if ($cars->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cars_list->RowCnt ?>_cars_car_img" class="form-group cars_car_img">
<div id="fd_x<?php echo $cars_list->RowIndex ?>_car_img">
<span title="<?php echo $cars->car_img->title() ? $cars->car_img->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($cars->car_img->ReadOnly || $cars->car_img->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cars" data-field="x_car_img" name="x<?php echo $cars_list->RowIndex ?>_car_img" id="x<?php echo $cars_list->RowIndex ?>_car_img"<?php echo $cars->car_img->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fn_x<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo $cars->car_img->Upload->FileName ?>">
<?php if (Post("fa_x<?php echo $cars_list->RowIndex ?>_car_img") == "0") { ?>
<input type="hidden" name="fa_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fa_x<?php echo $cars_list->RowIndex ?>_car_img" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fa_x<?php echo $cars_list->RowIndex ?>_car_img" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fs_x<?php echo $cars_list->RowIndex ?>_car_img" value="200">
<input type="hidden" name="fx_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fx_x<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo $cars->car_img->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fm_x<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo $cars->car_img->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $cars_list->RowIndex ?>_car_img" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php if ($cars->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $cars_list->RowCnt ?>_cars_car_img" class="cars_car_img">
<span>
<?php echo GetFileViewTag($cars->car_img, $cars->car_img->getViewValue()) ?>
</span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$cars_list->ListOptions->render("body", "right", $cars_list->RowCnt);
?>
	</tr>
<?php if ($cars->RowType == ROWTYPE_ADD || $cars->RowType == ROWTYPE_EDIT) { ?>
<script>
fcarslist.updateLists(<?php echo $cars_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if (!$cars->isGridAdd())
		if (!$cars_list->Recordset->EOF)
			$cars_list->Recordset->moveNext();
}
?>
<?php
	if ($cars->isGridAdd() || $cars->isGridEdit()) {
		$cars_list->RowIndex = '$rowindex$';
		$cars_list->loadRowValues();

		// Set row properties
		$cars->resetAttributes();
		$cars->RowAttrs = array_merge($cars->RowAttrs, array('data-rowindex'=>$cars_list->RowIndex, 'id'=>'r0_cars', 'data-rowtype'=>ROWTYPE_ADD));
		AppendClass($cars->RowAttrs["class"], "ew-template");
		$cars->RowType = ROWTYPE_ADD;

		// Render row
		$cars_list->renderRow();

		// Render list options
		$cars_list->renderListOptions();
		$cars_list->StartRowCnt = 0;
?>
	<tr<?php echo $cars->rowAttributes() ?>>
<?php

// Render list options (body, left)
$cars_list->ListOptions->render("body", "left", $cars_list->RowIndex);
?>
	<?php if ($cars->id->Visible) { // id ?>
		<td data-name="id">
<input type="hidden" data-table="cars" data-field="x_id" name="o<?php echo $cars_list->RowIndex ?>_id" id="o<?php echo $cars_list->RowIndex ?>_id" value="<?php echo HtmlEncode($cars->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cars->car_img->Visible) { // car_img ?>
		<td data-name="car_img">
<span id="el$rowindex$_cars_car_img" class="form-group cars_car_img">
<div id="fd_x<?php echo $cars_list->RowIndex ?>_car_img">
<span title="<?php echo $cars->car_img->title() ? $cars->car_img->title() : $Language->phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ew-tooltip<?php if ($cars->car_img->ReadOnly || $cars->car_img->Disabled) echo " d-none"; ?>">
	<span><?php echo $Language->phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cars" data-field="x_car_img" name="x<?php echo $cars_list->RowIndex ?>_car_img" id="x<?php echo $cars_list->RowIndex ?>_car_img"<?php echo $cars->car_img->editAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fn_x<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo $cars->car_img->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fa_x<?php echo $cars_list->RowIndex ?>_car_img" value="0">
<input type="hidden" name="fs_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fs_x<?php echo $cars_list->RowIndex ?>_car_img" value="200">
<input type="hidden" name="fx_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fx_x<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo $cars->car_img->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $cars_list->RowIndex ?>_car_img" id= "fm_x<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo $cars->car_img->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $cars_list->RowIndex ?>_car_img" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="cars" data-field="x_car_img" name="o<?php echo $cars_list->RowIndex ?>_car_img" id="o<?php echo $cars_list->RowIndex ?>_car_img" value="<?php echo HtmlEncode($cars->car_img->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$cars_list->ListOptions->render("body", "right", $cars_list->RowIndex);
?>
<script>
fcarslist.updateLists(<?php echo $cars_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
<?php if ($cars->isAdd() || $cars->isCopy()) { ?>
<input type="hidden" name="<?php echo $cars_list->FormKeyCountName ?>" id="<?php echo $cars_list->FormKeyCountName ?>" value="<?php echo $cars_list->KeyCount ?>">
<?php } ?>
<?php if ($cars->isGridAdd()) { ?>
<input type="hidden" name="action" id="action" value="gridinsert">
<input type="hidden" name="<?php echo $cars_list->FormKeyCountName ?>" id="<?php echo $cars_list->FormKeyCountName ?>" value="<?php echo $cars_list->KeyCount ?>">
<?php echo $cars_list->MultiSelectKey ?>
<?php } ?>
<?php if ($cars->isEdit()) { ?>
<input type="hidden" name="<?php echo $cars_list->FormKeyCountName ?>" id="<?php echo $cars_list->FormKeyCountName ?>" value="<?php echo $cars_list->KeyCount ?>">
<?php } ?>
<?php if ($cars->isGridEdit()) { ?>
<input type="hidden" name="action" id="action" value="gridupdate">
<input type="hidden" name="<?php echo $cars_list->FormKeyCountName ?>" id="<?php echo $cars_list->FormKeyCountName ?>" value="<?php echo $cars_list->KeyCount ?>">
<?php echo $cars_list->MultiSelectKey ?>
<?php } ?>
<?php if (!$cars->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
</form><!-- /.ew-list-form -->
<?php

// Close recordset
if ($cars_list->Recordset)
	$cars_list->Recordset->Close();
?>
<?php if (!$cars->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$cars->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?php echo CurrentPageName() ?>">
<?php if (!isset($cars_list->Pager)) $cars_list->Pager = new PrevNextPager($cars_list->StartRec, $cars_list->DisplayRecs, $cars_list->TotalRecs, $cars_list->AutoHidePager) ?>
<?php if ($cars_list->Pager->RecordCount > 0 && $cars_list->Pager->Visible) { ?>
<div class="ew-pager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ew-prev-next"><div class="input-group input-group-sm">
<div class="input-group-prepend">
<!-- first page button -->
	<?php if ($cars_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerFirst") ?>" href="<?php echo $cars_list->pageUrl() ?>start=<?php echo $cars_list->Pager->FirstButton->Start ?>"><i class="icon-first ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerFirst") ?>"><i class="icon-first ew-icon"></i></a>
	<?php } ?>
<!-- previous page button -->
	<?php if ($cars_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerPrevious") ?>" href="<?php echo $cars_list->pageUrl() ?>start=<?php echo $cars_list->Pager->PrevButton->Start ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerPrevious") ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } ?>
</div>
<!-- current page number -->
	<input class="form-control" type="text" name="<?php echo TABLE_PAGE_NO ?>" value="<?php echo $cars_list->Pager->CurrentPage ?>">
<div class="input-group-append">
<!-- next page button -->
	<?php if ($cars_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerNext") ?>" href="<?php echo $cars_list->pageUrl() ?>start=<?php echo $cars_list->Pager->NextButton->Start ?>"><i class="icon-next ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerNext") ?>"><i class="icon-next ew-icon"></i></a>
	<?php } ?>
<!-- last page button -->
	<?php if ($cars_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerLast") ?>" href="<?php echo $cars_list->pageUrl() ?>start=<?php echo $cars_list->Pager->LastButton->Start ?>"><i class="icon-last ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerLast") ?>"><i class="icon-last ew-icon"></i></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $cars_list->Pager->PageCount ?></span>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($cars_list->Pager->RecordCount > 0) { ?>
<div class="ew-pager ew-rec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $cars_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $cars_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $cars_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $cars_list->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($cars_list->TotalRecs == 0 && !$cars->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $cars_list->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php
$cars_list->showPageFooter();
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
$cars_list->terminate();
?>