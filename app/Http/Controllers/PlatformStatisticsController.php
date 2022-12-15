<?php

namespace App\Http\Controllers;

use App\StorageLayer\PlatformStatisticsRepository;
use App\ViewModels\PlatformStatistics;
use Illuminate\Contracts\View\View;

class PlatformStatisticsController extends Controller {
    protected $platformStatisticsRepository;

    /**
     * @param PlatformStatisticsRepository $platformStatisticsRepository
     */
    public function __construct(PlatformStatisticsRepository $platformStatisticsRepository) {
        $this->platformStatisticsRepository = $platformStatisticsRepository;
    }

    public function show_platform_statistics(): View {
        $viewModel = new PlatformStatistics(
            $this->platformStatisticsRepository->getPlatformStatistics(),
            $this->platformStatisticsRepository->getGameFlavorsPerLanguageStatistics(),
            $this->platformStatisticsRepository->getNumOfGameFlavorsPerUser()
        );
        return view('admin.platform-statistics', compact('viewModel'));
    }
}
