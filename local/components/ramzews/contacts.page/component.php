<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult["CONTACTS"] = CContactTable::GetAll(array(), array("SORT" => "ASC"));

$this->IncludeComponentTemplate();