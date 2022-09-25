<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Classes\Policy;
use App\Classes\County;
use App\Classes\Line;
use App\Classes\Tiv;
use Storage;

class Index extends Controller
{
    public function test(Request $request){

        Log::info('[Index][test]');
        Log::info("[Index][test] Método Recibido: ". $request->getMethod());
  
        if($request->isMethod('GET')) {

            Log::info("[Index][test] storage_path: ". storage_path());

            $insurance_csv_title = collect();
            $insurance_csv_values = collect();

            if (($open = fopen(storage_path() . "/FL_insurance_sample.csv", "r")) !== FALSE) {

                $row = 0;

                while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                    
                    if($row==0){
                        foreach($data as $item)
                            $insurance_csv_title->push($item);

                    } else {

                        $insurance_csv_values->push([$insurance_csv_title[2] => $data[2], $insurance_csv_title[8] => $data[8], $insurance_csv_title[15] => $data[15] ]);
                    }

                    $row++;
                }

                fclose($open);
            }

            $insurance_csv_values_grouped_county = $insurance_csv_values->groupBy('county');

            $insurance_csv_values_grouped_line = $insurance_csv_values->groupBy('line');

            $policy = new Policy();
            $policy->county = new County();
            $policy->line = new Line();

            foreach($insurance_csv_values_grouped_county->keys() as $item){
                $policy->county->$item = new Tiv();
                $policy->county->$item->tiv_2012 = number_format((float)$insurance_csv_values_grouped_county[$item]->sum('tiv_2012'), 2, '.', '');
            }

            foreach($insurance_csv_values_grouped_line->keys() as $item){
                $policy->line->$item = new Tiv();
                $policy->line->$item->tiv_2012 = number_format((float)$insurance_csv_values_grouped_line[$item]->sum('tiv_2012'), 2, '.', '');
            }

            $output = json_encode($policy);

            Storage::disk('local')->put('output.json', $output);

            return $output;

        } else {
          abort(404);
        }
  
    }

}