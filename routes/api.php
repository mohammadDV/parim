<?php
use System\Router\Api\Route;

Route::get("/reset","ApiController@reset","reset");
Route::get("/execute","ApiController@execute","execute");
Route::get("/","ApiController@index","index");
Route::post("/save","ApiController@save","save");
