<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifyUserController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\DiseasesController;
use App\Http\Controllers\HealthRecordsController;
use App\Http\Controllers\PostCommentsController;
use App\Http\Controllers\PostLikesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ApllyToRoleController;
use App\Http\Controllers\ExcersiseController;
use App\Http\Controllers\ExcersiseMediaController;
use App\Http\Controllers\WorkoutCategorieController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\WorkoutExcersisesController;
use App\Http\Controllers\WorkoutReviewController;
use App\Models\Excersise;
use App\Http\Controllers\DashboardsController;
use App\Http\Controllers\DietController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\MealFoodController;
use App\Http\Controllers\SearchController;
use App\Models\MealFood;
use App\Http\Controllers\ChallengesExcercisesController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\AppControlController;
use App\Http\Controllers\MessageController;

//No token needed routes
Route::group(['middleware' => ['apikey', 'json', 'lang', 'bots', 'timeZone', 'seen', 'appcontrol']], function () {
    //user Registration
    Route::controller(AuthController::class)->group(function () {
        Route::post('/', 'register');
        Route::post('/gettoken', 'getTokenfromRefreshToken');
        Route::post('/login', 'login');
    });
    //Google + Facebook
    Route::post('/login/callback', [SocialiteController::class, 'handleProviderCallback']);
    //Forget Password
    Route::prefix('forgetpassword')->middleware('appcontrol')->controller(ForgotPasswordController::class)->group(function () {
        Route::post('/', 'submitForgetPasswordForm')->middleware(['emailVerified']);
        Route::post('/verify', 'verifytoken');
        Route::post('/reset', 'resetpassword')->middleware('emailVerified');;
    });
});

//verify Email
Route::group(['middleware' => ['apikey', 'json', 'lang', 'bots', 'timeZone', 'seen', 'appcontrol']], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::prefix('emailVerfiy')->controller(VerifyUserController::class)->group(function () {
            Route::post('/', 'verifyAccount');
            Route::post('/reget', 'reGetCode');
            Route::post('/newEmailReget', 'newEmailReGetCode');
        });
    });
});

