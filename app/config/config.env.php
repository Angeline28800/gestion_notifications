<?php
//injecte les infos de connexion selon environnement
return [
    'MySQL.host'     => '',// Adresse de la base, généralement localhost
    'MySQL.database'     => 'gestion_notifications',// Nom de la base de données
    'MySQL.login'     => 'root',// Nom de l'utilisateur MySQL
    'MySQL.password'     => '',// Mot de passe de l'utilisateur

    'DB' => \DI\create(MySQL::class),
];
