<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class FirebaseService
{
    protected Database $database;

    public function __construct()
    {
        $serviceAccount = storage_path('app/firebase/firebase-credentials.json');

        $this->database = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://management-6d07b-default-rtdb.firebaseio.com/') // Replace with your database URL
            ->createDatabase();
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }
}
