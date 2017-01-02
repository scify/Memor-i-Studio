<?php

namespace App\StorageLayer;
use App\Models\Language;

class LanguageStorage {

    public function getAllLanguages() {
        return Language::all();
    }
}