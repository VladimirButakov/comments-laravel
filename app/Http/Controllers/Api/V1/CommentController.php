<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Comments",
 *     description="Comments management API"
 * )
 */
class CommentController extends Controller
{
    public function __construct(
        private CommentService $commentService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/comments",
     *     summary="Create comment",
     *     tags={"Comments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "content", "commentable_type", "commentable_id"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="content", type="string", example="Comment content"),
     *             @OA\Property(property="commentable_type", type="string", example="App\Models\News"),
     *             @OA\Property(property="commentable_id", type="integer", example=1),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
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
            'user_id' => 'required|integer|exists:users,id',
            'content' => 'required|string',
            'commentable_type' => 'required|string|in:App\Models\News,App\Models\VideoPost,App\Models\Comment',
            'commentable_id' => 'required|integer',
            'parent_id' => 'nullable|integer|exists:comments,id',
        ]);

        $comment = $this->commentService->create($validated);
        return response()->json($comment, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/comments/{id}",
     *     summary="Get comment by ID",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment details",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $comment = $this->commentService->getById($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        return response()->json($comment);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/comments/{id}",
     *     summary="Update comment (only by author)",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string", example="Updated comment content")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - not the author"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'sometimes|string',
        ]);

        $userId = $request->input('user_id');
        $comment = $this->commentService->update($id, $validated, $userId);

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found or you are not the author'
            ], 403);
        }

        return response()->json($comment);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/comments/{id}",
     *     summary="Delete comment (soft delete, only by author)",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment deleted"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - not the author"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * )
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $userId = $request->input('user_id');
        $deleted = $this->commentService->delete($id, $userId);

        if (!$deleted) {
            return response()->json([
                'message' => 'Comment not found or you are not the author'
            ], 403);
        }

        return response()->json(['message' => 'Comment deleted']);
    }
}
