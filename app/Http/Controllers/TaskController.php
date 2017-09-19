<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepo;
use App\Traits\FilterConstraint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class TaskController extends Controller
{
    use FilterConstraint;

    protected $repo;

    public function __construct(TaskRepo $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $options = $this->parseFilterOptions($request->all());

        $tasks = $this->repo->all(
            $options['where'],
            $options['limit'],
            $options['page']
        );

        return $tasks;
    }

    public function store(Request $request)
    {
        return JsonResponse::create(
            $this->repo->create($request->all())->toArray(),
            201
        );
    }

    public function show($id)
    {
        return JsonResponse::create(
            $this->repo->show($id)->toArray(),
            200
        );
    }

    public function destroy($id)
    {
        return JsonResponse::create(
            $this->repo->delete($id),
            204
        );
    }

    public function update(Request $request, $id)
    {
        $task = $this->repo->findOrFail($id);
        $task->fill($request->all())->save();
        return JsonResponse::create(
            $task,
            200
        );
    }
}
