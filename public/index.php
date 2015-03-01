<?php
require '../vendor/autoload.php';

use MemcachedManager\Config;

//load configuration
Config::writeBulk( require '../config/config.local.php' );
Config::write( 'environment', getenv( 'APPLICATION_ENV' ) );

// Prepare app
$app = new \Slim\Slim(
    array(
        'templates.path' => '../templates',
    )
);

//create singleton resources
$app->container->singleton( 'log', function ()
{
    $log = new \Monolog\Logger( 'slim-skeleton' );
    $log->pushHandler( new \Monolog\Handler\StreamHandler( getenv( 'LOGGING_ROOT' ) . '/app.log', \Monolog\Logger::DEBUG ) );

    return $log;
} );

$app->container->singleton( 'memcached', function ()
{
    $memcached = new \MemcachedManager\Client( Config::read( 'memcached' ) );

    return $memcached;
} );

// Prepare view
$app->view( new \Slim\Views\Twig() );
$app->view->parserOptions    = array(
    'charset'          => 'utf-8',
    'cache'            => realpath( '../templates/cache' ),
    'auto_reload'      => true,
    'strict_variables' => false,
    'autoescape'       => true
);
$app->view->parserExtensions = array( new \Slim\Views\TwigExtension(), new \Twig_Extension_Debug() );
$app->view->set( 'asset_path', Config::read( 'asset.path' ) );
$app->view->set( 'environment', Config::read( 'environment' ) );

$app->get( '/', function () use ( $app )
{
    $app->render( 'index.html', array( 'clusters' => $app->memcached->getClusters() ) );
} );

$app->group( '/cluster', function () use ( $app )
{
    // get cluster properties
    $app->get( '/:clusterName', function ( $clusterName ) use ( $app )
    {
//        $t = $app->memcached->getCluster( $clusterName );
//
//        echo "<pre>";
//        print_r( $t );
//        die();

        // Render cluster view
        $app->render( 'pages/cluster.html', array( 'clusterName' => $clusterName, 'cluster' => $app->memcached->getCluster( $clusterName ) ) );
    } );

    // get cluster properties
    $app->get( '/:clusterName/keys', function ( $clusterName ) use ( $app )
    {
        // Render cluster view
        $app->render( 'pages/keys/list.html', array( 'clusterName' => $clusterName, 'keystore' => $app->memcached->getAllKeys( $clusterName ) ) );
    } );

    $app->group( '/:clusterName/key', function () use ( $app )
    {

        // add key
        $app->post( '/add', function ( $clusterName ) use ( $app )
        {
            $app->memcached->addKey( /*$clusterName,*/
                $app->request->post( 'key' ), $app->request->post( 'value' ) );

            // Redirect to cluster view
            $app->redirect( '/cluster/' . $clusterName . '/keys' );
        } );

        // edit key
        $app->get( '/edit/:key', function ( $clusterName, $key ) use ( $app )
        {
            // Render node view
            $app->render( 'pages/keys/edit.html', array( 'clusterName' => $clusterName, 'key' => $app->memcached->getKey( $key ) ) );
        } );

        // add key
        $app->post( '/edit', function ( $clusterName ) use ( $app )
        {
            $app->memcached->editKey( $app->request->post( 'key' ), $app->request->post( 'value' ) );

            // Redirect to cluster view
            $app->redirect( '/cluster/' . $clusterName . '/keys' );
        } );

        // increment key
        $app->get( '/increment/:key', function ( $clusterName, $key ) use ( $app )
        {
            $app->memcached->incrementKey( $key );

            // Redirect to cluster view
            $app->redirect( '/cluster/' . $clusterName . '/keys' );
        } );

        // decrement key
        $app->get( '/decrement/:key', function ( $clusterName, $key ) use ( $app )
        {
            $app->memcached->decrementKey( $key );

            // Redirect to cluster view
            $app->redirect( '/cluster/' . $clusterName . '/keys' );
        } );

        // delete key
        $app->get( '/delete/:key', function ( $clusterName, $key ) use ( $app )
        {
            $app->memcached->deleteKey( $key );

            // Redirect to cluster view
            $app->redirect( '/cluster/' . $clusterName . '/keys' );
        } );
    } );
} );

// Run app
$app->run();