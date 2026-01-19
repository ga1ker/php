<?php

return [
    'driver'   => 'pgsql',  // PostgreSQL
    'host'     => 'ep-misty-term-aeokam9a-pooler.c-2.us-east-2.aws.neon.tech',
    'port'     => 5432,     // Default Postgres
    'dbname'   => 'neondb',
    'username' => 'neondb_owner',
    'password' => 'npg_Lu3Gle6YRjcQ',
    'sslmode'  => 'require',
    'channel_binding' => 'require',  // PGCHANNELBINDING
    'charset'  => 'UTF8',   // Postgres usa mayúsculas
];
