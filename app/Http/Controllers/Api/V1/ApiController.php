<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Comments API",
 *     description="API для управления комментариями к новостям и видео постам"
 * )
 * @OA\Server(
 *     url="http://localhost:8080",
 *     description="Local server"
 * )
 * 
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="News",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="News title"),
 *     @OA\Property(property="description", type="string", example="News description"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="VideoPost",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Video title"),
 *     @OA\Property(property="description", type="string", example="Video description"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Comment",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="commentable_type", type="string", example="App\Models\News"),
 *     @OA\Property(property="commentable_id", type="integer", example=1),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
 *     @OA\Property(property="content", type="string", example="Comment content"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true)
 * )
 */
class ApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Comments API',
            'version' => '1.0.0',
            'endpoints' => [
                'docs' => '/api/documentation',
                'users' => '/api/v1/users',
                'news' => '/api/v1/news',
                'video-posts' => '/api/v1/video-posts',
                'comments' => '/api/v1/comments',
            ]
        ]);
    }
}
