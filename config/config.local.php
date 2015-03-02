<?php
return array(
    'asset.path' => '/theme/assets',

    'memcached'  => array(
        'local'   => array(
            array(
                'cluster' => 'local',
                'name'    => 'local1',
                'host'    => 'localhost',
                'port'    => 11211
            )
        ),
        'staging' => array(
            array(
                'cluster' => 'staging',
                'name'    => 'crated-staging',
                'host'    => 'cp-staging.korsk3.cfg.use1.cache.amazonaws.com',
                'port'    => 11211
            )
        )
    )
);
