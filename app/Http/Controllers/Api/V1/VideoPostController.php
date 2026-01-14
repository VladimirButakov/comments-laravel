<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\VideoPostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Video Posts",
 *     description="Video posts management API"
 * )
 */
class VideoPostController extends Controller
{
    public function __construct(
        private VideoPostService $videoPostService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/video-posts",
     *     summary="Get all video posts",
     *     tags={"Video Posts"},
     *     @OA\Response(
     *         response=200,
     *         description="List of video posts",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/VideoPost"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $posts = $this->videoPostService->getAll();
        return response()->json($posts);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/video-posts",
     *     summary="Create video post",
     *     tags={"Video Posts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description"},
     *             @OA\Property(property="title", type="string", example="Video Post Title"),
     *             @OA\Property(property="description", type="string", example="Video Post Description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Video post created",
     *         @OA\JsonContent(ref="#/components/schemas/VideoPost")
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

        $post = $this->videoPostService->create($validated);
        return response()->json($post, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/video-posts/{id}",
     *     summary="Get video post by ID with comments",
     *     tags={"Video Posts"},
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
     *         description="Video post details with comments"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Video post not found"
     *     )
     * )
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $post = $this->videoPostService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'Video post not found'], 404);
        }

        $perPage = $request->get('per_page', 15);
        $cursor = $request->get('cursor');
        
        $comments = $post->comments()
            ->with(['user', 'children'])
            ->whereNull('parent_id')
            ->orderBy('id')
            ->cursorPaginate($perPage, ['*'], 'cursor', $cursor);

        return response()->json([
            'video_post' => $post,
            'comments' => $comments
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/video-posts/{id}",
     *     summary="Update video post",
     *     tags={"Video Posts"},
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
     *         description="Video post updated",
     *         @OA\JsonContent(ref="#/components/schemas/VideoPost")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Video post not found"
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
        ]);

        $post = $this->videoPostService->update($id, $validated);
        if (!$post) {
            return response()->json(['message' => 'Video post not found'], 404);
        }
        return response()->json($post);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/video-posts/{id}",
     *     summary="Delete video post",
     *     tags={"Video Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Video post deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Video post not found"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->videoPostService->delete($id);
        if (!$deleted) {
            return response()->json(['message' => 'Video post not found'], 404);
        }
        return response()->json(['message' => 'Video post deleted']);
    }
}




