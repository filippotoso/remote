<?php

namespace FilippoToso\Remote;

use League\Flysystem\MountManager;
use Illuminate\Support\Facades\Storage;

class Remote {

    static public function copy($from, $to) {

        $from_disk = strstr($from,  ':', TRUE);
        $to_disk = strstr($to,  ':', TRUE);

        $manager = new MountManager([
            $from_disk => Storage::disk($from_disk)->getDriver(),
            $to_disk => Storage::disk($to_disk)->getDriver(),
        ]);

        return $manager->copy($from, $to);

    }

    static public function move($from, $to) {

        $from_disk = strstr($from,  ':', TRUE);
        $to_disk = strstr($to,  ':', TRUE);

        $manager = new MountManager([
            $from_disk => Storage::disk($from_disk)->getDriver(),
            $to_disk => Storage::disk($to_disk)->getDriver(),
        ]);

        return $manager->move($from, $to);

    }

}
