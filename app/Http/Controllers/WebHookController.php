<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class WebHookController extends Controller
{
    protected $cmds_commit = [
        'pwd',
        'ls',
        'git checkout -f',
        'git pull origin master',
        'php artisan clear-compiled',
        'php artisan optimize',
        'php artisan storage:link',
        'rm -rf bootstrap/cache/config.php',
        'php artisan vendor:publish'
    ];

    protected $id = 'UF18K935UrZ8CENT3WZlztJRl+D60BNjRkg+wXcKdxM=';

    public function commit(Request $request, $actionType, $secret){

        if($this->id == $secret) {
            $content = json_decode($request->getContent());
            $type = $content->push->changes[0]->new->type;
            $name = $content->push->changes[0]->new->name;

            $output = $this->executeCommands($this->cmds_commit, $actionType);
            $retorno = [ 'msg' => "Deploy da versão ".$name.' efetuado com sucesso', 'data' => $output ];
            return response()->json($retorno);
        }
    }

    protected function executeLocal($cmd)
    {
        try {
            $process = new Process($cmd);
            $process->setWorkingDirectory('/Users/henriquefelix/Desktop/atsportugal_hook');
            $process->run();

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    protected function executeDev($cmd)
    {
        try {
            $process = new Process($cmd);
            $process->setWorkingDirectory('/home/qy2s87b2/public_html/dev.atsportugal.com');
            return $process->run();

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    protected function executeProd($cmd)
    {
        try {
            $process = new Process($cmd);
            $process->setWorkingDirectory('public_html/atsportugal_new');
            return $process->run();

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    protected function executeCommands(Array $cmds, $action) {

	    $output = [];
        foreach ($cmds as $cmd) {
            $out['cmd'] = $cmd;
            
            switch($action){
                case 'local':
                    $out['saida'] = $this->executeLocal($cmd);
                    break;
                case 'development':
                    $out['saida'] = $this->executeDev($cmd);
                    break;
                case 'production':
                    $out['saida'] = $this->executeProd($cmd);
                    break;
            }   
            
	         array_push($output, $out);
        }
	    return $output;
    }
}
