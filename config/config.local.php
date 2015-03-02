<?php

/**
 * Modify this file to configure the clusters to connect to.  The
 * array should be formatted with the cluster name as the key, and an
 * array of node as values.
 *
 * Example:
 *
 *  'memcached'  => array(
 *      'cluster1'   => array(
 *          array(
 *              'cluster' => 'local',
 *              'name'    => 'local1',
 *              'host'    => 'localhost-one',
 *              'port'    => 11211
 *          ),
 *          array(
 *              'cluster' => 'local',
 *              'name'    => 'local2',
 *              'host'    => 'localhost-two',
 *              'port'    => 11211
 *          )
 *      ),
 *      'cluster2'   => array(
 *          array(
 *              'cluster' => 'local',
 *              'name'    => 'local1',
 *              'host'    => 'localhost-one.other.com',
 *              'port'    => 11211
 *          ),
 *          array(
 *              'cluster' => 'local',
 *              'name'    => 'local2',
 *              'host'    => 'localhost-two.other.com',
 *              'port'    => 11211
 *          )
 *      )
 *  )
 *
 *
 */

return array(
    'asset.path' => '/theme/assets',

    'memcached'  => array(
        'local' => array(
            array(
                'cluster' => 'local',
                'name'    => 'local1',
                'host'    => 'localhost',
                'port'    => 11211
            )
        )
    )
);
