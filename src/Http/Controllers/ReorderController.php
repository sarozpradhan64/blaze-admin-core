<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReorderController extends Controller
{
    private static array $models = [
        'services'            => \Blaze\AdminCore\Models\Service::class,
        'service-categories'  => \Blaze\AdminCore\Models\ServiceCategory::class,
        'service-features'    => \Blaze\AdminCore\Models\ServiceFeature::class,
        'projects'            => \Blaze\AdminCore\Models\Project::class,
        'project-categories'  => \Blaze\AdminCore\Models\ProjectCategory::class,
        'project-images'      => \Blaze\AdminCore\Models\ProjectImage::class,
        'project-videos'      => \Blaze\AdminCore\Models\ProjectVideo::class,
        'project-statistics'  => \Blaze\AdminCore\Models\ProjectStatistic::class,
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