//Token needed routes
Route::group(['middleware' => ['apikey', 'json', 'lang', 'timeZone', 'emailVerified', 'seen', 'deltedAccount', 'auth:api', 'appcontrol']], function () {
    Route::prefix('user')->controller(AuthController::class)->group(function () {
        Route::post('/info', 'info'); //add his info
        Route::get('/profile', 'useraccount'); //get his profile
        Route::get('/profile/{id}', 'show'); //get user->id profile
        Route::get('/logout', 'logout')->withoutMiddleware(['emailVerified', 'deltedAccount']);
        Route::get('/all_logout', 'allLogout');
        Route::put('/update', 'update');
        Route::post('/updateEmail', 'updateEmail')->middleware('provider');
        Route::post('/updatePassword', 'updatePassword')->middleware('provider');
        Route::post('/verifyNewEmail', 'confirmNewEmail');
        Route::post('/delete', 'firstdestroy');
        Route::post('/recover/reget', 'reGetRecoveryCode')->middleware('bots')->withoutMiddleware('deltedAccount');
        Route::post('/recover', 'recoverVerify')->middleware('bots')->withoutMiddleware('deltedAccount');
    });
    Route::prefix('user')->controller(FollowController::class)->group(function () {
        Route::get('/follow/{id}', 'follow');
        Route::get('/unfollow/{id}', 'unfollow');
        Route::get('/followers/{id}', 'getFollowers');
        Route::get('/following/{id}', 'getFollowing');
        Route::get('/block/{id}', 'block');
        Route::get('/unblock/{id}', 'unblock');
        Route::get('/blocklist', 'blocklist');
    });
    Route::prefix('diseases')->controller(DiseasesController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
    Route::prefix('hRecord')->controller(HealthRecordsController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::delete('/', 'destroy');
    });
    Route::prefix('posts')->controller(PostsController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/vote/{id}/{vote_id}', 'vote')->middleware('block');
        Route::get('/save/{id}', 'savePost')->middleware('block');
        Route::get('/savedPosts', 'savePostList');
        Route::get('/myPosts', 'showMyPosts')->middleware('posts');
        Route::get('/showPosts/{user_id}', 'showOthersPosts')->middleware('block');
        Route::get('/report/{id}', 'report')->middleware('block');
        Route::post('/', 'storeNormal')->middleware('posts');
        Route::post('/poll', 'storepoll')->middleware('posts');
        Route::put('/update/{id}', 'updateNormal')->middleware('posts');
        Route::put('/updatePoll/{id}', 'updatePoll')->middleware('posts');
        Route::delete('/{id}', 'destroy')->middleware('posts');
    });
    Route::prefix('posts/like')->middleware(['block', 'likeable'])->controller(PostLikesController::class)->group(function () {
        Route::get('/list/{id}', 'likeList');
        Route::get('/{id}/{type}', 'like');
    });
    Route::prefix('posts/comment')->middleware(['block'])->controller(PostCommentsController::class)->group(function () {
        Route::get('/{id}', 'index');
        Route::post('/{id}', 'store');
        Route::put('/{id}', 'update')->withoutMiddleware('block');
        Route::delete('/{id}', 'destroy')->withoutMiddleware('block');
        Route::get('/report/{id}', 'report')->withoutMiddleware('block');
    });
    Route::prefix('cv')->controller(ApllyToRoleController::class)->group(function () {
        Route::get('/', 'show');
        Route::Post('/deleteRole', 'DowngradeRole');
        Route::get('/{id}', 'showOthers');
        Route::get('/acc/{id}', 'Accept')->middleware('ms');
        Route::get('/ref/{id}', 'Refuse')->middleware('ms');
        Route::post('/', 'store');
        Route::delete('/', 'destroy');
    });
    Route::prefix('dash')->middleware('ms')->controller(DashboardsController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/cvs', 'CVsDashboard');
        Route::get('/posts', 'PostsDashboard');
        Route::get('/repPosts', 'ReportedPosts');
        Route::get('/RPComments', 'ReportedComments');
        Route::get('/ARPosts/{id}', 'AcceptPost');
        Route::get('/ARComments/{id}', 'AcceptCommentReport');
        Route::get('/repChallenges', 'ReportedChallenges');
        Route::get('/ARChallenges/{id}', 'AcceptChallengeReport');
    });
    Route::prefix('search')->controller(SearchController::class)->group(function () {
        Route::post('/', 'search');
        Route::post('/sug', 'searchSug');
    });
    Route::prefix('chEx')->middleware('ms')->controller(ChallengesExcercisesController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::get('/{id}', 'show');
        Route::delete('/{id}', 'destroy');
    });
    Route::prefix('ch')->controller(ChallengeController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/list', 'exList');
        Route::get('/rep/{id}', 'report'); //add Block
        Route::get('/rev/{id}/{num}', 'review'); //add Block
        Route::get('/sub/{id}', 'sub'); //add Block
        Route::post('/done/{id}', 'done'); //add Block
        Route::post('/', 'store')->middleware('coach');
        Route::put('/{id}', 'update')->middleware('coach');
        Route::put('/{id}', 'update')->middleware('coach');
        Route::get('/my', 'showMy')->middleware('coach');
        Route::get('/show/{id}', 'show'); //add block protect
        Route::get('/mySub', 'showMySubs');
        Route::delete('/{id}', 'destroy')->middleware('coach');
    });
    Route::prefix('App')->controller(AppControlController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/edit', 'update');
    });
    Route::prefix('msg')->controller(MessageController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::Delete('/{id}', 'destroy');
    });
});
Route::get('/anyc', function (Request $request) {
    $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiYTZkZTRiNDE4ZmQ3ZDNiYzRhNzI1YTMxMjJmNTRhN2VhMTQ3MjQ1Y2QzZDJkYWU1MTkxNzNiMTlhY2ZkYWQ2YjU4N2JjODViOGM5ZDIwY2QiLCJpYXQiOjE2NTk2MTgzNzIuMzU0MTczLCJuYmYiOjE2NTk2MTgzNzIuMzU0MTgzLCJleHAiOjE2NjAyMjMxNzIuMTM2Nzg2LCJzdWIiOiIxIiwic2NvcGVzIjpbIioiXX0.W22lCB78gFTz8zjFWIURIYoUBobBqK57dIJLweOoM0DZaSozt9jv62j4CA3pEAabQmRrbLQml3s1pDkySjY_-oiY5ok3UOM9mgxDcBnf8fePzTOM2xnLqYLOSGCwbFQNmRmHE-uY-zOI390UK27e5I-22Qs71EEm7b1rrkdS_wKzcAEmsrltxSCQ7_tfmB-q0ZubLD6H8pVM48xXBRNHKHQeUr2Jv-ZL5TfyTX9T4zgpVytVFodSzDoWUC1CqrvLQu7_AjCQTR5qs5-Ao9pi9TT7DHTtMVHK3CE2go5um-Jxve6XfQqGfv8fPwJh3X5etC9bDfjyYurm6L1eqoW9xejUKXHIb4YAexZ7LgzoT5L4dcXEEzKe5sEeJgGu1vfw2Apg1B-BRzVjvg8KfAxaYs91sKycmxlAtiz82pzrDE7BYXxPpkLwdlwsUGbjRjFaY0H1iIaGfJ-FobNqad5L2t4o2aDun0GCboOa3p3RiZtYZrkvwHDfVgQqckXRZcczvBp5BEJxI_AKR0ki9t2LBrY1Ah3K0l9iIpsn6PR8rjDCO8AzPAd5-QHSRxfjeHiqxGVFwiV5pe-xJNI7GLfqqJA3ufEn22aeCvEvEps5Ao_IvnYGGBpdnA3ElmL7h3_8mhBkqYqmiQaQUQ4QVafTOivRo1-ZLN_-FgBTSkx4EFc';
    return 55;
    return ($token);
});

