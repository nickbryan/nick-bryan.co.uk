<?php

namespace App;

use League\Flysystem\Filesystem;

class Treehouse
{
    const FILE_PATH = 'cache/treehouse.json';

    /**
     * @var Filesystem
     */
    private $fileSystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->fileSystem = $filesystem;
    }

    /**
     * @param bool $array
     * @return mixed
     */
    public function getData($array = false)
    {
        if (!$this->fileSystem->has(self::FILE_PATH)) {
            $this->fileSystem->write(self::FILE_PATH, '');
        }

        return json_decode($this->fileSystem->read(self::FILE_PATH), $array);
    }

    /**
     * @param array $data
     */
    public function saveData(array $data)
    {
        $data['badges'] = array_reverse($data['badges']);
        arsort($data['points']);

        $this->fileSystem->update(self::FILE_PATH, json_encode($data));
    }
}
