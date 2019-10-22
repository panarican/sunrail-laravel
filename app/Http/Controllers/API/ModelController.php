<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class ModelController extends Controller
{
    /**
     * @var int $cacheTime
     */
    protected $cacheTime;

    /**
     * @var Model $model
     */
    protected $model;

    /**
     * ModelController constructor.
     * @param Model    $model
     * @param int|null $cacheTime
     */
    public function __construct(Model $model, ?int $cacheTime = 302400)
    {
        $this->model = $model;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        try {
            // Get query.
            $qb = $this->model->query();

            // Get request values.
            $filterColumn = strtolower($request->get('filter_column', ''));
            $filterColumn = in_array($filterColumn, $this->model->getFillable()) ? $filterColumn : null;
            $filterValue = $request->get('filter_value');
            $sortColumn = strtolower($request->get('sort_column', ''));
            $sortColumn = in_array($sortColumn, $this->model->getFillable()) ? $sortColumn : null;
            $sortValue = strtolower($request->get('sort_value', 'asc'));
            $sortValue = in_array($sortValue, ['asc', 'desc']) ? $sortValue : 'asc';
            $perPage = (int) $request->get('per_page', 15);
            $all = (bool) $request->get('all');

            // Filter column.
            if ($filterColumn) {
                $qb->where($filterColumn, 'LIKE', "%{$filterValue}%");
            }

            // Sort column.
            if ($sortColumn) {
                $qb->orderBy($sortColumn, $sortValue);
            }

            // Get all data or paginate it.
            if ($all) {
                $data = $qb->get();
            } else {
                $data = $qb->paginate($perPage)->appends($request->query());
            }

            return Response::create($data);
        } catch (\Throwable $exception) {
            return Response::create(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            return Response::create($this->model->query()->findOrFail($id));
        } catch (\Throwable $exception) {
            return Response::create(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
