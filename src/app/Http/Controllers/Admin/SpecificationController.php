<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\SpecificationResource;
use App\Models\Specification;
use App\Http\Controllers\Controller;
use cebe\openapi\exceptions\TypeErrorException;
use cebe\openapi\Reader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SpecificationController extends Controller
{
    /**
     * SpecificationController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(Specification::class, 'specification', [
            'only' => ['index', 'destroy'],
        ]);
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('admin/specifications/index', [
            'specifications' => SpecificationResource::collection(
                Specification::when(request('q'), function (Builder $query, $q) {
                        $query->where('name', 'like', "%{$q}%");
                    })
                    ->latest()
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }

    /**
     * @return \Inertia\Response
     */
    public function showImportForm()
    {
        $this->authorize('create', Specification::class);
        return Inertia::render('admin/specifications/import');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $this->authorize('create', Specification::class);
        $request->validate(['file' => ['required', 'mimetypes:text/yaml,text/plain']]);

        try {
            $openapi = Reader::readFromYaml($request->file('file')->get());
            Specification::create([
                'name' => $openapi->info->title,
                'version' => $openapi->info->version,
                'description' => $openapi->info->description,
                'openapi' => $openapi,
            ]);

            return redirect()
                ->route('admin.specifications.index')
                ->with('success', __('Specification created successfully'));
        } catch (TypeErrorException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param Specification $specification
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Specification $specification)
    {
        $specification->delete();

        return redirect()
            ->back()
            ->with('success', __('Specification deleted successfully'));
    }
}
