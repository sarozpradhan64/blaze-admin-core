<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReorderController extends Controller
{
    private static array $models = [
        'services'            => \App\Models\Service::class,
        'service-categories'  => \App\Models\ServiceCategory::class,
        'service-features'    => \App\Models\ServiceFeature::class,
        'projects'            => \App\Models\Project::class,
        'project-categories'  => \App\Models\ProjectCategory::class,
        'project-images'      => \App\Models\ProjectImage::class,
        'project-videos'      => \App\Models\ProjectVideo::class,
        'project-statistics'  => \App\Models\ProjectStatistic::class,
        'team-members'        => \Blaze\AdminCore\Models\TeamMember::class,
        'testimonials'        => \Blaze\AdminCore\Models\Testimonial::class,
        'gallery-albums'      => \Blaze\AdminCore\Models\GalleryAlbum::class,
        'gallery-items'       => \Blaze\AdminCore\Models\GalleryItem::class,
    ];

    public function __invoke(Request $request, string $resource)
    {
        $model = self::$models[$resource] ?? null;

        abort_unless($model, 404);

        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer'])['ids'];

        $model::applyReorder($ids);

        return response()->noContent();
    }
}




