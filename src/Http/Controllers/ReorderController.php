<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\Models\GalleryAlbum;
use Blaze\AdminCore\Models\GalleryItem;
use Blaze\AdminCore\Models\Project;
use Blaze\AdminCore\Models\ProjectCategory;
use Blaze\AdminCore\Models\ProjectImage;
use Blaze\AdminCore\Models\ProjectStatistic;
use Blaze\AdminCore\Models\ProjectVideo;
use Blaze\AdminCore\Models\Service;
use Blaze\AdminCore\Models\ServiceCategory;
use Blaze\AdminCore\Models\ServiceFeature;
use Blaze\AdminCore\Models\TeamMember;
use Blaze\AdminCore\Models\Testimonial;
use Illuminate\Http\Request;

class ReorderController extends Controller
{
    private static array $models = [
        'services' => Service::class,
        'service-categories' => ServiceCategory::class,
        'service-features' => ServiceFeature::class,
        'projects' => Project::class,
        'project-categories' => ProjectCategory::class,
        'project-images' => ProjectImage::class,
        'project-videos' => ProjectVideo::class,
        'project-statistics' => ProjectStatistic::class,
        'team-members' => TeamMember::class,
        'testimonials' => Testimonial::class,
        'gallery-albums' => GalleryAlbum::class,
        'gallery-items' => GalleryItem::class,
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
