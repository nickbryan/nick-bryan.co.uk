<?php
/**
 * Define all application routes in this file.
 *
 * @var Slim\App $app
 */
use App\Actions\AboutAction;
use App\Actions\BlogAction;
use App\Actions\BlogPageAction;
use App\Actions\HomeAction;
use App\Actions\TreehouseSaveAction;

$app->get('/', HomeAction::class)->setName('homePage');

$app->get('/about', AboutAction::class)->setName('aboutPage');

$app->post('/treehouse', TreehouseSaveAction::class);

$app->get('/blog', BlogAction::class)->setName('blogPage');

$app->get('/blog/{slug}', BlogPageAction::class)->add('middleware.handlePageNotFound');
