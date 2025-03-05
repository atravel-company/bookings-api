<?php

namespace App\Http\Controllers;

use Illuminate\Http\File;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File as FileImg;
use Carbon\Carbon;

class BaseController extends Controller
{
    public function imgUpload($file, $patch = 'app/public/img/users/', $storageType = "storage"){


        $disk = \Storage::disk($storageType);

        $filename  = Carbon::now()->timestamp . '_user.jpeg'; //img name

        $destinationpath = $patch.$filename;

        $disk->put(str_replace("//","/",$destinationpath), FileImg::get($file) ); // upload the img into the database

        /* limpa o cache ao subir imagem */
        $commands = ['php artisan cache:clear', 'php artisan clear-compiled','php artisan optimize'];

        /* limpa o cache */
        foreach($commands as $c){

          $process = new Process($c);
          // Set working directory only in production
          if (app()->environment('prod')) {
            $process->setWorkingDirectory('/var/www/vhosts/atsportugal.com/httpdocs/');
          }          
          $process->run();
        }

        return $disk->url(str_replace("//","/",$destinationpath)); // img url
    }



}