//Workout + Excersise Routes
//Add MiddleWares
Route::group(['middleware' => ['apikey', 'json', 'lang', 'timeZone', 'auth:api', 'emailVerified', 'deltedAccount']], function () {
    Route::prefix('workout_categorie')->controller(WorkoutCategorieController::class)->group(function () {
        Route::get('/show', 'show');
        Route::post('/create', 'create');
        Route::post('/delete', 'destroy');
        Route::post('/update', 'edit');
        Route::get('/all', 'index');
    });

    Route::prefix('workout')->controller(WorkoutController::class)->group(function () {
    });

    Route::prefix('workout')->controller(WorkoutController::class)->group(function () {
        Route::get('/show', 'show');
        Route::post('/create', 'create');
        Route::post('/delete', 'destroy');
        Route::post('/update', 'edit');
        Route::get('/all', 'index');
    });

    Route::prefix('workout_excersise')->controller(WorkoutExcersisesController::class)->group(function () {
        Route::get('/show', 'show');
        Route::post('/create', 'create');
        Route::post('/delete', 'destroy');
        Route::post('/update', 'edit');
        Route::get('/all', 'index');
        Route::post('/review', 'review');
    });

    Route::prefix('excersise')->controller(ExcersiseController::class)->group(function () {
        Route::get('/show', 'show');
        Route::post('/create', 'create');
        Route::post('/delete', 'destroy');
        Route::post('/update', 'edit');
        Route::get('/all', 'index');
    });

    Route::prefix('excersise_media')->controller(ExcersiseMediaController::class)->group(function () {
        Route::get('/show', 'show');
        Route::post('/create', 'create');
        Route::post('/delete', 'destroy');
        Route::post('/update', 'edit');
        Route::get('/all', 'index');
    });

    Route::prefix('workout_review')->controller(WorkoutReviewController::class)->group(function () {
        Route::get('/show', 'show');
        Route::post('/create', 'create');
        Route::post('/delete', 'destroy');
        Route::post('/update', 'edit');
        Route::get('/all', 'index');
    });
});

Route::group(['middleware' => ['apikey', 'json', 'lang', 'timeZone', 'auth:api', 'emailVerified', 'deltedAccount']], function () {
    Route::prefix('food')->controller(FoodController::class)->group(function () {
        Route::post('/show', 'show');
        Route::post('/create', 'create');
        Route::post('/delete', 'destroy');
        Route::post('/update', 'edit');
        Route::get('/all', 'index');
    });

    Route::prefix('mealfood')->controller(MealFoodController::class)->group(function () {
        Route::post('/show', 'show');
        Route::post('/create', 'create');
        Route::post('/delete', 'destroy');
        Route::post('/update', 'edit');
        Route::get('/all', 'index');
    });

    Route::prefix('meal')->controller(MealController::class)->group(function () {
        Route::post('/show', 'show');
        Route::post('/create', 'create');
        Route::post('/delete', 'destroy');
        Route::post('/update', 'edit');
        Route::get('/all', 'index');
    });

    Route::prefix('diet')->controller(DietController::class)->group(function () {
        Route::post('/show', 'show');
        Route::post('/create', 'create');
        Route::post('/delete', 'destroy');
        Route::post('/update', 'edit');
        Route::get('/all', 'index');
    });
});


// Route::prefix('home')->controller(HomePageController::class)->group(function()
//     {
//         Route::get('/summary' , 'summary');
//     });
