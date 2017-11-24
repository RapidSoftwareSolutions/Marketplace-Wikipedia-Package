<?php
$routes = [
    'metadata',
    'getPageByTitles', // 1
    'getPage', // 1
    'getPageByRevisionId', //1
    'getFileUrl', //1
    'getFilesInfo', //1
    'getWatchList', //1
    'getPagesCategories',
    'createPage', // 1
    'updatePage', //1
    'deletePage', //1
    'comparePages', //1
    'updateMessageList', //1
    'sendEmailToUser', //1
    'getRevisionByPageId', //1
    'getRevisionByPageTitle', //1
    'uploadFile',
    'getPageContent', //1
    'getAllImageFromPage',
    'getFileUsage',
    'getCurrentUser'
];
foreach($routes as $file) {
    require __DIR__ . '/../src/routes/'.$file.'.php';
}

