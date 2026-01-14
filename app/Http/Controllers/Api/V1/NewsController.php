<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Tag(
 *     name="News",
 *     description="News management API"
 * )
 */
class NewsController extends Controller
{
    public function __construct(
        private NewsService $newsService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/news",
     *     summary="Get all news",
     *     tags={"News"},
     *     @OA\Response(
     *         response=200,
     *         description="List of news",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/News"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $news = $this->newsService->getAll();
        return response()->json($news);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/news",
     *     summary="Create news",
     *     tags={"News"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description"},
     *             @OA\Property(property="title", type="string", example="News Title"),
     *             @OA\Property(property="description", type="string", example="News Description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="News created",
     *         @OA\JsonContent(ref="#/components/schemas/News")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $news = $this->newsService->create($validated);
        return response()->json($news, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/news/{id}",
     *     summary="Get news by ID with comments",
     *     tags={"News"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="cursor",
     *         in="query",
     *         required=false,
     *         description="Cursor for pagination",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="News details with comments"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="News not found"
     *     )
     * )
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $news = $this->newsService->getById($id);
        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $perPage = $request->get('per_page', 15);
        $cursor = $request->get('cursor');
        
        $comments = $news->comments()
            ->with(['user', 'children'])
            ->whereNull('parent_id')
            ->orderBy('id')
            ->cursorPaginate($perPage, ['*'], 'cursor', $cursor);

        return response()->json([
            'news' => $news,
            'comments' => $comments
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/news/{id}",
     *     summary="Update news",
     *     tags={"News"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Title"),
     *             @OA\Property(property="description", type="string", example="Updated Description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="News updated",
     *         @OA\JsonContent(ref="#/components/schemas/News")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="News not found"
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
        ]);

        $news = $this->newsService->update($id, $validated);
        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }
        return response()->json($news);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/news/{id}",
     *     summary="Delete news",
     *     tags={"News"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="News deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="News not found"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->newsService->delete($id);
        if (!$deleted) {
            return response()->json(['message' => 'News not found'], 404);
        }
        return response()->json(['message' => 'News deleted']);
    }
}




