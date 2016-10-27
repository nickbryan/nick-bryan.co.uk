<?php
/**
 * Define all application dependencies in this file.
 *
 * @var Slim\App $app
 */
use App\Actions\AboutAction;
use App\Actions\BlogAction;
use App\Actions\BlogPageAction;
use App\Actions\HomeAction;
use App\Actions\PageNotFoundAction;
use App\Actions\TreehouseSaveAction;
use App\ContentParser;
use App\Middleware\HandlePageNotFound;
use App\ParsedownExtraParser;
use App\Treehouse;
use Interop\Container\ContainerInterface;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Mni\FrontYAML\Parser;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

$container = $app->getContainer();

$container['view'] = function(ContainerInterface $container) {
    $settings = $container->get('settings');

    $view = new Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new TwigExtension($container->get('router'), $container->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new Twig_Extension_StringLoader());

    return $view;
};

$container['foundHandler'] = function(ContainerInterface $container) {
    return new RequestResponseArgs();
};

$container['notFoundHandler'] = function(ContainerInterface $container) {
    return new PageNotFoundAction($container->get('view'), $container->get('settings')['not_found_template']);
};

$container['middleware.handlePageNotFound'] = function(ContainerInterface $container) {
    return new HandlePageNotFound($container->get('notFoundHandler'));
};

$container[Parser::class] = function() {
    return new Parser(null, new ParsedownExtraParser());
};

$container['filesystem.blog'] = function (ContainerInterface $container) {
    return new Filesystem(new Local(__DIR__ . '/views/markdown/blog'));
};

$container['filesystem.markdown'] = function (ContainerInterface $container) {
    return new Filesystem(new Local(__DIR__ . '/views/markdown'));
};

$container['filesystem.storage'] = function (ContainerInterface $container) {
    return new Filesystem(new Local(__DIR__ . '/../storage/'));
};

$container[ContentParser::class] = function(ContainerInterface $container) {
    return new ContentParser($container->get('filesystem.markdown'), $container->get(Parser::class));
};

$container[Treehouse::class] = function(ContainerInterface $container) {
    return new Treehouse($container->get('filesystem.storage'));
};

$container[HomeAction::class] = function(ContainerInterface $container) {
    return new HomeAction(
        $container->get('view'),
        $container->get('filesystem.markdown'),
        $container->get(ContentParser::class)
    );
};

$container[AboutAction::class] = function(ContainerInterface $container) {
    return new AboutAction(
        $container->get('view'),
        $container->get('filesystem.markdown'),
        $container->get(ContentParser::class),
        $container->get(Treehouse::class)
    );
};

$container[TreehouseSaveAction::class] = function(ContainerInterface $container) {
    return new TreehouseSaveAction($container->get(Treehouse::class));
};

$container[BlogAction::class] = function(ContainerInterface $container) {
    return new BlogAction($container->get('view'));
};

$container[BlogPageAction::class] = function(ContainerInterface $container) {
    return new BlogPageAction(
        $container->get('view'),
        $container->get('filesystem.blog'),
        $container->get(ContentParser::class),
        $container->get('settings')['blog']
    );
};
