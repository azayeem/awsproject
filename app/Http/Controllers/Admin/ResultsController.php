<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Admin\Result\IndexResult;
use App\Http\Requests\Admin\Result\StoreResult;
use App\Http\Requests\Admin\Result\UpdateResult;
use App\Http\Requests\Admin\Result\DestroyResult;
use Brackets\AdminListing\Facades\AdminListing;
use App\Models\Result;

class ResultsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  IndexResult $request
     * @return Response|array
     */
    public function index(IndexResult $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Result::class)
            ->modifyQuery(function($query) use ($request){
                $query->join('users', 'users.id', '=', 'results.user_id');
                $query->select('results.*', 'users.name', 'users.email');
            })
            ->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'path', 'users.name', 'users.email','results_filled_date'],

            // set columns to searchIn
            ['id', 'path', 'users.name', 'users.email','results_filled_date']
        );

        if ($request->ajax()) {
            return ['data' => $data];
        }


        return view('admin.result.index', ['data' => $data]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('admin.result.create');

        return view('admin.result.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreResult $request
     * @return Response|array
     */
    public function store(StoreResult $request)
    {
        // Sanitize input
        $sanitized = $request->validated();

        // Store the Result
        $result = Result::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/results'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/results');
    }

    /**
     * Display the specified resource.
     *
     * @param  Result $result
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Result $result)
    {
        $this->authorize('admin.result.show', $result);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Result $result
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Result $result)
    {
        $this->authorize('admin.result.edit', $result);

        return view('admin.result.edit', [
            'result' => $result,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateResult $request
     * @param  Result $result
     * @return Response|array
     */
    public function update(UpdateResult $request, Result $result)
    {
        // Sanitize input
        $sanitized = $request->validated();

        // Update changed values Result
        $result->update($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/results'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/results');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DestroyResult $request
     * @param  Result $result
     * @return Response|bool
     * @throws \Exception
     */
    public function destroy(DestroyResult $request, Result $result)
    {
        $result->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    }
