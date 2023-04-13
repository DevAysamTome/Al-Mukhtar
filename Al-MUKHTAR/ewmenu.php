<?php
namespace PHPMaker2019\project2;

// Menu Language
if ($Language && $Language->LanguageFolder == $LANGUAGE_FOLDER)
	$MenuLanguage = &$Language;
else
	$MenuLanguage = new Language();

// Navbar menu
$topMenu = new Menu("navbar", TRUE, TRUE);
$topMenu->addMenuItem(3, "mi_cars", $MenuLanguage->MenuPhrase("3", "MenuText"), "carslist.php", -1, "", IsLoggedIn() || AllowListMenu('{0EA10286-4F61-46C1-99C7-B5845D78C542}cars'), FALSE, FALSE, "", "", TRUE);
$topMenu->addMenuItem(1, "mi_nav", $MenuLanguage->MenuPhrase("1", "MenuText"), "navlist.php", -1, "", IsLoggedIn() || AllowListMenu('{0EA10286-4F61-46C1-99C7-B5845D78C542}nav'), FALSE, FALSE, "", "", TRUE);
$topMenu->addMenuItem(2, "mi_slideshow", $MenuLanguage->MenuPhrase("2", "MenuText"), "slideshowlist.php", -1, "", IsLoggedIn() || AllowListMenu('{0EA10286-4F61-46C1-99C7-B5845D78C542}slideshow'), FALSE, FALSE, "", "", TRUE);
$topMenu->addMenuItem(4, "mi_carimg", $MenuLanguage->MenuPhrase("4", "MenuText"), "carimglist.php", -1, "", IsLoggedIn() || AllowListMenu('{0EA10286-4F61-46C1-99C7-B5845D78C542}carimg'), FALSE, FALSE, "", "", TRUE);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", TRUE, FALSE);
$sideMenu->addMenuItem(3, "mi_cars", $MenuLanguage->MenuPhrase("3", "MenuText"), "carslist.php", -1, "", IsLoggedIn() || AllowListMenu('{0EA10286-4F61-46C1-99C7-B5845D78C542}cars'), FALSE, FALSE, "", "", TRUE);
$sideMenu->addMenuItem(1, "mi_nav", $MenuLanguage->MenuPhrase("1", "MenuText"), "navlist.php", -1, "", IsLoggedIn() || AllowListMenu('{0EA10286-4F61-46C1-99C7-B5845D78C542}nav'), FALSE, FALSE, "", "", TRUE);
$sideMenu->addMenuItem(2, "mi_slideshow", $MenuLanguage->MenuPhrase("2", "MenuText"), "slideshowlist.php", -1, "", IsLoggedIn() || AllowListMenu('{0EA10286-4F61-46C1-99C7-B5845D78C542}slideshow'), FALSE, FALSE, "", "", TRUE);
$sideMenu->addMenuItem(4, "mi_carimg", $MenuLanguage->MenuPhrase("4", "MenuText"), "carimglist.php", -1, "", IsLoggedIn() || AllowListMenu('{0EA10286-4F61-46C1-99C7-B5845D78C542}carimg'), FALSE, FALSE, "", "", TRUE);
echo $sideMenu->toScript();
?>