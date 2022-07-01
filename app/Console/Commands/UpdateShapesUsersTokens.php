<?php

namespace App\Console\Commands;

use App\BusinessLogicLayer\managers\SHAPES\ShapesIntegrationManager;
use Illuminate\Console\Command;

class UpdateShapesUsersTokens extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shapes:update-user-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the auth token for all shapes users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ShapesIntegrationManager $shapesIntegrationManager) {
        $shapesIntegrationManager->updateSHAPESAuthTokenForUsers();
        return 0;
    }
}
