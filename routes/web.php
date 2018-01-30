<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');
Route::get('/images/{storage}/{filename}', 'ImageViewController@viewImage');
Route::get('/admin/event/banner/{id}/image/{locale}', 'Admin\EventController@viewEventBannerImage');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->namespace('Admin')->middleware(['admin','manager'])->group(function() {

    // Not using for now, leave it here first
    // Route::get('/', 'UserController@index');
    // Route::get('/user/{id}', 'UserController@viewProfile');

    Route::get('/', function() {
        return redirect()->action('Admin\EventController@viewLiveEvent');
    });

    Route::get('/fc/view', 'FieldCreationController@view');
    Route::get('/fc/new', 'FieldCreationController@showCreateNewFieldForm');
    Route::get('/fc/field/{id}', 'FieldCreationController@showEditFieldForm');
    Route::post('/fc/create', 'FieldCreationController@createNewField');
    Route::put('/fc/field/{id}/reorder', 'FieldCreationController@reorderField');
    Route::put('/fc/field/{id}', 'FieldCreationController@updateField');
    Route::delete('/fc/field/{id}', 'FieldCreationController@deleteField');

    Route::get('/event/live', 'EventController@viewLiveEvent');
    Route::get('/event/list', 'EventController@showEventList');
    Route::get('/event/new', 'EventController@showCreateEventForm');
    Route::get('/event/import-template', 'EventController@getImportMemberTemplate');
    Route::get('/event/{id}/import-member', 'EventController@showImportMemberForm');
    Route::get('event/{id}/score-board', 'EventController@showScoreBoard');
    Route::get('/event/{id}', 'EventController@viewEvent');
    Route::get('/event/{id}/edit', 'EventController@showEditEventForm');
    Route::get('/event/{id}/export-data', 'EventController@exportParticipantData');
    Route::put('/event/{id}/edit', 'EventController@updateEvent');
    Route::post('/event/{id}/import-member', 'EventController@importMemberWithCSV');
    Route::post('/event/{id}/save-import-member', 'EventController@saveImportMember');
    Route::post('/event/create', 'EventController@createEvent');
    Route::delete('/event/{id}/ended', 'EventController@markEventAsEnded');
    Route::delete('/event/{id}', 'EventController@deleteEvent');

    Route::get('/event/{eventId}/banner', 'EventController@showEventBanners');
    Route::get('/event/{eventId}/banner/new', 'EventController@showCreateEventBannerForm');
    Route::get('/event/banner/{id}/edit', 'EventController@showEditEventBannerForm');
    Route::post('/event/{eventId}/banner/create', 'EventController@createEventBanner');
    Route::put('/event/banner/{id}/reorder', 'EventController@reorderBanner');
    Route::put('/event/banner/{id}/edit', 'EventController@updateEventBanner');
    Route::delete('/event/banner/{id}/delete', 'EventController@deleteEventBanner');

    Route::get('/event/{eventId}/show-manager', 'EventController@showManager');
    Route::get('/event/{eventId}/activities-title','EventController@activitiesTitle');
    Route::put('/event/{eventId}/update-activities-titles', 'EventController@updateActivitiesTitle');
    Route::put('/event/{eventId}/update-manager', 'EventController@updateManager');

    Route::get('/event/{eventId}/summary', 'EventController@eventSummary');

    Route::get('/event/{eventId}/stage', 'EventStageController@viewEventStages');
    Route::get('/event/{eventId}/stage/new', 'EventStageController@showCreateStageForm');
    Route::get('/event/stage/{id}/edit', 'EventStageController@showEditStageForm');
    Route::put('/event/stage/{id}/edit', 'EventStageController@updateStage');
    Route::put('/event/stage/{id}/reorder', 'EventStageController@reorderStage');
    Route::post('/event/{eventId}/stage/new', 'EventStageController@createStage');
    Route::delete('/event/stage/{id}/delete', 'EventStageController@deleteStage');

    Route::get('/event/stage/{stageId}/game', 'GameController@viewGames');
    Route::put('/event/stage/game/{id}/reorder', 'GameController@reorderGame');
    // Input Type
    Route::get('/event/stage/{stageId}/game-input/new', 'GameController@showCreateInputGameForm');
    Route::get('/event/stage/game-input/{id}/edit', 'GameController@showEditInputGameForm');
    Route::get('/event/stage/game-input/{gameId}/field/new', 'GameController@showCreateInputGameFieldForm');
    Route::get('/event/stage/game-input/field/{id}/edit', 'GameController@showEditInputGameFieldForm');
    Route::put('/event/stage/game-input/{id}/edit', 'GameController@updateInputGame');
    Route::put('/event/stage/game-input/field/{id}/edit', 'GameController@updateInputGameField');
    Route::post('/event/stage/{stageId}/game-input/create', 'GameController@createInputGame');
    Route::post('/event/stage/game-input/{gameId}/field/create', 'GameController@createInputGameField');
    Route::delete('/event/stage/game-input/{id}/delete', 'GameController@deleteInputGame');
    Route::delete('/event/stage/game-input/field/{id}/delete', 'GameController@deleteInputGameField');
    Route::put('/event/stage/game-input/field/{id}/reorder', 'GameController@reorderInputGameField');
    // Rule Type
    Route::get('/event/stage/{stageId}/game-rule/new', 'GameController@showCreateRuleGameForm');
    Route::get('/event/stage/game-rule/{id}/edit', 'GameController@showEditRuleGameForm');
    Route::put('/event/stage/game-rule/{id}/edit', 'GameController@updateRuleGame');
    Route::post('/event/stage/{stageId}/game-rule/create', 'GameController@createRuleGame');
    Route::delete('/event/stage/game-rule/{id}/delete', 'GameController@deleteRuleGame');

    Route::get('/event/particpant/register', 'ParticipantController@showRegisterParticipantForm');
    Route::get('/event/particpant/{id}/edit', 'ParticipantController@showEditParticipantForm');
    Route::get('/event/participant/{id}', 'ParticipantController@viewProfile');
    Route::get('/event/participant/{participantId}/game-participation/{participationId}', 'ParticipantController@viewGameInputDetail');
    Route::get('/event/participant/{id}/reward', 'ParticipantController@showRewardParticipantForm');
    Route::post('/event/participant/{id}/reward', 'ParticipantController@rewardParticipant');
    Route::post('/event/particpant/register', 'ParticipantController@registerParticipant');
    Route::put('/event/participant/{id}/edit', 'ParticipantController@updateParticipant');
    Route::delete('/event/participant/{id}', 'ParticipantController@deleteParticipant');
    Route::get('/event/participant/{id}/{activity_id}/reset_activity', 'ParticipantController@resetActivity');
    Route::put('/event/participant/{id}/{activity_id}/edit_score', 'ParticipantController@updateParticipantScore');

    Route::get('/event-ended/summary', 'EventController@eventEndedSummary');

    Route::get('/qc/view', 'QRCodeController@index');
    Route::get('/qc/new', 'QRCodeController@showCreateForm');
    Route::get('qc/{id}/image', 'QRCodeController@viewQRCodeImage');
    Route::get('/qc/event/{eventId}/print', 'QRCodeController@printQRCodes');
    Route::get('/qc/{id}/print', 'QRCodeController@printIndividualQRCode');
    Route::post('/qc/create', 'QRCodeController@createQRCode');
    Route::get('/qc/{id}', 'QRCodeController@showEditForm');
    Route::put('/qc/{id}', 'QRCodeController@updateQRCode');
    Route::delete('/qc/{id}', 'QRCodeController@deleteQRCode');

    Route::get('/gift', 'GiftController@index');
    Route::get('/gift/new', 'GiftController@showCreateForm');
    Route::get('/gift/print', 'GiftController@printGifts');
    Route::get('/gift/{id}/edit', 'GiftController@showEditForm');
    Route::post('/gift/create', 'GiftController@createGift');
    Route::put('/gift/{id}/edit', 'GiftController@updateGift');
    Route::delete('/gift/{id}', 'GiftController@deleteGift');

    Route::get('/manager', 'EventManagerController@index');
    Route::get('/manager/create', 'EventManagerController@create');
    Route::get('/manager/{id}/edit', 'EventManagerController@edit');
    Route::post('/manager/create', 'EventManagerController@store');
    Route::put('/manager/{id}/edit', 'EventManagerController@update');
    Route::delete('/manager/{id}', 'EventManagerController@delete');
    Route::get('/manager/{id}', 'EventManagerController@show');

});
