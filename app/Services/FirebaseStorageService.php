<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage;
use Illuminate\Support\Facades\Storage as LaravelStorage;

class FirebaseStorageService
{
    protected $storage;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))
            ->withDefaultStorageBucket('cleanconnectmanagement.firebasestorage.app'); // Correct bucket name

        $this->storage = $factory->createStorage();
    }

    public function upload($file, $folder)
    {
        $bucket = $this->storage->getBucket();

        $fileName = $folder . '/' . uniqid() . '_' . $file->getClientOriginalName();

        $bucket->upload(
            file_get_contents($file->getRealPath()),
            ['name' => $fileName]
        );

        return 'https://firebasestorage.googleapis.com/v0/b/' . $bucket->name() . '/o/' . urlencode($fileName) . '?alt=media';
    }
}
