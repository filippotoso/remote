<?php

namespace FilippoToso\Remote;

use League\Flysystem\MountManager;
use Illuminate\Support\Facades\Storage;

class Remote {

    /**
     * Copy a directory recursively and with optional overwriting
     * @method copyDirectory
     * @param  string        $from      The source folder in "disk://folder/subfolder" format
     * @param  string        $to        The destination folder in "disk://folder/subfolder" format
     * @param  boolean       $recursive If enabled the copy is recursive
     * @param  boolean       $overwrite If enabled the destination files are overwritten
     * @return void
     */
    public static function copyDirectory($from, $to, $recursive = TRUE, $overwrite = TRUE) {

        if (strpos($from, '://') < 1) {
            throw new InvalidArgumentException('No prefix detected in path: ' . $from);
        }

        if (strpos($to, '://') < 1) {
            throw new InvalidArgumentException('No prefix detected in path: ' . $to);
        }

        list($from_disk, $from_root) = explode('://', $from, 2);
        list($to_disk, $to_root) = explode('://', $to, 2);

        $manager = new MountManager([
            $from_disk => Storage::disk($from_disk)->getDriver(),
            $to_disk => Storage::disk($to_disk)->getDriver(),
        ]);

        if (!Storage::disk($from_disk)->exists($from)) {
            throw new InvalidArgumentException('Source directory doesn\'t exist: ' . $from);
        }

        $contents = $manager->listContents($from, $recursive);

        foreach ($contents as $content) {

            if ($content['type'] == 'file') {

                $from_path = $from_disk . '://' . $content['path'];
                $to_path = str_finish($to, '/') . substr($content['path'], strlen($from_root) + 1);

                if ($manager->has($to_path)) {
                    if (!$overwrite) {
                        continue;
                    }
                    $manager->delete($to_path);
                }

                $manager->copy($from_path, $to_path);

            }

        }

    }

    /**
     * Move a directory  with optional overwriting
     * @method moveDirectory
     * @param  string        $from      The source folder in "disk://folder/subfolder" format
     * @param  string        $to        The destination folder in "disk://folder/subfolder" format
     * @param  boolean       $overwrite If enabled the destination files are overwritten
     * @return void
     */
    public static function moveDirectory($from, $to, $overwrite = TRUE) {

        if (strpos($from, '://') < 1) {
            throw new InvalidArgumentException('No prefix detected in path: ' . $from);
        }

        if (strpos($to, '://') < 1) {
            throw new InvalidArgumentException('No prefix detected in path: ' . $to);
        }

        Remote::copyDirectory($from, $to, TRUE, $overwrite);

        list($from_disk, $from_root) = explode('://', $from, 2);
        list($to_disk, $to_root) = explode('://', $to, 2);

        $manager = new MountManager([
            $from_disk => Storage::disk($from_disk)->getDriver(),
            $to_disk => Storage::disk($to_disk)->getDriver(),
        ]);

        if (!Storage::disk($from_disk)->exists($from)) {
            throw new InvalidArgumentException('Source directory doesn\'t exist: ' . $from);
        }

        $manager->deleteDir($from);

    }

    /**
     * Copy a file  with optional overwriting
     * @method copy
     * @param  string        $from      The source file in "disk://folder/file.dat" format
     * @param  string        $to        The destination file in "disk://folder/file.dat" format
     * @param  boolean       $overwrite If enabled the destination file is overwritten
     * @return void
     */
    public static function copy($from, $to, $overwrite = TRUE) {

        if (strpos($from, '://') < 1) {
            throw new InvalidArgumentException('No prefix detected in path: ' . $from);
        }

        if (strpos($to, '://') < 1) {
            throw new InvalidArgumentException('No prefix detected in path: ' . $to);
        }

        $from_disk = strstr($from,  ':', TRUE);
        $to_disk = strstr($to,  ':', TRUE);

        $manager = new MountManager([
            $from_disk => Storage::disk($from_disk)->getDriver(),
            $to_disk => Storage::disk($to_disk)->getDriver(),
        ]);

        if (!Storage::disk($from_disk)->exists($from)) {
            throw new InvalidArgumentException('Source file doesn\'t exist: ' . $from);
        }

        if (Storage::disk($to_disk)->has($to)) {
            if (!$overwrite) {
                return FALSE;
            }
            Storage::disk($to_disk)->delete($to);
        }

        return $manager->copy($from, $to);

    }

    /**
     * Move a file  with optional overwriting
     * @method move
     * @param  string        $from      The source file in "disk://folder/file.dat" format
     * @param  string        $to        The destination file in "disk://folder/file.dat" format
     * @param  boolean       $overwrite If enabled the destination file is overwritten
     * @return void
     */
    public static function move($from, $to, $overwrite = TRUE) {

        if (strpos($from, '://') < 1) {
            throw new InvalidArgumentException('No prefix detected in path: ' . $from);
        }

        if (strpos($to, '://') < 1) {
            throw new InvalidArgumentException('No prefix detected in path: ' . $to);
        }

        $from_disk = strstr($from,  ':', TRUE);
        $to_disk = strstr($to,  ':', TRUE);

        $manager = new MountManager([
            $from_disk => Storage::disk($from_disk)->getDriver(),
            $to_disk => Storage::disk($to_disk)->getDriver(),
        ]);

        if (!Storage::disk($from_disk)->exists($from)) {
            throw new InvalidArgumentException('Source file doesn\'t exist: ' . $from);
        }

        if (Storage::disk($to_disk)->has($to)) {
            if (!$overwrite) {
                return FALSE;
            }
            Storage::disk($to_disk)->delete($to);
        }

        return $manager->move($from, $to);

    }

}
