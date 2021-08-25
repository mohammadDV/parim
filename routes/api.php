<?php
use System\Router\Api\Route;

Route::get("/remove","ApiController@remove","remove");
Route::get("/execute","ApiController@execute","execute");
Route::get("/","ApiController@index","index");
Route::post("/save","ApiController@save","save");
