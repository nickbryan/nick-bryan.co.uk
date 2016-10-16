<?php
/**
 * Define all application dependencies in this file.
 *
 * @var Slim\App $app
 */
use App\Actions\AboutAction;
use App\Actions\HomeAction;
use App\Actions\TreehouseSaveAction;
use App\ContentParser;
use App\ParsedownExtraParser;
use App\Treehouse;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Mni\FrontYAML\Parser;
use Slim\Container;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

$container = $app->getContainer();

$container['view'] = function(Container $container) {
    $settings = $container->get('settings');

    $view = new Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new TwigExtension($container->get('router'), $container->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new Twig_Extension_StringLoader());

    return $view;
};

$container[Parser::class] = function() {
    return new Parser(null, new ParsedownExtraParser());
};

$container[ContentParser::class] = function(Container $container) {
    $filesystem = new Filesystem(new Local(__DIR__.'/views/markdown'));

    return new ContentParser($filesystem, $container->get(Parser::class));
};

$container[Treehouse::class] = function(Container $container) {
    $filesystem = new Filesystem(new Local(__DIR__.'/../storage/'));

    return new Treehouse($filesystem);
};

$container[HomeAction::class] = function(Container $container) {
    return new HomeAction($container->get('view'), $container->get(ContentParser::class));
};

$container[AboutAction::class] = function(Container $container) {
    return new AboutAction($container->get('view'), $container->get(ContentParser::class), $container->get(Treehouse::class));
};

$container[TreehouseSaveAction::class] = function(Container $container) {
    return new TreehouseSaveAction($container->get(Treehouse::class));
};
