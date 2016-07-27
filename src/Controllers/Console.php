<?php
/**
 * Created by PhpStorm.
 * User: vagrant
 * Date: 4/29/16
 * Time: 1:20 AM
 */
namespace M2Console\Controllers;

use Twig_Environment;
use Twig_Loader_Filesystem;

class Console extends AbstractController
{
    const PROJECT_DIR_FILE = CONSOLE_BP . '/data/projectdir.json';
    const SNIPPETS_FILE = CONSOLE_BP . '/data/snippets.md';

    protected function execute()
    {
        if (!file_exists(self::PROJECT_DIR_FILE)) {
            return $this->ci->view->render($this->response, 'no_projectdir.twig');
        }

        $projectsDir = json_decode(file_get_contents(self::PROJECT_DIR_FILE), true);

        $baseDir = empty($_COOKIE['BASE_DIR']) ? false : $_COOKIE['BASE_DIR'];

        $snippets = $this->loadSnippetsFromFile();

        return $this->ci->view->render($this->response, 'console.twig', [
            'projectsDir' => $projectsDir,
            'baseDir' => $baseDir,
            'snippets' => json_encode($snippets),
        ]);
    }

    protected function loadSnippetsFromFile()
    {
        $snippets = [];
        if (file_exists(self::SNIPPETS_FILE)) {
            $fileContent = file_get_contents(self::SNIPPETS_FILE);
            $matches = [];
            if ($count = preg_match_all('/\#(.*)\n```((?:.*\n)*?)```/', $fileContent, $matches)) {
                for ($i = 0; $i < $count; $i++) {
                    $snippets[] = ['title' => $matches[1][$i], 'code' => $matches[2][$i]];
                }
            }
        }

        return $snippets;
    }
}