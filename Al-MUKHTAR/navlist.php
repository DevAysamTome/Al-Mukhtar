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
$nav_list = new nav_list();

// Run the page
$nav_list->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$nav_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if (!$nav->isExport()) { ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "list";
var fnavlist = currentForm = new ew.Form("fnavlist", "list");
fnavlist.formKeyCountName = '<?php echo $nav_list->FormKeyCountName ?>';

// Form_CustomValidate event
fnavlist.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fnavlist.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var fnavlistsrch = currentSearchForm = new ew.Form("fnavlistsrch");

// Filters
fnavlistsrch.filterList = <?php echo $nav_list->getFilterList() ?>;
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
<?php if (!$nav->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($nav_list->TotalRecs > 0 && $nav_list->ExportOptions->visible()) { ?>
<?php $nav_list->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($nav_list->ImportOptions->visible()) { ?>
<?php $nav_list->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($nav_list->SearchOptions->visible()) { ?>
<?php $nav_list->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($nav_list->FilterOptions->visible()) { ?>
<?php $nav_list->FilterOptions->render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
$nav_list->renderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if (!$nav->isExport() && !$nav->CurrentAction) { ?>
<form name="fnavlistsrch" id="fnavlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?php echo CurrentPageName() ?>">
<?php $searchPanelClass = ($nav_list->SearchWhere <> "") ? " show" : " show"; ?>
<div id="fnavlistsrch-search-panel" class="ew-search-panel collapse<?php echo $searchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="nav">
	<div class="ew-basic-search">
<div id="xsr_1" class="ew-row d-sm-flex">
	<div class="ew-quick-search input-group">
		<input type="text" name="<?php echo TABLE_BASIC_SEARCH ?>" id="<?php echo TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo HtmlEncode($nav_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo HtmlEncode($Language->phrase("Search")) ?>">
		<input type="hidden" name="<?php echo TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo HtmlEncode($nav_list->BasicSearch->getType()) ?>">
		<div class="input-group-append">
			<button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?php echo $Language->phrase("SearchBtn") ?></button>
			<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false"><span id="searchtype"><?php echo $nav_list->BasicSearch->getTypeNameShort() ?></span></button>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item<?php if ($nav_list->BasicSearch->getType() == "") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this)"><?php echo $Language->phrase("QuickSearchAuto") ?></a>
				<a class="dropdown-item<?php if ($nav_list->BasicSearch->getType() == "=") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'=')"><?php echo $Language->phrase("QuickSearchExact") ?></a>
				<a class="dropdown-item<?php if ($nav_list->BasicSearch->getType() == "AND") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'AND')"><?php echo $Language->phrase("QuickSearchAll") ?></a>
				<a class="dropdown-item<?php if ($nav_list->BasicSearch->getType() == "OR") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'OR')"><?php echo $Language->phrase("QuickSearchAny") ?></a>
			</div>
		</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $nav_list->showPageHeader(); ?>
<?php
$nav_list->showMessage();
?>
<?php if ($nav_list->TotalRecs > 0 || $nav->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($nav_list->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> nav">
<?php if (!$nav->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$nav->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?php echo CurrentPageName() ?>">
<?php if (!isset($nav_list->Pager)) $nav_list->Pager = new PrevNextPager($nav_list->StartRec, $nav_list->DisplayRecs, $nav_list->TotalRecs, $nav_list->AutoHidePager) ?>
<?php if ($nav_list->Pager->RecordCount > 0 && $nav_list->Pager->Visible) { ?>
<div class="ew-pager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ew-prev-next"><div class="input-group input-group-sm">
<div class="input-group-prepend">
<!-- first page button -->
	<?php if ($nav_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerFirst") ?>" href="<?php echo $nav_list->pageUrl() ?>start=<?php echo $nav_list->Pager->FirstButton->Start ?>"><i class="icon-first ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerFirst") ?>"><i class="icon-first ew-icon"></i></a>
	<?php } ?>
<!-- previous page button -->
	<?php if ($nav_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerPrevious") ?>" href="<?php echo $nav_list->pageUrl() ?>start=<?php echo $nav_list->Pager->PrevButton->Start ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerPrevious") ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } ?>
</div>
<!-- current page number -->
	<input class="form-control" type="text" name="<?php echo TABLE_PAGE_NO ?>" value="<?php echo $nav_list->Pager->CurrentPage ?>">
<div class="input-group-append">
<!-- next page button -->
	<?php if ($nav_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerNext") ?>" href="<?php echo $nav_list->pageUrl() ?>start=<?php echo $nav_list->Pager->NextButton->Start ?>"><i class="icon-next ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerNext") ?>"><i class="icon-next ew-icon"></i></a>
	<?php } ?>
<!-- last page button -->
	<?php if ($nav_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerLast") ?>" href="<?php echo $nav_list->pageUrl() ?>start=<?php echo $nav_list->Pager->LastButton->Start ?>"><i class="icon-last ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerLast") ?>"><i class="icon-last ew-icon"></i></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $nav_list->Pager->PageCount ?></span>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($nav_list->Pager->RecordCount > 0) { ?>
<div class="ew-pager ew-rec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $nav_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $nav_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $nav_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $nav_list->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fnavlist" id="fnavlist" class="form-inline ew-form ew-list-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($nav_list->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $nav_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="nav">
<div id="gmp_nav" class="<?php if (IsResponsiveLayout()) { ?>table-responsive <?php } ?>card-body ew-grid-middle-panel">
<?php if ($nav_list->TotalRecs > 0 || $nav->isGridEdit()) { ?>
<table id="tbl_navlist" class="table ew-table"><!-- .ew-table ##-->
<thead>
	<tr class="ew-table-header">
<?php

// Header row
$nav_list->RowType = ROWTYPE_HEADER;

// Render list options
$nav_list->renderListOptions();

// Render list options (header, left)
$nav_list->ListOptions->render("header", "left");
?>
<?php if ($nav->id->Visible) { // id ?>
	<?php if ($nav->sortUrl($nav->id) == "") { ?>
		<th data-name="id" class="<?php echo $nav->id->headerCellClass() ?>"><div id="elh_nav_id" class="nav_id"><div class="ew-table-header-caption"><?php echo $nav->id->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $nav->id->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event,'<?php echo $nav->SortUrl($nav->id) ?>',1);"><div id="elh_nav_id" class="nav_id">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $nav->id->caption() ?></span><span class="ew-table-header-sort"><?php if ($nav->id->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($nav->id->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($nav->nav_name->Visible) { // nav_name ?>
	<?php if ($nav->sortUrl($nav->nav_name) == "") { ?>
		<th data-name="nav_name" class="<?php echo $nav->nav_name->headerCellClass() ?>"><div id="elh_nav_nav_name" class="nav_nav_name"><div class="ew-table-header-caption"><?php echo $nav->nav_name->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nav_name" class="<?php echo $nav->nav_name->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event,'<?php echo $nav->SortUrl($nav->nav_name) ?>',1);"><div id="elh_nav_nav_name" class="nav_nav_name">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $nav->nav_name->caption() ?><?php echo $Language->phrase("SrchLegend") ?></span><span class="ew-table-header-sort"><?php if ($nav->nav_name->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($nav->nav_name->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($nav->nav_link->Visible) { // nav_link ?>
	<?php if ($nav->sortUrl($nav->nav_link) == "") { ?>
		<th data-name="nav_link" class="<?php echo $nav->nav_link->headerCellClass() ?>"><div id="elh_nav_nav_link" class="nav_nav_link"><div class="ew-table-header-caption"><?php echo $nav->nav_link->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nav_link" class="<?php echo $nav->nav_link->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event,'<?php echo $nav->SortUrl($nav->nav_link) ?>',1);"><div id="elh_nav_nav_link" class="nav_nav_link">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $nav->nav_link->caption() ?><?php echo $Language->phrase("SrchLegend") ?></span><span class="ew-table-header-sort"><?php if ($nav->nav_link->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($nav->nav_link->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$nav_list->ListOptions->render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($nav->ExportAll && $nav->isExport()) {
	$nav_list->StopRec = $nav_list->TotalRecs;
} else {

	// Set the last record to display
	if ($nav_list->TotalRecs > $nav_list->StartRec + $nav_list->DisplayRecs - 1)
		$nav_list->StopRec = $nav_list->StartRec + $nav_list->DisplayRecs - 1;
	else
		$nav_list->StopRec = $nav_list->TotalRecs;
}
$nav_list->RecCnt = $nav_list->StartRec - 1;
if ($nav_list->Recordset && !$nav_list->Recordset->EOF) {
	$nav_list->Recordset->moveFirst();
	$selectLimit = $nav_list->UseSelectLimit;
	if (!$selectLimit && $nav_list->StartRec > 1)
		$nav_list->Recordset->move($nav_list->StartRec - 1);
} elseif (!$nav->AllowAddDeleteRow && $nav_list->StopRec == 0) {
	$nav_list->StopRec = $nav->GridAddRowCount;
}

// Initialize aggregate
$nav->RowType = ROWTYPE_AGGREGATEINIT;
$nav->resetAttributes();
$nav_list->renderRow();
while ($nav_list->RecCnt < $nav_list->StopRec) {
	$nav_list->RecCnt++;
	if ($nav_list->RecCnt >= $nav_list->StartRec) {
		$nav_list->RowCnt++;

		// Set up key count
		$nav_list->KeyCount = $nav_list->RowIndex;

		// Init row class and style
		$nav->resetAttributes();
		$nav->CssClass = "";
		if ($nav->isGridAdd()) {
		} else {
			$nav_list->loadRowValues($nav_list->Recordset); // Load row values
		}
		$nav->RowType = ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$nav->RowAttrs = array_merge($nav->RowAttrs, array('data-rowindex'=>$nav_list->RowCnt, 'id'=>'r' . $nav_list->RowCnt . '_nav', 'data-rowtype'=>$nav->RowType));

		// Render row
		$nav_list->renderRow();

		// Render list options
		$nav_list->renderListOptions();
?>
	<tr<?php echo $nav->rowAttributes() ?>>
<?php

// Render list options (body, left)
$nav_list->ListOptions->render("body", "left", $nav_list->RowCnt);
?>
	<?php if ($nav->id->Visible) { // id ?>
		<td data-name="id"<?php echo $nav->id->cellAttributes() ?>>
<span id="el<?php echo $nav_list->RowCnt ?>_nav_id" class="nav_id">
<span<?php echo $nav->id->viewAttributes() ?>>
<?php echo $nav->id->getViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($nav->nav_name->Visible) { // nav_name ?>
		<td data-name="nav_name"<?php echo $nav->nav_name->cellAttributes() ?>>
<span id="el<?php echo $nav_list->RowCnt ?>_nav_nav_name" class="nav_nav_name">
<span<?php echo $nav->nav_name->viewAttributes() ?>>
<?php echo $nav->nav_name->getViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($nav->nav_link->Visible) { // nav_link ?>
		<td data-name="nav_link"<?php echo $nav->nav_link->cellAttributes() ?>>
<span id="el<?php echo $nav_list->RowCnt ?>_nav_nav_link" class="nav_nav_link">
<span<?php echo $nav->nav_link->viewAttributes() ?>>
<?php echo $nav->nav_link->getViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$nav_list->ListOptions->render("body", "right", $nav_list->RowCnt);
?>
	</tr>
<?php
	}
	if (!$nav->isGridAdd())
		$nav_list->Recordset->moveNext();
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
<?php if (!$nav->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
</form><!-- /.ew-list-form -->
<?php

// Close recordset
if ($nav_list->Recordset)
	$nav_list->Recordset->Close();
?>
<?php if (!$nav->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$nav->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?php echo CurrentPageName() ?>">
<?php if (!isset($nav_list->Pager)) $nav_list->Pager = new PrevNextPager($nav_list->StartRec, $nav_list->DisplayRecs, $nav_list->TotalRecs, $nav_list->AutoHidePager) ?>
<?php if ($nav_list->Pager->RecordCount > 0 && $nav_list->Pager->Visible) { ?>
<div class="ew-pager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ew-prev-next"><div class="input-group input-group-sm">
<div class="input-group-prepend">
<!-- first page button -->
	<?php if ($nav_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerFirst") ?>" href="<?php echo $nav_list->pageUrl() ?>start=<?php echo $nav_list->Pager->FirstButton->Start ?>"><i class="icon-first ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerFirst") ?>"><i class="icon-first ew-icon"></i></a>
	<?php } ?>
<!-- previous page button -->
	<?php if ($nav_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerPrevious") ?>" href="<?php echo $nav_list->pageUrl() ?>start=<?php echo $nav_list->Pager->PrevButton->Start ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerPrevious") ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } ?>
</div>
<!-- current page number -->
	<input class="form-control" type="text" name="<?php echo TABLE_PAGE_NO ?>" value="<?php echo $nav_list->Pager->CurrentPage ?>">
<div class="input-group-append">
<!-- next page button -->
	<?php if ($nav_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerNext") ?>" href="<?php echo $nav_list->pageUrl() ?>start=<?php echo $nav_list->Pager->NextButton->Start ?>"><i class="icon-next ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerNext") ?>"><i class="icon-next ew-icon"></i></a>
	<?php } ?>
<!-- last page button -->
	<?php if ($nav_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerLast") ?>" href="<?php echo $nav_list->pageUrl() ?>start=<?php echo $nav_list->Pager->LastButton->Start ?>"><i class="icon-last ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerLast") ?>"><i class="icon-last ew-icon"></i></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $nav_list->Pager->PageCount ?></span>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($nav_list->Pager->RecordCount > 0) { ?>
<div class="ew-pager ew-rec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $nav_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $nav_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $nav_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $nav_list->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($nav_list->TotalRecs == 0 && !$nav->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $nav_list->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php
$nav_list->showPageFooter();
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
$nav_list->terminate();
?>