<?php

namespace App\ViewModels;

use Illuminate\Support\Collection;

class PlatformStatistics {

    public $generalPlatformStatistics;
    public $gameFlavorsPerLanguageStatistics;
    public $gameFlavorsPerUserStatistics;

    /**
     * @param Collection $generalPlatformStatistics
     * @param Collection $gameFlavorsPerLanguageStatistics
     * @param Collection $gameFlavorsPerUserStatistics
     */
    public function __construct(Collection $generalPlatformStatistics,
                                Collection $gameFlavorsPerLanguageStatistics,
                                Collection $gameFlavorsPerUserStatistics) {
        $this->generalPlatformStatistics = $generalPlatformStatistics;
        $this->gameFlavorsPerLanguageStatistics = $gameFlavorsPerLanguageStatistics;
        $this->gameFlavorsPerUserStatistics = $gameFlavorsPerUserStatistics;
    }


}
