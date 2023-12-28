<?php

namespace App\Http\Controllers;

use App\Models\DepartmentDetile;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentDetileController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DepartmentDetile::all();

        return $this->success_resposnes($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validtion = $this->rules($request);

        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }
        $result = DepartmentDetile::create($request->all());
        if (is_null($result)) {
            return $this->fiald_resposnes();
        }

        return  $this->success_resposnes($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DepartmentDetile  $departmentDetile
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resulte = DepartmentDetile::find($id);
        if (is_null($resulte)) {
            return $this->fiald_resposnes();
        }
        return $this->success_resposnes($resulte);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DepartmentDetile  $departmentDetile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $validtion = $this->rules($request);

        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }
        $result = DepartmentDetile::find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes();
        }
        $result->update($request->all());
        return $this->success_resposnes($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DepartmentDetile  $departmentDetile
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $result = DepartmentDetile::find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes();
        }
        $result->delete();
        return $this->success_resposnes($result);
    }

    public function rules(Request $request)
    {

        return Validator::make($request->all(), [
            "department_id" => ["required"],
            "semester" => ["required"],
            "level" => ["required"],
            "start_date" => ["required"],
            "end_date" => ["required"]
        ]);
    }
}
