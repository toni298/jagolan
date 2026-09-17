<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function index(): View
    {
        $stats = $this->userRepository->getDashboardStats();

        return view('admin.dashboard', compact('stats'));
    }
}
