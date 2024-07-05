<?php

namespace App\Http\Controllers;

use App\Actions\FileUploadAction;
use App\Http\ApiResponse;
use App\Http\Requests\UploadFileRequest;
use App\Http\Resources\FileResource;
use App\Repositories\FileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

/**
 * Class FileController
 * Handles file related actions.
 *
 */
class FileController extends Controller
{
    /**
     * @var FileUploadAction
     */
    protected $fileUploadAction;

    /**
     * @var FileRepository
     */
    protected $fileRepository;

    /**
     * FileController constructor.
     *
     * @param FileUploadAction $fileUploadAction
     */
    public function __construct(FileUploadAction $fileUploadAction, FileRepository $fileRepository)
    {
        $this->fileUploadAction = $fileUploadAction;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/file/upload",
     *     tags={"File"},
     *     summary="Upload a file",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="file", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File uploaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/FileResource")
     *         )
     *     )
     * )
     *
     * Handle the file upload.
     *
     * @param UploadFileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(UploadFileRequest $request)
    {
        $file = $this->fileUploadAction->execute($request->validated());

        return ApiResponse::send(true, Response::HTTP_OK, 'File uploaded successfully', FileResource::make($file));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/file/{uuid}",
     *     tags={"File"},
     *     summary="Download a file",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File downloaded successfully",
     *         @OA\MediaType(
     *             mediaType="application/octet-stream"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="File not found"
     *     )
     * )
     *
     * Handle the file download.
     *
     * @param string $uuid
     * @return \Illuminate\Http\Response
     */
    public function download($uuid)
    {
        $file = $this->fileRepository->findByUuid($uuid);

        if (!$file) {
            return ApiResponse::send(false, Response::HTTP_NOT_FOUND, 'File not found');
        }
        
        return  Storage::disk('public')->download($file->path, $file->name);
    }
}
