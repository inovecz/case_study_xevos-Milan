<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::all();
        $employeesArray = [];

        foreach ($employees as $employee) {
            $employeesArray[] = new EmployeeResource($employee);
        }

        return response()->json(
            $employeesArray
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeJson(Request $request)
    {
        $employeesJson = Http::get('https://xevos.store/domaci-ukol/Jmena.json')->json();

        DB::table('employees')->delete();

        foreach ($employeesJson as $employee) {
            Employee::create($employee);
        }

        $employees = Employee::all();
        $employeesArray = [];

        foreach ($employees as $employee) {
            $employeesArray[] = new EmployeeResource($employee);
        }

        return response()->json(
            $employeesArray
        );
    }

    public function storeSalaries(Request $request)
    {
        $employees = Employee::all();
        $employees1 = Http::get('https://xevos.store/domaci-ukol/Zamestnavatel1.json')->json();
        $employees2 = Http::get('https://xevos.store/domaci-ukol/Zamestnavatel2.json')->json();
        $employees3 = Http::get('https://xevos.store/domaci-ukol/Zamestnavatel3.json')->json();

        foreach ($employees as $employee) {
            $key = $employee->getName() . " " . $employee->getSurname();
            $employeesArray[$key] = $employee->getSalary();

            foreach ($employees1 as $employee1) {
                $key1 = $employee1['jmeno'] . " " . $employee1['prijmeni'];
                $employeesArray1[$key1] = $employee1['plat'];
                if ($key == $key1 && $employeesArray1[$key1]>$employeesArray[$key]) {
                    $employee->plat = $employeesArray1[$key1];
                    $employee->save();
                }
            };

            foreach ($employees2 as $employee2) {
                $key2 = $employee2['jmeno'] . " " . $employee2['prijmeni'];
                $employeesArray2[$key2] = $employee2['plat'];
                if ($key == $key2 && $employeesArray2[$key2]>$employeesArray[$key] && $employeesArray2[$key2]>$employeesArray1[$key1]) {
                    $employee->plat = $employeesArray2[$key2];
                    $employee->save();
                }
            };

            foreach ($employees3 as $employee3) {
                $key3 = $employee3['jmeno'] . " " . $employee3['prijmeni'];
                $employeesArray3[$key3] = $employee3['plat'];
                if ($key == $key3 && $employeesArray3[$key3]>$employeesArray[$key] && $employeesArray3[$key3]>$employeesArray1[$key1] 
                && $employeesArray3[$key3]>$employeesArray2[$key2]) {
                    $employee->plat = $employeesArray3[$key3];
                    $employee->save();
                }
            };
        };

        foreach ($employees as $employee) {
            $returnEmployeesArray[] = new EmployeeResource($employee);
        }

        return response()->json(
            $returnEmployeesArray
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $array = [
            'id' => $request->id,
            'jmeno' => $request->name,
            'prijmeni' => $request->surname,
            'date' => $request->date,
        ];

        $employee = Employee::find($id);
        $employee->update($array);

        $employees = Employee::all();
        $employeesArray = [];

        foreach ($employees as $employee) {
            $employeesArray[] = new EmployeeResource($employee);
        }

        return response()->json(
            $employeesArray
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* $id = $request->input('id'); */
        Employee::destroy($id);

        $employees = Employee::all();
        $employeesArray = [];

        foreach ($employees as $employee) {
            $employeesArray[] = new EmployeeResource($employee);
        }

        return response()->json(
            $employeesArray
        );
    }
}
