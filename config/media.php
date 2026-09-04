<?php

return [

    /*
    |----------------------------------------------------------------------
    | Chemin de ffmpeg
    |----------------------------------------------------------------------
    |
    | ffmpeg sert à alléger les vidéos envoyées depuis l'administration.
    | Laissez « ffmpeg » si l'outil est installé normalement sur le serveur.
    | Sinon, indiquez son chemin complet dans .env :
    |
    |     FFMPEG_PATH=/usr/local/bin/ffmpeg
    |
    | S'il est absent, les vidéos sont enregistrées telles quelles : l'envoi
    | fonctionne toujours, mais le fichier garde son poids d'origine.
    |
    */

    'ffmpeg' => env('FFMPEG_PATH', 'ffmpeg'),

    'ffprobe' => env('FFPROBE_PATH', 'ffprobe'),

];
