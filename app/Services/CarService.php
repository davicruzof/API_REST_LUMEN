<?php

    namespace App\Services;

    use App\Repositores\CarRepositoryInterface;
    use Illuminate\Database\QueryException;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Symfony\Component\HttpFoundation\Response;
    use App\Models\ValidationCar;

    class CarService
    {
        private $carRepositoy;

        public function __construct(CarRepositoryInterface $carRepositoy)
        {
            $this->carRepositoy = $carRepositoy;
        }

        public function getAll()
        {
            try {
                $cars = $this->carRepositoy->getAll();
                if (count($cars) > 0) {
                    return response()->json($cars, Response::HTTP_OK);
                } else {
                    return response()->json([], Response::HTTP_OK);
                }
            }catch (QueryException $err){
                return response()->json([
                    'error' =>  'Erro de conexão com o banco de dados'
                ], Response::HTTP_OK);
            }
        }

        public function get($id)
        {
            try{
                $car = $this->carRepositoy->get($id);
                if(!is_null($car)){
                    return response()->json($car, Response::HTTP_OK);
                }else{
                    return response()->json("Nada foi encontrado",Response::HTTP_OK);
                }
            }catch (QueryException $err){
                return response()->json([
                    'error' =>  'Erro de conexão com o banco de dados'
                ], Response::HTTP_OK);
            }
        }

        public function store(Request $request)
        {

            $validator = Validator::make(
                $request->all(),
                ValidationCar::RULES_CARS
            );

            if($validator->fails()){
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            try {
                $car = $this->carRepositoy->store($request);
                return response()->json($car, Response::HTTP_CREATED);
            }catch (QueryException $err){
                return response()->json([
                    'error' =>  'Erro de conexão com o banco de dados'
                ], Response::HTTP_OK);
            }
        }

        public function update($id, Request $request)
        {
            $validator = Validator::make(
                $request->all(),
                ValidationCar::RULES_CARS
            );

            if($validator->fails()){
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }
            try {
                $car = $this->carRepositoy->update($id,$request);
                return response()->json($car, Response::HTTP_OK);
            }catch (QueryException $err){
                return response()->json([
                    'error' =>  'Erro de conexão com o banco de dados'
                ], Response::HTTP_OK);
            }
        }

        public function destroy($id)
        {
            try {
                $car = $this->carRepositoy->destroy($id);
                return response()->json(null, Response::HTTP_OK);
            }catch (QueryException $err){
                return response()->json([
                    'error' =>  'Erro de conexão com o banco de dados'
                ], Response::HTTP_OK);
            }
        }
    }
