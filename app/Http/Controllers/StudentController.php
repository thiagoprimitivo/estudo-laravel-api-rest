<?php

/**
 * @SWG\Swagger(
 *     basePath="/api",
 *     schemes={"http"},
 *     host="http://escola.test",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="API Treinaweb",
 *         description="Projeto de API do curso de Laravel do treinaweb",
 *         @SWG\Contact(
 *             email="darius@matulionis.lt"
 *         ),
 *     )
 * )
 */

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StudentRequest;

use Illuminate\Http\Request;
use SimpleXMLElement;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Student as StudentResource;
use App\Http\Resources\Students as StudentCollection;

class StudentController extends Controller
{
    /**
     * @SWG\Get(
     *      path="/students",
     *      operationId="getStudentsList",
     *      tags={"Students"},
     *      summary="Get list of students",
     *      description="Returns list of students",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @SWG\Response(response=400, description="Bad request"),
     *       security={
     *           {"api_key_security_example": {}}
     *       }
     *     )
     *
     * Returns list of students
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->query('includes') === 'classroom') {
            $students = Student::with('classroom')->paginate(1);
        } else {
            $students = Student::paginate(1);
        }

        return (new StudentCollection($students))
                        ->response()
                        ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request)
    {
        return Student::create($request->all());
    }

    /**
     * @SWG\Get(
     *      path="/students/{id}",
     *      operationId="getStudentById",
     *      tags={"Students"},
     *      summary="Get student information",
     *      description="Returns student data",
     *      @SWG\Parameter(
     *          name="id",
     *          description="Student id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=404, description="Resource Not Found"),
     *      security={
     *         {
     *             "oauth2_security_example": {"write:students", "read:students"}
     *         }
     *     },
     * )
     *
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        if(request()->header('Accept') === 'application/xml') {
            return $this->getStudentXmlResponse($student);
        }

        if(request()->wantsJson()) {
            return new StudentResource($student);
        }

        return response(['message' => 'não conheço esse formato']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentRequest $request, Student $student)
    {
        $student->update($request->all());

        return [];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return [];
    }

    public function getStudentXmlResponse($student)
    {
        $student = $student->toArray();

        $xml = new SimpleXMLElement('<student/>');

        array_walk_recursive($student, function ($value, $key) use ($xml){
           $xml->addChild($key, $value);
        });

        return response($xml->asXML(), 200)
                    ->header('Content-Type', 'application/xml');
    }
}
