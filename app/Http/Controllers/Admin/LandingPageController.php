<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateLandingPageRequest;
use App\Repositories\BlogRepository;
use App\Repositories\TestimonialRepository;
use App\Services\LandingPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function __construct(
        protected BlogRepository $blogRepository,
        protected TestimonialRepository $testimonialRepository,
        protected LandingPageService $landingPageService
    ) {}

    public function index(): View
    {
        // Auto-create the single landing page record if it does not exist yet.
        $blog = $this->blogRepository->getSingleton();
        $content = $blog->content ?? $this->blogRepository->defaultContent();
        $testimonials = $this->testimonialRepository->all();

        return view('admin.landing-page.index', compact('blog', 'content', 'testimonials'));
    }

    /**
     * Single submit: persist the entire landing page (content + testimonials) at once.
     */
    public function update(UpdateLandingPageRequest $request): RedirectResponse
    {
        $blog = $this->blogRepository->getSingleton();
        $this->landingPageService->save($blog, $request->validated());

        return redirect()
            ->route('admin.landing-page.index')
            ->with('success', 'Perubahan landing page berhasil disimpan.');
    }
}
