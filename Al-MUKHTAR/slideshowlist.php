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
$slideshow_list = new slideshow_list();

// Run the page
$slideshow_list->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$slideshow_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if (!$slideshow->isExport()) { ?>
<script>

// Form object
currentPageID = ew.PAGE_ID = "list";
var fslideshowlist = currentForm = new ew.Form("fslideshowlist", "list");
fslideshowlist.formKeyCountName = '<?php echo $slideshow_list->FormKeyCountName ?>';

// Form_CustomValidate event
fslideshowlist.Form_CustomValidate = function(fobj) { // DO NOT CHANGE THIS LINE!

	// Your custom validation code here, return false if invalid.
	return true;
}

// Use JavaScript validation or not
fslideshowlist.validateRequired = <?php echo json_encode(CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var fslideshowlistsrch = currentSearchForm = new ew.Form("fslideshowlistsrch");

// Filters
fslideshowlistsrch.filterList = <?php echo $slideshow_list->getFilterList() ?>;
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
<?php if (!$slideshow->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($slideshow_list->TotalRecs > 0 && $slideshow_list->ExportOptions->visible()) { ?>
<?php $slideshow_list->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($slideshow_list->ImportOptions->visible()) { ?>
<?php $slideshow_list->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($slideshow_list->SearchOptions->visible()) { ?>
<?php $slideshow_list->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($slideshow_list->FilterOptions->visible()) { ?>
<?php $slideshow_list->FilterOptions->render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
$slideshow_list->renderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if (!$slideshow->isExport() && !$slideshow->CurrentAction) { ?>
<form name="fslideshowlistsrch" id="fslideshowlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?php echo CurrentPageName() ?>">
<?php $searchPanelClass = ($slideshow_list->SearchWhere <> "") ? " show" : " show"; ?>
<div id="fslideshowlistsrch-search-panel" class="ew-search-panel collapse<?php echo $searchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="slideshow">
	<div class="ew-basic-search">
<div id="xsr_1" class="ew-row d-sm-flex">
	<div class="ew-quick-search input-group">
		<input type="text" name="<?php echo TABLE_BASIC_SEARCH ?>" id="<?php echo TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo HtmlEncode($slideshow_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo HtmlEncode($Language->phrase("Search")) ?>">
		<input type="hidden" name="<?php echo TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo HtmlEncode($slideshow_list->BasicSearch->getType()) ?>">
		<div class="input-group-append">
			<button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?php echo $Language->phrase("SearchBtn") ?></button>
			<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false"><span id="searchtype"><?php echo $slideshow_list->BasicSearch->getTypeNameShort() ?></span></button>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item<?php if ($slideshow_list->BasicSearch->getType() == "") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this)"><?php echo $Language->phrase("QuickSearchAuto") ?></a>
				<a class="dropdown-item<?php if ($slideshow_list->BasicSearch->getType() == "=") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'=')"><?php echo $Language->phrase("QuickSearchExact") ?></a>
				<a class="dropdown-item<?php if ($slideshow_list->BasicSearch->getType() == "AND") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'AND')"><?php echo $Language->phrase("QuickSearchAll") ?></a>
				<a class="dropdown-item<?php if ($slideshow_list->BasicSearch->getType() == "OR") echo " active"; ?>" href="javascript:void(0);" onclick="ew.setSearchType(this,'OR')"><?php echo $Language->phrase("QuickSearchAny") ?></a>
			</div>
		</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $slideshow_list->showPageHeader(); ?>
<?php
$slideshow_list->showMessage();
?>
<?php if ($slideshow_list->TotalRecs > 0 || $slideshow->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($slideshow_list->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> slideshow">
<?php if (!$slideshow->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$slideshow->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?php echo CurrentPageName() ?>">
<?php if (!isset($slideshow_list->Pager)) $slideshow_list->Pager = new PrevNextPager($slideshow_list->StartRec, $slideshow_list->DisplayRecs, $slideshow_list->TotalRecs, $slideshow_list->AutoHidePager) ?>
<?php if ($slideshow_list->Pager->RecordCount > 0 && $slideshow_list->Pager->Visible) { ?>
<div class="ew-pager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ew-prev-next"><div class="input-group input-group-sm">
<div class="input-group-prepend">
<!-- first page button -->
	<?php if ($slideshow_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerFirst") ?>" href="<?php echo $slideshow_list->pageUrl() ?>start=<?php echo $slideshow_list->Pager->FirstButton->Start ?>"><i class="icon-first ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerFirst") ?>"><i class="icon-first ew-icon"></i></a>
	<?php } ?>
<!-- previous page button -->
	<?php if ($slideshow_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerPrevious") ?>" href="<?php echo $slideshow_list->pageUrl() ?>start=<?php echo $slideshow_list->Pager->PrevButton->Start ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerPrevious") ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } ?>
</div>
<!-- current page number -->
	<input class="form-control" type="text" name="<?php echo TABLE_PAGE_NO ?>" value="<?php echo $slideshow_list->Pager->CurrentPage ?>">
<div class="input-group-append">
<!-- next page button -->
	<?php if ($slideshow_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerNext") ?>" href="<?php echo $slideshow_list->pageUrl() ?>start=<?php echo $slideshow_list->Pager->NextButton->Start ?>"><i class="icon-next ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerNext") ?>"><i class="icon-next ew-icon"></i></a>
	<?php } ?>
<!-- last page button -->
	<?php if ($slideshow_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerLast") ?>" href="<?php echo $slideshow_list->pageUrl() ?>start=<?php echo $slideshow_list->Pager->LastButton->Start ?>"><i class="icon-last ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerLast") ?>"><i class="icon-last ew-icon"></i></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $slideshow_list->Pager->PageCount ?></span>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($slideshow_list->Pager->RecordCount > 0) { ?>
<div class="ew-pager ew-rec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $slideshow_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $slideshow_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $slideshow_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $slideshow_list->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fslideshowlist" id="fslideshowlist" class="form-inline ew-form ew-list-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($slideshow_list->CheckToken) { ?>
<input type="hidden" name="<?php echo TOKEN_NAME ?>" value="<?php echo $slideshow_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="slideshow">
<div id="gmp_slideshow" class="<?php if (IsResponsiveLayout()) { ?>table-responsive <?php } ?>card-body ew-grid-middle-panel">
<?php if ($slideshow_list->TotalRecs > 0 || $slideshow->isGridEdit()) { ?>
<table id="tbl_slideshowlist" class="table ew-table"><!-- .ew-table ##-->
<thead>
	<tr class="ew-table-header">
<?php

// Header row
$slideshow_list->RowType = ROWTYPE_HEADER;

// Render list options
$slideshow_list->renderListOptions();

// Render list options (header, left)
$slideshow_list->ListOptions->render("header", "left");
?>
<?php if ($slideshow->id->Visible) { // id ?>
	<?php if ($slideshow->sortUrl($slideshow->id) == "") { ?>
		<th data-name="id" class="<?php echo $slideshow->id->headerCellClass() ?>"><div id="elh_slideshow_id" class="slideshow_id"><div class="ew-table-header-caption"><?php echo $slideshow->id->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $slideshow->id->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event,'<?php echo $slideshow->SortUrl($slideshow->id) ?>',1);"><div id="elh_slideshow_id" class="slideshow_id">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $slideshow->id->caption() ?></span><span class="ew-table-header-sort"><?php if ($slideshow->id->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($slideshow->id->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($slideshow->img_link->Visible) { // img_link ?>
	<?php if ($slideshow->sortUrl($slideshow->img_link) == "") { ?>
		<th data-name="img_link" class="<?php echo $slideshow->img_link->headerCellClass() ?>"><div id="elh_slideshow_img_link" class="slideshow_img_link"><div class="ew-table-header-caption"><?php echo $slideshow->img_link->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="img_link" class="<?php echo $slideshow->img_link->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event,'<?php echo $slideshow->SortUrl($slideshow->img_link) ?>',1);"><div id="elh_slideshow_img_link" class="slideshow_img_link">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $slideshow->img_link->caption() ?><?php echo $Language->phrase("SrchLegend") ?></span><span class="ew-table-header-sort"><?php if ($slideshow->img_link->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($slideshow->img_link->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$slideshow_list->ListOptions->render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($slideshow->ExportAll && $slideshow->isExport()) {
	$slideshow_list->StopRec = $slideshow_list->TotalRecs;
} else {

	// Set the last record to display
	if ($slideshow_list->TotalRecs > $slideshow_list->StartRec + $slideshow_list->DisplayRecs - 1)
		$slideshow_list->StopRec = $slideshow_list->StartRec + $slideshow_list->DisplayRecs - 1;
	else
		$slideshow_list->StopRec = $slideshow_list->TotalRecs;
}
$slideshow_list->RecCnt = $slideshow_list->StartRec - 1;
if ($slideshow_list->Recordset && !$slideshow_list->Recordset->EOF) {
	$slideshow_list->Recordset->moveFirst();
	$selectLimit = $slideshow_list->UseSelectLimit;
	if (!$selectLimit && $slideshow_list->StartRec > 1)
		$slideshow_list->Recordset->move($slideshow_list->StartRec - 1);
} elseif (!$slideshow->AllowAddDeleteRow && $slideshow_list->StopRec == 0) {
	$slideshow_list->StopRec = $slideshow->GridAddRowCount;
}

// Initialize aggregate
$slideshow->RowType = ROWTYPE_AGGREGATEINIT;
$slideshow->resetAttributes();
$slideshow_list->renderRow();
while ($slideshow_list->RecCnt < $slideshow_list->StopRec) {
	$slideshow_list->RecCnt++;
	if ($slideshow_list->RecCnt >= $slideshow_list->StartRec) {
		$slideshow_list->RowCnt++;

		// Set up key count
		$slideshow_list->KeyCount = $slideshow_list->RowIndex;

		// Init row class and style
		$slideshow->resetAttributes();
		$slideshow->CssClass = "";
		if ($slideshow->isGridAdd()) {
		} else {
			$slideshow_list->loadRowValues($slideshow_list->Recordset); // Load row values
		}
		$slideshow->RowType = ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$slideshow->RowAttrs = array_merge($slideshow->RowAttrs, array('data-rowindex'=>$slideshow_list->RowCnt, 'id'=>'r' . $slideshow_list->RowCnt . '_slideshow', 'data-rowtype'=>$slideshow->RowType));

		// Render row
		$slideshow_list->renderRow();

		// Render list options
		$slideshow_list->renderListOptions();
?>
	<tr<?php echo $slideshow->rowAttributes() ?>>
<?php

// Render list options (body, left)
$slideshow_list->ListOptions->render("body", "left", $slideshow_list->RowCnt);
?>
	<?php if ($slideshow->id->Visible) { // id ?>
		<td data-name="id"<?php echo $slideshow->id->cellAttributes() ?>>
<span id="el<?php echo $slideshow_list->RowCnt ?>_slideshow_id" class="slideshow_id">
<span<?php echo $slideshow->id->viewAttributes() ?>>
<?php echo $slideshow->id->getViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($slideshow->img_link->Visible) { // img_link ?>
		<td data-name="img_link"<?php echo $slideshow->img_link->cellAttributes() ?>>
<span id="el<?php echo $slideshow_list->RowCnt ?>_slideshow_img_link" class="slideshow_img_link">
<span>
<?php echo GetFileViewTag($slideshow->img_link, $slideshow->img_link->getViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$slideshow_list->ListOptions->render("body", "right", $slideshow_list->RowCnt);
?>
	</tr>
<?php
	}
	if (!$slideshow->isGridAdd())
		$slideshow_list->Recordset->moveNext();
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
<?php if (!$slideshow->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
</form><!-- /.ew-list-form -->
<?php

// Close recordset
if ($slideshow_list->Recordset)
	$slideshow_list->Recordset->Close();
?>
<?php if (!$slideshow->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$slideshow->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?php echo CurrentPageName() ?>">
<?php if (!isset($slideshow_list->Pager)) $slideshow_list->Pager = new PrevNextPager($slideshow_list->StartRec, $slideshow_list->DisplayRecs, $slideshow_list->TotalRecs, $slideshow_list->AutoHidePager) ?>
<?php if ($slideshow_list->Pager->RecordCount > 0 && $slideshow_list->Pager->Visible) { ?>
<div class="ew-pager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ew-prev-next"><div class="input-group input-group-sm">
<div class="input-group-prepend">
<!-- first page button -->
	<?php if ($slideshow_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerFirst") ?>" href="<?php echo $slideshow_list->pageUrl() ?>start=<?php echo $slideshow_list->Pager->FirstButton->Start ?>"><i class="icon-first ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerFirst") ?>"><i class="icon-first ew-icon"></i></a>
	<?php } ?>
<!-- previous page button -->
	<?php if ($slideshow_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerPrevious") ?>" href="<?php echo $slideshow_list->pageUrl() ?>start=<?php echo $slideshow_list->Pager->PrevButton->Start ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerPrevious") ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } ?>
</div>
<!-- current page number -->
	<input class="form-control" type="text" name="<?php echo TABLE_PAGE_NO ?>" value="<?php echo $slideshow_list->Pager->CurrentPage ?>">
<div class="input-group-append">
<!-- next page button -->
	<?php if ($slideshow_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerNext") ?>" href="<?php echo $slideshow_list->pageUrl() ?>start=<?php echo $slideshow_list->Pager->NextButton->Start ?>"><i class="icon-next ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerNext") ?>"><i class="icon-next ew-icon"></i></a>
	<?php } ?>
<!-- last page button -->
	<?php if ($slideshow_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $Language->phrase("PagerLast") ?>" href="<?php echo $slideshow_list->pageUrl() ?>start=<?php echo $slideshow_list->Pager->LastButton->Start ?>"><i class="icon-last ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $Language->phrase("PagerLast") ?>"><i class="icon-last ew-icon"></i></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $slideshow_list->Pager->PageCount ?></span>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($slideshow_list->Pager->RecordCount > 0) { ?>
<div class="ew-pager ew-rec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $slideshow_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $slideshow_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $slideshow_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $slideshow_list->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($slideshow_list->TotalRecs == 0 && !$slideshow->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $slideshow_list->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php
$slideshow_list->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<?php if (!$slideshow->isExport()) { ?>
<script>

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$slideshow_list->terminate();
?>