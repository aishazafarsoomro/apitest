<?php
namespace App\Traits;
use Illuminate\Support\Facades\Storage;

Trait Common
{
    protected function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }
    public function read_csv($filePath)
    {
        $file = fopen($filePath, "r");
        $array = array();
        $row = 1;
        $headings = array();
        while ( ($data = fgetcsv($file, 1000, ",")) !==FALSE )
        {
            $d = count($data);
            if($row == 1){
                $headings = $data;
                $row++;
                continue;
            }
            $new_row = array();
            for($i=0;$i<$d;$i++)
            {
                $new_row[$headings[$i]] = $data[$i];
            }
            array_push($array, $new_row);
        }
        fclose($file);
        return $array;
    }
    public function read_csvline($file,$record_no)
    {
        $file = fopen($file, "r");
        $row = 1;
        $record = array();
        $headings = array();
        while ( ($data = fgetcsv($file, 1000, ",")) !==FALSE )
        {
            if($row==1)
            {
                $headings = $data;
            }
            else if($row==$record_no)
            {
                $d = count($data);
                for($i=0;$i<$d;$i++)
                {
                    $record[$headings[$i]] = $data[$i];
                }
                break;
            }
            $row++;
        }
        fclose($file);
        return $record;
    }
    protected function append_row($file,$line)
    {
        $handle = fopen($file,'a');
        fputcsv($handle,$line);
        fclose($handle);
    }
    protected function delete_record($file,$record_no)
    {
        $data_file = fopen($file,'r');
        $row = 0;
        $records = array();
        while ( ($data = fgetcsv($data_file, 1000, ",")) !==FALSE )
        {
            $row++;
            if($row == $record_no){
                continue;
            }
            array_push($records, $data);
        }
        $temp_file_name = Storage::disk("public")->path("temp_csv.csv");
        $temp_file = fopen($temp_file_name,"w");
        foreach ($records as $record) {
            fputcsv($temp_file, $record);
        }
        fclose($data_file);
        fclose($temp_file);
        rename($temp_file_name,$file);
    }
    protected function edit_row($file,$row_no,$record)
    {
        $data_file = fopen($file,'r');
        $row = 0;
        $records = array();
        while ( ($data = fgetcsv($data_file, 1000, ",")) !==FALSE )
        {
            $row++;
            if($row == $row_no){
                foreach($record as $key=>$val)
                {
                    $data[$key] = $val;
                }
            }
            array_push($records, $data);
        }
        $temp_file_name = Storage::disk("public")->path("temp_csv.csv");
        $temp_file = fopen($temp_file_name,"w");
        foreach ($records as $record) {
            fputcsv($temp_file, $record);
        }
        //fputcsv($temp_file,$records);
        fclose($data_file);
        fclose($temp_file);
        rename($temp_file_name,$file);
    }
}
